<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Nurse;
use App\Models\Availability;
use App\Models\Certification;
use App\Models\Experience;
use App\Models\Job;
use App\Enums\Role;
use App\Enums\State;
use App\Models\Offer;
use App\Models\NurseAsset;
use App\Models\Follows;
use App\Models\FacilityFollows;
use App\Models\Facility;
use App\Models\FacilityRating;
use App\Models\States;
use App\Models\Cities;
use App\Models\JobAsset;
use App\Models\JobOffer;
use App\Models\NurseRating;
use App\Models\EmailTemplate;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Providers\AppServiceProvider;

class ApiController extends Controller
{
    /**
     * Class constructor.
     */
    private $check;
    private $message;
    private $return_data;

    use SendsPasswordResetEmails;

    public function __construct()
    {
        $this->check = "0";
        $this->return_data = (object) array();
        $this->message = "User data";
        $this->param_missing = "Required parameters not found";
        $this->invalid_request  = "Invalid request method";
    }

    public function generate_token()
    {
        return hash_hmac('sha256', Str::random(40) . time(), config('app.key'));
    }

    public function timeAgo($time = NULL)
    {
        // Calculate difference between current
        // time and given timestamp in seconds
        $diff     = time() - $time;
        // Time difference in seconds
        $sec     = $diff;
        // Convert time difference in minutes
        $min     = round($diff / 60);
        // Convert time difference in hours
        $hrs     = round($diff / 3600);
        // Convert time difference in days
        $days     = round($diff / 86400);
        // Convert time difference in weeks
        $weeks     = round($diff / 604800);
        // Convert time difference in months
        $mnths     = round($diff / 2600640);
        // Convert time difference in years
        $yrs     = round($diff / 31207680);
        // Check for seconds
        if ($sec <= 60) {
            $string = "$sec seconds ago";
        }
        // Check for minutes
        else if ($min <= 60) {
            if ($min == 1) {
                $string = "one minute ago";
            } else {
                $string = "$min minutes ago";
            }
        }
        // Check for hours
        else if ($hrs <= 24) {
            if ($hrs == 1) {
                $string = "an hour ago";
            } else {
                $string = "$hrs hours ago";
            }
        }
        // Check for days
        else if ($days <= 7) {
            if ($days == 1) {
                $string = "Yesterday";
            } else {
                $string = "$days days ago";
            }
        }
        // Check for weeks
        else if ($weeks <= 4.3) {
            if ($weeks == 1) {
                $string = "a week ago";
            } else {
                $string = "$weeks weeks ago";
            }
        }
        // Check for months
        else if ($mnths <= 12) {
            if ($mnths == 1) {
                $string = "a month ago";
            } else {
                $string = "$mnths months ago";
            }
        }
        // Check for years
        else {
            if ($yrs == 1) {
                $string = "one year ago";
            } else {
                $string = "$yrs years ago";
            }
        }
        return $string;
    }

