<?php

use Bones\Router;
use Bones\Request;
use Controllers\Backend\DashboardController;
use Controllers\Backend\UserController;
use Controllers\AuthController;
use Barriers\Admin\IsAuthenticated;
use Controllers\Backend\SettingController;
use Controllers\Backend\OrderController;
use Controllers\Backend\ProductController;


Router::bunch('/admin', ['as' => 'admin.', 'barrier' => [IsAuthenticated::class]], function() {
	Router::get('/dashboard', [ DashboardController::class, 'index' ])->name('dashboard');
	Router::bunch('/users', ['as' => 'users.'], function() {
		Router::get('/list', [ UserController::class, 'index' ])->name('list');
		Router::get('/view/{user}', [ UserController::class, 'view' ])->name('view');
		Router::get('/edit/{user}', [ UserController::class, 'edit' ])->name('edit');
		Router::post('/update/{user}', [ UserController::class, 'update' ])->name('update');
	});

	Router::bunch('/products', ['as' => 'products.'], function () {
		Router::get('/', [ProductController::class, 'index'])->name('index');
		Router::bunch('/{product}', ['as' => ''], function () {
			Router::get('/view', [ProductController::class, 'view'])->name('view');
		});
	});

	Router::bunch('/orders', ['as' => 'orders.'], function () {
		Router::get('/', [OrderController::class, 'index'])->name('index');
		Router::bunch('/{order}', ['as' => ''], function () {
			Router::get('/view', [OrderController::class, 'view'])->name('view');
		});
	});

	Router::bunch('/settings', ['as' => 'settings.'], function() {
		Router::get('/list', [ SettingController::class, 'index' ])->name('list');
		Router::post('/update', [ SettingController::class, 'update' ])->name('update');
		Router::get('/price-profits', [ SettingController::class, 'priceProfits' ])->name('price-profits');
		Router::post('/profits-update', [ SettingController::class, 'profitUpdate' ])->name('profit.update');
	});
});

Router::get('/language/{lang}', function(Request $request, $lang) {
	session()->setLanguage($lang);
	return redirect()->back();
})->name('set-lang');

Router::get('/logout', [ AuthController::class, 'logout' ])->name('auth.logout');