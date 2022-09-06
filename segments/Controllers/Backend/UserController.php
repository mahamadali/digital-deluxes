<?php

namespace Controllers\Backend;

use Bones\Request;
use Models\Role;
use Models\User;
use Models\PaymentMethod;
use Models\UserPaymentMethod;


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

	public function edit(Request $request, User $user)
	{

		$payment_methods = PaymentMethod::where('status','ACTIVE')->get();

		$user_payment_methods = UserPaymentMethod::where('user_id',$user->id)->get();
		

		return render('backend/admin/user/edit', [
			'user' => $user,
			'payment_methods' => $payment_methods,
			'user_payment_methods' => $user_payment_methods,
		]);
	}

	public function update(Request $request, User $user)
	{
		$validator = $request->validate([
			'email' => 'required|unique:users,email,'.$user->id,
			'first_name' => 'required',
			'last_name' => 'required',
			'phone' => 'required',
			'country_code' => 'required',
			'age' => 'required',
			'address' => 'required',
		]);

		if ($validator->hasError()) {
			return redirect()->withFlashError(implode('<br>', $validator->errors()))->with('old', $request->all())->back();
		}

		$user->first_name = $request->first_name;
		$user->last_name = $request->last_name;
		$user->email = $request->email;
		$user->phone = $request->phone;
		$user->country_code = $request->country_code;
		$user->age = $request->age;
		$user->address = $request->address;
		$user->status = $request->status;

		if ($request->hasFile('profile_image')) {
            $file = $request->files('profile_image');
			$uploadTo = 'assets/uploads/user-logos/';
			$uploadAs = $user->id. '-' . uniqid() . '.' . $file->extension;
			if ($file->save(pathWith($uploadTo), $uploadAs)) {
				unlink($user->profile_image);
				$user->profile_image = $uploadTo . $uploadAs;
			}
		}

		$user->save();

		return redirect()->withFlashSuccess('User updated successfully')->back();
	}

	

	public function paymentMethodUpdate(Request $request, User $user)
	{

		$payment_methods = PaymentMethod::where('status','ACTIVE')->get();
		UserPaymentMethod::where('user_id',$user->id)->delete();

		foreach($payment_methods as $method){
			$method_id = "status_".$method->id;
			$status = $request->{$method_id} == 'Active' ? 1 : 0; 
			$user_payment_method = new UserPaymentMethod();
			$user_payment_method->payment_method_id = $method->id;
			$user_payment_method->user_id = $user->id;
			$user_payment_method->status = $status;
			$user_payment_method->save();
		}
		

		return redirect()->withFlashSuccess('Payment method updated successfully')->back();
	}
	

}