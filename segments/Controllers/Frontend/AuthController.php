<?php

namespace Controllers\Frontend;

use Bones\Alert;
use Bones\Request;
use Bones\Session;
use Mail\WelcomeEmail;
use Jolly\Engine;
use Models\Role;
use Models\User;
use Google_Client;
use Google_Service_Oauth2;

class AuthController
{
	public function index(Request $request)
	{
		// init configuration
		$clientID = setting('google.client_id');
		$clientSecret = setting('google.client_secret');;
		$redirectUri = setting('google.redirect_url');
		
		// create Client Request to access Google API
		$client = new Google_Client();
		$client->setClientId($clientID);
		$client->setClientSecret($clientSecret);
		$client->setRedirectUri($redirectUri);
		$client->addScope("email");
		$client->addScope("profile");
		
		// authenticate code from Google OAuth Flow
		if (isset($_GET['code'])) {
		$token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
		$client->setAccessToken($token['access_token']);
		
		// get profile info
		$google_oauth = new Google_Service_Oauth2($client);
		$google_account_info = $google_oauth->userinfo->get();
		
		$email =  $google_account_info->email;
		$name =  $google_account_info->name;
		$nameExplode = explode(" ", $name);
		
		$user = User::where('email', $email)->first();
		if(empty($user)) {

			// Remote image URL
			$url = $google_account_info->picture;

			// Image path
			$img = 'assets/uploads/user-logos/'.time().'.png';

			// Save image 
			file_put_contents($img, file_get_contents($url));

			$user = new User();
			$user->first_name = $nameExplode[0] ?? '';
			$user->last_name = $nameExplode[1] ?? '';
			$user->email = $email;
			$user->profile_image = $img;
			$user->role_id = Role::where('name', 'user')->first()->id;
			$user = $user->save();
		}

		session()->setLanguage('en');
		Session::set('auth', $user);
		return $this->redirectAfterLogin($user);

		// now you can use this profile info to create account in your website and make user logged in.
		} else {
			$google_login_url = $client->createAuthUrl();
		}
		return render('frontend/auth/login', ['google_login_url' =>  $google_login_url]);
	}

	public function checkLogin(Request $request)
	{
		$email = $request->email;
		$password = $request->password;

		$user = User::where('email', $email)->where('password', md5($password))->with('role')->first();
		if (!empty($user) ) {
			session()->setLanguage('en');
			Session::set('auth', $user);
			return $this->redirectAfterLogin($user);
		} else {
			return redirect()->to(route('frontend.auth.login'))->withFlashError('Incorrect credentials!')->go();
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
			return redirect()->to(route('frontend.home'))->go();
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
		return render('frontend/auth/signup');
	}

	public function register(Request $request)
	{

		$validator = $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'law_firm' => 'required',
            'email' => 'required|unique:users,email'
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
		$user->city_id = $request->city_id;
		$user->law_firm = $request->law_firm;
		$user->role_id = Role::where('name', 'user')->first()->id;
		$user = $user->save();

		foreach($request->practice_areas as $key => $practice_area) {
			$user_practice_area = new UserPracticeArea();
			$user_practice_area->user_id = $user->id;
			$user_practice_area->practice_area_id = ($practice_area == 'other') ? NULL : $practice_area;
			$user_practice_area->other = $request->other_practice_area[$key];
			$user_practice_area->save();
		}

		Alert::as(new WelcomeEmail(User::where('id', $user->id)->with('city')->first()))->notify();

		return response()->json([
				'status' => 200,
				'message' => 'Registration success!'
			]);

	}

}