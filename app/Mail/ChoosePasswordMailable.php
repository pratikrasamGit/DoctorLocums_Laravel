<?php

namespace App\Mail;

use App\Models\Invite;
use DateInterval;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ChoosePasswordMailable extends Mailable
{
    use Queueable, SerializesModels;
    public $chooseLink;

    public $valid_for;
    
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($chooseLink)
    {
        $this->chooseLink = $chooseLink;
        $this->valid_for = Invite::VALID_FOR_HOURS;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $ret = $this
			->subject(__('Choose Password for your account.'))
			->markdown('emails.choosepassword');

		return $ret;
    }
}
