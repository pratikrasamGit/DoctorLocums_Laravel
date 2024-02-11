<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;
    protected $maxAttempts = 5; // Default is 5
    protected $decayMinutes = 5; // Default is 1

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    //protected $redirectTo = RouteServiceProvider::HOME;
    public function redirectTo()
    {
        $user = Auth::user();
        $lat = '';
        $lang = '';
        switch ($user->role) {
            case 'FACILITY':
            case 'FACILITYADMIN':
                if ($user->facilities()->first()) {
                    $lat = $user->facilities()->first()->f_lat;
                    $lang = $user->facilities()->first()->f_lang;
                }
                break;
            case 'NURSE':
                if ($user->nurse) {
                    $lat = $user->nurse->n_lat;
                    $lang = $user->nurse->n_lang;
                }
                break;
            default:
                $lat = '';
                $lang = '';
        }
        Session::put('lat', $lat);
        Session::put('lang', $lang);
        return RouteServiceProvider::HOME;
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request)
    {
        $this->validateLogin($request);
        $url = 'https://www.google.com/recaptcha/api/siteverify';
        $remoteip = $_SERVER['REMOTE_ADDR'];
        $data = [
            'secret' => config('services.recaptcha.secret'),
            'response' => $request->get('recaptcha'),
            'remoteip' => $remoteip
        ];
        $options = [
            'http' => [
                'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                'method' => 'POST',
                'action' => 'login',
                'content' => http_build_query($data)
            ]
        ];
        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        $resultJson = json_decode($result);

        /* captcha restricted 15-dec-2021 */

        /* if ($resultJson->success != true) {
            return back()->withErrors(['captcha' => 'Human verification failed.']);
        }
        if ($resultJson->score >= 0.3 && $resultJson->action === 'login') {*/

        /* captcha restricted 15-dec-2021 */

        if (1 == 1) {
            //Validation was successful, add your form submission logic here
            // If the class is using the ThrottlesLogins trait, we can automatically throttle
            // the login attempts for this application. We'll key this by the username and
            // the IP address of the client making these requests into this application.
            if (
                method_exists($this, 'hasTooManyLoginAttempts') &&
                $this->hasTooManyLoginAttempts($request)
            ) {
                $this->fireLockoutEvent($request);

                return $this->sendLockoutResponse($request);
            }

            if (Auth::attempt(['email' => $request->email, 'password' => $request->password, 'active' => true])) {
                return $this->sendLoginResponse($request);
            }

            // If the login attempt was unsuccessful we will increment the number of attempts
            // to login and redirect the user back to the login form. Of course, when this
            // user surpasses their maximum number of attempts they will get locked out.
            $this->incrementLoginAttempts($request);

            return $this->sendFailedLoginResponse($request);
        } else {
            return back()->withErrors(['captcha' => 'Human verification failed.']);
        }
    }

    protected function sendFailedLoginResponse(Request $request)
    {
        throw ValidationException::withMessages([
            $this->username() => [trans('auth.failed')],
        ]);
    }

    public function logout(Request $request)
    {
        $lat = session()->get('lat');
        $lang = session()->get('lang');
        $this->guard()->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        session()->put('lat', $lat);
        session()->put('lang', $lang);

        if ($response = $this->loggedOut($request)) {
            return $response;
        }

        return $request->wantsJson()
            ? new JsonResponse('', 204)
            : redirect('/');
    }
}
