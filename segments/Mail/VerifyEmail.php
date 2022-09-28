<?php

namespace Mail;

use Contributors\Mail\Mailer;

class VerifyEmail extends Mailer
{
    public $user;

    public function __construct($user)
    {
        $this->user = $user;
    }

    public function prepare()
    {
        return $this->html(content('mails/verify', ['user' => $this->user]))
                    ->to($this->user->email)
                    ->subject('Welcome to ' . setting('app.title', 'Jolly Framework!'));
    }

}