    public function getSpecialities()
    {
        $controller = new Controller();
        $specialties = $controller->getSpecialities()->pluck('title', 'id');
        $spl = [];
        if (!empty($specialties)) {
            foreach ($specialties as $key => $val) {
                $spl[] = ['id' => $key, 'name' => $val];
            }
        }
        $this->check = "1";
        $this->message = "Specialities has been listed successfully";
        $this->return_data = $spl;

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function getGeographicPreferences()
    {
        $controller = new Controller();
        $workLocations = $controller->getGeographicPreferences()->pluck('title', 'id');
        $work_location = [];
        if (!empty($workLocations)) {
            foreach ($workLocations as $key => $val) {
                $work_location[] = ['id' => $key, 'name' => $val];
            }
        }
        $this->check = "1";
        $this->message = "Work location's has been listed successfully";
        $this->return_data = $work_location;

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function stateList()
    {
        /* $this->return_data = $this->getStateOptions(); */
        $ret = [];
        foreach (State::getKeys() as $key => $value) {
            $ret[]['state'] = $value;
        }
        $this->check = "1";
        $this->message = "State's has been listed successfully";
        $this->return_data = $ret;
        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function shiftDuration()
    {
        $shifts = $this->getShifts()->pluck('title', 'id');
        $this->check = "1";
        $this->message = "Shift duration has been listed successfully";
        $data = [];
        foreach ($shifts as $key => $value) {
            $data[] = ['id' => $key, "name" => $value];
        }
        asort($data);
        $data1 = [];
        foreach ($data as $key1 => $value1) {
            $data1[] = ['id' => $value1['id'], "name" => $value1['name']];
        }

        $this->return_data = $data1;
        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function assignmentDurations()
    {
        $assignmentDurations = $this->getAssignmentDurations()->pluck('title', 'id');
        $data = [];
        foreach ($assignmentDurations as $key => $value) {
            $data[] = ['id' => $key, "name" => $value];
        }
        $this->check = "1";
        $this->message = "Assignment duration's has been listed successfully";
        $this->return_data = $data;
        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function preferredShifts()
    {
        $preferredShifts = $this->getPreferredShift()->pluck('title', 'id');
        $data = [];
        foreach ($preferredShifts as $key => $value) {
            $data[] = ['id' => $key, "name" => $value];
        }
        $this->check = "1";
        $this->message = "Preferred shift's has been listed successfully";
        $this->return_data = $data;
        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function getWeekDay()
    {
        $weekDays = $this->getWeekDayOptions();
        $data = [];
        foreach ($weekDays as $key => $value) {
            $data[] = ['id' => $key, "name" => $value];
        }
        $this->check = "1";
        $this->message = "Week day's has been listed successfully";
        $this->return_data = $data;
        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function login(Request $request)
    {
        if (isset($request->email) && $request->email != "" && isset($request->password) && $request->password != "" && isset($request->fcm_token) && $request->fcm_token != "") {
            if (Auth::attempt(['email' => $request->email, 'password' => $request->password, 'active' => true])) {
                $return_data = [];
                $user_data = User::where('email', '=', $request->email)->get()->first();
                if (!empty($user_data) && $user_data != null) {
                    $user_data->fcm_token = $request->fcm_token;
                    if ($user_data->update()) {
                        $user = User::where('id', '=', $user_data->id)->get()->first();
                        if (isset($user->role) && $user->role == "NURSE") {
                            $return_data = $this->profileCompletionFlagStatus($type = "login", $user);
                        } else {
                            $return_data = $this->facilityProfileCompletionFlagStatus($type = "login", $user);
                        }
                        $this->check = "1";
                        $this->message = "Logged in successfully";
                    } else $this->message = "Problem occurred while updating the token, Please try again later";
                } else $this->message = "User record not found";

                $this->return_data = $return_data;
            } else {
                $this->message = "Invalid email or password";
            }
        } else {
            $this->message = $this->param_missing;
        }

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function sendResetLinkEmail(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            // We will send the password reset link to this user. Once we have attempted
            // to send the link, we will examine the response then see the message we
            // need to show to the user. Finally, we'll send out a proper response.

            $check_user = User::where(['email' => $request->email]);
            if ($check_user->count() > 0) {
                $user = $check_user->first();

                $temp = EmailTemplate::where(['slug' => 'nurse_reset_password']);
                if ($temp->count() > 0) {
                    $t = $temp->first();
                    $data = [
                        'to_email' => $user->email,
                        'to_name' => $user->first_name . ' ' . $user->last_name
                    ];
                    $token = $this->generate_token();
                    $replace_array = ['###RESETLINK###' => url('password/reset', $token)];
                    $this->basic_email($template = "nurse_reset_password", $data, $replace_array);
                }
                $this->check = "1";
                $this->message = "Reset password link sent successfully";
            } else {
                $this->message = "User not found";
            }
        }

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function register(Request $request)
    {
        if (
            isset($request->first_name) && $request->first_name != "" &&
            isset($request->last_name) && $request->last_name != "" &&
            isset($request->email) && $request->email != "" &&
            isset($request->mobile) && $request->mobile != "" &&
            isset($request->password) && $request->password != "" &&
            isset($request->nursing_license_state) && $request->nursing_license_state != "" &&
            isset($request->nursing_license_number) && $request->nursing_license_number != "" &&
            isset($request->specialty) && $request->specialty != "" &&
            isset($request->work_location) && $request->work_location != "" &&
            isset($request->fcm_token) && $request->fcm_token != ""
        ) {
            $user_data = User::where('email', '=', $request->email)->first();
            if ($user_data === null) {
                $user = User::create([
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'mobile' => $request->mobile,
                    'email' => $request->email,
                    'user_name' => $request->email,
                    'password' => Hash::make($request->password),
                    'role' => Role::getKey(Role::NURSE),
                    'fcm_token' => $request->fcm_token
                ]);
                $nurse = Nurse::create([
                    'user_id' => $user->id,
                    'nursing_license_state' => $request->nursing_license_state,
                    'nursing_license_number' => $request->nursing_license_number,
                    'specialty' => $request->specialty,
                ]);
                $availability = Availability::create([
                    'nurse_id' => $nurse->id,
                    'work_location' => $request->work_location,
                ]);
                $user->assignRole('Nurse');

                $reg_user = User::where('email', '=', $request->email)->get()->first();

                /* mail */
                $data = [
                    'to_email' => $reg_user->email,
                    'to_name' => $reg_user->first_name . ' ' . $reg_user->last_name
                ];
                $replace_array = ['###USERNAME###' => $reg_user->first_name . ' ' . $reg_user->last_name];
                $this->basic_email($template = "new_registration", $data, $replace_array);
                /* mail */

                $return_data = $this->profileCompletionFlagStatus($type = "login", $reg_user);
                $this->check = "1";
                $this->message = "Your account has been registered successfully";
                $this->return_data = $return_data;
                // if ($_SERVER['HTTP_HOST'] != "localhost" || $_SERVER['HTTP_HOST'] != "127.0.0.1:8000") $this->sendNotifyEmail($user);
            } else {
                $this->message = "Your account is already created please login..!";
            }
        } else {
            $this->message = $this->param_missing;
        }

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function profileCompletionFlagStatus($type = "", $user)
    {
        $controller = new Controller();
        $specialties = $controller->getSpecialities()->pluck('title', 'id');
        $assignmentDurations = $this->getAssignmentDurations()->pluck('title', 'id');
        $shifts = $this->getShifts()->pluck('title', 'id');
        $workLocations = $controller->getGeographicPreferences()->pluck('title', 'id');
        $leadershipRoles = $this->getLeadershipRoles()->pluck('title', 'id');
        $seniorityLevels = $this->getSeniorityLevel()->pluck('title', 'id');
        $jobFunctions = $this->getJobFunction()->pluck('title', 'id');
        $ehrProficienciesExp = $this->getEHRProficiencyExp()->pluck('title', 'id');
        $weekDays = $this->getWeekDayOptions();
        $nursingDegrees = $this->getNursingDegrees()->pluck('title', 'id');
        $certifications = $this->getCertifications()->pluck('title', 'id');
        $preferredShifts = $this->getPreferredShift()->pluck('title', 'id');

        $nurse = Nurse::where('user_id', '=', $user->id)->get()->first();
        $availability = Availability::where('nurse_id', '=', $nurse->id)->get()->first();
        /* profile status flag */
        $profile_detail_flag = "0";
        if (
            (isset($user->first_name) && $user->first_name != "") &&
            (isset($user->last_name) && $user->last_name != "") &&
            (isset($user->email) && $user->email != "") &&
            (isset($user->mobile) && $user->mobile != "") &&
            (isset($nurse->nursing_license_state) && $nurse->nursing_license_state != "") &&
            (isset($nurse->nursing_license_number) && $nurse->nursing_license_number != "") &&
            (isset($nurse->specialty) && $nurse->specialty != "") &&
            (isset($availability->work_location) && $availability->work_location != "") &&
            (isset($nurse->address) && $nurse->address != "") &&
            (isset($nurse->city) && $nurse->city != "") &&
            (isset($nurse->state) && $nurse->state != "") &&
            (isset($nurse->postcode) && $nurse->postcode != "") &&
            (isset($nurse->country) && $nurse->country != "")
        ) $profile_detail_flag = "1";
        /* profile status flag */

        /* Hourly rate and availability */
        $hourly_rate_and_availability = "0";
        if ((isset($nurse->hourly_pay_rate) && $nurse->hourly_pay_rate != "") &&
            (isset($availability->shift_duration) && $availability->shift_duration != "") &&
            (isset($availability->assignment_duration) && $availability->assignment_duration != "") &&
            (isset($availability->preferred_shift) && $availability->preferred_shift != "") &&
            (isset($availability->days_of_the_week) && $availability->days_of_the_week != "") &&
            (isset($availability->earliest_start_date) && $availability->earliest_start_date != "")
        ) $hourly_rate_and_availability = "1";
        /* Hourly rate and availability */

        $return_data['id'] = (isset($user->id) && $user->id != "") ? $user->id : "";
        $return_data['nurse_id'] = (isset($nurse->id) && $nurse->id != "") ? $nurse->id : "";
        $return_data['role'] = (isset($user->role) && $user->role != "") ? $user->role : "";
        $return_data['fcm_token'] = (isset($user->fcm_token) && $user->fcm_token != "") ? $user->fcm_token : "";
        $return_data['fullName'] = (isset($user->fullName) && $user->fullName != "") ? $user->fullName : "";
        $return_data['date_of_birth'] = (isset($user->date_of_birth) && $user->date_of_birth != "") ? $user->date_of_birth : "";
        $return_data['email_notification'] = (isset($user->email_notification) && $user->email_notification != "") ? strval($user->email_notification) : "";
        $return_data['sms_notification'] = (isset($user->sms_notification) && $user->sms_notification != "") ? strval($user->sms_notification) : "";

        $return_data['first_name'] = (isset($user->first_name) && $user->first_name != "") ? $user->first_name : "";
        $return_data['last_name'] = (isset($user->last_name) && $user->last_name != "") ? $user->last_name : "";
        $return_data['email'] = (isset($user->email) && $user->email != "") ? $user->email : "";

        $return_data['image'] = (isset($user->image) && $user->image != "") ? url("storage/assets/nurses/profile/" . $user->image) : "";

        $profileNurse = \Illuminate\Support\Facades\Storage::get('assets/nurses/8810d9fb-c8f4-458c-85ef-d3674e2c540a');
        if ($user->image) {
            $t = \Illuminate\Support\Facades\Storage::exists('assets/nurses/profile/' . $user->image);
            if ($t) {
                $profileNurse = \Illuminate\Support\Facades\Storage::get('assets/nurses/profile/' . $user->image);
            }
        }
        $return_data["image_base"] = 'data:image/jpeg;base64,' . base64_encode($profileNurse);

        $return_data['mobile'] = (isset($user->mobile) && $user->mobile != "") ? $user->mobile : "";
        $return_data['nursing_license_state'] = (isset($nurse->nursing_license_state) && $nurse->nursing_license_state != "") ? $nurse->nursing_license_state : "";
        $return_data['nursing_license_number'] = (isset($nurse->nursing_license_number) && $nurse->nursing_license_number != "") ? $nurse->nursing_license_number : "";
        $return_data['specialty'] = $spl = [];
        if (isset($nurse->specialty) && $nurse->specialty != "") {
            $specialty_array = explode(",", $nurse->specialty);
            if (is_array($specialty_array)) {
                foreach ($specialty_array as $key => $spl_id) {
                    $spl_name = (isset($specialties[$spl_id])) ? $specialties[$spl_id] : "";
                    $spl[] = ['id' => $spl_id, 'name' => $spl_name];
                }
            }
            $return_data['specialty'] = $spl;
        }
        $return_data['work_location'] = (isset($availability->work_location) && $availability->work_location != "") ? $availability->work_location : "";
        $return_data['work_location_definition'] = isset($workLocations[strval($availability->work_location)]) ? $workLocations[strval($availability->work_location)] : "";
        $return_data['address'] = (isset($nurse->address) && $nurse->address != "") ? $nurse->address : "";
        $return_data['city'] = (isset($nurse->city) && $nurse->city != "") ? $nurse->city : "";
        $return_data['state'] = (isset($nurse->state) && $nurse->state != "") ? $nurse->state : "";
        $return_data['postcode'] = (isset($nurse->postcode) && $nurse->postcode != "") ? $nurse->postcode : "";
        $return_data['country'] = (isset($nurse->country) && $nurse->country != "") ? $nurse->country : "";
        $return_data['hourly_pay_rate'] = (isset($nurse->hourly_pay_rate) && $nurse->hourly_pay_rate != "") ? strval($nurse->hourly_pay_rate) : "";
        $return_data['shift_duration'] = (isset($availability->shift_duration) && $availability->shift_duration != "") ? strval($availability->shift_duration) : "";
        $return_data['shift_duration_definition'] = (isset($shifts[$availability->shift_duration]) && $shifts[$availability->shift_duration] != "") ? $shifts[strval($availability->shift_duration)] : "";
        $return_data['assignment_duration'] = (isset($availability->assignment_duration) && $availability->assignment_duration != "") ? strval($availability->shift_duration) : "";
        $return_data['assignment_duration_definition'] = (isset($assignmentDurations[$availability->assignment_duration]) && $assignmentDurations[$availability->assignment_duration] != "") ? $assignmentDurations[strval($availability->assignment_duration)] : "";
        $return_data['preferred_shift'] = (isset($availability->preferred_shift) && $availability->preferred_shift != "") ? strval($availability->preferred_shift) : "";
        $return_data['preferred_shift_definition'] = (isset($preferredShifts[$availability->preferred_shift]) &&  $preferredShifts[$availability->preferred_shift] != "") ?  $preferredShifts[$availability->preferred_shift] : "";
        $return_data['days_of_the_week'] = [];
        if ($availability->days_of_the_week != "") $return_data['days_of_the_week'] = explode(",", $availability->days_of_the_week);
        $return_data['earliest_start_date'] = (isset($availability->earliest_start_date) && $availability->earliest_start_date != "") ? date('m/d/Y', strtotime($availability->earliest_start_date)) : "";

        $return_data['profile_detail_flag'] = $profile_detail_flag;
        $return_data['hourly_rate_and_avail_flag'] = $hourly_rate_and_availability;

        /* experience */
        $experience["highest_nursing_degree"] = (isset($nurse->highest_nursing_degree) && $nurse->highest_nursing_degree != "") ? strval($nurse->highest_nursing_degree) : "";
        $experience["highest_nursing_degree_definition"] = (isset($nursingDegrees[$nurse->highest_nursing_degree]) && $nursingDegrees[$nurse->highest_nursing_degree] != "") ? $nursingDegrees[$nurse->highest_nursing_degree] : "";
        $experience["college_uni_name"] = (isset($nurse->college_uni_name) && $nurse->college_uni_name != "") ? $nurse->college_uni_name : "";
        $experience["college_uni_city"] = (isset($nurse->college_uni_city) && $nurse->college_uni_city != "") ? $nurse->college_uni_city : "";
        $experience["college_uni_state"] = (isset($nurse->college_uni_state) && $nurse->college_uni_state != "") ? $nurse->college_uni_state : "";
        $experience["college_uni_country"] = (isset($nurse->college_uni_country) && $nurse->college_uni_country != "") ? $nurse->college_uni_country : "";
        $experience["experience_as_acute_care_facility"] = (isset($nurse->experience_as_acute_care_facility) && $nurse->experience_as_acute_care_facility != "") ? $nurse->experience_as_acute_care_facility : "";
        $experience["experience_as_ambulatory_care_facility"] = (isset($nurse->experience_as_ambulatory_care_facility) && $nurse->experience_as_ambulatory_care_facility != "") ? $nurse->experience_as_ambulatory_care_facility : "";

        $experience["ehr_proficiency_cerner"] = (isset($nurse->ehr_proficiency_cerner) && $nurse->ehr_proficiency_cerner != "") ? strval($nurse->ehr_proficiency_cerner) : "";
        $experience["ehr_proficiency_cerner_definition"] = (isset($ehrProficienciesExp[$nurse->ehr_proficiency_cerner]) && $ehrProficienciesExp[$nurse->ehr_proficiency_cerner] != "") ? $ehrProficienciesExp[$nurse->ehr_proficiency_cerner] : "";
        $experience["ehr_proficiency_meditech"] = (isset($nurse->ehr_proficiency_meditech) && $nurse->ehr_proficiency_meditech != "") ? strval($nurse->ehr_proficiency_meditech) : "";
        $experience["ehr_proficiency_meditech_definition"] = (isset($ehrProficienciesExp[$nurse->ehr_proficiency_meditech]) && $ehrProficienciesExp[$nurse->ehr_proficiency_meditech] != "") ? $ehrProficienciesExp[$nurse->ehr_proficiency_meditech] : "";
        $experience["ehr_proficiency_epic"] = (isset($nurse->ehr_proficiency_epic) && $nurse->ehr_proficiency_epic != "") ? strval($nurse->ehr_proficiency_epic) : "";
        $experience["ehr_proficiency_epic_definition"] = (isset($ehrProficienciesExp[$nurse->ehr_proficiency_epic]) && $ehrProficienciesExp[$nurse->ehr_proficiency_epic] != "") ? $ehrProficienciesExp[$nurse->ehr_proficiency_epic] : "";

        $experience["ehr_proficiency_other"] = (isset($nurse->ehr_proficiency_other) && $nurse->ehr_proficiency_other != "") ? $nurse->ehr_proficiency_other : "";
        $return_data['experience'] = $experience;
        /* experience */

        /* certitficate */
        $certitficate = [];
        $cert = Certification::where(['nurse_id' => $nurse->id])->whereNull('deleted_at')->get();
        if ($cert->count() > 0) {
            $c = $cert;
            foreach ($c as $key => $v) {
                // if ($v->deleted_at != "") {
                $crt_data['certificate_id'] = (isset($v->id) && $v->id != "") ? $v->id : "";
                $crt_data['search_for_credential'] = (isset($v->type) && $v->type != "") ? $v->type : "";
                $crt_data['search_for_credential_definition'] = (isset($certifications[$v->type]) && $certifications[$v->type] != "") ? $certifications[$v->type] : "";
                // $crt_data['license_number'] = (isset($v->license_number) && $v->license_number != "") ? $v->license_number : "";
                $crt_data['effective_date'] = (isset($v->effective_date) && $v->effective_date != "") ? date('m/d/Y', strtotime($v->effective_date)) : "";
                $crt_data['expiration_date'] = (isset($v->expiration_date) && $v->expiration_date != "") ? date('m/d/Y', strtotime($v->expiration_date)) : "";
                $crt_data['certificate_image'] = (isset($v->certificate_image) && $v->certificate_image != "") ? url('storage/assets/nurses/certifications/' . $nurse->id . '/' . $v->certificate_image) : "";

                $certificate_image_base = "";
                if ($v->certificate_image) {
                    $t = \Illuminate\Support\Facades\Storage::exists('assets/nurses/certifications/' . $v->certificate_image);
                    if ($t) {
                        $facility_logo = \Illuminate\Support\Facades\Storage::get('assets/nurses/certifications/' . $v->certificate_image);
                    }
                }
                $crt_data['certificate_image_base'] = ($certificate_image_base != "") ? 'data:image/jpeg;base64,' . base64_encode($certificate_image_base) : "";


                // $crt_data['active'] = (isset($v->active) && $v->active != "") ? $v->active : "";
                $crt_data['deleted_at'] = (isset($v->deleted_at) && $v->deleted_at != "") ? $v->deleted_at : "";
                /*   $crt_data['created_at'] = (isset($v->created_at) && $v->created_at != "") ? $v->created_at : "";
                    $crt_data['updated_at'] = (isset($v->updated_at) && $v->updated_at != "") ? $v->updated_at : ""; */
                $certitficate[] = $crt_data;
                // }
            }
        }
        $return_data['certitficate'] = $certitficate;
        $return_data['resume'] = (isset($nurse->resume) && $nurse->resume != "") ? url('storage/assets/nurses/resumes/' . $nurse->id . '/' . $nurse->resume) : "";
        /* certitficate */


        /* role interest */
        $optyesno = ['1' => "Yes", '0' => "No"];

        $role_interest["nu_video_embed_url"] = (isset($nurse->nu_video_embed_url) && $nurse->nu_video_embed_url != "") ? $nurse->nu_video_embed_url : "";
        $role_interest["nu_video_embed_url_definition"] = (isset($optyesno[$nurse->nu_video_embed_url]) && $optyesno[$nurse->nu_video_embed_url] != "") ? $optyesno[$nurse->nu_video_embed_url] : "";
        $role_interest['serving_preceptor'] = (isset($nurse->serving_preceptor)) ? strval($nurse->serving_preceptor) : "";
        $role_interest['serving_preceptor_definition'] = (isset($optyesno[$nurse->serving_preceptor]) && $optyesno[$nurse->serving_preceptor] != "") ? $optyesno[$nurse->serving_preceptor] : "";
        $role_interest['serving_interim_nurse_leader'] = (isset($nurse->serving_interim_nurse_leader)) ? strval($nurse->serving_interim_nurse_leader) : "";
        $role_interest['serving_interim_nurse_leader_definition'] = (isset($optyesno[$nurse->serving_interim_nurse_leader]) && $optyesno[$nurse->serving_interim_nurse_leader] != "") ? $optyesno[$nurse->serving_interim_nurse_leader] : "";
        $role_interest['clinical_educator'] = (isset($nurse->clinical_educator)) ? strval($nurse->clinical_educator) : "";
        $role_interest['clinical_educator_definition'] = (isset($optyesno[$nurse->clinical_educator]) && $optyesno[$nurse->clinical_educator] != "") ? $optyesno[$nurse->clinical_educator] : "";
        $role_interest['is_daisy_award_winner'] = (isset($nurse->is_daisy_award_winner)) ? strval($nurse->is_daisy_award_winner) : "";
        $role_interest['is_daisy_award_winner_definition'] = (isset($optyesno[$nurse->is_daisy_award_winner]) && $optyesno[$nurse->is_daisy_award_winner] != "") ? $optyesno[$nurse->is_daisy_award_winner] : "";
        $role_interest['employee_of_the_mth_qtr_yr'] = (isset($nurse->employee_of_the_mth_qtr_yr)) ? strval($nurse->employee_of_the_mth_qtr_yr) : "";
        $role_interest['employee_of_the_mth_qtr_yr_definition'] = (isset($optyesno[$nurse->employee_of_the_mth_qtr_yr]) && $optyesno[$nurse->employee_of_the_mth_qtr_yr] != "") ? $optyesno[$nurse->employee_of_the_mth_qtr_yr] : "";
        $role_interest['other_nursing_awards'] = (isset($nurse->other_nursing_awards)) ? strval($nurse->other_nursing_awards) : "";
        $role_interest['other_nursing_awards_definition'] = (isset($optyesno[$nurse->other_nursing_awards]) && $optyesno[$nurse->other_nursing_awards] != "") ? $optyesno[$nurse->other_nursing_awards] : "";
        $role_interest['is_professional_practice_council'] = (isset($nurse->is_professional_practice_council)) ? strval($nurse->is_professional_practice_council) : "";
        $role_interest['is_professional_practice_council_definition'] = (isset($optyesno[$nurse->is_professional_practice_council]) && $optyesno[$nurse->is_professional_practice_council] != "") ? $optyesno[$nurse->is_professional_practice_council] : "";
        $role_interest['is_research_publications'] = (isset($nurse->is_research_publications)) ? strval($nurse->is_research_publications) : "";
        $role_interest['is_research_publications_definition'] = (isset($optyesno[$nurse->is_research_publications]) && $optyesno[$nurse->is_research_publications] != "") ? $optyesno[$nurse->is_research_publications] : "";
        $role_interest['leadership_roles'] = (isset($nurse->leadership_roles) && $nurse->leadership_roles != "") ? strval($nurse->leadership_roles) : "";
        $role_interest['leadership_roles_definition'] = (isset($leadershipRoles[$nurse->leadership_roles]) && $leadershipRoles[$nurse->leadership_roles] != "") ? $leadershipRoles[$nurse->leadership_roles] : "";

        $role_interest['summary'] = (isset($nurse->summary) && $nurse->summary != "") ? $nurse->summary : "";
        $role_interest['languages'] = (isset($nurse->languages) && $nurse->languages != "") ? explode(",", $nurse->languages) : "";

        /* nurse assets */
        $role_interest['additional_pictures'] = $role_interest['additional_files'] = [];
        $nurse_assets = NurseAsset::where(['nurse_id' => $nurse->id, 'active' => '1'])->get();

        if ($nurse_assets->count() > 0) {
            foreach ($nurse_assets as $nac_ => $na) {
                if ($na->filter == "additional_photos") $role_interest['additional_pictures'][] = ['asset_id' => $na->id, 'photo' => url('storage/assets/nurses/additional_photos/' . $nurse->id . '/' . $na->name)];
                else $role_interest['additional_files'][] = ['asset_id' => $na->id, 'photo' => url('storage/assets/nurses/additional_files/' . $nurse->id . '/' . $na->name)];
            }
        }
        /* nurse assets */
        $return_data['role_interest'] = $role_interest;
        /* role interest */

        return $return_data;
    }

    public function personalDetail(Request $request)
    {
        $user = User::where('id', $request->id)->first();
        $nurse = Nurse::where('user_id', $request->id)->first();
        $validator = \Validator::make($request->all(), [
            'id' => 'required',
            /* 'image' => 'nullable|max:1024|image|mimes:jpeg,png,jpg', */
            'first_name' => 'required|regex:/^[a-zA-Z]+$/|min:3|max:100',
            'last_name' => 'required|regex:/^[a-zA-Z]+$/|min:3|max:100',
            'mobile' => 'required|regex:/^[0-9 \+]+$/|min:4|max:20',
            'email' => $this->emailRegEx($user),
            'nursing_license_state' => 'required|regex:/^[a-zA-Z ]+$/|min:2|max:50',
            'nursing_license_number' => 'nullable|regex:/^[a-zA-Z0-9]+$/|min:2|max:50',
            'specialty' => 'required',
            'address' => 'required|regex:/^[a-zA-Z 0-9,\-\/]+$/|min:1|max:150',
            'city' => 'required|regex:/^[a-zA-Z ]+$/|min:2|max:50',
            'state' => 'required',
            'postcode' => 'required|regex:/^[a-zA-Z0-9]+$/|min:3|max:10',
            'country' => 'required|regex:/^[a-zA-Z ]+$/|min:3',
        ]);
        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            $return_data = [];
            if ($user) {
                /* User */
                if (isset($request->first_name) && $request->first_name != "") $user->first_name = $request->first_name;
                if (isset($request->last_name) && $request->last_name != "") $user->last_name = $request->last_name;
                if (isset($request->email) && $request->email != "") $user->email = $request->email;
                if (isset($request->mobile) && $request->mobile != "") $user->mobile = $request->mobile;
                if ($request->hasFile('profile_image') && $request->file('profile_image') != null) {
                    $request->file('profile_image')->storeAs('assets/nurses/profile', $nurse->id);
                    $user->image = $nurse->id;
                }
                $u = $user->update();
                /* User */

                /*  Nurse */
                if (isset($request->specialty) && $request->specialty != "") $nurse->specialty = $request->specialty;
                if (isset($request->address) && $request->address != "") $nurse->address = $request->address;
                if (isset($request->city) && $request->city != "") $nurse->city = $request->city;
                if (isset($request->state) && $request->state != "") $nurse->state = $request->state;
                if (isset($request->postcode) && $request->postcode != "") $nurse->postcode = $request->postcode;
                if (isset($request->country) && $request->country != "") $nurse->country = $request->country;
                if (isset($request->nursing_license_number) && $request->nursing_license_number != "") $nurse->nursing_license_number = $request->nursing_license_number;
                $n = $nurse->update();
                /*  Nurse */

                if ($u || $n) {
                    $this->check = "1";
                    $return_data = $this->profileCompletionFlagStatus($type = "", $user);
                    $this->message = "Personal detail updated successfully";
                } else {
                    $this->message = "Problem occurred while updating the profile detail, Please try again later";
                }
            } else {
                $this->message = "User not exists";
            }
            $this->return_data = $return_data;
        }

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function availability(Request $request)
    {
        $messages = [
            "id.required" => "Id is required",
            "shift_duration.required" => "Select shift duration",
            "assignment_duration.required" => "Select assignment duration",
            "preferred_shift.required" => "Select preferred shift",
            "days_of_the_week.required" => "Select preferred days of the week",
            "earliest_start_date.date" => "Earliest start date is not valid date",
            "work_location.required" => "Select work location",
        ];

        $validator = \Validator::make($request->all(), [
            'id' => 'required',
            'hourly_pay_rate' => 'required|regex:/^[0-9]+$/|min:1|max:3',
            'shift_duration' => 'required',
            'assignment_duration' => 'required',
            'preferred_shift' => 'required',
            'days_of_the_week' => 'required',
            'earliest_start_date' => 'nullable|date|after_or_equal:now',
            'work_location' => 'required',
        ], $messages);

        $user_data = User::where('id', '=', $request->id)->first();
        if ($user_data != null) {
            if ($validator->fails()) {
                $this->message = $validator->errors()->first();
            } else {
                /* nurse */
                $nurse = Nurse::where('user_id', '=', $request->id)->get()->first();
                if (isset($request->hourly_pay_rate) && $request->hourly_pay_rate != "") {
                    $tmpRate =  $request->hourly_pay_rate * 25 / 100;
                    $facility_hourly_pay_rate = $request->hourly_pay_rate + $tmpRate;
                    $nurse->__set('facility_hourly_pay_rate', $facility_hourly_pay_rate);
                }
                $nurse->hourly_pay_rate = $request->hourly_pay_rate;
                $n = $nurse->update();
                /* nurse */

                /* availability */
                $availability = Availability::where('nurse_id', '=', $nurse->id)->get()->first();
                if (isset($request->shift_duration) && $request->shift_duration != "") $availability->shift_duration = $request->shift_duration;
                if (isset($request->preferred_shift) && $request->preferred_shift != "") $availability->preferred_shift = $request->preferred_shift;
                if (isset($request->days_of_the_week) && $request->days_of_the_week != "") $availability->days_of_the_week = $request->days_of_the_week;
                if (isset($request->assignment_duration) && $request->assignment_duration != "") $availability->assignment_duration = $request->assignment_duration;
                if (isset($request->earliest_start_date) && $request->earliest_start_date != "") $availability->earliest_start_date = $request->earliest_start_date;
                if (isset($request->work_location) && $request->work_location != "") $availability->work_location = $request->work_location;
                $a = $availability->update();
                /* availability */
                // Hourly Rate & Availability Updated

                if ($a || $n) {
                    $this->check = "1";
                    $this->message = "Hourly rate & availability updated successfully";
                    $this->return_data = $this->profileCompletionFlagStatus($type = "", $user_data);
                } else {
                    $this->message = "Problem occurred while updating the profile detail, Please try again later";
                }
            }
        } else {
            $this->message = "User not exists";
        }

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function sendNotifyEmail($user)
    {
        Mail::send(
            new RegistrationMailable(
                $user->first_name,
                $user->last_name,
                $user->email
            )
        );
    }

    public function facilityTypes()
    {
        $facilityTypes = $this->getFacilityType()->pluck('title', 'id');
        $data = [];
        foreach ($facilityTypes as $key => $value) {
            $data[] = ['id' => $key, "name" => $value];
        }
        $this->check = "1";
        $this->message = "facility type's has been listed successfully";
        $this->return_data = $data;
        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function NursingDegrees()
    {
        $nursingDegrees = $this->getNursingDegrees()->pluck('title', 'id');
        $data = [];
        foreach ($nursingDegrees as $key => $value) {
            $data[] = ['id' => $key, "name" => $value];
        }
        $this->check = "1";
        $this->message = "Nursing degree's has been listed successfully";
        $this->return_data = $data;
        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function searchForCredentialsOptions()
    {
        $certifications = $this->getCertifications()->pluck('title', 'id');
        $data = [];
        foreach ($certifications as $key => $value) {
            $data[] = ['id' => $key, "name" => $value];
        }
        $this->check = "1";
        $this->message = "Search for credentials has been listed successfully";
        $this->return_data = $data;
        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function getMediaOptions(Nurse $nurse)
    {
        $certs = $nurse->getMedia('certificates');
        $data = [];
        foreach ($certs as $key => $value) {
            $data[] = ['id' => $key, "name" => $value];
        }
        $this->check = "1";
        $this->message = "Search for credentials has been listed successfully";
        $this->return_data = $certs;
        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function getEHRProficiencyExpOptions()
    {
        $ehrProficienciesExp = $this->getEHRProficiencyExp()->pluck('title', 'id');
        $data = [];
        foreach ($ehrProficienciesExp as $key => $value) {
            $data[] = ['id' => $key, "name" => $value];
        }
        asort($data);
        $data1 = [];
        foreach ($data as $key1 => $value1) {
            $data1[] = ['id' => strval($value1['id']), "name" => $value1['name']];
        }
        $this->check = "1";
        $this->message = "EHR proficiency exp options has been listed successfully";
        $this->return_data = $data1;
        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function getEMedicalRecordsOptions()
    {
        $controller = new controller();
        $electronic_medical_records = $controller->getEMedicalRecords()->pluck('title', 'id');
        $data = [];
        foreach ($electronic_medical_records as $key => $value) {
            $data[] = ['id' => $key, "name" => $value];
        }
        $this->check = "1";
        $this->message = "EMedical records options has been listed successfully";
        $this->return_data = $data;
        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function getNursingDegreesOptions()
    {
        $nursingDegrees = $this->getNursingDegrees()->pluck('title', 'id');
        $data = [];
        foreach ($nursingDegrees as $key => $value) {
            $data[] = ['id' => $key, "name" => $value];
        }
        $this->check = "1";
        $this->message = "EHR proficiency exp options has been listed successfully";
        $this->return_data = $data;
        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function nurseExperienceSelectionOptions(Request $request)
    {
        $messages = [
            "id.required" => "Id is required"
        ];

        $validator = \Validator::make($request->all(), [
            'id' => 'required'
        ], $messages);


        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            $nurse = Nurse::where('user_id', '=', $request->id)->first();
            if ($nurse != null) {
                $nuexperience = $this->nurseExperienceSelection($nurse);
                $data = [];
                foreach ($nuexperience as $key => $value) {
                    $data[] = ['id' => $key, "name" => $value];
                }
                $this->check = "1";
                $this->message = "facility type's has been listed successfully";
                $this->return_data = $nuexperience;
            } else {
                $this->message = "Nurse not found";
            }
        }

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function Experience(Request $request)
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

        $validator = \Validator::make($request->all(), [
            'id' => 'required',
            'highest_nursing_degree' => 'required',
            'college_uni_name' => 'required|regex:/^[a-zA-Z 0-9,\-\/]+$/|min:1|max:255',
            'college_uni_city' => 'required|regex:/^[a-zA-Z ]+$/|min:2|max:50',
            'college_uni_state' => 'required',
            'college_uni_country' => 'required|regex:/^[a-zA-Z ]+$/|min:3',
            'experience_as_acute_care_facility' => 'nullable|regex:/^[0-9.\+]+$/|max:5',
            'experience_as_ambulatory_care_facility' => 'nullable|regex:/^[0-9.\+]+$/|max:5',
            'ehr_proficiency_cerner' => 'required',
            'ehr_proficiency_meditech' => 'required',
            'ehr_proficiency_epic' => 'required',
            'ehr_proficiency_other' => 'nullable|regex:/^[a-zA-Z 0-9]+$/|min:2|max:50',
        ], $messages);

        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            $user_info = USER::where('id', '=', $request->id);
            if ($user_info->count() > 0) {
                $user = $user_info->first();
                $nurse_info = Nurse::where('user_id', '=', $request->id);
                if ($nurse_info->count() > 0) {
                    $nurse = $nurse_info->first();

                    $update_data = [
                        'highest_nursing_degree' => $request->highest_nursing_degree,
                        'college_uni_name' => $request->college_uni_name,
                        'college_uni_city' => $request->college_uni_city,
                        'college_uni_state' => $request->college_uni_state,
                        'college_uni_country' => $request->college_uni_country,
                        'experience_as_acute_care_facility' => $request->experience_as_acute_care_facility,
                        'experience_as_ambulatory_care_facility' => $request->experience_as_ambulatory_care_facility,
                        'ehr_proficiency_cerner' => $request->ehr_proficiency_cerner,
                        'ehr_proficiency_meditech' => $request->ehr_proficiency_meditech,
                        'ehr_proficiency_epic' => $request->ehr_proficiency_epic,
                        'ehr_proficiency_other' =>  $request->ehr_proficiency_other,
                    ];
                    $update = NURSE::where(['id' => $nurse->id])->update($update_data);
                    if ($update) {
                        $this->check = "1";
                        $this->message = "Experience updated successfully";
                        $this->return_data = $this->profileCompletionFlagStatus($type = "", $user);
                        // $this->return_data = $experience;
                    } else {
                        $this->message = "Failed to update the experience, Please try again later";
                    }
                } else {
                    $this->message = "Nurse not found";
                }
            } else {
                $this->message = "User not found";
            }
        }

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function test(Request $request)
    {
        /* $follows = new Follows();
        $user_data = Follows::where('email', '=', $request->email)->get()->first(); */

        /* $nurse = JobOffer::create([
            'job_id' => "8245d08d-732c-45bc-bbaf-e3f19bcdadfb",
            'offer_id' => "1d779d61-5be9-47e7-a6be-d1c757a7c7c1",
        ]); */
        // $nurse->save();

        /* $facility_rating = FacilityRating::create(['nurse_id' => '1d779d61-5be9-47e7-a6be-d1c757a7c7c1', 'facility_id' => '1d779d61-5be9-47e7-a6be-d1c757a7c7c1']);
        $facility_rating->save(); */

        /*  $test = (object) [];
        $messages = [
            "id.required" => "Id is required",
        ];

        $validator = \Validator::make($request->all(), [
            'id' => 'required',
        ], $messages);

        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            $this->check = "1";
            $this->message = "Testing Response";
            $test->response = ["msg" => "test message"];
        } */
        /*$template = EmailTemplate::create([
            'label' => "choosepassword",
            'content' => "choose password content",
        ]);*/
        // $template = NurseRating::create([
        //     'label' => "choosepassword",
        //     'content' => "choose password content",
        // ]);
        // $template->save();

        /*$this->message == "NO";
        if (exists('/storage/assets/facilities/facility_logo/image20_1641315145.jpeg')) {
            $this->message == "Yes";
        }*/
        $this->return_data = "";

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function addCredentials(Request $request)
    {
        $messages = [
            "id.required" => "Id is required",
            "type.required" => "Select Credential",
            "type.exists" => "Selected Credential does not exist",
            "effective_date.required" => "Enter Effective Date",
            "effective_date.date" => "Effective Date is not valid",
            "expiration_date.required" => "Enter Expiration Date",
            "expiration_date.date" => "Expiration Date is not valid",
            "expiration_date.after" => "Expiration Date should be after Effective Date.",
            "certificate_image.max" => "Allowed File Size is 5 MB",
            "certificate_image.mimes" => "Allowed File Types are jpeg, png, jpg, pdf",
            "resume.mimes" => "Allowed File Types are doc, docx, pdf, txt",
            "resume.max" => "Allowed File Size is 2 MB",
        ];

        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'type' => 'required|numeric|exists:keywords,id',
            'effective_date' => 'required|date',
            'expiration_date' => "required|date|after:effective_date",
            'certificate_image' => 'nullable|max:5120|mimes:jpeg,png,jpg,pdf',
            'resume' => 'mimes:doc,docx,pdf,txt|max:2048',
        ], $messages);

        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            $nurse_info = Nurse::where('user_id', '=', $request->id);

            if ($nurse_info->count() > 0) {
                $nurse = $nurse_info->first();

                /* certification */
                $add_array = [
                    'nurse_id' => $nurse->id,
                    'type' => $request->type,
                    'effective_date' => $request->effective_date,
                    'expiration_date' => $request->expiration_date,
                ];
                $certification = Certification::create($add_array);

                if ($request->hasFile('certificate_image')) {
                    $certificate_image_name_full = $request->file('certificate_image')->getClientOriginalName();
                    $certificate_image_name = pathinfo($certificate_image_name_full, PATHINFO_FILENAME);
                    $certificate_image_ext = $request->file('certificate_image')->getClientOriginalExtension();
                    $certificate_image = $certificate_image_name . '_' . time() . '.' . $certificate_image_ext;
                    $certification_array["certificate_image"] = $certificate_image;
                    $certification_img_update = Certification::where(['id' => $certification->id])->update($certification_array);
                    //Upload Image
                    $request->file('certificate_image')->storeAs('assets/nurses/certifications/' . $nurse->id, $certificate_image);
                }
                /* certification */

                if ($certification == true) {
                    $cert_ret = Certification::where('id', '=', $certification->id)->first();

                    /* certificate data */
                    $certifications = $this->getCertifications()->pluck('title', 'id');
                    $cert_data["id"] = (isset($cert_ret->id) && $cert_ret->id != "") ? $cert_ret->id : "";
                    $cert_data["nurse_id"] = (isset($cert_ret->nurse_id) && $cert_ret->nurse_id != "") ? $cert_ret->nurse_id : "";
                    $cert_data["search_for_credential"] = (isset($cert_ret->type) && $cert_ret->type != "") ? $cert_ret->type : "";
                    $cert_data["search_for_credential_definition"] = (isset($certifications[$cert_ret->type]) && $certifications[$cert_ret->type] != "") ? $certifications[$cert_ret->type] : "";
                    $cert_data["license_number"] = (isset($cert_ret->license_number) && $cert_ret->license_number != "") ? $cert_ret->license_number : "";
                    $cert_data["effective_date"] = (isset($cert_ret->effective_date) && $cert_ret->effective_date != "") ?  date('m/d/Y', strtotime($cert_ret->effective_date)) : "";
                    $cert_data["expiration_date"] = (isset($cert_ret->expiration_date) && $cert_ret->expiration_date != "") ?  date('m/d/Y', strtotime($cert_ret->expiration_date)) : "";
                    $cert_data["certificate_image"] = (isset($cert_ret->certificate_image) && $cert_ret->certificate_image != "") ? url('storage/assets/nurses/certifications/' . $nurse->id . '/' . $cert_ret->certificate_image) : "";
                    // $cert_data["active"] = (isset($cert_ret->active) && $cert_ret->active != "") ? $cert_ret->active : "";
                    // $cert_data["deleted_at"] = (isset($cert_ret->deleted_at) && $cert_ret->deleted_at != "") ? date('m/d/Y H:i:s', strtotime($cert_ret->deleted_at)) : "";
                    // $cert_data["created_at"] = (isset($cert_ret->created_at) && $cert_ret->created_at != "") ?  date('m/d/Y H:i:s', strtotime($cert_ret->created_at)) : "";
                    // $cert_data["updated_at"] = (isset($cert_ret->updated_at) && $cert_ret->updated_at != "") ?  date('m/d/Y H:i:s', strtotime($cert_ret->updated_at)) : "";
                    /* certificate data */

                    /* nurse data */
                    $nurse_return = Nurse::select('resume')->where('user_id', '=', $request->id)->first();
                    $cert_data["resume"] = (isset($nurse_return->resume) && $nurse_return->resume != "") ? url('storage/assets/nurses/resumes/' . $nurse->id . '/' . $nurse_return->resume) : "";
                    /* nurse data */

                    $this->check = "1";
                    $this->message = "Certification added successfully";
                    $this->return_data = $cert_data;
                } else {
                    $this->message = "Problem occurred while updating certification, Please try again later";
                }
            } else {
                $this->message = "Nurse not found";
            }
        }
        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function editCredentials(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'certificate_id' => 'required',
            'type' => 'required|numeric|exists:keywords,id',
            'effective_date' => 'required|date',
            'expiration_date' => "required|date|after:effective_date",
            'certificate_image' => 'nullable|max:5120|mimes:jpeg,png,jpg,pdf',
        ]);

        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            $user_info = USER::where('id', $request->user_id);
            if ($user_info->count() > 0) {
                $user = $user_info->first();
                $nurse_info = NURSE::where('user_id', $request->user_id);
                if ($nurse_info->count() > 0) {
                    $nurse = $nurse_info->first();
                    $certificate_array = [
                        "type" => $request->type,
                        "effective_date" => $request->effective_date,
                        "expiration_date" => $request->expiration_date,
                    ];
                    if ($request->hasFile('certificate_image')) {
                        $certificate_image_name_full = $request->file('certificate_image')->getClientOriginalName();
                        $certificate_image_name = pathinfo($certificate_image_name_full, PATHINFO_FILENAME);
                        $certificate_image_ext = $request->file('certificate_image')->getClientOriginalExtension();
                        $certificate_image = $certificate_image_name . '_' . time() . '.' . $certificate_image_ext;
                        $certificate_array["certificate_image"] = $certificate_image;
                        //Upload Image
                        $request->file('certificate_image')->storeAs('assets/nurses/certifications/' . $nurse->id, $certificate_image);
                    }
                    $certification = Certification::where(['id' => $request->certificate_id])->update($certificate_array);
                    if ($certification == true) {
                        $this->check = "1";
                        $this->message = "Certificate updated successfully";
                        $this->return_data = $this->profileCompletionFlagStatus($type = "", $user);
                    } else {
                        $this->message = "Failed to update the certificate. Please try again later";
                    }
                } else {
                    $this->message = "Nurse not found";
                }
            } else {
                $this->message = "User not found";
            }
        }

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function removeCredentialDoc(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'certificate_id' => 'required',
            // 'certificate_image' => 'required'
        ]);

        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            $user_info = USER::where('id', $request->user_id);
            if ($user_info->count() > 0) {
                $user = $user_info->first();
                $nurse_info = NURSE::where('user_id', $request->user_id);
                if ($nurse_info->count() > 0) {
                    $nurse = $nurse_info->first();

                    $certificate = Certification::where(['id' => $request->certificate_id])->whereNull('deleted_at')->get();
                    if ($certificate->count() > 0) {
                        $cert = $certificate->first();

                        $del = Storage::delete('assets/nurses/certifications/' . $nurse->id . '/' . $cert->certificate_image);
                        $remove = Certification::where(['id' => $request->certificate_id])->update(['deleted_at' => date('m/d/Y H:i:s')]);
                        if ($del && $remove) {
                            $this->check = "1";
                            $this->message = "Certificate removed successfully";
                        } else {
                            $this->message = "Failed to remove or certificate removed already. Please try again later";
                        }
                    } else {
                        $this->message = "Certificate already removed";
                    }

                    /* $file = explode("/", $request->certificate_image); //file_exists();
                    if (isset($file) && is_array($file) && !empty($file)) {
                        $t = Storage::exists('assets/nurses/certifications/' . $nurse->id . '/' . end($file));
                        if ($t) {
                        } else {
                            $this->message = "Certificate already removed";
                        }
                    } */
                }
            }
        }

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function resume(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'resume' => 'required|mimes:doc,docx,pdf,txt|max:2048',
        ]);

        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            $user_info = USER::where('id', $request->user_id);
            if ($user_info->count() > 0) {
                $user = $user_info->first();
                $nurse_info = NURSE::where('user_id', $request->user_id);
                if ($nurse_info->count() > 0) {
                    $nurse = $nurse_info->first();
                    $nurse_update = false;
                    if ($request->hasFile('resume')) {
                        $resume_name_full = $request->file('resume')->getClientOriginalName();
                        $resume_name = pathinfo($resume_name_full, PATHINFO_FILENAME);
                        $resume_ext = $request->file('resume')->getClientOriginalExtension();
                        $resume = $resume_name . '_' . time() . '.' . $resume_ext;
                        $nurse_array["resume"] = $resume;
                        //Upload Image
                        $request->file('resume')->storeAs('assets/nurses/resumes/' . $nurse->id, $resume);
                        $nurse_update = NURSE::where(['id' => $nurse->id])->update($nurse_array);
                        $nurse->addMediaFromRequest('resume')->usingName($nurse->id)->toMediaCollection('resumes');
                    }

                    if ($nurse_update == true) {
                        $this->check = "1";
                        $this->message = "Resume updated successfully";
                        $this->return_data = $this->profileCompletionFlagStatus($type = "", $user);
                    } else {
                        $this->message = "Failed to update the resume. Please try again later";
                    }
                } else {
                    $this->message = "Nurse not found";
                }
            } else {
                $this->message = "User not found";
            }
        }

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function leadershipRoles()
    {
        $leadershipRoles = $this->getLeadershipRoles()->pluck('title', 'id');
        $data = [];
        foreach ($leadershipRoles as $key => $value) {
            $data[] = ['id' => $key, "name" => $value];
        }
        $this->check = "1";
        $this->message = "Leadership roles has been listed successfully";
        $this->return_data = $data;
        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function getLanguages()
    {
        $languages = $this->getLanguageOptions();
        $data = [];
        foreach ($languages as $key => $value) {
            $data[] = ["language" => $value];
        }
        $this->check = "1";
        $this->message = "Languages has been listed successfully";
        $this->return_data = $data;
        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function rolePage1(Request $request)
    {
        $messages = [
            "id.required" => "Id is required",
            "leadership_roles.required_if" => "Please select leadership role",
        ];

        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'serving_preceptor' => 'boolean',
            'serving_interim_nurse_leader' => 'boolean',
            'leadership_roles' => 'required_if:serving_interim_nurse_leader,1',
            'clinical_educator' => 'boolean',
            'is_daisy_award_winner' => 'boolean',
            'employee_of_the_mth_qtr_yr' => 'boolean',
            'other_nursing_awards' => 'boolean',
            'is_professional_practice_council' => 'boolean',
            'is_research_publications' => 'boolean',
        ], $messages);
        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            $return_data = [];
            $nurse =  Nurse::where('user_id', '=', $request->id)->first();
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
            $this->check = "1";
            $this->return_data = $nurse;
            $this->message = "Role and Interest Updated Successfully";
        }
        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function rolePage2(Request $request)
    {
        $messages = [
            'id' => 'ID is required',
            "additional_pictures.max" => "Additional Photos can't be more than 4.",
            "additional_files.max" => "Additional Files can't be more than 4.",
            "additional_pictures.*.mimes" => "Additional Photos should be image or png jpg",
            "additional_files.*.mimes" => "Additional Files should be doc or pdf",
            "additional_pictures.*.max" => "Additional Photos should not be more than 5mb",
            "additional_files.*.max" => "Additional Files should not be more than 1mb",
            "nu_video.url" => "YouTube and Vimeo should be a valid link",
            "nu_video.max" => "YouTube and Vimeo should be a valid link"
        ];

        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'additional_pictures' => 'max:4',
            'additional_pictures.*' => 'nullable|max:5120|image|mimes:jpeg,png,jpg',
            'additional_files' => 'max:4',
            'additional_files.*' => 'nullable|max:1024|mimes:pdf,doc,docx',
            'nu_video' => 'nullable|url|max:255',
        ], $messages);
        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            $return_data = [];
            $nurse =  Nurse::where('user_id', '=', $request->id)->first();
            $nurse->summary = $request->summary;
            $nurse->save();
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
            $this->check = "1";
            $this->return_data = $nurse;
            $this->message = "Role and Interest Updated Successfully";
        }
        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function jobList(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'user_id' => 'required',
        ]);

        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            $whereCond = [
                'facilities.active' => true,
                'jobs.is_open' => "1"
            ];

            $ret = Job::select('jobs.id as job_id', 'jobs.*')
                ->leftJoin('facilities', function ($join) {
                    $join->on('facilities.id', '=', 'jobs.facility_id');
                })
                ->where($whereCond)
                ->orderBy('jobs.created_at', 'desc');

            // $search_keyword = $request->search_keyword;

            if (isset($request->search_location) && $request->search_location != "") $ret->search(['address', 'city', 'state', 'postcode'], $request->search_location);

            if (isset($request->open_assignment_type) && $request->open_assignment_type != "") {
                $ret->where('jobs.preferred_specialty', '=', $request->open_assignment_type);
            }

            if (isset($request->facility_type) && $request->facility_type != "") {
                $type = explode(",", $request->facility_type);
                if (is_array($type) && !empty($type)) {
                    $ret->where(function (Builder $query) use ($type) {
                        $query->whereIn('type', $type);
                    });
                }
            }

            if (isset($request->electronic_medical_records) && $request->electronic_medical_records != "") {
                $electronic_medical_records = explode(",", $request->electronic_medical_records);
                if (is_array($electronic_medical_records) && !empty($electronic_Medical_records)) {
                    $ret->where(function (Builder $query) use ($electronic_medical_records) {
                        $query->whereIn('f_emr', $electronic_medical_records);
                    });
                }
            }

            if (isset($request->facility_id) && $request->facility_id != "") {
                $facility_id = $request->facility_id;
                $ret->where('facility_id', $facility_id);
            }

            $job_data = $ret->paginate(10);

            $result = $this->jobData($job_data, $request->user_id);
            $this->check = "1";
            $this->message = "Jobs listed successfully";
            $this->return_data = $result;
        }

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function viewJob(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'id' => 'required',
        ]);

        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {

            $whereCond = [
                'facilities.active' => true,
                'jobs.is_open' => "1",
                'jobs.id' => $request->id
            ];

            $ret = Job::select('jobs.id as job_id', 'jobs.*', 'facilities.*')
                ->leftJoin('facilities', function ($join) {
                    $join->on('facilities.id', '=', 'jobs.facility_id');
                })
                ->where($whereCond)
                ->orderBy('jobs.created_at', 'desc');

            $jobdata = $ret->paginate(1);
            $result = $this->jobData($jobdata);

            $this->check = "1";
            $this->message = "View job listed successfully";
            $this->return_data = $result;
        }

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    /* public function jobOffers(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'id' => 'required',
        ]);

        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            $nurse = Nurse::where('user_id', '=', $request->id)->get()->first();
            $whereCond = [
                'active' => true
            ];
            $offers = Offer::where($whereCond)
                ->where('nurse_id', $nurse->id)
                ->whereNotNull('job_id')
                ->orderBy('created_at', 'desc')
                ->paginate(10);

            $this->return_data = $offers;
        }
        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    } */

    public function jobApplied(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'user_id' => 'required',
            'job_id' => 'required',
            'type' => 'required',
        ]);

        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            $check_exists = Follows::where([
                'user_id' => $request->user_id,
                'job_id' => $request->job_id,
            ]);
            if ($check_exists->count() > 0) {
                $follows = Follows::where([
                    'user_id' => $request->user_id,
                    'job_id' => $request->job_id,
                ])->update(['applied_status' => $request->type]);
            } else {
                $follows = Follows::create([
                    'user_id' => $request->user_id,
                    'job_id' => $request->job_id,
                    'applied_status' => $request->type
                ]);
            }

            $this->check = "1";
            $this->message = ($request->type == "1") ? "Applied successfully" : "Apply removed successfully";
        }

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function jobLikes(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'user_id' => 'required',
            'job_id' => 'required',
            'like' => 'required',
        ]);


        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            $check_exists = Follows::where([
                'user_id' => $request->user_id,
                'job_id' => $request->job_id,
            ]);
            if ($check_exists->count() > 0) {
                $follows = Follows::where([
                    'user_id' => $request->user_id,
                    'job_id' => $request->job_id,
                ])->update(['like_status' => $request->like]);
            } else {
                $follows = Follows::create([
                    'user_id' => $request->user_id,
                    'job_id' => $request->job_id,
                    'like_status' => $request->like
                ]);
            }

            $this->check = "1";
            $this->message = ($request->like == "1") ? "Liked successfully" : "Disliked successfully";
        }

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function jobData($jobdata, $user_id = "")
    {
        $result = [];
        if (!empty($jobdata)) {
            $controller = new Controller();
            $specialties = $controller->getSpecialities()->pluck('title', 'id');
            $assignmentDurations = $this->getAssignmentDurations()->pluck('title', 'id');
            $shifts = $this->getShifts()->pluck('title', 'id');
            $workLocations = $controller->getGeographicPreferences()->pluck('title', 'id');
            $leadershipRoles = $this->getLeadershipRoles()->pluck('title', 'id');
            $seniorityLevels = $this->getSeniorityLevel()->pluck('title', 'id');
            $jobFunctions = $this->getJobFunction()->pluck('title', 'id');
            $ehrProficienciesExp = $this->getEHRProficiencyExp()->pluck('title', 'id');
            $weekDays = $this->getWeekDayOptions();

            foreach ($jobdata as $key => $job) {

                $j_data["job_id"] = isset($job->job_id) ? $job->job_id : "";

                $j_data["preferred_specialty"] = isset($job->preferred_specialty) ? $job->preferred_specialty : "";
                $j_data["preferred_specialty_definition"] = isset($specialties[$job->preferred_specialty])  ? $specialties[$job->preferred_specialty] : "";

                $j_data["preferred_assignment_duration"] = isset($job->preferred_assignment_duration) ? $job->preferred_assignment_duration : "";
                $j_data["preferred_assignment_duration_definition"] = isset($assignmentDurations[$job->preferred_assignment_duration]) ? $assignmentDurations[$job->preferred_assignment_duration] : "";

                $j_data["preferred_shift_duration"] = isset($job->preferred_shift_duration) ? $job->preferred_shift_duration : "";
                $j_data["preferred_shift_duration_definition"] = isset($shifts[$job->preferred_shift_duration]) ? $shifts[$job->preferred_shift_duration] : "";

                $j_data["preferred_work_location"] = isset($job->preferred_work_location) ? $job->preferred_work_location : "";
                $j_data["preferred_work_location_definition"] = isset($workLocations[$job->preferred_work_location]) ? $workLocations[$job->preferred_work_location] : "";

                $j_data["preferred_work_area"] = isset($job->preferred_work_area) ? $job->preferred_work_area : "";
                $j_data["preferred_days_of_the_week"] = isset($job->preferred_days_of_the_week) ? explode(",", $job->preferred_days_of_the_week) : [];
                $j_data["preferred_hourly_pay_rate"] = isset($job->preferred_hourly_pay_rate) ? $job->preferred_hourly_pay_rate : "";
                $j_data["preferred_experience"] = isset($job->preferred_experience) ? $job->preferred_experience : "";
                $j_data["description"] = isset($job->description) ? $job->description : "";
                $j_data["created_at"] = isset($job->created_at) ? date('d-F-Y h:i A', strtotime($job->created_at)) : "";
                $j_data["created_at_definition"] = isset($job->created_at) ? "Posted " . $this->timeAgo(date(strtotime($job->created_at))) : "";
                $j_data["updated_at"] = isset($job->updated_at) ? date('d-F-Y h:i A', strtotime($job->updated_at)) : "";
                $j_data["deleted_at"] = isset($job->deleted_at) ? date('d-F-Y h:i A', strtotime($job->deleted_at)) : "";
                $j_data["created_by"] = isset($job->created_by) ? $job->created_by : "";
                $j_data["slug"] = isset($job->slug) ? $job->slug : "";
                $j_data["active"] = isset($job->active) ? $job->active : "";
                $j_data["facility_id"] = isset($job->facility_id) ? $job->facility_id : "";
                $j_data["job_video"] = isset($job->job_video) ? $job->job_video : "";

                $j_data["seniority_level"] = isset($job->seniority_level) ? $job->seniority_level : "";
                $j_data["seniority_level_definition"] = isset($seniorityLevels[$job->seniority_level]) ? $seniorityLevels[$job->seniority_level] : "";

                $j_data["job_function"] = isset($job->job_function) ? $job->job_function : "";
                $j_data["job_function_definition"] = isset($jobFunctions[$job->job_function]) ? $jobFunctions[$job->job_function] : "";

                $j_data["responsibilities"] = isset($job->responsibilities) ? $job->responsibilities : "";
                $j_data["qualifications"] = isset($job->qualifications) ? $job->qualifications : "";

                $j_data["job_cerner_exp"] = isset($job->job_cerner_exp) ? $job->job_cerner_exp : "";
                $j_data["job_cerner_exp_definition"] = isset($ehrProficienciesExp[$job->job_cerner_exp]) ? $ehrProficienciesExp[$job->job_cerner_exp] : "";

                $j_data["job_meditech_exp"] = isset($job->job_meditech_exp) ? $job->job_meditech_exp : "";
                $j_data["job_meditech_exp_definition"] = isset($ehrProficienciesExp[$job->job_meditech_exp]) ? $ehrProficienciesExp[$job->job_meditech_exp] : "";

                $j_data["job_epic_exp"] = isset($job->job_epic_exp) ? $job->job_epic_exp : "";
                $j_data["job_epic_exp_definition"] = isset($ehrProficienciesExp[$job->job_epic_exp]) ? $ehrProficienciesExp[$job->job_epic_exp] : "";

                $j_data["job_other_exp"] = isset($job->job_other_exp) ? $job->job_other_exp : "";
                // $j_data["job_photos"] = isset($job->job_photos) ? $job->job_photos : "";
                $j_data["video_embed_url"] = isset($job->video_embed_url) ? $job->video_embed_url : "";
                $j_data["is_open"] = isset($job->is_open) ? $job->is_open : "";
                $j_data["name"] = isset($job->facility->name) ? $job->facility->name : "";
                $j_data["address"] = isset($job->facility->address) ? $job->facility->address : "";
                $j_data["city"] = isset($job->facility->city) ? $job->facility->city : "";
                $j_data["state"] = isset($job->facility->state) ? $job->facility->state : "";
                $j_data["postcode"] = isset($job->facility->postcode) ? $job->facility->postcode : "";
                $j_data["type"] = isset($job->facility->type) ? $job->facility->type : "";

                $j_data["facility_logo"] = isset($job->facility->facility_logo) ? url("storage/assets/facilities/facility_logo/" . $job->facility->facility_logo) : "";
                $facility_logo = "";
                if ($job->facility->facility_logo) {
                    $t = \Illuminate\Support\Facades\Storage::exists('assets/facilities/facility_logo/' . $job->facility->facility_logo);
                    if ($t) {
                        $facility_logo = \Illuminate\Support\Facades\Storage::get('assets/facilities/facility_logo/' . $job->facility->facility_logo);
                    }
                }
                $j_data["facility_logo_base"] = ($facility_logo != "") ? 'data:image/jpeg;base64,' . base64_encode($facility_logo) : "";

                $j_data["facility_email"] = isset($job->facility->facility_email) ? $job->facility->facility_email : "";
                $j_data["facility_phone"] = isset($job->facility->facility_phone) ? $job->facility->facility_phone : "";
                $j_data["specialty_need"] = isset($job->facility->specialty_need) ? $job->facility->specialty_need : "";
                $j_data["cno_message"] = isset($job->facility->cno_message) ? $job->facility->cno_message : "";

                $j_data["cno_image"] = isset($job->facility->cno_image) ? $job->facility->cno_image : "";
                $cno_image = "";
                if ($job->facility->cno_image) {
                    $t = \Illuminate\Support\Facades\Storage::exists('assets/facilities/cno_image/' . $job->facility->cno_image);
                    if ($t) {
                        $cno_image = \Illuminate\Support\Facades\Storage::get('assets/facilities/cno_image/' . $job->facility->cno_image);
                    }
                }
                $j_data["cno_image_base"] = ($cno_image != "") ? 'data:image/jpeg;base64,' . base64_encode($cno_image) : "";

                $j_data["gallary_images"] = isset($job->facility->gallary_images) ? $job->facility->gallary_images : "";
                $j_data["video"] = isset($job->facility->video) ? $job->facility->video : "";
                $j_data["facebook"] = isset($job->facility->facebook) ? $job->facility->facebook : "";
                $j_data["twitter"] = isset($job->facility->twitter) ? $job->facility->twitter : "";
                $j_data["linkedin"] = isset($job->facility->linkedin) ? $job->facility->linkedin : "";
                $j_data["instagram"] = isset($job->facility->instagram) ? $job->facility->instagram : "";
                $j_data["pinterest"] = isset($job->facility->pinterest) ? $job->facility->pinterest : "";
                $j_data["tiktok"] = isset($job->facility->tiktok) ? $job->facility->tiktok : "";
                $j_data["sanpchat"] = isset($job->facility->sanpchat) ? $job->facility->sanpchat : "";
                $j_data["youtube"] = isset($job->facility->youtube) ? $job->facility->youtube : "";
                $j_data["about_facility"] = isset($job->facility->about_facility) ? $job->facility->about_facility : "";
                $j_data["facility_website"] = isset($job->facility->facility_website) ? $job->facility->facility_website : "";
                $j_data["f_lat"] = isset($job->facility->f_lat) ? $job->facility->f_lat : "";
                $j_data["f_lang"] = isset($job->facility->f_lang) ? $job->facility->f_lang : "";
                $j_data["f_emr"] = isset($job->facility->f_emr) ? $job->facility->f_emr : "";
                $j_data["f_emr_other"] = isset($job->facility->f_emr_other) ? $job->facility->f_emr_other : "";
                $j_data["f_bcheck_provider"] = isset($job->facility->f_bcheck_provider) ? $job->facility->f_bcheck_provider : "";
                $j_data["f_bcheck_provider_other"] = isset($job->facility->f_bcheck_provider_other) ? $job->facility->f_bcheck_provider_other : "";
                $j_data["nurse_cred_soft"] = isset($job->facility->nurse_cred_soft) ? $job->facility->nurse_cred_soft : "";
                $j_data["nurse_cred_soft_other"] = isset($job->facility->nurse_cred_soft_other) ? $job->facility->nurse_cred_soft_other : "";
                $j_data["nurse_scheduling_sys"] = isset($job->facility->nurse_scheduling_sys) ? $job->facility->nurse_scheduling_sys : "";
                $j_data["nurse_scheduling_sys_other"] = isset($job->facility->nurse_scheduling_sys_other) ? $job->facility->nurse_scheduling_sys_other : "";
                $j_data["time_attend_sys"] = isset($job->facility->time_attend_sys) ? $job->facility->time_attend_sys : "";
                $j_data["time_attend_sys_other"] = isset($job->facility->time_attend_sys_other) ? $job->facility->time_attend_sys_other : "";
                $j_data["licensed_beds"] = isset($job->facility->licensed_beds) ? $job->facility->licensed_beds : "";
                $j_data["trauma_designation"] = isset($job->facility->trauma_designation) ? $job->facility->trauma_designation : "";

                /* total applied */
                $total_follow_count = Follows::where(['job_id' => $job->job_id, "applied_status" => "1", 'status' => "1"])->count();
                $j_data["total_applied"] = strval($total_follow_count);
                /* total applied */

                /* liked */
                $is_applied = "0";
                if ($user_id != "")
                    $is_applied = Follows::where(['job_id' => $job->job_id, "applied_status" => "1", 'status' => "1", "user_id" => $user_id])->count();
                /* liked */
                $j_data["is_applied"] = strval($is_applied);

                /* liked */
                $is_liked = "0";
                if ($user_id != "")
                    $is_liked = Follows::where(['job_id' => $job->job_id, "like_status" => "1", 'status' => "1", "user_id" => $user_id])->count();
                /* liked */
                $j_data["is_liked"] = strval($is_liked);

                $j_data["shift"] = "Days";
                $j_data["start_date"] = date('d F Y', strtotime($job->start_date));

                $result[] = $j_data;
            }
        }
        return $result;
    }

