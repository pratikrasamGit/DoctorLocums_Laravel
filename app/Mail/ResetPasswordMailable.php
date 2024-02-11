<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\EmailTemplate;

class ResetPasswordMailable extends Mailable
{
    use Queueable, SerializesModels;
    public $content;
    public $resetLink;
    public $user;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($resetLink, $user)
    {
        $this->content = "";
        $temp = EmailTemplate::where(['slug' => "nurse_reset_password"]);
        if ($temp->count() > 0) {
            $t = $temp->first();
            $replace_array = ['###RESETLINK###' => $this->resetLink];
            $this->content = strtr($t->content, $replace_array);

            $this->subject($t->label);
        }
        $this->resetLink = $resetLink;
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $ret = $this->markdown('mail-templates.template');

        return $ret;
    }
}
