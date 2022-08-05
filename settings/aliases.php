<?php

use Barriers\VerifyToken;
use Barriers\Admin\IsAuthenticated;
use Barriers\Admin\IsNotAuthenticated;
use Barriers\User\IsFrontAuthenticated;
use Barriers\User\IsFrontNotAuthenticated;

return [

    // Add Barrier aliases to use as an alias
    'Barriers' => [
        'verify-token' => VerifyToken::class,
        'is-auth' => IsAuthenticated::class,
        'is-not-auth' => IsNotAuthenticated::class,
        'is-front-auth' => IsFrontAuthenticated::class,
        'is-front-not-auth' => IsFrontNotAuthenticated::class,
    ],

];