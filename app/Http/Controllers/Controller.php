<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Enums\State;
use App\Enums\WeekDays;
use App\Enums\KeywordEnum;
use App\Enums\Languages;
use App\Models\Keyword;
use Illuminate\Support\Facades\Auth;
use App\Models\Experience;
use App\Models\Facility;
use App\Models\Nurse;
use App\Models\Department;
use App\Models\Countries;
use App\Models\States;
use App\Models\Cities;
use App\Models\EmailTemplate;
use App\Models\FacilityRating;
use App\Models\NurseRating;
use Illuminate\Database\Eloquent\Builder;
use Spatie\Geocoder\Geocoder;
use App\Models\Offer;
use App\Models\Invite;
use App\Mail\ChoosePasswordMailable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function getStateOptions()
    {
        $ret = ['' => 'Select State'];
        foreach (State::getKeys() as $key) {
            $ret[$key] = $key;
        }
        return $ret;
    }
    public function getKeywordOptions()
    {
        $ret = ['' => 'Select Keyword Filter'];
        foreach (KeywordEnum::getKeys() as $key) {
            $ret[$key] = $key;
        }
        return $ret;
    }
    public function getWeekDayOptions()
    {
        foreach (WeekDays::getKeys() as $key) {
            $ret[$key] = $key;
        }
        return $ret;
    }
    public function getLanguageOptions()
    {
        foreach (Languages::getKeys() as $key) {
            $ret[$key] = self::getLanguageOption($key);
        }
        return $ret;
    }
    public static function getLanguageOption($key)
    {
        if ($key == Languages::getKey(Languages::FrenchFrenchCreole)) {
            return 'French & French Creole';
        } else if ($key == Languages::getKey(Languages::FilipinoORTagalog)) {
            return 'Filipino or Tagalog';
        } else if ($key == Languages::getKey(Languages::Chinese)) {
            return 'Chinese (Cantonese, Mandarin, other varieties)';
        } else {
            return $key;
        }
    }
    public function getAssignmentDurations()
    {
        $ret = Keyword::where('active', true)->where('filter', 'AssignmentDuration')->orderBy('title', 'ASC');
        return $ret;
    }
    public function getLeadershipRoles()
    {
        $ret = Keyword::where('active', true)->where('filter', 'LeadershipRoles')->orderBy('title', 'ASC');
        return $ret;
    }
    public function getNursingDegrees()
    {
        $ret = Keyword::where('active', true)->where('filter', 'NursingDegree')->orderBy('title', 'ASC');
        return $ret;
    }
    public function getSpecialities()
    {
        $ret = Keyword::where('active', true)->where('filter', 'Speciality')->orderBy('title', 'ASC');
        return $ret;
    }
    public function getEducations()
    {
        $ret = Keyword::where('active', true)->where('filter', 'Education')->orderBy('title', 'ASC');
        return $ret;
    }
    public function getCertifications()
    {
        $ret = Keyword::where('active', true)->where('filter', 'Certification')->orderBy('title', 'ASC');
        return $ret;
    }
    public function getEHRSoftwares()
    {
        $ret = Keyword::where('active', true)->where('filter', 'EHRSoftwares')->orderBy('title', 'ASC');
        return $ret;
    }
    public function getEHRProficiencies()
    {
        $ret = Keyword::where('active', true)->where('filter', 'EHRProficiency')->orderBy('title', 'ASC');
        return $ret;
    }
    public function getEHRProficiencyExp()
    {
        $ret = Keyword::where('active', true)->where('filter', 'EHRProficiencyExp')->orderBy('title', 'ASC');
        return $ret;
    }
    public function getShifts()
    {
        $ret = Keyword::where('active', true)->where('filter', 'Shift')->orderBy('title', 'ASC');
        return $ret;
    }
    public function getGeographicPreferences()
    {
        $ret = Keyword::where('active', true)->where('filter', 'GeographicPreference')->orderBy('title', 'ASC');
        return $ret;
    }
    public function getDaisyCategories()
    {
        $ret = Keyword::where('active', true)->where('filter', 'DaisyCategory')->orderBy('title', 'ASC');
        return $ret;
    }
    public function getFacilityType()
    {
        $ret = Keyword::where('active', true)->where('filter', 'FacilityType')->orderBy('title', 'ASC');
        return $ret;
    }
    public function getPreferredShift()
    {
        $ret = Keyword::where('active', true)->where('filter', 'PreferredShift')->orderBy('title', 'ASC');
        return $ret;
    }
    public function getEMedicalRecords()
    {
        $ret = Keyword::where('active', true)->where('filter', 'EMedicalRecords')->orderBy('title', 'ASC');
        return $ret;
    }
    public function getBCheckProvider()
    {
        $ret = Keyword::where('active', true)->where('filter', 'BCheckProvider')->orderBy('title', 'ASC');
        return $ret;
    }
    public function getNCredentialingSoftware()
    {
        $ret = Keyword::where('active', true)->where('filter', 'NCredentialingSoftware')->orderBy('title', 'ASC');
        return $ret;
    }
    public function getNSchedulingSystem()
    {
        $ret = Keyword::where('active', true)->where('filter', 'NSchedulingSystem')->orderBy('title', 'ASC');
        return $ret;
    }
    public function getTimeAttendanceSystem()
    {
        $ret = Keyword::where('active', true)->where('filter', 'TimeAttendanceSystem')->orderBy('title', 'ASC');
        return $ret;
    }
    public function getTraumaDesignation()
    {
        $ret = Keyword::where('active', true)->where('filter', 'TraumaDesignation')->orderBy('title', 'ASC');
        return $ret;
    }
    public function getSeniorityLevel()
    {
        $ret = Keyword::where('active', true)->where('filter', 'SeniorityLevel')->orderBy('title', 'ASC');
        return $ret;
    }
    public function getJobFunction()
    {
        $ret = Keyword::where('active', true)->where('filter', 'JobFunction')->orderBy('title', 'ASC');
        return $ret;
    }
    public function emailRegEx($user = null)
    {
        if ($user) {
            return 'required|email|max:255|unique:users,email,' . $user->id;
        }
        return 'required|email|max:255|unique:users,email';
    }
    public function passwordRegEx()
    {
        return 'required|string|confirmed|min:6|max:255|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[\[\]\{\}\';:\.,#?!@$%^&*-]).{6,}$/';
    }
    public function nurseExperienceSelection($nurse)
    {
        $ret = Experience::where('nurse_id', $nurse->id)
            ->orderBy('created_at', 'DESC')
            ->get();
        return $ret;
    }
    public function facilitySelection($search_text = null)
    {
        $whereCond = [
            'active' => true
        ];
        $ret = Facility::where($whereCond)
            ->orderBy('created_at', 'desc');
        if (isset($search_text) && $search_text) {
            $ret->search([
                'name',
                'facility_email',
                'city',
            ], $search_text);
        }
        return $ret;
    }
    public function departmentSelection()
    {
        $whereCond = [
            'active' => true
        ];
        $ret = Department::where($whereCond)
            ->orderBy('created_at', 'desc');
        return $ret;
    }
    public function nurseSelection($search_text = null)
    {
        $whereCond = [
            'active' => true
        ];
        $ret = Nurse::where($whereCond)
            ->orderBy('created_at', 'desc');
        if ($search_text) {
            $tmpNames = explode(" ", $search_text);
            $ret->whereHas('user', function (Builder $query) use ($tmpNames) {
                $query->whereIn('first_name', $tmpNames);
                $query->orWhere(function (Builder $query) use ($tmpNames) {
                    $query->whereIn('last_name', $tmpNames);
                });
                $query->orWhere(function (Builder $query) use ($tmpNames) {
                    $query->whereIn('email', $tmpNames);
                });
            });
        }
        return $ret;
    }

    public function parse_youtube($link)
    {

        $regexstr = '~
			# Match Youtube link and embed code
			(?:				 				# Group to match embed codes
				(?:<iframe [^>]*src=")?	 	# If iframe match up to first quote of src
				|(?:				 		# Group to match if older embed
					(?:<object .*>)?		# Match opening Object tag
					(?:<param .*</param>)*  # Match all param tags
					(?:<embed [^>]*src=")?  # Match embed tag to the first quote of src
				)?				 			# End older embed code group
			)?				 				# End embed code groups
			(?:				 				# Group youtube url
				https?:\/\/		         	# Either http or https
				(?:[\w]+\.)*		        # Optional subdomains
				(?:               	        # Group host alternatives.
				youtu\.be/      	        # Either youtu.be,
				| youtube\.com		 		# or youtube.com
				| youtube-nocookie\.com	 	# or youtube-nocookie.com
				)				 			# End Host Group
				(?:\S*[^\w\-\s])?       	# Extra stuff up to VIDEO_ID
				([\w\-]{11})		        # $1: VIDEO_ID is numeric
				[^\s]*			 			# Not a space
			)				 				# End group
			"?				 				# Match end quote if part of src
			(?:[^>]*>)?			 			# Match any extra stuff up to close brace
			(?:				 				# Group to match last embed code
				</iframe>		         	# Match the end of the iframe
				|</embed></object>	        # or Match the end of the older embed
			)?				 				# End Group of last bit of embed code
			~ix';

        preg_match($regexstr, $link, $matches);

        return $matches;
    }

    public function update_latlang($address = null, $city = null, $state = null, $postcode = null)
    {

        $client = new \GuzzleHttp\Client();
        $geocoder = new Geocoder($client);
        $geocoder->setApiKey(config('geocoder.key'));
        $geocoder->setCountry(config('geocoder.country', 'US'));
        if ($address && $city && $state && $postcode) {
            $fulladdress = $address . ', ' . $city . ', ' . $state . ', ' . $postcode;
            return $geocoder->getCoordinatesForAddress($fulladdress);
        } elseif ($postcode) {
            return $geocoder->getCoordinatesForAddress($postcode);
        } elseif ($city && $state) {
            return $geocoder->getCoordinatesForAddress($city . ', ' . $state);
        } elseif ($city && !$state) {
            return $geocoder->getCoordinatesForAddress($city);
        } elseif (!$city && $state) {
            return $geocoder->getCoordinatesForAddress($state);
        }
    }

    public function parse_vimeo($link)
    {

        $regexstr = '~
			# Match Vimeo link and embed code
			(?:<iframe [^>]*src=")?		# If iframe match up to first quote of src
			(?:							# Group vimeo url
				https?:\/\/				# Either http or https
				(?:[\w]+\.)*			# Optional subdomains
				vimeo\.com				# Match vimeo.com
				(?:[\/\w]*\/videos?)?	# Optional video sub directory this handles groups links also
				\/						# Slash before Id
				([0-9]+)				# $1: VIDEO_ID is numeric
				[^\s]*					# Not a space
			)							# End group
			"?							# Match end quote if part of src
			(?:[^>]*></iframe>)?		# Match the end of the iframe
			(?:<p>.*</p>)?		        # Match any title information stuff
			~ix';

        preg_match($regexstr, $link, $matches);

        return $matches;
    }

    public function getNotifications()
    {
        $user = Auth::user();
        $whereCond = [
            'active' => true
        ];
        if (Auth::user() && $user->hasRole(['Nurse'])) {
            $ret = Offer::where($whereCond)
                ->where('nurse_id', $user->nurse->id)
                ->whereNotNull('job_id')
                ->where('is_view', false)
                ->where('expiration', '>=', date('Y-m-d H:i:s'))
                ->orderBy('created_at', 'desc');
            return $ret;
        }
        return Offer::where($whereCond)
            ->where('nurse_id', null)
            ->orderBy('created_at', 'desc');
    }

    public function sendNotifyEmail($invite)
    {
        $to_email = $invite->user->email;
        $encodeEmail = md5($to_email);
        $url = URL::temporarySignedRoute('update-pwd', now()->addHours(Invite::VALID_FOR_HOURS), ['token' => $invite->token, 'emailID' => $encodeEmail]);
        // Mail::to($to_email)->send(new ChoosePasswordMailable($url));
        $data = ['to_email' => $to_email, 'to_name' => $invite->user->first_name . ' ' . $invite->user->last_name];
        $replace_array = ['###RESETLINK###' => $url];
        $this->basic_email($template = "admin_invite_change_password", $data, $replace_array);
    }

    public function withinMaxDistance($rat, $location, $radius = 50)
    {

        $haversine = "(3961 * acos(cos(radians($location->latitude))
						* cos(radians(model.latitude))
						* cos(radians(model.longitude)
						- radians($location->longitude))
						+ sin(radians($location->latitude))
						* sin(radians(model.latitude))))";
        return $rat
            ->select("*") //pick the columns you want here.
            ->selectRaw("{$haversine} AS distance")
            ->whereRaw("{$haversine} < ?", [$radius]);
    }

    public function getCountries()
    {
        $ret = Countries::orderBy('name', 'ASC');
        return $ret;
    }

    public function getUsaStates()
    {
        $ret = States::where(['country_id' => '233'])->orderBy('name', 'ASC');
        return $ret;
    }

    public function basic_email($template = null, $data = [], $replace_array = [])
    {
        $body_content = $arr['subject'] = "";
        $temp = EmailTemplate::where(['status' => '1', 'slug' => $template]);
        if ($temp->count() > 0) {
            $t = $temp->first();
            $arr['subject'] = $t->label;
            $body_content = strtr($t->content, $replace_array);
        }

        $arr['to_email'] = (isset($data['to_email']) && $data['to_email'] != "") ? $data['to_email'] : "";
        $arr['to_name'] = (isset($data['to_name']) && $data['to_name'] != "") ? $data['to_name'] : "";

        Mail::send(['html' => 'mail-templates.template'], array("content" => $body_content), function ($message) use ($arr) {
            $message->to($arr['to_email'], $arr['to_name'])->subject($arr['subject']);
            $message->from('noreply@nurseify.app', 'Team Nurseify');
        });
        // echo "Basic Email Sent. Check your inbox.";
    }

    public function adminFacilityRating($facility_id = "", $nurse_id = "")
    {
        $facility_rating_where_overall = ['facility_id' => $facility_id, 'is_deleted' => '0'];
        $rating_info_over_all = FacilityRating::where($facility_rating_where_overall);
        $overall_rating = $on_board = $nurse_team_work = $leadership_support = $tools_todo_my_job = [];
        if ($rating_info_over_all->count() > 0) {
            foreach ($rating_info_over_all->get() as $key => $r) {
                $overall_rating[] = $r->overall;
                $on_board[] = $r->on_board;
                $nurse_team_work[] = $r->nurse_team_work;
                $leadership_support[] = $r->leadership_support;
                $tools_todo_my_job[] = $r->tools_todo_my_job;
            }
        }
        $rating['over_all'] = $this->ratingCalculationOverall(count($overall_rating), $overall_rating);
        $ratings['on_board'] = $this->ratingCalculationOverall(count($on_board), $on_board);
        $ratings['nurse_team_work'] = $this->ratingCalculationOverall(count($nurse_team_work), $nurse_team_work);
        $ratings['leadership_support'] = $this->ratingCalculationOverall(count($leadership_support), $leadership_support);
        $ratings['tools_todo_my_job'] = $this->ratingCalculationOverall(count($tools_todo_my_job), $tools_todo_my_job);

        return $rating;
    }

    public function adminNurseRating($job_id = "", $nurse_id = "")
    {
        /* rating */
        if ($job_id != "")
            $nurse_rating_where = ['job_id' => $job_id, 'nurse_id' => $nurse_id, 'is_deleted' => '0'];
        else
            $nurse_rating_where = ['nurse_id' => $nurse_id, 'is_deleted' => '0'];

        $rating_info = NurseRating::where($nurse_rating_where);
        $overall = $clinical_skills = $nurse_teamwork = $interpersonal_skills = $work_ethic = $experience = [];
        if ($rating_info->count() > 0) {
            foreach ($rating_info->get() as $key => $r) {
                $overall[] = $r->overall;
                $clinical_skills[] = $r->clinical_skills;
                $nurse_teamwork[] = $r->nurse_teamwork;
                $interpersonal_skills[] = $r->interpersonal_skills;
                $work_ethic[] = $r->work_ethic;
                $experience[] = $r->experience;
            }
        }
        $ratings['over_all'] = $this->ratingCalculationOverall(count($overall), $overall);
        $ratings['clinical_skills'] = $this->ratingCalculationOverall(count($clinical_skills), $clinical_skills);
        $ratings['nurse_team_work'] = $this->ratingCalculationOverall(count($nurse_teamwork), $nurse_teamwork);
        $ratings['interpersonal_skills'] = $this->ratingCalculationOverall(count($interpersonal_skills), $interpersonal_skills);
        $ratings['work_ethic'] = $this->ratingCalculationOverall(count($work_ethic), $work_ethic);
        $ratings['experience'] = $experience;
        /* rating */

        return $ratings;
    }

    public function ratingCalculationOverall($count, $array)
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
}
