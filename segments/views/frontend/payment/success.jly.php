@extends('app')

@block("title") {{ setting('app.title', 'Ali Rocks!') }} @endblock
@block("content")
<main class="page-main">
    <div class="uk-grid" data-uk-grid>
        <div class="uk-width">
            <div class="widjet --filters">
                <div class="widjet__head">
                    <h3 class="uk-text-lead">{{ trans('payment_success.order_placed_success') }}</h3>
                </div>
            </div>
            <div class="game-card --horizontal favourites-game">
                <div class="game-card__box" style="flex-direction: column;padding:50px;">
                        <a style="font-size: 20px;">{{ trans('payment_success.thankyou_msg_success') }}</a>
                        <div class="game-card__genre"></div>
                        <br><br>
                        <div class="game-card__bottom">
                            <a class="uk-button uk-button-primary uk-width-1-1" href="{{ route('frontend.home') }}">{{ trans('payment_success.go_to_home') }}</a>
                        </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endblock