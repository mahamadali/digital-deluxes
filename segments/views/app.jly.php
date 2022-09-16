<!DOCTYPE html>
<html lang="en">
    <head>
        <title>@plot('title')</title>
        <meta charset="utf-8">
        <meta content="Templines" name="author">
        <meta content="TeamHost" name="description">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
        @plot('meta-tags')
        <meta name="HandheldFriendly" content="true">
        <meta name="format-detection" content="telephone=no">
        <meta content="IE=edge" http-equiv="X-UA-Compatible">
        <meta name="token" content="{{ prevent_csrf_token() }}">
        <link rel="shortcut icon" href="{{ url('assets/frontend/img/favicon.png') }}" type="image/x-icon">
        <link rel="stylesheet" href="{{ url('assets/frontend/css/libs.min.css') }}">
        <link rel="stylesheet" href="{{ url('assets/frontend/css/main.css') }}">
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Marcellus&display=swap" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.0.1/css/toastr.css" rel="stylesheet"/>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.css">
        <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
        @plot('styles')
    </head>
    <body class="page-home {{ (session()->has('dark-mode') && session()->get('dark-mode') == 1) ? 'dark-theme' : '' }}">
        
        <a class="action-btn floating-cart-widget" id="cd-cart-trigger" href="Javascript:void(0);" class="floating-cart-widget"><i class="ico_shopping-cart"></i><span class="animation-ripple-delay1">{{ !empty(auth()) ? count(auth()->cart_items()) : 0 }}</span></a>
        <!-- <input id="toggle" type="checkbox"> -->
        


        <!-- Loader-->
        <div id="page-preloader">
            <div class="preloader-1">
                <div class="loader-text">Loading</div>
                <span class="line line-1"></span>
                <span class="line line-2"></span>
                <span class="line line-3"></span>
                <span class="line line-4"></span>
                <span class="line line-5"></span>
                <span class="line line-6"></span>
                <span class="line line-7"></span>
                <span class="line line-8"></span>
                <span class="line line-9"></span>
            </div>

        </div>
        <!-- Loader end-->

        <div class="page-wrapper">
            @include('layout/header')
            @include('frontend/mb-search-area')
            <div class="page-content">
                @include('layout/aside')
                @plot('content')
            </div>
        </div>
        @plot('popup')
        @include('frontend/popups')
        @include('frontend/modals')
        <script src="{{ url('assets/frontend/js/libs.js') }}"></script>
        <script src="{{ url('assets/frontend/js/main.js') }}"></script>
        <script src="{{ url('assets/frontend/js/general-func.js') }}"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.0.1/js/toastr.js"></script>
        <script type="text/javascript" src="https://checkout.wompi.co/widget.js"></script>
        <script src="{{ url('assets/js/js-intlTelInput.min.js') }}"></script>
        <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
        <script src="{{ url('assets/frontend/js/jquery.validate.min.js') }}"></script>

        <script>

