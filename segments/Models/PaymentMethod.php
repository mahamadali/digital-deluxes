<?php

namespace Models;

use Models\Base\Model;


class PaymentMethod extends Model
{
    protected $table = 'payment_methods';

    public function little_logos() {
        return $this->hasMany(PaymentLogo::class, 'payment_id');
    }

}