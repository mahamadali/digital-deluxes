<?php

namespace Models;

use Models\Base\Model;

class TransactionLog extends Model
{
	protected $table = 'transaction_logs';

    protected $with = ['payment_method_info', 'user'];

    public function payment_method_info() {
        return $this->parallelTo(PaymentMethod::class, 'payment_method_id');
    }

    public function user() {
        return $this->parallelTo(User::class, 'user_id');
    }

}