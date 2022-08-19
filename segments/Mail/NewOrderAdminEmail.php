<?php

namespace Mail;

use Contributors\Mail\Mailer;

class NewOrderAdminEmail extends Mailer
{
    public $order;

    public function __construct($order)
    {
        $this->order = $order;
    }

    public function prepare()
    {
        $admin = admin();
        if(!empty($admin)) {
            return $this->html(content('mails/new-order-admin-email', ['order' => $this->order]))
                    ->to($admin->value)
                    ->subject('Game Keys - ' . setting('app.title', 'Jolly Framework!'));
        }
    }

}