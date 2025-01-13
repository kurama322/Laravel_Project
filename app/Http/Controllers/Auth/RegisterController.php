<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Enums\RoleEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserCreateRequest;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;


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
    protected $redirectTo = '/home';

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
    protected function validator(array $data): \Illuminate\Contracts\Validation\Validator
    {

        return Validator::make($data, app(UserCreateRequest::class)->rules());
    }

    /**
     * Create a new user instance after a valid registration.
     * @param array $data
     * @return  \App\Models\User
     *
     * @throws ValidationException
     */

    protected function create(array $data)
    {

        $user = User::create(
            $this->validator($data)->validated()
        );
            $user->assignRole(RoleEnum::CUSTOMER->value);

        return $user;
    }
}
