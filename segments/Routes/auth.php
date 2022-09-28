<?php

use Bones\Router;
use Controllers\AuthController;
use Controllers\Frontend\AuthController as frontAuth;


Router::bunch('/auth', ['as' => 'auth.', 'barrier' => ['is-not-auth']], function() {
	Router::get('/login', [ AuthController::class, 'index' ])->name('login');
	Router::post('/check-login', [ AuthController::class, 'checkLogin' ])->name('check.login');
	Router::get('/sign-up', [ AuthController::class, 'signup' ])->name('sign-up');
	Router::post('/register', [ AuthController::class, 'register' ])->name('register');
	Router::get('/verify/{id}', [ AuthController::class, 'verify' ])->name('verify');
});


Router::bunch('/', ['as' => 'frontend.auth.', 'barrier' => ['is-front-not-auth']], function() {
	Router::get('/login', [ frontAuth::class, 'index' ])->name('login');
	Router::post('/check-login', [ frontAuth::class, 'checkLogin' ])->name('check.login');
	Router::get('/sign-up', [ frontAuth::class, 'signup' ])->name('sign-up');
	Router::post('/register', [ frontAuth::class, 'register' ])->name('register');
});

Router::bunch('/callback', ['as' => 'callback.'], function () {
	Router::get('/google', [frontAuth::class, 'index'])->name('googlecallback');
	Router::get('/facebook', [frontAuth::class, 'facebookcallback'])->name('facebookcallback');
});