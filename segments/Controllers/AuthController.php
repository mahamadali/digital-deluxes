<?php

namespace Controllers;

use Bones\Alert;
use Bones\Request;
use Bones\Session;
use Mail\WelcomeEmail;
use Jolly\Engine;
use Models\Role;
use Models\User;

class AuthController
{
	public function index(Request $request)
	{
		return render('backend/auth/login');
	}

	public function checkLogin(Request $request)
	{
		$email = $request->email;
		$password = $request->password;

		$user = User::where('email', $email)->where('password', md5($password))->with('role')->first();
		if ( !empty($user) ) {
			Session::set('auth', $user);
			return $this->redirectAfterLogin($user);
		} else {
			return redirect()->to(route('auth.login'))->withFlashError('Incorrect credentials!')->go();
		}
	}

	public function autoLogin(Request $request, $email)
	{
		$auth = User::where('email', $email)->with('role')->first();
		session()->set('auth', $auth);

		return $this->redirectAfterLogin($auth);
	}

	public function redirectAfterLogin($user) {
		$role = $user->role->name ?? '';
		switch ($role) {
			case 'admin':
			return redirect()->to(route('admin.dashboard'))->go();
			break;
			case 'user':
			return redirect()->to(route('user.dashboard'))->go();
			break;
			default:
			return Engine::error([
				'error' => 'Unauthorised Access!'
			]);
			break;
		}
		
	}

	public function logout(Request $request) {
		Session::remove('auth');
		return redirect()->to(route('auth.login'))->go();
	}

	public function signup()
	{
		$practice_areas = PracticeArea::get();
		$cities = City::get();
		return render('auth/signup', [
			'practice_areas' => $practice_areas,
			 'cities' => $cities
		]);
	}

	public function register(Request $request)
	{

		$validator = $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'phone' => 'required',
            'email' => 'required|unique:users,email',
			'password' => 'required',
        ],[
            'email.unique' => 'Email must be unique'
        ]);

        if ($validator->hasError()) {
            return response()->json(['status' => 304, 'errors' => $validator->errors()]);
        }

		$user = new User();
		$user->first_name = $request->first_name;
		$user->last_name = $request->last_name;
		$user->email = $request->email;
		$user->phone = $request->phone;
		$user->password = md5($request->password);
		$user->role_id = Role::where('name', 'user')->first()->id;
		$user = $user->save();

		

		Alert::as(new WelcomeEmail(User::where('id', $user->id)->first()))->notify();

		return response()->json([
				'status' => 200,
				'message' => 'Registration success!'
			]);

	}

}