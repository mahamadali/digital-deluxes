@extends('app')

@block("title") {{ setting('app.title', 'Ali Rocks!') }} @endblock

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
                                    <a href=""><img src="{{ $product->coverImageOriginal }}" alt="banner" style="height: 400px;width: 100%;object-fit: cover;"></a>
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
                                    <div class="game-card__media"><a href="10_game-profile.html"><img src="{{ $product->coverImageOriginal }}" alt="{{ $product->name }}" /></a></div>
                                    <div class="game-card__info"><a class="game-card__title" href="10_game-profile.html"> {{ $product->name }}</a>
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
                                            <div class="game-card__price"><span>${{ $product->price }}</span></div>
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
                                <div class="game-card__box">
                                    <div class="game-card__media"><a href="10_game-profile.html"><img src="{{ $product->coverImageOriginal }}" alt="{{ $product->name }}" /></a></div>
                                    <div class="game-card__info"><a class="game-card__title" href="10_game-profile.html"> {{ $product->name }}</a>
                                        <div class="game-card__genre">
                                        {{ $product->platform }}
                                        </div>
                                        <div class="game-card__rating-and-price">
                                            <!-- <div class="game-card__rating"><span>4.8</span><i class="ico_star"></i></div> -->
                                            <div class="game-card__price"><span>${{ $product->price }} </span></div>
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