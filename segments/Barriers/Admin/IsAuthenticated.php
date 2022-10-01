<?php

namespace Barriers\Admin;

use Bones\Request;
use Bones\Session;

class IsAuthenticated
{
	public function check(Request $request)
	{
		if(!empty(auth()->id)) {

			if(user()->role->name == 'user') {
				return redirect()->to(route('frontend.home'))->go();
			}
			return true;
		}
		
		return redirect()->to(route('auth.login'))->go();
	}
}