    public function browse_facilities(Request $request)
    {
        // dd($request->all());
        /* $validator = \Validator::make($request->all(), [
            'user_id' => 'required',
        ]);

        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
        } */

        if (isset($request->facility_id) && $request->facility_id != "") {
            $whereCond = ['facilities.id' => $request->facility_id, 'facilities.active' => true];
        } else {
            $whereCond = ['facilities.active' => true, 'jobs.is_open' => "1"];
        }

        $ret = Facility::select('facilities.id as facility_id', 'facilities.*', 'jobs.preferred_specialty')
            ->leftJoin('jobs', function ($join) {
                $join->on('facilities.id', '=', 'jobs.facility_id');
            })
            ->where($whereCond);


        /*if (isset($request->search_location) && $request->search_location != "") {
                $search_location = $request->search_location;
                $ret->search([
                    'address',
                    'city',
                    'state',
                    'postcode'
                ], $search_location);
            }*/

        if (isset($request->facility_type) && $request->facility_type  != "") {
            $type = $request->facility_type;
            $ret->where(function (Builder $query) use ($type) {
                $query->whereIn('type', $type);
            });
        }

        if (isset($request->electronic_medical_records) && $request->electronic_medical_records != "") {
            $electronic_medical_records = $request->electronic_medical_records;
            $ret->where(function (Builder $query) use ($electronic_medical_records) {
                $query->whereIn('f_emr', $electronic_medical_records);
            });
        }

        /* name search for api */
        if (isset($request->search_keyword) && $request->search_keyword != "") {
            $search_keyword = $request->search_keyword;
            $ret->search([
                'name'
            ], $search_keyword);
        }
        /* name search for api */

        /*new update jan 10*/
        $open_assignment_type = (isset($request->open_assignment_type) && $request->open_assignment_type != "") ? $request->open_assignment_type : "";
        /*if ($open_assignment_type) {
                $ret->where('jobs.preferred_specialty', '=', $open_assignment_type);
        }*/
        if ($open_assignment_type != "") {
            $ret->where(function (Builder $query) use ($open_assignment_type) {
                $query->whereIn('jobs.preferred_specialty', $open_assignment_type);
            });
        }
        /*new update jan 10*/

        /*new update jan 10*/
        /* state city and postcode new update */
        $states = (isset($request->state) && $request->state != "") ? $request->state : "";
        if (isset($states) && $states != "") {
            $getStates = States::where(['id' => $states])->get();
            if ($getStates->count() > 0) {
                $selected_state = $getStates->first();
                $name = $selected_state->name;
                $iso2 = $selected_state->iso2;
                $ret->where(function (Builder $query1) use ($name, $iso2) {
                    $query1->where('state', array($name));
                    $query1->orWhere('state', array($iso2));
                });
            }
        }

        $cities = (isset($request->city) && $request->city != "") ? $request->city : "";
        if (isset($cities) && $cities != "") {
            $getCities = Cities::where(['id' => $cities])->get();
            if ($getCities->count() > 0) {
                $selected_city = $getCities->first();
                $name = $selected_city->name;
                $ret->where(function (Builder $query1) use ($name) {
                    $query1->where('city', array($name));
                });
            }
        }

        $zipcode = (isset($request->zipcode) && $request->zipcode != "") ? $request->zipcode : "";
        if (isset($zipcode) && $zipcode != "") {
            $ret->where(function (Builder $query_zip) use ($zipcode) {
                $query_zip->where('postcode', array($zipcode));
            });
            /*$zipcode_inp = [];
                $nearest = $this->getNearestMiles($zipcode);
                if (isset($nearest['results']) && !empty($nearest['results'])) {
                    foreach ($nearest['results'] as $zipkey => $zip_res) {
                        $zipcode_inp[] = $zip_res['code'];
                    }
                }
                if (!empty($zipcode_inp)) {
                    $ret->where(function (Builder $query_zip) use ($zipcode_inp) {
                        $query_zip->whereIn('postcode', $zipcode_inp);
                    });
                } else {
                    $ret->where(function (Builder $query_zip) use ($zipcode) {
                        $query_zip->where('postcode', array($zipcode));
                    });
                }*/
        }
        /* state city and postcode new update */
        /*new update jan 10*/

        $ret->groupBy('facilities.id')->orderBy('created_at', 'desc');
        $facility_data = (isset($request->facility_id) && $request->facility_id != "") ? $ret->paginate(1) : $ret->paginate(10);
        $user_id = (isset($request->user_id) && $request->user_id != "") ? $request->user_id : "";

        $response = $this->facilityData($facility_data, $user_id);
        // $response = $this->facilityData($facility_data, $user_id = $request->user_id);

        $this->check = "1";
        $this->message = "Facilities listed below";
        $this->return_data = $response;

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function facilityFollows(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'user_id' => 'required',
            'facility_id' => 'required',
            'type' => 'required',
        ]);


        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            $check_exists = FacilityFollows::where([
                'user_id' => $request->user_id,
                'facility_id' => $request->facility_id,
            ]);
            if ($check_exists->count() > 0) {
                $follows = FacilityFollows::where([
                    'user_id' => $request->user_id,
                    'facility_id' => $request->facility_id,
                ])->update(['follow_status' => strval($request->type)]);
            } else {
                $follows = FacilityFollows::create([
                    'user_id' => $request->user_id,
                    'facility_id' => $request->facility_id,
                    'follow_status' => strval($request->type)
                ]);
            }

