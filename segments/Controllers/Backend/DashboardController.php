<?php

namespace Controllers\Backend;

use Bones\Request;
use Models\User;
use Models\City;
use Models\Order;
use Models\PracticeArea;
use Models\Product;

class DashboardController
{
	public function index(Request $request)
	{
		$kinguin_balance = getKinguinBalance();
		
		$total_users = count(User::whereHas('role', function($query) {
			return $query->where('name', 'user');
		})->get());

		$products = count(Product::get());

		$orders = count(Order::get());

		return render('backend/admin/dashboard', [
			'total_users' => $total_users,
			'kinguin_balance' => $kinguin_balance->balance,
			'products' => $products,
			'orders' => $orders
		]);
	}
}