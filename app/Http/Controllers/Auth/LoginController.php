<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Notifications\TwoFactorCode;
use Illuminate\Contracts\Auth\Authenticatable;
use Carbon\Carbon;
use App\PasswordSecurity;
use Illuminate\Support\Facades\Validator;


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

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    //protected $redirectTo = RouteServiceProvider::HOME;
    protected function redirectTo()
    {

    if(auth()->user()->hasRole('admin'))

    {
            return '/users';
    }
    else{
        return '/home';
    }
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

    
    protected function validateLogin(Request $request)
    {
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
                'content' => http_build_query($data)
                ]
            ];

        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        $resultJson = json_decode($result);

        if ($resultJson->success != true) {
            return back()->withErrors(['captcha' => 'ReCaptcha Error']);
        }
        
        if ($resultJson->score >= 0.3) {
        //Validation was successful, add your form submission logic here
                $this->validate($request, [
                $this->username() => 'required',
                'password' => 'required',
                //'g-recaptcha-response' => 'required|captcha',
                ]);
        } else {
            return back()->with('ReCaptcha Error');
        }
    }
    
    protected function authenticated(Request $request, $user)
    {   
        if ($user && $user->isDisabled() == true)
        {
            auth()->guard()->logout();
            return redirect('/login')->with('error','This account has been disabled, please contact the administrator.');
        }

        //if password expiration is already set
        if (PasswordSecurity::where('user_id', $user->id)->exists()) {

            $request->session()->forget('password_expired_id');
            
            $password_updated_at = $user->passwordSecurity->password_updated_at;
            $password_expiry_days = $user->passwordSecurity->password_expiry_days;
            $password_expiry_at = Carbon::parse($password_updated_at)->addDays($password_expiry_days);


            if($password_expiry_at->lessThan(Carbon::now()))
            {
                $request->session()->put('password_expired_id',$user->id);
                auth()->logout();

                return redirect('/passwordExpiration')->with('message', "Your Password has expired, You need to change your password.");
            }           
        }

        //if password expiration does not exist
        else {
            $passwordSecurity = PasswordSecurity::create([
                'user_id' => $user->id,
                'password_expiry_days' => 120,
                'password_updated_at' => Carbon::now(),
            ]);
        }


        //log user login
        $userIP = $this->getUserIP();
        
        activity()
        ->event('Login')
        ->withProperties(['ip_address' => $userIP])
        ->log('A user has logged in');

        return redirect()->intended($this->redirectPath());




    }
    
    function getUserIP() {
        if( array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER) && !empty($_SERVER['HTTP_X_FORWARDED_FOR']) ) {
            if (strpos($_SERVER['HTTP_X_FORWARDED_FOR'], ',')>0) {
                $addr = explode(",",$_SERVER['HTTP_X_FORWARDED_FOR']);
                return trim($addr[0]);
            } else {
                return $_SERVER['HTTP_X_FORWARDED_FOR'];
            }
        }
        else {
            return $_SERVER['REMOTE_ADDR'];
        }
    }

    
    
}
