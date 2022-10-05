@extends('app')

@block("title") {{ setting('app.title', 'Ali Rocks!') }} @endblock
@block("content")
<main class="page-main">
    <div class="uk-grid" data-uk-grid>
        <div class="uk-width">
            <div class="widjet --filters">
                <div class="widjet__head">
                    <h3 class="uk-text-lead">{{ trans('menu.support_tickets') }}</h3>
                    <a href="#modal-report" class="uk-button uk-button-danger" data-uk-toggle><i class="ico_report"></i> <span>{{ trans('support.report') }}</span></a>
                </div>
            </div>
            <div class="game-card__box" style="overflow-x:auto;">
            <table class="uk-table">
                <thead>
                    <tr>
                    <td>{{ trans('support.ticket') }} #</td>
                    <td>{{ trans('support.title') }} #</td>
                    <td>{{ trans('orders.status') }}</td>
                    <td>{{ trans('support.is_read') }}?</td>
                    <td>{{ trans('orders.created_at') }}</td>
                    <td>{{ trans('orders.action') }}</td>
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
                        <a href="{{ route('frontend.support-tickets.view', ['ticket' => $ticket->id]) }}" class="uk-button uk-button-danger uk-button-sm">{{ trans('orders.view') }}</a>
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

@block('scripts')
<script>
    $("#support-ticket-form").validate({
        rules: {
            title: {
                required: true
            },
            order_number: {
                required: true
            },
            details: {
                required: true
            },
        },
        submitHandler: function(form) {
            $(form).find('button[type="submit"]').html('<i class="fa fa-spinner fa-spin"></i>Processing...');
            $(form).find('button[type="submit"]').prop('disabled', true);
            formData = new FormData(form),
            $.ajax({
            url : $(form).attr('action'),
            type : 'POST',
            data : formData,
            dataType: 'json',
            contentType: false, processData: false,
            success: function(response) {

                $(form).find('button[type="submit"]').html('Submit');
                $(form).find('button[type="submit"]').prop('disabled', false);

                $('#messages').html('');
                if(response.status == 304) {
                    response.errors.forEach(error => {
                        $('#messages').append('<p align="center" style="color:red;">'+error+'</p>');
                    });
                }

                if(response.status == 200) {
                    $('#messages').append('<p align="center" style="color:green;">'+response.message+'</p>');
                    form.reset();
                }
            },
            error: function() {
                $(form).find('button[type="submit"]').html('Submit');
                $(form).find('button[type="submit"]').prop('disabled', false);
            }
            });
            
            }
        });
</script>
@endblock