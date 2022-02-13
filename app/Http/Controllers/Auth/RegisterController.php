<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Validator;


class RegisterController extends Controller
{

    protected $status = 200;
    public function store()
    {
    }

    public function showRegistrationForm()
    {
        $departments = Department::available()->get();
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'username' => ['required', 'string', 'min:3', 'max:255', 'unique:users', 'alpha_dash'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:4'],
            'department_id' => ['required', 'integer', 'max:255'],
            'phone' => ['required'],
        ]);
    }

    protected function create(array $data)
    {
        $level = env('AUTHORIZATION_LEVEL', 1);
        return User::create([
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'phone_number' => $data['phone'],
            'department_id' => $data['department_id'],
            'authorization_level' => $level,
        ]);
    }

    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        event(new Registered($user = $this->create($request->only([
            'email', 'password', 'username', 'department_id', 'phone'
        ]))));


        Auth::login($user);

        //return auth()->user();


        $token = $user->createToken('authToken')->plainTextToken;

        if ($response = $this->registered($request, $user)) {
            return $response;
        }

        return new Response(['token_type' => 'bearer', 'token' => $token], 201);
    }

    /**
     * Get the guard to be used during registration.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard();
    }

    /**
     * The user has been registered.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function registered(Request $request, $user)
    {
        //
    }
}