<?php

namespace Models;

use Models\Base\Model;


class Order extends Model
{
    protected $table = 'orders';
    
    protected $with = ['user', 'keys'];
    
    public function user() {
        return $this->parallelTo(User::class, 'user_id');
    }
    
    public function keys() {
        return $this->hasMany(GameKey::class, 'order_id');
    }

}