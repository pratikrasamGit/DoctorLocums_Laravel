<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Invite;
use Illuminate\Support\Str;
use App\Enums\Role;

class UsersController extends Controller
{
    public function __construct()
	{
		$this->middleware('auth');
        $this->middleware('permission:admin-delete', ['only' => ['destroy']]);
        $this->middleware('role:Administrator|Admin');
    }

    public function sendInvite(User $user)
    {
        if( $user->role === Role::getKey(Role::NURSE) || 
           $user->role === Role::getKey(Role::FACILITY) || 
           $user->role === Role::getKey(Role::FACILITYADMIN) ){
            $invite = Invite::updateOrCreate(
                [
                    'user_id' => $user->id,
                ],
                [
                    'token' => hash_hmac('sha256', Str::random(40).time(), config('app.key')),
                ]
            );
            $this->sendNotifyEmail($invite);
            return redirect()->back()->with('success', __('Invitation Sent.'));
            exit;
        }
    }  
}
