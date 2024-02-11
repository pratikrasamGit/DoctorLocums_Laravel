<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\EmailTemplate;

class RegistrationMailable extends Mailable
{
    use Queueable, SerializesModels;
    public $content;
    public $first_name;
    public $last_name;
    public $email;

    /**
     * Create a new message instance.
     *
     * @param $first_name - first_name of nurse
     * @param $last_name - last_name of nurse
     * @param $email - email id of nurse
     *
     * @return void
     */
    public function __construct($first_name, $last_name, $email)
    {
        /* $this->first_name = $first_name;
        $this->last_name = $last_name;
        $this->email = $email; */

        $this->content = "";
        $temp = EmailTemplate::where(['slug' => "new_registration"]);
        if ($temp->count() > 0) {
            $t = $temp->first();
            $replace_array = ['###USERNAME###' => $first_name . ' ' . $last_name];
            $this->content = strtr($t->content, $replace_array);

            $this->to($email);
            $this->subject($t->label);
        }
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('mail-templates.template');
    }
}
