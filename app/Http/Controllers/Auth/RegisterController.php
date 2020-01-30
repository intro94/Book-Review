<?php

namespace App\Http\Controllers\Auth;

use App\EntryPoint;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

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
    protected $redirectTo = '/';

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
        // под такие вещи в ларе создают Request
        return Validator::make($data, [
            'last_name' => ['required', 'string', 'max:255'],
            'first_name' => ['required', 'string', 'max:255'],
            'login_name' => ['required', 'string', 'max:255', 'unique:entry_points'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\EntryPoint
     */
    protected function create(array $data)
    {
        $user = User::create([
            'type' => User::USER_TYPE,
            'email' => $data['email'],
            'last_name' => $data['last_name'],
            'first_name' => $data['first_name']
        ]);

        return EntryPoint::create([
            'user_id' => $user->id,
            'type' => EntryPoint::NATIVE_REG,
            'password' => Hash::make($data['password']),
            'login_name' => $data['login_name'],
        ]);
    }
}
