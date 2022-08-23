<?php

namespace Controllers\Backend;

use Bones\Database;
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

		$products = Database::rawQueryOne('SELECT count(id) as total_products FROM `products`')->total_products;
		
		$orders = Database::rawQueryOne('SELECT count(id) as total_orders FROM `orders`')->total_orders;

		return render('backend/admin/dashboard', [
			'total_users' => $total_users,
			'kinguin_balance' => $kinguin_balance->balance,
			'products' => $products,
			'orders' => $orders
		]);
	}
}