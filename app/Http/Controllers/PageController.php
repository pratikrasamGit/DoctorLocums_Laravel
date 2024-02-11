<?php

namespace App\Http\Controllers;

use App\Models\Nurse;
use App\Models\Facility;
use App\Models\Certification;
use App\Models\NurseAsset;
use App\Models\Job;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\Offer;
use App\Models\States;
use App\Models\Cities;
use App\Models\FacilityRating;
use App\Models\NurseRating;
use App\Enums\OfferStatus;
use Illuminate\Support\Facades\File;

class PageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function browse_nurses(Request $request)
    {
        $controller = new Controller();
        $nurses = $this->nurseSelections($request)->paginate(9);

        $rating = [];
        foreach ($nurses as $key => $value) {
            $rating[$value->id] = $controller->adminNurseRating($job_id = "", $nurse_id = $value->id);
        }

        return view('pages.nurses.list')->with(
            compact(['nurses', 'rating'])
        );
    }

    public function nurseSelections($request)
    {
        // dd($request->all());
        $whereCond = [
            'active' => true
        ];

        $order_by = (isset($request->view) && $request->view != "") ? $request->view : "";
        if ($order_by == "oldest") {
            $a = 'created_at';
            $b = 'asc';
        } elseif ($order_by == "newest") {
            $a = 'created_at';
            $b = 'desc';
        } elseif ($order_by == "low-to-high") {
            $a = 'hourly_pay_rate';
            $b = 'asc';
        } elseif ($order_by == "high-to-low") {
            $a = 'hourly_pay_rate';
            $b = 'desc';
        } else {
            $a = 'created_at';
            $b = 'desc';
        }

        $ret = Nurse::where($whereCond)
            ->orderBy($a, $b);

        $specialty = $request->specialty;
        $availability = $request->availability;
        $search_keyword = $request->search_keyword;
        $search_bill_rate = $request->search_bill_rate;
        $search_tenure = $request->search_tenure;
        $certification = $request->certification;

        /*specialty filter nwe update 06/dec/2021 */
        if ($specialty) {
            $ret->where(function (Builder $query) use ($specialty) {
                foreach ($specialty as $key => $search_spl_id) {
                    if ($search_spl_id != "")
                        $query->orWhere('specialty', 'like', '%' . $search_spl_id . '%');
                }
            });
        }
        /*specialty filter nwe update 06/dec/2021 */

        if ($availability) {
            $ret->whereHas('availability', function (Builder $query1) use ($availability) {
                $query1->whereIn('days_of_the_week', $availability);
            });
        }

        if ($search_bill_rate) {
            $tmpRates = explode(",", $search_bill_rate);
            $finaltmpRatef = $tmpRates[0];
            $finaltmpRatet = $tmpRates[1];
            $ret->where(function (Builder $query) use ($finaltmpRatef, $finaltmpRatet) {
                $query->whereBetween('facility_hourly_pay_rate', array(intval($finaltmpRatef), intval($finaltmpRatet)));
            });
        }

        if ($search_tenure) {
            $tmpTenures = explode(",", $search_tenure);
            $finaltmpTenuref = $tmpTenures[0];
            $finaltmpTenuret = $tmpTenures[1];
            $ret->where(function (Builder $query) use ($finaltmpTenuref, $finaltmpTenuret) {
                $query->whereBetween('experience_as_acute_care_facility', array(intval($finaltmpTenuref), intval($finaltmpTenuret)));
                $query->orWhere(function (Builder $query) use ($finaltmpTenuref, $finaltmpTenuret) {
                    $query->whereBetween('experience_as_ambulatory_care_facility', array(intval($finaltmpTenuref), intval($finaltmpTenuret)));
                });
            });
        }

        if ($certification) {
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

        /* keywords filter new update 06/dec/2021 */
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
        /* keywords filter new update 06/dec/2021 */

        return $ret;
    }

    /* below function fetches the surrounded zipcode around 50 mile for provided zipcode new update 06/dec/2021 */
    public function getNearestMiles($zipcode)
    {
        $return = [];
        if ($zipcode != "") {
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://app.zipcodebase.com/api/v1/radius?code=' . $zipcode . '&country=US&unit=miles&radius=50',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => array(
                    'apikey: 106b0400-37d6-11ec-b135-07247a2b9eab'
                ),
            ));

            $response = curl_exec($curl);

            if (curl_errno($curl)) {
                $error_msg = curl_error($curl);
            }
            curl_close($curl);

            $return = (isset($error_msg)) ? [] : json_decode($response, TRUE);
        }

        return $return;
    }
    /* below function fetches the surrounded zipcode around 50 mile for provided zipcode new update 06/dec/2021 */


    /* major cities in us, new update 06/dec/2021 */
    public function citiesList()
    {
        $cities_list = [
            "anchorage" => "99507", "juneau" => "99801", "fairbanks" => "99709", "badger" => "99705", "palmer" => "99645", "birmingham" => "35211", "huntsville" => "35810", "montgomery" => "36117", "mobile" => "36695", "tuscaloosa" => "35405", "little rock" => "72204", "fayetteville" => "72701", "fort smith" => "72903", "springdale" => "72764", "jonesboro" => "72401", "phoenix" => "85032", "tucson" => "85710", "mesa" => "85204", "chandler" => "85225", "scottsdale" => "85251", "los angeles" => "90011", "san diego" => "92154", "san jose" => "95123", "san francisco" => "94112", "fresno" => "93722", "denver" => "80219", "colorado springs" => "80918", "aurora" => "80013", "fort collins" => "80525", "lakewood" => "80226", "bridgeport" => "06606", "new haven" => "06511", "stamford" => "06902", "hartford" => "06106", "waterbury" => "06708", "washington" => "20011", "shaw" => "20001", "adams morgan" => "20009", "chevy chase" => "20015", "bloomingdale" => "20001", "wilmington" => "19805", "dover" => "19904", "newark" => "19711", "middletown" => "19709", "bear" => "19701", "jacksonville" => "32210", "miami" => "33186", "tampa" => "33647", "orlando" => "32811", "st. petersburg" => "33710", "atlanta" => "30349", "augusta" => "30906", "columbus" => "31907", "savannah" => "31405", "athens" => "30606", "honolulu" => "96817", "east honolulu" => "96818", "pearl city" => "96782", "hilo" => "96720", "kailua" => "96740", "des moines" => "50317", "cedar rapids" => "52402", "davenport" => "52806", "sioux city" => "51106", "iowa city" => "52240", "boise" => "83709", "meridian" => "83646", "nampa" => "83686", "idaho falls" => "83401", "caldwell" => "83605", "chicago" => "60629", "aurora" => "60505", "naperville" => "60565", "joilet" => "60435", "rockford" => "61107", "indianapolis" => "46227", "fort wayne" => "46835", "evansville" => "47714", "carmel" => "46032", "south bend" => "46614", "wichita" => "67212", "overland park" => "66212", "kansas city" => "66102", "olathe" => "66062", "topeka" => "66614", "louisville" => "40299", "lexington" => "40509", "bowling green" => "42101", "owensboro" => "42301", "covington" => "41011", "new orleans" => "70119", "baton rouge" => "70808", "shreveport" => "71106", "metairie" => "70003", "lafayette" => "70506", "boston" => "02124", "worcester" => "01604", "springfield" => "01109", "cambridge" => "02139", "lowell" => "01852", "baltimore" => "21215", "columbia" => "21044", "germantown" => "20874", "silver spring" => "20906", "waldorf" => "20602", "detroit" => "48228", "grand rapids" => "49504", "warren" => "48089", "sterling heights" => "48310", "lansing " => "48911", "minneapolis" => "55407", "st. paul" => "55106", "rochester" => "55901", "duluth" => "55811", "bloomington" => "55420", "kansas city" => "64114", "st. louis" => "63116", "springfield" => "65807", "columbia" => "65203", "independence " => "64055", "jackson" => "39212", "gulfport" => "39503", "southaven" => "38671", "biloxi" => "39531", "hattiesburg" => "39401", "billings" => "59101", "missoula" => "59808", "great falls" => "59401", "bozeman" => "59715", "butte" => "59701", "charlotte" => "28205", "raleigh" => "27603", "greensboro" => "27413", "durham" => "27703", "winston-salem" => "27101", "fargo" => "58102", "bismarck" => "58501", "grand forks" => "58201", "minot" => "58701", "west fargo" => "58078", "omaha" => "68007", "lincon " => "68501", "bellevue" => "68005", "grand island" => "68801", "kearney" => "68845", "manchester" => "03101", "nashua" => "03060", "concord" => "03301", "dover" => "03820", "rochester" => "03867", "newark" => "07101", "jersey city" => "07302", "paterson" => "07501", "elizabeth" => "07201", "toms river" => "08753", "albuquerque" => "87101", "las cruces" => "88001", "rio rancho" => "87144", "santa fe" => "87501", "roswell" => "88202", "las vegas" => "88901", "henderson" => "89002", "reno" => "89502", "north las vegas" => "89030", "paradise" => "89103", "new york" => "10011", "buffalo" => "14201", "rochester" => "14602", "yonkers" => "10701", "syracuse" => "13201", "columbus" => "43210", "cleveland" => "44101", "cincinnati" => "45003", "toledo" => "43604", "akron" => "44320", "oklahoma city" => "73008", "tulsa" => "74008", "norman" => "73019", "broken arrow" => "74011", "edmond" => "73003", "portland" => "97201", "salem" => "97301", "eugene" => "97402", "hillsboro" => "97124", "gresham" => "97080", "philadelphia" => "19102", "pittsburgh" => "15222", "allentown" => "18104", "erie" => "16504", "reading" => "19602", "providence" => "02901", "warwick" => "02886", "cranston" => "02920", "pawtucket" => "02861", "east providence" => "02914", "north charleston" => "29405", "mount pleasant" => "29464", "rock hill" => "29732", "greenville" => "29611", "summerville" => "29485", "sioux falls" => "57101", "rapid city" => "57701", "aberdeen" => "57401", "brookings" => "57006", "watertown" => "57201", "nashville" => "37011", "memphis" => "37501", "knoxville" => "37901", "clarksville" => "37040", "chattanooga" => "37341", "houston" => "77002", "austin" => "78701", "san antonio" => "78204", "dallas" => "75201", "fort worth" => "76102", "salt lake city" => "84101", "west valley city" => "84119", "west jordan" => "84081", "provo" => "84097", "orem" => "84058", "virginia beach" => "23451", "chesapeake" => "23320", "norfolk" => "23502", "arlington" => "22206", "richmond" => "23220", "burlington" => "05401", "south burlington" => "05403", "rutland" => "05701", "essex junction" => "05451", "bennington" => "05201", "seattle" => "98121", "spokane" => "99201", "tacoma" => "98402", "vancouver" => "98660", "kent" => "98032", "milwaukee" => "53201", "madison" => "53558", "green bay" => "54229", "kenosha" => "53140", "racine" => "53401", "charleston" => "25301", "huntington" => "25701", "morgantown" => "26501", "parkersburg" => "26101", "wheeling" => "26003", "cheyenne" => "82001", "casper" => "82609", "laramie" => "82070", "gillette" => "82716", "rock springs" => "82901"
        ];

        return $cities_list;
    }
    /* major cities in us, new update 06/dec/2021 */


    public function view_nurse($slug)
    {
        $nurse = Nurse::where('slug', $slug)->first();
        $user = $nurse->user;
        $availability = $nurse->availability;
        $certifications = $nurse->certifications;
        $experiences = $nurse->experiences;
        $nuexperience = $this->nurseExperienceSelection($nurse);

        $controller = new Controller();
        $rating = $controller->adminNurseRating($job_id = "", $nurse_id = $nurse->id);

        return view('pages.nurses.single')->with(
            compact(['user', 'nurse', 'availability', 'certifications', 'experiences', 'nuexperience', 'rating'])
        );
    }

    public function browse_facilities(Request $request)
    {
        $controller = new Controller();
        $facilities = $this->facilitySelections($request)->paginate(9);
        $rating = [];
        foreach ($facilities as $key => $value) {
            $rating[$value->id] = $controller->adminFacilityRating($facility_id = $value->id, $nurse_id = "");
        }
        return view('pages.facilities.list')->with(
            compact(['facilities', 'rating'])
        );
    }

    public function facilitySelections($request)
    {
        $whereCond = ['facilities.active' => true, 'jobs.is_open' => "1"];

        $ret = Facility::select('facilities.*', 'jobs.preferred_specialty')
            ->leftJoin('jobs', function ($join) {
                $join->on('facilities.id', '=', 'jobs.facility_id');
            })->where($whereCond)->orderBy('created_at', 'desc');

        /*new update jan 10*/
        // $search_location = $request->search_location;
        /*new update jan 10*/

        // $search_keyword = $request->search_keyword;

        $type = $request->type;
        if ($type) {
            $ret->where(function (Builder $query) use ($type) {
                $query->whereIn('type', $type);
            });
        }

        $electronic_medical_records = $request->electronic_medical_records;
        if ($electronic_medical_records) {
            $ret->where(function (Builder $query) use ($electronic_medical_records) {
                $query->whereIn('f_emr', $electronic_medical_records);
            });
        }
        /*new update jan 10*/
        /*if ($search_location) {
            $ret->search([
                'address',
                'city',
                'state',
                'postcode'
            ], $search_location);
        }*/
        /*new update jan 10*/

        /*new update jan 10*/
        $open_assignment_type = $request->open_assignment_type;
        /*if ($open_assignment_type) {
            $ret->where('jobs.preferred_specialty', '=', $open_assignment_type);
        }*/
        if ($open_assignment_type) {
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

        return $ret;
        /* new update 08-dec-2021 */
    }

    public function view_facility(Facility $facility)
    {
        /* $facility_rating_where_overall = ['facility_id' => $facility->id, 'is_deleted' => '0'];
        $rating_info_over_all = FacilityRating::where($facility_rating_where_overall);
        $overall_rating = [];
        if ($rating_info_over_all->count() > 0) {
            foreach ($rating_info_over_all->get() as $key => $r) {
                $overall_rating[] = $r->overall;
            }
        }
        $rating['over_all_rating'] = $this->ratingCalculation(count($overall_rating), $overall_rating); */

        $controller = new Controller();
        $rating = $controller->adminFacilityRating($facility->id, $nurse_id = "");

        $showBreadcumbs = true;
        return view('pages.facilities.single')->with(
            compact(['facility', 'showBreadcumbs', 'rating'])
        );
    }

    public function browse_jobs(Request $request)
    {
        /*$whereCond = [
			'active' => true
		];
        $ret = Job::where($whereCond)
			->orderBy('created_at', 'desc');
		$jobs = $ret->paginate(9);
        return view('pages.jobs.list')->with(
			compact(['jobs'])
		);*/

        /* new */
        /* $whereCond = [
            'facilities.active' => true,
            'jobs.is_open' => "1"
        ];

        $ret = Job::select('jobs.*', 'facilities.*')
            ->leftJoin('facilities', function ($join) {
                $join->on('facilities.id', '=', 'jobs.facility_id');
            })
            ->where($whereCond)
            ->orderBy('jobs.created_at', 'desc'); */

        $user = Auth::user();
        if ($user->role == "FACILITYADMIN" || $user->role == "FACILITY") {
            $whereCond = [
                'facilities.active' => true,
                'jobs.is_open' => "1",
            ];
        } else {
            $whereCond = [
                'facilities.active' => true,
                'jobs.is_open' => "1",
                'jobs.active' => "1"
            ];
        }

        $ret = Job::select('jobs.id as job_id', 'jobs.*')
            ->leftJoin('facilities', function ($join) {
                $join->on('facilities.id', '=', 'jobs.facility_id');
            })
            ->where($whereCond)
            ->orderBy('jobs.created_at', 'desc');

        $search_location = $request->search_location;
        // $search_keyword = $request->search_keyword;
        $open_assignment_type = $request->open_assignment_type;
        $type = $request->type;
        $electronic_medical_records = $request->electronic_medical_records;
        if ($type) {
            $ret->where(function (Builder $query) use ($type) {
                $query->whereIn('type', $type);
            });
        }
        if ($electronic_medical_records) {
            $ret->where(function (Builder $query) use ($electronic_medical_records) {
                $query->whereIn('f_emr', $electronic_medical_records);
            });
        }
        if ($search_location) {
            $ret->search([
                'address',
                'city',
                'state',
                'postcode'
            ], $search_location);
        }

        if ($open_assignment_type) {
            $ret->where('jobs.preferred_specialty', '=', $open_assignment_type);
        }
        /* new */

        $jobs = $ret->paginate(10);
        $facilities = $this->facilitySelections($request)->paginate(10);
        $specialities = $this->getSpecialities()->pluck('title', 'id');
        return view('pages.jobs.list')->with(
            compact(['jobs', 'facilities', 'specialities'])
        );
    }

    public function view_job(Job $job)
    {
        $user = Auth::user();
        $job_status['status'] = "";
        foreach ($job->offers as $jo) {
            if ($jo->status == "Active") {
                $job_status['status'] = "Active";
            } elseif ($jo->status == "Rejected") {
                $job_status['status'] = "Rejected";
            }
        }
        // dd($job_status);
        return view('pages.jobs.single')->with(
            compact(['job', 'job_status'])
        );
    }

    public function view_profile()
    {
        $user = Auth::user();
        $showBreadcumbs = false;
        if (Auth::user() && $user->hasRole('Nurse') && $user->nurse) {
            $nurse = $user->nurse;
            $user = $nurse->user;
            $availability = $nurse->availability;
            $certifications = $nurse->certifications;
            $experiences = $nurse->experiences;
            $nuexperience = $this->nurseExperienceSelection($nurse);
            return view('pages.nurses.single')->with(
                compact(['user', 'nurse', 'availability', 'certifications', 'experiences', 'nuexperience'])
            );
        }
        if (Auth::user() && $user->hasRole(['Facility', 'FacilityAdmin']) && $user->facilities()->first()) {
            $facility = $user->facilities()->first();
            return view('pages.facilities.single')->with(
                compact(['facility', 'showBreadcumbs'])
            );
        }
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
        $t = Storage::exists('assets/nurses/certifications/' . $nurse->id . '/' . $certification->certificate_image);
        if ($t && $certification->certificate_image) {
            $pathToFile = storage_path('assets/nurses/certifications/' . $nurse->id . '/' . $certification->certificate_image);
            return response()->file($pathToFile);
        }
        return null;
    }

    /**
     * download the specified resource from storage.
     *
     * @param  NurseAsset $nurseAsset
     * @param  Nurse $nurse
     * @return \Illuminate\Http\Response
     */
    public function assetDownload(Nurse $nurse, NurseAsset $nurseAsset)
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
     * download the specified resource from storage.
     *
     * @param  $slug
     * @return \Illuminate\Http\Response
     */
    public function view_resume_media(Nurse $nurse)
    {
        if ($nurse->user && $nurse->user->hasRole('Nurse')) {
            $resume = $nurse->getMedia('resumes');
            if (isset($resume) && count($resume) > 0) {
                if (File::exists($resume[0]->getPath())) {
                    return response()->file($resume[0]->getPath());
                }
            }
            return null;
        }
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

    public function nurseOffers(Nurse $nurse)
    {
        $whereCond = [
            'active' => true
        ];
        $offers = Offer::where($whereCond)
            ->where('nurse_id', $nurse->id)
            ->whereNotNull('job_id')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return view('nurses.nurse-offers')->with(
            compact(['offers', 'nurse'])
        );
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @param  Job $job
     * @return \Illuminate\Http\Response
     */
    public function jobAcceptPost(Request $request, Job $job)
    {
        $user = Auth::user();

        $check_offer = Offer::where(['nurse_id' => $user->nurse->id, 'status' => 'Pending', 'active' => '1', 'job_id' => $job->id])
            ->where('expiration', '>=', date('Y-m-d H:i:s'))->orderBy('created_at', 'desc');

        if ($check_offer->count() > 0) {
            $offer = $check_offer->first();
            $update = Offer::where(['nurse_id' => $user->nurse->id,  'job_id' => $job->id])->update(['status' => 'Active']);
            if ($update) {
                $nurse_info = Nurse::where(['id' => $user->nurse->id]);
                if ($nurse_info->count() > 0) {
                    $nurse = $nurse_info->first();
                    $user_info = User::where(['id' => $nurse->user_id]);
                    if ($user_info->count() > 0) {
                        $user = $user_info->first(); // nurse user info
                        $facility_user_info = User::where(['id' => $offer->created_by]);
                        if ($facility_user_info->count() > 0) {
                            $facility_user = $facility_user_info->first(); // facility user info
                            /* mail to nurse */
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
                            /* mail to nurse */

                            /* mail to facility */
                            $facility_data = [
                                'to_email' => $facility_user->email,
                                'to_name' => $facility_user->first_name . ' ' . $facility_user->last_name
                            ];

                            $facility_replace_array = [
                                '###USERNAME###' => $facility_user->first_name . ' ' . $facility_user->last_name,
                                '###NURSENAME###' => $user->first_name . ' ' . $user->last_name,
                                '###PREFERREDSPECIALITY###' => \App\Providers\AppServiceProvider::keywordTitle($offer->job->preferred_specialty),
                                '###FACILITYNAME###' => $facility_user->facilities[0]->name,
                                '###FACILITYLOCATION###' => $facility_user->facilities[0]->city . ',' . $facility_user->facilities[0]->state,
                                '###SPECIALITY###' => \App\Providers\AppServiceProvider::keywordTitle($offer->job->preferred_specialty),
                                '###STARTDATE###' => date('d F Y', strtotime($offer->job->start_date)),
                                '###ASSIGNMENTDURATION###' => \App\Providers\AppServiceProvider::keywordTitle($offer->job->preferred_assignment_duration),
                                '###SHIFTDURATION###' => \App\Providers\AppServiceProvider::keywordTitle($offer->job->preferred_shift_duration),
                                '###PREFERREDSHIFT###' => \App\Providers\AppServiceProvider::keywordTitle($offer->job->preferred_shift),
                                '###NURSEPROFILELINK###' => url('browse-nurses/' . $nurse->slug),
                            ];
                            /* mail to facility */

                            $this->basic_email($template = "accept_offer_confirmation_facility", $facility_data, $facility_replace_array);
                        }
                    }
                }
                return redirect()->back()->with('success', __('Job Accepted.'));
            } else {
                return redirect()->back()->with('success', __('Failed to accept the job, Please try again later.'));
            }
        } else {
            return redirect()->back()->with('success', __('Offer not found or already accepted.'));
        }
        exit;
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @param  Job $job
     * @return \Illuminate\Http\Response
     */
    public function jobRejectPost(Request $request, Job $job)
    {
        $user = Auth::user();

        $check_offer = Offer::where(['nurse_id' => $user->nurse->id, 'status' => 'Pending', 'active' => '1', 'job_id' => $job->id])
            ->where('expiration', '>=', date('Y-m-d H:i:s'));
        if ($check_offer->count() > 0) {
            $offer = $check_offer->first();
            $update = Offer::where(['nurse_id' => $user->nurse->id,  'job_id' => $job->id])->update(['status' => 'Rejected']);
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
                            /* mail to nurse */
                            $data = [
                                'to_email' => $user->email,
                                'to_name' => $user->first_name . ' ' . $user->last_name
                            ];
                            $replace_array = [
                                '###NURSENAME###' => $user->first_name . ' ' . $user->last_name,
                                '###FACILITYNAME###' => $facility_user->facilities[0]->name
                            ];
                            $this->basic_email($template = "reject_offer_nurse", $data, $replace_array);
                            /* mail to nurse */

                            /* mail to facility */
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
                            /* mail to facility */
                        }
                    }
                }
                return redirect()->back()->with('success', __('Job Rejected.'));
            } else {
                return redirect()->back()->with('success', __('Failed to reject the job, Please try again later'));
            }
        } else {
            return redirect()->back()->with('success', __('Offer not found or already rejected'));
        }
        exit;
    }

    public function getStateByCountry(Request $request)
    {
        $data['states'] = States::where("country_id", $request->country_id)->get(["name", "id"]);
        return response()->json($data);
    }

    public function getCityByState(Request $request)
    {
        $data['cities'] = Cities::where("state_id", $request->state_id)->get(["name", "id"]);
        return response()->json($data);
    }

    public function getAdminFacilityRating(Request $request)
    {
        $user = Auth::user();
        $controller = new Controller();

        // $user->hasRole
        $admin_id = "";
        if ($user->role == "NURSE") {
            $admin_id = (isset($user->nurse->id) && $user->nurse->id != "") ? $user->nurse->id : "";
        } elseif ($user->role == "FULLADMIN") {
            $admin_id = (isset($user->id) && $user->id != "") ? $user->id : "";
        }
        $facility_id = (isset($request->facility_id) && $request->facility_id != "") ? $request->facility_id : "";
        $ratings = [];
        if ($admin_id != "" && $facility_id != "") {
            /* rating */
            $facility_rating_where = ['facility_id' => $facility_id, 'nurse_id' => $admin_id, 'is_deleted' => '0'];
            $rating_info = FacilityRating::where($facility_rating_where);
            $overall = $on_board = $nurse_team_work = $leadership_support = $tools_todo_my_job = [];
            $experience = "";
            if ($rating_info->count() > 0) {
                foreach ($rating_info->get() as $key => $r) {
                    $overall[] = $r->overall;
                    $on_board[] = $r->on_board;
                    $nurse_team_work[] = $r->nurse_team_work;
                    $leadership_support[] = $r->leadership_support;
                    $tools_todo_my_job[] = $r->tools_todo_my_job;
                    $experience = $r->experience;
                }
            }
            $ratings['over_all'] = $controller->ratingCalculationOverall(count($overall), $overall);
            $ratings['on_board'] = $controller->ratingCalculationOverall(count($on_board), $on_board);
            $ratings['nurse_team_work'] = $controller->ratingCalculationOverall(count($nurse_team_work), $nurse_team_work);
            $ratings['leadership_support'] = $controller->ratingCalculationOverall(count($leadership_support), $leadership_support);
            $ratings['tools_todo_my_job'] = $controller->ratingCalculationOverall(count($tools_todo_my_job), $tools_todo_my_job);
            $ratings['experience'] = $experience;
            /* rating */
        }
        return view('inc.facility_rating_modal')->with(
            compact(['ratings', 'user', 'facility_id'])
        );
        // return response()->json($view);
    }

    public function updateFacilityRating(Request $request)
    {
        $user = Auth::user();

        $insert = [];
        $admin_id = (isset($user->id) && $user->id != "") ? $insert['nurse_id'] = $user->id : "";
        if ($user->role == "NURSE") {
            $admin_id = (isset($user->nurse->id) && $user->nurse->id != "") ? $insert['nurse_id'] = $user->nurse->id : "";
        }
        $facility_id = (isset($request->facility_id) && $request->facility_id != "") ? $insert['facility_id'] = $request->facility_id : "";

        (isset($request->rating)  && is_numeric($request->rating) && $request->rating != "0") ? $insert['overall'] = $request->rating : "";
        (isset($request->onboarding)  && is_numeric($request->onboarding) && $request->onboarding != "0") ? $insert['on_board'] = $request->onboarding : "";
        (isset($request->nurse_team_work)  && is_numeric($request->nurse_team_work) && $request->nurse_team_work != "0") ? $insert['nurse_team_work'] = $request->nurse_team_work : "";
        (isset($request->leadership_support)  && is_numeric($request->leadership_support) && $request->leadership_support != "0") ? $insert['leadership_support'] = $request->leadership_support : "";
        (isset($request->tools_todo_my_job)  && is_numeric($request->tools_todo_my_job) && $request->tools_todo_my_job != "0") ? $insert['tools_todo_my_job'] = $request->tools_todo_my_job : "";
        (isset($request->experience) && $request->experience != "") ? $insert['experience'] = $request->experience : "";

        $facility_rating_where = ['facility_id' => $facility_id, 'nurse_id' => $admin_id, 'is_deleted' => '0'];
        $rating_info = FacilityRating::where($facility_rating_where);
        if ($rating_info->count() == 0) {
            $rating = FacilityRating::create($insert);
        } else {
            $rating = FacilityRating::where($facility_rating_where)->update($insert);
        }

        if ($rating) {
            $return_data = ['status' => 'success', 'message' => 'Rating updated successfully'];
        } else {
            $return_data = ['status' => 'fail', 'message' => 'Failed to update rating, Please try again later'];
        }

        return response()->json($return_data);
    }

    public function getAdminNurseRating(Request $request)
    {
        $user = Auth::user();
        $controller = new Controller();

        $admin_id = (isset($user->id) && $user->id != "") ? $user->id : "";
        $nurse_id = (isset($request->nurse_id) && $request->nurse_id != "") ? $request->nurse_id : "";
        $ratings = [];
        if ($nurse_id != "") {
            $ratings = $controller->adminNurseRating($job_id = "00000000-0000-0000-0000-000000000000", $nurse_id);
        }
        return view('inc.nurse_rating_modal')->with(
            compact(['ratings', 'user', 'nurse_id'])
        );
        // return response()->json($view);
    }

    public function updateNurseRating(Request $request)
    {
        $user = Auth::user();

        $insert = [];
        // $admin_id = (isset($user->id) && $user->id != "") ?  $user->id : "";
        $nurse_id = (isset($request->nurse_id) && $request->nurse_id != "") ? $insert['nurse_id'] = $request->nurse_id : "";
        $job_id = $insert['job_id'] = "00000000-0000-0000-0000-000000000000";

        (isset($request->rating)  && is_numeric($request->rating) && $request->rating != "0") ? $insert['overall'] = $request->rating : "";
        (isset($request->clinical_skills)  && is_numeric($request->clinical_skills) && $request->clinical_skills != "0") ? $insert['clinical_skills'] = $request->clinical_skills : "";
        (isset($request->nurse_team_work)  && is_numeric($request->nurse_team_work) && $request->nurse_team_work != "0") ? $insert['nurse_teamwork'] = $request->nurse_team_work : "";
        (isset($request->interpersonal_skills)  && is_numeric($request->interpersonal_skills) && $request->interpersonal_skills != "0") ? $insert['interpersonal_skills'] = $request->interpersonal_skills : "";
        (isset($request->work_ethic)  && is_numeric($request->work_ethic) && $request->work_ethic != "0") ? $insert['work_ethic'] = $request->work_ethic : "";
        (isset($request->experience) && $request->experience != "") ? $insert['experience'] = $request->experience : "";

        $nurse_rating_where = ['job_id' => $job_id, 'nurse_id' => $nurse_id, 'is_deleted' => '0'];
        $rating_info = NurseRating::where($nurse_rating_where);
        $overall = $clinical_skills = $nurse_teamwork = $interpersonal_skills = $work_ethic = $experience = [];
        if ($rating_info->count() == 0) {
            $rating = NurseRating::create($insert);
        } else {
            $rating = NurseRating::where($nurse_rating_where)->update($insert);
        }

        if ($rating) {
            $return_data = ['status' => 'success', 'message' => 'Rating updated successfully'];
        } else {
            $return_data = ['status' => 'fail', 'message' => 'Failed to update rating, Please try again later'];
        }

        return response()->json($return_data);
    }
}
