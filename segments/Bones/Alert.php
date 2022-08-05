<?php

namespace Bones;

use Bones\Skeletons\Supporters\AutoMethodMap;
use Contributors\Mail\Mailer;
use Contributors\SMS\Texter;
use JollyException\AlertException;

class Alert extends AutoMethodMap
{
    protected $notifiable;

    public function __construct($notifiable = null)
    {
        $this->notifiable = $notifiable;
    }

    public function __as($notifiable)
    {
        $this->notifiable = $notifiable;
        return $this;
    }

    public function notify()
    {
        if (!empty($this->notifiable)) {
            if (is_subclass_of($this->notifiable, Mailer::class)) {
                return $this->notifiable->prepare()->send();
            } else if (is_subclass_of($this->notifiable, Texter::class)) {
                return $this->notifiable->prepare()->send();
            }
        }

        throw new AlertException('Empty notifiable entity passed');
    }

}