<?php

namespace Controllers\Backend;

use Bones\Request;
use Models\Role;
use Models\User;

class UserController
{
    public function index(Request $request)
	{
		$users = User::whereHas('role', function($query) {
			return $query->where('name', 'user');
		})->get();
		
		$totalUsers = count($users);
		
		return render('backend/admin/user/list', [
			'users' => $users,
			'totalUsers' => $totalUsers
		]);
	}

	

	public function view(Request $request, User $user)
	{
		return render('backend/admin/user/view', [
			'user' => $user
		]);
	}

	
	

}