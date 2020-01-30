<?php

namespace App\Http\Controllers\Auth;

use App\EntryPoint;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

/**
 * Class SocialController
 * @package App\Http\Controllers\Auth
 */
class SocialController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Social Login Controller
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
     * @return string
     */
    public function username()
    {
        return 'login_name';
    }

    /**
     * Redirect the user to the GitHub authentication page.
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function github()
    {
        //хотелось бы самостоятельной реализации
        return Socialite::driver('github')->redirect();
    }

    /**
     * Redirect the user to the GitHub authentication page.
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function google()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Obtain the user information from GitHub.
     *
     * @return \Illuminate\Routing\Redirector
     */
    public function githubCallback()
    {
        try {
            $user = Socialite::driver('github')->user();
        } catch (\Exception $e) {
            return redirect(route('login.github'));
        }

        $auth_user = $this->findOrCreateUser($user, EntryPoint::GITHUB_REG);

        Auth::login($auth_user);

        return redirect($this->redirectTo);
    }

    /**
     * Obtain the user information from Google.
     *
     * @return \Illuminate\Routing\Redirector
     */
    public function googleCallback()
    {
        try {
            $user = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return redirect(route('login.google'));
        }

        $auth_user = $this->findOrCreateUser($user, EntryPoint::GOOGLE_REG);

        Auth::login($auth_user);

        return redirect($this->redirectTo);
    }

    /**
     * Return user if exists; create and return if doesn't
     *
     * @param $oauth_user
     * @param $oauth_type
     * @return EntryPoint
     */
    private function findOrCreateUser($oauth_user, $oauth_type)
    {
        if ($auth = EntryPoint::where('type', $oauth_type)->where('login_name', EntryPoint::$oauth_names[$oauth_type] . "_{$oauth_user->id}")->first()) {
            return $auth;
        }

        if (!$user = User::where('email', $oauth_user->email)->first()) {
            // есть firstOrCreate
            $user = User::create([
                'type' => User::USER_TYPE,
                'email' => $oauth_user->email
            ]);

            if ($oauth_type == EntryPoint::GOOGLE_REG) {
                $user->last_name = $oauth_user->user['family_name'] ? $oauth_user->user['family_name'] : '';
                $user->first_name = $oauth_user->user['given_name'] ? $oauth_user->user['given_name'] : '';
                $user->save();
            }
        }

        return EntryPoint::create([
            'user_id' => $user->id,
            'type' => $oauth_type,
            'login_name' => EntryPoint::$oauth_names[$oauth_type] . "_{$oauth_user->id}",
        ]);
    }
}
