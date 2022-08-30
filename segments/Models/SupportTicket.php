<?php

namespace Models;

use Models\Base\Model;

class SupportTicket extends Model
{
	protected $table = 'support_tickets';

    protected $with = ['user', 'messages'];

    protected $attach = ['attachments_list'];

    public function user() {
        return $this->parallelTo(User::class, 'user_id');
    }

    public function getAttachmentsListProperty() {
        return explode(",", $this->attachments);
    }

    public function messages() {
        return $this->hasMany(TicketMessage::class, 'ticket_id');
    }

}