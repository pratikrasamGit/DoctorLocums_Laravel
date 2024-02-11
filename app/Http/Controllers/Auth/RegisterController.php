<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use App\Models\Nurse;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Enums\Role;
use App\Models\Availability;
use Illuminate\Support\Facades\Mail;
use App\Mail\RegistrationMailable;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;


class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'first_name' => ['required', 'regex:/^[a-zA-Z0-9]+$/', 'string', 'min:3', 'max:100'],
            'last_name' => ['required', 'regex:/^[a-zA-Z0-9]+$/', 'string', 'min:3', 'max:100'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'mobile' => ['required', 'regex:/^[0-9]+$/', 'min:10', 'max:15', 'unique:users'],
            'nursing_license_state' => ['required', 'min:2', 'max:15'],
            'nursing_license_number' => ['required', 'regex:/^[a-zA-Z0-9 ]+$/', 'min:5', 'max:20', 'unique:nurses'],
            'specialty' => ['required'],
            'work_location' => ['required'],
            'password' => ['required', 'string', 'min:6', 'max:255', 'confirmed', 'regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[\[\]\{\}\';:\.,#?!@$%^&*-]).{6,}$/'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        $user = User::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'mobile' => $data['mobile'],
            'email' => $data['email'],
            'user_name' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => Role::getKey(Role::NURSE),
        ]);        

        $nurse = Nurse::create([
            'user_id' => $user->id,
            'nursing_license_state' => $data['nursing_license_state'],
            'nursing_license_number' => $data['nursing_license_number'],
            'specialty' => $data['specialty'],
        ]);

        $availability = Availability::create([
            'nurse_id' => $nurse->id,
            'work_location' => $data['work_location'],
        ]);

        $user->assignRole('Nurse');
        $this->sendNotifyEmail($user);

        return $user;
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $this->validator($request->all())->validate();
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
                'action' => 'register',
                'content' => http_build_query($data)
                ]
            ];
        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        $resultJson = json_decode($result);
        if ($resultJson->success != true) {
                return back()->withErrors(['captcha' => 'Human verification failed.']);
                }
        if ($resultJson->score >= 0.3 && $resultJson->action === 'register') {
                //Validation was successful, add your form submission logic here
                $email = $request->email;
                if(!User::where('email',$email)->first()){ 
                event(new Registered($user = $this->create($request->all()))); 
                }       
                return redirect(route('login'))->with('success', 'Your Account is created please login..!');
        } else {
                return back()->withErrors(['captcha' => 'Human verification failed.']);
        }        
        //return $this->registered($request, $user)
          //              ?: redirect($this->redirectPath())->with('success', 'Your Account is created please login..!');
    }

    public function sendNotifyEmail($user)
	{
        Mail::send(
			new RegistrationMailable(
                $user->first_name,
                $user->last_name,
                $user->email
            )
		);
    }
}
