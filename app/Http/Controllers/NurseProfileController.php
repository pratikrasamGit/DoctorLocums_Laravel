<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Experience;
use App\Models\Certification;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\RegistrationMailable;

class NurseProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:Nurse');
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();        
        $nurse = Auth::user()->nurse;
        $availability = $nurse->availability;
        if(isset($availability->days_of_the_week)){
            $availability->days_of_the_week = explode(',', $availability->days_of_the_week);
        }
        $nuexperience = $this->nurseExperienceSelection($nurse);
        $certs = $nurse->getMedia('certificates');
        $states = $this->getStateOptions();
        $weekDays = $this->getWeekDayOptions();
        $assignmentDurations = $this->getAssignmentDurations()->pluck('title', 'id');
        $leadershipRoles = $this->getLeadershipRoles()->pluck('title', 'id');
        $nursingDegrees = $this->getNursingDegrees()->pluck('title', 'id');
        $specialities = $this->getSpecialities()->pluck('title', 'id');
        $educations = $this->getEducations()->pluck('title', 'id');
        $certifications = $this->getCertifications()->pluck('title', 'id');
        $ehrSoftwares = $this->getEHRSoftwares()->pluck('title', 'id');
        $ehrProficiencies = $this->getEHRProficiencies()->pluck('title', 'id');
        $ehrProficienciesExp = $this->getEHRProficiencyExp()->pluck('title', 'id');
        $shifts = $this->getShifts()->pluck('title', 'id');
        $geographicPreferences = $this->getGeographicPreferences()->pluck('title', 'id');
        $daisyCategories = $this->getDaisyCategories()->pluck('title', 'id');
        $facilityTypes = $this->getFacilityType()->pluck('title', 'id');
        $preferredShifts = $this->getPreferredShift()->pluck('title', 'id');
        
        return view('nurses.profile.edit', [
            'nurse' => $nurse,
            'user' => $user,
            'certs' => $certs,
            'states' => $states,
            'assignmentDurations' => $assignmentDurations,
            'leadershipRoles' => $leadershipRoles,
            'nursingDegrees' => $nursingDegrees,
            'specialities' => $specialities,
            'educations' => $educations,
            'certifications' => $certifications,
            'ehrSoftwares' => $ehrSoftwares,
            'ehrProficiencys' => $ehrProficiencies,
            'ehrProficienciesExp' => $ehrProficienciesExp,
            'shifts' => $shifts,
            'geographicPreferences' => $geographicPreferences,
            'daisyCategories' => $daisyCategories,
            'weekDays' => $weekDays,
            'facilityTypes' => $facilityTypes,
            'preferredShifts' => $preferredShifts,
            'availability' => $availability,
            'nuexperience' => $nuexperience
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $nurse = $user->nurse;
        $availability = $nurse->availability;        
        if ($request->has('update_profile')) {
            $this->performValidation($request);
            $this->validate($request, [
                'email' => $this->emailRegEx($user),
            ]);
            if ($request->input('password')) {
                $this->validate($request, [
                    'password' => $this->passwordRegEx()
                ]);
                $user->__set('password', Hash::make($request->input('password')));
            }
            $user->__set('first_name', $request->first_name);
            $user->__set('last_name', $request->last_name);
            $user->__set('email', $request->email);
            $user->__set('mobile', $request->mobile);
            if ($request->hasFile('image')) {
                $request->file('image')->storeAs('assets/nurses/profile', $id);
                $user->__set('image', $id);
            }
            $user->update();
            if($request->input('days_of_the_week')){
                $days_of_the_week = $request->input('days_of_the_week');
                $tmp = implode(',', $days_of_the_week);   
                $request->merge(['days_of_the_week' => $tmp]);          
            }
            $availability->update($request->toArray());                        
            $params = $request->toArray();
            $params['serving_preceptor'] =
                isset($params['serving_preceptor']) && !!$params['serving_preceptor'];
            $params['serving_interim_nurse_leader'] =
                isset($params['serving_interim_nurse_leader']) && !!$params['serving_interim_nurse_leader'];            
            $nurse->update($params);
            return Redirect::back()->with('success', 'Profile Updated');
        }
        if ($request->has('add_exp')) {
			$this->validate($request, [
                'organization_name' => 'required|min:10|max:255',
                'exp_city' => 'required|min:2|max:20',
                'exp_state' => 'required',
                'facility_type' => 'required',
                'start_date' => 'required|date',
                'organization_department_name' => 'required|min:5|max:255',
                'position_title' => 'required|min:5|max:100',
                'description_job_duties' => 'required|min:50|max:500',
			]);
			$experience = new Experience($request->toArray());
			$experience->__set('nurse_id', $id);
			$experience->save();
			return Redirect::back()->with('success', 'Experience Added');
        }
        if ($request->has('add_cred')) {
            $this->validate($request, [
                'type' => 'required',
                'certificate_image' => 'required',
            ]);
            $certification = new Certification($request->toArray());
			$certification->__set('nurse_id', $id);
            $certification->save();
            if($request->has('certificate_image')){
                $nurse->addMediaFromRequest('certificate_image')
                    ->usingName($request->type)
                    ->toMediaCollection('certificates');
            }
			return Redirect::back()->with('success', 'Certification Added');
        }
        if ($request->has('upload_resume')) {
            $this->validate($request, [
                'resume' => 'required|mimes:doc,docx,pdf,txt|max:2048',
            ]);
            $nurse->addMediaFromRequest('resume')
                    ->usingName($nurse->id)
                    ->toMediaCollection('resumes');
            return Redirect::back()->with('success', 'Resume Uploded');
        }
    }

    private function performValidation($request)
    {
        $this->validate($request, [
            'image' => 'nullable|max:500|image|mimes:jpeg,png,jpg',
            'first_name' => 'required|min:3|max:100',
            'last_name' => 'required|min:3|max:100',
            'mobile' => 'required|min:10|max:15',
            'serving_preceptor' => 'boolean',
            'serving_interim_nurse_leader' => 'boolean'
        ]);
    }

    public function testEmail(){
        $user = Auth::user();    
        $full_name = $user->first_name.' '.$user->last_name;
        Mail::to($user->email, $full_name)->send(
			new RegistrationMailable(
                $user->first_name,
                $user->last_name,
                $user->email
            )
        );
        
        return Redirect::back()->with('success', 'Email Sent');
    }
}
