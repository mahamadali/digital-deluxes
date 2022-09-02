<?php

namespace Models;

use Models\Base\Model;

class TransactionLog extends Model
{
	protected $table = 'transaction_logs';

    protected $with = ['payment_method'];

    public function payment_method() {
        return $this->parallelTo(PaymentMethod::class, 'payment_method_id');
    }

}