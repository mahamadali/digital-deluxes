@extends('app')

@block("title") {{ setting('app.title', 'Ali Rocks!') }} @endblock
@block("content")
<main class="page-main">
<div class="uk-grid" data-uk-grid="">
    <div class="uk-width-1-3@l uk-first-column">
        <div class="widjet --wallet">
            <div class="widjet__head">
                <h3 class="uk-text-lead">{{ trans('menu.wallet') }}</h3>
            </div>
            <div class="widjet__body">
                <div class="wallet-info">
                    <div class="wallet-value">{{ user()->wallet_amount }} EUR</div>
                    <div class="wallet-label"><i class="icon-wallet"></i> {{ trans('wallet.available') }}</div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="uk-grid" data-uk-grid="">
    @foreach($paymentMethods as $paymentMethod):
    <div class="uk-width-1-3@l">
        <div class="widjet --payment-method">
            <div class="widjet__body">
                <div class="payment-card">
                    <div class="payment-card__head">
                        <!-- <div class="payment-card__chip"><img src="{{ url('assets/frontend/img/payment-logos/'.$paymentMethod->title.'.png') }}" alt="chip"></div> -->
                        <div class="payment-card__logo"><img src="{{ url('assets/frontend/img/payment-logos/'.$paymentMethod->title.'.png') }}" alt="logo"></div>
                    </div>
                    <div class="payment-card__number">{{ $paymentMethod->title }}</div>
                    <div class="payment-card__value" style="text-align: center;"><a href="{{ route('frontend.wallet.recharge', ['payment_method' => $paymentMethod->id]) }}" class="recharge-button">{{ trans('wallet.recharge') }}</a></div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
<div class="uk-grid" data-uk-grid="">
    <div class="uk-width-2-3@l">
        <div class="widjet --activities">
            <div class="widjet__head">
                <h3 class="uk-text-lead">{{ trans('wallet.activities') }}</h3>
                <!-- <a href="09_games-store.html">View All</a> -->
            </div>
            <div class="widjet__body">
                <ul class="activities-list">
                    @foreach($transactions as $transaction):
                    <li class="activities-item">
                        <div class="activities-item__logo"><a href="Javascript:void(0);"><img src="{{ url('assets/frontend/img/payment-logos/'.$transaction->payment_method_info->title.'.png') }}" alt="image"></a></div>
                        <div class="activities-item__info"><a class="activities-item__title" href="Javascript:void(0);"> {{ $transaction->payment_method_info->title }}</a>
                            <div class="activities-item__date">{{ date('d M, Y H:i', strtotime($transaction->created_at)) }}</div>
                        </div>
                        <div class="activities-item__price">{{ $transaction->kind_of_tx == 'CREDIT' ? '+' : '-' }} ${{ $transaction->amount }} {{ $transaction->currency }}</div>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>
</main>
@endblock

@block('scripts')
<script>
    $(document).ready(function() {
        
    });
</script>
@endblock