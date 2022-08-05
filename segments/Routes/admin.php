<?php

use Bones\Router;
use Controllers\Backend\DashboardController;
use Controllers\Backend\UserController;
use Controllers\AuthController;
use Barriers\Admin\IsAuthenticated;
use Controllers\Backend\SettingController;



Router::bunch('/admin', ['as' => 'admin.', 'barrier' => [IsAuthenticated::class]], function() {
	Router::get('/dashboard', [ DashboardController::class, 'index' ])->name('dashboard');
	Router::bunch('/users', ['as' => 'users.'], function() {
		Router::get('/list', [ UserController::class, 'index' ])->name('list');
		Router::get('/view/{user}', [ UserController::class, 'view' ])->name('view');
	});

	Router::bunch('/settings', ['as' => 'settings.'], function() {
		Router::get('/list', [ SettingController::class, 'index' ])->name('list');
		Router::post('/update', [ SettingController::class, 'update' ])->name('update');
	});
});

Router::get('/logout', [ AuthController::class, 'logout' ])->name('auth.logout');