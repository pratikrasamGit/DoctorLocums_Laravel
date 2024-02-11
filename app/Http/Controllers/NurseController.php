<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Nurse;
use App\Models\User;
use App\Models\Availability;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Hash;
use App\Models\Experience;
use App\Models\Certification;
use Illuminate\Support\Str;
use App\Enums\Role;
use App\Models\NurseAsset;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class NurseController extends Controller
{
    public function __construct()
    {
        // $this->middleware('permission:admin-create', ['only' => ['create', 'store']]);
        // $this->middleware('permission:admin-edit', ['only' => ['edit', 'update']]);
        // $this->middleware('permission:admin-show', ['only' => ['index']]);
        // $this->middleware('permission:admin-delete', ['only' => ['destroy']]);
        $this->middleware('auth');
        $this->middleware('permission:admin-delete', ['only' => ['destroy']]);
        $this->middleware('role:Administrator|Admin');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $nurses = $this->nurseSelection()->paginate(10);
        return view('admin.nurses.index')->with(
			compact(['nurses'])
		);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $nurse = new Nurse();
        $user = new User();
        $availability = new Availability();
        $specialities = $this->getSpecialities()->pluck('title', 'id'); 
        $geographicPreferences = $this->getGeographicPreferences()->pluck('title', 'id'); 
        $states = $this->getStateOptions();
        return view('admin.nurses.create')->with(
			compact(['nurse','user','availability','specialities','geographicPreferences','states'])
		);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->performValidation($request);
        // $this->validate($request, [
        //     'nursing_license_number' => 'required|min:5|max:20|unique:nurses,nursing_license_number',
		// ]);
        $this->validate($request, [
            'email' => $this->emailRegEx(),
        ]);
        $user = new User($request->toArray());
        $user->password = Hash::make(Str::random(10));
        $user->user_name = $request->email;
        $user->role = Role::getKey(Role::NURSE);
        $user->assignRole('Nurse');
        $user->save();
        if($request->input('specialty')){
            $specialty = $request->input('specialty');
            $tmp = implode(',', $specialty);   
            $request->merge(['specialty' => $tmp]);          
        }
        $nurse = new Nurse($request->toArray());
        $nurse->user_id = $user->id;
        // $latlang = $this->update_latlang($address = '',$request->city,$request->state,$request->postcode); 
        //     if(isset($latlang) && count($latlang)>0){
        //        if(isset($latlang['lat']) && isset($latlang['lng'])){
        //         $nurse->__set('n_lat', $latlang['lat']);
        //         $nurse->__set('n_lang', $latlang['lng']);
        //        }
        // }
        $nurse->save();
        $availability = new Availability($request->toArray());
        $availability->nurse_id = $nurse->id;
        $availability->save();
        return redirect('/admin/nurses')->with('success', 'Nurse Created');        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Nurse $nurse
     * @return \Illuminate\Http\Response
     */
    public function edit(Nurse $nurse)
    {
        $nuexperience = $this->nurseExperienceSelection($nurse);
        $certs = $nurse->getMedia('certificates');
        $states = $this->getStateOptions();
        $weekDays = $this->getWeekDayOptions();
        $languages = $this->getLanguageOptions();
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
        $user = $nurse->user;
        $availability = $nurse->availability;
        if(isset($availability->days_of_the_week)){
            $availability->days_of_the_week = explode(',', $availability->days_of_the_week);
        }
        if(isset($nurse->specialty)){
            $nurse->specialty = explode(',', $nurse->specialty);
        }
        if(isset($nurse->languages)){
            $nurse->languages = explode(',', $nurse->languages);
        }
        return view('admin.nurses.edit')->with(compact([
                    'nurse','user','availability','specialities','geographicPreferences','states',
                    'nuexperience','certs','weekDays','assignmentDurations','leadershipRoles',
                    'nursingDegrees','educations','certifications','ehrSoftwares','ehrProficiencies',
                    'ehrProficienciesExp','shifts','geographicPreferences','daisyCategories','facilityTypes',
                    'preferredShifts','languages'
                    ]));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Nurse $nurse
     * @return \Illuminate\Http\Response
     */
    public function update(Nurse $nurse, Request $request)
    {
        if ($request->has('update_profile')) {
            $this->performValidation($request);
            if(preg_match('/https?:\/\/(?:[\w]+\.)*youtube\.com\/watch\?v=[^&]+/', $request->nu_video, $vresult)) {
                $youTubeID = $this->parse_youtube($request->nu_video);
                $embedURL = 'https://www.youtube.com/embed/'.$youTubeID[1];
                $nurse->__set('nu_video_embed_url', $embedURL);  
                $nurse->update(); 
            } elseif(preg_match('/https?:\/\/(?:[\w]+\.)*vimeo\.com(?:[\/\w]*\/videos?)?\/([0-9]+)[^\s]*+/', $request->nu_video, $vresult)) {
                    $vimeoID = $this->parse_vimeo($request->nu_video);
                    $embedURL = 'https://player.vimeo.com/video/'.$vimeoID[1];
                    $nurse->__set('nu_video_embed_url', $embedURL);  
                    $nurse->update(); 
            }
            $user = $nurse->user;
            $this->validate($request, [
                'email' => $this->emailRegEx($user),
            ]);            
            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->email = $request->email;
            $user->mobile = $request->mobile;       
            if ($request->hasFile('image')) {
                $request->file('image')->storeAs('assets/nurses/profile', $nurse->id);
                $user->image = $nurse->id;                
            }   
            $user->update(); 
            $availability = $nurse->availability;            
            if($request->input('days_of_the_week')){
                $days_of_the_week = $request->input('days_of_the_week');
                $tmp = implode(',', $days_of_the_week);   
                $request->merge(['days_of_the_week' => $tmp]);          
            } 
            if($request->input('specialty')){
                $specialty = $request->input('specialty');
                $tmp = implode(',', $specialty);   
                $request->merge(['specialty' => $tmp]);          
            }
            if($request->input('languages')){
                $languages = $request->input('languages');
                $tmp = implode(',', $languages);   
                $request->merge(['languages' => $tmp]);          
            } 
            if($additional_photos=$request->file('additional_pictures')){
                foreach($additional_photos as $additional_photo){
                    $additional_photo_name_full = $additional_photo->getClientOriginalName();
                    $additional_photo_name = pathinfo($additional_photo_name_full, PATHINFO_FILENAME);
                    $additional_photo_ext = $additional_photo->getClientOriginalExtension();
                    $additional_photo_finalname = $additional_photo_name . '_' . time() . '.' . $additional_photo_ext;
                    //Upload Image
                    $additional_photo->storeAs('assets/nurses/additional_photos/'.$nurse->id, $additional_photo_finalname);
                    NurseAsset::create([
                        'nurse_id' => $nurse->id,
                        'name' => $additional_photo_finalname,
                        'filter' => 'additional_photos'
                    ]);
                }
            }
            if($additional_files=$request->file('additional_files')){
                foreach($additional_files as $additional_file){
                    $additional_file_name_full = $additional_file->getClientOriginalName();
                    $additional_file_name = pathinfo($additional_file_name_full, PATHINFO_FILENAME);
                    $additional_file_ext = $additional_file->getClientOriginalExtension();
                    $additional_file_finalname = $additional_file_name . '_' . time() . '.' . $additional_file_ext;
                    //Upload Image
                    $additional_file->storeAs('assets/nurses/additional_files/'.$nurse->id, $additional_file_finalname);
                    NurseAsset::create([
                        'nurse_id' => $nurse->id,
                        'name' => $additional_file_finalname,
                        'filter' => 'additional_files'
                    ]);
                }
            }   
            if($request->input('hourly_pay_rate')){
                $tmpRate =  $request->hourly_pay_rate * 25 / 100;
                $facility_hourly_pay_rate = $request->hourly_pay_rate + $tmpRate;
                $nurse->__set('facility_hourly_pay_rate', $facility_hourly_pay_rate);  
                $nurse->update(); 
            } 
            $beforeupdateCity = $nurse->city;
            $beforeupdatestate = $nurse->state;
            $beforeupdatePostCode = $nurse->postcode;
            if($beforeupdateCity != $request->city || $beforeupdatestate != $request->state || $beforeupdatePostCode != $request->postcode){
                // $latlang = $this->update_latlang($address = '',$request->city,$request->state,$request->postcode); 
                // if(isset($latlang) && count($latlang)>0){
                //     if(isset($latlang['lat']) && isset($latlang['lng'])){
                //         $nurse->__set('n_lat', $latlang['lat']);
                //         $nurse->__set('n_lang', $latlang['lng']);
                //         $nurse->update(); 
                //    }
                // }
            }                        
            $params = $request->toArray();
            $params['is_verified'] =
                isset($params['is_verified']) && !!$params['is_verified'];
                /*new column added 09 dec 2021 */
            $params['is_verified_nli'] = isset($params['is_verified_nli']) ? "1":"0";
                /*new column added 09 dec 2021 */
            $params['serving_preceptor'] =
                isset($params['serving_preceptor']) && !!$params['serving_preceptor'];
            $params['serving_interim_nurse_leader'] =
                isset($params['serving_interim_nurse_leader']) && !!$params['serving_interim_nurse_leader'];
            $params['clinical_educator'] =
                isset($params['clinical_educator']) && !!$params['clinical_educator'];
            $params['is_daisy_award_winner'] =
                isset($params['is_daisy_award_winner']) && !!$params['is_daisy_award_winner'];
            $params['employee_of_the_mth_qtr_yr'] =
                isset($params['employee_of_the_mth_qtr_yr']) && !!$params['employee_of_the_mth_qtr_yr'];
            $params['other_nursing_awards'] =
                isset($params['other_nursing_awards']) && !!$params['other_nursing_awards'];
            $params['is_professional_practice_council'] =
                isset($params['is_professional_practice_council']) && !!$params['is_professional_practice_council'];
            $params['is_research_publications'] =
                isset($params['is_research_publications']) && !!$params['is_research_publications'];
            $nurse->update($params);
            $availability->update($request->toArray());
            return Redirect::back()->with('success', 'Nurse Updated');
        }    
        if ($request->has('add_exp')) {
			$this->validate($request, [
                'organization_name' => 'required|max:255',
                'facility_type' => 'required|numeric|exists:keywords,id',
                'start_date' => 'required|date',
                'end_date' => "nullable|date|after:start_date",
			]);
			$experience = new Experience($request->toArray());
			$experience->__set('nurse_id', $nurse->id);
			$experience->save();
			$redirect = $request->input('url');
            if(isset($redirect) && $redirect){
                return redirect($redirect)->with('success', 'Work History Added.');
            }
            return redirect()->back()->with('success', 'Work History Added.');
            }
        if ($request->has('add_credentials')) {
            $this->validate($request, [
                'type' => 'required|numeric|exists:keywords,id',
                'effective_date' => 'nullable|date',
                'expiration_date' => "nullable|date|after:effective_date",
                'certificate_image' => 'nullable|max:5120|mimes:jpeg,png,jpg,pdf',
            ]);
            $certification = Certification::updateOrCreate(
                [
                    'nurse_id' => $nurse->id,
                    'type' => $request->type
                ],
                [
                    'effective_date' => $request->effective_date,
                    'expiration_date' => $request->expiration_date
                ]
            );
            if($request->hasFile('certificate_image')) {
                $certificate_image_name_full = $request->file('certificate_image')->getClientOriginalName();
                $certificate_image_name = pathinfo($certificate_image_name_full, PATHINFO_FILENAME);
                $certificate_image_ext = $request->file('certificate_image')->getClientOriginalExtension();
                $certificate_image = $certificate_image_name . '_' . time() . '.' . $certificate_image_ext;
                $certification->certificate_image = $certificate_image;
                //Upload Image
                $request->file('certificate_image')->storeAs('assets/nurses/certifications/'.$nurse->id, $certificate_image);
                $certification->update();
            }
            $redirect = $request->input('url');
            if(isset($redirect) && $redirect){
                return redirect($redirect)->with('success', 'Credential Added.');
            }
            return redirect()->back()->with('success', 'Credential Added.');
        }
        if ($request->has('upload_resume')) {
            $this->validate($request, [
                'resume' => 'required|mimes:doc,docx,pdf,txt|max:2048',
            ]);
            if($request->hasFile('resume')) {
                $resume_name_full = $request->file('resume')->getClientOriginalName();
                $resume_name = pathinfo($resume_name_full, PATHINFO_FILENAME);
                $resume_ext = $request->file('resume')->getClientOriginalExtension();
                $resume = $resume_name . '_' . time() . '.' . $resume_ext;
                $nurse->resume = $resume;
                //Upload Image
                $request->file('resume')->storeAs('assets/nurses/resumes/'.$nurse->id, $resume);
                $nurse->update();
            }
            $nurse->addMediaFromRequest('resume')
                    ->usingName($nurse->id)
                    ->toMediaCollection('resumes');
            return Redirect::back()->with('success', 'Resume Uploded');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Nurse $nurse
     * @return \Illuminate\Http\Response
     */
    public function destroy(Nurse $nurse)
    {
        $availability = $nurse->availability;
        $user = $nurse->user;
        $availability->delete();
        $nurse->delete();
        $user->delete();
        return Redirect::back()->with('success', 'Nurse Removed');
        
    }

    public function trashed()
    {
        $nurses = Nurse::onlyTrashed()->paginate(10);
        return view('admin.nurses.index-trashed')->with(
			compact(['nurses'])
		);
    }

    public function restore($id)
    {
        $nurse = Nurse::withTrashed()->find($id);
        if($nurse){
            $nurse->restore();  
            $nurse->user->restore(); 
            if($nurse->availability){
                $nurse->availability->restore();
            }
        }
        return Redirect::back()->with('success', 'Nurse Restored');
    }

    public function nurse_reset_password(Nurse $nurse)
    {
        $user = $nurse->user;
        $response = Password::broker()->sendResetLink(['email' => $user->email]);
        if($response == Password::RESET_LINK_SENT){
            return Redirect::back()->with('success', 'Password rest email sent');
        } else {
            return Redirect::back()->with('success', 'Password reset email failed');
        }
    }

    public function search(Request $request)
	{
		$search_text = $request->search_text;

		$nurses = $this->nurseSelection($search_text)->paginate(10);

		return view('admin.nurses.index')->with(
			compact(['nurses'])
		);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  NurseAsset $nurseAsset
     * @param  Nurse $nurse
     * @return \Illuminate\Http\Response
     */
    public function destroyDocument(Nurse $nurse, NurseAsset $nurseAsset)
    {
        $t = Storage::exists('assets/nurses/'.$nurseAsset->filter.'/'.$nurse->id.'/'.$nurseAsset->name);
        if ($t && $nurseAsset->name) {
            Storage::delete('assets/nurses/'.$nurseAsset->filter.'/'.$nurse->id.'/'.$nurseAsset->name);
        }
        $nurseAsset->delete();
        return redirect()->back()->with('success', 'Photo/File removed.');               
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Certification $certification
     * @param  Nurse $nurse
     * @return \Illuminate\Http\Response
     */
    public function destroyCertifications(Nurse $nurse, Certification $certification)
    {
        $t = Storage::exists('assets/nurses/certifications/'.$nurse->id.'/'.$certification->certificate_image);
        if ($t && $certification->certificate_image) {
            Storage::delete('assets/nurses/certifications/'.$nurse->id.'/'.$certification->certificate_image);
        }
        $certification->delete();
        return redirect()->back()->with('success', 'Credential removed.');               
    }

    /**
     * download the specified resource from storage.
     *
     * @param  NurseAsset $nurseAsset
     * @param  Nurse $nurse
     * @return \Illuminate\Http\Response
     */
    public function download(Nurse $nurse, NurseAsset $nurseAsset)
    {
        $pathToFile = '';
        $t = Storage::exists('assets/nurses/'.$nurseAsset->filter.'/'.$nurse->id.'/'.$nurseAsset->name);
        if ($t && $nurseAsset->name) {
            $pathToFile = storage_path('assets/nurses/'.$nurseAsset->filter.'/'.$nurse->id.'/'.$nurseAsset->name);
            return response()->file($pathToFile);
        }
        return null;
    }

    /**
     * download the specified resource from storage.
     *
     * @param  Nurse $nurse
     * @return \Illuminate\Http\Response
     */
    public function downloadResume(Nurse $nurse)
    {
        $pathToFile = '';
        $t = Storage::exists('assets/nurses/resumes/'.$nurse->id.'/'.$nurse->resume);
        if ($t && $nurse->resume) {
            $pathToFile = storage_path('assets/nurses/resumes/'.$nurse->id.'/'.$nurse->resume);
            return response()->file($pathToFile);
        }
        return null;
    }

    /**
     * download the specified resource from storage.
     *
     * @param  Nurse $nurse
     * @return \Illuminate\Http\Response
     */
    public function mediaDownloadResume(Nurse $nurse)
    {
        if( $nurse->user && $nurse->user->hasRole('Nurse')){
            $resume = $nurse->getMedia('resumes');
            if(isset($resume) && count($resume) > 0){
                if(File::exists($resume[0]->getPath())){
                    return response()->file($resume[0]->getPath());
                }                
            }
            return null;            
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  Nurse $nurse
     * @return \Illuminate\Http\Response
     */
    public function addWorkHistory(Nurse $nurse)
    {
        $states = $this->getStateOptions();
        $facilityTypes = $this->getFacilityType()->pluck('title', 'id');
        $experience = new Experience();
        return view('admin.nurses.workhistory')->with(
            compact(['nurse','states','facilityTypes','experience'])
        );
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  Nurse $nurse
     * @param  Experience $experience
     * @return \Illuminate\Http\Response
     */
    public function editWorkHistory(Nurse $nurse, Experience $experience)
    {
        $states = $this->getStateOptions();
        $facilityTypes = $this->getFacilityType()->pluck('title', 'id');
        return view('admin.nurses.edit-workhistory')->with(
            compact(['nurse','states','facilityTypes','experience'])
        );        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Nurse $nurse
     * @param  Experience $experience
     * @return \Illuminate\Http\Response
     */
    public function editWorkHistoryPost(Request $request, Nurse $nurse, Experience $experience)
    { 
        $this->validate($request, [
            'organization_name' => 'required|max:255',
            'facility_type' => 'required|numeric|exists:keywords,id',
            'start_date' => 'required|date',
            'end_date' => "nullable|date|after:start_date",
        ]);
        $params = $request->toArray();
        $experience->update($params);
        $redirect = $request->input('url');
        if(isset($redirect) && $redirect){
            return redirect($redirect)->with('success', 'Work History Updated.');
        }
        return redirect()->back()->with('success', 'Work History Updated.');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  Nurse $nurse
     * @return \Illuminate\Http\Response
     */
    public function addCredential(Nurse $nurse)
    {
        $credentials = $this->getCertifications()->pluck('title', 'id');
        $certification = new Certification();
        return view('admin.nurses.cred')->with(
            compact(['nurse','credentials','certification'])
        );
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  Nurse $nurse
     * @param  Certification $certification
     * @return \Illuminate\Http\Response
     */
    public function editCredential(Nurse $nurse, Certification $certification)
    {
        $credentials = $this->getCertifications()->pluck('title', 'id');
        return view('admin.nurses.edit-cred')->with(
            compact(['nurse','credentials','certification'])
        );
        
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @param  Nurse $nurse
     * @param  Certification $certification
     * @return \Illuminate\Http\Response
     */
    public function editCredentialPost(Request $request, Nurse $nurse, Certification $certification)
    {
        $this->validate($request, [
            'type' => 'required|numeric|exists:keywords,id',
            'effective_date' => 'nullable|date',
            'expiration_date' => "nullable|date|required_if:effective_date,!=,null|after:effective_date",
            'certificate_image' => 'nullable|max:5120|mimes:jpeg,png,jpg,pdf',
        ]);
        $params = $request->toArray();
        $certification->update($params);
        if($request->hasFile('certificate_image')) {
            $certificate_image_name_full = $request->file('certificate_image')->getClientOriginalName();
            $certificate_image_name = pathinfo($certificate_image_name_full, PATHINFO_FILENAME);
            $certificate_image_ext = $request->file('certificate_image')->getClientOriginalExtension();
            $certificate_image = $certificate_image_name . '_' . time() . '.' . $certificate_image_ext;
            $certification->certificate_image = $certificate_image;
            //Upload Image
            $request->file('certificate_image')->storeAs('assets/nurses/certifications/'.$nurse->id, $certificate_image);
            $certification->update();
        }
        $redirect = $request->input('url');
        if(isset($redirect) && $redirect){
            return redirect($redirect)->with('success', 'Credential Updated.');
        }
        return redirect()->back()->with('success', 'Credential Updated.');
    }

    /**
     * download the specified resource from storage.
     *
     * @param  Certification $certification
     * @param  Nurse $nurse
     * @return \Illuminate\Http\Response
     */
    public function downloadCredDocument(Nurse $nurse, Certification $certification)
    {
        $pathToFile = '';
        $t = Storage::exists('assets/nurses/certifications/'.$nurse->id.'/'.$certification->certificate_image);
        if ($t && $certification->certificate_image) {
            $pathToFile = storage_path('assets/nurses/certifications/'.$nurse->id.'/'.$certification->certificate_image);
            return response()->file($pathToFile);
        }
        return null;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Certification $certification
     * @param  Nurse $nurse
     * @return \Illuminate\Http\Response
     */
    public function destroyCredDocument(Nurse $nurse, Certification $certification)
    {
        $t = Storage::exists('assets/nurses/certifications/'.$nurse->id.'/'.$certification->certificate_image);
        if ($t && $certification->certificate_image) {
            Storage::delete('assets/nurses/certifications/'.$nurse->id.'/'.$certification->certificate_image);
        }
        return redirect()->back()->with('success', 'Certificate removed.');               
    }
    
    private function performValidation($request)
	{
        $messages = [
            "additional_pictures.*.mimes" => "Additional Photos should be image or png jpg",
            "additional_files.*.mimes" => "Additional Files should be doc or pdf",
            "additional_pictures.*.max" => "Additional Photos should not be more than 5mb",
            "additional_files.*.max" => "Additional Files should not be more than 1mb",
            "nu_video.url" => "YouTube and Vimeo should be a valid link"
         ];

		$this->validate($request, [
            'first_name' => 'required|max:100',
            'last_name' => 'required|max:100',
            'image' => 'nullable|max:5120|image|mimes:jpeg,png,jpg',
            'additional_pictures' => 'nullable',
            'additional_pictures.*' => 'image|mimes:jpeg,png,jpg|max:5120',
            'additional_files' => 'nullable',
            'additional_files.*' => 'mimes:pdf,doc,docx|max:2048',
            //'mobile' => 'required|min:10|max:15',
            'specialty' => 'required',
            'serving_preceptor' => 'boolean',
            'serving_interim_nurse_leader' => 'boolean',
            'clinical_educator' => 'boolean',
            'is_daisy_award_winner' => 'boolean',
            'employee_of_the_mth_qtr_yr' => 'boolean',
            'other_nursing_awards' => 'boolean',
            'is_professional_practice_council' => 'boolean',
            'is_research_publications' => 'boolean',
            'nu_video' => 'nullable|url|max:255',
		],$messages);
	}

    /**
    * @param Request $request
	 *
	 * @throws \Exception
	 *
	 * @return View
	 */
	public function nurses_export()
	{
        $nurses = Nurse::orderBy('created_at', 'desc')->get();
		$this->export_csv($nurses);
		exit;
	}

    private function export_csv($nurses)
	{
        header("Content-type: text/csv");
		header("Content-Disposition: attachment; filename=Nurses.csv");
		header("Pragma: no-cache");
		header("Expires: 0");
		$headers = array("First Name", "Last Name", "Email", "Mobile", "Address", "City", "State", "Postcode", "Country", "Specialities", "Nursing License State", "Nursing License Number");

		$main_body = array();
		array_push($main_body, $headers);
		$body_row = array();
		if (count($nurses) > 0) {
			foreach ($nurses as $nurse) {
                $user = $nurse->user;
                $specialty = "";
                if (isset($nurse->specialty) && $nurse->specialty != ""){
                    foreach(explode(',', $nurse->specialty) as $spl){
                        $specialty .= \App\Providers\AppServiceProvider::keywordTitle($spl)." | ";
                    }
                }
				$body_row = array($user->first_name, $user->last_name, $user->email, $user->mobile, $nurse->address, $nurse->city, $nurse->state, $nurse->postcode, $nurse->country, $specialty, $nurse->nursing_license_state, $nurse->nursing_license_number);
				array_push($main_body, $body_row);
			}
		}
		$rows = $main_body;
        foreach ($rows as $row) {
			$new_row_array = array();
			foreach ($row as $item) {
				array_push($new_row_array, "\"" . $item . "\"");
			}
			echo (implode(",", $new_row_array));
			echo ("\n");
		}
		exit;
    }
}
