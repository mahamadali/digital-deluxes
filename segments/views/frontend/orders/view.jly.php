@extends('app')

@block("title") {{ setting('app.title', 'Ali Rocks!') }} @endblock

@block("styles")
<style>
    a:hover {
        color: #000;
    } 

    .order-info-label {
        color: #7b7b7b;
        font-size: 15px;
        font-style: normal;
        font-weight: 500;
        line-height: 0.31;
    }
</style>
@endblock

@block("content")
<main class="page-main">
    <div class="uk-grid" data-uk-grid>
        <div class="uk-width">
            <div class="widjet --filters">
                <div class="widjet__head">
                    <h3 class="uk-text-lead">{{ trans('orders.title') }} #{{ $order->id }}</h3>
                </div>
            </div>

            <div class="game-card --horizontal favourites-game">
                <div class="game-card__box">
                    <div class="game-card__info" style="width: 100%;">
                        <a class="game-card__title" href="#">Order Info</a>
                         
                        <div class="game-card__bottom">
                            <div class="game-card__rating-and-price">
                                <div class="order-info-label">Payment Method: <span>{{ $order->payment_method_type }} </span></div>
                            </div>
                            <div class="game-card__rating-and-price">
                                <div class="order-info-label">Transaction ID: <span>{{ $order->transaction_id }} </span></div>
                            </div>
                        </div>
                        <div class="game-card__bottom">
                            <div class="game-card__rating-and-price">
                                <div class="order-info-label">Status: <span>{{ $order->status }} </span></div>
                            </div>
                            <div class="game-card__rating-and-price">
                                <div class="order-info-label">Currency: <span>{{ $order->currency }} </span></div>
                            </div>
                        </div>
                        <div class="game-card__bottom">
                            <div class="game-card__rating-and-price">
                                <div class="order-info-label">Total: <span>€{{ $order->order_total() }} </span></div>
                            </div>
                            <div class="game-card__rating-and-price">
                                <div class="order-info-label">Created At: <span>{{ date('M d, Y, H:i', strtotime($order->created_at)) }} </span></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="game-card --horizontal favourites-game">
                <div class="game-card__box">
                    <div class="game-card__info" style="width: 100%;">
                        <a class="game-card__title" href="#"> Kinguin Info</a>
                         
                        <div class="game-card__bottom">
                            <div class="game-card__rating-and-price">
                                <div class="order-info-label">OrderId: <span>{{ $order->kg_orderid }} </span></div>
                            </div>
                            <div class="game-card__rating-and-price">
                                <div class="order-info-label">Order External Id: <span>{{ $order->reference }} </span></div>
                            </div>
                        </div>
                        <div class="game-card__bottom">
                            <div class="game-card__rating-and-price">
                                <div class="order-info-label">Dispatch Id: <span>{{ $order->dispatchId }} </span></div>
                            </div>
                            <div class="game-card__rating-and-price">
                                <div class="order-info-label">Status: <span>{{ $order->kg_order_status }} </span></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="game-card --horizontal favourites-game">
                <div class="game-card__box">
                    <div class="game-card__info" style="width: 100%;">
                        <a class="game-card__title" href="#"> Order Items</a>
                         
                        <div class="game-card__bottom">
                            <table class="uk-table">
                                <thead>
                                    <tr>
                                        <td>ID</td>
                                        <td>Name</td>
                                        <td>Price</td>
                                        <td>Qty</td>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($order->items as $item):
                                    <tr>
                                        <th>{{ $item->product_id }}</th>
                                        <th>{{ $item->product_name }}</th>
                                        <th>€{{ $item->product_price }}</th>
                                        <th>{{ $item->product_qty }}</th>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="game-card --horizontal favourites-game">
                <div class="game-card__box">
                    <div class="game-card__info" style="width: 100%;">
                        <a class="game-card__title" href="#"> Game Serial Keys</a>
                         
                        <div class="game-card__bottom">
                            <table class="uk-table">
                                <thead>
                                    <tr>
                                        <td>Name</td>
                                        <td>Serial</td>
                                        <td>Offer Id</td>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($order->keys as $key):
                                    <tr>
                                        <th>{{ $key->name }}</th>
                                        <th>{{ $key->serial }}</th>
                                        <th>{{ $key->offerId }}</th>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endblock