$( function() {
            
            $('.search__input input[type="search"]').autocomplete({
                source: "{{ route('api.search-product') }}",
                minLength: 1,
                select: function( event, ui ) {
                    if(typeof ui.item.id != 'undefined') {
                        //window.location.href = "{ { url('store/view') } }/"+ui.item.id;
                    } else {
                        setTimeout(function() {
                            $('.search__input input[type="search"]').val('');
                        }, 500)
                    }
                },
            }).data('ui-autocomplete')._renderItem = function(ul, item){
                return $("<li class='ui-autocomplete-row'></li>")
                    .data("item.autocomplete", item)
                    .append(item.label)
                    .appendTo(ul);
                };;

            

            $('.search__input .search_name_mb').autocomplete({
                source: "{{ route('api.search-product') }}",
                minLength: 1,
                select: function( event, ui ) {
                    if(typeof ui.item.id != 'undefined') {
                        //window.location.href = "{ { url('store/view') } }/"+ui.item.id;
                    } else {
                        setTimeout(function() {
                            $('.search__input .search_name_mb').val('');
                        }, 500)
                    }
                },
            }).data('ui-autocomplete')._renderItem = function(ul, item){
                return $("<li class='ui-autocomplete-row'></li>")
                    .data("item.autocomplete", item)
                    .append(item.label)
                    .appendTo(ul);
                };

            // $('.search-category-input').on('change', function() {
            //     window.location.href = '{ { route("frontend.store.list") }}?category='+$(this).val();
            // });

            
        });

        // var exchangePrice = '{ { cartTotal() } }';
        // var cartTotal = exchangePrice;
        
        // if(cartTotal > 0 && cartTotal > 1500) {
        //     cartTotal = '{ { currencyConverter(strtoupper(session()->get('platform_currency')), "COP", cartTotal(), false) } }';
        //     var amountInCents = (cartTotal * 100).toFixed(0);
        //     var user_phone = '{ { !empty(auth()) ? user()->phone : "" } }';
        //     if(user_phone != '') {
        //         var checkout = new WidgetCheckout({
        //         currency: 'COP',
        //         amountInCents: amountInCents,
        //         reference: '{ { strtoupper(random_strings(12)) } }',
        //         publicKey: '{ { setting("wompi.PUB_KEY") } }',
        //         redirectUrl: '{ { setting("wompi.REDIRECT_URL") } }', // Opcional
        //         customerData: { // Opcional
        //             email:'{ { !empty(auth()) ? user()->email : "" } }',
        //             fullName: '{ { !empty(auth()) ? user()->first_name." ".user()->last_name : "" } }',
        //             phoneNumber: '{ { !empty(auth()) ? user()->phone : "" } }',
        //             phoneNumberPrefix: '+{ { !empty(auth()) ? user()->country_code : "" } }',
        //             legalId: '{ { !empty(auth()) ? user()->id : 0 } }',
        //             legalIdType: 'CC'
        //         }
        //         });
        //     }
        // }

        // $('.checkout-btn').on('click', function() {
        //     var user_phone = '{ { !empty(auth()) ? user()->phone : "" } }';
        //     if(user_phone == '') {
        //         toastr.error('Please enter your phone in your profile to make payment');
        //         return false;
        //     } else {
        //         checkout.open(function ( result ) {
        //         var transaction = result.transaction
        //         console.log('Transaction ID: ', transaction.id)
        //         console.log('Transaction object: ', transaction)
        //         $('#page-preloader').show();
        //         if(transaction.status == "APPROVED") {
        //             window.location.href = transaction.redirectUrl+"?id="+transaction.id;
        //         } else {
        //             $('#page-preloader').hide();
        //             toastr.error(transaction.statusMessage);
        //         }
        //         });
        //     }
        // });

        
        </script>

        <!-- Code provided by Google -->
        <div id="google_translate_element"></div>
        <script type="text/javascript">
            function googleTranslateElementInit() {
                new google.translate.TranslateElement({pageLanguage: 'en', autoDisplay: false}, 'google_translate_element'); //remove the layout
            }
        </script>
        <script src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit" type="text/javascript"></script>


        <script type="text/javascript">
            jQuery('.products_limitation').each(function(index, currentElement) {
                var limited_country = $(currentElement).attr('data-region_limitation');
                var limited_product_id = $(currentElement).attr('data-product_id');
                var country_code = $(currentElement).attr('data-region_limitation');
                var user_country = '<?php echo !empty(auth()->country_info) ? user()->country_info->country_name : "" ?? "" ?>';
                var country_code = '<?php echo !empty(auth()->country_info) ? user()->country_info->country_code : "" ?? "" ?>';
                
                if(user_country.length == 0) {
                    checkWithIPProduct(limited_country, limited_product_id);
                } else {
                    handleIpProduct(user_country, country_code, limited_country, limited_product_id, limited_product_id);
                }
            });

            function checkWithIPProduct(limited_country,limited_product_id) {
                $.getJSON("https://api.ipify.org/?format=json", function(e) {
                    
                    fetch("https://ip-api.io/json/"+e.ip, {
                        headers: { Accept: "application/json" },
                    })
                        .then((resp) => resp.json())
                        .catch(() => {
                        return {
                            country: "us",
                        };
                        })
                        .then((resp) => {
                            handleIp(resp.country_name, resp.country_code, limited_country, limited_product_id);
                        });
                });
                
            }

            function handleIpProduct(country, code, limited_country, limited_product_id) {
                var restriction_countries = limited_country;
                if(restriction_countries == '' || restriction_countries.toLowerCase() == 'region free' || restriction_countries.toLowerCase() == 'rest of the world' || restriction_countries.toLowerCase() == 'outside europe' || restriction_countries.toLowerCase() == 'other' || restriction_countries.toLowerCase() == 'other') {
                    $('#country_restriction_success_text_cart_'+limited_product_id).show();
                    $('#country_restriction_danger_text_cart_'+limited_product_id).hide();
                    $('#country_restriction_success_text_cart_'+limited_product_id).find('.YOUR_COUNTRY').text(country);
                } else {
                    var allowCountry = 0;
                    
                    $.getJSON("{{ route('api.region-countries') }}?region="+restriction_countries, function(e) {
                        var total_countries = Object.keys(e.data).length;
                        if(total_countries > 0) {
                            var counter = 0;
                            $.each(e.data, function (i) {
                                
                                if(code == i) {
                                    allowCountry = 1;
                                }
                                counter++;
                                if(total_countries == counter) {
                                    if(allowCountry == 0) {
                                        $('#country_restriction_success_text_cart_'+limited_product_id).hide();
                                        $('#country_restriction_danger_text_cart_'+limited_product_id).show();
                                        $('#country_restriction_danger_text_cart_'+limited_product_id).find('.YOUR_COUNTRY').text(country)
                                    } else {
                                        $('#country_restriction_success_text_cart_'+limited_product_id).show();
                                        $('#country_restriction_danger_text_cart_'+limited_product_id).hide();
                                        $('#country_restriction_success_text_cart_'+limited_product_id).find('.YOUR_COUNTRY').text(country)
                                    }
                                }
                            });
                        } else {
                            if(restriction_countries == country) {
                                $('#country_restriction_success_text_cart_'+limited_product_id).show();
                                $('#country_restriction_danger_text_cart_'+limited_product_id).hide();
                                $('#country_restriction_success_text_cart_'+limited_product_id).find('.YOUR_COUNTRY').text(country)
                            } else {
                                $('#country_restriction_success_text_cart_'+limited_product_id).hide();
                                $('#country_restriction_danger_text_cart_'+limited_product_id).show();
                                $('#country_restriction_danger_text_cart_'+limited_product_id).find('.YOUR_COUNTRY').text(country)
                            }
                        }
                    });
                }
                
            }

            $('.change-language').on('click', function() {
                var cookie_val = $(this).data('href');
                document.cookie = "googtrans=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
                document.cookie = "googtrans="+cookie_val+"; expires=Thu, 31 Dec {{ date('Y') }} 12:00:00 UTC;domain=digitaldeluxes.com;path=/";
                $('#page-preloader').show();
                location.reload();
            });
        </script>
        @plot('scripts')
        @include('layout/alert')
    </body>
</html>