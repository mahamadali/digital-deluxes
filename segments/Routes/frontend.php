<?php

use Bones\Router;
use Controllers\WelcomeController;
use Controllers\Frontend\StoreController;
use Controllers\Frontend\CartController;
use Controllers\Frontend\PaymentController;
use Controllers\Frontend\OrderController;
use Controllers\Frontend\BlogController;
use Controllers\Frontend\WalletController;
use Controllers\SupportTicketController;
use Controllers\Frontend\CheckoutController;

Router::get('/', [WelcomeController::class, 'index'])->name('home');

Router::bunch('/', ['as' => 'frontend.', 'barrier' => ['is-front-auth']], function () {
  Router::get('/home', [WelcomeController::class, 'index'])->name('home')->withOutBarrier('is-front-auth');

  Router::bunch('/profile', ['as' => 'profile.'], function () {
    Router::get('/', [WelcomeController::class, 'Profile'])->name('index');
    Router::post('/update', [WelcomeController::class, 'update'])->name('update');
    Router::post('/updatepassword', [WelcomeController::class, 'updatepassword'])->name('updatepassword');
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
    Router::get('/', [StoreController::class, 'index'])->name('list')->withOutBarrier('is-front-auth');;
    Router::get('/view/{product}', [StoreController::class, 'view'])->name('view')->withOutBarrier('is-front-auth');;
    Router::post('/add-to-fav/{product}', [StoreController::class, 'addToFav'])->name('add-to-fav');
    Router::post('/remove-from-fav/{product}', [StoreController::class, 'removeFromFav'])->name('remove-from-fav');
  });

  Router::bunch('/checkout', ['as' => 'checkout.'], function () {
    Router::get('/', [CheckoutController::class, 'index'])->name('index');
    Router::post('/create-order', [CheckoutController::class, 'createOrder'])->name('createOrder');
  });

  Router::bunch('/payment', ['as' => 'payment.'], function () {
    Router::get('/', [PaymentController::class, 'index'])->name('store')->withOutBarrier('is-front-auth');
    Router::any('/check', [PaymentController::class, 'check'])->name('check')->withOutBarrier('is-front-auth');
    Router::get('/success', [PaymentController::class, 'success'])->name('success')->withOutBarrier('is-front-auth');
    Router::any('/notify', [PaymentController::class, 'notify'])->name('notify')->withOutBarrier('is-front-auth');
    Router::any('/kg_order_complete', [PaymentController::class, 'kg_order_complete'])->name('kg_order_complete')->withOutBarrier('is-front-auth');
    Router::any('/kg_order_status', [PaymentController::class, 'kg_order_status'])->name('kg_order_status')->withOutBarrier('is-front-auth');
    Router::bunch('/mercadopago', ['as' => 'mercadopago.'], function () {
      Router::any('/success', [PaymentController::class, 'mercadopago_success'])->name('success')->withOutBarrier('is-front-auth');
      Router::get('/failure/{payment_method}', [PaymentController::class, 'mercadopago_failure'])->name('failure')->withOutBarrier('is-front-auth');
      Router::get('/pending/{payment_method}', [PaymentController::class, 'mercadopago_pending'])->name('pending')->withOutBarrier('is-front-auth');
    });

    Router::bunch('/mercadopago-order', ['as' => 'mercadopago-order.'], function () {
      Router::any('/success', [PaymentController::class, 'mercadopago_order_success'])->name('success')->withOutBarrier('is-front-auth');
      Router::get('/failure', [PaymentController::class, 'mercadopago_order_failure'])->name('failure')->withOutBarrier('is-front-auth');
      Router::get('/pending', [PaymentController::class, 'mercadopago_order_pending'])->name('pending')->withOutBarrier('is-front-auth');
    });

    Router::bunch('/stripe', ['as' => 'stripe.'], function () {
      Router::any('/success/{payment_method}', [PaymentController::class, 'stripe_success'])->name('success')->withOutBarrier('is-front-auth');
      Router::get('/failure/{payment_method}', [PaymentController::class, 'stripe_failure'])->name('failure')->withOutBarrier('is-front-auth');
    });

    Router::bunch('/paypal-wallet', ['as' => 'paypal-wallet.'], function () {
      Router::any('/success', [PaymentController::class, 'paypal_wallet_success'])->name('success')->withOutBarrier('is-front-auth');
      Router::get('/cancel', [PaymentController::class, 'paypal_wallet_cancel'])->name('cancel')->withOutBarrier('is-front-auth');
    });

    Router::bunch('/paypal-order', ['as' => 'paypal-order.'], function () {
      Router::any('/success/{payment_method}/{order_reference}', [PaymentController::class, 'paypal_order_success'])->name('success')->withOutBarrier('is-front-auth');
      Router::get('/cancel/{payment_method}', [PaymentController::class, 'paypal_order_cancel'])->name('cancel')->withOutBarrier('is-front-auth');
    });


    Router::bunch('/coinbase-wallet', ['as' => 'coinbase-wallet.'], function () {
      Router::any('/notify', [PaymentController::class, 'coinbase_wallet_notify'])->name('notify')->withOutBarrier('is-front-auth');
      Router::any('/success/{payment_method}', [PaymentController::class, 'coinbase_wallet_success'])->name('success')->withOutBarrier('is-front-auth');
      Router::any('/cancel/{payment_method}', [PaymentController::class, 'coinbase_wallet_cancel'])->name('cancel')->withOutBarrier('is-front-auth');
    });

    Router::bunch('/coinbase-order', ['as' => 'coinbase-order.'], function () {
      Router::any('/success/{payment_method}', [PaymentController::class, 'coinbase_order_success'])->name('success')->withOutBarrier('is-front-auth');
      Router::any('/cancel/{payment_method}', [PaymentController::class, 'coinbase_order_cancel'])->name('cancel')->withOutBarrier('is-front-auth');
    });
    
  });

  Router::bunch('/support-tickets', ['as' => 'support-tickets.'], function () {
    Router::post('/create', [SupportTicketController::class, 'submit'])->name('submit');
    Router::get('/', [SupportTicketController::class, 'listing'])->name('listing');
		Router::bunch('/{ticket}', ['as' => ''], function () {
			Router::get('/view', [SupportTicketController::class, 'userView'])->name('view');
			Router::post('/message', [SupportTicketController::class, 'sendMessage'])->name('message');
			Router::get('/update-status/{status}', [SupportTicketController::class, 'updateStatus'])->name('update-status');
		});
  });

  Router::bunch('/wallet', ['as' => 'wallet.'], function () {
    Router::get('/', [WalletController::class, 'index'])->name('index');
    Router::bunch('/{payment_method}', ['as' => ''], function () {
      Router::get('/recharge', [WalletController::class, 'recharge'])->name('recharge');
      Router::post('/recharge-post', [WalletController::class, 'rechargePost'])->name('recharge.submit');
      Router::get('/recharge-success', [WalletController::class, 'rechargeSuccess'])->name('recharge.success');
    });
  });

});


Router::bunch('/blogs', ['as' => 'blogs.'], function () {
  Router::get('/', [BlogController::class, 'index'])->name('index');
  Router::bunch('/{blog}', ['as' => ''], function () {
    Router::get('/view', [BlogController::class, 'view'])->name('view');
  });
});
Router::post('/update-screen-mode', [WelcomeController::class, 'updateScreenMode'])->name('update-screen-mode');
Router::get('/{cms}', [WelcomeController::class, 'cmspage'])->name('cmspage');