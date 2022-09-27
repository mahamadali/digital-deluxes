@extends('app')

@block("title") {{ setting('app.title', 'Ali Rocks!') }} - {{ setting('app.description') }} @endblock

@block("meta-tags")
<meta name="title" content="Digital Deluxes">
<meta name="description" content="Cheap Game CD Keys | Activation codes for Steam, Origin, Uplay, Battle.net, Xbox, Playstation.">
<meta name="keywords" content="Digital Deluxes">
<meta name="robots" content="noindex, nofollow">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="language" content="English">

<meta property="og:title" content="{{ setting('app.title') }}">
<meta property="og:type" content="website">
<meta property="og:description" content="{{ setting('app.description') }}">
<meta property="og:image" content="{{ url('assets/img/2.png') }}">
<meta property="og:url" content="{{ setting('app.base_url') }}">
<meta name="twitter:card" content="summary_large_image">
@endblock

@block("content")
<main class="page-main">
    <div class="uk-grid" data-uk-grid>
        <div class="uk-width-2-3@l uk-width-3-3@m uk-width-3-3@s">
            <h3 class="uk-text-lead">{{ trans('homepage.recommended_featured') }}</h3>
            <div class="js-recommend">
                <div class="swiper">
                    <div class="swiper-wrapper">

                        @foreach($latest_products as $product):
                        <div class="swiper-slide">
                            <div class="recommend-slide">
                                <div class="tour-slide__box">
                                    <a href="{{ url('store/view/'.$product->id.'/'.$product->slug) }}">
                                        <img src="{{ $product->coverImageOriginal }}" alt="banner" style="height: 400px;width: 100%;object-fit: cover;filter: brightness(50%);">
                                        <div class="bottom-left">{{ $product->name }}</div>
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endforeach

                    </div>
                    <div class="swipper-nav">
                        <div class="swiper-button-prev"></div>
                        <div class="swiper-button-next"></div>
                    </div>
                    <div class="swiper-pagination"></div>
                </div>
            </div>
        </div>
        <div class="uk-width-1-3@l uk-width-3-3@m uk-width-3-3@s">
            <h3 class="uk-text-lead">{{ trans('homepage.trending_now') }}</h3>
            <div class="js-trending">
                <div class="swiper">
                    <div class="swiper-wrapper">
                        @foreach($tranding_products as $product):    
                        <div class="swiper-slide">
                            <div class="game-card --horizontal">
                                <div class="game-card__box">
                                    <div class="game-card__media"><a href="{{ url('store/view/'.$product->id.'/'.$product->slug) }}"><img src="{{ $product->coverImageOriginal }}" alt="{{ $product->name }}" /></a></div>
                                    <div class="game-card__info"><a class="game-card__title" href="{{ url('store/view/'.$product->id.'/'.$product->slug) }}"> {{ $product->name }}</a>
                                        <div class="game-card__genre">
                                        @php
                                            if(strlen($product->description) > 300){
                                                echo substr($product->description, 0,300)."...";
                                            }else{
                                                echo $product->description;
                                            }
                                        @endphp
                                        </div>
                                        <div class="game-card__rating-and-price">
                                            <!-- <div class="game-card__rating"><span>4.5</span><i class="ico_star"></i></div> -->
                                            <div class="game-card__price"><span>{{ currencySymbol() }}{{ $product->price }}</span></div>
                                            <div class="game-card__rating add_to_cart" ><a href="{{ route('frontend.cart.add',[$product->id]) }}"><i class="ico_shopping-cart"></i></a></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <div class="swipper-nav">
                        <div class="swiper-button-prev"></div>
                        <div class="swiper-button-next"></div>
                    </div>
                    <div class="swiper-pagination"></div>
                </div>
            </div>
        </div>
        <div class="uk-width-1-1">
            <h3 class="uk-text-lead">{{ trans('homepage.most_popular') }}</h3>
            <div class="js-popular">
                <div class="swiper">
                    <div class="swiper-wrapper">
                        @foreach($popular_products as $product):                                                
                        <div class="swiper-slide">
                            <div class="game-card">
                                <div class="game-card__box fixed">
                                    <div class="game-card__media"><a href="{{ url('store/view/'.$product->id.'/'.$product->slug) }}"><img src="{{ $product->coverImageOriginal }}" alt="{{ $product->name }}" /></a></div>
                                    <div class="game-card__info"><a class="game-card__title" href="{{ url('store/view/'.$product->id.'/'.$product->slug) }}"> {{ $product->name }}</a>
                                        <div class="game-card__genre">
                                        {{ $product->platform }}
                                        @foreach($product->platform_logos()->get() as $logo):
                                            <img class="platform-logos" src="{{ url($logo->path) }}">
                                        @endforeach
                                        </div>
                                        <div class="game-card__rating-and-price">
                                            <!-- <div class="game-card__rating"><span>4.8</span><i class="ico_star"></i></div> -->
                                            <div class="game-card__price"><span>{{ currencySymbol() }}{{ $product->price }} </span></div>
                                            <div class="game-card__rating add_to_cart" ><a href="{{ route('frontend.cart.add',[$product->id]) }}"><i class="ico_shopping-cart"></i></a></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <div class="swiper-button-prev"></div>
                    <div class="swiper-button-next"></div>
                    <div class="swiper-pagination"></div>
                </div>
            </div>
        </div>
    </div>
</main>
@endblock