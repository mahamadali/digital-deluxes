@extends('app')

@block("title") {{ setting('app.title', 'Ali Rocks!') }} - {{ $product->name }} @endblock

@block("meta-tags")
<meta property="og:title" content="{{ $product->name }}">
<meta property="og:type" content="article">
<meta property="og:description" content="{{ setting('app.description') }}">
<meta property="og:image" content="{{ $product->featuredImage()->url_original ?? '' }}">
<meta property="og:url" content="{{ url('store/view/'.$product->id.'/'.$product->slug) }}">
<meta name="twitter:card" content="summary_large_image">
@endblock

@block('styles')
<style>
    .grey_text{
        color:#767676;
    }
    .platform-logos {
        max-height: 40px;
    }
</style>
@endblock

@block("content")
<main class="page-main">
                <ul class="uk-breadcrumb">
                    <li><a href="{{ route('frontend.store.list') }}"><span data-uk-icon="chevron-left"></span><span>{{ trans('store.back_to_store') }}</span></a></li>
                    <li><span>{{ $product->name }}</span></li>
                </ul>
                <h3 class="uk-text-lead">{{ $product->name }} 
                    @if(!empty($product->originalName)):
                    <small>({{ $product->originalName }})</small>
                    @endif
                </h3>
                <div class="uk-grid uk-grid-small" data-uk-grid>
                    <div class="uk-width-2-3@s">
                        <div class="gallery">
                            <div class="js-gallery-big gallery-big">
                                <div class="swiper">
                                    <div class="swiper-wrapper">
                                        @if(!empty($product->videos())):
                                            @foreach($product->videos() as $vedeo):
                                                <div class="swiper-slide">
                                                <iframe id="player0" width="100%" height="500" src="https://www.youtube.com/embed/{{ $vedeo->video_id }}?enablejsapi=1&html5=1&rel=0" frameborder="0" allowfullscreen iframe-video></iframe>
                                                </div>
                                            @endforeach    
                                        @endif

                                        @if(!empty($product->screenshots())):
                                            @foreach($product->screenshots() as $screenshot):
                                                <div class="swiper-slide"><img src="{{ $screenshot->url_original }}"></div>
                                            @endforeach    
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="js-gallery-small gallery-small">
                                <div class="swiper">
                                    <div class="swiper-wrapper">

                                        @if(!empty($product->videos())):
                                            @foreach($product->videos() as $vedeo):
                                                <div class="swiper-slide">
                                                    <!-- <iframe id="player0" width="100%" height="500" src="https://www.youtube.com/embed/{{ $vedeo->video_id }}?enablejsapi=1&html5=1&rel=0" frameborder="0" allowfullscreen iframe-video></iframe> -->
                                                    <img src="https://img.youtube.com/vi/{{ $vedeo->video_id }}/mqdefault.jpg" alt="{{ $vedeo->name }}">
                                                </div>
                                            @endforeach    
                                        @endif

                                        @if(!empty($product->screenshots())):
                                            @foreach($product->screenshots() as $screenshot):
                                                <div class="swiper-slide"><img src="{{ $screenshot->url_original }}"></div>
                                            @endforeach    
                                        @endif

                                    </div>
                                </div>
                                <div class="swiper-pagination"></div>
                            </div>
                        </div>

                        <div class="game-profile-card" style="margin-top: 50px;">
                            <div class="game-profile-card__title">
                                <b>{{ trans('store.activation_details') }}:</b>
                                <p>{{ $product->activationDetails }}</p>
                            </div>
                        </div>

                        @if(!empty($product->systemRequirements())):
                        <div class="game-profile-card" style="margin-top: 50px;">
                            <div class="game-profile-card__title">
                                <b>{{ trans('store.system_requirements') }}:</b>
                                <p>
                                <ul class="game-profile-card__list">
                                    <li>
                                        @foreach($product->systemRequirements() as $system_requirement):
                                        <div>
                                            <b class="grey_text">{{ $system_requirement->system }}</b>
                                            <br>
                                            @if(is_array($system_requirement->requirement)):
                                                {{ $system_requirement->requirement ? implode(', ',json_decode($system_requirement->requirement)) : '' }}
                                            @else
                                                {{ $system_requirement->requirement ? $system_requirement->requirement : '' }}
                                            @endif
                                            
                                        </div>
                                        @endforeach
                                    </li>
                                </ul>
                                </p>
                            </div>
                        </div>
                        @endif

                        @if(!empty($product->languages)):
                        <div class="game-profile-card" style="margin-top: 50px;">
                            <div class="game-profile-card__title">
                                <b>{{ trans('store.languages') }}:</b>
                                <p>
                                    <ul class="game-profile-card__type">
                                        @foreach(json_decode($product->languages) as $language):
                                            <li><span>{{ $language }}</span></li>
                                        @endforeach    
                                    </ul>
                                </p>
                            </div>
                        </div>
                        @endif

                        @if(!empty($product->tags)):
                        <div class="game-profile-card" style="margin-top: 50px;">
                            <div class="game-profile-card__title">
                                <b>{{ trans('store.tags') }}:</b>
                                <p>
                                    <ul class="game-profile-card__type">
                                        @foreach(json_decode($product->tags) as $tag):
                                            <li><span>{{ $tag }}</span></li>
                                        @endforeach    
                                    </ul>
                                </p>
                            </div>
                        </div>
                        @endif

                    </div>
                    <div class="uk-width-1-3@s">
                        <div class="game-profile-price">
                            <div class="country_restriction_success_text" style="display: none;"><i class="ico_tick-circle"></i> {{ trans('view_product.can_be_activated_in') }}: <span class="YOUR_COUNTRY"></span></div>
                            <div class="country_restriction_danger_text" style="display: none;"><i class="ico_close-circle"></i> {{ trans('view_product.cant_be_activated_in') }}: <span class="YOUR_COUNTRY"></span></div>
                            <div class="row" style="margin-top: 10px;">
                                @foreach($platformLogos as $logo):
                                    <img class="platform-logos" src="{{ url($logo->path) }}">
                                @endforeach
                            </div>
                            <hr>
                            <div class="game-profile-price__value">{{ currencySymbol() }}{{ $product->price }} {{ strtoupper(session()->get('platform_currency')) }}</div>
                            @if($productQty > 0):
                            <a style="display: none;" href="{{ route('frontend.cart.add',[$product->id]) }}" class="uk-button uk-button-danger uk-width-1-1 buy-now-btn buy-now-btn-currect">
                                <span class="ico_shopping-cart"></span><span>{{ trans('store.buy_now') }}</span>
                            </a>

                            <a style="display: none;" href="#modal-buy-now-restrication" class="uk-button uk-button-danger uk-width-1-1 buy-now-btn buy-now-btn-wrong" data-uk-toggle><span class="ico_shopping-cart"></span><span>{{ trans('store.buy_now') }}</span></a>


                            @endif
                            @if(!empty(auth()) && $product->isInWishlist()):
                            <button class="uk-button uk-button-danger uk-width-1-1 remove_from_fav" data-url="{{ route('frontend.store.remove-from-fav', ['product' => $product->id]) }}" type="button">
                                <span class="ico_favourites"></span><span>{{ trans('store.remove_from_wishlist') }}</span>
                            </button>
                            @else
                            <button class="uk-button uk-button-primary uk-width-1-1 add_to_fav" data-url="{{ route('frontend.store.add-to-fav', ['product' => $product->id]) }}" type="button">
                                <span class="ico_favourites"></span><span>{{ trans('store.add_to_wishlist') }}</span>
                            </button>
                            @endif
                            <div id="messages"></div>
                        </div>
                        
                        <div class="game-profile-card">
                            <div class="game-profile-card__media"><img src="{{ $product->coverImageOriginal }}" alt="game-profile-card"></div>
                            <div class="game-profile-card__intro"><span>{{ $product->description ? $product->description : '' }}</span></div>
                            <ul class="game-profile-card__list">
                                <!-- <li>
                                    <div>Reviews:</div>
                                    <div class="game-card__rating"><span>4.7</span><i class="ico_star"></i><span class="rating-vote">(433)</span></div>
                                </li> -->
                                <li>
                                    <div></div>
                                    <div class="game-card__rating"><span>{{ $productQty }} {{ trans('view_product.left_in_stock') }}</span></div>
                                </li>
                                <li>
                                    <div>{{ trans('store.regionalLimitations') }}:</div>
                                    <div>{{ $product->regionalLimitations }}</div>
                                </li>
                                <li>
                                    <div>{{ trans('store.release_date') }}:</div>
                                    <div>{{ $product->releaseDate ? date("d M, Y", strtotime($product->releaseDate)) : '' }}</div>
                                </li>
                                <li>
                                    <div>{{ trans('store.developer') }}:</div>
                                    <div>{{ $product->developers ? implode(', ',json_decode($product->developers)) : '' }}</div>
                                </li>

                                <li>
                                    <div>{{ trans('store.publisher') }}:</div>
                                    <div>{{ $product->publishers ? implode(', ',json_decode($product->publishers)) : '' }}</div>
                                </li>


                                <li>
                                    <div>{{ trans('store.genres') }}:</div>
                                    <div>{{ $product->genres ? implode(', ',json_decode($product->genres)) : '' }}</div>
                                </li>

                                <!-- <li>
                                    <div>{{ trans('store.merchant_name') }}:</div>
                                    <div>{{ $product->merchantName ? implode(', ',json_decode($product->merchantName)) : '' }}</div>
                                </li> -->

                                <li>
                                    <div>{{ trans('store.platforms') }}:</div>
                                    <div>{{ $product->platform }}</div>
                                </li>
                               
                            </ul>
                            
                            
                        </div>
                    </div>
                </div>
            </main>
