<?php

use Bones\Router;
use Controllers\ProductController;



Router::bunch('/api', ['as' => 'api.'], function() {
	Router::get('/sync-product', [ ProductController::class, 'syncProduct' ])->name('sync-product');
});
