<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Http\Request;
use App\Models\User;

class UpdateLastLoggedInAt
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Handle the event.
     *
     * @param  Login  $event
     * @return void
     */
    public function handle(Login $event)
    {
        if ($user = User::where('email', $this->request->email)->first()) {
            $user->setAttribute('last_login_at', new \DateTime());  
            $user->setAttribute('last_login_ip', $this->request->ip());  
            $user->update();  
        }
    }
}