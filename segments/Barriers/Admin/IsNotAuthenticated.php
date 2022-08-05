<?php

namespace Barriers\Admin;

use Bones\Request;
use Bones\Session;

class IsNotAuthenticated
{
	public function check(Request $request)
	{
		if(Session::has('auth')) {
			return redirect()->to(route('admin.dashboard'))->go();
		}

		return true;
	}
}