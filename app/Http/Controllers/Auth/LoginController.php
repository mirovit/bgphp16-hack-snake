<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Socialite;

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
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }

    /**
     * Redirect the user to the GitHub authentication page.
     *
     * @return Response
     */
    public function redirectToProvider($driver)
    {
        if(!in_array($driver, ['facebook'])) {
            return redirect()->route('app.waiting-room');
        }

        return Socialite::driver($driver)->redirect();
    }

    /**
     * Obtain the user information from GitHub.
     *
     * @return Response
     */
    public function handleProviderCallback($driver)
    {
        if(!in_array($driver, ['facebook'])) {
            return redirect()->route('app.waiting-room');
        }

        if($driver == 'facebook') {
            $fbUser = Socialite::driver($driver)->user();

            $user = User::where('email', $fbUser->email)->first();

            if (!$user) {
                $user = $this->create([
                    'name' => $fbUser->getName(),
                    'email' => $fbUser->getEmail(),
                ]);
            }

            \Auth::login($user);
        }

        return redirect()->route('app.waiting-room');
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        return User::create($data);
    }
}
