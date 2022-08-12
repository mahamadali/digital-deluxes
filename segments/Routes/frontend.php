<?php

use Bones\Router;
use Controllers\WelcomeController;
use Controllers\Frontend\StoreController;

Router::get('/', [ WelcomeController::class, 'index' ])->name('home')->barrier('is-front-auth');
Router::bunch('/', ['as' => 'frontend.', 'barrier' => ['is-front-auth']], function() {
	Router::get('/home', [ WelcomeController::class, 'index' ])->name('home');
   
    Router::bunch('/profile', ['as' => 'profile.'], function() {
      Router::get('/', [ WelcomeController::class, 'Profile' ])->name('index');
      Router::post('/update', [WelcomeController::class, 'update' ])->name('update');
    });
  
    Router::get('/site-logout', [ WelcomeController::class, 'frontend_logout' ])->name('logout');

    Router::bunch('/store', ['as' => 'store.'], function() {
		Router::get('/', [ StoreController::class, 'index' ])->name('list');
        Router::get('/view/{product}', [ StoreController::class, 'view' ])->name('view');
	});
});