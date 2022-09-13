@extends('app')

@block("title") {{ setting('app.title', 'Ali Rocks!') }} @endblock
<link rel="stylesheet" href="{{ url('assets/frontend/css/pagination.css') }}">
<style>
  .col-custom-6{
    width: 50%;
  }

  .col-custom-6{
    width: 100%;
  }


  .col-custom-row-12{
    width: 100% !important;
    margin-bottom: 10px;

  }
  .custom-right{
    float: right;
  }

  .custom__body{
    padding: 20px 30px;
    border-radius: 5px;
    background: #fff;
  }

  .custom-center{
    text-align: center;
  }
</style>
@block("content")
<main class="page-main">
    <div class="uk-grid" data-uk-grid>
        
        <div class="uk-width-2-3@l">
            <div class="widjet --filters">
                <div class="widjet__head">
                    <h3 class="uk-text-lead">{{ trans('cart.carts') }}</h3>
                </div>
            </div>
            <div class="widjet --market">
            <form>
                @foreach($cart_details as $cart):
                <div class="widjet__body">
                    <div class="widjet-game">
                        <div class="widjet-game__media"><a href="#"><img src="{{ $cart->product->coverImage }}" alt="image"></a></div>
                        <div class="widjet-game__info">
                            <div class="widjet-game__title"><a href="#">{{ $cart->product->name }}</a></div>
                            <div class="widjet-game__starting">{{ trans('cart.price') }}: $<b class="price">{{ $cart->product->price }}</b></div>
                            <div class="widjet-game__quantity">
                                {{ trans('cart.quantity') }}:<input type="text"  class="uk-input quantity" value="{{ $cart->product_qty }}" style="height:35px;width:100px;">
                                </div>
                        </div>

                        <div style="width: 100%;">
                            <a href="{{ route('frontend.cart.remove',[$cart->id]) }}"><i class="ico_trash" style="float: right;color:red;cursor:pointer;"></i></a>
                        </div>
                    </div>
                </div>
               @endforeach
               <form>       
            </div>
        </div>
        <div class="uk-width-1-3@l">
            <div class="widjet --upload">
                <div class="widjet__head">
                    <h3 class="uk-text-lead">{{ trans('cart.checkout') }}</h3>
                </div>
                <div class="custom__body">
                 <div class="col-custom-row-12">
                    <div class="col-custom-12">
                         Subtotal  {{ currencySymbol() }}<b class="custom-right subtotal_count"></b>
                    </div>
                 </div>

                 <div class="col-custom-row-12">
                    <div class="col-custom-12">
                         Total  {{ currencySymbol() }}<b class="custom-right total_count"></b>
                    </div>
                 </div>
                 <div class="col-custom-row-12">
                    <div class="col-custom-12">
                        <button class="uk-button uk-button-secondary custom-center" type="button" style="width: 100%;">{{ trans('cart.checkout') }}</button></div> 
                    </div>
                 </div>
                </div>
        </div>
    </div>
</main>
@endblock

@block('scripts')
<script>
    $(document).ready(function() {
        doCalc();
    });

    $(document).on('keyup keypress blur change', '.quantity', function() {
        doCalc();
    })

    function doCalc(){
        var price = 0;
        var qty = 0;
        var item_total = 0;

        $(".widjet-game__info").each(function() {
             price  = $(this).find('.price').html();
             qty  = $(this).find('.quantity').val();
             item_total += price * qty;
        });

        item_total = parseFloat(item_total).toFixed(2);
        $('.subtotal_count').html(item_total);
        $('.total_count').html(item_total);

    }
</script>
@endblock