<?php

use Bones\Router;
use Bones\Request;
use Controllers\Backend\DashboardController;
use Controllers\Backend\UserController;
use Controllers\AuthController;
use Barriers\Admin\IsAuthenticated;
use Controllers\Backend\BlogController;
use Controllers\Backend\SettingController;
use Controllers\Backend\OrderController;
use Controllers\Backend\ProductController;
use Controllers\Backend\PaymentMethodController;
use Controllers\SupportTicketController;
use Controllers\Backend\CMSController;


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
		Router::bunch('/platform-logos', ['as' => 'platform-logos.'], function() {
			Router::get('/', [ SettingController::class, 'platformLogos' ])->name('index');
			Router::post('/platformLogoPost', [ SettingController::class, 'platformLogoPost' ])->name('post');
			Router::bunch('/{logo}', ['as' => ''], function() {
				Router::get('/remove', [ SettingController::class, 'platformLogoRemove' ])->name('remove');
			});
		});

		Router::bunch('/payment-methods', ['as' => 'payment-methods.'], function() {
			Router::get('/', [ PaymentMethodController::class, 'index' ])->name('index');
			Router::post('/change-status', [ PaymentMethodController::class, 'changeStatus' ])->name('change-status');
		});
	});

	Router::bunch('/blogs', ['as' => 'blogs.'], function () {
		Router::get('/', [BlogController::class, 'index'])->name('index');
		Router::get('/create', [BlogController::class, 'create'])->name('create');
		Router::post('/store', [BlogController::class, 'store'])->name('store');
		Router::bunch('/{blog}', ['as' => ''], function () {
			Router::get('/view', [BlogController::class, 'view'])->name('view');
			Router::get('/edit', [BlogController::class, 'edit'])->name('edit');
			Router::post('/update', [BlogController::class, 'update'])->name('update');
			Router::get('/delete', [BlogController::class, 'delete'])->name('delete');
		});
	});

	Router::bunch('/cms', ['as' => 'cms.'], function () {
		Router::get('/create', [CMSController::class, 'create'])->name('create');
		Router::post('/store/{page}', [CMSController::class, 'store'])->name('store');
	});

	Router::bunch('/support-tickets', ['as' => 'support-tickets.'], function () {
		Router::get('/', [SupportTicketController::class, 'index'])->name('index');
		Router::bunch('/{ticket}', ['as' => ''], function () {
			Router::get('/view', [SupportTicketController::class, 'view'])->name('view');
			Router::post('/message', [SupportTicketController::class, 'sendMessage'])->name('message');
			Router::get('/update-status/{status}', [SupportTicketController::class, 'updateStatus'])->name('update-status');
		});
	});
});

Router::get('/language/{lang}', function(Request $request, $lang) {
	session()->setLanguage($lang);
	return redirect()->back();
})->name('set-lang');

Router::get('/currency/{currency}', function(Request $request, $currency) {
	session()->setCurrency($currency);
	$base_price = currencyConverter('EUR', strtoupper($currency), 1);
	session()->set('base_price', $base_price);
	return redirect()->back();
})->name('set-currency');

Router::get('/logout', [ AuthController::class, 'logout' ])->name('auth.logout');