<?php

use Bones\Router;
use Controllers\WelcomeController;

Router::get('/', [ WelcomeController::class, 'index' ])->name('home')->barrier('is-front-auth');
Router::bunch('/', ['as' => 'frontend.', 'barrier' => ['is-front-auth']], function() {
	Router::get('/home', [ WelcomeController::class, 'index' ])->name('home');
    Router::get('/site-logout', [ WelcomeController::class, 'frontend_logout' ])->name('logout');
});