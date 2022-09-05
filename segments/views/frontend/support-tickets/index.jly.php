@extends('app')

@block("title") {{ setting('app.title', 'Ali Rocks!') }} @endblock
@block("content")
<main class="page-main">
    <div class="uk-grid" data-uk-grid>
        <div class="uk-width">
            <div class="widjet --filters">
                <div class="widjet__head">
                    <h3 class="uk-text-lead">{{ trans('menu.support_tickets') }}</h3>
                    <a href="#modal-report" class="uk-button uk-button-danger" data-uk-toggle><i class="ico_report"></i> <span>Report</span></a>
                </div>
            </div>
            <div class="game-card__box">
            <table class="uk-table">
                <thead>
                    <tr>
                    <td>Ticket #</td>
                    <td>Title #</td>
                    <td>Status</td>
                    <td>Is Read?</td>
                    <td>Created At</td>
                    <td>Action</td>
                    </tr>
                </thead>
            <tbody>
            @foreach($tickets as $ticket):
                <tr>
                    <td>{{ $ticket->id }}</td>
                    <td>{{ $ticket->title }}</td>
                    <td>{{ $ticket->status }}</td>
                    <td>{{ $ticket->is_read }}</td>
                    <td>{{ date('M d, Y, H:i', strtotime($ticket->created_at)) }}</td>
                    <td>
                        <a href="{{ route('frontend.support-tickets.view', ['ticket' => $ticket->id]) }}" class="uk-button uk-button-danger uk-button-sm">View</a>
                    </td>
                </tr>
            @endforeach
            </tbody>
            </table>
            </div>
        </div>
    </div>
</main>
@endblock