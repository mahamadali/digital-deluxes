<?php

namespace Barriers\User;

use Bones\Request;
use Bones\Session;

class IsFrontAuthenticated
{
	public function check(Request $request)
	{
		
		if(!empty(auth()->id)) {
			return true;
		}

		return redirect()->to(route('frontend.auth.login'))->go();
	}
}