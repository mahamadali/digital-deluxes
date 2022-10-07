<?php

namespace Controllers\Backend;

use Bones\Database;
use Bones\Request;
use Models\User;
use Models\City;
use Models\Order;
use Models\OrderItem;
use Models\PracticeArea;
use Models\Product;

class DashboardController
{
	public function index(Request $request)
	{
		$kinguin_balance = getKinguinBalance();
		
		$total_users = User::whereHas('role', function($query) {
			return $query->where('name', 'user');
		})->count();

		$products = Product::count();
		
		$orders = Order::count();

		$total_wallet_amount = User::sum('wallet_amount');

		$today_profit = OrderItem::whereDate('created_at', date("Y-m-d"))->sum('product_price_profit');

		$monthly_profit = OrderItem::whereMonth('created_at', 'MONTH(CURRENT_DATE())')->whereYear('created_at', 'YEAR(CURRENT_DATE()) ')->sum('product_price_profit');

		$total_profit = OrderItem::sum('product_price_profit');
		
		return render('backend/admin/dashboard', [
			'total_users' => $total_users,
			'kinguin_balance' => $kinguin_balance->balance,
			'products' => $products,
			'orders' => $orders,
			'total_wallet_amount' => $total_wallet_amount,
			'today_profit' => $today_profit,
			'monthly_profit' => $monthly_profit,
			'total_profit' => $total_profit
		]);
	}
}