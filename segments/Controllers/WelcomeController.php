<?php

namespace Controllers;

use Bones\Request;
use Bones\Session;

class WelcomeController
{
    public function index()
    {
        return render('frontend/home');
    }

    public function frontend_logout(Request $request) {
		Session::remove('auth');
		return redirect()->to(route('frontend.auth.login'))->go();
	}

    public function Profile(Request $request) {
        return render('frontend/profile');
	}
    
    
}