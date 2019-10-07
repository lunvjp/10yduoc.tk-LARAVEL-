<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Validator;
use Socialite;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    /**
     * Redirect the user to the GitHub authentication page.
     *
     * @return Response
     */
    public function loginFacebook(Request $request) {
        //Convert JSON data into PHP variable
        $userData = json_decode($request['userData']);

        $user = User::where([
            ['email', $userData->email],
            ['oauth_provider', $request['oauth_provider']],
        ])->first();

        if (!$user) {
            $user = new User;
            $user->email = $userData->email;
            $user->oauth_uid = $userData->id;
            $user->first_name = $userData->first_name;
            $user->last_name = $userData->last_name;
            $user->name = $userData->first_name.' '.$userData->last_name;
            $user->gender = $userData->gender;
            $user->locale = $userData->locale;
            $user->picture = $userData->picture->data->url;
            $user->link = $userData->link;
            $user->oauth_provider = $request['oauth_provider'];
            $user->save();
        }

        Auth()->login($user);
//        return redirect('/do-test');
    }

    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    /**
     * Obtain the user information from GitHub.
     *
     * @return Response
     */
    public function handleProviderCallback($provider)
    {
        try {
            $socialUser = Socialite::driver($provider)->user();
        } catch(\Exception $e) {
            return redirect('/');
        }

        // Kiểm tra tài khoản đó đã tồn tại trong bảng User hay chưa
//        $user = User::firstOrCreate(
//            ['email' => $socialUser->getEmail()],
//            ['oauth_provider' => 'facebook']
//        );
        $user = User::where([
            ['email', $socialUser->getEmail()],
            ['oauth_provider', $provider],
        ])->first();

        if (!$user) {
            $user = new User;
            $user->email = $socialUser->getEmail();
            $user->oauth_uid = $socialUser->getId();
            $user->name = $socialUser->getName();
            $user->picture = $socialUser->getAvatar();
            $user->oauth_provider = $provider;
            $user->save();
        }

        Auth()->login($user);
        return redirect('/do-test');
    }

    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/do-test';

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware($this->guestMiddleware(), ['except' => 'logout']);
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
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
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
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }
}
