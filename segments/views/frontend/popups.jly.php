<div id="cd-shadow-layer"></div>

<div id="cd-cart">
    <h2>SHOPPING CART <a href="#0" class="cd-item-remove cd-img-replace close_cart_panel" style="float:right;">Remove</a></h2>
    <ul class="cd-cart-items">
        @if(cartTotal() > 0):
        <div class="widjet__body">
            @foreach(cartItems() as $cart):
                
                <div class="widjet-game">
                    <div class="widjet-game__media"><a href="{{ url('store/view/'.$cart->product()->id) }}"><img src="{{ $cart->product()->coverImage }}" alt="image"></a></div>
                    <div class="widjet-game__info">
                        <div class="widjet-game__title"><a href="{{ url('store/view/'.$cart->product()->id) }}">{{ $cart->product()->name }}</a></div>
                        <div class="widjet-game__starting">Price: {{ currencySymbol() }}<b class="price">{{ $cart->product()->price }}</b></div>
                        <div class="widjet-game__quantity">
                            Quantity:<input type="number" onkeypress="return isNumber(event)" min="1" class="uk-input quantity" value="{{ $cart->product_qty }}" data-url="{{ route('frontend.cart.update-qty', ['product' => $cart->product()->id]) }}" style="height:35px;width:100px;">
                            </div>
                    </div>

                    <div style="width: 100%;">
                        <a href="{{ route('frontend.cart.remove',[$cart->id]) }}"><i class="ico_trash" style="float: right;color:red;cursor:pointer;"></i></a>
                    </div>
                </div>
                <hr>
                
            @endforeach
        </div>
        @else
        <li>
             No item in cart
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
        <p>Subtotal <span>{{ currencySymbol() }}<span class="subtotal_count"></span></span></p>
        <p>Total <span>{{ currencySymbol() }}<span class="total_count"></span></span></p>
    </div> <!-- cd-cart-total -->

    <a href="#0" class="checkout-btn">Checkout</a>
    @endif
    
    <p class="cd-go-to-cart"><a href="{{ route('frontend.home') }}">Continue Shopping</a></p>
</div> <!-- cd-cart -->