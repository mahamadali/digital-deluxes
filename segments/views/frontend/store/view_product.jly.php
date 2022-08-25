@extends('app')

@block("title") {{ setting('app.title', 'Ali Rocks!') }} @endblock

@block('styles')
<style>
    .grey_text{
        color:#767676;
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
                                            {{ $system_requirement->requirement ? implode(', ',json_decode($system_requirement->requirement)) : '' }}
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
                            <div class="country_restriction_success_text" style="display: none;"><i class="ico_tick-circle"></i> Can be activated in: <span class="YOUR_COUNTRY"></span></div>
                            <div class="country_restriction_danger_text" style="display: none;"><i class="ico_close-circle"></i> Cannot be activated in: <span class="YOUR_COUNTRY"></span></div>
                            <hr>
                            <div class="game-profile-price__value">€{{ $product->price }} EUR</div>
                            <a href="{{ route('frontend.cart.add',[$product->id]) }}" class="uk-button uk-button-danger uk-width-1-1 buy-now-btn">
                                <span class="ico_shopping-cart"></span><span>{{ trans('store.buy_now') }}</span>
                            </a>
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
                                    <div class="game-card__rating"><span>{{ $product->qty }} left in stock</span></div>
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
        toastr.error('Please login to check product is available for your country or not');
    } else {
        var user_country = '<?php echo !empty(auth()->country_info) ? user()->country_info->country_name : "" ?? "" ?>';
        
        if(user_country.length == 0) {
            fetch("https://ip-api.io/json/<?php echo getUserIP(); ?>", {
                headers: { Accept: "application/json" },
            })
                .then((resp) => {
                    if(resp.status == 404) {
                        toastr.error('Please add your country in your profile')
                    } else {
                        resp.json();
                    }
                } 
                )
                .catch(() => {
                return {
                    country: "us",
                };
                })
                .then((resp) => handleIp(resp.country_name));
        } else {
            handleIp(user_country);
        }
    }
    
    

        function handleIp(country) {
            var restriction_countries = '<?php echo $product->regionalLimitations ?>';
            if(restriction_countries == 'Region free') {
                $('.country_restriction_success_text').show();
                $('.country_restriction_danger_text').hide();
                $('.country_restriction_success_text').find('.YOUR_COUNTRY').text(country);
            } else {
                if(country == restriction_countries) {
                    $('.country_restriction_success_text').hide();
                    $('.country_restriction_danger_text').show();
                    $('.country_restriction_danger_text').find('.YOUR_COUNTRY').text(country)
                } else {
                    $('.country_restriction_success_text').show();
                    $('.country_restriction_danger_text').hide();
                    $('.country_restriction_success_text').find('.YOUR_COUNTRY').text(country)
                }
            }
            
        }
</script>
@endblock