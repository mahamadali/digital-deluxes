<?php

namespace Models;

use Models\Base\Model;
use Models\PaymentMethod;

class UserPaymentMethod extends Model
{
    protected $table = 'user_payment_method';

    public function payment_method_info(){
		return $this->parallelTo(PaymentMethod::class, 'payment_method_id');
	}

}