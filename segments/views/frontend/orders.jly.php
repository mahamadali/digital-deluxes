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
            @foreach($orders as $order):
                <table class="uk-table">
                    <thead>
                        <tr>
                            <td>Order #</td>
                            <td>Transaction #</td>
                            <td>Amount</td>
                            <td>Created At</td>
                            <td>Action</td>
                        </tr>
                    </thead
                </table>
            @endforeach
        </div>
    </div>
</main>
@endblock