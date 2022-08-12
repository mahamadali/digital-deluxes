@extends('app')

@block("title") {{ setting('app.title', 'Ali Rocks!') }} @endblock
<link rel="stylesheet" href="{{ url('assets/frontend/css/pagination.css') }}">
<style>
  
</style>
@block("content")
<main class="page-main">
    <div class="uk-grid" data-uk-grid>
        <div class="uk-width-2-3@l">
            <div class="widjet --filters">
                <div class="widjet__head">
                    <h3 class="uk-text-lead">Carts</h3>
                </div>
            </div>
            <div class="widjet --market">
                @foreach($cart_details as $cart):
                <div class="widjet__body">
                    <div class="widjet-game">
                        <div class="widjet-game__media"><a href="10_game-profile.html"><img src="{{ $cart->product()->coverImage }}" alt="image"></a></div>
                        <div class="widjet-game__info">
                            <div class="widjet-game__title"><a href="10_game-profile.html">{{ $cart->product()->name }}</a></div>
                            <!-- <div class="widjet-game__game-name"></div> -->
                            <div class="widjet-game__starting">Price:<b>${{ $cart->product()->price }}</b></div>
                            <div class="widjet-game__quantity">
                                Quantity:<input type="text" class="uk-input" value="{{ $cart->product_qty }}" style="height:35px;width:100px;">
                                </div>
                        </div>

                        <div style="width: 100%;">
                            <i class="ico_trash" style="float: right;color:red;cursor:pointer;"></i>
                        </div>
                    </div>
                </div>
               @endforeach

            </div>
        </div>
        <div class="uk-width-1-3@l">
            <div class="widjet --upload">
                <!-- <div class="widjet__head">
                    <h3 class="uk-text-lead">Upload Item</h3>
                </div> -->
                <div class="widjet__body">
                    <div class="widjet-game__title">
                         Subtotal : 100
                    </div>

                    <!-- <div class="widjet-game__title">
                        Total : 100
                    </div>

                    <button class="uk-button uk-button-secondary" type="button">CHECKOUT</button></div> -->
                </div>
        </div>
    </div>
</main>
@endblock