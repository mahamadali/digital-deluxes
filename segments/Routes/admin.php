<?php

use Bones\Router;
use Bones\Request;
use Controllers\Backend\DashboardController;
use Controllers\Backend\UserController;
use Controllers\AuthController;
use Barriers\Admin\IsAuthenticated;
use Controllers\Backend\SettingController;
use Controllers\Backend\OrderController;


Router::bunch('/admin', ['as' => 'admin.', 'barrier' => [IsAuthenticated::class]], function() {
	Router::get('/dashboard', [ DashboardController::class, 'index' ])->name('dashboard');
	Router::bunch('/users', ['as' => 'users.'], function() {
		Router::get('/list', [ UserController::class, 'index' ])->name('list');
		Router::get('/view/{user}', [ UserController::class, 'view' ])->name('view');
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
	});
});

Router::get('/language/{lang}', function(Request $request, $lang) {
	session()->setLanguage($lang);
});

Router::get('/logout', [ AuthController::class, 'logout' ])->name('auth.logout');