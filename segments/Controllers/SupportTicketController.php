<?php

namespace Controllers;

use Bones\Request;
use Bones\Session;
use Models\Country;
use Models\Product;
use Models\SupportTicket;
use Models\TicketMessage;
use Models\User;


class SupportTicketController
{
    public function submit(Request $request)
	{
		$validator = $request->validate([
			'title' => 'required',
			'order_number' => 'required',
			'details' => 'required'
		]);

		if ($validator->hasError()) {
			return response()->json(['status' => 304, 'errors' => $validator->errors()]);
        }

		$userData = $request->getOnly(['title', 'order_number', 'details']);
        $ticket = new SupportTicket();
        $ticket->user_id = auth()->id;
        $ticket->title = $request->title;
        $ticket->order_number = $request->order_number;
        $ticket->details = $request->details;
        $attachments = [];
		if ($request->hasFile('attachments')) {
			foreach($request->files('attachments') as $key => $logo):
				$uploadTo = 'assets/uploads/support-tickets/';
                $uploadAs = 'T-' . uniqid() . '.' . $logo->extension;
                if ($logo->save(pathWith($uploadTo), $uploadAs)) {
                    $logoPath = $uploadTo . $uploadAs;
                    $attachments[] = $logoPath;
                }
			endforeach;
		}

        $ticket->attachments = implode(",", $attachments);
        $ticket = $ticket->save();

		return response()->json([
            'status' => 200,
            'message' => 'Ticket has been submitted! Your ticket id is #'.$ticket->id
        ]);

	}

    public function index() {
        $tickets = SupportTicket::get();
        $allTickets = SupportTicket::get();
        $pendingTickets = SupportTicket::where('status', 'PENDING')->get();
        $completedTickets = SupportTicket::where('status', 'COMPLETED')->get();
        $answeredTickets = 0;
        foreach($pendingTickets as $pendingTicket) {
            $ticketMessages = TicketMessage::where('ticket_id', $pendingTicket->id)->get();
            if(count($ticketMessages) > 0) {
                $answeredTickets++;
            }
        }

        foreach($completedTickets as $completedTicket) {
            $ticketMessages = TicketMessage::where('ticket_id', $completedTicket->id)->get();
            if(count($ticketMessages) > 0) {
                $answeredTickets++;
            }
        }

        return render('backend/admin/support-tickets/index', [
			'tickets' => $tickets,
            'pendingTickets' => count($pendingTickets),
            'completedTickets' => count($completedTickets),
            'answeredTickets' => $answeredTickets,
            'allTickets' => count($allTickets)
		]);
    }

    public function view(Request $request, SupportTicket $ticket) {
        
        $ticket->is_read = 'READ';
        $ticket->save();

        return render('backend/admin/support-tickets/view', [
			'ticket' => $ticket,
            'attachments' => $ticket->attachments_list
		]);
    }

    public function sendMessage(Request $request, SupportTicket $ticket) {
        $ticketMessage = new TicketMessage();
        $ticketMessage->message = $request->message;
        $ticketMessage->sender_id = auth()->id;
        $ticketMessage->receiver_id = $ticket->user->id;
        $ticketMessage->ticket_id = $ticket->id;
        $ticketMessage->save();
        return redirect()->withFlashSuccess('Message sent!')->back();
    }

    public function updateStatus(Request $request, SupportTicket $ticket, $status) {
        
        $ticket->status = $status;
        $ticket->save();

        return redirect()->withFlashSuccess('Ticket mark as '. $status)->back();
    }

    public function listing(Request $request) {
        $tickets = SupportTicket::where('user_id', auth()->id)->orderBy('id')->get();
        return render('frontend/support-tickets/index', [
			'tickets' => $tickets,
		]);
    }

    public function userView(Request $request, SupportTicket $ticket) {

        return render('frontend/support-tickets/view', [
			'ticket' => $ticket,
            'attachments' => $ticket->attachments_list
		]);
    }

	
}