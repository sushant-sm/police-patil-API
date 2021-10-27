<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\User;
use GuzzleHttp\Psr7\Request;
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
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'string', 'max:255'],
            'mobile' => 'nullable|numeric|digits:10',
            'address' => 'nullable|string',
            'joindate' => 'nullable',
            'enddate' => 'nullable',
            'psdistance' => 'nullable',
            'ordernumber' => 'nullable',
            'latitude' => 'nullable',
            'longitude' => 'nullable',
            'taluka' => 'nullable',
            'dangerzone' => 'nullable',
            'village' => 'nullable',
            'psid' => 'required'
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data, Request $request)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => $data['role'],
            'mobile' => $data['mobile'],
            'address' => $data['address'],
            'joindate' => $data['joindate'],
            'enddate' => $data['enddate'],
            'psdistance' => $data['psdistance'],
            'latitude' => $data['latitude'],
            'longitude' => $data['longitude'],
            'taluka' => $data['taluka'],
            'dangerzone' => $data['dangerzone'],
            'village' => $data['village'],
            'ordernumber' => $data['ordernumber'],
        ]);
    }
}
