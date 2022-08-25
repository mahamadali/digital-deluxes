<?php

use Bones\Router;
use Controllers\ProductController;



Router::bunch('/api', ['as' => 'api.'], function() {
	Router::get('/sync-product', [ ProductController::class, 'syncProduct' ])->name('sync-product');
	Router::get('/sync-product-images', [ ProductController::class, 'syncProductImages' ])->name('sync-product-images');
	Router::any('/product-update', [ ProductController::class, 'productUpdate' ])->name('product-update');
	Router::get('/search-product', [ProductController::class, 'search'])->name('search-product');
	Router::get('/region-countries', [ProductController::class, 'regionCountries'])->name('region-countries');
});

