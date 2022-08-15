<?php

namespace Controllers;

use Bones\Request;
use Bones\Session;
use Models\Product;
use Models\User;


class WelcomeController
{
    public function index()
    {
        $latest_products = Product::whereNotNull('coverImageOriginal')->orderBy('RAND()')->limit(3)->get();

        $tranding_products = Product::whereNotNull('coverImageOriginal')->orderBy('RAND()')->limit(3)->get();

        $popular_products = Product::whereNotNull('coverImageOriginal')->orderBy('RAND()')->limit(10)->get();
        

        return render('frontend/home', [
			'latest_products' => $latest_products,
            'tranding_products' => $tranding_products,
            'popular_products' => $popular_products
		]);
        
    }

    public function frontend_logout(Request $request) {
		Session::remove('auth');
		return redirect()->to(route('frontend.auth.login'))->go();
	}

    public function Profile(Request $request) {
        $user = User::where('id', auth()->id)->first();
        return render('frontend/profile', [
			'user' => $user
		]);
	}

    public function update(Request $request)
	{
		$validator = $request->validate([
			'first_name' => 'required|min:2|max:18',
			'last_name' => 'required|min:2|max:18',
            'password' => ['eqt:confirm_password'],
			'phone' => 'required|min:10|max:20',
		]);

		if ($validator->hasError()) {
            return redirect()->withFlashError(implode('<br>', $validator->errors()))->with('old', $request->all())->back();
        }

		$userData = $request->getOnly(['first_name', 'last_name',  'password','phone','profile_image']);

		$logoPath = null;
		if ($request->hasFile('profile_image')) {
			$logo = $request->files('profile_image');
			if (!$logo->isImage()) {
				return redirect()->withFlashError('Profile picture must be type of image')->with('old', $request->all())->back();
			} else {
				$uploadTo = 'assets/uploads/user-logos/';
				$uploadAs = 'user-logos-' . uniqid() . '.' . $logo->extension;
				if ($logo->save(pathWith($uploadTo), $uploadAs)) {
                    if(!empty($userData['profile_image']) && file_exists($userData['profile_image'])) {
                        unlink($userData['profile_image']);
                    }
					$logoPath = $uploadTo . $uploadAs;
                    $userData['profile_image'] = $logoPath;
				} else {
					return redirect()->withFlashError('Profile picture upload failed!')->with('old', $request->all())->back();
				}
			}
		}


		if (!empty($userData['password'])) {
			$userData['password'] = md5($userData['password']);
		} else {
			unset($userData['password']);
		}

    //    print_r($userData);
    //     exit;
		if (User::where('id', auth()->id)->update($userData)) {
			return redirect()->withFlashSuccess('Profile updated successfully!')->with('old', $request->all())->back();
		} else {
			return redirect()->withFlashError('Oops! Something went wrong!')->back();
		}

	}

	public function wishlist(Request $request) {
		return render('frontend/wishlist', [
			'wishlists' => user()->wishlists
		]);
	}

	public function orders(Request $request) {
		dd(user()->orders);
		return render('frontend/orders', [
			'orders' => user()->orders
		]);
	}
    
    
}