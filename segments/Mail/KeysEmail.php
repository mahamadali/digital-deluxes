<?php

namespace Mail;

use Contributors\Mail\Mailer;

class KeysEmail extends Mailer
{
    public $order;

    public function __construct($order)
    {
        $this->order = $order;
    }

    public function prepare()
    {
        return $this->html(content('mails/game-keys', ['order' => $this->order]))
                    ->to($this->order->user->email)
                    ->subject('Game Keys - ' . setting('app.title', 'Jolly Framework!'));
    }

}