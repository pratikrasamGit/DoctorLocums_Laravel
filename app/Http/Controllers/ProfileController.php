<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Hash;
use App\Models\Experience;
use App\Models\Certification;
use Illuminate\Support\Facades\Mail;
use App\Mail\RegistrationMailable;
use App\Models\Facility;
use App\Models\Nurse;
use App\Models\NurseAsset;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'access.profile']);
        $this->middleware('role:Nurse|Facility|FacilityAdmin');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        if (Auth::user() && $user->hasRole('Nurse') && $user->nurse) {
            return redirect(route('personal-detail', [$user->nurse->id]));
            exit;
        }
        if (Auth::user() && $user->hasRole(['FacilityAdmin', 'Facility']) && $user->facilities()->first()) {
            return redirect(route('facility-detail', [$user->facilities()->first()->id]));
            exit;
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  Nurse $nurse
     * @return \Illuminate\Http\Response
     */
    public function personalDetail(Nurse $nurse)
    {
        $user = Auth::user();
        $states = $this->getStateOptions();
        $specialities = $this->getSpecialities()->pluck('title', 'id');
        $countries = $this->getCountries()->pluck('name', 'name');
        $usaIsoStates = $this->getUsaStates()->pluck('name', 'name');
        if (isset($nurse->specialty)) {
            $nurse->specialty = explode(',', $nurse->specialty);
        }
        return view('nurses.partials.profile-personal')->with(
            compact(['nurse', 'user', 'states', 'specialities', 'countries', 'usaIsoStates'])
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Nurse $nurse
     * @return \Illuminate\Http\Response
     */
    public function personalDetailPost(Request $request, Nurse $nurse)
    {
        $user = Auth::user();
        $this->performValidationPersonalDetail($request);
        $this->validate($request, [
            'email' => $this->emailRegEx($user),
        ]);
        if ($request->input('password')) {
            $this->validate($request, [
                'password' => $this->passwordRegEx()
            ]);
            $user->password = Hash::make($request->input('password'));
        }
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;
        $user->mobile = $request->mobile;
        if ($request->hasFile('image')) {
            $request->file('image')->storeAs('assets/nurses/profile', $nurse->id);
            $user->image = $nurse->id;
        }
        $user->update();
        $beforeupdateCity = $nurse->city;
        $beforeupdatestate = $nurse->state;
        $beforeupdatePostCode = $nurse->postcode;
        if ($beforeupdateCity != $request->city || $beforeupdatestate != $request->state || $beforeupdatePostCode != $request->postcode) {
            // $latlang = $this->update_latlang($address = '',$request->city,$request->state,$request->postcode);
            // if(isset($latlang) && count($latlang)>0){
            //     if(isset($latlang['lat']) && isset($latlang['lng'])){
            //         $nurse->__set('n_lat', $latlang['lat']);
            //         $nurse->__set('n_lang', $latlang['lng']);
            //         $nurse->update();
            //     }
            // }
        }
        if ($request->input('specialty')) {
            $specialty = $request->input('specialty');
            $tmp = implode(',', $specialty);
            $request->merge(['specialty' => $tmp]);
        }
        $params = $request->toArray();
        $nurse->update($params);
        return redirect('profile-setup/' . $nurse->id . '/availability')->with('success', 'Personal Detail Updated.');
        exit;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  Nurse $nurse
     * @return \Illuminate\Http\Response
     */
    public function scheduleOnboarding(Nurse $nurse)
    {
        return view('nurses.partials.profile-onboarding')->with(
            compact(['nurse'])
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Nurse $nurse
     * @return \Illuminate\Http\Response
     */
    public function scheduleOnboardingPost(Request $request, Nurse $nurse)
    {
        return redirect()->back()->with('success', 'Schedule Onboarding Updated.');
        exit;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  Nurse $nurse
     * @return \Illuminate\Http\Response
     */
    public function createGigwageAccount(Nurse $nurse)
    {
        return view('nurses.partials.profile-gigwageaccount')->with(
            compact(['nurse'])
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Nurse $nurse
     * @return \Illuminate\Http\Response
     */
    public function createGigwageAccountPost(Request $request, Nurse $nurse)
    {
        $user = $nurse->user;
        $status = "success";
        $msg = "Invitation already sent to setup direct deposite account please check your email for furtur process.";
        if (!$nurse->is_gig_invite) {
            $apiKey = "4099d0e8302e64a7d3901d8750ab0044";
            $secret = "cad48f7730c4c29134ad7712c9e9c111fd18f11f9de786387a70789d4f4fb69758d71840b49761a6f70bba5b40fe7fb08b25100658e6691143959ed9a44b5e42";
            $timestamp = round(microtime(true) * 1000);
            $signature = hash_hmac('sha256', $timestamp, $secret, false);
            $response = Http::withHeaders([
                'X-Gw-Api-Key' => $apiKey,
                'X-Gw-Timestamp' => $timestamp,
                'X-Gw-Signature' => $signature,
            ])->post('https://sandbox.gigwage.com/api/v1/contractors', [
                'contractor' => [
                    "first_name" => $user->first_name,
                    "last_name" => $user->last_name,
                    "email" => $user->email
                ]
            ]);
            if ($response->successful()) {
                $result = $response->json();
                $gigID = $result['contractor']['id'];
                $inviteURL = "https://sandbox.gigwage.com/api/v1/contractors/$gigID/invite";
                $nurse->__set('is_gig_invite', true);
                $nurse->__set('gig_account_id', $gigID);
                $nurse->__set('gig_account_create_date', $result['contractor']['created_at']);
                $nurse->save();
                $response1 = Http::withHeaders([
                    'X-Gw-Api-Key' => $apiKey,
                    'X-Gw-Timestamp' => $timestamp,
                    'X-Gw-Signature' => $signature,
                ])->post($inviteURL);
                if ($response1->successful()) {
                    $result1 = $response1->json();
                    $nurse->__set('gig_account_invite_date', $result1['contractor']['invited_at']);
                    $nurse->save();
                }
            }
            $status = "error";
            switch ($response->status()) {
                case 400:
                    $msg = "Bad Request -- Your request is invalid.";
                    break;
                case 401:
                    $msg = "Unauthorized -- Your API key is wrong.";
                    break;
                case 403:
                    $msg = "Forbidden -- Not for you bruh.";
                    break;
                case 404:
                    $msg = "Not Found -- The resource you could not be found.";
                    break;
                case 429:
                    $msg = "Too Many Requests -- Slow ya roll!";
                    break;
                case 500:
                    $msg = "Internal Server Error -- We had a problem with our server. Try again later.";
                    break;
                case 503:
                    $msg = "Service Unavailable -- W're temporarily offline for maintenance. Please try again later.";
                    break;
                default:
                    $status = "success";
                    $msg = "The GigWage invitation was resent to setup your direct deposit account.  Please check your email for additional guidance.";
            }
        }
        return redirect()->back()->with($status, $msg);
        exit;
    }

    public function inviteGigwageAccount(Nurse $nurse, $id)
    {
        $status = "error";
        $msg = "Invalid Request.";
        if ($id === $nurse->gig_account_id) {
            $apiKey = "4099d0e8302e64a7d3901d8750ab0044";
            $secret = "cad48f7730c4c29134ad7712c9e9c111fd18f11f9de786387a70789d4f4fb69758d71840b49761a6f70bba5b40fe7fb08b25100658e6691143959ed9a44b5e42";
            $timestamp = round(microtime(true) * 1000);
            $signature = hash_hmac('sha256', $timestamp, $secret, false);
            $gigID = $nurse->gig_account_id;
            $inviteURL = "https://sandbox.gigwage.com/api/v1/contractors/$gigID/invite";
            $response1 = Http::withHeaders([
                'X-Gw-Api-Key' => $apiKey,
                'X-Gw-Timestamp' => $timestamp,
                'X-Gw-Signature' => $signature,
            ])->post($inviteURL);
            if ($response1->successful()) {
                $result1 = $response1->json();
                $nurse->__set('gig_account_invite_date', $result1['contractor']['invited_at']);
                $nurse->save();
            }
            $status = "error";
            switch ($response1->status()) {
                case 400:
                    $msg = "Bad Request -- Your request is invalid.";
                    break;
                case 401:
                    $msg = "Unauthorized -- Your API key is wrong.";
                    break;
                case 403:
                    $msg = "Forbidden -- Not for you bruh.";
                    break;
                case 404:
                    $msg = "Not Found -- The resource you could not be found.";
                    break;
                case 429:
                    $msg = "Too Many Requests -- Slow ya roll!";
                    break;
                case 500:
                    $msg = "Internal Server Error -- We had a problem with our server. Try again later.";
                    break;
                case 503:
                    $msg = "Service Unavailable -- W're temporarily offline for maintenance. Please try again later.";
                    break;
                default:
                    $status = "success";
                    $msg = "The GigWage invitation was resent to setup your direct deposit account.  Please check your email for additional guidance.";
            }
        }
        return redirect()->back()->with($status, $msg);
        exit;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  Nurse $nurse
     * @return \Illuminate\Http\Response
     */
    public function availability(Nurse $nurse)
    {
        $availability = $nurse->availability;
        $shifts = $this->getShifts()->pluck('title', 'id');
        $assignmentDurations = $this->getAssignmentDurations()->pluck('title', 'id');
        $geographicPreferences = $this->getGeographicPreferences()->pluck('title', 'id');
        $preferredShifts = $this->getPreferredShift()->pluck('title', 'id');
        $weekDays = $this->getWeekDayOptions();
        if (isset($availability->days_of_the_week)) {
            $availability->days_of_the_week = explode(',', $availability->days_of_the_week);
        }
        return view('nurses.partials.profile-availability')->with(
            compact([
                'nurse', 'availability', 'shifts', 'assignmentDurations', 'geographicPreferences',
                'preferredShifts', 'weekDays'
            ])
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Nurse $nurse
     * @return \Illuminate\Http\Response
     */
    public function availabilityPost(Request $request, Nurse $nurse)
    {
        $this->performValidationAvailability($request);
        if ($request->input('hourly_pay_rate')) {
            $tmpRate =  $request->hourly_pay_rate * 25 / 100;
            $facility_hourly_pay_rate = $request->hourly_pay_rate + $tmpRate;
            $nurse->__set('facility_hourly_pay_rate', $facility_hourly_pay_rate);
            $nurse->hourly_pay_rate = $request->hourly_pay_rate;
            $nurse->update();
        }
        if ($request->input('days_of_the_week')) {
            $days_of_the_week = $request->input('days_of_the_week');
            $tmp = implode(',', $days_of_the_week);
            $request->merge(['days_of_the_week' => $tmp]);
        }
        $availability = $nurse->availability;
        $availability->update($request->toArray());
        return redirect()->back()->with('success', 'Hourly Rate & Availability Updated.');
        exit;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  Nurse $nurse
     * @return \Illuminate\Http\Response
     */
    public function certifications(Nurse $nurse)
    {
        $nuexperience = $this->nurseExperienceSelection($nurse);
        $certifications = $this->getCertifications()->pluck('title', 'id');
        $certs = $nurse->getMedia('certificates');
        $states = $this->getStateOptions();
        $ehrProficienciesExp = $this->getEHRProficiencyExp()->pluck('title', 'id');
        $nursingDegrees = $this->getNursingDegrees()->pluck('title', 'id');
        $facilityTypes = $this->getFacilityType()->pluck('title', 'id');
        return view('nurses.partials.profile-certificates')->with(
            compact([
                'nurse', 'certs', 'nuexperience', 'certifications', 'states', 'ehrProficienciesExp',
                'nursingDegrees', 'facilityTypes'
            ])
        );
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
        return view('nurses.partials.profile-workhistory')->with(
            compact(['nurse', 'states', 'facilityTypes', 'experience'])
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
        return view('nurses.partials.profile-editworkhistory')->with(
            compact(['nurse', 'states', 'facilityTypes', 'experience'])
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
    public function editWorkHistoryPost(Request $request, Experience $experience, Nurse $nurse)
    {
        $this->performValidationExperience($request);
        $params = $request->toArray();
        $experience->update($params);
        return redirect(route('work-history', [$nurse->id]))->with('success', 'Work History Updated.');
        exit;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Nurse $nurse
     * @return \Illuminate\Http\Response
     */
    public function certificationsPost(Request $request, Nurse $nurse)
    {
        if ($request->has('add_exp')) {
            $this->performValidationExperience($request);
            $experience = new Experience($request->toArray());
            $experience->__set('nurse_id', $nurse->id);
            $experience->save();
            return redirect(route('work-history', [$nurse->id]))->with('success', 'Work History Added.');
            exit;
        }
        if ($request->has('add_credentials')) {
            $this->validate($request, [
                'type' => 'required|numeric|exists:keywords,id',
                'effective_date' => 'required|date',
                'expiration_date' => "required|date|after:effective_date",
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
            if ($request->hasFile('certificate_image')) {
                $certificate_image_name_full = $request->file('certificate_image')->getClientOriginalName();
                $certificate_image_name = pathinfo($certificate_image_name_full, PATHINFO_FILENAME);
                $certificate_image_ext = $request->file('certificate_image')->getClientOriginalExtension();
                $certificate_image = $certificate_image_name . '_' . time() . '.' . $certificate_image_ext;
                $certification->certificate_image = $certificate_image;
                //Upload Image
                $request->file('certificate_image')->storeAs('assets/nurses/certifications/' . $nurse->id, $certificate_image);
                $certification->update();
            }
            return redirect(route('work-history', [$nurse->id]))->with('success', 'Credential Added.');
            exit;
        }
        if ($request->has('upload_resume')) {
            $this->validate($request, [
                'resume' => 'required|mimes:doc,docx,pdf,txt|max:2048',
            ]);
            if ($request->hasFile('resume')) {
                $resume_name_full = $request->file('resume')->getClientOriginalName();
                $resume_name = pathinfo($resume_name_full, PATHINFO_FILENAME);
                $resume_ext = $request->file('resume')->getClientOriginalExtension();
                $resume = $resume_name . '_' . time() . '.' . $resume_ext;
                $nurse->resume = $resume;
                //Upload Image
                $request->file('resume')->storeAs('assets/nurses/resumes/' . $nurse->id, $resume);
                $nurse->update();
            }
            $nurse->addMediaFromRequest('resume')
                ->usingName($nurse->id)
                ->toMediaCollection('resumes');
            return redirect()->back()->with('success', 'Resume Uploded.');
            exit;
        }
        $this->performValidationCertification($request);
        $params = $request->toArray();
        $nurse->update($params);
        return redirect()->back()->with('success', 'Work History Updated.');
        exit;
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
        return view('nurses.partials.profile-credential')->with(
            compact(['nurse', 'credentials', 'certification'])
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
        return view('nurses.partials.profile-editcredential')->with(
            compact(['nurse', 'credentials', 'certification'])
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
            'effective_date' => 'required|date',
            'expiration_date' => "required|date|after:effective_date",
            'certificate_image' => 'nullable|max:5120|mimes:jpeg,png,jpg,pdf',
        ]);
        $params = $request->toArray();
        $certification->update($params);
        if ($request->hasFile('certificate_image')) {
            $certificate_image_name_full = $request->file('certificate_image')->getClientOriginalName();
            $certificate_image_name = pathinfo($certificate_image_name_full, PATHINFO_FILENAME);
            $certificate_image_ext = $request->file('certificate_image')->getClientOriginalExtension();
            $certificate_image = $certificate_image_name . '_' . time() . '.' . $certificate_image_ext;
            $certification->certificate_image = $certificate_image;
            //Upload Image
            $request->file('certificate_image')->storeAs('assets/nurses/certifications/' . $nurse->id, $certificate_image);
            $certification->update();
        }
        return redirect(route('work-history', [$nurse->id]))->with('success', 'Credential Updated.');
        exit;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  Nurse $nurse
     * @return \Illuminate\Http\Response
     */
    public function roleInterest(Nurse $nurse)
    {
        $leadershipRoles = $this->getLeadershipRoles()->pluck('title', 'id');
        $languages = $this->getLanguageOptions();
        if (isset($nurse->languages)) {
            $nurse->languages = explode(',', $nurse->languages);
        }
        return view('nurses.partials.profile-assessment')->with(
            compact(['nurse', 'leadershipRoles', 'languages'])
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Nurse $nurse
     * @return \Illuminate\Http\Response
     */
    public function roleInterestPost(Request $request, Nurse $nurse)
    {
        $this->performValidationRoleInterest($request);
        if (preg_match('/https?:\/\/(?:[\w]+\.)*youtube\.com\/watch\?v=[^&]+/', $request->nu_video, $vresult)) {
            $youTubeID = $this->parse_youtube($request->nu_video);
            $embedURL = 'https://www.youtube.com/embed/' . $youTubeID[1];
            $nurse->__set('nu_video_embed_url', $embedURL);
            $nurse->update();
        } elseif (preg_match('/https?:\/\/(?:[\w]+\.)*vimeo\.com(?:[\/\w]*\/videos?)?\/([0-9]+)[^\s]*+/', $request->nu_video, $vresult)) {
            $vimeoID = $this->parse_vimeo($request->nu_video);
            $embedURL = 'https://player.vimeo.com/video/' . $vimeoID[1];
            $nurse->__set('nu_video_embed_url', $embedURL);
            $nurse->update();
        }
        $params = $request->toArray();
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
        if ($additional_photos = $request->file('additional_pictures')) {
            foreach ($additional_photos as $additional_photo) {
                $additional_photo_name_full = $additional_photo->getClientOriginalName();
                $additional_photo_name = pathinfo($additional_photo_name_full, PATHINFO_FILENAME);
                $additional_photo_ext = $additional_photo->getClientOriginalExtension();
                $additional_photo_finalname = $additional_photo_name . '_' . time() . '.' . $additional_photo_ext;
                //Upload Image
                $additional_photo->storeAs('assets/nurses/additional_photos/' . $nurse->id, $additional_photo_finalname);
                NurseAsset::create([
                    'nurse_id' => $nurse->id,
                    'name' => $additional_photo_finalname,
                    'filter' => 'additional_photos'
                ]);
            }
        }
        if ($additional_files = $request->file('additional_files')) {
            foreach ($additional_files as $additional_file) {
                $additional_file_name_full = $additional_file->getClientOriginalName();
                $additional_file_name = pathinfo($additional_file_name_full, PATHINFO_FILENAME);
                $additional_file_ext = $additional_file->getClientOriginalExtension();
                $additional_file_finalname = $additional_file_name . '_' . time() . '.' . $additional_file_ext;
                //Upload Image
                $additional_file->storeAs('assets/nurses/additional_files/' . $nurse->id, $additional_file_finalname);
                NurseAsset::create([
                    'nurse_id' => $nurse->id,
                    'name' => $additional_file_finalname,
                    'filter' => 'additional_files'
                ]);
            }
        }
        return redirect()->back()->with('success', 'Role Interest Updated.');
        exit;
    }

    /**
     *
     * @param  Certification $certification
     * @param  Nurse $nurse
     * @return \Illuminate\Http\Response
     */
    public function destroyCertifications(Nurse $nurse, Certification $certification)
    {
        $t = Storage::exists('assets/nurses/certifications/' . $nurse->id . '/' . $certification->certificate_image);
        if ($t && $certification->certificate_image) {
            Storage::delete('assets/nurses/certifications/' . $nurse->id . '/' . $certification->certificate_image);
        }
        $certification->delete();
        return redirect()->back()->with('success', 'Credential removed.');
    }

    /**
     *
     * @param  Certification $certification
     * @param  Nurse $nurse
     * @return \Illuminate\Http\Response
     */
    public function destroyCredDocument(Nurse $nurse, Certification $certification)
    {
        $t = Storage::exists('assets/nurses/certifications/' . $nurse->id . '/' . $certification->certificate_image);
        if ($t && $certification->certificate_image) {
            Storage::delete('assets/nurses/certifications/' . $nurse->id . '/' . $certification->certificate_image);
        }
        return redirect()->back()->with('success', 'Certificate removed.');
    }

    /**
     *
     * @param  Certification $certification
     * @param  Nurse $nurse
     * @return \Illuminate\Http\Response
     */
    public function downloadCredDocument(Nurse $nurse, Certification $certification)
    {
        $pathToFile = '';
        $t = Storage::exists('assets/nurses/certifications/' . $nurse->id . '/' . $certification->certificate_image);
        if ($t && $certification->certificate_image) {
            $pathToFile = storage_path('assets/nurses/certifications/' . $nurse->id . '/' . $certification->certificate_image);
            return response()->file($pathToFile);
        }
        return null;
    }

    /**
     *
     * @param  Nurse $nurse
     * @return \Illuminate\Http\Response
     */
    public function downloadResume(Nurse $nurse)
    {
        $pathToFile = '';
        $t = Storage::exists('assets/nurses/resumes/' . $nurse->id . '/' . $nurse->resume);
        if ($t && $nurse->resume) {
            $pathToFile = storage_path('assets/nurses/resumes/' . $nurse->id . '/' . $nurse->resume);
            return response()->file($pathToFile);
        }
        return null;
    }

    /**
     *
     * @param  NurseAsset $nurseAsset
     * @param  Nurse $nurse
     * @return \Illuminate\Http\Response
     */
    public function destroyDocument(Nurse $nurse, NurseAsset $nurseAsset)
    {
        $t = Storage::exists('assets/nurses/' . $nurseAsset->filter . '/' . $nurse->id . '/' . $nurseAsset->name);
        if ($t && $nurseAsset->name) {
            Storage::delete('assets/nurses/' . $nurseAsset->filter . '/' . $nurse->id . '/' . $nurseAsset->name);
        }
        $nurseAsset->delete();
        return redirect()->back()->with('success', 'Photo/File removed.');
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
        $t = Storage::exists('assets/nurses/' . $nurseAsset->filter . '/' . $nurse->id . '/' . $nurseAsset->name);
        if ($t && $nurseAsset->name) {
            $pathToFile = storage_path('assets/nurses/' . $nurseAsset->filter . '/' . $nurse->id . '/' . $nurseAsset->name);
            return response()->file($pathToFile);
        }
        return null;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  Facility $facility
     * @return \Illuminate\Http\Response
     */
    public function facilityDetail(Facility $facility)
    {
        $states = $this->getStateOptions();
        $facilityTypes = $this->getFacilityType()->pluck('title', 'id');
        $eMedicalRecords = $this->getEMedicalRecords()->pluck('title', 'id');
        $eMedicalRecords['other'] = 'Other';
        $bCheckProviders = $this->getBCheckProvider()->pluck('title', 'id');
        $bCheckProviders['other'] = 'Other';
        $nCredentialingSoftwares = $this->getNCredentialingSoftware()->pluck('title', 'id');
        $nCredentialingSoftwares['other'] = 'Other';
        $nSchedulingSystems = $this->getNSchedulingSystem()->pluck('title', 'id');
        $nSchedulingSystems['other'] = 'Other';
        $timeAttendanceSystems = $this->getTimeAttendanceSystem()->pluck('title', 'id');
        $timeAttendanceSystems['other'] = 'Other';
        $traumaDesignations = $this->getTraumaDesignation()->pluck('title', 'id');
        $traumaDesignations['na'] = 'N/A';
        return view('facilities.profile.edit')->with(
            compact([
                'facility', 'facilityTypes', 'states', 'eMedicalRecords',
                'bCheckProviders', 'nCredentialingSoftwares', 'nSchedulingSystems',
                'timeAttendanceSystems', 'traumaDesignations'
            ])
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Facility $facility
     * @return \Illuminate\Http\Response
     */
    public function facilityDetailPost(Request $request, Facility $facility)
    {
        $this->performValidationFacility($request);
        if (preg_match('/https?:\/\/(?:[\w]+\.)*youtube\.com\/watch\?v=[^&]+/', $request->video, $vresult)) {
            $youTubeID = $this->parse_youtube($request->video);
            $embedURL = 'https://www.youtube.com/embed/' . $youTubeID[1];
            $facility->__set('video_embed_url', $embedURL);
            $facility->update();
        } elseif (preg_match('/https?:\/\/(?:[\w]+\.)*vimeo\.com(?:[\/\w]*\/videos?)?\/([0-9]+)[^\s]*+/', $request->video, $vresult)) {
            $vimeoID = $this->parse_vimeo($request->video);
            $embedURL = 'https://player.vimeo.com/video/' . $vimeoID[1];
            $facility->__set('video_embed_url', $embedURL);
            $facility->update();
        }

        /* Geo location update */
        $beforeupdateAddress = $facility->address;
        $beforeupdateCity = $facility->city;
        $beforeupdatestate = $facility->state;
        $beforeupdatePostCode = $facility->postcode;
        if ($beforeupdateAddress != $request->address || $beforeupdateCity != $request->city || $beforeupdatestate != $request->state || $beforeupdatePostCode != $request->postcode) {
            $latlang = $this->update_latlang($request->address, $request->city, $request->state, $request->postcode);
            if (isset($latlang) && count($latlang) > 0) {
                if (isset($latlang['lat']) && isset($latlang['lng'])) {
                    $facility->__set('f_lat', $latlang['lat']);
                    $facility->__set('f_lang', $latlang['lng']);
                    $facility->update();
                }
            }
        }
        /* Geo location update */

        $params = $request->toArray();
        $facility->update($params);
        if ($request->hasFile('facility_logo')) {
            $facility_logo_name_full = $request->file('facility_logo')->getClientOriginalName();
            $facility_logo_name = pathinfo($facility_logo_name_full, PATHINFO_FILENAME);
            $facility_logo_ext = $request->file('facility_logo')->getClientOriginalExtension();
            $facility_logo = $facility_logo_name . '_' . time() . '.' . $facility_logo_ext;
            $facility->facility_logo = $facility_logo;
            //Upload Image
            $request->file('facility_logo')->storeAs('assets/facilities/facility_logo', $facility_logo);
            $facility->update();
        }
        if ($request->hasFile('cno_image')) {
            $cno_image_name_full = $request->file('cno_image')->getClientOriginalName();
            $cno_image_name = pathinfo($cno_image_name_full, PATHINFO_FILENAME);
            $cno_image_ext = $request->file('cno_image')->getClientOriginalExtension();
            $cno_image = $cno_image_name . '_' . time() . '.' . $cno_image_ext;
            $facility->cno_image = $cno_image;
            //Upload Image
            $request->file('cno_image')->storeAs('assets/facilities/cno_image', $cno_image);
            $facility->update();
        }
        return Redirect::back()->with('success', 'Profile Updated');
    }

    private function performValidationFacility($request)
    {
        $this->validate($request, [
            'name' => 'required|min:10|max:255',
            'type' => 'required',
            'facility_email' => 'nullable|email|max:255',
            'facility_phone' => 'required|min:10|max:15',
            'address' => 'required|min:5|max:190',
            'city' => 'required|min:3|max:20',
            'state' => 'required',
            'postcode' => 'required|min:4|max:6',
            'facility_logo' => 'nullable|max:1024|image|mimes:jpeg,png,jpg,gif',
            'cno_image' => 'nullable|max:1024|image|mimes:jpeg,png,jpg,gif',
            'video' => 'nullable|url|max:255',
            'facebook' => 'nullable|url|max:255',
            'twitter' => 'nullable|url|max:255',
            'linkedin' => 'nullable|url|max:255',
            'instagram' => 'nullable|url|max:255',
            'pinterest' => 'nullable|url|max:255',
            'tiktok' => 'nullable|url|max:255',
            'sanpchat' => 'max:255',
            'youtube' => 'nullable|url|max:255',
            'facility_website' => 'nullable|url|max:255',
            'f_emr' => 'required',
            'f_emr_other' => 'nullable|required_if:f_emr,other|regex:/^[a-zA-Z 0-9,\-\/]+$/|min:1|max:150',
            'f_bcheck_provider' => 'required',
            'f_bcheck_provider_other' => 'nullable|required_if:f_bcheck_provider,other|regex:/^[a-zA-Z 0-9,\-\/]+$/|min:1|max:150',
            'nurse_cred_soft' => 'required',
            'nurse_cred_soft_other' => 'nullable|required_if:nurse_cred_soft,other|regex:/^[a-zA-Z 0-9,\-\/]+$/|min:1|max:150',
            'nurse_scheduling_sys' => 'required',
            'nurse_scheduling_sys_other' => 'nullable|required_if:nurse_scheduling_sys,other|regex:/^[a-zA-Z 0-9,\-\/]+$/|min:1|max:150',
            'time_attend_sys' => 'required',
            'time_attend_sys_other' => 'nullable|required_if:time_attend_sys,other|regex:/^[a-zA-Z 0-9,\-\/]+$/|min:1|max:150',
            'licensed_beds' => 'nullable|regex:/^[0-9]+$/|min:1|max:20',
        ]);
    }

    private function performValidationPersonalDetail($request)
    {
        $messages = [
            "image.max" => "Profile Photo can't be more than 1mb.",
            "image.mimes" => "Profile Photo should be image or png jpg",
            "first_name.required" => "Please add first name",
            "first_name.regex" => "First name should be alphabet and no space",
            "first_name.min" => "First name is short",
            "first_name.max" => "First name is too long",
            "last_name.required" => "Please add last name",
            "last_name.regex" => "Last name should be alphabet and no space",
            "last_name.min" => "Last name is short",
            "last_name.max" => "Last name is too long",
            "mobile.regex" => "Mobile number not valid",
            "address.regex" => "Address not valid",
            // "city.regex" => "City not valid",
            // "country.regex" => "Country not valid",
            "postcode.regex" => "Postcode not valid",
            "nursing_license_state.required" => "Please add nursing license state",
            "nursing_license_state.regex" => "Nursing license state should be alphabet",
            "nursing_license_state.min" => "Nursing license state is short",
            "nursing_license_state.max" => "Nursing license state is too long",
            "nursing_license_number.regex" => "Nursing license number not valid",
            "nursing_license_number.min" => "Nursing license number is short",
            "nursing_license_number.max" => "Nursing license number is too long",
        ];

        $this->validate($request, [
            'image' => 'nullable|max:1024|image|mimes:jpeg,png,jpg',
            'first_name' => 'required|regex:/^[a-zA-Z]+$/|min:3|max:100',
            'last_name' => 'required|regex:/^[a-zA-Z]+$/|min:3|max:100',
            'mobile' => 'required|regex:/^[0-9 \+]+$/|min:4|max:20',
            'address' => 'required|regex:/^[a-zA-Z 0-9,\-\/]+$/|min:1|max:150',
            'city' => 'nullable|min:2|max:50',
            'state' => 'required',
            'country' => 'required|min:3',
            'postcode' => 'required|regex:/^[a-zA-Z0-9]+$/|min:3|max:10',
            'specialty' => 'required',
            'nursing_license_state' => 'required|regex:/^[a-zA-Z ]+$/|min:2|max:50',
            'nursing_license_number' => 'nullable|regex:/^[a-zA-Z0-9]+$/|min:2|max:50',
        ], $messages);
    }

    private function performValidationAvailability($request)
    {
        $messages = [
            "shift_duration.required" => "Select shift duration",
            "assignment_duration.required" => "Select assignment duration",
            "preferred_shift.required" => "Select preferred shift",
            "days_of_the_week.required" => "Select preferred days of the week",
            "earliest_start_date.date" => "Earliest start date is not valid date",
            "work_location.required" => "Select work location",
        ];

        $this->validate($request, [
            'hourly_pay_rate' => 'required|regex:/^[0-9]+$/|min:1|max:3',
            'shift_duration' => 'required',
            'assignment_duration' => 'required',
            'preferred_shift' => 'required',
            'days_of_the_week' => 'required',
            'earliest_start_date' => 'nullable|date|after_or_equal:now',
            'work_location' => 'required',
        ], $messages);
    }

    private function performValidationExperience($request)
    {
        $this->validate($request, [
            'organization_name' => 'required|regex:/^[a-zA-Z 0-9,\-\/]+$/|min:5|max:255',
            'exp_city' => 'required|regex:/^[a-zA-Z ]+$/|min:2|max:50',
            'exp_state' => 'required',
            'facility_type' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'organization_department_name' => 'required|regex:/^[a-zA-Z 0-9,\-\/]+$/|min:5|max:255',
            'position_title' => 'required|regex:/^[a-zA-Z 0-9,\-\/]+$/|min:5|max:100',
            'description_job_duties' => 'required|regex:/^[a-zA-Z 0-9,\-\/]+$/|min:5|max:500',
        ]);
    }

    private function performValidationCertification($request)
    {
        $messages = [
            "highest_nursing_degree.required" => "Select highest nursing degree",
            "ehr_proficiency_cerner.required" => "Select Cerner",
            "ehr_proficiency_meditech.required" => "Select Meditech",
            "ehr_proficiency_epic.required" => "Select Epic",
            "ehr_proficiency_other.regex" => "Other ehr proficiency not valid",
            "college_uni_name.required" => "Please add college / university name",
            "college_uni_name.regex" => "College / university name not valid",
            "college_uni_name.min" => "College / university name is short",
            "college_uni_name.max" => "College / university name too long",
            "college_uni_city.required" => "Please add city",
            "college_uni_city.regex" => "City not valid",
            "college_uni_city.min" => "City is short",
            "college_uni_city.max" => "City is too long",
            "college_uni_country.required" => "Please add country",
            "college_uni_country.regex" => "Country not valid",
            "college_uni_country.min" => "Country is short",
            "college_uni_country.max" => "Country is too long",
            "experience_as_acute_care_facility.regex" => "Enter valid acute care facility experience",
            "experience_as_ambulatory_care_facility.regex" => "Enter valid non-acute care nursing experience",
        ];

        $this->validate($request, [
            'highest_nursing_degree' => 'required',
            'college_uni_name' => 'required|regex:/^[a-zA-Z 0-9,\-\/]+$/|min:1|max:255',
            'college_uni_city' => 'required|regex:/^[a-zA-Z ]+$/|min:2|max:50',
            'college_uni_state' => 'required',
            'college_uni_country' => 'required|regex:/^[a-zA-Z ]+$/|min:3|max:10',
            'experience_as_acute_care_facility' => 'nullable|regex:/^[0-9.\+]+$/|max:5',
            'experience_as_ambulatory_care_facility' => 'nullable|regex:/^[0-9.\+]+$/|max:5',
            'ehr_proficiency_cerner' => 'required',
            'ehr_proficiency_meditech' => 'required',
            'ehr_proficiency_epic' => 'required',
            'ehr_proficiency_other' => 'nullable|regex:/^[a-zA-Z 0-9]+$/|min:2|max:50',
        ], $messages);
    }

    private function performValidationRoleInterest($request)
    {
        $messages = [
            "additional_pictures.max" => "Additional Photos can't be more than 4.",
            "additional_files.max" => "Additional Files can't be more than 4.",
            "additional_pictures.*.mimes" => "Additional Photos should be image or png jpg",
            "additional_files.*.mimes" => "Additional Files should be doc or pdf",
            "additional_pictures.*.max" => "Additional Photos should not be more than 5mb",
            "additional_files.*.max" => "Additional Files should not be more than 1mb",
            "leadership_roles.required_if" => "Please select leadership role",
            "nu_video.url" => "YouTube and Vimeo should be a valid link",
            "nu_video.max" => "YouTube and Vimeo should be a valid link"
        ];

        $this->validate($request, [
            'additional_pictures' => 'max:4',
            'additional_pictures.*' => 'nullable|max:5120|image|mimes:jpeg,png,jpg',
            'serving_preceptor' => 'boolean',
            'serving_interim_nurse_leader' => 'boolean',
            'leadership_roles' => 'required_if:serving_interim_nurse_leader,1',
            'clinical_educator' => 'boolean',
            'is_daisy_award_winner' => 'boolean',
            'employee_of_the_mth_qtr_yr' => 'boolean',
            'other_nursing_awards' => 'boolean',
            'is_professional_practice_council' => 'boolean',
            'is_research_publications' => 'boolean',
            'additional_files' => 'max:4',
            'additional_files.*' => 'nullable|max:1024|mimes:pdf,doc,docx',
            'nu_video' => 'nullable|url|max:255',
        ], $messages);
    }

    public function testEmail()
    {
        $user = Auth::user();
        $full_name = $user->first_name . ' ' . $user->last_name;
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