@endblock

@block('scripts')
<script>
    $(document).ready(function() {
        $(document).on('click', '.add_to_fav', function() {
            var obj = $(this);
            $.ajax({
                url : $(this).data('url'),
                type : 'POST',
                data: {
                    'prevent_csrf_token': '{{ prevent_csrf_token() }}'
                },
                dataType: 'json',
                success: function(response) {
                    $('#messages').html('<p align="center" style="color:green;">'+response.message+'</p>');
                    $(obj).after('<button class="uk-button uk-button-danger uk-width-1-1 remove_from_fav" data-url="{{ route('frontend.store.remove-from-fav', ['product' => $product->id]) }}" type="button"><span class="ico_favourites"></span><span>Remove from Wishlist</span></button>');
                    $(obj).remove();
                },
                error: function() {
                    toastr.error('Please login!');
                }
            });
        });

        $(document).on('click', '.remove_from_fav', function() {
            var obj = $(this);
            $.ajax({
                url : $(this).data('url'),
                type : 'POST',
                data: {
                    'prevent_csrf_token': '{{ prevent_csrf_token() }}'
                },
                dataType: 'json',
                success: function(response) {
                    $('#messages').html('<p align="center" style="color:green;">'+response.message+'</p>');
                    $(obj).after('<button class="uk-button uk-button-primary uk-width-1-1 add_to_fav" data-url="{{ route('frontend.store.add-to-fav', ['product' => $product->id]) }}" type="button"><span class="ico_favourites"></span><span>Add to Wishlist</span></button>');
                    $(obj).remove();
                }
            });
        });
    });

    var loggedInUser = '<?php echo !empty(auth()) ? user()->id : '' ?? "" ?>';

    if(loggedInUser == '') {
        checkWithIP();
    } else {
        var user_country = '<?php echo !empty(auth()->country_info) ? user()->country_info->country_name : "" ?? "" ?>';
        var country_code = '<?php echo !empty(auth()->country_info) ? user()->country_info->country_code : "" ?? "" ?>';
        
        if(user_country.length == 0) {
            checkWithIP();
        } else {
            handleIp(user_country, country_code);
        }
    }

    function checkWithIP() {
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
                    handleIp(resp.country_name, resp.country_code);
                });
        });
        
    }

    
        function handleIp(country, code) {
            var restriction_countries = '<?php echo $product->regionalLimitations ?>';
            
            if(restriction_countries.toLowerCase() == 'region free' || restriction_countries.toLowerCase() == 'rest of the world' || restriction_countries.toLowerCase() == 'outside europe' || restriction_countries.toLowerCase() == 'other' || restriction_countries.toLowerCase() == 'other') {
                var restriction_countries = '<?php echo $forspecificregion ?>';
                if(restriction_countries != '') {
                    checkWithRegion(restriction_countries, code, country);
                } else {
                    $('.country_restriction_success_text').show();
                    $('.country_restriction_danger_text').hide();
                    $('.country_restriction_success_text').find('.YOUR_COUNTRY').text(country);

                    $('.buy-now-btn-currect').show();
                    $('.buy-now-btn-wrong').hide();
                }
                
            } else {
                checkWithRegion(restriction_countries, code, country);
            }
            
        }

        function checkWithRegion(restriction_countries, code, country) {
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
                                    $('.country_restriction_success_text').hide();
                                    $('.country_restriction_danger_text').show();
                                    $('.country_restriction_danger_text').find('.YOUR_COUNTRY').text(country);

                                    $('.buy-now-btn-currect').hide();
                                    $('.buy-now-btn-wrong').show();
                                } else {
                                    $('.country_restriction_success_text').show();
                                    $('.country_restriction_danger_text').hide();
                                    $('.country_restriction_success_text').find('.YOUR_COUNTRY').text(country);

                                    $('.buy-now-btn-currect').show();
                                    $('.buy-now-btn-wrong').hide();
                                }
                            }
                        });
                    } else {
                        if(restriction_countries == country) {
                            $('.country_restriction_success_text').show();
                            $('.country_restriction_danger_text').hide();
                            $('.country_restriction_success_text').find('.YOUR_COUNTRY').text(country);

                            $('.buy-now-btn-currect').show();
                            $('.buy-now-btn-wrong').hide();
                        } else {
                            $('.country_restriction_success_text').hide();
                            $('.country_restriction_danger_text').show();
                            $('.country_restriction_danger_text').find('.YOUR_COUNTRY').text(country);

                            $('.buy-now-btn-currect').hide();
                            $('.buy-now-btn-wrong').show();
                        }
                    }
                });
        }


        $('.close_buynow_restrication_popup').click(function(){
            $('#modal-buy-now-restrication').removeClass('uk-open');
        })

        
        $('.buy_now_current_choice').click(function(){
            $('#modal-buy-now-restrication').removeClass('uk-open');
            // $('.buy-now-btn-currect').trigger('click');
            window.location.href="{{ route('frontend.cart.add',[$product->id]) }}";
        })
</script>
@endblock