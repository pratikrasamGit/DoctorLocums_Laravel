<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Auth\VerificationController;
use Illuminate\Http\Request;
use App\Models\Invite;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\URL;

class GuestController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function updatePass($token,$emailID)
    {
        $ret = Invite::where(['token' => $token])->first();
        $url = URL::temporarySignedRoute( 
            'update-pwd-post', now()->addMinutes(5), ['token' => $token, 'emailID' => $emailID]
        );
        if($ret = Invite::where(['token' => $token])->first()){
            $email = $ret->user->email;
            if($emailID === md5($email)){
                return view('auth.passwords.choose')->with(
                    compact(['token','email','emailID','url'])
                );
                exit;
            }
        }
        return redirect('/');
        exit;
    }
    public function updatePassPost(Request $request, $token, $emailID)
    {
        $ret = Invite::where(['token' => $token])->first();
        if(isset($ret)){
            $email = $ret->user->email;
            if($emailID === md5($email)){
                $this->validate($request, [
                    'password' => 'required|string|confirmed|min:8|max:20|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[\[\]\{\}\';:\.,#?!@$%^&*-]).{6,}$/'
                ]);
                $ret->user->password = Hash::make($request->password);
                $ret->user->active = true;
                if($ret->user->update()){
                    $ret->delete();
                }
                VerificationController::innerVerify($ret->user);
                return redirect(route('login'))->with('success', __('Your account was updated successfully. Please log in.'));
                exit;
            }
       }
       return redirect('/');
       exit;
    }
}
