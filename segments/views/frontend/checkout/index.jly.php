@extends('app')

@block("title") {{ setting('app.title', 'Ali Rocks!') }} @endblock
<style>
  .uk-input {
    border-radius: 0px;
  }
  .uk-text-sub-lead {
    font-family: 'Marcellus', serif;
    font-size: 15px;
    color: #1f1f1f;
    margin-bottom: 5px;
  }
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
  .iti--separate-dial-code
    {
        width: 100% !important;
    }

    small {
        font-size: 10px;
    }

    .payment_method_info {
        border: 1px solid #f4d919;
        padding: 10px;
    }
    .payment_method_info label {
        cursor: pointer;
    }
</style>
@block("content")
<main class="page-main">
    
<div class="widjet --filters">
    <div class="widjet__head">
        <h3 class="uk-text-lead">{{ trans('checkout.title') }}</h3>
    </div>
</div>
<form method="post" action="{{ route('frontend.checkout.createOrder') }}" id="checkout-form" enctype="multipart/form-data">
    {{ prevent_csrf() }}
    <div class="uk-grid" data-uk-grid>
        
        <div class="uk-width-2-3@l">
            
            <div class="widjet --market">
            <div class="widjet --bio">
                    <div class="">
                        <h6 class="uk-text-sub-lead">{{ trans('profile.customer_info') }}</h6>
                    </div>
                    <div class="widjet__body">
                        @if (session()->hasFlash('error')):
                        <div class="alert-danger">
                            <span>{{ session()->flash('error') }}</span>
                        </div>
                        @endif

                        @if (session()->hasFlash('success')):
                        <div class="alert-success text-center">
                            <span>{{ session()->flash('success') }}</span>
                        </div>
                        @endif
                        
                        
                        <div class="uk-grid" data-uk-grid>
                            <div class="uk-width-1-2@l"><label>{{ trans('checkout.first_name') }}</label><input type="text" class="uk-input" name="first_name" placeholder="{{ trans('checkout.first_name') }}" value="{{ $user->first_name }}" required></div>
                            <div class="uk-width-1-2@l"><label>{{ trans('checkout.last_name') }}</label><input type="text" class="uk-input" name="last_name" placeholder="{{ trans('checkout.last_name') }}" value="{{ $user->last_name }}" required></div>
                        </div>

                        <div class="uk-margin"><label>{{ trans('checkout.email') }}</label><input type="email" class="uk-input" name="email" placeholder="{{ trans('checkout.email') }}" value="{{ $user->email }}" required></div>
                        
                        <div class="uk-grid" data-uk-grid>
                        <div class="uk-width-1-1@l"><label>{{ trans('checkout.country_code') }}</label><input type="hidden" name="country_code" id="country_code" value="{{ $user->country_code }}"><input type="text" class="uk-input" name="phone" id="phone" placeholder="{{ trans('checkout.phone') }}" value="{{ $user->phone }}" required></div>
                        </div>
                        <div class="uk-grid" data-uk-grid>
                        <div class="uk-width-1-1@l"><label>{{ trans('checkout.address') }}</label><input type="text" class="uk-input" name="address" placeholder="{{ trans('checkout.address') }}" value="{{ $user->address }}" required></div>
                        
                        </div>
                        <div class="uk-grid" data-uk-grid>
                        <div class="uk-width-1-2@l">
                            <label>{{ trans('checkout.country') }}</label>
                            <select class="uk-input" name="country" required>
                                <option value="">{{ trans('checkout.country') }}</option>
                                @foreach($countries as $country):
                                    <option value="{{ $country->id }}" {{ $user->country == $country->id ? 'selected' : '' }}>{{ $country->country_name }}</option>
                                @endforeach
                            </select>
                            
                        </div>
                        <div class="uk-width-1-2@l">
                            <label>{{ trans('checkout.city') }}</label>
                            <input type="text" class="uk-input" name="city" placeholder="{{ trans('checkout.city') }}" value="{{ $user->city }}" required></div>
                        </div>
                        
                        
                    </div>
                </div>
            </div>

            <div class="widjet --market">
            <div class="widjet --bio">
                    <div class="">
                        <h6 class="uk-text-sub-lead">{{ trans('checkout.additional_note') }}</h6>
                    </div>
                    <div class="widjet__body">
                        
                        <div class="uk-margin"><label>{{ trans('checkout.note') }}</label><textarea class="uk-input" name="additional_note"></textarea></div>
                        
                    </div>
                </div>
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
                    {{ trans('cart.subtotal') }}  <small class="custom-right" style="margin-left: 5px;">COP</small><b class="custom-right">{{ round($total_amount, 2,) }}</b>
                    </div>
                 </div>

                 <div class="col-custom-row-12">
                    <div class="col-custom-12">
                    {{ trans('cart.total') }}  <small class="custom-right" style="margin-left: 5px;">COP</small><b class="custom-right">{{ round($total_amount, 2) }}</b>
                    </div>
                 </div>
                </div>

                <div class="custom__body" style="margin-top: 10px;">

                 <div class="widjet__head">
                    <h6 class="uk-text-sub-lead">{{ trans('checkout.payment_methods') }}</h6>
                </div>
                @if($walletEnable):
                <div class="col-custom-row-12 payment_method_info">
                    <div class="col-custom-12">
                    <label><input type="radio" name="payment_method" data-value="Wallet" value="Wallet" required> {{ trans('menu.wallet') }} <small>{{ round($wallet_in_cop, 2) }} COP</small></label>
                    </div>
                 </div>
                 @endif
                @foreach($payment_methods as $key => $payment_method):
                 <div class="col-custom-row-12 payment_method_info">
                    <div class="col-custom-12">
                    <label><input type="radio" name="payment_method" data-value="{{ $payment_method->title }}" value="{{ $payment_method->id }}" {{ ($key == 0) ? 'required' : '' }}> {{ $payment_method->title }} <small>{{ $payment_method->currency }}</small> 
                    
                    @if(file_exists($payment_method->main_logo)):
                    <div class="payment-card__logo"><img src="{{ url($payment_method->main_logo) }}" alt="logo"></div>
                    @endif
                    </label>
                    </div>
                 </div>
                 @endforeach
                 <div class="col-custom-row-12">
                <div class="col-custom-12">
                    <button class="uk-button uk-button-danger custom-center" type="submit" style="width: 100%;">{{ trans('checkout.place_order') }}</button>
                </div> 
                </div>
                </div> 
            </div>
        </div>
    </div>
    <input type="hidden" name="order_reference" value="{{ $order_reference }}">
    </form>
