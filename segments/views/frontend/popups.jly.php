<div id="cd-shadow-layer"></div>

<div id="cd-cart">
    <h2>{{ trans('cart_popup.shopping_cart') }} <a href="#0" class="cd-item-remove cd-img-replace close_cart_panel" style="float:right;">{{ trans('cart_popup.remove') }}</a></h2>
    <ul class="cd-cart-items">
        @if(cartTotal() > 0):
        <div class="widjet__body">
            @foreach(cartItems() as $cart):
                
                <div class="widjet-game">
                    <div class="widjet-game__media"><a href="{{ url('store/view/'.$cart->product->id) }}"><img src="{{ $cart->product->coverImage }}" alt="image"></a></div>
                    <div class="widjet-game__info">
                        <div class="widjet-game__title"><a href="{{ url('store/view/'.$cart->product->id) }}">{{ $cart->product->name }}</a></div>
                        <div class="widjet-game__starting">{{ trans('cart.price') }}: {{ currencySymbol() }}<b class="price">{{ $cart->product->price }}</b></div>
                        <div class="widjet-game__quantity">
                            {{ trans('cart.quantity') }}:<input type="number" onkeypress="return isNumber(event)" min="1" class="uk-input quantity" value="{{ $cart->product_qty }}" data-url="{{ route('frontend.cart.update-qty', ['product' => $cart->product->id]) }}" style="height:35px;width:100px;">
                            </div>
                    </div>

                    <div style="width: 100%;">
                        <a href="{{ route('frontend.cart.remove',[$cart->id]) }}"><i class="ico_trash" style="float: right;color:red;cursor:pointer;"></i></a>
                    </div>
                </div>
                <div class="products_limitation" data-region_limitation="{{ $cart->product->regionalLimitations }}" data-product_id="{{ $cart->product->id }}"></div>
                <div id="product_limit_from_title_{{ $cart->product->id }}" data-region_limitation_from_title="{{ findForRegion($cart->product->slug) }}"></div>
                <div class="country_restriction_success_text_cart" style="display: none;" id="country_restriction_success_text_cart_{{ $cart->product->id }}"><i class="ico_tick-circle"></i> {{ trans('view_product.can_be_activated_in') }}: <span class="YOUR_COUNTRY"></span></div>
                <div class="country_restriction_danger_text_cart" style="display: none;" id="country_restriction_danger_text_cart_{{ $cart->product->id }}"><i class="ico_close-circle"></i> {{ trans('view_product.cant_be_activated_in') }}: <span class="YOUR_COUNTRY"></span></div>
                <hr>
                
            @endforeach
        </div>
        @else
        <li>
             {{ trans('cart_popup.no_item_cart') }}
        </li>
        @endif
        <!-- <li>
            <span class="cd-qty">1x</span> Product Name
            <div class="cd-price">$9.99</div>
            <a href="#0" class="cd-item-remove cd-img-replace">Remove</a>
        </li> -->
        
    </ul> <!-- cd-cart-items -->
    @if(cartTotal() > 0):          
    <div class="cd-cart-total">
        <p>{{ trans('cart.subtotal') }} <span>{{ currencySymbol() }}<span class="subtotal_count"></span></span></p>
        <p>{{ trans('cart.total') }} <span>{{ currencySymbol() }}<span class="total_count"></span></span></p>
    </div> <!-- cd-cart-total -->

    <a href="{{ route('frontend.checkout.index') }}" class="checkout-btn">{{ trans('checkout.title') }}</a>
    @endif
    
    <p class="cd-go-to-cart"><a href="{{ route('frontend.home') }}">{{ trans('cart_popup.continue_shopping') }}</a></p>
</div> <!-- cd-cart -->