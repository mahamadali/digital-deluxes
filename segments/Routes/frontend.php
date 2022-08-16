<?php

use Bones\Router;
use Controllers\WelcomeController;
use Controllers\Frontend\StoreController;
use Controllers\Frontend\CartController;
use Controllers\Frontend\PaymentController;
use Controllers\Frontend\OrderController;

Router::get('/', [WelcomeController::class, 'index'])->name('home')->barrier('is-front-auth');

Router::bunch('/', ['as' => 'frontend.', 'barrier' => ['is-front-auth']], function () {
  Router::get('/home', [WelcomeController::class, 'index'])->name('home');

  Router::bunch('/profile', ['as' => 'profile.'], function () {
    Router::get('/', [WelcomeController::class, 'Profile'])->name('index');
    Router::post('/update', [WelcomeController::class, 'update'])->name('update');
  });

  Router::bunch('/orders', ['as' => 'orders.'], function () {
    Router::get('/', [OrderController::class, 'index'])->name('index');
    Router::bunch('/{order}', ['as' => ''], function () {
      Router::get('/view', [OrderController::class, 'view'])->name('view');
    });
  });


  Router::bunch('/cart', ['as' => 'cart.'], function () {
    Router::get('/', [CartController::class, 'index'])->name('index');
    Router::get('/add/{product_id}', [CartController::class, 'addToCart'])->name('add');
    Router::get('/remove/{cart_id}', [CartController::class, 'removeToCart'])->name('remove');
    Router::post('/update-qty/{product}', [CartController::class, 'updateQty'])->name('update-qty');
  });


  Router::get('/wishlist', [WelcomeController::class, 'wishlist'])->name('wishlist');

  Router::get('/site-logout', [WelcomeController::class, 'frontend_logout'])->name('logout');

  Router::bunch('/store', ['as' => 'store.'], function () {
    Router::get('/', [StoreController::class, 'index'])->name('list');
    Router::get('/view/{product}', [StoreController::class, 'view'])->name('view');
    Router::post('/add-to-fav/{product}', [StoreController::class, 'addToFav'])->name('add-to-fav');
    Router::post('/remove-from-fav/{product}', [StoreController::class, 'removeFromFav'])->name('remove-from-fav');
  });

  Router::bunch('/payment', ['as' => 'payment.'], function () {
    Router::get('/', [PaymentController::class, 'index'])->name('store');
    Router::any('/check', [PaymentController::class, 'check'])->name('check');
    Router::get('/success/{order}', [PaymentController::class, 'success'])->name('success');
    Router::any('/notify', [PaymentController::class, 'notify'])->name('notify');
    Router::any('/kg_order_complete', [PaymentController::class, 'kg_order_complete'])->name('kg_order_complete');
    Router::any('/kg_order_status', [PaymentController::class, 'kg_order_status'])->name('kg_order_status');
  });
});