            $this->check = "1";
            $this->message = ($request->type == "1") ? "Followed successfully" : "Unfollowed successfully";
        }

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function facilityLikes(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'user_id' => 'required',
            'facility_id' => 'required',
            'like' => 'required',
        ]);


        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            $check_exists = FacilityFollows::where([
                'user_id' => $request->user_id,
                'facility_id' => $request->facility_id,
            ]);
            if ($check_exists->count() > 0) {
                $follows = FacilityFollows::where([
                    'user_id' => $request->user_id,
                    'facility_id' => $request->facility_id,
                ])->update(['like_status' => $request->like]);
            } else {
                $follows = FacilityFollows::create([
                    'user_id' => $request->user_id,
                    'facility_id' => $request->facility_id,
                    'like_status' => $request->like
                ]);
            }

            $this->check = "1";
            $this->message = ($request->like == "1") ? "Liked successfully" : "Disliked successfully";
        }

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function facilityData($facility_result, $user_id = "")
    {
        $result = [];
        if (!empty($facility_result)) {
            foreach ($facility_result as $key => $facility_data) {
                $facility["id"] = (isset($facility_data->id) && $facility_data->id != "") ? $facility_data->facility_id : "";
                // $facility_logo = (isset($facility_data->facility_logo) && $facility_data->facility_logo != "") ?  url("storage/assets/facilities/facility_logo/" . $facility_data->facility_logo) : "";
                $t = Storage::exists('assets/facilities/facility_logo/' . $facility_data->facility_logo);
                if ($t) $facility["facility_logo"] = url("storage/assets/facilities/facility_logo/" . $facility_data->facility_logo);
                else $facility["facility_logo"] = "";

                $facility_logo = "";
                if ($facility_data->facility_logo) {
                    $t = \Illuminate\Support\Facades\Storage::exists('assets/facilities/facility_logo/' . $facility_data->facility_logo);
                    if ($t) {
                        $facility_logo = \Illuminate\Support\Facades\Storage::get('assets/facilities/facility_logo/' . $facility_data->facility_logo);
                    }
                }
                $facility["facility_logo_base"] = ($facility_logo != "") ? 'data:image/jpeg;base64,' . base64_encode($facility_logo) : "";

                $facility["created_by"] = (isset($facility_data->created_by) && $facility_data->created_by != "") ? $facility_data->created_by : "";
                $facility["name"] = (isset($facility_data->name) && $facility_data->name != "") ? $facility_data->name : "";
                $facility["address"] = (isset($facility_data->address) && $facility_data->address != "") ? $facility_data->address : "";
                $facility["city"] = (isset($facility_data->city) && $facility_data->city != "") ? $facility_data->city : "";
                $facility["state"] = (isset($facility_data->state) && $facility_data->state != "") ? $facility_data->state : "";
                $facility["postcode"] = (isset($facility_data->postcode) && $facility_data->postcode != "") ? $facility_data->postcode : "";
                $facility["facility_type"] = (isset($facility_data->type) && $facility_data->type != "") ? strval($facility_data->type) : "";
                $facility["facility_type_definition"] = (isset($facility_data->type) && $facility_data->type != "") ? \App\Providers\AppServiceProvider::keywordTitle($facility_data->type) : "";
                $facility["active"] = (isset($facility_data->active) && $facility_data->active != "") ? $facility_data->active : "";
                $facility["deleted_at"] = (isset($facility_data->deleted_at) && $facility_data->deleted_at != "") ? $facility_data->deleted_at : "";
                $facility["created_at"] = (isset($facility_data->created_at) && $facility_data->created_at != "") ? $facility_data->created_at : "";
                $facility["updated_at"] = (isset($facility_data->updated_at) && $facility_data->updated_at != "") ? $facility_data->updated_at : "";
                $facility["facility_email"] = (isset($facility_data->facility_email) && $facility_data->facility_email != "") ? $facility_data->facility_email : "";
                $facility["facility_phone"] = (isset($facility_data->facility_phone) && $facility_data->facility_phone != "") ? $facility_data->facility_phone : "";
                $facility["specialty_need"] = (isset($facility_data->specialty_need) && $facility_data->specialty_need != "") ? $facility_data->specialty_need : "";
                $facility["slug"] = (isset($facility_data->slug) && $facility_data->slug != "") ? $facility_data->slug : "";
                $facility["cno_message"] = (isset($facility_data->cno_message) && $facility_data->cno_message != "") ? $facility_data->cno_message : "";

                $facility["cno_image"] = (isset($facility_data->cno_image) && $facility_data->cno_image != "") ? url('storage/assets/facilities/cno_image/' . $facility_data->cno_image) : "";
                $cno_image = "";
                if ($facility_data->cno_image) {
                    $t = \Illuminate\Support\Facades\Storage::exists('assets/facilities/cno_image/' . $facility_data->cno_image);
                    if ($t) {
                        $cno_image = \Illuminate\Support\Facades\Storage::get('assets/facilities/cno_image/' . $facility_data->cno_image);
                    }
                }
                $facility_info["cno_image_base"] = ($cno_image != "") ? 'data:image/jpeg;base64,' . base64_encode($cno_image) : "";

                $facility["gallery_images"] = (isset($facility_data->gallary_images) && $facility_data->gallary_images != "") ? $facility_data->gallary_images : "";
                $facility["video"] = (isset($facility_data->video) && $facility_data->video != "") ? $facility_data->video : "";
                $facility["facebook"] = (isset($facility_data->facebook) && $facility_data->facebook != "") ? $facility_data->facebook : "";
                $facility["twitter"] = (isset($facility_data->twitter) && $facility_data->twitter != "") ? $facility_data->twitter : "";
                $facility["linkedin"] = (isset($facility_data->linkedin) && $facility_data->linkedin != "") ? $facility_data->linkedin : "";
                $facility["instagram"] = (isset($facility_data->instagram) && $facility_data->instagram != "") ? $facility_data->instagram : "";
                $facility["pinterest"] = (isset($facility_data->pinterest) && $facility_data->pinterest != "") ? $facility_data->pinterest : "";
                $facility["tiktok"] = (isset($facility_data->tiktok) && $facility_data->tiktok != "") ? $facility_data->tiktok : "";
                $facility["sanpchat"] = (isset($facility_data->sanpchat) && $facility_data->sanpchat != "") ? $facility_data->sanpchat : "";
                $facility["youtube"] = (isset($facility_data->youtube) && $facility_data->youtube != "") ? $facility_data->youtube : "";
                $facility["about_facility"] = (isset($facility_data->about_facility) && $facility_data->about_facility != "") ? $facility_data->about_facility : "";
                $facility["facility_website"] = (isset($facility_data->facility_website) && $facility_data->facility_website != "") ? $facility_data->facility_website : "";
                $facility["video_embed_url"] = (isset($facility_data->video_embed_url) && $facility_data->video_embed_url != "") ? $facility_data->video_embed_url : "";
                $facility["f_lat"] = (isset($facility_data->f_lat) && $facility_data->f_lat != "") ? $facility_data->f_lat : "";
                $facility["f_lang"] = (isset($facility_data->f_lang) && $facility_data->f_lang != "") ? $facility_data->f_lang : "";
                $facility["f_emr"] = (isset($facility_data->f_emr) && $facility_data->f_emr != "") ? $facility_data->f_emr : "";
                $facility["f_emr_definition"] = (isset($facility_data->f_emr) && $facility_data->f_emr != "") ? \App\Providers\AppServiceProvider::keywordTitle($facility_data->f_emr) : "";
                $facility["f_emr_other"] = (isset($facility_data->f_emr_other) && $facility_data->f_emr_other != "") ? $facility_data->f_emr_other : "";

                $facility["f_bcheck_provider"] = (isset($facility_data->f_bcheck_provider) && $facility_data->f_bcheck_provider != "") ? $facility_data->f_bcheck_provider : "";
                if ($facility["f_bcheck_provider"] == "0") $facility["f_bcheck_provider_definition"] = "Other";
                else $facility["f_bcheck_provider_definition"] = (isset($facility_data->f_bcheck_provider) && $facility_data->f_bcheck_provider != "") ? \App\Providers\AppServiceProvider::keywordTitle($facility_data->f_bcheck_provider) : "";
                $facility["f_bcheck_provider_other"] = (isset($facility_data->f_bcheck_provider_other) && $facility_data->f_bcheck_provider_other != "") ? $facility_data->f_bcheck_provider_other : "";

                $facility["nurse_cred_soft"] = (isset($facility_data->nurse_cred_soft) && $facility_data->nurse_cred_soft != "") ? $facility_data->nurse_cred_soft : "";
                if ($facility["nurse_cred_soft"] == "0") $facility["nurse_cred_soft_definition"] = "Other";
                else $facility["nurse_cred_soft_definition"] = (isset($facility_data->nurse_cred_soft) && $facility_data->nurse_cred_soft != "") ? \App\Providers\AppServiceProvider::keywordTitle($facility_data->nurse_cred_soft) : "";
                $facility["nurse_cred_soft_other"] = (isset($facility_data->nurse_cred_soft_other) && $facility_data->nurse_cred_soft_other != "") ? $facility_data->nurse_cred_soft_other : "";

                $facility["nurse_scheduling_sys"] = (isset($facility_data->nurse_scheduling_sys) && $facility_data->nurse_scheduling_sys != "") ? $facility_data->nurse_scheduling_sys : "";
                if ($facility["nurse_scheduling_sys"] == "0") $facility["nurse_scheduling_sys_definition"] = "Other";
                else $facility["nurse_scheduling_sys_definition"] = (isset($facility_data->nurse_scheduling_sys) && $facility_data->nurse_scheduling_sys != "") ? \App\Providers\AppServiceProvider::keywordTitle($facility_data->nurse_scheduling_sys) : "";
                $facility["nurse_scheduling_sys_other"] = (isset($facility_data->nurse_scheduling_sys_other) && $facility_data->nurse_scheduling_sys_other != "") ? $facility_data->nurse_scheduling_sys_other : "";

                $facility["time_attend_sys"] = (isset($facility_data->time_attend_sys) && $facility_data->time_attend_sys != "") ? $facility_data->time_attend_sys : "";
                $facility["time_attend_sys_definition"] = (isset($facility_data->time_attend_sys) && $facility_data->time_attend_sys != "") ? \App\Providers\AppServiceProvider::keywordTitle($facility_data->time_attend_sys) : "";
                $facility["time_attend_sys_other"] = (isset($facility_data->time_attend_sys_other) && $facility_data->time_attend_sys_other != "") ? $facility_data->time_attend_sys_other : "";

                $facility["licensed_beds"] = (isset($facility_data->licensed_beds) && $facility_data->licensed_beds != "") ? $facility_data->licensed_beds : "";
                $facility["licensed_beds_definition"] = (isset($facility_data->licensed_beds) && $facility_data->licensed_beds != "") ? \App\Providers\AppServiceProvider::keywordTitle($facility_data->licensed_beds) : "";
                $facility["trauma_designation"] = (isset($facility_data->trauma_designation) && $facility_data->trauma_designation != "") ? $facility_data->trauma_designation : "";
                $facility["trauma_designation_definition"] = (isset($facility_data->trauma_designation) && $facility_data->trauma_designation != "") ? \App\Providers\AppServiceProvider::keywordTitle($facility_data->trauma_designation) : "";
                $facility["preferred_specialty"] = (isset($facility_data->preferred_specialty) && $facility_data->preferred_specialty != "") ? strval($facility_data->preferred_specialty) : "";
                $facility["preferred_specialty_definition"] = (isset($facility_data->preferred_specialty) && $facility_data->preferred_specialty != "") ? \App\Providers\AppServiceProvider::keywordTitle($facility_data->preferred_specialty) : "";

                $facility["total_jobs"] = Job::where(['active' => '1', 'facility_id' => $facility_data->id])->count();

                /* rating */
                if ($user_id != "") {
                    $nurse_id = "";
                    $nurse = Nurse::where(['user_id' => $user_id]);
                    if ($nurse->count() > 0) {
                        $nurse_data = $nurse->first();
                        $nurse_id = (isset($nurse_data->id) && $nurse_data->id != "") ? $nurse_data->id : "";
                    }
                    if ($nurse_id != "")
                        $facility_rating_where = ['facility_id' => $facility_data->id, 'nurse_id' => $nurse_id];
                    else $facility_rating_where = ['facility_id' => $facility_data->id];
                } else {
                    $facility_rating_where = ['facility_id' => $facility_data->id];
                }
                $rating_info = FacilityRating::where($facility_rating_where);
                $overall = $on_board = $nurse_team_work = $leadership_support = $tools_todo_my_job = $a = [];
                if ($rating_info->count() > 0) {
                    foreach ($rating_info->get() as $key => $r) {
                        $overall[] = $r->overall;
                        $on_board[] = $r->on_board;
                        $nurse_team_work[] = $r->nurse_team_work;
                        $leadership_support[] = $r->leadership_support;
                        $tools_todo_my_job[] = $r->tools_todo_my_job;
                    }
                }
                $rating['over_all'] = $this->ratingCalculation(count($overall), $overall);
                $rating['on_board'] = $this->ratingCalculation(count($on_board), $on_board);
                $rating['nurse_team_work'] = $this->ratingCalculation(count($nurse_team_work), $nurse_team_work);
                $rating['leadership_support'] = $this->ratingCalculation(count($leadership_support), $leadership_support);
                $rating['tools_todo_my_job'] = $this->ratingCalculation(count($tools_todo_my_job), $tools_todo_my_job);

                /* rating */
                $facility["rating"] = $rating;

                $is_follow = "0";
                if ($user_id != "")
                    $is_follow = FacilityFollows::where(['facility_id' => $facility_data->facility_id, "follow_status" => "1", 'status' => "1", "user_id" => $user_id])->count();

                $facility["is_follow"] = $is_follow;

                $is_like = "0";
                if ($user_id != "")
                    $is_like = FacilityFollows::where(['facility_id' => $facility_data->facility_id, "like_status" => "1", 'status' => "1", "user_id" => $user_id])->count();

                $facility["is_like"] = $is_like;

                $result[] = $facility;
            }
        }
        return $result;
    }

    public function jobOffered(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'user_id' => 'required',
        ]);

        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            $nurse = Nurse::where('user_id', '=', $request->user_id)->get();
            if ($nurse->count() > 0) {
                $nurse = $nurse->first();
                $whereCond = [
                    'active' => true,
                    'status' => 'Pending'
                ];
                $offers = Offer::where($whereCond)
                    ->where('nurse_id', $nurse->id)
                    ->where('expiration', '>=', date('Y-m-d H:i:s'))
                    ->whereNotNull('job_id')
                    ->orderBy('created_at', 'desc');

                $o_data['offer'] = [];
                $limit = 25;
                $total_pages = ceil($offers->count() / $limit);
                $o_data['total_pages_available'] =  strval($total_pages);
                $o_data["current_page"] = (isset($_REQUEST['page']) && is_numeric($_REQUEST['page'])) ? $_REQUEST['page'] : "1";
                $o_data['results_per_page'] = strval($limit);

                if ($offers->count() > 0) {
                    $result = $offers->paginate($limit);

                    /* common */
                    $controller = new Controller();
                    $specialties = $controller->getSpecialities()->pluck('title', 'id');
                    $assignmentDurations = $this->getAssignmentDurations()->pluck('title', 'id');
                    $shifts = $this->getShifts()->pluck('title', 'id');
                    $workLocations = $controller->getGeographicPreferences()->pluck('title', 'id');
                    $leadershipRoles = $this->getLeadershipRoles()->pluck('title', 'id');
                    $seniorityLevels = $this->getSeniorityLevel()->pluck('title', 'id');
                    $jobFunctions = $this->getJobFunction()->pluck('title', 'id');
                    $ehrProficienciesExp = $this->getEHRProficiencyExp()->pluck('title', 'id');
                    $weekDays = $this->getWeekDayOptions();
                    /* common */

                    foreach ($result as $key => $off_val) {
                        $jobinfo = Job::where(['id' => $off_val->job_id])->get()->first();
                        $facility_info = Facility::where(['id' => $jobinfo->facility_id])->get()->first();

                        $days = [];
                        if (isset($jobinfo->preferred_days_of_the_week)) {
                            $day_s = explode(",", $jobinfo->preferred_days_of_the_week);
                            if (is_array($day_s) && !empty($day_s)) {
                                foreach ($day_s as $day) {
                                    if ($day == "Sunday") $days[] = "Su";
                                    elseif ($day == "Monday") $days[] = "M";
                                    elseif ($day == "Tuesday") $days[] = "T";
                                    elseif ($day == "Wednesday") $days[] = "W";
                                    elseif ($day == "Thursday") $days[] = "Th";
                                    elseif ($day == "Friday") $days[] = "F";
                                    elseif ($day == "Saturday") $days[] = "Sa";
                                }
                            }
                        }

                        $facility_logo = "";
                        if ($facility_info->facility_logo) {
                            $t = \Illuminate\Support\Facades\Storage::exists('assets/facilities/facility_logo/' . $facility_info->facility_logo);
                            if ($t) {
                                $facility_logo = \Illuminate\Support\Facades\Storage::get('assets/facilities/facility_logo/' . $facility_info->facility_logo);
                            }
                        }

                        $o_data['offer'][] = [
                            "offer_expiration" => date('d-m-Y h:i A', strtotime($off_val->expiration)),
                            "offer_id" => $off_val->id,
                            "job_id" => $off_val->job_id,
                            "facility_logo" => (isset($facility_info->facility_logo) && $facility_info->facility_logo != "") ? url("storage/assets/facilities/facility_logo/" . $facility_info->facility_logo) : "",
                            "facility_logo_base" => ($facility_logo != "") ? 'data:image/jpeg;base64,' . base64_encode($facility_logo) : "",
                            "facility_name" => (isset($facility_info->name) && $facility_info->name != "") ? $facility_info->name : "",
                            "job_title" => \App\Providers\AppServiceProvider::keywordTitle($jobinfo->preferred_specialty),
                            "assignment_duration" => (isset($jobinfo->preferred_assignment_duration)) ? $jobinfo->preferred_assignment_duration : "",
                            "assignment_duration_definition" => (isset($assignmentDurations[$jobinfo->preferred_assignment_duration])) ? $assignmentDurations[$jobinfo->preferred_assignment_duration] : "",
                            "shift_definition" => "Days",
                            "working_days" => (!empty($days)) ? implode(",", $days) : "",
                            "working_days_definition" => $days,
                            "hourly_pay_rate" => isset($jobinfo->preferred_hourly_pay_rate) ? $jobinfo->preferred_hourly_pay_rate : "0",
                            "start_date" => (isset($jobinfo->start_date) && $jobinfo->start_date != "") ? $jobinfo->start_date : "",
                            "end_date" => (isset($jobinfo->end_date) && $jobinfo->end_date != "") ? $jobinfo->end_date : "",
                            "status" => "pending",
                        ];
                    }
                    $this->check = "1";
                    $this->message = "Job offers listed successfully";
                } else {
                    $this->message = "Currently no offers for you";
                }
                $this->return_data = $o_data;
            } else {
                $this->message = "Nurse not found";
            }
        }
        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function viewJobOffered(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'user_id' => 'required',
            'offer_id' => 'required',
        ]);

        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            $nurse = Nurse::where('user_id', '=', $request->user_id)->get()->first();
            $whereCond = [
                'active' => true,
            ];
            /* 'status' => 'Pending' */
            $offers = Offer::where($whereCond)
                ->where('id', $request->offer_id)
                ->where('nurse_id', $nurse->id)
                ->whereNotNull('job_id')
                ->orderBy('created_at', 'desc');

            $o_data = [];
            if ($offers->count() > 0) {
                $result = $offers->paginate(10);

                /* common */
                $controller = new Controller();
                $specialties = $controller->getSpecialities()->pluck('title', 'id');
                $assignmentDurations = $this->getAssignmentDurations()->pluck('title', 'id');
                $shifts = $this->getShifts()->pluck('title', 'id');
                $workLocations = $controller->getGeographicPreferences()->pluck('title', 'id');
                $leadershipRoles = $this->getLeadershipRoles()->pluck('title', 'id');
                $seniorityLevels = $this->getSeniorityLevel()->pluck('title', 'id');
                $jobFunctions = $this->getJobFunction()->pluck('title', 'id');
                $ehrProficienciesExp = $this->getEHRProficiencyExp()->pluck('title', 'id');
                $weekDays = $this->getWeekDayOptions();
                /* common */

                foreach ($result as $key => $off_val) {
                    $jobinfo = Job::where(['id' => $off_val->job_id])->get()->first();
                    $facility_info = Facility::where(['id' => $jobinfo->facility_id])->get()->first();

                    $days = [];
                    if (isset($jobinfo->preferred_days_of_the_week)) {
                        $day_s = explode(",", $jobinfo->preferred_days_of_the_week);
                        if (is_array($day_s) && !empty($day_s)) {
                            foreach ($day_s as $day) {
                                if ($day == "Sunday") $days[] = "Su";
                                elseif ($day == "Monday") $days[] = "M";
                                elseif ($day == "Tuesday") $days[] = "T";
                                elseif ($day == "Wednesday") $days[] = "W";
                                elseif ($day == "Thursday") $days[] = "Th";
                                elseif ($day == "Friday") $days[] = "F";
                                elseif ($day == "Saturday") $days[] = "Sa";
                            }
                        }
                    }

                    $about_job = [
                        'seniority_level' => (isset($jobinfo->seniority_level)) ? strval($jobinfo->seniority_level) : "",
                        'seniority_level_definition' => (isset($seniorityLevels[$jobinfo->seniority_level])) ? $seniorityLevels[$jobinfo->seniority_level] : "",
                        'preferred_shift_duration' => (isset($jobinfo->preferred_shift_duration)) ? strval($jobinfo->preferred_shift_duration) : "",
                        'preferred_shift_duration_definition' => (isset($shifts[$jobinfo->preferred_shift_duration])) ? $shifts[$jobinfo->preferred_shift_duration] : "",
                        'preferred_experience' => isset($jobinfo->preferred_experience) ? $jobinfo->preferred_experience : "",
                        'cerner' => (isset($jobinfo->job_cerner_exp)) ? strval($jobinfo->job_cerner_exp) : "",
                        'cerner_definition' => (isset($ehrProficienciesExp[$jobinfo->job_cerner_exp])) ? $ehrProficienciesExp[$jobinfo->job_cerner_exp] : "",
                        'meditech' => (isset($jobinfo->job_meditech_exp)) ? strval($jobinfo->job_meditech_exp) : "",
                        'meditech_definition' => (isset($ehrProficienciesExp[$jobinfo->job_meditech_exp])) ? $ehrProficienciesExp[$jobinfo->job_meditech_exp] : "",
                        'epic' => (isset($jobinfo->job_epic_exp)) ? strval($jobinfo->job_epic_exp) : "",
                        'epic_definition' => (isset($ehrProficienciesExp[$jobinfo->job_epic_exp])) ? $ehrProficienciesExp[$jobinfo->job_epic_exp] : "",
                    ];

                    $rating = [];
                    $rating_flag = "0";
                    $nurse_rating_info = NurseRating::where(['nurse_id' => $nurse->id, 'job_id' => $jobinfo->id, 'status' => '1', 'is_deleted' => '0']);
                    if ($nurse_rating_info->count() > 0) {
                        $rating_flag = "1";
                        $r = $nurse_rating_info->first();
                        $rating['overall'] = (isset($r->overall) && $r->overall != "") ? $r->overall : "0";
                        $rating['clinical_skills'] = (isset($r->clinical_skills) && $r->clinical_skills != "") ? $r->clinical_skills : "0";
                        $rating['nurse_teamwork'] = (isset($r->nurse_teamwork) && $r->nurse_teamwork != "") ? $r->nurse_teamwork : "0";
                        $rating['interpersonal_skills'] = (isset($r->interpersonal_skills) && $r->interpersonal_skills != "") ? $r->interpersonal_skills : "0";
                        $rating['work_ethic'] = (isset($r->work_ethic) && $r->work_ethic != "") ? $r->work_ethic : "0";
                        $rating['experience'] = (isset($r->experience) && $r->experience != "") ? $r->experience : "";
                    }

                    $facility_logo = "";
                    if ($facility_info->facility_logo) {
                        $t = \Illuminate\Support\Facades\Storage::exists('assets/facilities/facility_logo/' . $facility_info->facility_logo);
                        if ($t) {
                            $facility_logo = \Illuminate\Support\Facades\Storage::get('assets/facilities/facility_logo/' . $facility_info->facility_logo);
                        }
                    }

                    $o_data[] = [
                        "offer_id" => $off_val->id,
                        "job_id" => $off_val->job_id,
                        "facility_logo" => (isset($facility_info->facility_logo) && $facility_info->facility_logo != "") ? url("storage/assets/facilities/facility_logo/" . $facility_info->facility_logo) : "",
                        "facility_logo_base" => ($facility_logo != "") ? 'data:image/jpeg;base64,' . base64_encode($facility_logo) : "",
                        "facility_name" => (isset($facility_info->name) && $facility_info->name != "") ? $facility_info->name : "",
                        "preferred_work_location" => (isset($jobinfo->preferred_work_location)) ? strval($jobinfo->preferred_work_location) : "",
                        "preferred_work_location_definition" => (isset($workLocations[$jobinfo->preferred_work_location])) ? $workLocations[$jobinfo->preferred_work_location] : "",
                        "job_title" => \App\Providers\AppServiceProvider::keywordTitle($jobinfo->preferred_specialty),
                        "job_description" => (isset($jobinfo->description)) ? $jobinfo->description : "",
                        "assignment_duration" => (isset($jobinfo->preferred_assignment_duration)) ? $jobinfo->preferred_assignment_duration : "",
                        "assignment_duration_definition" => (isset($assignmentDurations[$jobinfo->preferred_assignment_duration])) ? $assignmentDurations[$jobinfo->preferred_assignment_duration] : "",
                        "shift_definition" => "Days",
                        "working_days" => (!empty($days)) ? implode(",", $days) : "",
                        "working_days_definition" => $days,
                        "hourly_pay_rate" => isset($jobinfo->preferred_hourly_pay_rate) ? $jobinfo->preferred_hourly_pay_rate : "0",
                        "status" => "pending",
                        "about_job" => $about_job,
                        'start_date' => (isset($jobinfo->start_date) && $jobinfo->start_date != "") ? date('d F Y', strtotime($jobinfo->start_date)) : "",
                        'end_date' => (isset($jobinfo->end_date) && $jobinfo->end_date != "") ? date('d F Y', strtotime($jobinfo->end_date)) : "",
                        'rating_flag' => $rating_flag,
                        'rating' => (!empty($rating)) ? $rating : (object)array(),
                        /* 'job_data' => $jobinfo */
                    ];
                }
                // $o_data["current_page"] = (isset($_GET['page']) && is_numeric($_GET['page'])) ? $_GET['page'] : "0";
                $this->check = "1";
                $this->message = "Job offers listed successfully";
            } else {
                $this->message = "Currently no offers for you";
            }
            $this->return_data = $o_data;
        }
        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function jobActive(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'user_id' => 'required',
        ]);

        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            /*  dropdown data's */
            $controller = new Controller();
            $assignmentDurations = $this->getAssignmentDurations()->pluck('title', 'id');
            $specialties = $controller->getSpecialities()->pluck('title', 'id');
            /*  dropdown data's */
            $nurse_info = Nurse::where(['user_id' => $request->user_id]);
            if ($nurse_info->count() > 0) {
                $nurse = $nurse_info->first();

                $limit = 25;
                $ret = Offer::where(['active' => "1", 'nurse_id' => $nurse->id])
                    ->orderBy('created_at', 'desc');
                // ->skip(0)->take($limit);
                $offer_info = $ret->paginate($limit);

                $tot_res = 0;
                $my_jobs['data'] = [];
                if ($offer_info->count() > 0) {
                    foreach ($offer_info as $key => $off) {
                        if ($off->job->end_date >= date('Y-m-d')) {
                            $o['offer_id'] = $off->id;

                            /* facility info */
                            $o['facility_id'] = $o['facility_logo'] = $o['facility_name'] = "";
                            $facility_info = User::where(['id' => $off->created_by]);
                            if ($facility_info->count() > 0) {
                                $facility = $facility_info->first();
                                $o['facility_id'] = (isset($facility->facilities[0]->id) && $facility->facilities[0]->id != "") ? $facility->facilities[0]->id : "";

                                $o['facility_logo'] = (isset($facility->facilities[0]->facility_logo)) ? url('storage/assets/facilities/facility_logo/' . $facility->facilities[0]->facility_logo) : "";
                                $facility_logo = "";
                                if (isset($facility->facilities[0]->facility_logo)) {
                                    $t = \Illuminate\Support\Facades\Storage::exists('assets/facilities/facility_logo/' . $facility->facilities[0]->facility_logo);
                                    if ($t) {
                                        $facility_logo = \Illuminate\Support\Facades\Storage::get('assets/facilities/facility_logo/' . $facility->facilities[0]->facility_logo);
                                    }
                                }
                                $o["facility_logo_base"] = ($facility_logo != "") ? 'data:image/jpeg;base64,' . base64_encode($facility_logo) : "";
                                $o['facility_name'] = (isset($facility->facilities[0]->name) && $facility->facilities[0]->name != "") ? $facility->facilities[0]->name : "";
                            }
                            /* facility info */

                            $o['title'] = (isset($specialties[$off->job->preferred_specialty]) && $specialties[$off->job->preferred_specialty] != "") ? $specialties[$off->job->preferred_specialty] : "";
                            $o['work_duration'] = (isset($off->job->preferred_shift_duration) && $off->job->preferred_shift_duration != "") ? strval($off->job->preferred_shift_duration) : "";
                            $o['work_duration_definition'] = (isset($off->job->preferred_shift_duration) && $off->job->preferred_shift_duration != "") ? \App\Providers\AppServiceProvider::keywordTitle($off->job->preferred_shift_duration) : "";

                            $o['shift'] = (isset($off->job->preferred_shift) && $off->job->preferred_shift != "") ? strval($off->job->preferred_shift) : "";
                            $o['shift_definition'] = (isset($off->job->preferred_shift) && $off->job->preferred_shift != "") ? \App\Providers\AppServiceProvider::keywordTitle($off->job->preferred_shift) : "";

                            $o['work_days'] = (isset($off->job->preferred_days_of_the_week) && $off->job->preferred_days_of_the_week != "") ? $off->job->preferred_days_of_the_week : "";
                            $days = [];
                            if (isset($off->job->preferred_days_of_the_week)) {
                                $day_s = explode(",", $off->job->preferred_days_of_the_week);
                                if (is_array($day_s) && !empty($day_s)) {
                                    foreach ($day_s as $day) {
                                        if ($day == "Sunday") $days[] = "Su";
                                        elseif ($day == "Monday") $days[] = "M";
                                        elseif ($day == "Tuesday") $days[] = "T";
                                        elseif ($day == "Wednesday") $days[] = "W";
                                        elseif ($day == "Thursday") $days[] = "Th";
                                        elseif ($day == "Friday") $days[] = "F";
                                        elseif ($day == "Saturday") $days[] = "Sa";
                                    }
                                }
                            }
                            $o['work_days_array'] = ($o['work_days'] != "") ? $days : [];
                            $o['work_days_string'] = ($o['work_days'] != "") ? implode(",", $days) : "";
                            $o['hourly_rate'] = (isset($off->job->preferred_hourly_pay_rate) && $off->job->preferred_hourly_pay_rate != "") ? strval($off->job->preferred_hourly_pay_rate) : "0";
                            $o['start_date'] = date('d F Y', strtotime($off->job->start_date));
                            $o['end_date'] = date('d F Y', strtotime($off->job->end_date));

                            if ($tot_res == 0) $tot_res += 1; //initialized first page`
                            $tot_res += 1;
                            $my_jobs['data'][] = $o;
                        }
                    }
                }

                $total_pages = ceil($ret->count() / $limit);
                $my_jobs['total_pages_available'] =  strval($total_pages);
                $my_jobs["current_page"] = (isset($_REQUEST['page']) && is_numeric($_REQUEST['page'])) ? $_REQUEST['page'] : "1";
                $my_jobs['results_per_page'] = strval($limit);

                $this->check = "1";
                $this->message = "Active jobs listed successfully";
                $this->return_data = $my_jobs;
            } else {
                $this->message = "Nurse not found";
            }
        }

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function jobCompleted(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'user_id' => 'required',
        ]);

        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            /*  dropdown data's */
            $controller = new Controller();
            $assignmentDurations = $this->getAssignmentDurations()->pluck('title', 'id');
            $specialties = $controller->getSpecialities()->pluck('title', 'id');
            /*  dropdown data's */
            $nurse_info = Nurse::where(['user_id' => $request->user_id]);
            if ($nurse_info->count() > 0) {
                $nurse = $nurse_info->first();

                $limit = 25;
                $ret = Offer::where(['active' => "1", 'nurse_id' => $nurse->id])
                    ->orderBy('created_at', 'desc');
                // ->skip(0)->take($limit);
                $offer_info = $ret->paginate($limit);

                $tot_res = 0;
                $my_jobs['data'] = [];
                if ($offer_info->count() > 0) {
                    foreach ($offer_info as $key => $off) {
                        if ($off->job->end_date < date('Y-m-d')) {
                            $o['offer_id'] = $off->id;

                            /* facility info */
                            $o['facility_id'] = $o['facility_logo'] = $o['facility_name'] = "";
                            $facility_info = User::where(['id' => $off->created_by]);
                            if ($facility_info->count() > 0) {
                                $facility = $facility_info->first();
                                $o['facility_id'] = (isset($facility->facilities[0]->id) && $facility->facilities[0]->id != "") ? $facility->facilities[0]->id : "";

                                $o['facility_logo'] = (isset($facility->facilities[0]->facility_logo)) ? url('storage/assets/facilities/facility_logo/' . $facility->facilities[0]->facility_logo) : "";


                                $facility_logo = "";
                                if (isset($facility->facilities[0]->facility_logo)) {
                                    $t = \Illuminate\Support\Facades\Storage::exists('assets/facilities/facility_logo/' . $facility->facilities[0]->facility_logo);
                                    if ($t) {
                                        $facility_logo = \Illuminate\Support\Facades\Storage::get('assets/facilities/facility_logo/' . $facility->facilities[0]->facility_logo);
                                    }
                                }
                                $o["facility_logo_base"] = ($facility_logo != "") ? 'data:image/jpeg;base64,' . base64_encode($facility_logo) : "";

                                $o['facility_name'] = (isset($facility->facilities[0]->name) && $facility->facilities[0]->name != "") ? $facility->facilities[0]->name : "";
                            }
                            /* facility info */

                            $o['title'] = (isset($specialties[$off->job->preferred_specialty]) && $specialties[$off->job->preferred_specialty] != "") ? $specialties[$off->job->preferred_specialty] : "";
                            $o['work_duration'] = (isset($off->job->preferred_shift_duration) && $off->job->preferred_shift_duration != "") ? strval($off->job->preferred_shift_duration) : "";
                            $o['work_duration_definition'] = (isset($off->job->preferred_shift_duration) && $off->job->preferred_shift_duration != "") ? \App\Providers\AppServiceProvider::keywordTitle($off->job->preferred_shift_duration) : "";
                            $o['shift'] = (isset($off->job->preferred_shift) && $off->job->preferred_shift != "") ? strval($off->job->preferred_shift) : "";
                            $o['shift_definition'] = (isset($off->job->preferred_shift) && $off->job->preferred_shift != "") ? \App\Providers\AppServiceProvider::keywordTitle($off->job->preferred_shift) : "";
                            $o['work_days'] = (isset($off->job->preferred_days_of_the_week) && $off->job->preferred_days_of_the_week != "") ? $off->job->preferred_days_of_the_week : "";
                            $days = [];
                            if (isset($off->job->preferred_days_of_the_week)) {
                                $day_s = explode(",", $off->job->preferred_days_of_the_week);
                                if (is_array($day_s) && !empty($day_s)) {
                                    foreach ($day_s as $day) {
                                        if ($day == "Sunday") $days[] = "Su";
                                        elseif ($day == "Monday") $days[] = "M";
                                        elseif ($day == "Tuesday") $days[] = "T";
                                        elseif ($day == "Wednesday") $days[] = "W";
                                        elseif ($day == "Thursday") $days[] = "Th";
                                        elseif ($day == "Friday") $days[] = "F";
                                        elseif ($day == "Saturday") $days[] = "Sa";
                                    }
                                }
                            }
                            $o['work_days_array'] = ($o['work_days'] != "") ? $days : [];
                            $o['work_days_string'] = ($o['work_days'] != "") ? implode(",", $days) : "";
                            $o['hourly_rate'] = (isset($off->job->preferred_hourly_pay_rate) && $off->job->preferred_hourly_pay_rate != "") ? strval($off->job->preferred_hourly_pay_rate) : "0";
                            $o['start_date'] = date('d F Y', strtotime($off->job->start_date));
                            $o['end_date'] = date('d F Y', strtotime($off->job->end_date));

                            if ($tot_res == 0) $tot_res += 1; //initialized first page`
                            $tot_res += 1;
                            $my_jobs['data'][] = $o;
                        }
                    }
                }

                $total_pages = ceil($ret->count() / $limit);
                $my_jobs['total_pages_available'] =  strval($total_pages);
                $my_jobs["current_page"] = (isset($_REQUEST['page']) && is_numeric($_REQUEST['page'])) ? $_REQUEST['page'] : "1";
                $my_jobs['results_per_page'] = strval($limit);

                $this->check = "1";
                $this->message = "Completed jobs listed successfully";
                $this->return_data = $my_jobs;
            } else {
                $this->message = "Nurse not found";
            }
        }

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function jobAcceptPost(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'user_id' => 'required',
            'offer_id' => 'required',
        ]);

        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            $check_offer = Offer::where(['id' => $request->offer_id, 'status' => 'Pending', 'active' => '1'])
                ->where('expiration', '>=', date('Y-m-d H:i:s'))->get();

            if ($check_offer->count() > 0) {
                $update = Offer::where(['id' => $request->offer_id])->update(['status' => 'Active']);
                if ($update) {
                    $offer = $check_offer->first();
                    $facility_email = $offer->creator->email;

                    $nurse_info = Nurse::where(['id' => $offer->nurse_id]);
                    if ($nurse_info->count() > 0) {
                        $nurse = $nurse_info->first();
                        $user_info = User::where(['id' => $nurse->user_id]);
                        if ($user_info->count() > 0) {
                            $user = $user_info->first(); // nurse user info
                            $facility_user_info = User::where(['id' => $offer->created_by]);
                            if ($facility_user_info->count() > 0) {
                                $facility_user = $facility_user_info->first(); // facility user info
                                $data = [
                                    'to_email' => $user->email,
                                    'to_name' => $user->first_name . ' ' . $user->last_name
                                ];
                                $replace_array = [
                                    '###NURSENAME###' => $user->first_name . ' ' . $user->last_name,
                                    '###FACILITYNAME###' => $facility_user->facilities[0]->name,
                                    '###FACILITYLOCATION###' => $facility_user->facilities[0]->city . ',' . $facility_user->facilities[0]->state,
                                    '###SPECIALITY###' => \App\Providers\AppServiceProvider::keywordTitle($offer->job->preferred_specialty),
                                    '###STARTDATE###' => date('d F Y', strtotime($offer->job->start_date)),
                                    '###ASSIGNMENTDURATION###' => \App\Providers\AppServiceProvider::keywordTitle($offer->job->preferred_assignment_duration),
                                    '###SHIFTDURATION###' => \App\Providers\AppServiceProvider::keywordTitle($offer->job->preferred_shift_duration),
                                    '###PREFERREDSHIFT###' => \App\Providers\AppServiceProvider::keywordTitle($offer->job->preferred_shift),
                                ];
                                $this->basic_email($template = "accept_offer_nurse", $data, $replace_array);

                                $facility_data = [
                                    'to_email' => $facility_user->email,
                                    'to_name' => $facility_user->first_name . ' ' . $facility_user->last_name
                                ];

                                $facility_replace_array = [
                                    '###USERNAME###' => $facility_user->first_name . ' ' . $facility_user->last_name,
                                    '###NURSENAME###' => $user->first_name . ' ' . $user->last_name,
                                    '###PREFERREDSPECIALITY###' => \App\Providers\AppServiceProvider::keywordTitle($offer->job->preferred_specialty),
                                    '###NURSEPROFILELINK###' => url('browse-nurses/' . $nurse->slug),
                                    '###FACILITYNAME###' => $facility_user->facilities[0]->name,
                                    '###SPECIALITY###' => \App\Providers\AppServiceProvider::keywordTitle($offer->job->preferred_specialty),
                                    '###STARTDATE###' => date('d F Y', strtotime($offer->job->start_date)),
                                    '###ASSIGNMENTDURATION###' => \App\Providers\AppServiceProvider::keywordTitle($offer->job->preferred_assignment_duration),
                                    '###SHIFTDURATION###' => \App\Providers\AppServiceProvider::keywordTitle($offer->job->preferred_shift_duration),
                                    '###PREFERREDSHIFT###' => \App\Providers\AppServiceProvider::keywordTitle($offer->job->preferred_shift),
                                ];

                                $this->basic_email($template = "accept_offer_confirmation_facility", $facility_data, $facility_replace_array);
                            }
                        }
                    }

                    $this->check = "1";
                    $this->message = "You have accepted this job successfully";
                    $this->return_data = $offer;
                } else {
                    $this->return_data = "Failed to accept the job, Please try again later";
                }
            } else {
                $this->message = "Offer not found";
            }
        }

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function jobRejectPost(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'user_id' => 'required',
            'offer_id' => 'required'
        ]);
        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            $check_offer = Offer::where(['id' => $request->offer_id, 'status' => 'Pending', 'active' => '1'])
                ->where('expiration', '>=', date('Y-m-d H:i:s'));
            if ($check_offer->count() > 0) {
                $offer = $check_offer->first();
                $update = Offer::where(['id' => $request->offer_id])->update(['status' => 'Rejected']);
                if ($update) {
                    $nurse_info = Nurse::where(['id' => $offer->nurse_id]);
                    if ($nurse_info->count() > 0) {
                        $nurse = $nurse_info->first();
                        $user_info = User::where(['id' => $nurse->user_id]);
                        if ($user_info->count() > 0) {
                            $user = $user_info->first(); // nurse user info
                            $facility_user_info = User::where(['id' => $offer->created_by]);
                            if ($facility_user_info->count() > 0) {
                                $facility_user = $facility_user_info->first(); // facility user info
                                /* nurse email */
                                $data = [
                                    'to_email' => $user->email,
                                    'to_name' => $user->first_name . ' ' . $user->last_name
                                ];
                                $replace_array = [
                                    '###NURSENAME###' => $user->first_name . ' ' . $user->last_name,
                                    '###FACILITYNAME###' => $facility_user->facilities[0]->name
                                ];
                                $this->basic_email($template = "reject_offer_nurse", $data, $replace_array);
                                /* nurse email */

                                /* facility user */
                                $facility_data = [
                                    'to_email' => $facility_user->email,
                                    'to_name' => $facility_user->first_name . ' ' . $facility_user->last_name
                                ];
                                $facility_replace_array = [
                                    '###USERNAME###' => $facility_user->first_name . ' ' . $facility_user->last_name,
                                    '###NURSENAME###' => $user->first_name . ' ' . $user->last_name,
                                    '###PREFERREDSPECIALITY###' => \App\Providers\AppServiceProvider::keywordTitle($offer->job->preferred_specialty),
                                    '###FACILITYNAME###' => $facility_user->facilities[0]->name,
                                ];
                                $this->basic_email($template = "reject_offer_facility", $facility_data, $facility_replace_array);
                                /* facility user */
                            }
                        }
                    }
                    $this->check = "1";
                    $this->return_data = "You have rejected this job successfully";
                    $this->return_data = $offer;
                } else {
                    $this->return_data = "Failed to reject the job, Please try again later";
                }
            } else {
                $this->message = "Offer not found";
            }
        }

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function notification(Request $request)
    {
        $nurse_info = NURSE::where('user_id', $request->user_id);
        if ($nurse_info->count() > 0) {
            $nurse = $nurse_info->first();
            $whereCond = ['active' => true];
            $ret = Offer::where($whereCond)
                ->where('nurse_id', $nurse->id)
                ->whereNotNull('job_id')
                ->where('is_view', false)
                ->where('expiration', '>=', date('Y-m-d H:i:s'))
                ->orderBy('created_at', 'desc')->get();

            if ($ret->count() > 0) {
                $n = [];
                $notifications = $ret;
                foreach ($notifications as $notification) {
                    $n[] = [
                        "notification_id" => $notification->id, "message" => "You have a new offer from " . $notification->job->facility->name . " that matches your assignment preference and or profile. Please see <b style='color:#2BE3BD'> " . \App\Providers\AppServiceProvider::keywordTitle($notification->job->preferred_specialty) . " </b> to review and accept or reject the offer within 48 hours.", "date" => date('d F', strtotime($notification->created_at))
                    ];
                }
                $this->check = "1";
                $this->message = "Notifications has been listed successfully";
                $this->return_data = $n;
            } else {
                $this->check = "1";
                $this->message = "Currently there are no notifications";
            }
        } else {
            $this->message = "Nurse not found";
        }

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function removeNotification(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'user_id' => 'required',
            "notification_id" => 'required'
        ]);

        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            $nurse_info = NURSE::where('user_id', $request->user_id)->get();
            if ($nurse_info->count() > 0) {
                $nurse = $nurse_info->first();

                $whereCond = ['active' => true, 'id' => $request->notification_id];
                $ret = Offer::where($whereCond)
                    ->where('nurse_id', $nurse->id)
                    ->whereNotNull('job_id')
                    ->where('is_view', false)
                    // ->where('expiration', '>=', date('Y-m-d H:i:s'))
                    ->orderBy('created_at', 'desc')->get();
                if ($ret->count() > 0) {
                    $notification = $ret->first();

                    $update_array['is_view'] = "1";
                    $update_array['is_view_date'] = date('Y-m-d H:i:s');
                    $update = Offer::where(['id' => $notification->id])->update($update_array);
                    if ($update == true) {
                        $this->check = "1";
                        $this->message = "Notification cleared successfully";
                        // $this->return_data = $notification;
                    } else {
                        $this->message = "Failed to clear notification, Please try again later";
                    }
                } else {
                    $this->message = "Notification already viewed/cleared";
                }
            } else {
                $this->message = "Nurse not found";
            }
        }

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function settings(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'user_id' => 'required',
        ]);

        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            $user_info = USER::where('id', $request->user_id);
            $response = [];
            if ($user_info->count() > 0) {
                $user = $user_info->get()->first();
                $nurse_info = NURSE::where('user_id', $user->id);
                if ($nurse_info->count() > 0) {
                    $nurse = $nurse_info->get()->first();
                    $response["first_name"] = (isset($user->first_name) && $user->first_name != "") ? $user->first_name : "";
                    $response["last_name"] = (isset($user->last_name) && $user->last_name != "") ? $user->last_name : "";
                    $response["full_name"] = $user->first_name . " " . $user->last_name;
                    $response["profile_picture"] = url('storage/assets/nurses/profile/' . $nurse->user->image);
                    $profileNurse = \Illuminate\Support\Facades\Storage::get('assets/nurses/8810d9fb-c8f4-458c-85ef-d3674e2c540a');
                    if ($nurse->user->image) {
                        $t = \Illuminate\Support\Facades\Storage::exists('assets/nurses/profile/' . $nurse->user->image);
                        if ($t) {
                            $profileNurse = \Illuminate\Support\Facades\Storage::get('assets/nurses/profile/' . $nurse->user->image);
                        }
                    }
                    $response["profile_picture_base"] = 'data:image/jpeg;base64,' . base64_encode($profileNurse);

                    $response["address"] = (isset($nurse->address) && $nurse->address != "") ? $nurse->address : "";
                    $response["city"] = (isset($nurse->city) && $nurse->city != "") ? $nurse->city : "";
                    $response["state"] = (isset($nurse->state) && $nurse->state != "") ? $nurse->state : "";
                    $response["postcode"] = (isset($nurse->postcode) && $nurse->postcode != "") ? $nurse->postcode : "";
                    $response["country"] = (isset($nurse->country) && $nurse->country != "") ? $nurse->country : "";
                    $response["nursing_license_number"] = (isset($nurse->nursing_license_number) && $nurse->nursing_license_number != "") ? $nurse->nursing_license_number : "";
                    $response["bil_rate"] = (isset($nurse->hourly_pay_rate) && $nurse->hourly_pay_rate != "") ? $nurse->hourly_pay_rate : "5";
                    $exp = (isset($nurse->experience_as_acute_care_facility) && $nurse->experience_as_acute_care_facility != "") ? $nurse->experience_as_acute_care_facility : "0";
                    $non_exp = (isset($nurse->experience_as_ambulatory_care_facility) && $nurse->experience_as_ambulatory_care_facility != "") ? $nurse->experience_as_ambulatory_care_facility : "0";
                    $response["experience"] = strval($exp + $non_exp);
                    /* availability */
                    $availability = Availability::where('nurse_id', $nurse->id);
                    $response["shift"] = $response["shift_definition"] = "";
                    if ($availability->count() > 0) {
                        $preferredShifts = $this->getPreferredShift()->pluck('title', 'id');
                        $avail = $availability->get()->first();
                        $response["shift"] = (isset($avail->preferred_shift) && $avail->preferred_shift != "") ? strval($avail->preferred_shift) : "";
                        $response["shift_definition"] = (isset($preferredShifts[$avail->preferred_shift]) && $preferredShifts[$avail->preferred_shift] != "") ? $preferredShifts[$avail->preferred_shift] : "";
                    }
                    /* availability */
                    $this->check = "1";
                    $this->message = "Nurse info listed successfully";
                } else {
                    $this->message = "Nurse not found";
                }
                $this->return_data =  $response;
            } else {
                $this->message = "User not found";
            }
        }

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function NurseProfileInfo(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'user_id' => 'required',
        ]);

        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            $user_info = USER::where('id', $request->user_id);
            if ($user_info->count() > 0) {
                $user = $user_info->first();
                $this->check = "1";
                $this->message = "User profile details listed successfully";
                $this->return_data = $this->profileCompletionFlagStatus($type = "", $user);
            } else {
                $this->message = "Nurse not found";
            }
        }

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function updateRoleInterest(Request $request)
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

        $validator = \Validator::make(
            $request->all(),
            [
                'user_id' => 'required',
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
            ],
            $messages
        );

        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            $user_info = USER::where('id', $request->user_id);
            $response = [];
            if ($user_info->count() > 0) {
                $user = $user_info->get()->first();
                $nurse_info = NURSE::where('user_id', $user->id);
                if ($nurse_info->count() > 0) {
                    $nurse = $nurse_info->get()->first();

                    if (preg_match('/https?:\/\/(?:[\w]+\.)*youtube\.com\/watch\?v=[^&]+/', $request->nu_video, $vresult)) {
                        $youTubeID = $this->parse_youtube($request->nu_video);
                        $embedURL = 'https://www.youtube.com/embed/' . $youTubeID[1];
                        $nurse_array["nu_video_embed_url"] = $embedURL;
                    } elseif (preg_match('/https?:\/\/(?:[\w]+\.)*vimeo\.com(?:[\/\w]*\/videos?)?\/([0-9]+)[^\s]*+/', $request->nu_video, $vresult)) {
                        $vimeoID = $this->parse_vimeo($request->nu_video);
                        $embedURL = 'https://player.vimeo.com/video/' . $vimeoID[1];
                        $nurse_array["nu_video_embed_url"] = $embedURL;
                    }
                    if (isset($request->serving_preceptor) && $request->serving_preceptor != "") $nurse_array['serving_preceptor'] = $request->serving_preceptor;
                    else $nurse_array['serving_preceptor'] = "0";
                    if (isset($request->serving_interim_nurse_leader) && $request->serving_interim_nurse_leader != "") $nurse_array['serving_interim_nurse_leader'] = $request->serving_interim_nurse_leader;
                    else $nurse_array['serving_interim_nurse_leader'] = "0";
                    if (isset($request->clinical_educator) && $request->clinical_educator != "") $nurse_array['clinical_educator'] = $request->clinical_educator;
                    else $nurse_array['clinical_educator'] = "0";
                    if (isset($request->is_daisy_award_winner) && $request->is_daisy_award_winner != "") $nurse_array['is_daisy_award_winner'] = $request->is_daisy_award_winner;
                    else $nurse_array['is_daisy_award_winner'] = "0";
                    if (isset($request->employee_of_the_mth_qtr_yr) && $request->employee_of_the_mth_qtr_yr != "") $nurse_array['employee_of_the_mth_qtr_yr'] = $request->employee_of_the_mth_qtr_yr;
                    else $nurse_array['employee_of_the_mth_qtr_yr'] = "0";
                    if (isset($request->other_nursing_awards) && $request->other_nursing_awards != "") $nurse_array['other_nursing_awards'] = $request->other_nursing_awards;
                    else $nurse_array['other_nursing_awards'] = "0";
                    if (isset($request->is_professional_practice_council) && $request->is_professional_practice_council != "") $nurse_array['is_professional_practice_council'] = $request->is_professional_practice_council;
                    else $nurse_array['is_professional_practice_council'] = "0";
                    if (isset($request->is_research_publications) && $request->is_research_publications != "") $nurse_array['is_research_publications'] = $request->is_research_publications;
                    else $nurse_array['is_research_publications'] = "0";
                    if (isset($request->leadership_roles) && $request->leadership_roles != "") $nurse_array['leadership_roles'] = $request->leadership_roles;
                    if (isset($request->languages) && $request->languages != "") $nurse_array['languages'] = $request->languages;
                    if (isset($request->summary) && $request->summary != "") $nurse_array['summary'] = $request->summary;
                    /* if (isset($request->languages) && $request->languages) {
                        $explode = explode(",", $request->languages);
                        $nurse_array['languages'] = (is_array($explode) && !empty($explode)) ? implode(",", $explode) : "";
                    } */
                    $nurse_update = NURSE::where(['id' => $nurse->id])->update($nurse_array);

                    $additional_pictures_status = false;
                    if ($additional_photos = $request->file('additional_pictures')) {
                        foreach ($additional_photos as $additional_photo) {
                            $additional_photo_name_full = $additional_photo->getClientOriginalName();
                            $additional_photo_name = pathinfo($additional_photo_name_full, PATHINFO_FILENAME);
                            $additional_photo_ext = $additional_photo->getClientOriginalExtension();
                            $additional_photo_finalname = $additional_photo_name . '_' . time() . '.' . $additional_photo_ext;
                            //Upload Image
                            $additional_photo->storeAs('assets/nurses/additional_photos/' . $nurse->id, $additional_photo_finalname);
                            $additional_pictures_insert = NurseAsset::create([
                                'nurse_id' => $nurse->id,
                                'name' => $additional_photo_finalname,
                                'filter' => 'additional_photos'
                            ]);

                            if ($additional_pictures_insert) $additional_pictures_status = true;
                        }
                    }

                    $additional_files_status = false;
                    if ($additional_files = $request->file('additional_files')) {
                        foreach ($additional_files as $additional_file) {
                            $additional_file_name_full = $additional_file->getClientOriginalName();
                            $additional_file_name = pathinfo($additional_file_name_full, PATHINFO_FILENAME);
                            $additional_file_ext = $additional_file->getClientOriginalExtension();
                            $additional_file_finalname = $additional_file_name . '_' . time() . '.' . $additional_file_ext;
                            //Upload Image
                            $additional_file->storeAs('assets/nurses/additional_files/' . $nurse->id, $additional_file_finalname);
                            $additional_files_insert = NurseAsset::create([
                                'nurse_id' => $nurse->id,
                                'name' => $additional_file_finalname,
                                'filter' => 'additional_files'
                            ]);

                            if ($additional_files_insert) $additional_files_status = true;
                        }
                    }

                    if ($nurse_update == true || ($additional_pictures_status == true || $additional_files_status == true)) {
                        $this->check = "1";
                        $this->message = "Role Interest updated successfully";
                        $this->return_data = $this->profileCompletionFlagStatus($type = "", $user);
                    } else {
                        $this->message = "Failed to update role interest, Please try again later";
                    }
                }
            }
        }

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function destroyRoleInterestDocument(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'user_id' => 'required',
            "asset_id" => 'required'
        ]);

        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            $nurse_info = NURSE::where('user_id', $request->user_id)->get();
            if ($nurse_info->count() > 0) {
                $nurse = $nurse_info->first();
                $nurse_assets = NurseAsset::where(['id' => $request->asset_id])->get();
                if ($nurse_assets->count() > 0) {
                    $nurseAsset = $nurse_assets->first();
                    $t = Storage::exists('assets/nurses/' . $nurseAsset->filter . '/' . $nurse->id . '/' . $nurseAsset->name);
                    if ($t && $nurseAsset->name) {
                        Storage::delete('assets/nurses/' . $nurseAsset->filter . '/' . $nurse->id . '/' . $nurseAsset->name);
                    }
                    $delete = $nurseAsset->delete();
                    if ($delete) {
                        $this->check = "1";
                        $this->message = "Document removed successfully";
                    } else {
                        $this->message = "Failed to remove document, Please try again later";
                    }
                } else {
                    $this->message = "Document already removed/not found";
                }
            } else {
                $this->message = "User not found";
            }
        }
        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function profilePictureUpload(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'user_id' => 'required',
            'profile_image' => "required"
        ]);

        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            $user_info = USER::where('id', $request->user_id);
            $response = [];
            if ($user_info->count() > 0) {
                $user = $user_info->get()->first();
                $nurse_info = NURSE::where('user_id', $user->id);
                if ($nurse_info->count() > 0) {
                    $nurse = $nurse_info->get()->first();
                    if ($request->hasFile('profile_image') && $request->file('profile_image') != null) {
                        $request->file('profile_image')->storeAs('assets/nurses/profile', $nurse->id);
                        $update_array['image'] = $nurse->id;
                        $update = USER::where(['id' => $user->id])->update($update_array);
                        if ($update == true) {
                            $this->check = "1";
                            $this->message = "Profile picture updated successfully";
                        } else {
                            $this->message = "Failed to update profile picture, please try again later";
                        }
                    } else {
                        $this->message = "Profile image not found";
                    }
                } else {
                    $this->message = "NUrse not found";
                }
            } else {
                $this->message = "User not found";
            }
        }

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function termsAndConditions()
    {
        $this->message = "Terms and Conditions";
        $this->check = "1";
        $this->return_data = '<p>Provides an online resource for health care professionals. These terms may be changed from time to time and without further notice. Your continued use of the Site after any such changes constitutes your acceptance of the new terms. If you do not agree to abide by these or any future terms, please do not use the Site or download materials from it. GE Healthcare, a division of General Electric Company ("GE"), may terminate, change, suspend or discontinue any aspect of the Site, including the availability of any features, at any time. GE may remove, modify or otherwise change any content, including that of third parties, on or from this Site.</p><p>Provides an online resource for health care professionals. These terms may be changed from time to time and without further notice. Your continued use of the Site after any such changes constitutes your acceptance of the new terms. If you do not agree to abide by these or any future terms, please do not use the Site or download materials from it. GE Healthcare, a division of General Electric Company ("GE"), may terminate, change, suspend or discontinue any aspect of the Site, including the availability of any features, at any time. GE may remove, modify or otherwise change any content, including that of third parties, on or from this Site. </p> <p>Provides an online resource for health care professionals. These terms may be changed from time to time and without further notice. Your continued use of the Site after any such changes constitutes your acceptance of the new terms. If you do not agree to abide by these or any future terms, please do not use the Site or download materials from it. GE Healthcare, a division of General Electric Company ("GE"), may terminate, change, suspend or discontinue any aspect of the Site, including the availability of any features, at any time. GE may remove, modify or otherwise change any content, including that of third parties, on or from this Site. </p>';
        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function privacyPolicy()
    {
        $this->message = "Privacy Policy";
        $this->check = "1";
        $this->return_data = '<p>Provides an online resource for health care professionals. These terms may be changed from time to time and without further notice. Your continued use of the Site after any such changes constitutes your acceptance of the new terms. If you do not agree to abide by these or any future terms, please do not use the Site or download materials from it. GE Healthcare, a division of General Electric Company ("GE"), may terminate, change, suspend or discontinue any aspect of the Site, including the availability of any features, at any time. GE may remove, modify or otherwise change any content, including that of third parties, on or from this Site.</p><p>Provides an online resource for health care professionals. These terms may be changed from time to time and without further notice. Your continued use of the Site after any such changes constitutes your acceptance of the new terms. If you do not agree to abide by these or any future terms, please do not use the Site or download materials from it. GE Healthcare, a division of General Electric Company ("GE"), may terminate, change, suspend or discontinue any aspect of the Site, including the availability of any features, at any time. GE may remove, modify or otherwise change any content, including that of third parties, on or from this Site. </p> <p>Provides an online resource for health care professionals. These terms may be changed from time to time and without further notice. Your continued use of the Site after any such changes constitutes your acceptance of the new terms. If you do not agree to abide by these or any future terms, please do not use the Site or download materials from it. GE Healthcare, a division of General Electric Company ("GE"), may terminate, change, suspend or discontinue any aspect of the Site, including the availability of any features, at any time. GE may remove, modify or otherwise change any content, including that of third parties, on or from this Site. </p>';
        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function aboutAPP()
    {
        $this->message = "About App";
        $this->check = "1";
        $this->return_data = '<p>Provides an online resource for health care professionals. These terms may be changed from time to time and without further notice. Your continued use of the Site after any such changes constitutes your acceptance of the new terms. If you do not agree to abide by these or any future terms, please do not use the Site or download materials from it. GE Healthcare, a division of General Electric Company ("GE"), may terminate, change, suspend or discontinue any aspect of the Site, including the availability of any features, at any time. GE may remove, modify or otherwise change any content, including that of third parties, on or from this Site.</p><p>Provides an online resource for health care professionals. These terms may be changed from time to time and without further notice. Your continued use of the Site after any such changes constitutes your acceptance of the new terms. If you do not agree to abide by these or any future terms, please do not use the Site or download materials from it. GE Healthcare, a division of General Electric Company ("GE"), may terminate, change, suspend or discontinue any aspect of the Site, including the availability of any features, at any time. GE may remove, modify or otherwise change any content, including that of third parties, on or from this Site. </p> <p>Provides an online resource for health care professionals. These terms may be changed from time to time and without further notice. Your continued use of the Site after any such changes constitutes your acceptance of the new terms. If you do not agree to abide by these or any future terms, please do not use the Site or download materials from it. GE Healthcare, a division of General Electric Company ("GE"), may terminate, change, suspend or discontinue any aspect of the Site, including the availability of any features, at any time. GE may remove, modify or otherwise change any content, including that of third parties, on or from this Site. </p>';
        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function changePassword(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'user_id' => 'required',
            'password' => 'required|string|min:6|max:255|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[\[\]\{\}\';:\.,#?!@$%^&*-]).{6,}$/'
        ]);

        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            $user_info = USER::where('id', $request->user_id);

            if ($user_info->count() > 0) {
                $user = $user_info->get()->first();
                $nurse_info = NURSE::where('user_id', $user->id);
                if ($nurse_info->count() > 0) {
                    $nurse = $nurse_info->get()->first();
                    $update_array['password'] = Hash::make($request->password);
                    $update = USER::where(['id' => $user->id])->update($update_array);
                    if ($update == true) {
                        $this->check = "1";
                        $this->message = "Password changed successfully";
                    } else {
                        $this->message = "Failed to change password, please try again later";
                    }
                } else {
                    $this->message = "Nurse not found";
                }
            } else {
                $this->message = "User not found";
            }
        }

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function facilityRatings(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'user_id' => 'required',
            'facility_id' => 'required',
        ]);

        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            $user_info = USER::where('id', $request->user_id);
            if ($user_info->count() > 0) {
                $user = $user_info->get()->first();
                $nurse_info = NURSE::where('user_id', $user->id);
                if ($nurse_info->count() > 0) {
                    $nurse = $nurse_info->get()->first();
                    $insert_array['nurse_id'] = $nurse->id;
                    if (isset($request->facility_id) && $request->facility_id != "")
                        $insert_array['facility_id'] = $request->facility_id;
                    if (isset($request->overall) && $request->overall != "")
                        $update_array['overall'] = $insert_array['overall'] = $request->overall;
                    if (isset($request->on_board) && $request->on_board != "")
                        $update_array['on_board'] = $insert_array['on_board'] = $request->on_board;
                    if (isset($request->nurse_team_work) && $request->nurse_team_work != "")
                        $update_array['nurse_team_work'] = $insert_array['nurse_team_work'] = $request->nurse_team_work;
                    if (isset($request->leadership_support) && $request->leadership_support != "")
                        $update_array['leadership_support'] = $insert_array['leadership_support'] = $request->leadership_support;
                    if (isset($request->tools_todo_my_job) && $request->tools_todo_my_job != "")
                        $update_array['tools_todo_my_job'] = $insert_array['tools_todo_my_job'] = $request->tools_todo_my_job;
                    if (isset($request->experience) && $request->experience != "")
                        $update_array['experience'] = $insert_array['experience'] = $request->experience;

                    $check_exists = FacilityRating::where(['nurse_id' => $nurse->id, 'facility_id' => $request->facility_id])->get();
                    if ($check_exists->count() > 0) {
                        $rating_row = $check_exists->first();
                        $data = FacilityRating::where(['id' => $rating_row->id])->update($update_array);
                    } else {
                        $data = FacilityRating::create($insert_array);
                    }

                    if (isset($data) && $data == true) {
                        $this->check = "1";
                        $this->message = "Your rating is submitted successfully";
                    } else {
                        $this->message = "Failed to update ratings, Please try again later";
                    }
                } else {
                    $this->message = "Nurse not found";
                }
            } else {
                $this->message = "User not found";
            }
        }

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function newPhoneNumber(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'user_id' => 'required',
            'phone_number' => 'required|regex:/^[0-9 \+]+$/|min:4|max:20',
        ]);

        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            $user_info = USER::where('id', $request->user_id);
            if ($user_info->count() > 0) {
                $user = $user_info->get()->first();
                $otp = substr(str_shuffle("0123456789"), 0, 4);
                $rand_enc = substr(str_shuffle("0123456789AaBbCcDdEeFfGgHhIiJjKkLlMmNnOoPpQqRrSsTtUuVvWwXxYyZz"), 0, 6);
                $update_otp = USER::where(['id' => $user->id])->update(['otp' => $otp, 'new_mobile' => $request->phone_number]);
                if ($update_otp) {
                    $this->check = "1";
                    $this->message = "OTP send successfully to this number";
                    $this->return_data = ['otp' => $otp];
                } else {
                    $this->message = "Failed to send otp, Please try again later";
                }
            } else {
                $this->message = "User not found";
            }
        }

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function confirmOTP(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'user_id' => 'required',
            'otp' => 'required|min:4|max:4',
        ]);

        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            $user_info = USER::where('id', $request->user_id);
            if ($user_info->count() > 0) {
                $user = $user_info->get()->first();
                if ($user->otp == $request->otp) {
                    $update_otp = USER::where(['id' => $user->id])->update(['mobile' => $user->new_mobile, 'otp' => NULL, 'new_mobile' => NULL]);
                    if ($update_otp) {
                        $this->check = "1";
                        $this->message = "Phone number updated successfully";
                    } else {
                        $this->message = "Failed to update phone number, Please try again later";
                    }
                } else {
                    $this->message = "Invalid OTP, Please enter the correct otp";
                }
            } else {
                $this->message = "User not found";
            }
        }

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function getCountries()
    {
        $controller = new controller();
        $countries = $controller->getCountries()->pluck('name', 'id');
        $data = [];
        foreach ($countries as $key => $value) {
            $data[] = ['country_id' => strval($key), "name" => $value];
        }
        // moved usa and canada to top of the row
        $this->moveElement($data, 235, 0);
        $this->moveElement($data, 40, 1);
        // moved usa and canada to top of the row
        $this->check = "1";
        $this->message = "Countries listed successfully";
        $this->return_data = $data;
        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    function moveElement(&$array, $a, $b)
    {
        $out = array_splice($array, $a, 1);
        array_splice($array, $b, 0, $out);
    }

    public function getStates(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'country_id' => 'required',
        ]);

        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            $get_states = States::where(['country_id' => $request->country_id])->get();
            if ($get_states->count() > 0) {
                $states = $get_states;
                $data = [];
                foreach ($states as $key => $value) {
                    $data[] = ['state_id' => strval($value->id), "name" => $value->name, 'iso_name' => $value->iso2];
                }
                $this->check = "1";
                $this->message = "States listed successfully";
                $this->return_data = $data;
            } else {
                $this->return_data = [];
            }
        }

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function getCities(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'state_id' => 'required',
        ]);

        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            $get_cities = Cities::where(['state_id' => $request->state_id])->get();
            if ($get_cities->count() > 0) {
                $cities = $get_cities;
                $data = [];
                foreach ($cities as $key => $value) {
                    $data[] = ['city_id' => strval($value->id), "name" => $value->name];
                }
                $this->check = "1";
                $this->message = "Cities listed successfully";
                $this->return_data = $data;
            } else {
                $this->return_data = [];
            }
        }

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function facilityProfileCompletionFlagStatus($type = "", $user)
    {
        $facility = $user->facilities()->first();

        $states = $this->getStateOptions();
        $facilityTypes = $this->getFacilityType()->pluck('title', 'id');

        $eMedicalRecords = $this->getEMedicalRecords()->pluck('title', 'id');
        $eMedicalRecords['0'] = 'Other';

        $bCheckProviders = $this->getBCheckProvider()->pluck('title', 'id');
        $bCheckProviders['0'] = 'Other';

        $nCredentialingSoftwares = $this->getNCredentialingSoftware()->pluck('title', 'id');
        $nCredentialingSoftwares['0'] = 'Other';

        $nSchedulingSystems = $this->getNSchedulingSystem()->pluck('title', 'id');
        $nSchedulingSystems['0'] = 'Other';

        $timeAttendanceSystems = $this->getTimeAttendanceSystem()->pluck('title', 'id');
        $timeAttendanceSystems['0'] = 'Other';

        $traumaDesignations = $this->getTraumaDesignation()->pluck('title', 'id');
        $traumaDesignations['0'] = 'N/A';

        // $facility_info["facility_id"] = (isset($facility->id) && $facility->id != "") ? $facility->id : "";
        // $facility_info["created_by"] = (isset($facility->created_by) && $facility->created_by != "") ? $facility->created_by : "";
        $facility_info["role"] = "FACILITYADMIN";
        $facility_info["user_id"] = (isset($facility->pivot->user_id) && $facility->pivot->user_id != "") ? $facility->pivot->user_id : "";
        $facility_info["facility_id"] = (isset($facility->pivot->facility_id) && $facility->pivot->facility_id != "") ? $facility->pivot->facility_id : "";
        $facility_info["facility_name"] = (isset($facility->name) && $facility->name != "") ? $facility->name : "";
        $facility_info["facility_address"] = (isset($facility->address) && $facility->address != "") ? $facility->address : "";
        $facility_info["facility_city"] = (isset($facility->city) && $facility->city != "") ? $facility->city : "";
        $facility_info["facility_state"] = (isset($facility->state) && $facility->state != "") ? $facility->state : "";
        $facility_info["facility_postcode"] = (isset($facility->postcode) && $facility->postcode != "") ? $facility->postcode : "";

        $facility_info["facility_logo"] = (isset($facility->facility_logo) && $facility->facility_logo != "") ? url('storage/assets/facilities/facility_logo/' . $facility->facility_logo) : "";
        $facility_logo = "";
        if ($facility->facility_logo) {
            $t = \Illuminate\Support\Facades\Storage::exists('assets/facilities/facility_logo/' . $facility->facility_logo);
            if ($t) {
                $facility_logo = \Illuminate\Support\Facades\Storage::get('assets/facilities/facility_logo/' . $facility->facility_logo);
            }
        }
        $facility_info["facility_logo_base"] = ($facility_logo != "") ? 'data:image/jpeg;base64,' . base64_encode($facility_logo) : "";

        $facility_info["facility_email"] = (isset($facility->facility_email) && $facility->facility_email != "") ? $facility->facility_email : "";
        $facility_info["facility_phone"] = (isset($facility->facility_phone) && $facility->facility_phone != "") ? $facility->facility_phone : "";
        $facility_info["specialty_need"] = (isset($facility->specialty_need) && $facility->specialty_need != "") ? $facility->specialty_need : "";

        $facility_info["facility_type"] = (isset($facility->type) && $facility->type != "") ? strval($facility->type) : "";
        $facility_info["facility_type_definition"] = (isset($facilityTypes[$facility->type]) && $facilityTypes[$facility->type] != "") ? $facilityTypes[$facility->type] : "";
        // $facility_info["active"] = (isset($facility->active) && $facility->active != "") ? $facility->active : "";
        // $facility_info["deleted_at"] = (isset($facility->deleted_at) && $facility->deleted_at != "") ? $facility->deleted_at : "";
        // $facility_info["created_at"] = (isset($facility->created_at) && $facility->created_at != "") ? $facility->created_at : "";
        // $facility_info["updated_at"] = (isset($facility->updated_at) && $facility->updated_at != "") ? $facility->updated_at : "";

        $facility_info["slug"] = (isset($facility->slug) && $facility->slug != "") ? $facility->slug : "";
        $facility_info["cno_message"] = (isset($facility->cno_message) && $facility->cno_message != "") ? $facility->cno_message : "";
        $facility_info["cno_image"] = (isset($facility->cno_image) && $facility->cno_image != "") ? url('storage/assets/facilities/cno_image/' . $facility->cno_image) : "";

        $cno_image = "";
        if ($facility->cno_image) {
            $t = \Illuminate\Support\Facades\Storage::exists('assets/facilities/cno_image/' . $facility->cno_image);
            if ($t) {
                $cno_image = \Illuminate\Support\Facades\Storage::get('assets/facilities/cno_image/' . $facility->cno_image);
            }
        }
        $facility_info["cno_image_base"] = ($cno_image != "") ? 'data:image/jpeg;base64,' . base64_encode($cno_image) : "";

        $facility_info["gallary_images"] = (isset($facility->gallary_images) && $facility->gallary_images != "") ? $facility->gallary_images : "";

        $facility_info["video"] = (isset($facility->video) && $facility->video != "") ? $facility->video : "";

        $facility_info["about_facility"] = (isset($facility->about_facility) && $facility->about_facility != "") ? $facility->about_facility : "";
        $facility_info["facility_website"] = (isset($facility->facility_website) && $facility->facility_website != "") ? $facility->facility_website : "";
        $facility_info["video_embed_url"] = (isset($facility->video_embed_url) && $facility->video_embed_url != "") ? $facility->video_embed_url : "";
        // $facility_info["f_lat"] = (isset($facility->f_lat) && $facility->f_lat != "") ? $facility->f_lat : "";
        // $facility_info["f_lang"] = (isset($facility->f_lang) && $facility->f_lang != "") ? $facility->f_lang : "";

        /* facility_emr */
        $facility_info["facility_emr"] = (isset($facility->f_emr) && $facility->f_emr != "") ? strval($facility->f_emr) : "";
        $facility_info["facility_emr_definition"] = (isset($eMedicalRecords[$facility->f_emr]) && $eMedicalRecords[$facility->f_emr] != "") ? $eMedicalRecords[$facility->f_emr] : "";
        $facility_info["facility_emr_other"] = (($facility_info["facility_emr"] == "other" || $facility_info["facility_emr"] == "0") && ((isset($facility->f_emr_other) && $facility->f_emr_other != ""))) ? $facility->f_emr_other : "";
        /* facility_emr */

        /* facility_bcheck_provider */
        $facility_info["facility_bcheck_provider"] = (isset($facility->f_bcheck_provider) && $facility->f_bcheck_provider != "") ? strval($facility->f_bcheck_provider) : "";
        $facility_info["facility_bcheck_provider_definition"] = (isset($bCheckProviders[$facility->f_bcheck_provider]) && $bCheckProviders[$facility->f_bcheck_provider] != "") ? $bCheckProviders[$facility->f_bcheck_provider] : "";
        $facility_info["facility_bcheck_provider_other"] = (($facility_info["facility_bcheck_provider"] == "other" || $facility_info["facility_bcheck_provider"] == "0") && ((isset($facility->f_bcheck_provider_other) && $facility->f_bcheck_provider_other != ""))) ? $facility->f_bcheck_provider_other : "";
        /* facility_bcheck_provider */

        /* nurse_cred_soft */
        $facility_info["nurse_cred_soft"] = (isset($facility->nurse_cred_soft) && $facility->nurse_cred_soft != "") ? strval($facility->nurse_cred_soft) : "";
        $facility_info["nurse_cred_soft_definition"] = (isset($nCredentialingSoftwares[$facility->nurse_cred_soft]) && $nCredentialingSoftwares[$facility->nurse_cred_soft] != "") ? $nCredentialingSoftwares[$facility->nurse_cred_soft] : "";
        $facility_info["nurse_cred_soft_other"] = (($facility_info["nurse_cred_soft"] == "other" || $facility_info["nurse_cred_soft"] == "0") && ((isset($facility->nurse_cred_soft_other) && $facility->nurse_cred_soft_other != ""))) ? $facility->nurse_cred_soft_other : "";
        /* nurse_cred_soft */

        /* nurse_scheduling_sys */
        $facility_info["nurse_scheduling_sys"] = (isset($facility->nurse_scheduling_sys) && $facility->nurse_scheduling_sys != "") ? strval($facility->nurse_scheduling_sys) : "";
        $facility_info["nurse_scheduling_sys_definition"] = (isset($nSchedulingSystems[$facility->nurse_scheduling_sys]) && $nSchedulingSystems[$facility->nurse_scheduling_sys] != "") ? $nSchedulingSystems[$facility->nurse_scheduling_sys] : "";
        $facility_info["nurse_scheduling_sys_other"] = (($facility_info["nurse_scheduling_sys"] == "other" || $facility_info["nurse_scheduling_sys"] == "0") && ((isset($facility->nurse_scheduling_sys_other) && $facility->nurse_scheduling_sys_other != ""))) ? $facility->nurse_scheduling_sys_other : "";
        /* nurse_scheduling_sys */

        /* time_attend_sys */
        $facility_info["time_attend_sys"] = (isset($facility->time_attend_sys) && $facility->time_attend_sys != "") ? strval($facility->time_attend_sys) : "";
        $facility_info["time_attend_sys_definition"] = (isset($timeAttendanceSystems[$facility->time_attend_sys]) &&  $timeAttendanceSystems[$facility->time_attend_sys] != "") ?  $timeAttendanceSystems[$facility->time_attend_sys] : "";
        $facility_info["time_attend_sys_other"] = (($facility_info["time_attend_sys"] == "other" || $facility_info["time_attend_sys"] == "0") && ((isset($facility->time_attend_sys_other) && $facility->time_attend_sys_other != ""))) ? $facility->time_attend_sys_other : "";
        /* time_attend_sys */

        $facility_info["licensed_beds"] = (isset($facility->licensed_beds) && $facility->licensed_beds != "") ? $facility->licensed_beds : "";

        /* trauma_designation */
        $facility_info["trauma_designation"] = (isset($facility->trauma_designation) && $facility->trauma_designation != "") ? strval($facility->trauma_designation) : "";
        $facility_info["trauma_designation_definition"] = (isset($traumaDesignations[$facility->trauma_designation]) && $traumaDesignations[$facility->trauma_designation] != "") ? $traumaDesignations[$facility->trauma_designation] : "";
        /* trauma_designation */

        /* social logins */
        $facility_social = [];
        $facility_social["facebook"] = (isset($facility->facebook) && $facility->facebook != "") ? $facility->facebook : "";
        $facility_social["twitter"] = (isset($facility->twitter) && $facility->twitter != "") ? $facility->twitter : "";
        $facility_social["linkedin"] = (isset($facility->linkedin) && $facility->linkedin != "") ? $facility->linkedin : "";
        $facility_social["instagram"] = (isset($facility->instagram) && $facility->instagram != "") ? $facility->instagram : "";
        $facility_social["pinterest"] = (isset($facility->pinterest) && $facility->pinterest != "") ? $facility->pinterest : "";
        $facility_social["tiktok"] = (isset($facility->tiktok) && $facility->tiktok != "") ? $facility->tiktok : "";
        $facility_social["sanpchat"] = (isset($facility->sanpchat) && $facility->sanpchat != "") ? $facility->sanpchat : "";
        $facility_social["youtube"] = (isset($facility->youtube) && $facility->youtube != "") ? $facility->youtube : "";
        $facility_info['facility_social'] = $facility_social;
        /* social logins */

        $facility_info['facility_profile_flag'] = "0";
        if (
            $facility_info["facility_name"] != "" &&
            $facility_info["facility_type"] != "" &&
            $facility_info["facility_phone"] != "" &&
            $facility_info["facility_address"] != "" &&
            $facility_info["facility_city"] != "" &&
            $facility_info["facility_state"] != "" &&
            $facility_info["facility_postcode"] != "" &&
            $facility_info["facility_emr"] != "" &&
            $facility_info["facility_bcheck_provider"] != "" &&
            $facility_info["nurse_cred_soft"] != "" &&
            $facility_info["nurse_scheduling_sys"] != "" &&
            $facility_info["time_attend_sys"] != ""
        ) $facility_info['facility_profile_flag'] = "1";

        return $facility_info;
    }

    public function facilityDropdown($type)
    {
        if ($type == "getmedicalrecords") {
            $eMedicalRecords = $this->getEMedicalRecords()->pluck('title', 'id');
            $eMedicalRecords['0'] = 'Other';
            $data = [];
            foreach ($eMedicalRecords as $key => $value) {
                $data[] = ["id" => $key, "name" => $value];
            }
            $this->return_data = $data;
        } elseif ($type == "getbcheckprovider") {
            $bCheckProviders = $this->getBCheckProvider()->pluck('title', 'id');
            $bCheckProviders['0'] = 'Other';
            $data = [];
            foreach ($bCheckProviders as $key => $value) {
                $data[] = ["id" => $key, "name" => $value];
            }
            $this->return_data = $data;
        } elseif ($type == "getncredentialingsoftware") {
            $nCredentialingSoftwares = $this->getNCredentialingSoftware()->pluck('title', 'id');
            $nCredentialingSoftwares['0'] = 'Other';
            $data = [];
            foreach ($nCredentialingSoftwares as $key => $value) {
                $data[] = ["id" => $key, "name" => $value];
            }
            $this->return_data = $data;
        } elseif ($type == "getnschedulingsystem") {
            $nSchedulingSystems = $this->getNSchedulingSystem()->pluck('title', 'id');
            $nSchedulingSystems['0'] = 'Other';
            $data = [];
            foreach ($nSchedulingSystems as $key => $value) {
                $data[] = ["id" => $key, "name" => $value];
            }
            $this->return_data = $data;
        } elseif ($type == "gettraumadesignation") {
            $traumaDesignations = $this->getTraumaDesignation()->pluck('title', 'id');
            $traumaDesignations['0'] = 'N/A';
            $data = [];
            foreach ($traumaDesignations as $key => $value) {
                $data[] = ["id" => $key, "name" => $value];
            }
            $this->return_data = $data;
        } elseif ($type == 'gettimeattendancesystem') {
            $timeAttendanceSystems = $this->getTimeAttendanceSystem()->pluck('title', 'id');
            $timeAttendanceSystems['0'] = 'Other';
            $data = [];
            foreach ($timeAttendanceSystems as $key => $value) {
                $data[] = ["id" => $key, "name" => $value];
            }
            $this->return_data = $data;
        }

        $this->check = "1";
        $this->message = "Dropdown options listed successfully";
        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function facilityDetail(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'user_id' => 'required',
            'facility_id' => 'required',
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
            'video_link' => 'nullable|url|max:255',
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
            'f_emr_other' => 'nullable|required_if:f_emr,0|regex:/^[a-zA-Z 0-9,\-\/]+$/|min:1|max:150',
            'f_bcheck_provider' => 'required',
            'f_bcheck_provider_other' => 'nullable|required_if:f_bcheck_provider,0|regex:/^[a-zA-Z 0-9,\-\/]+$/|min:1|max:150',
            'nurse_cred_soft' => 'required',
            'nurse_cred_soft_other' => 'nullable|required_if:nurse_cred_soft,0|regex:/^[a-zA-Z 0-9,\-\/]+$/|min:1|max:150',
            'nurse_scheduling_sys' => 'required',
            'nurse_scheduling_sys_other' => 'nullable|required_if:nurse_scheduling_sys,0|regex:/^[a-zA-Z 0-9,\-\/]+$/|min:1|max:150',
            'time_attend_sys' => 'required',
            'time_attend_sys_other' => 'nullable|required_if:time_attend_sys,0|regex:/^[a-zA-Z 0-9,\-\/]+$/|min:1|max:150',
            'licensed_beds' => 'nullable|regex:/^[0-9]+$/|min:1|max:20',
        ]);

        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            $update_array = [];

            $embedURL = "";
            if (preg_match('/https?:\/\/(?:[\w]+\.)*youtube\.com\/watch\?v=[^&]+/', $request->video_link)) {
                $youTubeID = $this->parse_youtube($request->video_link);
                $embedURL = 'https://www.youtube.com/embed/' . $youTubeID[1];
            } elseif (preg_match('/https?:\/\/(?:[\w]+\.)*vimeo\.com(?:[\/\w]*\/videos?)?\/([0-9]+)[^\s]*+/', $request->video_link)) {
                $vimeoID = $this->parse_vimeo($request->video_link);
                $embedURL = 'https://player.vimeo.com/video/' . $vimeoID[1];
            }
            if ($embedURL != "") $update_array['video_embed_url'] = $embedURL;

            /* required */
            $update_array['name'] = $request->name;
            $update_array['type'] = $request->type;
            $update_array['facility_phone'] = $request->facility_phone;
            $update_array['address'] = $request->address;
            $update_array['city'] = $request->city;
            $update_array['state'] = $request->state;
            $update_array['postcode'] = $request->postcode;
            $update_array['f_emr'] = $request->f_emr;
            $update_array['f_bcheck_provider'] = $request->f_bcheck_provider;
            $update_array['nurse_cred_soft'] = $request->nurse_cred_soft;
            $update_array['nurse_scheduling_sys'] = $request->nurse_scheduling_sys;
            $update_array['time_attend_sys'] = $request->time_attend_sys;
            /* required */

            $facility_id = "";
            if (isset($request->facility_id) && $request->facility_id != "") $facility_id = $request->facility_id;
            if (isset($request->facility_email) && $request->facility_email != "") $update_array["facility_email"] = $request->facility_email;
            if (isset($request->facebook) && $request->facebook != "") $update_array["facebook"] = $request->facebook;
            if (isset($request->twitter) && $request->twitter != "") $update_array["twitter"] = $request->twitter;
            if (isset($request->linkedin) && $request->linkedin != "") $update_array["linkedin"] = $request->linkedin;
            if (isset($request->instagram) && $request->instagram != "") $update_array["instagram"] = $request->instagram;
            if (isset($request->pinterest) && $request->pinterest != "") $update_array["pinterest"] = $request->pinterest;
            if (isset($request->tiktok) && $request->tiktok != "") $update_array["tiktok"] = $request->tiktok;
            if (isset($request->sanpchat) && $request->sanpchat != "") $update_array["sanpchat"] = $request->sanpchat;
            if (isset($request->youtube) && $request->youtube != "") $update_array["youtube"] = $request->youtube;
            if (isset($request->facility_website) && $request->facility_website != "") $update_array["facility_website"] = $request->facility_website;
            if (isset($request->trauma) && $request->trauma != "") $update_array["trauma_designation"] = $request->trauma;
            if (isset($request->senior_leader_message) && $request->senior_leader_message != "") $update_array["cno_message"] = $request->senior_leader_message;
            if (isset($request->about_facility) && $request->about_facility != "") $update_array["about_facility"] = $request->about_facility;

            if (isset($request->f_emr) && $request->f_emr != "") $update_array["f_emr"] = $request->f_emr;
            if (isset($update_array["f_emr"]) && ($update_array["f_emr"] == "other" || $update_array["f_emr"] == "0")) {
                if (isset($update_array["f_emr_other"]) && $update_array["f_emr_other"] != "")  $request->f_emr_other;
            } else $update_array["f_emr_other"] = "";

            if (isset($update_array["f_bcheck_provider"]) && ($update_array["f_bcheck_provider"] == "other" || $update_array["f_bcheck_provider"] == "0")) {
                if (isset($request->f_bcheck_provider_other) && $request->f_bcheck_provider_other != "") $update_array["f_bcheck_provider_other"] = $request->f_bcheck_provider_other;
            } else $update_array["f_bcheck_provider_other"] = "";

            if (isset($update_array["nurse_cred_soft"]) && ($update_array["nurse_cred_soft"] == "other" || $update_array["nurse_cred_soft"] == "0")) {
                if (isset($request->nurse_cred_soft_other) && $request->nurse_cred_soft_other != "") $update_array["nurse_cred_soft_other"] = $request->nurse_cred_soft_other;
            } else $update_array["nurse_cred_soft_other"] = "";

            if (isset($update_array["nurse_scheduling_sys"]) && ($update_array["nurse_scheduling_sys"] == "other" || $update_array["nurse_scheduling_sys"] == "0")) {
                if (isset($request->nurse_scheduling_sys_other) && $request->nurse_scheduling_sys_other != "") $update_array["nurse_scheduling_sys_other"] = $request->nurse_scheduling_sys_other;
            } else $update_array["nurse_scheduling_sys_other"] = "";

            if (isset($update_array["time_attend_sys"]) && ($update_array["time_attend_sys"] == "other" || $update_array["time_attend_sys"] == "0")) {
                if (isset($request->time_attend_sys_other) && $request->time_attend_sys_other != "") $update_array["time_attend_sys_other"] = $request->time_attend_sys_other;
            } else $update_array["time_attend_sys_other"] = "";

            if (isset($request->licensed_beds) && $request->licensed_beds != "") $update_array["licensed_beds"] = $request->licensed_beds;

            if ($request->hasFile('facility_logo')) {
                $facility_logo_name_full = $request->file('facility_logo')->getClientOriginalName();
                $facility_logo_name = pathinfo($facility_logo_name_full, PATHINFO_FILENAME);
                $facility_logo_ext = $request->file('facility_logo')->getClientOriginalExtension();
                $facility_logo = $facility_logo_name . '_' . time() . '.' . $facility_logo_ext;
                $update_array['facility_logo'] = $facility_logo;
                //Upload Image
                $request->file('facility_logo')->storeAs('assets/facilities/facility_logo', $facility_logo);
            }

            if ($request->hasFile('cno_image')) {
                $cno_image_name_full = $request->file('cno_image')->getClientOriginalName();
                $cno_image_name = pathinfo($cno_image_name_full, PATHINFO_FILENAME);
                $cno_image_ext = $request->file('cno_image')->getClientOriginalExtension();
                $cno_image = $cno_image_name . '_' . time() . '.' . $cno_image_ext;
                $update_array['cno_image'] = $cno_image;
                //Upload Image
                $request->file('cno_image')->storeAs('assets/facilities/cno_image', $cno_image);
                // $facility->update();
            }

            if (isset($update_array) && !empty($update_array) && $facility_id != "") {
                $update = Facility::where(['id' => $facility_id])->update($update_array);
                if ($update) {
                    $user_id = $request->user_id;
                    $user = User::where(['id' => $user_id])->get()->first();
                    $this->check = "1";
                    $this->message = "Profile updated successfully";
                    $this->return_data = $this->facilityProfileCompletionFlagStatus($type = "", $user);
                } else {
                    $this->message = "Failed to update profile, Please try again later";
                }
            }
        }

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function changeFacilityLogo(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'api_key' => 'required',
            'facility_id' => 'required',
            'facility_logo' => 'required|max:1024|image|mimes:jpeg,png,jpg,gif',
        ]);

        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            $facility_id = (isset($request->facility_id) && $request->facility_id != "") ? $request->facility_id : "";
            // dd($request->all());
            if ($request->hasFile('facility_logo') && $facility_id != "") {
                $facility_logo_name_full = $request->file('facility_logo')->getClientOriginalName();
                $facility_logo_name = pathinfo($facility_logo_name_full, PATHINFO_FILENAME);
                $facility_logo_ext = $request->file('facility_logo')->getClientOriginalExtension();
                $facility_logo = $facility_logo_name . '_' . time() . '.' . $facility_logo_ext;
                $update_array['facility_logo'] = $facility_logo;
                //Upload Image
                $request->file('facility_logo')->storeAs('assets/facilities/facility_logo', $facility_logo);

                $update = Facility::where(['id' => $facility_id])->update($update_array);
                if ($update) {
                    $this->check = "1";
                    $this->message = "Facility logo updated successfully";
                } else {
                    $this->message = "Failed to update profile, Please try again later";
                }
            } else {
                $this->message = "Required parameters not found";
            }
        }

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function browseNurses(Request $request)
    {
        $whereCond = ['active' => true];
        if (isset($request->nurse_id) && $request->nurse_id != "") {
            $whereCond = array_merge($whereCond, ['id' => $request->nurse_id]);
        }
        $ret = [];
        Nurse::where($whereCond)
            ->orderBy('created_at', 'desc');
        /* new */

        // dd($request->all());
        $whereCond = [
            'active' => true
        ];
        $ret = Nurse::where($whereCond)
            ->orderBy('created_at', 'desc');

        $certification = $request->certification;

        $specialty = (isset($request->specialty) && $request->specialty != "") ? json_decode($request->specialty) : [];
        /*specialty filter nwe update 06/dec/2021 */
        if (is_array($specialty) && !empty($specialty)) {
            $ret->where(function (Builder $query) use ($specialty) {
                foreach ($specialty as $key => $search_spl_id) {
                    if ($search_spl_id != "")
                        $query->orWhere('specialty', 'like', '%' . $search_spl_id . '%');
                }
            });
        }
        /*specialty filter nwe update 06/dec/2021 */

        $availability = (isset($request->availability) && $request->availability != "") ? json_decode($request->availability) : [];
        if (is_array($availability) && !empty($availability)) {
            $ret->whereHas('availability', function (Builder $query1) use ($availability) {
                $query1->whereIn('days_of_the_week', $availability);
            });
        }

        $search_bill_rate_from = (isset($request->search_bill_rate_from) && $request->search_bill_rate_from != "") ? $request->search_bill_rate_from : "";
        $search_bill_rate_to = (isset($request->search_bill_rate_to) && $request->search_bill_rate_to != "") ? $request->search_bill_rate_to : "";
        if ($search_bill_rate_from != "" && $search_bill_rate_to != "") {
            $ret->where(function (Builder $query) use ($search_bill_rate_from,  $search_bill_rate_to) {
                $query->whereBetween('facility_hourly_pay_rate', array(intval($search_bill_rate_from), intval($search_bill_rate_to)));
            });
        }

        $search_tenure_from = (isset($request->search_tenure_from) && $request->search_tenure_from != "") ? $request->search_tenure_from : "";
        $search_tenure_to = (isset($request->search_tenure_to) && $request->search_tenure_to != "") ? $request->search_tenure_to : "";
        if ($search_tenure_from != "" && $search_tenure_to != "") {
            $ret->where(function (Builder $query) use ($search_tenure_from, $search_tenure_to) {
                $query->whereBetween('experience_as_acute_care_facility', array(intval($search_tenure_from), intval($search_tenure_to)));
                $query->orWhere(function (Builder $query) use ($search_tenure_from, $search_tenure_to) {
                    $query->whereBetween('experience_as_ambulatory_care_facility', array(intval($search_tenure_from), intval($search_tenure_to)));
                });
            });
        }

        $certification = (isset($request->certification) && $request->certification != "") ? $request->certification : "";
        if ($certification != "") {
            $ret->whereHas('certifications', function (Builder $query) use ($certification) {
                $query->whereIn('type', $certification);
            });
        }

        /* state city and postcode new update */
        $states = (isset($request->state) && $request->state != "") ? $request->state : "";
        if (isset($states) && $states != "") {
            $getStates = States::where(['id' => $states])->get();
            if ($getStates->count() > 0) {
                $selected_state = $getStates->first();
                $name = $selected_state->name;
                $iso2 = $selected_state->iso2;
                $ret->where(function (Builder $query1) use ($name, $iso2) {
                    $query1->where('state', array($name));
                    $query1->orWhere('state', array($iso2));
                });
            }
        }

        $cities = (isset($request->city) && $request->city != "") ? $request->city : "";
        if (isset($cities) && $cities != "") {
            $getCities = Cities::where(['id' => $cities])->get();
            if ($getCities->count() > 0) {
                $selected_city = $getCities->first();
                $name = $selected_city->name;
                $ret->where(function (Builder $query1) use ($name) {
                    $query1->where('city', array($name));
                });
            }
        }

        $zipcode = (isset($request->zipcode) && $request->zipcode != "") ? $request->zipcode : "";
        if (isset($zipcode) && $zipcode != "") {
            $zipcode_inp = [];
            $nearest = $this->getNearestMiles($zipcode);
            if (isset($nearest['results']) && !empty($nearest['results'])) {
                foreach ($nearest['results'] as $zipkey => $zip_res) {
                    $zipcode_inp[] = $zip_res['code'];
                }
            }
            if (!empty($zipcode_inp)) {
                $ret->where(function (Builder $query_zip) use ($zipcode_inp) {
                    $query_zip->whereIn('postcode', $zipcode_inp);
                });
            } else {
                $ret->where(function (Builder $query_zip) use ($zipcode) {
                    $query_zip->where('postcode', array($zipcode));
                });
            }
        }
        /* state city and postcode new update */

        /* keywords filter */
        $search_keyword = (isset($request->search_keyword) && $request->search_keyword != "") ? $request->search_keyword : "";
        if ($search_keyword) {
            $ret->search([
                'user.first_name',
                'user.last_name',
                'user.email',
                'nursing_license_state',
                'nursing_license_number',
                'availability.days_of_the_week',
                'experiences.organization_name',
                'experiences.organization_department_name',
                'experiences.description_job_duties'
            ], $search_keyword);
        }
        /* keywords filter */

        /* new */
        $nurses_list = $ret->count();
        if ($ret->count() > 0) {
            $limit = 25;
            $total_pages = ceil($ret->count() / $limit);
            $nurse_data = $ret->paginate($limit);
            $nurse_info['data'] = $this->nurseInfo($nurse_data);
            $nurse_info['total_pages_available'] =  strval($total_pages);
            $nurse_info["current_page"] = (isset($_REQUEST['page']) && is_numeric($_REQUEST['page'])) ? $_REQUEST['page'] : "1";
            $nurse_info['results_per_page'] = strval($limit);

            $this->check = "1";
            $this->message = "Nurses listed successfully";
            $this->return_data = $nurse_info;
        } else {
            $this->message = "No nurses found";
        }

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    /* average rating calculation */
    public function ratingCalculation($count, $array)
    {
        $max = 0;
        $n = $count; // get the count of comments
        if (!empty($array)) {
            foreach ($array as $key => $rating) { // iterate through array
                $max = $max + $rating;
            }
        }

        return ($max == 0 || $n == 0) ? "0" : strval(round(($max / $n), 1));
    }
    /* average rating calculation */

    /* major cities in us, */
    public function citiesList()
    {
        $cities_list = [
            "anchorage" => "99507", "juneau" => "99801", "fairbanks" => "99709", "badger" => "99705", "palmer" => "99645", "birmingham" => "35211", "huntsville" => "35810", "montgomery" => "36117", "mobile" => "36695", "tuscaloosa" => "35405", "little rock" => "72204", "fayetteville" => "72701", "fort smith" => "72903", "springdale" => "72764", "jonesboro" => "72401", "phoenix" => "85032", "tucson" => "85710", "mesa" => "85204", "chandler" => "85225", "scottsdale" => "85251", "los angeles" => "90011", "san diego" => "92154", "san jose" => "95123", "san francisco" => "94112", "fresno" => "93722", "denver" => "80219", "colorado springs" => "80918", "aurora" => "80013", "fort collins" => "80525", "lakewood" => "80226", "bridgeport" => "06606", "new haven" => "06511", "stamford" => "06902", "hartford" => "06106", "waterbury" => "06708", "washington" => "20011", "shaw" => "20001", "adams morgan" => "20009", "chevy chase" => "20015", "bloomingdale" => "20001", "wilmington" => "19805", "dover" => "19904", "newark" => "19711", "middletown" => "19709", "bear" => "19701", "jacksonville" => "32210", "miami" => "33186", "tampa" => "33647", "orlando" => "32811", "st. petersburg" => "33710", "atlanta" => "30349", "augusta" => "30906", "columbus" => "31907", "savannah" => "31405", "athens" => "30606", "honolulu" => "96817", "east honolulu" => "96818", "pearl city" => "96782", "hilo" => "96720", "kailua" => "96740", "des moines" => "50317", "cedar rapids" => "52402", "davenport" => "52806", "sioux city" => "51106", "iowa city" => "52240", "boise" => "83709", "meridian" => "83646", "nampa" => "83686", "idaho falls" => "83401", "caldwell" => "83605", "chicago" => "60629", "aurora" => "60505", "naperville" => "60565", "joilet" => "60435", "rockford" => "61107", "indianapolis" => "46227", "fort wayne" => "46835", "evansville" => "47714", "carmel" => "46032", "south bend" => "46614", "wichita" => "67212", "overland park" => "66212", "kansas city" => "66102", "olathe" => "66062", "topeka" => "66614", "louisville" => "40299", "lexington" => "40509", "bowling green" => "42101", "owensboro" => "42301", "covington" => "41011", "new orleans" => "70119", "baton rouge" => "70808", "shreveport" => "71106", "metairie" => "70003", "lafayette" => "70506", "boston" => "02124", "worcester" => "01604", "springfield" => "01109", "cambridge" => "02139", "lowell" => "01852", "baltimore" => "21215", "columbia" => "21044", "germantown" => "20874", "silver spring" => "20906", "waldorf" => "20602", "detroit" => "48228", "grand rapids" => "49504", "warren" => "48089", "sterling heights" => "48310", "lansing " => "48911", "minneapolis" => "55407", "st. paul" => "55106", "rochester" => "55901", "duluth" => "55811", "bloomington" => "55420", "kansas city" => "64114", "st. louis" => "63116", "springfield" => "65807", "columbia" => "65203", "independence " => "64055", "jackson" => "39212", "gulfport" => "39503", "southaven" => "38671", "biloxi" => "39531", "hattiesburg" => "39401", "billings" => "59101", "missoula" => "59808", "great falls" => "59401", "bozeman" => "59715", "butte" => "59701", "charlotte" => "28205", "raleigh" => "27603", "greensboro" => "27413", "durham" => "27703", "winston-salem" => "27101", "fargo" => "58102", "bismarck" => "58501", "grand forks" => "58201", "minot" => "58701", "west fargo" => "58078", "omaha" => "68007", "lincon " => "68501", "bellevue" => "68005", "grand island" => "68801", "kearney" => "68845", "manchester" => "03101", "nashua" => "03060", "concord" => "03301", "dover" => "03820", "rochester" => "03867", "newark" => "07101", "jersey city" => "07302", "paterson" => "07501", "elizabeth" => "07201", "toms river" => "08753", "albuquerque" => "87101", "las cruces" => "88001", "rio rancho" => "87144", "santa fe" => "87501", "roswell" => "88202", "las vegas" => "88901", "henderson" => "89002", "reno" => "89502", "north las vegas" => "89030", "paradise" => "89103", "new york" => "10011", "buffalo" => "14201", "rochester" => "14602", "yonkers" => "10701", "syracuse" => "13201", "columbus" => "43210", "cleveland" => "44101", "cincinnati" => "45003", "toledo" => "43604", "akron" => "44320", "oklahoma city" => "73008", "tulsa" => "74008", "norman" => "73019", "broken arrow" => "74011", "edmond" => "73003", "portland" => "97201", "salem" => "97301", "eugene" => "97402", "hillsboro" => "97124", "gresham" => "97080", "philadelphia" => "19102", "pittsburgh" => "15222", "allentown" => "18104", "erie" => "16504", "reading" => "19602", "providence" => "02901", "warwick" => "02886", "cranston" => "02920", "pawtucket" => "02861", "east providence" => "02914", "north charleston" => "29405", "mount pleasant" => "29464", "rock hill" => "29732", "greenville" => "29611", "summerville" => "29485", "sioux falls" => "57101", "rapid city" => "57701", "aberdeen" => "57401", "brookings" => "57006", "watertown" => "57201", "nashville" => "37011", "memphis" => "37501", "knoxville" => "37901", "clarksville" => "37040", "chattanooga" => "37341", "houston" => "77002", "austin" => "78701", "san antonio" => "78204", "dallas" => "75201", "fort worth" => "76102", "salt lake city" => "84101", "west valley city" => "84119", "west jordan" => "84081", "provo" => "84097", "orem" => "84058", "virginia beach" => "23451", "chesapeake" => "23320", "norfolk" => "23502", "arlington" => "22206", "richmond" => "23220", "burlington" => "05401", "south burlington" => "05403", "rutland" => "05701", "essex junction" => "05451", "bennington" => "05201", "seattle" => "98121", "spokane" => "99201", "tacoma" => "98402", "vancouver" => "98660", "kent" => "98032", "milwaukee" => "53201", "madison" => "53558", "green bay" => "54229", "kenosha" => "53140", "racine" => "53401", "charleston" => "25301", "huntington" => "25701", "morgantown" => "26501", "parkersburg" => "26101", "wheeling" => "26003", "cheyenne" => "82001", "casper" => "82609", "laramie" => "82070", "gillette" => "82716", "rock springs" => "82901"
        ];

        return $cities_list;
    }
    /* major cities in us, */

    public function nurseInfo($nurse)
    {
        $controller = new Controller();
        $specialties = $controller->getSpecialities()->pluck('title', 'id');
        $ehrProficienciesExp = $this->getEHRProficiencyExp()->pluck('title', 'id');

        $nurse_result = [];
        foreach ($nurse as $key => $n) {
            $nurse_data["nurse_id"] = (isset($n->id) && $n->id != "") ? $n->id : "";
            $nurse_data["user_id"] = (isset($n->user_id) && $n->user_id != "") ? $n->user_id : "";

            /* user tables records */
            $nurse_data["role"] = (isset($n->user->role) && $n->user->role != "") ? $n->user->role : "";
            $nurse_data["first_name"] = (isset($n->user->first_name) && $n->user->first_name != "") ? $n->user->first_name : "";
            $nurse_data["last_name"] = (isset($n->user->last_name) && $n->user->last_name != "") ? $n->user->last_name : "";

            $nurse_data["nurse_logo"] = (isset($n->user->image) && $n->user->image != "") ? url('storage/assets/nurses/profile/' . $n->user->image) : "";
            $profileNurse = \Illuminate\Support\Facades\Storage::get('assets/nurses/8810d9fb-c8f4-458c-85ef-d3674e2c540a');
            if ($n->user->image) {
                $t = \Illuminate\Support\Facades\Storage::exists('assets/nurses/profile/' . $n->user->image);
                if ($t) {
                    $profileNurse = \Illuminate\Support\Facades\Storage::get('assets/nurses/profile/' . $n->user->image);
                }
            }
            $nurse_data["nurse_logo_base"] = 'data:image/jpeg;base64,' . base64_encode($profileNurse);

            $nurse_data["nurse_email"] = (isset($n->user->email) && $n->user->email != "") ? $n->user->email : "";
            $nurse_data["user_name"] = (isset($n->user->user_name) && $n->user->user_name != "") ? $n->user->user_name : "";
            $nurse_data["fcm_token"] = (isset($n->user->fcm_token) && $n->user->fcm_token != "") ? $n->user->fcm_token : "";
            // $nurse_data["email_verified_at"] = (isset($n->user->email_verified_at) && $n->user->email_verified_at != "") ? $n->user->email_verified_at : "";
            $nurse_data["date_of_birth"] = (isset($n->user->date_of_birth) && $n->user->date_of_birth != "") ? $n->user->date_of_birth : "";
            $nurse_data["mobile"] = (isset($n->user->mobile) && $n->user->mobile != "") ? $n->user->mobile : "";
            $nurse_data["new_mobile"] = (isset($n->user->new_mobile) && $n->user->new_mobile != "") ? $n->user->new_mobile : "";
            // $nurse_data["otp"] = (isset($n->user->otp) && $n->user->otp != "") ? $n->user->otp : "";
            $nurse_data["email_notification"] = (isset($n->user->email_notification) && $n->user->email_notification != "") ? strval($n->user->email_notification) : "";
            $nurse_data["sms_notification"] = (isset($n->user->sms_notification) && $n->user->sms_notification != "") ? strval($n->user->sms_notification) : "";
            // $nurse_data["active"] = (isset($n->user->active) && $n->user->active != "") ? $n->user->active : "";
            // $nurse_data["remember_token"] = (isset($n->user->remember_token) && $n->user->remember_token != "") ? $n->user->remember_token : "";
            // $nurse_data["deleted_at"] = (isset($n->user->deleted_at) && $n->user->deleted_at != "") ? $n->user->deleted_at : "";
            // $nurse_data["created_at"] = (isset($n->user->created_at) && $n->user->created_at != "") ? $n->user->created_at : "";
            // $nurse_data["updated_at"] = (isset($n->user->updated_at) && $n->user->updated_at != "") ? $n->user->updated_at : "";
            // $nurse_data["banned_until"] = (isset($n->user->banned_until) && $n->user->banned_until != "") ? $n->user->banned_until : "";
            // $nurse_data["last_login_at"] = (isset($n->user->last_login_at) && $n->user->last_login_at != "") ? $n->user->last_login_at : "";
            // $nurse_data["last_login_ip"] = (isset($n->user->last_login_ip) && $n->user->last_login_ip != "") ? $n->user->last_login_ip : "";
            /* user tables records */

            $nurse_data["specialty"] = (isset($n->specialty) && $n->specialty != "") ? $n->specialty : "";
            $specialties_array = ($nurse_data["specialty"] != "") ? $spl = explode(",", $nurse_data["specialty"]) : [];
            $nurse_data["specialty_definition"] = [];
            foreach ($specialties_array as $spl) {
                $spl_name = (isset($specialties[$spl]) && $specialties[$spl] != "") ? $specialties[$spl] : "";
                if ($spl_name != "")
                    $nurse_data["specialty_definition"][] = ['id' => $spl, 'name' => $spl_name];
            }
            $nurse_data["nursing_license_state"] = (isset($n->nursing_license_state) && $n->nursing_license_state != "") ? $n->nursing_license_state : "";
            $nurse_data["nursing_license_number"] = (isset($n->nursing_license_number) && $n->nursing_license_number != "") ? $n->nursing_license_number : "";
            $nurse_data["highest_nursing_degree"] = (isset($n->highest_nursing_degree) && $n->highest_nursing_degree != "") ? strval($n->highest_nursing_degree) : "";
            $nurse_data["serving_preceptor"] = (isset($n->serving_preceptor) && $n->serving_preceptor != "") ? strval($n->serving_preceptor) : "";
            $nurse_data["serving_interim_nurse_leader"] = (isset($n->serving_interim_nurse_leader) && $n->serving_interim_nurse_leader != "") ? strval($n->serving_interim_nurse_leader) : "";
            $nurse_data["leadership_roles"] = (isset($n->leadership_roles) && $n->leadership_roles != "") ? strval($n->leadership_roles) : "";
            $nurse_data["address"] = (isset($n->address) && $n->address != "") ? $n->address : "";
            $nurse_data["city"] = (isset($n->city) && $n->city != "") ? $n->city : "";
            $nurse_data["state"] = (isset($n->state) && $n->state != "") ? $n->state : "";
            $nurse_data["postcode"] = (isset($n->postcode) && $n->postcode != "") ? $n->postcode : "";
            $nurse_data["country"] = (isset($n->country) && $n->country != "") ? $n->country : "";
            $nurse_data["hourly_pay_rate"] = (isset($n->hourly_pay_rate) && $n->hourly_pay_rate != "") ? $n->hourly_pay_rate : "";
            $nurse_data["experience_as_acute_care_facility"] = (isset($n->experience_as_acute_care_facility) && $n->experience_as_acute_care_facility != "") ? $n->experience_as_acute_care_facility : "";
            $nurse_data["experience_as_ambulatory_care_facility"] = (isset($n->experience_as_ambulatory_care_facility) && $n->experience_as_ambulatory_care_facility != "") ? $n->experience_as_ambulatory_care_facility : "";
            // $nurse_data["active"] = (isset($n->active) && $n->active != "") ? $n->active : "";
            // $nurse_data["deleted_at"] = (isset($n->deleted_at) && $n->deleted_at != "") ? $n->deleted_at : "";
            // $nurse_data["created_at"] = (isset($n->created_at) && $n->created_at != "") ? $n->created_at : "";
            // $nurse_data["updated_at"] = (isset($n->updated_at) && $n->updated_at != "") ? $n->updated_at : "";

            $nurse_data["ehr_proficiency_cerner"] = (isset($n->ehr_proficiency_cerner) && $n->ehr_proficiency_cerner != "") ? strval($n->ehr_proficiency_cerner) : "";
            $nurse_data["ehr_proficiency_cerner_definition"] = (isset($ehrProficienciesExp[$n->ehr_proficiency_cerner]) && $ehrProficienciesExp[$n->ehr_proficiency_cerner] != "") ? $ehrProficienciesExp[$n->ehr_proficiency_cerner] : "";
            $nurse_data["ehr_proficiency_meditech"] = (isset($n->ehr_proficiency_meditech) && $n->ehr_proficiency_meditech != "") ? strval($n->ehr_proficiency_meditech) : "";
            $nurse_data["ehr_proficiency_meditech_definition"] = (isset($ehrProficienciesExp[$n->ehr_proficiency_meditech]) && $ehrProficienciesExp[$n->ehr_proficiency_meditech] != "") ? $ehrProficienciesExp[$n->ehr_proficiency_meditech] : "";
            $nurse_data["ehr_proficiency_epic"] = (isset($n->ehr_proficiency_epic) && $n->ehr_proficiency_epic != "") ? strval($n->ehr_proficiency_epic) : "";
            $nurse_data["ehr_proficiency_epic_definition"] = (isset($ehrProficienciesExp[$n->ehr_proficiency_epic]) && $ehrProficienciesExp[$n->ehr_proficiency_epic] != "") ? $ehrProficienciesExp[$n->ehr_proficiency_epic] : "";


            $nurse_data["slug"] = (isset($n->slug) && $n->slug != "") ? $n->slug : "";
            $nurse_data["summary"] = (isset($n->summary) && $n->summary != "") ? $n->summary : "";
            $nurse_data["nurses_video"] = (isset($n->nurses_video) && $n->nurses_video != "") ? $n->nurses_video : "";
            $nurse_data["nurses_facebook"] = (isset($n->nurses_facebook) && $n->nurses_facebook != "") ? $n->nurses_facebook : "";
            $nurse_data["nurses_twitter"] = (isset($n->nurses_twitter) && $n->nurses_twitter != "") ? $n->nurses_twitter : "";
            $nurse_data["nurses_linkedin"] = (isset($n->nurses_linkedin) && $n->nurses_linkedin != "") ? $n->nurses_linkedin : "";
            $nurse_data["nurses_instagram"] = (isset($n->nurses_instagram) && $n->nurses_instagram != "") ? $n->nurses_instagram : "";
            $nurse_data["nurses_pinterest"] = (isset($n->nurses_pinterest) && $n->nurses_pinterest != "") ? $n->nurses_pinterest : "";
            $nurse_data["nurses_tiktok"] = (isset($n->nurses_tiktok) && $n->nurses_tiktok != "") ? $n->nurses_tiktok : "";
            $nurse_data["nurses_sanpchat"] = (isset($n->nurses_sanpchat) && $n->nurses_sanpchat != "") ? $n->nurses_sanpchat : "";
            $nurse_data["nurses_youtube"] = (isset($n->nurses_youtube) && $n->nurses_youtube != "") ? $n->nurses_youtube : "";
            $nurse_data["clinical_educator"] = (isset($n->clinical_educator) && $n->clinical_educator != "") ? strval($n->clinical_educator) : "";
            $nurse_data["is_daisy_award_winner"] = (isset($n->is_daisy_award_winner) && $n->is_daisy_award_winner != "") ? strval($n->is_daisy_award_winner) : "";
            $nurse_data["employee_of_the_mth_qtr_yr"] = (isset($n->employee_of_the_mth_qtr_yr) && $n->employee_of_the_mth_qtr_yr != "") ? strval($n->employee_of_the_mth_qtr_yr) : "";
            $nurse_data["other_nursing_awards"] = (isset($n->other_nursing_awards) && $n->other_nursing_awards != "") ? strval($n->other_nursing_awards) : "";
            $nurse_data["is_professional_practice_council"] = (isset($n->is_professional_practice_council) && $n->is_professional_practice_council != "") ? strval($n->is_professional_practice_council) : "";
            $nurse_data["is_research_publications"] = (isset($n->is_research_publications) && $n->is_research_publications != "") ? $n->is_research_publications : "";
            $nurse_data["credential_title"] = (isset($n->credential_title) && $n->credential_title != "") ? $n->credential_title : "";
            $nurse_data["mu_specialty"] = (isset($n->mu_specialty) && $n->mu_specialty != "") ? $n->mu_specialty : "";
            $nurse_data["additional_photos"] = (isset($n->additional_photos) && $n->additional_photos != "") ? $n->additional_photos : "";
            $nurse_data["languages"] = (isset($n->languages) && $n->languages != "") ? $n->languages : "";
            $nurse_data["additional_files"] = (isset($n->additional_files) && $n->additional_files != "") ? $n->additional_files : "";
            $nurse_data["college_uni_name"] = (isset($n->college_uni_name) && $n->college_uni_name != "") ? $n->college_uni_name : "";
            $nurse_data["college_uni_city"] = (isset($n->college_uni_city) && $n->college_uni_city != "") ? $n->college_uni_city : "";
            $nurse_data["college_uni_state"] = (isset($n->college_uni_state) && $n->college_uni_state != "") ? $n->college_uni_state : "";
            $nurse_data["college_uni_country"] = (isset($n->college_uni_country) && $n->college_uni_country != "") ? $n->college_uni_country : "";
            $nurse_data["facility_hourly_pay_rate"] = (isset($n->facility_hourly_pay_rate) && $n->facility_hourly_pay_rate != "") ? $n->facility_hourly_pay_rate : "";
            $nurse_data["n_lat"] = (isset($n->n_lat) && $n->n_lat != "") ? $n->n_lat : "";
            $nurse_data["n_lang"] = (isset($n->n_lang) && $n->n_lang != "") ? $n->n_lang : "";
            $nurse_data["resume"] = (isset($n->resume) && $n->resume != "") ? $n->resume : "";
            $nurse_data["nu_video"] = (isset($n->nu_video) && $n->nu_video != "") ? $n->nu_video : "";
            $nurse_data["nu_video_embed_url"] = (isset($n->nu_video_embed_url) && $n->nu_video_embed_url != "") ? $n->nu_video_embed_url : "";
            $nurse_data["is_verified"] = (isset($n->is_verified) && $n->is_verified != "") ? $n->is_verified : "";
            $nurse_data["is_verified_nli"] = (isset($n->is_verified_nli) && $n->is_verified_nli != "") ? $n->is_verified_nli : "";
            $nurse_data["gig_account_id"] = (isset($n->gig_account_id) && $n->gig_account_id != "") ? $n->gig_account_id : "";
            $nurse_data["is_gig_invite"] = (isset($n->is_gig_invite) && $n->is_gig_invite != "") ? $n->is_gig_invite : "";
            $nurse_data["gig_account_create_date"] = (isset($n->gig_account_create_date) && $n->gig_account_create_date != "") ? $n->gig_account_create_date : "";
            $nurse_data["gig_account_invite_date"] = (isset($n->gig_account_invite_date) && $n->gig_account_invite_date != "") ? $n->gig_account_invite_date : "";

            /* rating */
            // for these below columns need to be created in DB
            /* $rating['over_all'] = (isset($n->over_all) && $n->over_all != "") ? $n->over_all : "0";
            $rating['clinical_skills'] = (isset($n->clinical_skills) && $n->clinical_skills != "") ? $n->clinical_skills : "0";
            $rating['nurse_teamwork'] = (isset($n->nurse_teamwork) && $n->nurse_teamwork != "") ? $n->nurse_teamwork : "0";
            $rating['interpersonal_skills'] = (isset($n->interpersonal_skills) && $n->interpersonal_skills != "") ? $n->interpersonal_skills : "0";
            $rating['work_ethic'] = (isset($n->work_ethic) && $n->work_ethic != "") ? $n->work_ethic : "0"; */

            $rating_info = NurseRating::where(['nurse_id' => $n->id]);
            $overall = $clinical_skills = $nurse_teamwork = $interpersonal_skills = $work_ethic = $a = [];
            if ($rating_info->count() > 0) {
                foreach ($rating_info->get() as $key => $r) {
                    $overall[] = $r->overall;
                    $clinical_skills[] = $r->clinical_skills;
                    $nurse_teamwork[] = $r->nurse_teamwork;
                    $interpersonal_skills[] = $r->interpersonal_skills;
                    $work_ethic[] = $r->work_ethic;
                }
            }
            $rating['over_all'] = $this->ratingCalculation(count($overall), $overall);
            $rating['clinical_skills'] = $this->ratingCalculation(count($clinical_skills), $clinical_skills);
            $rating['nurse_teamwork'] = $this->ratingCalculation(count($nurse_teamwork), $nurse_teamwork);
            $rating['interpersonal_skills'] = $this->ratingCalculation(count($interpersonal_skills), $interpersonal_skills);
            $rating['work_ethic'] = $this->ratingCalculation(count($work_ethic), $work_ethic);
            $nurse_data["rating"] = $rating;
            /* rating */
            $nurse_data['work_experience'] = (isset($n->work_experience) && $n->work_experience != "") ? $n->work_experience : "";

            $nurse_result[] = $nurse_data;
        }
        return $nurse_result;
    }

    public function createJob($type = "create", Request $request)
    {
        $messages = [
            "job_photos.*.mimes" => "Photos should be image or png jpg",
            "job_photos.*.max" => "Photos should not be more than 5mb"
        ];

        $validation_array = [
            'user_id' => 'required',
            'facility_id' => 'required',
            'preferred_assignment_duration' => 'required',
            'seniority_level' => 'required',
            'job_function' => 'required',
            'preferred_shift' => 'required',
            'preferred_specialty' => 'required',
            'preferred_shift_duration' => 'required',
            'preferred_work_location' => 'required',
            'preferred_days_of_the_week' => 'required',
            'preferred_experience' => 'required|regex:/^[0-9. \+]+$/|max:6',
            'preferred_hourly_pay_rate' => 'required|numeric',
            'job_cerner_exp' => 'required',
            'job_meditech_exp' => 'required',
            'job_epic_exp' => 'required',
            'job_other_exp' => 'nullable|regex:/^[a-zA-Z0-9 ,\>\<\(\)\-\~\+\']+$/|min:3|max:100',
            'description' => 'required|min:10',
            'responsibilities' => 'required|min:10',
            'qualifications' => 'required|min:10',
            'job_video' => 'nullable|url|max:255',
            'job_photos' => 'nullable',
            'job_photos.*' => 'image|mimes:jpeg,png,jpg|max:2048',
            'start_date' => 'required|date|before:end_date',
            'end_date' => 'required|date|after:start_date',
        ];
        if ($type == "update") {
            $validation_array = array_merge($validation_array, ['job_id' => 'required']);
        }
        $validator = \Validator::make($request->all(), $validation_array, $messages);

        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            // dd($request->all());
            $update_array["facility_id"] = (isset($request->facility_id) && $request->facility_id != "") ? $request->facility_id : "";
            $update_array["preferred_assignment_duration"] = (isset($request->preferred_assignment_duration) && $request->preferred_assignment_duration != "") ? $request->preferred_assignment_duration : "";
            $update_array["seniority_level"] = (isset($request->seniority_level) && $request->seniority_level != "") ? $request->seniority_level : "";
            $update_array["job_function"] = (isset($request->job_function) && $request->job_function != "") ? $request->job_function : "";
            $update_array["preferred_specialty"] = (isset($request->preferred_specialty) && $request->preferred_specialty != "") ? $request->preferred_specialty : "";
            $update_array["preferred_shift_duration"] = (isset($request->preferred_shift_duration) && $request->preferred_shift_duration != "") ? $request->preferred_shift_duration : "";
            $update_array["preferred_work_location"] = (isset($request->preferred_work_location) && $request->preferred_work_location != "") ? $request->preferred_work_location : "";
            $preferred_days_of_the_week = (isset($request->preferred_days_of_the_week) && $request->preferred_days_of_the_week != "") ? json_decode($request->preferred_days_of_the_week) : [];
            if (is_array($preferred_days_of_the_week) && !empty($preferred_days_of_the_week)) {
                $update_array["preferred_days_of_the_week"] = implode(',', $preferred_days_of_the_week);
            }
            $update_array["preferred_experience"] = (isset($request->preferred_experience) && $request->preferred_experience != "") ? $request->preferred_experience : "";
            $update_array["preferred_hourly_pay_rate"] = (isset($request->preferred_hourly_pay_rate) && $request->preferred_hourly_pay_rate != "") ? $request->preferred_hourly_pay_rate : "";
            $update_array["job_cerner_exp"] = (isset($request->job_cerner_exp) && $request->job_cerner_exp != "") ? $request->job_cerner_exp : "";
            $update_array["job_meditech_exp"] = (isset($request->job_meditech_exp) && $request->job_meditech_exp != "") ? $request->job_meditech_exp : "";
            $update_array["job_epic_exp"] = (isset($request->job_epic_exp) && $request->job_epic_exp != "") ? $request->job_epic_exp : "";
            if (isset($request->job_other_exp) && $request->job_other_exp != "") $update_array["job_other_exp"] = $request->job_other_exp;
            $update_array["description"] = (isset($request->description) && $request->description != "") ? $request->description : "";
            $update_array["responsibilities"] = (isset($request->responsibilities) && $request->responsibilities != "") ? $request->responsibilities : "";
            $update_array["qualifications"] = (isset($request->qualifications) && $request->qualifications != "") ? $request->qualifications : "";
            $update_array["active"] = (isset($request->active) && $request->active != "") ? $request->active : "1";

            $update_array["start_date"] = (isset($request->start_date) && $request->start_date != "") ? date('Y-m-d', strtotime($request->start_date)) : NULL;
            $update_array["end_date"] = (isset($request->end_date) && $request->end_date != "") ? date('Y-m-d', strtotime($request->end_date)) : NULL;
            $update_array["preferred_shift"] = (isset($request->preferred_shift) && $request->preferred_shift != "") ? $request->preferred_shift : NULL;

            if (isset($request->job_video) && $request->job_video != "") {
                if (preg_match('/https?:\/\/(?:[\w]+\.)*youtube\.com\/watch\?v=[^&]+/', $request->job_video, $vresult)) {
                    $youTubeID = $this->parse_youtube($request->job_video);
                    $embedURL = 'https://www.youtube.com/embed/' . $youTubeID[1];
                    $update_array['video_embed_url'] = $embedURL;
                } elseif (preg_match('/https?:\/\/(?:[\w]+\.)*vimeo\.com(?:[\/\w]*\/videos?)?\/([0-9]+)[^\s]*+/', $request->job_video, $vresult)) {
                    $vimeoID = $this->parse_vimeo($request->job_video);
                    $embedURL = 'https://player.vimeo.com/video/' . $vimeoID[1];
                    $update_array['video_embed_url'] = $embedURL;
                }
            }
            if ($type == "update") {
                /* update job */
                $job_id = (isset($request->job_id) && $request->job_id != "") ? $request->job_id : "";
                $job = Job::where(['id' => $job_id])->update($update_array);
                /* update job */
            } else {
                /* create job */
                $update_array["created_by"] = (isset($request->user_id) && $request->user_id != "") ? $request->user_id : "";
                $job = Job::create($update_array);
                /* create job */
            }

            if (!empty($job) && $job_photos = $request->file('job_photos')) {
                foreach ($job_photos as $job_photo) {
                    $job_photo_name_full = $job_photo->getClientOriginalName();
                    $job_photo_name = pathinfo($job_photo_name_full, PATHINFO_FILENAME);
                    $job_photo_ext = $job_photo->getClientOriginalExtension();
                    $job_photo_finalname = $job_photo_name . '_' . time() . '.' . $job_photo_ext;
                    //Upload Image
                    $job_id_val = ($type == "update") ? $job_id : $job->id;
                    $job_photo->storeAs('assets/jobs/' . $job_id_val, $job_photo_finalname);
                    JobAsset::create(['job_id' => $job_id_val, 'name' => $job_photo_finalname, 'filter' => 'job_photos']);
                }
            }

            if ($job) {
                $this->check = "1";
                $this->message = "Job " . $type . "d successfully";
                if ($type == "update") {
                    $job_data = Job::where(['id' => $job_id]);
                    if ($job_data->count() > 0) {
                        $this->return_data = $job_data->first();
                    }
                } else {
                    $this->return_data = $job;
                }
            } else {
                $this->check = "0";
                $this->message = "Failed to create job, Please try again later";
            }
        }

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function removeJobDocument(Request $request)
    {
        $validator = \Validator::make($request->all(), ['job_id' => 'required', 'asset_id' => 'required']);
        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            $id = (isset($request->asset_id) && $request->asset_id != "") ? $request->asset_id : "";
            $job_id = (isset($request->job_id) && $request->job_id != "") ? $request->job_id : "";

            $jobAsset = JobAsset::where(['id' => $id, 'job_id' => $job_id, 'active' => '1', 'deleted_at' => NULL]);
            if ($jobAsset->count() > 0) {
                $job_asset = $jobAsset->first();
                $t = Storage::exists('assets/jobs/' . $job_asset->id . '/' . $job_asset->name);
                if ($t && $job_asset->name) {
                    Storage::delete('assets/jobs/' . $job_asset->id . '/' . $job_asset->name);
                }
                $delete = $job_asset->delete();
                if ($delete) {
                    $this->check = "1";
                    $this->message = "Job photo removed successfully";
                } else {
                    $this->message = "Job photo not found or already removed";
                }
            } else {
                $this->message = "Job photo not found or already removed";
            }
        }


        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function getSeniorityLevelOptions()
    {
        $seniorityLevels = $this->getSeniorityLevel()->pluck('title', 'id');
        $data = [];
        foreach ($seniorityLevels as $key => $value) {
            $data[] = ['id' => $key, "name" => $value];
        }
        $this->check = "1";
        $this->message = "Seniority level's has been listed successfully";
        $this->return_data = $data;
        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function getJobFunctionOptions()
    {
        $jobFunctions = $this->getJobFunction()->pluck('title', 'id');
        $data = [];
        foreach ($jobFunctions as $key => $value) {
            $data[] = ['id' => $key, "name" => $value];
        }
        $this->check = "1";
        $this->message = "Job function's has been listed successfully";
        $this->return_data = $data;

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function apiJobInvite(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'nurse_id' => 'required',
            'facility_id' => 'required',
            'job_id' => 'required',
        ]);

        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            $insert_offer["nurse_id"] = $request->nurse_id;
            $insert_offer["created_by"] = $request->facility_id;
            $insert_offer["job_id"] = $request->job_id;
            $insert_offer["expiration"] = date("Y-m-d H:i:s", strtotime('+48 hours'));

            $offer = Offer::create($insert_offer);
            if ($offer) {

                $off_data["id"] = (isset($offer->id) && $offer->id != "") ? $offer->id : "";
                $off_data["nurse_id"] = (isset($offer->nurse_id) && $offer->nurse_id != "") ? $offer->nurse_id : "";
                $off_data["facility"] = (isset($offer->created_by) && $offer->created_by != "") ? $offer->created_by : "";
                $off_data["job_id"] = (isset($offer->job_id) && $offer->job_id != "") ? $offer->job_id : "";
                $off_data["expiration"] = (isset($offer->expiration) && $offer->expiration != "") ? date('d-m-Y', strtotime($offer->expiration)) : "";

                /* mail */
                $nurse_info = Nurse::where(['id' => $request->nurse_id]);
                if ($nurse_info->count() > 0) {
                    $nurse = $nurse_info->first();
                    $user_info = User::where(['id' => $nurse->user_id]);
                    if ($user_info->count() > 0) {
                        $user = $user_info->first(); // nurse user info
                        $facility_user_info = User::where(['id' => $offer->created_by]);
                        if ($facility_user_info->count() > 0) {
                            $facility_user = $facility_user_info->first(); // facility user info
                            $data = [
                                'to_email' => $user->email,
                                'to_name' => $user->first_name . ' ' . $user->last_name
                            ];
                            $replace_array = [
                                '###NURSENAME###' => $user->first_name . ' ' . $user->last_name,
                                '###FACILITYNAME###' => $facility_user->facilities[0]->name,
                                '###LOCATION###' => $facility_user->facilities[0]->city . ',' . $facility_user->facilities[0]->state,
                                '###SPECIALITY###' => \App\Providers\AppServiceProvider::keywordTitle($offer->job->preferred_specialty),
                                '###STARTDATE###' => date('d F Y', strtotime($offer->job->start_date)),
                                '###DURATION###' => \App\Providers\AppServiceProvider::keywordTitle($offer->job->preferred_assignment_duration),
                                '###SHIFT###' => \App\Providers\AppServiceProvider::keywordTitle($offer->job->preferred_shift),
                                '###WORKINGDAYS###' => $offer->job->preferred_days_of_the_week,
                            ];
                            $this->basic_email($template = "facility_make_offer", $data, $replace_array);
                        }
                    }
                }
                /* mail */

                $this->check = "1";
                $this->message = "Offer sent successfully";
                $this->return_data = $off_data;
            } else {
                $this->check = "0";
                $this->message = "Failed to sent offer, Please try again later";
            }
        }

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function offeredNurses($type, Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'user_id' => 'required',
        ]);

        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {

            /*  dropdown data's */
            $controller = new Controller();
            $assignmentDurations = $this->getAssignmentDurations()->pluck('title', 'id');
            $specialties = $controller->getSpecialities()->pluck('title', 'id');
            $preferredShifts = $this->getPreferredShift()->pluck('title', 'id');
            /*  dropdown data's */
            $user_info = USER::where(['id' => $request->user_id])->get();
            if ($user_info->count() > 0) {
                $user = $user_info->first();

                $page = (isset($request->page_number) && $request->page_number != "") ? $request->page_number : "1";
                if ($type == "active")  $where = ['active' => '1', 'created_by' => $user->id, 'status' => 'Active'];
                elseif ($type == "completed") $where = ['active' => '1', 'created_by' => $user->id, 'status' => 'Active'];
                else $where = ['active' => '1', 'created_by' => $user->id, 'status' => 'Pending'];

                $limit = 25;
                $offers_info = Offer::where($where)
                    ->orderBy('created_at', 'desc')
                    ->paginate($limit);

                $total_pages = ceil($offers_info->count() / $limit);
                $offered['total_pages_available'] =  strval($total_pages);
                $offered["current_page"] = (isset($_REQUEST['page']) && is_numeric($_REQUEST['page'])) ? $_REQUEST['page'] : "1";
                $offered['results_per_page'] = strval($limit);

                $offered['data'] = [];
                if ($offers_info->count() > 0) {
                    foreach ($offers_info as $key => $off) {
                        /* $o['creator'] = $off->creator;
                        $o['nurse'] = $off->nurse;
                        $o['job'] = $off->job; */

                        $nurse_info = USER::where(['id' => $off->nurse->user_id])->get();
                        $first_name = $last_name = $image = "";
                        if ($user_info->count() > 0) {
                            $nurse = $nurse_info->first();
                            $first_name = (isset($nurse->first_name) && $nurse->first_name != "") ? $nurse->first_name : "";
                            $last_name = (isset($nurse->last_name) && $nurse->last_name != "") ? $nurse->last_name : "";
                            $image = (isset($nurse->image) && $nurse->image != "") ? url('storage/assets/nurses/profile/' . $nurse->image) : "";

                            $image_base = \Illuminate\Support\Facades\Storage::get('assets/nurses/8810d9fb-c8f4-458c-85ef-d3674e2c540a');
                            if ($nurse->image) {
                                $t = \Illuminate\Support\Facades\Storage::exists('assets/nurses/profile/' . $nurse->image);
                                if ($t) {
                                    $profileNurse = \Illuminate\Support\Facades\Storage::get('assets/nurses/profile/' . $nurse->image);
                                }
                            }
                            $image_base = 'data:image/jpeg;base64,' . base64_encode($profileNurse);
                        }
                        $o['nurse_first_name'] = $first_name;
                        $o['nurse_last_name'] = $last_name;
                        $o['nurse_image'] = $image;
                        $o['nurse_image_base'] = $image_base;

                        $o['preferred_shift'] = (isset($off->job->preferred_shift) && $off->job->preferred_shift != "") ? strval($off->job->preferred_shift) : "";
                        $o['preferred_shift_definition'] = (isset($preferredShifts[$off->job->preferred_shift]) && $preferredShifts[$off->job->preferred_shift] != "") ? $preferredShifts[$off->job->preferred_shift] : "";
                        $o['preferred_shift_duration'] = (isset($off->job->preferred_shift_duration) && $off->job->preferred_shift_duration != "") ? strval($off->job->preferred_shift_duration) : "";
                        $o['preferred_shift_duration_definition'] = (isset($specialties[$off->job->preferred_shift_duration]) && $specialties[$off->job->preferred_shift_duration] != "") ? $specialties[$off->job->preferred_shift_duration] : "";
                        $o['preferred_specialty'] = (isset($off->job->preferred_specialty) && $off->job->preferred_specialty != "") ? strval($off->job->preferred_specialty) : "";
                        $o['preferred_specialty_definition'] = (isset($specialties[$off->job->preferred_specialty]) && $specialties[$off->job->preferred_specialty] != "") ? $specialties[$off->job->preferred_specialty] : "";
                        $o['preferred_assignment_duration'] = (isset($off->job->preferred_assignment_duration) && $off->job->preferred_assignment_duration != "") ? strval($off->job->preferred_assignment_duration) : "0";
                        $o['preferred_assignment_duration_definition'] = (isset($assignmentDurations[$off->job->preferred_assignment_duration]) && $assignmentDurations[$off->job->preferred_assignment_duration] != "") ? $assignmentDurations[$off->job->preferred_assignment_duration] : "0";
                        /* nurse_info */
                        $nurse_info = USER::where(['id' => $request->user_id])->get();
                        /* nurse_info */
                        $o['preferred_hourly_pay_rate'] = (isset($off->job->preferred_hourly_pay_rate) && $off->job->preferred_hourly_pay_rate != "") ? strval($off->job->preferred_hourly_pay_rate) : "0";
                        $o['preferred_days_of_the_week'] = (isset($off->job->preferred_days_of_the_week) && $off->job->preferred_days_of_the_week != "") ? $off->job->preferred_days_of_the_week : "";
                        $days = [];
                        if (isset($off->job->preferred_days_of_the_week)) {
                            $day_s = explode(",", $off->job->preferred_days_of_the_week);
                            if (is_array($day_s) && !empty($day_s)) {
                                foreach ($day_s as $day) {
                                    if ($day == "Sunday") $days[] = "Su";
                                    elseif ($day == "Monday") $days[] = "M";
                                    elseif ($day == "Tuesday") $days[] = "T";
                                    elseif ($day == "Wednesday") $days[] = "W";
                                    elseif ($day == "Thursday") $days[] = "Th";
                                    elseif ($day == "Friday") $days[] = "F";
                                    elseif ($day == "Saturday") $days[] = "Sa";
                                }
                            }
                        }
                        $o['preferred_days_of_the_week_array'] = ($o['preferred_days_of_the_week'] != "") ? $days : [];
                        $o['preferred_days_of_the_week_string'] = ($o['preferred_days_of_the_week'] != "") ? implode(",", $days) : "";
                        $o['offered_at'] = (isset($off->created_at) && $off->created_at != "") ? date('D h:i A', strtotime($off->created_at)) : date('D h:i A');

                        /* rating */
                        $nurse_rating_info = NurseRating::where(['nurse_id' => $off->nurse_id, 'status' => '1', 'is_deleted' => '0']);
                        $overall = [];
                        $rating_flag = "0";
                        if ($nurse_rating_info->count() > 0) {
                            $rating_flag = "1";
                            foreach ($nurse_rating_info->get() as $key => $r) {
                                $overall[] = $r->overall;
                            }
                        }
                        $rating = $this->ratingCalculation(count($overall), $overall);
                        /* rating */

                        if ($type == "active" || $type == "completed") {
                            $o['start_date'] = date('d F Y', strtotime($off->job->start_date));
                        }
                        if ($type == "completed") {
                            $o['end_date'] = date('d F Y', strtotime($off->job->end_date));
                        }

                        if (($type == "completed") && ($off->job->end_date < date('Y-m-d'))) {
                            $o['rating_flag'] = $rating_flag;
                            $o['rating'] = $rating;
                            $o['ck_end'] = $off->job->end_date;
                            $offered['data'][] = $o;
                        } elseif (($type == "active") && ($off->job->end_date >= date('Y-m-d'))) {
                            $offered['data'][] = $o;
                        } elseif ($type == "list") {
                            $offered['data'][] = $o;
                        }
                    }

                    $this->check = "1";
                    $this->message = "Job offered listed successfully";
                    $this->return_data = $offered;
                } else {
                    $this->message = "Currently nothing " . $type;
                }
            } else {
                $this->message = "User not found";
            }
        }

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function facilityPostedJobs($type, Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'user_id' => 'required',
        ]);

        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            /*  dropdown data's */
            $controller = new Controller();
            $assignmentDurations = $this->getAssignmentDurations()->pluck('title', 'id');
            $specialties = $controller->getSpecialities()->pluck('title', 'id');
            /*  dropdown data's */
            $user_info = USER::where(['id' => $request->user_id])->get();
            if ($user_info->count() > 0) {
                $user = $user_info->first();

                // $page = (isset($request->page_number) && $request->page_number != "") ? $request->page_number : "1";
                /* if ($type == "active")  $where = ['active' => '1', 'created_by' => $user->id, 'status' => 'Active'];
                elseif ($type == "completed") $where = ['active' => '1', 'created_by' => $user->id, 'status' => 'Completed'];
                else $where = ['active' => '1', 'created_by' => $user->id, 'status' => 'Pending']; */

                $limit = 25;
                $whereCond = ['active' => '1', 'created_by' => $user->id];
                $ret = Job::where($whereCond)
                    ->orderBy('created_at', 'desc');
                $jobs_info = $ret->paginate($limit);


                $tot_res = 0;
                $my_jobs['data'] = [];
                if ($jobs_info->count() > 0) {
                    foreach ($jobs_info as $key => $job) {
                        /* $o['creator'] = $job->creator;
                        $o['nurse'] = $job->nurse;
                        $o['job'] = $job->job; */

                        $o['job_id'] = (isset($job->id) && $job->id != "") ? $job->id : "";
                        $o['facility_first_name'] = (isset($job->creator->first_name) && $job->creator->first_name != "") ? $job->creator->first_name : "";
                        $o['facility_last_name'] = (isset($job->creator->last_name) && $job->creator->last_name != "") ? $job->creator->last_name : "";
                        // $o['faci'] = $job->facility;
                        $o['facility_image'] = (isset($job->facility->facility_logo) && $job->facility->facility_logo != "") ? url('storage/assets/facilities/facility_logo/' . $job->facility->facility_logo) : "";

                        $facility_logo = "";
                        if ($job->facility->facility_logo) {
                            $t = \Illuminate\Support\Facades\Storage::exists('assets/facilities/facility_logo/' . $job->facility->facility_logo);
                            if ($t) {
                                $facility_logo = \Illuminate\Support\Facades\Storage::get('assets/facilities/facility_logo/' . $job->facility->facility_logo);
                            }
                        }
                        $o["facility_image_base"] = ($facility_logo != "") ? 'data:image/jpeg;base64,' . base64_encode($facility_logo) : "";


                        $o['preferred_shift'] = (isset($job->preferred_shift) && $job->preferred_shift != "") ? strval($job->preferred_shift) : "";
                        $o['preferred_shift_definition'] = (isset($job->preferred_shift) && $job->preferred_shift != "") ? \App\Providers\AppServiceProvider::keywordTitle($job->preferred_shift) : "";
                        $o['preferred_specialty'] = (isset($job->preferred_specialty) && $job->preferred_specialty != "") ? strval($job->preferred_specialty) : "";
                        $o['preferred_specialty_definition'] = (isset($specialties[$job->preferred_specialty]) && $specialties[$job->preferred_specialty] != "") ? $specialties[$job->preferred_specialty] : "";
                        $o['preferred_assignment_duration'] = (isset($job->preferred_assignment_duration) && $job->preferred_assignment_duration != "") ? strval($job->preferred_assignment_duration) : "0";
                        $o['preferred_assignment_duration_definition'] = (isset($assignmentDurations[$job->preferred_assignment_duration]) && $assignmentDurations[$job->preferred_assignment_duration] != "") ? $assignmentDurations[$job->preferred_assignment_duration] : "0";
                        $o['preferred_hourly_pay_rate'] = (isset($job->preferred_hourly_pay_rate) && $job->preferred_hourly_pay_rate != "") ? strval($job->preferred_hourly_pay_rate) : "0";
                        $o['preferred_days_of_the_week'] = (isset($job->preferred_days_of_the_week) && $job->preferred_days_of_the_week != "") ? $job->preferred_days_of_the_week : "";
                        $days = [];
                        if (isset($job->preferred_days_of_the_week)) {
                            $day_s = explode(",", $job->preferred_days_of_the_week);
                            if (is_array($day_s) && !empty($day_s)) {
                                foreach ($day_s as $day) {
                                    if ($day == "Sunday") $days[] = "Su";
                                    elseif ($day == "Monday") $days[] = "M";
                                    elseif ($day == "Tuesday") $days[] = "T";
                                    elseif ($day == "Wednesday") $days[] = "W";
                                    elseif ($day == "Thursday") $days[] = "Th";
                                    elseif ($day == "Friday") $days[] = "F";
                                    elseif ($day == "Saturday") $days[] = "Sa";
                                }
                            }
                        }
                        $o['preferred_days_of_the_week_array'] = ($o['preferred_days_of_the_week'] != "") ? $days : [];
                        $o['preferred_days_of_the_week_string'] = ($o['preferred_days_of_the_week'] != "") ? implode(",", $days) : "";

                        $o['facility_id'] = (isset($job->facility_id) && $job->facility_id != "") ? $job->facility_id : "";

                        $o['seniority_level'] = (isset($job->seniority_level) && $job->seniority_level != "") ? $job->seniority_level : "";
                        $o['seniority_level_definition'] = (isset($job->seniority_level) && $job->seniority_level != "") ? \App\Providers\AppServiceProvider::keywordTitle($job->seniority_level) : "";

                        $o['job_function'] = (isset($job->job_function) && $job->job_function != "") ? strval($job->job_function) : "";
                        $o['job_function_definition'] = (isset($job->job_function) && $job->job_function != "") ? \App\Providers\AppServiceProvider::keywordTitle($job->job_function) : "";

                        $o['preferred_shift_duration'] = (isset($job->preferred_shift_duration) && $job->preferred_shift_duration != "") ? strval($job->preferred_shift_duration) : "";
                        $o['preferred_shift_duration_definition'] = (isset($job->preferred_shift_duration) && $job->preferred_shift_duration != "") ? \App\Providers\AppServiceProvider::keywordTitle($job->preferred_shift_duration) : "";

                        $o['preferred_work_location'] = (isset($job->preferred_work_location) && $job->preferred_work_location != "") ? strval($job->preferred_work_location) : "";
                        $o['preferred_work_location_definition'] = (isset($job->preferred_work_location) && $job->preferred_work_location != "") ? \App\Providers\AppServiceProvider::keywordTitle($job->preferred_work_location) : "";

                        $o['preferred_experience'] = (isset($job->preferred_experience) && $job->preferred_experience != "") ? strval($job->preferred_experience) : "";
                        $o['preferred_experience_definition'] = (isset($job->preferred_experience) && $job->preferred_experience != "") ? \App\Providers\AppServiceProvider::keywordTitle($job->preferred_experience) : "";

                        $o['job_cerner_exp'] = (isset($job->job_cerner_exp) && $job->job_cerner_exp != "") ? strval($job->job_cerner_exp) : "";
                        $o['job_cerner_exp_definition'] = (isset($job->job_cerner_exp) && $job->job_cerner_exp != "") ? \App\Providers\AppServiceProvider::keywordTitle($job->job_cerner_exp) : "";

                        $o['job_meditech_exp'] = (isset($job->job_meditech_exp) && $job->job_meditech_exp != "") ? strval($job->job_meditech_exp) : "";
                        $o['job_meditech_exp_definition'] = (isset($job->job_meditech_exp) && $job->job_meditech_exp != "") ? \App\Providers\AppServiceProvider::keywordTitle($job->job_meditech_exp) : "";

                        $o['job_epic_exp'] = (isset($job->job_epic_exp) && $job->job_epic_exp != "") ? strval($job->job_epic_exp) : "";
                        $o['job_epic_exp_definition'] = (isset($job->job_epic_exp) && $job->job_epic_exp != "") ? \App\Providers\AppServiceProvider::keywordTitle($job->job_epic_exp) : "";

                        $o['job_other_exp'] = (isset($job->job_other_exp) && $job->job_other_exp != "") ? $job->job_other_exp : "";
                        $o['description'] = (isset($job->description) && $job->description != "") ? $job->description : "";
                        $o['responsibilities'] = (isset($job->responsibilities) && $job->responsibilities != "") ? $job->responsibilities : "";
                        $o['qualifications'] = (isset($job->qualifications) && $job->qualifications != "") ? $job->qualifications : "";
                        $o['job_video'] = (isset($job->job_video) && $job->job_video != "") ? $job->job_video : "";
                        $o['active'] = (isset($job->active) && $job->active != "") ? $job->active : "";

                        /* offered nurse id */
                        $o['offered_nurse_id'] = "";
                        if (isset($job->offers) && !empty($job->offers)) {
                            foreach ($job->offers as $key => $val) {
                                if (isset($val->status) && $val->status == "Active") {
                                    $o['offered_nurse_id'] = (isset($val->nurse_id) && $val->nurse_id != "") ? $val->nurse_id : "";
                                }
                            }
                        }

                        $comment = [];
                        if ($o['offered_nurse_id'] != "") {
                            $nurse_rating_info = NurseRating::where(['nurse_id' => $o['offered_nurse_id'], 'job_id' => $job->id]);
                            if ($nurse_rating_info->count() > 0) {
                                $facility_commented = $nurse_rating_info->first();
                                $comment['rating'] = (isset($facility_commented->overall) && $facility_commented->overall != "") ? $facility_commented->overall : "0";
                                $comment['experience'] = (isset($facility_commented->experience) && $facility_commented->experience != "") ? $facility_commented->experience : "";
                                $nurse_user_id = (isset($facility_commented->nurse->user_id) && $facility_commented->nurse->user_id != "") ? $facility_commented->nurse->user_id : "";
                                if ($nurse_user_id != "") {
                                    $nurse_user_info = User::where(['id' => $nurse_user_id]);
                                    if ($nurse_user_info->count() > 0) {
                                        $nui = $nurse_user_info->first();
                                        $comment['nurse_name'] = $nui->first_name . ' ' . $nui->last_name;
                                        $comment['nurse_image'] = (isset($nui->image) && $nui->image != "") ? url('storage/assets/nurses/profile/' . $nui->image) : "";

                                        $profileNurse = \Illuminate\Support\Facades\Storage::get('assets/nurses/8810d9fb-c8f4-458c-85ef-d3674e2c540a');
                                        if ($nui->image) {
                                            $t = \Illuminate\Support\Facades\Storage::exists('assets/nurses/profile/' . $nui->image);
                                            if ($t) {
                                                $profileNurse = \Illuminate\Support\Facades\Storage::get('assets/nurses/profile/' . $nui->image);
                                            }
                                        }
                                        $comment["nurse_image_base"] = 'data:image/jpeg;base64,' . base64_encode($profileNurse);
                                    }
                                }
                            }
                        }
                        $o['rating_comment'] = (!empty($comment)) ? $comment : (object)array();

                        /* offered nurse id */

                        /* job assets */
                        $job_uploads = [];
                        $job_assets = JobAsset::where(['active' => '1', 'job_id' => $job->id, "deleted_at" => NULL]);
                        if ($job_assets->count() > 0) {
                            foreach ($job_assets->get() as $key => $asset) {
                                $job_uploads[] = ['asset_id' => $asset->id, 'name' => url('storage/assets/jobs/' . $job->id . '/' . $asset->name)];
                            }
                        }
                        $o["job_photos"] = $job_uploads;
                        /* job assets */


                        if ($type == "posted") {
                            $count_applied = Follows::where(['job_id' => $job->id])->count();
                            $o['applied'] = strval($count_applied);
                        }

                        $o['start_date'] = date('d F Y', strtotime($job->start_date));
                        $o['end_date'] = date('d F Y', strtotime($job->end_date));

                        if ($type == "posted" && ((empty($job->offers[0])) || (isset($job->offers[0]->status) && $job->offers[0]->status == "Pending"))) {
                            if ($tot_res == 0) $tot_res += 1; //initialized first page`
                            $tot_res += 1;
                            $my_jobs['data'][] = $o;
                        } elseif ($type == "active" && (isset($job->offers[0]->status) && $job->offers[0]->status == "Active" && ($job->end_date >= date('Y-m-d')))) {
                            if ($tot_res == 0) $tot_res += 1; //initialized first page`
                            $tot_res += 1;
                            $my_jobs['data'][] = $o;
                        } elseif ($type == "completed" && (isset($job->offers[0]->status) && $job->offers[0]->status == "Active" && ($job->end_date < date('Y-m-d')))) {
                            if ($tot_res == 0) $tot_res += 1; //initialized first page`
                            $tot_res += 1;
                            $rating_info = NurseRating::where(['job_id' => $job->id]);
                            $o['rating_flag'] = ($rating_info->count() > 0) ? "1" : "0";
                            $my_jobs['data'][] = $o;
                        }
                    }

                    $this->check = "1";
                    $this->message = "Job offered listed successfully";
                } else {
                    $this->message = "Currently nothing " . $type;
                }
                $total_pages = ceil($tot_res / $limit);
                $my_jobs['total_pages_available'] =  strval($total_pages);
                $my_jobs["current_page"] = (isset($_REQUEST['page']) && is_numeric($_REQUEST['page'])) ? $_REQUEST['page'] : "1";
                $my_jobs['results_per_page'] = strval($limit);

                $this->return_data = $my_jobs;
            } else {
                $this->message = "User not found";
            }
        }

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function apiJobsList(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'user_id' => 'required',
            'facility_id' => 'required',
        ]);

        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            $jobs = [];
            $ret = Job::where('active', true)
                ->orderBy('created_at', 'desc');

            $ret->where('facility_id', $request->facility_id);

            $ids = [];
            $nurse = Nurse::where(['user_id' => $request->user_id])->first();
            if ($nurse->count() > 0) {
                if (isset($nurse->offers) && count($nurse->offers) > 0) {
                    $ids = $nurse->offers->whereNotNull('job_id')->pluck('id');
                }
            }
            $ret->whereDoesntHave('offers', function (Builder $query) use ($ids) {
                $query->whereIn('id', $ids);
            });
            $temp = $ret->get();
            foreach ($temp as $job) {
                $job_info = Job::where(['id' => $job->id, 'active' => '1']);
                $content = [];
                if ($job_info->count() > 0) {
                    $job = $job_info->first();
                    $content = [
                        'name' => $job->facility->name,
                        'location' => $job->facility->city . ', ' . $job->facility->state,
                        'specialty' => $job->preferred_specialty ? \App\Providers\AppServiceProvider::keywordTitle($job->preferred_specialty) : 'N/A',
                        'jobDetail' => [
                            'start_date' => $job->start_date ? date("jS F Y", strtotime($job->start_date)) : 'N/A',
                            'duration' => $job->preferred_assignment_duration ? \App\Providers\AppServiceProvider::keywordTitle($job->preferred_assignment_duration) : 'N/A',
                            'shift' => $job->preferred_shift_duration ? \App\Providers\AppServiceProvider::keywordTitle($job->preferred_shift_duration) : 'N/A',
                            'workdays' => $job->preferred_days_of_the_week ?: 'N/A',
                        ], 'terms' => '<p><strong>TERMS ACKNOWLEDGMENT</strong></p> <p>By clicking on the &ldquo;Make an Offer&rdquo; your facility agrees to pay the hourly bill rate reflected on the nurse&rsquo;s profile page per the terms established in the Nurseify vendor agreement</p> <p><strong>NEXT STEPS</strong></p> <ul> <li><strong>Webo User</strong>&nbsp;will have 48 hours to accept your booking request</li> <li>You will receive an email notice after the nurse accepts or rejects the request</li> <li>Assuming the nurse accepts, a Nurseify Consultant will contact you to coordinate onboarding logistics</li> <li>If the nurse rejects, we will provide additional nurses that may meet your need</li> <li>Contact us anytime at&nbsp;<a href="mailto:info@nurseify.app">info@nurseify.app</a></li> </ul>'
                    ];
                }

                $jobs[] = ['job_id' => $job->id, 'job' => $job->facility->name . ' - ' . \App\Providers\AppServiceProvider::keywordTitle($job->preferred_specialty), 'content' => $content];
            }

            if (!empty($jobs)) {
                $this->check = "1";
                $this->message = "Invite nurse for the jobs. Listed successfully";
                $this->return_data = $jobs;
            } else {
                $this->message = "No jobs available for this nurse";
            }
        }

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function apiJobFacility(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'job_id' => 'required',
        ]);

        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            $job_info = Job::where(['id' => $request->job_id, 'active' => '1']);
            if ($job_info->count() > 0) {
                $job = $job_info->first();
                $this->check = "1";
                $this->message = "Job information listed successfully";
                $this->return_data = [
                    'name' => $job->facility->name,
                    'location' => $job->facility->city . ', ' . $job->facility->state,
                    'specialty' => $job->preferred_specialty ? \App\Providers\AppServiceProvider::keywordTitle($job->preferred_specialty) : 'N/A',
                    'jobDetail' => [
                        'startdate' => $job->created_at ? date("jS F Y", strtotime($job->created_at)) : 'N/A',
                        'duration' => $job->preferred_assignment_duration ? \App\Providers\AppServiceProvider::keywordTitle($job->preferred_assignment_duration) : 'N/A',
                        'shift' => $job->preferred_shift_duration ? \App\Providers\AppServiceProvider::keywordTitle($job->preferred_shift_duration) : 'N/A',
                        'workdays' => $job->preferred_days_of_the_week ?: 'N/A',
                    ]
                ];
            }
        }

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function appliedNurses(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'job_id' => 'required',
        ]);

        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            $check_exists = Follows::where([
                'status' => '1',
                'job_id' => $request->job_id,
            ]);

            if ($check_exists->count() > 0) {
                $nurses_applied = $check_exists->get();
                $response = [];
                foreach ($nurses_applied as $key => $applied) {
                    $data['nurse_user_id']  = (isset($applied->creator->id) && $applied->creator->id != "") ? $applied->creator->id : "";
                    $nurse_info = NURSE::where(['user_id' => $data['nurse_user_id']]);

                    $data['nurse_id'] = "";
                    if ($nurse_info->count() > 0) {
                        $nurse = $nurse_info->first();
                        $data['nurse_id'] = $nurse->id;
                    }
                    $fname = (isset($applied->creator->first_name) && $applied->creator->first_name != "") ? $applied->creator->first_name : "";
                    $lname = (isset($applied->creator->last_name) && $applied->creator->last_name != "") ? $applied->creator->last_name : "";
                    $data['name'] = $fname . ' ' . $lname;
                    $data['profile'] = (isset($applied->creator->image) && $applied->creator->image != "") ? url('storage/assets/nurses/profile/' . $applied->creator->image) : "";

                    $profileNurse = "";
                    if ($applied->creator->image) {
                        $t = \Illuminate\Support\Facades\Storage::exists('assets/nurses/profile/' . $applied->creator->image);
                        if ($t) {
                            $profileNurse = \Illuminate\Support\Facades\Storage::get('assets/nurses/profile/' . $applied->creator->image);
                        }
                    }
                    $data["profile_base"] = ($profileNurse != "") ? 'data:image/jpeg;base64,' . base64_encode($profileNurse) : "";
                    /* rating */
                    $data['rating'] = "0";
                    $overall = [];
                    if ($data['nurse_id'] != "") {
                        $nurse_rating_info = NurseRating::where(['nurse_id' => $data['nurse_id'], 'status' => '1', 'is_deleted' => '0']);
                        if ($nurse_rating_info->count() > 0) {
                            foreach ($nurse_rating_info->get() as $key => $r) {
                                $overall[] = $r->overall;
                            }
                        }
                    }
                    $data['rating'] = $this->ratingCalculation(count($overall), $overall);
                    /* rating */

                    $response[] = $data;
                }
                if (!empty($response)) {
                    $this->check = "1";
                    $this->message = "Applied nurses listed successfully";
                    $this->return_data = $response;
                } else {
                    $this->message = "Nurse applied jobs looks empty";
                }
            } else {
                $this->message = "Nurse applied jobs looks empty";
            }
        }

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function nurseRating(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'user_id' => 'required',
            'nurse_id' => 'required',
            'job_id' => 'required',
        ]);

        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            $user_info = USER::where('id', $request->user_id);
            if ($user_info->count() > 0) {
                $user = $user_info->get()->first();
                $nurse_info = NURSE::where('id', $request->nurse_id);
                if ($nurse_info->count() > 0) {
                    $nurse = $nurse_info->get()->first();
                    $insert_array['nurse_id'] = $nurse->id;
                    if (isset($request->job_id) && $request->job_id != "")
                        $insert_array['job_id'] = $request->job_id;
                    if (isset($request->overall) && $request->overall != "")
                        $update_array['overall'] = $insert_array['overall'] = $request->overall;
                    if (isset($request->clinical_skills) && $request->clinical_skills != "")
                        $update_array['clinical_skills'] = $insert_array['clinical_skills'] = $request->clinical_skills;
                    if (isset($request->nurse_teamwork) && $request->nurse_teamwork != "")
                        $update_array['nurse_teamwork'] = $insert_array['nurse_teamwork'] = $request->nurse_teamwork;
                    if (isset($request->interpersonal_skills) && $request->interpersonal_skills != "")
                        $update_array['interpersonal_skills'] = $insert_array['interpersonal_skills'] = $request->interpersonal_skills;
                    if (isset($request->work_ethic) && $request->work_ethic != "")
                        $update_array['work_ethic'] = $insert_array['work_ethic'] = $request->work_ethic;
                    if (isset($request->experience) && $request->experience != "")
                        $update_array['experience'] = $insert_array['experience'] = $request->experience;

                    $check_exists = NurseRating::where(['nurse_id' => $nurse->id, 'job_id' => $request->job_id]);
                    if ($check_exists->count() > 0) {
                        $rating_row = $check_exists->first();
                        $data = NurseRating::where(['id' => $rating_row->id])->update($update_array);
                    } else {
                        $data = NurseRating::create($insert_array);
                    }

                    if (isset($data) && $data == true) {
                        $this->check = "1";
                        $this->message = "Your rating is submitted successfully";
                    } else {
                        $this->message = "Failed to update ratings, Please try again later";
                    }
                } else {
                    $this->message = "Nurse not found";
                }
            } else {
                $this->message = "User not found";
            }
        }

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function settingsFacility(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'facility_id' => 'required',
        ]);
        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            $facility_id = (isset($request->facility_id) && $request->facility_id != "") ? $request->facility_id : "";
            $facility_info = Facility::where(['id' => $facility_id, 'active' => '1']);
            if ($facility_info->count() > 0) {
                $facility = $facility_info->first();

                $return_data['facility_name'] = (isset($facility->name) && $facility->name != "") ? $facility->name : "";
                $return_data['address'] = (isset($facility->address) && $facility->address != "") ? $facility->address : "";
                $return_data['city'] = (isset($facility->city) && $facility->city != "") ? $facility->city : "";
                $return_data['state'] = (isset($facility->state) && $facility->state != "") ? $facility->state : "";
                $return_data['postcode'] = (isset($facility->postcode) && $facility->postcode != "") ? $facility->postcode : "";


                /* rating */
                $rating_info = FacilityRating::where(['facility_id' => $facility->id]);
                $overall = $on_board = $nurse_team_work = $leadership_support = $tools_todo_my_job = $a = [];
                if ($rating_info->count() > 0) {
                    foreach ($rating_info->get() as $key => $r) {
                        $overall[] = $r->overall;
                        $on_board[] = $r->on_board;
                        $nurse_team_work[] = $r->nurse_team_work;
                        $leadership_support[] = $r->leadership_support;
                        $tools_todo_my_job[] = $r->tools_todo_my_job;
                    }
                }
                $rating['over_all'] = $this->ratingCalculation(count($overall), $overall);
                $rating['on_board'] = $this->ratingCalculation(count($on_board), $on_board);
                $rating['nurse_team_work'] = $this->ratingCalculation(count($nurse_team_work), $nurse_team_work);
                $rating['leadership_support'] = $this->ratingCalculation(count($leadership_support), $leadership_support);
                $rating['tools_todo_my_job'] = $this->ratingCalculation(count($tools_todo_my_job), $tools_todo_my_job);
                $return_data["rating"] = $rating;
                /* rating */
                $return_data['review'] = strval($rating_info->count());
                $follow_count = FacilityFollows::where(['facility_id' => $facility->id])->count();
                $return_data['followers'] = strval($follow_count);

                $this->check = "1";
                $this->message = "Facility settings data listed successfully";
                $this->return_data = $return_data;
            } else {
                $this->message = "Facility not found";
            }
        }

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function notificationFacility(Request $request)
    {

        $created_by = (isset($request->user_id) && $request->user_id != "") ? $request->user_id : "";

        $ret = Offer::whereIn('job_id', function ($query) use ($created_by) {
            $query->select('id')
                ->from(with(new Job)->getTable())
                // ->whereIn('category_id', ['223', '15'])
                ->where('created_by', $created_by)
                ->where('active', 1);
        })->where('is_view', false)
            ->where('expiration', '>=', date('m/d/Y H:i:s'))
            ->orderBy('created_at', 'desc');

        if ($ret->count() > 0) {
            $n = [];
            $notifications = $ret->get();
            foreach ($notifications as $notification) {
                $user = USER::where(['id' => $notification->nurse->user_id])->first();
                $n[] = [
                    "notification_id" => $notification->id, "message" => "You have sent a new offer to " . $user->first_name . ' ' . $user->last_name . " that matches your <b style='color:#2BE3BD'> " . \App\Providers\AppServiceProvider::keywordTitle($notification->job->preferred_specialty) . " </b> job assignment preference and or profile."
                ];
            }
            $this->check = "1";
            $this->message = "Notifications has been listed successfully";
            $this->return_data = $n;
        } else {
            $this->check = "1";
            $this->message = "Currently there are no notifications";
        }

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function userImages(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'user_ids' => 'required',
        ]);
        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            $user_ids = (isset($request->user_ids) && $request->user_ids != "") ? $request->user_ids : "";
            if ($user_ids != "") {
                $user_id = explode(",", $user_ids);
                if (is_array($user_id) && !empty($user_id)) {
                    $user_info = User::whereIn('id', $user_id);
                    if ($user_info->count() > 0) {
                        $users = $user_info->get();
                        $a = [];
                        foreach ($users as $key => $u) {
                            if ($u->role == "NURSE") {
                                $profileNurse = \Illuminate\Support\Facades\Storage::get('assets/nurses/8810d9fb-c8f4-458c-85ef-d3674e2c540a');
                                if ($u->image) {
                                    $t = \Illuminate\Support\Facades\Storage::exists('assets/nurses/profile/' . $u->image);
                                    if ($t) {
                                        $profileNurse = \Illuminate\Support\Facades\Storage::get('assets/nurses/profile/' . $u->image);
                                    }
                                }

                                $a[] = ['id' => $u->id, 'image' => 'data:image/jpeg;base64,' . base64_encode($profileNurse)];
                            } elseif ($u->role == "FACILITYADMIN") {
                                $facility_logo = "";
                                if ($u->facilities[0]->facility_logo) {
                                    $t = \Illuminate\Support\Facades\Storage::exists('assets/facilities/facility_logo/' . $u->facilities[0]->facility_logo);
                                    if ($t) {
                                        $facility_logo = \Illuminate\Support\Facades\Storage::get('assets/facilities/facility_logo/' . $u->facilities[0]->facility_logo);
                                    }
                                }
                                $facility_logo_base = ($facility_logo != "") ? 'data:image/jpeg;base64,' . base64_encode($facility_logo) : "";

                                $a[] = ['id' => $u->id, 'image' => $facility_logo_base];
                            }
                        }

                        $this->check = "1";
                        $this->message = "Users images listed successfully";
                        $this->return_data = $a;
                    } else {
                        $this->message = "No users found";
                    }
                } else {
                    $this->message = "Input error";
                }
            } else {
                $this->message = "User ids looks empty";
            }
        }

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }
}
