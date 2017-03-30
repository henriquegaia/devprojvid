<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Developer;
use App\Company;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\DB;

class RegisterController extends Controller {
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
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data) {
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

    protected function create(array $data) {

        $createdUser = User::create([
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'password' => bcrypt($data['password'])
        ]);

        if (!$createdUser) {
            return false;
        }

        $createdSubUser = $this->createSubUser($data);

        if (!$createdSubUser) {
            return false;
        }

        return $createdUser;
    }

    private function createSubUser(array $data) {

        $user = DB::table('users')
                ->where('email', '=', $data['email'])
                ->get();

        $userId = $user[0]->id;

        switch ($data['registerType']) {
            case 'optDeveloper':
                return $this->createDeveloper($data, $userId);
            case 'optCompany':
                return $this->createCompany($data, $userId);
            default:
                die('registerController createSubUser: error type');
        }
    }

    private function createDeveloper(array $data, $userId) {

        $developer = new Developer();

        $developer->user_id = $userId;

        return $developer->save();
    }

    private function createCompany(array $data, $userId) {

        $company = new Company();

        $company->user_id = $userId;

        return $company->save();
    }

}
