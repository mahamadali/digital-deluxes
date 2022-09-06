<?php

namespace Models;

use Models\Base\Model;

class CustomerBillingInfo extends Model
{
    protected $table = 'customer_billing_infos';

    protected $with = ['country_info'];

    public function country_info() {
        return $this->parallelTo(Country::class, 'country');
    }

}