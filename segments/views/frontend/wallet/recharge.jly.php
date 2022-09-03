@extends('app')

@block("title") {{ setting('app.title', 'Ali Rocks!') }} @endblock
@block("content")
<main class="page-main">
<div class="uk-grid" data-uk-grid="">
    <div class="uk-width-1-3@l">
        <div class="widjet --payment-method">
            <div class="widjet__head">
                <h3 class="uk-text-lead">Wallet</h3>
            </div>
            <div class="widjet__body">
                <div class="payment-card">
                    <div class="payment-card__head">
                        <!-- <div class="payment-card__chip"><img src="{{ url('assets/frontend/img/payment-logos/'.$paymentMethod->title.'.png') }}" alt="chip"></div> -->
                        <div class="payment-card__logo"><img src="{{ url('assets/frontend/img/payment-logos/'.$paymentMethod->title.'.png') }}" alt="logo"></div>
                    </div>
                    <div class="payment-card__number">{{ $paymentMethod->title }}</div>
                    <div class="payment-card__value" style="text-align: center;">{{ $paymentMethod->currency }} currency</div>
                </div>
            </div>
        </div>
    </div>
    <div class="uk-width-2-3@l">
    <div class="widjet --bio">
        <div class="widjet__head">
            <h3 class="uk-text-lead">Add to Wallet</h3>
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
            <form method="post" action="{{ route('frontend.wallet.recharge.submit', ['payment_method' => $paymentMethod->id]) }}"  enctype="multipart/form-data" id="recharge-form">
            {{ prevent_csrf() }}
            
            <div class="uk-margin">
                <input type="hidden" name="payment_method" value="{{ $paymentMethod->title }}">
                <select class="uk-input" name="balance" required>
                    <option value="">Select</option>
                    @foreach($balances as $balance):
                        <option value="{{ $balance }}">${{ $balance }}</option>
                    @endforeach
                </select>
                
            </div>
            <div class="uk-margin"><button class="uk-button uk-button-danger uk-width-1-1" type="submit">ADD</button></div>
            </form>
            <div class="cho-container"></div>
        </div>
    </div>
    </div>
</div>
</main>

@endblock

@block('scripts')
<script src="https://sdk.mercadopago.com/js/v2"></script>
<script type="text/javascript" src="https://js.stripe.com/v3/"></script>
<script>
    $(document).ready(function() {
        $("#recharge-form").validate({
            rules: {
                balance: {
                    required: true
                }
            },
            messages: {
                balance: {
                    required: "Please choose balance",
                }
            },
            submitHandler: function(form) {
                
                if($(form).find('input[name="payment_method"]').val() == 'Wompi') {
                    cartTotal = $(form).find('select[name="balance"]').val();
                    
                    var amountInCents = (cartTotal * 100).toFixed(0);
                    var user_phone = '{{ !empty(auth()) ? user()->phone : "" }}';
                    if(user_phone != '') {
                        var checkout = new WidgetCheckout({
                        currency: 'COP',
                        amountInCents: amountInCents,
                        reference: '{{ strtoupper(random_strings(12)) }}',
                        publicKey: '{{ setting("wompi.PUB_KEY") }}',
                        redirectUrl: '{{ route("frontend.wallet.recharge.success", ["payment_method" => $paymentMethod->id]) }}', // Opcional
                        customerData: { // Opcional
                            email:'{{ !empty(auth()) ? user()->email : "" }}',
                            fullName: '{{ !empty(auth()) ? user()->first_name." ".user()->last_name : "" }}',
                            phoneNumber: '{{ !empty(auth()) ? user()->phone : "" }}',
                            phoneNumberPrefix: '+{{ !empty(auth()) ? user()->country_code : "" }}',
                            legalId: '{{ !empty(auth()) ? user()->id : 0 }}',
                            legalIdType: 'CC',
                        },
                        });

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
                    }
                } else {
                    $(form).find('button[type="submit"]').html('<i class="fa fa-spinner fa-spin"></i>Processing...');
                    $(form).find('button[type="submit"]').prop('disabled', true);
                    $.ajax({
                    url : $(form).attr('action'),
                    type : 'POST',
                    data : $(form).serializeArray(),
                    dataType: 'json',
                    success: function(response) {

                        $(form).find('button[type="submit"]').html('ADD');
                        $(form).find('button[type="submit"]').prop('disabled', false);

                        $('#messages').html('');
                        if(response.status == 304) {
                            response.errors.forEach(error => {
                                $('#messages').append('<p align="center" style="color:red;">'+error+'</p>');
                            });
                        }

                        if(response.status == 200 && response.payment_method == 'Mercado Pago') {
                            // $(form).find('button[type="submit"]').hide();
                            // const mp = new MercadoPago('{{ setting("mercadopago.public_key") }}', {
                            //     locale: 'es-AR'
                            // });
                            
                            if(response.redirectUrl != null) {
                                window.location.href = response.redirectUrl;
                            } else {
                                toastr.error('Currently '+response.payment_method+' is not responsive! Please try with other payment method.');
                            }

                            // mp.checkout({
                            //     preference: {
                            //     id: response.preference_id
                            //     },
                            //     render: {
                            //     container: '.cho-container',
                            //     label: 'Pay Now',
                            //     }
                            // });

                            // $('.mercadopago-button').click();
                            
                        }

                        if(response.status == 200 && response.payment_method == 'Stripe') {
                            
                            if(response.redirectUrl != null) {
                                window.stripe = Stripe('{{ setting("stripe.publishable_key") }}');
                                stripe.redirectToCheckout({ sessionId: response.stripeSessionId });
                            } else {
                                toastr.error('Currently '+response.payment_method+' is not responsive! Please try with other payment method.');
                            }
                            
                        }
                    },
                    error: function() {
                        $(form).find('button[type="submit"]').html('ADD');
                        $(form).find('button[type="submit"]').prop('disabled', false);
                    }
                    });
                }
                
                }
            });
    });
</script>
@endblock