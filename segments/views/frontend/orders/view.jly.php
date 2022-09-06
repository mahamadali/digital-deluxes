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
    .customer-info-tbl th, .customer-info-tbl td {
        padding: 5px 12px;
        margin-bottom: 20px;
    }

    .uk-table td {
        text-transform: none;
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
                                <div class="order-info-label">Total: 
                                    @if($order->currency == 'COP'):
                                    <span>{{ $order->amount_in_cents/100 }} COP</span>
                                    @else
                                    <span>{{ $order->amount_in_cents/100 }} {{ $order->currency }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="game-card__rating-and-price">
                                <div class="order-info-label">Created At: <span>{{ date('M d, Y, H:i', strtotime($order->created_at)) }} </span></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- <div class="game-card --horizontal favourites-game">
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
            </div> -->

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
                                        <th>${{ $item->product_price }}</th>
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

            @if(!empty($customer_billing_infos)):
            <div class="game-card --horizontal favourites-game">
                <div class="game-card__box">
                    <div class="game-card__info" style="width: 100%;">
                        <a class="game-card__title" href="#"> Customer Billing Information</a>
                         
                        <div class="game-card__bottom">
                            <table class="uk-table customer-info-tbl">
                                <tbody>
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone Number</th>
                                    </tr>
                                    <tr>
                                        <td>{{ $customer_billing_infos->first_name." ".$customer_billing_infos->last_name }}</td>
                                        <td>{{ $customer_billing_infos->email }}</td>
                                        <td>{{ $customer_billing_infos->phone_number }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="game-card__bottom">
                            <table class="uk-table customer-info-tbl">
                                <tbody>
                                    <tr style="margin-top: 20px;">
                                    <th>Address</th>
                                    <th colspan="2">Additional Note</th>
                                </tr>
                                <tr>
                                    <td>
                                        {{ $customer_billing_infos->address }}
                                        <br>
                                        {{ $customer_billing_infos->city }}
                                        <br>
                                        {{ $customer_billing_infos->country_info->country_name }}
                                    </td>
                                    <td colspan="2">{{ $customer_billing_infos->additional_note }}</td>
                                </tr>
                                </tbody>
                            </table>
                            
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</main>
@endblock