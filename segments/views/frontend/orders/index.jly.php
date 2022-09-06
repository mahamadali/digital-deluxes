@extends('app')

@block("title") {{ setting('app.title', 'Ali Rocks!') }} @endblock
@block("content")
<main class="page-main">
    <div class="uk-grid" data-uk-grid>
        <div class="uk-width">
            <div class="widjet --filters">
                <div class="widjet__head">
                    <h3 class="uk-text-lead">{{ trans('orders.title') }}</h3>
                </div>
            </div>
            <div class="game-card__box">
            <table class="uk-table">
                <thead>
                    <tr>
                        <td>Order #</td>
                        <td>Transaction #</td>
                        <td>Amount</td>
                        <td>Status</td>
                        <td>Created At</td>
                        <td>Action</td>
                    </tr>
                </thead>
            <tbody>
            @foreach($orders as $order):
                <tr>
                    <td>{{ $order->id }}</td>
                    <td>{{ $order->transaction_id }}</td>
                    @if($order->currency == 'COP'):
                        <td>{{ $order->amount_in_cents/ 100 }} {{ $order->currency }}</td>
                    @else
                    <td>{{ $order->order_amount }} {{ $order->currency }}</td>
                    @endif
                    
                    <td>{{ $order->status }}</td>
                    <td>{{ date('M d, Y, H:i', strtotime($order->created_at)) }}</td>
                    <td>
                        <a href="{{ route('frontend.orders.view', ['order' => $order->id]) }}" class="uk-button uk-button-danger">View</a>
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