<?php

use Bones\Router;
use Controllers\WelcomeController;
use Controllers\Frontend\StoreController;
use Controllers\Frontend\CartController;

Router::get('/', [WelcomeController::class, 'index'])->name('home')->barrier('is-front-auth');

Router::bunch('/', ['as' => 'frontend.', 'barrier' => ['is-front-auth']], function () {
  Router::get('/home', [WelcomeController::class, 'index'])->name('home');

  Router::bunch('/profile', ['as' => 'profile.'], function () {
    Router::get('/', [WelcomeController::class, 'Profile'])->name('index');
    Router::post('/update', [WelcomeController::class, 'update'])->name('update');
  });




  Router::bunch('/cart', ['as' => 'cart.'], function () {
    Router::get('/', [CartController::class, 'index'])->name('index');
    Router::get('/add/{product_id}', [CartController::class, 'addToCart'])->name('add');
  });


  Router::get('/wishlist', [WelcomeController::class, 'wishlist'])->name('wishlist');

  Router::get('/site-logout', [WelcomeController::class, 'frontend_logout'])->name('logout');

  Router::bunch('/store', ['as' => 'store.'], function () {
    Router::get('/', [StoreController::class, 'index'])->name('list');
    Router::get('/view/{product}', [StoreController::class, 'view'])->name('view');
    Router::post('/add-to-fav/{product}', [StoreController::class, 'addToFav'])->name('add-to-fav');
    Router::post('/remove-from-fav/{product}', [StoreController::class, 'removeFromFav'])->name('remove-from-fav');
  });
});
