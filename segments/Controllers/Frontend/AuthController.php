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
// Include required libraries
use Facebook\Facebook;
use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;

class AuthController
{
	public function index(Request $request)
	{

		// Call Facebook API
		$fb = new Facebook(array(
			'app_id' => setting('facebook.app_id'),
			'app_secret' => setting('facebook.app_secret'),
			'default_graph_version' => 'v3.2',
		));

		// Get redirect login helper
		$helper = $fb->getRedirectLoginHelper();

		// Get login url
		$permissions = ['email']; // Optional permissions
		$fb_loginURL = $helper->getLoginUrl(setting('facebook.redirect_url'), $permissions);


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
		return render('frontend/auth/login', ['google_login_url' =>  $google_login_url, 'fb_loginurl' => htmlspecialchars($fb_loginURL)]);
	}

	public function checkLogin(Request $request)
	{

		$validator = $request->validate([
            'email' => 'required',
            'password' => 'required',
			'g-recaptcha-response' => 'required'
        ],[
			'g-recaptcha-response.required' => trans('validation.recaptcha_required')
        ]);

		if ($validator->hasError()) {
			return redirect()->withFlashError(implode('<br>', $validator->errors()))->with('old', $request->all())->back();
		}

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
		
		if(empty($user)) {
			Session::remove('auth');
			return redirect()->to(route('frontend.auth.login'))->withFlashError('Your account has been removed!')->go();
		}

		if($user->status == 'Deactivate') {
			Session::remove('auth');
			return redirect()->to(route('frontend.auth.login'))->withFlashError('Your account has been suspended!')->go();
		}

		if(!session()->has('base_price') && empty(session()->get('base_price'))) {
			$usd_base = currencyConverter('EUR', 'USD', 1);
			$cop_base = currencyConverter('EUR', 'COP', 1);
			$eur_base = currencyConverter('EUR', 'EUR', 1);
			session()->set('base_price', $cop_base);
		}

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
            'email' => 'required|unique:users,email',
			'g-recaptcha-response' => 'required'
        ],[
            'email.unique' => 'Email must be unique',
			'g-recaptcha-response.required' => trans('validation.recaptcha_required')
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
		// $user->status = 'Deactivate';
		$user = $user->save();

		foreach($request->practice_areas as $key => $practice_area) {
			$user_practice_area = new UserPracticeArea();
			$user_practice_area->user_id = $user->id;
			$user_practice_area->practice_area_id = ($practice_area == 'other') ? NULL : $practice_area;
			$user_practice_area->other = $request->other_practice_area[$key];
			$user_practice_area->save();
		}

		Alert::as(new WelcomeEmail(User::where('id', $user->id)->with('city')->first()))->notify();
		// Alert::as(new VerifyEmail(User::where('id', $user->id)->with('city')->first()))->notify();
		

		return response()->json([
				'status' => 200,
				'message' => 'Registration success!'
				// 'message' => 'Please check your email and do verification process!'
			]);

	}

	

	public function facebookcallback() {

		// Call Facebook API
		$fb = new Facebook(array(
			'app_id' => setting('facebook.app_id'),
			'app_secret' => setting('facebook.app_secret'),
			'default_graph_version' => 'v3.2',
		));

		// Get redirect login helper
		$helper = $fb->getRedirectLoginHelper();

		$accessToken = $helper->getAccessToken();
			
		// OAuth 2.0 client handler helps to manage access tokens
		$oAuth2Client = $fb->getOAuth2Client();
		
		// Exchanges a short-lived access token for a long-lived one
		$longLivedAccessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
		
		
		// Set default access token to be used in script
		$fb->setDefaultAccessToken($longLivedAccessToken);
		
		
		// Getting user's profile info from Facebook
		try {
			$graphResponse = $fb->get('/me?fields=name,first_name,last_name,email,link,gender,picture');
			$fbUser = $graphResponse->getGraphUser();
		} catch(FacebookResponseException $e) {
			echo 'Graph returned an error: ' . $e->getMessage();
			session_destroy();
			// Redirect user back to app login page
			header("Location: ./");
			exit;
		} catch(FacebookSDKException $e) {
			echo 'Facebook SDK returned an error: ' . $e->getMessage();
			exit;
		}
		
		// Getting user's profile data
		$fbUserData = array();
		$fbUserData['oauth_uid']  = !empty($fbUser['id'])?$fbUser['id']:'';
		$fbUserData['first_name'] = !empty($fbUser['first_name'])?$fbUser['first_name']:'';
		$fbUserData['last_name']  = !empty($fbUser['last_name'])?$fbUser['last_name']:'';
		$fbUserData['email']      = !empty($fbUser['email'])?$fbUser['email']:'';
		$fbUserData['gender']     = !empty($fbUser['gender'])?$fbUser['gender']:'';
		$fbUserData['picture']    = !empty($fbUser['picture']['url'])?$fbUser['picture']['url']:'';
		$fbUserData['link']       = !empty($fbUser['link'])?$fbUser['link']:'';
		
		// Insert or update user data to the database
		$fbUserData['oauth_provider'] = 'facebook';
		
		$user = User::where('email', $fbUserData['email'])->first();
		
		if(empty($user)) {

			$img = '';
			if(!empty($fbUserData['picture'])) {
				// Remote image URL
				$url = $fbUserData['picture'];

				// Image path
				$img = 'assets/uploads/user-logos/'.time().'.png';

				// Save image 
				file_put_contents($img, file_get_contents($url));
			}

			$user = new User();
			$user->first_name = $fbUserData['first_name'];
			$user->last_name = $fbUserData['last_name'];
			$user->email = $fbUserData['email'];
			$user->profile_image = $img;
			$user->role_id = Role::where('name', 'user')->first()->id;
			$user = $user->save();
		}

		session()->setLanguage('en');
		Session::set('auth', $user);
		
		return $this->redirectAfterLogin($user);
	}

}