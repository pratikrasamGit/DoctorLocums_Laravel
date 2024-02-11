<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Facility;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Password;

class FacilitiesController extends Controller
{
    public function __construct()
    {
        // $this->middleware('permission:admin-create', ['only' => ['create', 'store']]);
        // $this->middleware('permission:admin-edit', ['only' => ['edit', 'update']]);
        // $this->middleware('permission:admin-show', ['only' => ['index']]);
        // $this->middleware('permission:admin-delete', ['only' => ['destroy']]);
        $this->middleware('auth');
        //$this->middleware('permission:admin-delete', ['only' => ['destroy']]);
        $this->middleware('role:Administrator|Admin');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $facilities = $this->facilitySelection()->paginate(10);
        return view('admin.facilities.index')->with(
            compact(['facilities'])
        );
    }

    public function search(Request $request)
    {
        $search_text = $request->search_text;
        $facilities = $this->facilitySelection($search_text)->paginate(10);
        return view('admin.facilities.index')->with(
            compact(['facilities'])
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $facility = new Facility();
        $facilityTypes = $this->getFacilityType()->pluck('title', 'id');
        $states = $this->getStateOptions();
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
        return view('admin.facilities.create')->with(
            compact([
                'facility', 'facilityTypes', 'states', 'eMedicalRecords',
                'bCheckProviders', 'nCredentialingSoftwares', 'nSchedulingSystems',
                'timeAttendanceSystems', 'traumaDesignations'
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
        $this->performValidation($request);
        $facility = new Facility($request->toArray());
        if (preg_match('/https?:\/\/(?:[\w]+\.)*youtube\.com\/watch\?v=[^&]+/', $request->video, $vresult)) {
            $youTubeID = $this->parse_youtube($request->video);
            $embedURL = 'https://www.youtube.com/embed/' . $youTubeID[1];
            $facility->__set('video_embed_url', $embedURL);
        } elseif (preg_match('/https?:\/\/(?:[\w]+\.)*vimeo\.com(?:[\/\w]*\/videos?)?\/([0-9]+)[^\s]*+/', $request->video, $vresult)) {
            $vimeoID = $this->parse_vimeo($request->video);
            $embedURL = 'https://player.vimeo.com/video/' . $vimeoID[1];
            $facility->__set('video_embed_url', $embedURL);
        }
        $facility->__set('created_by', Auth::user()->id);
        if ($request->hasFile('facility_logo')) {
            $facility_logo_name_full = $request->file('facility_logo')->getClientOriginalName();
            $facility_logo_name = pathinfo($facility_logo_name_full, PATHINFO_FILENAME);
            $facility_logo_ext = $request->file('facility_logo')->getClientOriginalExtension();
            $facility_logo = $facility_logo_name . '_' . time() . '.' . $facility_logo_ext;
            $facility->facility_logo = $facility_logo;
            //Upload Image
            $request->file('facility_logo')->storeAs('assets/facilities/facility_logo', $facility_logo);
        }
        if ($request->hasFile('cno_image')) {
            $cno_image_name_full = $request->file('cno_image')->getClientOriginalName();
            $cno_image_name = pathinfo($cno_image_name_full, PATHINFO_FILENAME);
            $cno_image_ext = $request->file('cno_image')->getClientOriginalExtension();
            $cno_image = $cno_image_name . '_' . time() . '.' . $cno_image_ext;
            $facility->cno_image = $cno_image;
            //Upload Image
            $request->file('cno_image')->storeAs('assets/facilities/cno_image', $cno_image);
        }

        /* Geolocation update */
        $latlang = $this->update_latlang($request->address, $request->city, $request->state, $request->postcode);
        if (isset($latlang) && count($latlang) > 0) {
            if (isset($latlang['lat']) && isset($latlang['lng'])) {
                $facility->__set('f_lat', $latlang['lat']);
                $facility->__set('f_lang', $latlang['lng']);
            }
        }
        /* Geolocation update */

        $facility->save();

        return redirect('/admin/facilities')->with('success', 'Facility Created');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Facility $facility
     * @return View
     */
    public function edit(Facility $facility)
    {
        $facilityTypes = $this->getFacilityType()->pluck('title', 'id');
        $states = $this->getStateOptions();
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
        return view('admin.facilities.edit')->with(
            compact([
                'facility', 'facilityTypes', 'states', 'eMedicalRecords',
                'bCheckProviders', 'nCredentialingSoftwares', 'nSchedulingSystems',
                'timeAttendanceSystems', 'traumaDesignations'
            ])
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Facility $facility
     * @param Request $request
     *
     * @throws ValidationException
     *
     * @return Redirect|RedirectResponse
     */
    public function update(Facility $facility, Request $request)
    {
        $this->performValidation($request);
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

        /* Geolocation lat and lon update */
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
        /* Geolocation lat and lon update */

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
        return redirect('/admin/facilities')->with('success', 'Facility Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Facility $facility
     *
     * @throws \Exception
     *
     * @return Redirect
     */
    public function destroy(Facility $facility)
    {
        $facility->delete();
        return redirect('/admin/facilities')->with('success', 'Facility Removed');
    }

    /**
     * Delete mapping between user and department
     *
     * @param User $user
     * @param Facility $facility
     *
     * @throws \Exception
     *
     * @return Redirect
     */
    public function detachUser(User $user, Facility $facility)
    {
        $facility->users()->detach($user->id);
        $user->delete();
        return redirect()->back()->with('success', 'User Removed');
    }

    public function reset_password(User $user)
    {
        $response = Password::broker()->sendResetLink(['email' => $user->email]);
        if ($response == Password::RESET_LINK_SENT) {
            return redirect('/admin/facilities')->with('success', 'Password rest email sent');
        } else {
            return redirect('/admin/facilities')->with('error', 'Password reset email failed');
        }
    }

    public function udateLatLong()
    {
        // $facilities = Facility::all();
        // foreach($facilities as $facility){
        //     $latlang = $this->update_latlang($facility->address,$facility->city,$facility->state,$facility->postcode); 
        //     if(isset($latlang) && count($latlang)>0){
        //         if(isset($latlang['lat']) && isset($latlang['lng'])){
        //             $facility->__set('f_lat', $latlang['lat']);
        //             $facility->__set('f_lang', $latlang['lng']);
        //             $facility->update(); 
        //         }
        //     }
        // }

    }

    private function performValidation($request)
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
}
