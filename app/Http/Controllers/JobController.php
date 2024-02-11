<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Job;
use App\Models\Offer;
use App\Models\JobAsset;
use App\Models\Nurse;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

class JobController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        //$this->middleware('permission:admin-delete', ['only' => ['destroy']]);
        $this->middleware('role:Administrator|Facility|Admin|FacilityAdmin');
        $this->middleware('access.job');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();

        $type = (isset($_GET['inactive']) && $_GET['inactive'] == "1") ? false : true;
        $whereCond = [
            'active' => $type,
        ];

        /* new update 06/dec/2021 */
        $ret = Job::where($whereCond)->orderBy('created_by', 'desc');
        /* new update 06/dec/2021 */
        if (Auth::user() && $user->hasRole(['Facility', 'FacilityAdmin'])) {
            if ($user->facilities()->first()) {
                $facility_id = $user->facilities()->first()->id;
            } else {
                $facility_id = null;
            }
            $ret->where('facility_id', $facility_id);
        }
        $jobs = $ret->paginate(10);
        if (Auth::user() && $user->hasRole(['Facility', 'FacilityAdmin'])) {
            return view('jobs.index')->with(
                compact(['jobs'])
            );
        }
        return view('admin.jobs.index')->with(
            compact(['jobs'])
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = Auth::user();
        $job = new Job();
        /* new update 06/dec/2021 */
        // $job->active = true;
        /* new update 06/dec/2021 */
        $specialities = $this->getSpecialities()->pluck('title', 'id');
        $assignmentDurations = $this->getAssignmentDurations()->pluck('title', 'id');
        $getPreferredShift = $this->getPreferredShift()->pluck('title', 'id');
        $shifts = $this->getShifts()->pluck('title', 'id');
        $geographicPreferences = $this->getGeographicPreferences()->pluck('title', 'id');
        $weekDays = $this->getWeekDayOptions();
        $seniorityLevels = $this->getSeniorityLevel()->pluck('title', 'id');
        $jobFunctions = $this->getJobFunction()->pluck('title', 'id');
        $ehrProficienciesExp = $this->getEHRProficiencyExp()->pluck('title', 'id');

        if (Auth::user() && $user->hasRole(['Facility', 'FacilityAdmin'])) {
            return view('jobs.create')->with(
                compact([
                    'job', 'specialities', 'assignmentDurations', 'shifts', 'geographicPreferences', 'weekDays',
                    'seniorityLevels', 'jobFunctions', 'ehrProficienciesExp', 'getPreferredShift'
                ])
            );
        }
        $facilities = $this->facilitySelection()->pluck('name', 'id');
        return view('admin.jobs.create')->with(
            compact([
                'job', 'specialities', 'assignmentDurations', 'shifts', 'geographicPreferences', 'weekDays',
                'facilities', 'seniorityLevels', 'jobFunctions', 'ehrProficienciesExp', 'getPreferredShift'
            ])
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
        $user = Auth::user();
        if (Auth::user() && $user->hasRole(['Administrator', 'Admin'])) {
            $this->validate($request, [
                'facility_id' => 'required'
            ]);
        }
        $this->performValidation($request);
        if ($request->input('preferred_days_of_the_week')) {
            $preferred_days_of_the_week = $request->input('preferred_days_of_the_week');
            $tmp = implode(',', $preferred_days_of_the_week);
            $request->merge(['preferred_days_of_the_week' => $tmp]);
        }
        $job = new Job($request->toArray());
        if (preg_match('/https?:\/\/(?:[\w]+\.)*youtube\.com\/watch\?v=[^&]+/', $request->job_video, $vresult)) {
            $youTubeID = $this->parse_youtube($request->job_video);
            $embedURL = 'https://www.youtube.com/embed/' . $youTubeID[1];
            $job->__set('video_embed_url', $embedURL);
        } elseif (preg_match('/https?:\/\/(?:[\w]+\.)*vimeo\.com(?:[\/\w]*\/videos?)?\/([0-9]+)[^\s]*+/', $request->job_video, $vresult)) {
            $vimeoID = $this->parse_vimeo($request->job_video);
            $embedURL = 'https://player.vimeo.com/video/' . $vimeoID[1];
            $job->__set('video_embed_url', $embedURL);
        }
        if (Auth::user() && $user->hasRole(['Facility', 'FacilityAdmin']) && $user->facilities()->first()) {
            $job->facility_id = $user->facilities()->first()->id;
        }
        $job->active = isset($job->active) && !!$job->active;
        $job->__set('created_by', $user->id);
        $job->save();
        if ($job_photos = $request->file('job_photos')) {
            foreach ($job_photos as $job_photo) {
                $job_photo_name_full = $job_photo->getClientOriginalName();
                $job_photo_name = pathinfo($job_photo_name_full, PATHINFO_FILENAME);
                $job_photo_ext = $job_photo->getClientOriginalExtension();
                $job_photo_finalname = $job_photo_name . '_' . time() . '.' . $job_photo_ext;
                //Upload Image
                $job_photo->storeAs('assets/jobs/' . $job->id, $job_photo_finalname);
                JobAsset::create([
                    'job_id' => $job->id,
                    'name' => $job_photo_finalname,
                    'filter' => 'job_photos'
                ]);
            }
        }
        if (Auth::user() && $user->hasRole(['Facility', 'FacilityAdmin'])) {
            return redirect('/jobs')->with('success', 'Job Post Created');
        }
        return redirect('/admin/jobs')->with('success', 'Job Post Created');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Job $job
     * @return \Illuminate\Http\Response
     */
    public function edit(Job $job)
    {
        $user = Auth::user();
        if ($user->can('update', $job)) {
            $specialities = $this->getSpecialities()->pluck('title', 'id');
            $assignmentDurations = $this->getAssignmentDurations()->pluck('title', 'id');
            $shifts = $this->getShifts()->pluck('title', 'id');
            $getPreferredShift = $this->getPreferredShift()->pluck('title', 'id');
            $geographicPreferences = $this->getGeographicPreferences()->pluck('title', 'id');
            $weekDays = $this->getWeekDayOptions();
            $seniorityLevels = $this->getSeniorityLevel()->pluck('title', 'id');
            $jobFunctions = $this->getJobFunction()->pluck('title', 'id');
            $ehrProficienciesExp = $this->getEHRProficiencyExp()->pluck('title', 'id');

            if (isset($job->preferred_days_of_the_week)) {
                $job->preferred_days_of_the_week = explode(',', $job->preferred_days_of_the_week);
            }
            if (Auth::user() && $user->hasRole(['Facility', 'FacilityAdmin'])) {
                return view('jobs.edit')->with(
                    compact([
                        'job', 'specialities', 'assignmentDurations', 'shifts', 'geographicPreferences', 'weekDays',
                        'seniorityLevels', 'jobFunctions', 'ehrProficienciesExp', 'getPreferredShift'
                    ])
                );
            }
            $facilities = $this->facilitySelection()->pluck('name', 'id');
            return view('admin.jobs.edit')->with(
                compact([
                    'job', 'specialities', 'assignmentDurations', 'shifts', 'geographicPreferences', 'weekDays', 'facilities',
                    'seniorityLevels', 'jobFunctions', 'ehrProficienciesExp', 'getPreferredShift'
                ])
            );
        } else {
            return redirect()->back()->with('success', 'You are not authorize.');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Job $job
     * @return \Illuminate\Http\Response
     */
    public function update(Job $job, Request $request)
    {
        $user = Auth::user();
        if ($user->can('update', $job)) {
            if (Auth::user() &&  $user->hasRole(['Administrator', 'Admin'])) {
                $this->validate($request, [
                    'facility_id' => 'required'
                ]);
            }
            $this->performValidation($request);
            if (preg_match('/https?:\/\/(?:[\w]+\.)*youtube\.com\/watch\?v=[^&]+/', $request->job_video, $vresult)) {
                $youTubeID = $this->parse_youtube($request->job_video);
                $embedURL = 'https://www.youtube.com/embed/' . $youTubeID[1];
                $job->__set('video_embed_url', $embedURL);
                $job->update();
            } elseif (preg_match('/https?:\/\/(?:[\w]+\.)*vimeo\.com(?:[\/\w]*\/videos?)?\/([0-9]+)[^\s]*+/', $request->job_video, $vresult)) {
                $vimeoID = $this->parse_vimeo($request->job_video);
                $embedURL = 'https://player.vimeo.com/video/' . $vimeoID[1];
                $job->__set('video_embed_url', $embedURL);
                $job->update();
            }
            if ($request->input('preferred_days_of_the_week')) {
                $preferred_days_of_the_week = $request->input('preferred_days_of_the_week');
                $tmp = implode(',', $preferred_days_of_the_week);
                $request->merge(['preferred_days_of_the_week' => $tmp]);
            }
            $params = $request->toArray();
            $params['active'] =
                isset($params['active']) && !!$params['active'];
            $job->update($params);
            if ($job_photos = $request->file('job_photos')) {
                foreach ($job_photos as $job_photo) {
                    $job_photo_name_full = $job_photo->getClientOriginalName();
                    $job_photo_name = pathinfo($job_photo_name_full, PATHINFO_FILENAME);
                    $job_photo_ext = $job_photo->getClientOriginalExtension();
                    $job_photo_finalname = $job_photo_name . '_' . time() . '.' . $job_photo_ext;
                    //Upload Image
                    $job_photo->storeAs('assets/jobs/' . $job->id, $job_photo_finalname);
                    JobAsset::create([
                        'job_id' => $job->id,
                        'name' => $job_photo_finalname,
                        'filter' => 'job_photos'
                    ]);
                }
            }
            if (Auth::user() &&  $user->hasRole(['Facility', 'FacilityAdmin'])) {
                return redirect('/jobs')->with('success', 'Job Post Updated');
            }
            return redirect('/admin/jobs')->with('success', 'Job Post Updated');
        } else {
            return redirect()->back()->with('success', 'You are not authorize.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Job $job
     * @return \Illuminate\Http\Response
     */
    public function destroy(Job $job)
    {
        $user = Auth::user();
        if ($user->can('delete', $job)) {
            $job->delete();
            return redirect('/admin/jobs')->with('success', 'Job Post Removed');
        } else {
            return redirect('/admin/jobs')->with('error', 'You are not authorize.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  JobAsset $jobAsset
     * @param  Job $job
     * @return \Illuminate\Http\Response
     */
    public function destroyDocument(Job $job, JobAsset $jobAsset)
    {
        $t = Storage::exists('assets/jobs/' . $job->id . '/' . $jobAsset->name);
        if ($t && $jobAsset->name) {
            Storage::delete('assets/jobs/' . $job->id . '/' . $jobAsset->name);
        }
        $jobAsset->delete();
        return redirect()->back()->with('success', 'Photo removed.');
    }

    public function apiJobsList(Nurse $nurse)
    {
        $user = Auth::user();
        $jobs = [];
        $ret = Job::where('active', true)
            ->orderBy('created_at', 'desc');
        if (Auth::user() && $user->hasRole(['Facility', 'FacilityAdmin'])) {
            if ($user->facilities()->first()) {
                $facility_id = $user->facilities()->first()->id;
            } else {
                $facility_id = null;
            }
            $ret->where('facility_id', $facility_id);
        }
        $ids = [];
        if (isset($nurse->offers) && count($nurse->offers) > 0) {
            $ids = $nurse->offers->whereNotNull('job_id')->pluck('id');
        }
        $ret->whereDoesntHave('offers', function (Builder $query) use ($ids) {
            $query->whereIn('id', $ids);
        });
        $temp = $ret->get();
        foreach ($temp as $job) {
            $jobs[$job->id] = $job->facility->name . ' - ' . \App\Providers\AppServiceProvider::keywordTitle($job->preferred_specialty);
        }
        return json_encode($jobs);
    }

    public function apiJobFacility(Job $job)
    {
        return response()->json([
            'name' => $job->facility->name,
            'location' => $job->facility->city . ', ' . $job->facility->state,
            'specialty' => $job->preferred_specialty ? \App\Providers\AppServiceProvider::keywordTitle($job->preferred_specialty) : 'N/A',
            'jobDetail' => [
                'startdate' => $job->created_at ? date("jS F Y", strtotime($job->created_at)) : 'N/A',
                'duration' => $job->preferred_assignment_duration ? \App\Providers\AppServiceProvider::keywordTitle($job->preferred_assignment_duration) : 'N/A',
                'shift' => $job->preferred_shift_duration ? \App\Providers\AppServiceProvider::keywordTitle($job->preferred_shift_duration) : 'N/A',
                'workdays' => $job->preferred_days_of_the_week ?: 'N/A',
            ]
        ]);
    }

    public function apiJobInvite(Job $job, Nurse $nurse)
    {
        $user = Auth::user();
        $offer = new Offer();
        $offer->nurse_id = $nurse->id;
        $offer->created_by = $user->id;
        $offer->job_id = $job->id;
        $offer->expiration = date("Y-m-d H:i:s", strtotime('+48 hours'));
        $offer->save();

        /* mail */
        $nurse_info = Nurse::where(['id' => $nurse->id]);
        if ($nurse_info->count() > 0) {
            $nurse = $nurse_info->first();
            $user_info = User::where(['id' => $nurse->user_id]);
            if ($user_info->count() > 0) {
                $nurse_user = $user_info->first(); // nurse user info
                $facility_user_info = User::where(['id' => $user->id]);
                if ($facility_user_info->count() > 0) {
                    $facility_user = $facility_user_info->first(); // facility user info
                    $data = [
                        'to_email' => $nurse_user->email,
                        'to_name' => $nurse_user->first_name . ' ' . $nurse_user->last_name
                    ];
                    $replace_array = [
                        '###NURSENAME###' => $nurse_user->first_name . ' ' . $nurse_user->last_name,
                        '###FACILITYNAME###' => $facility_user->facilities[0]->name,
                        '###LOCATION###' => $facility_user->facilities[0]->city . ',' . $facility_user->facilities[0]->state,
                        '###SPECIALITY###' => \App\Providers\AppServiceProvider::keywordTitle($offer->job->preferred_specialty),
                        '###STARTDATE###' => date('d F Y', strtotime($offer->job->start_date)),
                        '###DURATION###' => \App\Providers\AppServiceProvider::keywordTitle($offer->job->preferred_assignment_duration),
                        '###SHIFT###' => \App\Providers\AppServiceProvider::keywordTitle($offer->job->preferred_shift),
                        '###WORKINGDAYS###' => $offer->job->preferred_days_of_the_week,
                        '###REVIEWOFFER###' => url('browse-jobs/' . $offer->job->id),
                    ];
                    $this->basic_email($template = "facility_make_offer", $data, $replace_array);
                }
            }
        }
        /* mail */


        return response()->json([
            'success' => true,
        ]);
    }

    public function job_offers()
    {
        $whereCond = [
            'active' => true
        ];
        $offer = Offer::where($whereCond)
            ->orderBy('created_at', 'desc')->first();

        $offers = $offer->paginate(10);

        return view('jobs.offers.index')->with(
            compact(['offers'])
        );
    }

    private function performValidation($request)
    {
        $messages = [
            "job_photos.*.mimes" => "Photos should be image or png jpg",
            "job_photos.*.max" => "Photos should not be more than 5mb"
        ];

        $this->validate($request, [
            'preferred_specialty' => 'required',
            'preferred_assignment_duration' => 'required',
            'seniority_level' => 'required',
            'job_function' => 'required',
            'preferred_shift' => 'required',
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
        ], $messages);
    }
}
