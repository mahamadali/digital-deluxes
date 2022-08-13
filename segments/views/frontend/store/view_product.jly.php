@extends('app')

@block("title") {{ setting('app.title', 'Ali Rocks!') }} @endblock
<style>
    .grey_text{
        color:#767676;
    }
</style>
@block("content")
<main class="page-main">
                <ul class="uk-breadcrumb">
                    <li><a href="{{ route('frontend.store.list') }}"><span data-uk-icon="chevron-left"></span><span>{{ trans('store.back_to_store') }}</span></a></li>
                    <li><span>{{ $product->name }}</span></li>
                </ul>
                <h3 class="uk-text-lead">{{ $product->name }}</h3>
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
                    </div>
                    <div class="uk-width-1-3@s">
                        <div class="game-profile-card">
                            <div class="game-profile-card__media"><img src="{{ $product->coverImageOriginal }}" alt="game-profile-card"></div>
                            <div class="game-profile-card__intro"><span>{{ $product->description ? $product->description : 'N/A' }}</span></div>
                            <ul class="game-profile-card__list">
                                <!-- <li>
                                    <div>Reviews:</div>
                                    <div class="game-card__rating"><span>4.7</span><i class="ico_star"></i><span class="rating-vote">(433)</span></div>
                                </li> -->
                                <li>
                                    <div>{{ trans('store.release_date') }}:</div>
                                    <div>{{ $product->releaseDate ? date("d M,Y", strtotime($product->releaseDate)) : 'N/A' }}</div>
                                </li>
                                <li>
                                    <div>{{ trans('store.developer') }}:</div>
                                    <div>{{ $product->developers ? implode(', ',json_decode($product->developers)) : 'N/A' }}</div>
                                </li>

                                <li>
                                    <div>{{ trans('store.publisher') }}:</div>
                                    <div>{{ $product->publishers ? implode(', ',json_decode($product->publishers)) : 'N/A' }}</div>
                                </li>


                                <li>
                                    <div>{{ trans('store.genres') }}:</div>
                                    <div>{{ $product->genres ? implode(', ',json_decode($product->genres)) : 'N/A' }}</div>
                                </li>

                                <li>
                                    <div>{{ trans('store.merchant_name') }}:</div>
                                    <div>{{ $product->merchantName ? implode(', ',json_decode($product->merchantName)) : 'N/A' }}</div>
                                </li>

                                <li>
                                    <div>{{ trans('store.platforms') }}:</div>
                                    <div>{{ $product->platform }}</div>
                                </li>

                                <li>
                                    <div><b>{{ trans('store.activation_details') }}:</b></div>
                                 
                                </li>

                                <li>
                                    <div></div>
                                    <div>{{ $product->activationDetails }}</div>
                                </li>
                                

                               
                            </ul>
                            
                            @if(!empty($product->systemRequirements())):
                            <div><b class="grey_text">{{ trans('store.system_requirements') }}:</b></div>

                            <ul class="game-profile-card__type">
                                <li>
                                    @foreach($product->systemRequirements() as $system_requirement):
                                    <div><b>{{ $system_requirement->system }}</b></div>
                                    <div>{{ $system_requirement->requirement ? implode(', ',json_decode($system_requirement->requirement)) : 'N/A' }}</div>
                                    @endforeach
                                </li>
                            </ul>
                            @endif

                            <ul class="game-profile-card__type">
                                @if(!empty($product->tags)):
                                    @foreach(json_decode($product->tags) as $tag):
                                        <li><span>{{ $tag }}</span></li>
                                    @endforeach    
                                @endif
                            </ul>

                            
                        </div>
                        <div class="game-profile-price">
                            <div class="game-profile-price__value">${{ $product->price }} USD</div>
                            <button class="uk-button uk-button-danger uk-width-1-1" type="button">
                                <span class="ico_shopping-cart"></span><span>{{ trans('store.buy_now') }}</span>
                            </button>
                            @if($product->isInWishlist()):
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
</script>
@endblock