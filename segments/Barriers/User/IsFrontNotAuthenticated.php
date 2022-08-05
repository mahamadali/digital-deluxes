<?php

namespace Barriers\User;

use Bones\Request;
use Bones\Session;

class IsFrontNotAuthenticated
{
	public function check(Request $request)
	{
		if(Session::has('auth')) {
			return redirect()->to(route('frontend.home'))->go();
		}

		return true;
	}
}