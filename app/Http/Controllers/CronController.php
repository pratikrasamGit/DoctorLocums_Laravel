<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\EmailTemplate;
use App\Models\Nurse;
use App\Models\User;

class CronController extends Controller
{
    public function notifyFacilityWeekly()
    {
        $temp = EmailTemplate::where(['slug' => 'new_nurses_notification_facility']);
        if ($temp->count() > 0) {
            $t = $temp->first();

            /* body */
            $table_content = '<table class="table" border="1"> <thead> <tr> <th>S.NO</th> <th colspan="2">Name</th> <th>Specialty</th>  <th>State</th>  </tr> </thead> <tbody>';
            $nurse = Nurse::whereRaw('created_at > NOW() - INTERVAL 7 DAY')->orderBy('created_at', 'ASC');
            if ($nurse->count() > 0) {
                $i = 1;
                foreach ($nurse->get() as $key => $n) {
                    $table_content .= '<tr>';
                    $table_content .= '<td scope="row">' . $i++ . '</td>';

                    $table_content .= '<td colspan="2">' . $n->user->first_name . '  ' . $n->user->last_name . '</td>';
                    $table_content .= '<td>' . \App\Providers\AppServiceProvider::keywordTitle($n->specialty) . '</td>';
                    $table_content .= '<td>' . $n->nursing_license_state . '</td>';
                    $table_content .= '<td><a href="' . url('browse-nurses/' . $n->slug) . '">view profile</a></td>';
                    $table_content .= '</tr>';
                }
            }
            $table_content .= '</tbody> </table>';
            /* body */

            /* recipient */
            $recipient = [];
            $facility = User::where(['active' => '1', 'role' => 'FACILITYADMIN']);
            $facility_name = "";
            if ($facility->count() > 0) {
                foreach ($facility->get() as $key => $f) {
                    if (isset($f->facilities()->first()->name) && $f->facilities()->first()->name != "") {
                        $facility_name = $f->facilities()->first()->name;
                    } else {
                        $facility_name = $f->first_name . ' ' . $f->last_name;
                    }
                    if ($f->email != "") {
                        $data = [
                            'to_email' => $f->email,
                            'to_name' => $facility_name
                        ];

                        // $data = ['to_email' => 'webodevmern@gmail.com', 'to_name' => 'Venkat'];
                        $replace_array = ['###FACILITYNAME###' => $facility_name, '###NEWNURSES###' => $table_content];
                        $this->basic_email($template = "new_nurses_notification_facility", $data, $replace_array);
                    }
                }
            }
            /* recipient */
        }
    }
}
