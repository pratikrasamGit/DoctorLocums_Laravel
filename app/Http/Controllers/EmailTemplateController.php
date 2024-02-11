<?php

namespace App\Http\Controllers;


use App\Models\EmailTemplate;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Database\QueryException;

class EmailTemplateController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:Administrator');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $email_template = EmailTemplate::get();
        return view('admin.mail-templates.index')->with(
            compact(['email_template'])
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\EmailTemplate  $emailTemplate
     * @return \Illuminate\Http\Response
     */
    public function show($template)
    {
        $content = "";

        $temp = EmailTemplate::where(['id' => $template]);
        if ($temp->count() > 0) {
            $t = $temp->first();
            if (isset($t->slug) && $t->slug != "") {
                if ($t->slug == "nurse_reset_password") {
                    $replace_array = ['###RESETLINK###' => "#"];
                    $content = strtr($t->content, $replace_array);
                } elseif ($t->slug == "new_registration") {
                    $replace_array = ['###USERNAME###' => "Elizabeth"];
                    $content = strtr($t->content, $replace_array);
                } elseif ($t->slug == "facility_make_offer") {
                    $replace_array = [
                        '###NURSENAME###' => 'Elizabeth',
                        '###FACILITYNAME###' => 'Testing Facility',
                        '###LOCATION###' => 'Houston, Texas',
                        '###SPECIALITY###' => 'Critical Care',
                        '###STARTDATE###' => 'DD/MM/YYYY',
                        '###DURATION###' => '12-hour',
                        '###SHIFT###' => 'Day',
                        '###WORKINGDAYS###' => 'Sunday, Monday, Tuesday',
                        '###REVIEWOFFER###' => '#',
                    ];
                    $content = strtr($t->content, $replace_array);
                } elseif ($t->slug == "accept_offer_nurse") {
                    $replace_array = [
                        '###NURSENAME###' => "Elizabeth",
                        '###FACILITYNAME###' => "Testing Facility",
                        '###FACILITYLOCATION###' => "Houston, Texas",
                        '###SPECIALITY###' => "Critical Care",
                        '###STARTDATE###' => "DD/MM/YYYY",
                        '###ASSIGNMENTDURATION###' => "12 Weeks",
                        '###SHIFTDURATION### ' => "12-hour",
                        '###PREFERREDSHIFT###' => "Days",
                    ];
                    $content = strtr($t->content, $replace_array);
                } elseif ($t->slug == "accept_offer_confirmation_facility") {
                    $replace_array = [
                        '###USERNAME###' => 'Facility User',
                        '###NURSENAME###' => "Elizabeth",
                        '###PREFERREDSPECIALITY###' => 'Critical Care',
                        '###FACILITYNAME###' => "Testing Facility",
                        '###FACILITYLOCATION###' => "Houston, Texas",
                        '###SPECIALITY###' => "Critical Care",
                        '###STARTDATE###' => "DD/MM/YYYY",
                        '###ASSIGNMENTDURATION###' => "12 Weeks",
                        '###SHIFTDURATION### ' => "12-hour",
                        '###PREFERREDSHIFT###' => "Days",
                        '###NURSEPROFILELINK###' => "Nurse profile link"
                    ];
                    $content = strtr($t->content, $replace_array);
                } elseif ($t->slug == "reject_offer_nurse") {
                    $replace_array = [
                        '###NURSENAME###' => "Elizabeth",
                        '###FACILITYNAME###' => "Testing Facility",
                    ];
                    $content = strtr($t->content, $replace_array);
                } elseif ($t->slug == "reject_offer_facility") {
                    $replace_array = [
                        '###USERNAME###' => "Facility User",
                        '###NURSENAME###' => "Elizabeth",
                        '###PREFERREDSPECIALITY###' => 'Critical Care',
                        '###FACILITYNAME###' => "Testing Facility",
                    ];
                    $content = strtr($t->content, $replace_array);
                } elseif ($t->slug == "admin_invite_change_password") {
                    $replace_array = ['###RESETLINK###' => "#"];
                    $content = strtr($t->content, $replace_array);
                } elseif ($t->slug == "new_nurses_notification_facility") {
                    $table_content = '<table class="table" border="1"> <thead> <tr> <th>S.NO</th> <th colspan="2">Name</th> <th>Specialty</th>  <th>State</th>  </tr> </thead> <tbody>';
                    $table_content .= '<tr> <td scope="row">1</td> <td colspan="2">Elizabeth</td> <td>Mother-Baby</td> <td>Texas</td> <td>View Profile</td> </tr>';
                    $table_content .= '</tbody> </table>';
                    $replace_array = ['###FACILITYNAME###' => "Testing Facility", '###NEWNURSES###' => $table_content];
                    $content = strtr($t->content, $replace_array);
                }
            }
        }

        return view('mail-templates.template')->with(
            compact(['content'])
        );
        // $this->basic_email($template = "", $data = [], $replace_array = []);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\EmailTemplate  $emailTemplate
     * @return \Illuminate\Http\Response
     */
    public function edit(EmailTemplate $template)
    {
        //
        $et = $template;
        return view('admin.mail-templates.edit')->with(
            compact(['et'])
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\EmailTemplate  $emailTemplate
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $template)
    {
        try {
            $update_id = (isset($template) && $template != "") ? $template : "";
            if ($update_id != "") {
                $e_tmp = EmailTemplate::where(['id' => $update_id])->get();
                if ($e_tmp->count() > 0) {
                    $temp = $e_tmp->first();
                    $update = EmailTemplate::where(['id' => $update_id])->update(['content' => $request->content, 'label' => $request->label]);
                    if ($update == true) {
                        return redirect('/admin/email-template')->with('success', 'Email template updated successfully');
                    } else {
                        return redirect('/admin/email-template')->with('fail', 'Failed to update email template, Please try again later');
                    }
                }
            }
        } catch (QueryException $ex) {
            // return ['success' => false, 'error' => $ex->getMessage()];
            return redirect('/admin/email-template')->with('fail', $ex->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\EmailTemplate  $emailTemplate
     * @return \Illuminate\Http\Response
     */
    public function destroy(EmailTemplate $emailTemplate)
    {
        //
    }
}