</main>
@endblock

@block('scripts')
<script>
    function getIp(callback) {
    fetch("https://ipinfo.io/json?token=ee9dceccd60e6f", {
        headers: { Accept: "application/json" },
    })
        .then((resp) => resp.json())
        .catch(() => {
        return {
            country: "us",
        };
        })
        .then((resp) => callback(resp.country));
    }

    var phoneInputField = document.querySelector("#phone");
    const phoneInput = window.intlTelInput(phoneInputField, {
        initialCountry: "auto",
        separateDialCode: true,
        geoIpLookup:getIp,
        autoPlaceholder: "aggressive",
        nationalMode: true,
        utilsScript: "{{ url('assets/js/utils.js') }}",
    });

    phoneInputField.addEventListener("countrychange",function() {
    $('#country_code').val(phoneInput.getSelectedCountryData()['dialCode']);
    });

    phoneInput.setNumber("+<?php echo $user->country_code." ".$user->phone; ?>");

    $(document).ready(function() {
        $('#checkout-form').validate({
            submitHandler: function(form) {
                if($(form).find('input[name="payment_method"]:checked').data('value') == 'Wompi') {
                    var exchangePrice = '{{ $total_amount }}';
                    var cartTotal = exchangePrice;
                    
                    if(cartTotal > 0 && cartTotal > 1500) {
                        var amountInCents = (cartTotal * 100).toFixed(0);
                        var user_phone = '{{ !empty(auth()) ? user()->phone : "" }}';
                        if(user_phone != '') {
                            var checkout = new WidgetCheckout({
                                currency: 'COP',
                                amountInCents: amountInCents,
                                reference: '{{ $order_reference }}',
                                publicKey: '{{ setting("wompi.PUB_KEY") }}',
                                redirectUrl: '{{ setting("wompi.REDIRECT_URL") }}', // Opcional
                                customerData: { // Opcional
                                    email:'{{ !empty(auth()) ? user()->email : "" }}',
                                    fullName: '{{ !empty(auth()) ? user()->first_name." ".user()->last_name : "" }}',
                                    phoneNumber: '{{ !empty(auth()) ? user()->phone : "" }}',
                                    phoneNumberPrefix: '+{{ !empty(auth()) ? user()->country_code : "" }}',
                                    legalId: '{{ !empty(auth()) ? user()->id : 0 }}',
                                    legalIdType: 'CC'
                                }
                            });

                            var formData = new FormData(form);
                            saveCustomerBillingInfo($(form).attr('action'), formData);

                            checkout.open(function ( result ) {
                                var transaction = result.transaction
                                console.log('Transaction ID: ', transaction.id)
                                console.log('Transaction object: ', transaction)
                                $('#page-preloader').show();
                                if(transaction.status == "APPROVED") {
                                    window.location.href = transaction.redirectUrl+"?id="+transaction.id;
                                } else {
                                    $('#page-preloader').hide();
                                    toastr.error(transaction.statusMessage);
                                }
                            });
                        } else {
                            toastr.error('Please enter your phone in your profile to make payment');
                            return false;
                        }
                    }
                }
                if($(form).find('input[name="payment_method"]:checked').data('value') == 'Mercado Pago') {
                    var formData = new FormData(form);
                    $.ajax({
                        url : $(form).attr('action'),
                        type : 'POST',
                        data : formData,
                        dataType: 'json',
                        contentType: false, processData: false,
                        success: function(response) {
                            if(response.redirectUrl != null) {
                                window.location.href = response.redirectUrl;
                            } else {
                                toastr.error('Currently '+response.payment_method+' is not responsive! Please try with other payment method.');
                            }
                        },
                        error: function() {
                            
                        }
                    });
                }

                if($(form).find('input[name="payment_method"]:checked').data('value') == 'Wallet') {
                    $(form).find('button[type="submit"]').prop('disabled', true);
                    $('#page-preloader').show();
                    var formData = new FormData(form);
                    $.ajax({
                        url : $(form).attr('action'),
                        type : 'POST',
                        data : formData,
                        dataType: 'json',
                        contentType: false, processData: false,
                        success: function(response) {
                            $('#page-preloader').hide();
                            if(response.status == 304) {
                                toastr.error(response.message);
                                $(form).find('button[type="submit"]').prop('disabled', false);
                            } else {
                                toastr.success(response.message);
                                window.location.href = response.redirectUrl;
                            }
                        },
                        error: function() {
                            $(form).find('button[type="submit"]').prop('disabled', false);
                            toastr.error('Something went wrong! Please refresh page and try again!');
                            $('#page-preloader').hide();
                        }
                    });
                }

                if($(form).find('input[name="payment_method"]:checked').data('value') == 'Paypal') {
                    $(form).find('button[type="submit"]').prop('disabled', true);
                    $('#page-preloader').show();
                    var formData = new FormData(form);
                    $.ajax({
                        url : $(form).attr('action'),
                        type : 'POST',
                        data : formData,
                        dataType: 'json',
                        contentType: false, processData: false,
                        success: function(response) {
                            $('#page-preloader').hide();
                            if(response.status == 304) {
                                toastr.error(response.message);
                                $(form).find('button[type="submit"]').prop('disabled', false);
                            } else {
                                window.location.href = response.redirectUrl;
                            }
                        },
                        error: function() {
                            $(form).find('button[type="submit"]').prop('disabled', false);
                            toastr.error('Something went wrong! Please refresh page and try again!');
                            $('#page-preloader').hide();
                        }
                    });
                }

                if($(form).find('input[name="payment_method"]:checked').data('value') == 'Coinbase') {
                    $(form).find('button[type="submit"]').prop('disabled', true);
                    $('#page-preloader').show();
                    var formData = new FormData(form);
                    $.ajax({
                        url : $(form).attr('action'),
                        type : 'POST',
                        data : formData,
                        dataType: 'json',
                        contentType: false, processData: false,
                        success: function(response) {
                            $('#page-preloader').hide();
                            if(response.status == 304) {
                                toastr.error(response.message);
                                $(form).find('button[type="submit"]').prop('disabled', false);
                            } else {
                                window.location.href = response.redirectUrl;
                            }
                        },
                        error: function() {
                            $(form).find('button[type="submit"]').prop('disabled', false);
                            toastr.error('Something went wrong! Please refresh page and try again!');
                            $('#page-preloader').hide();
                        }
                    });
                }
            }
        });
    })
</script>
@endblock