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
                        <a class="game-card__title" href="#">{{ trans('orders.order_info') }}</a>
                         
                        <div class="game-card__bottom">
                            <div class="game-card__rating-and-price">
                                <div class="order-info-label">{{ trans('orders.payment_method') }}: <span>{{ $order->payment_method_type }} </span></div>
                            </div>
                            <div class="game-card__rating-and-price">
                                <div class="order-info-label">{{ trans('orders.transaction_id') }}: <span>{{ $order->transaction_id }} </span></div>
                            </div>
                        </div>
                        <div class="game-card__bottom">
                            <div class="game-card__rating-and-price">
                                <div class="order-info-label">{{ trans('orders.status') }}: <span>{{ $order->status }} </span></div>
                            </div>
                            <div class="game-card__rating-and-price">
                                <div class="order-info-label">{{ trans('orders.currency') }}: <span>{{ $order->currency }} </span></div>
                            </div>
                        </div>
                        <div class="game-card__bottom">
                            <div class="game-card__rating-and-price">
                                <div class="order-info-label">{{ trans('cart.total') }}: 
                                    @if($order->currency == 'COP'):
                                    <span>{{ $order->amount_in_cents/100 }} COP</span>
                                    @else
                                    <span>{{ $order->amount_in_cents/100 }} {{ $order->currency }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="game-card__rating-and-price">
                                <div class="order-info-label">{{ trans('orders.created_at') }}: <span>{{ date('M d, Y, H:i', strtotime($order->created_at)) }} </span></div>
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
                        <a class="game-card__title" href="#">{{ trans('orders.order_items') }}</a>
                         
                        <div class="game-card__bottom">
                            <table class="uk-table">
                                <thead>
                                    <tr>
                                        <td>{{ trans('orders.id') }}</td>
                                        <td>{{ trans('orders.name') }}</td>
                                        <td>{{ trans('cart.price') }}</td>
                                        <td>{{ trans('orders.qty') }}</td>
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
                        <a class="game-card__title" href="#"> {{ trans('orders.game_serial_keys') }}</a>
                         
                        <div class="game-card__bottom">
                            <table class="uk-table">
                                <thead>
                                    <tr>
                                        <td>{{ trans('orders.name') }}</td>
                                        <td>{{ trans('orders.serial') }}</td>
                                        <td>{{ trans('orders.offer_id') }}</td>
                                    </tr>
                                </thead>
                                <tbody>
                                @if (!empty($order->keys)):
                                @foreach($order->keys as $key):
                                    <tr>
                                        <th>{{ $key->name }}</th>
                                        <th>{{ $key->serial }}</th>
                                        <th>{{ $key->offerId }}</th>
                                    </tr>
                                @endforeach
                                @endif
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
                        <a class="game-card__title" href="#"> {{ trans('orders.customer_bill_info') }}</a>
                         
                        <div class="game-card__bottom">
                            <table class="uk-table customer-info-tbl">
                                <tbody>
                                    <tr>
                                        <th>{{ trans('orders.name') }}</th>
                                        <th>{{ trans('login_page.email') }}</th>
                                        <th>{{ trans('checkout.phone') }}</th>
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
                                        <th>{{ trans('checkout.address') }}</th>
                                        <th colspan="2">{{ trans('checkout.additional_note') }}</th>
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