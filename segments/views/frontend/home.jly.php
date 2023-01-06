@extends('app')

@block("title") {{ setting('app.title', 'Ali Rocks!') }} @endblock

@block("meta-tags")
<meta property="og:url"                content="https://digitaldeluxes.com" />
<meta property="og:type"               content="article" />
<meta property="og:title"              content="Digital Deluxes - Cheap Game CD Keys | Activation codes for Steam, Origin, Uplay, Battle.net ,Xbox, Playstation." />
<meta property="og:description"        content="Enjoy the cheapest games at now. Get CD Keys for Steam, Origin, Uplay, Battle.net, GOG, PSN and XBOX. Instant delivery." />
<meta property="og:image"              content="https://digitaldeluxes.com/assets/img/2.png" />
@endblock

@block('styles')
<style type="text/css">
    .wrapper_tabcontent .tabcontent {
       display: none !important;
    }

    .wrapper_tabcontent .tabcontent.active {
       display: block !important;
    }

    .wrapper_tabcontent .tabcontent p {
       margin-top: 20px;
    }

    .home-bottom-tabs .tabs .tablinks {
        border: none;
    }

    .home-bottom-tabs .tabs .tablinks.active {
        border-bottom: 1px solid #f46119;
    }

    .home-bottom-tabs .tabs .tablinks p {
        color: #F46119;
        font-size: 17px;
        font-style: normal;
        font-weight: 700;
        line-height: 1.31;
        transition: all 0.25s linear;
    }
    .dMWgxv {
        display: block;
        position: relative;
        margin-bottom: 3rem;
        border-radius: 1.2rem;
        overflow: hidden;
    }

    .dMWgxv a {
        background-color: transparent;
    }

    .epXOGW img {
        max-width: 100%;
    }

    .gNfMiK {
/*        position: absolute;*/
        bottom: 0px;
        width: 100%;
        min-height: 2rem;
        padding: 0rem;
        font-size: 0.8rem;
        line-height: 2rem;
        text-align: center;
        font-weight: bold;
        color: rgb(255, 255, 255);
        text-transform: uppercase;
        transition: all 0.2s ease-in-out 0s;
        background: rgb(34 33 33);
        opacity: 1
    }

    @media only screen and (max-width: 600px) {
        .home-bottom-tabs {
            margin-top: 40px;
        }
    }
</style>
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
                            @php
            if($product->manual_keys_available()->count() == 0 && $product->product_type == 'M'):
                continue;
            endif;
            @endphp
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
                            @php
            if($product->manual_keys_available()->count() == 0 && $product->product_type == 'M'):
                continue;
            endif;
            @endphp
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
                            @php
            if($product->manual_keys_available()->count() == 0 && $product->product_type == 'M'):
                continue;
            endif;
            @endphp                                                
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
        

        <div class="uk-width-1-1">
            <div class="about">
                <a class="bg_links social portfolio" href="https://www.rafaelalucas.com" target="_blank">
                    <span class="icon"></span>
                </a>
                <a class="bg_links social dribbble" href="https://dribbble.com/rafaelalucas" target="_blank">
                    <span class="icon"></span>
                </a>
                <a class="bg_links social linkedin" href="https://www.linkedin.com/in/rafaelalucas/" target="_blank">
                    <span class="icon"></span>
                </a>
                <a class="bg_links logo"></a>
            </div>
                <!-- end about -->

            <section id="wrapper" class="home-bottom-tabs">
                <div class="content">
                    <!-- Tab links -->
                    <div class="tabs">
                        <button class="tablinks active" data-country="Genre"><p data-title="Genre">Genre</p></button>
                        <button class="tablinks" data-country="Platform"><p data-title="Platform">Platform</p></button>
                        <a href="{{ route('frontend.store.list') }}?genre=Software" style="margin-left: 12px;">Software</a>
                        <a href="{{ route('frontend.store.list') }}?genre=Subscription" style="margin-left: 12px;">Subscription</a>
                    </div>

                    <!-- Tab content -->
                    <div class="wrapper_tabcontent">
                        <div id="Genre" class="tabcontent active">
                            <div class="uk-grid uk-child-width-1-6@xl uk-child-width-1-4@l uk-child-width-1-3@s uk-flex-middle uk-grid-small">
                                @foreach(productGenres() as $genre):
                                @php
                                $genreImageName = strtolower(str_replace(' ', '-', $genre));
                                $genreImageName = strtolower(str_replace('-/-', '-', $genreImageName));
                                $genreImageName = str_replace('&', 'and', $genreImageName);
                                if($genreImageName == 'co-op'):
                                $genreImageName = 'cooperative';
                                elseif($genreImageName == 'adult-games'):
                                $genreImageName = 'software';
                                endif;
                                @endphp
                                <div class="sc-svwmk5-4 epXOGW uk-width-1-6@l uk-width-1-2">
                                    <a class="sc-svwmk5-2 dMWgxv">
                                        <a href="{{ route('frontend.store.list') }}?genre={{ $genre }}">
                                            @if(str_contains($genreImageName, 'steam')):
                                            <img src="https://static.kinguin.net/media/wysiwyg/platforms/steam.jpg" alt="{{ $genre }}">
                                            @elseif(str_contains($genreImageName, 'xbox')):
                                            <img src="https://static.kinguin.net/media/wysiwyg/platforms/xbox-one.png" alt="{{ $genre }}">
                                            @else
                                            <img src="https://static.kinguin.net/media/wysiwyg/genres/{{ $genreImageName }}.jpg" alt="{{ $genre }}">
                                            @endif
                                            <div class="sc-svwmk5-3 gNfMiK">{{ $genre }}</div>
                                        </a>
                                    </a>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <div id="Platform" class="tabcontent">
                            <div class="uk-grid uk-child-width-1-6@xl uk-child-width-1-4@l uk-child-width-1-3@s uk-flex-middle uk-grid-small">
                                @foreach(platforms() as $platformInfo):
                                @php
                                $platform = $platformInfo['platform'];
                                
                                $platformImageName = strtolower(str_replace(' ', '-', $platform));
                                $platformImageName = strtolower(str_replace('.', '-', $platformImageName));
                                $platformImageName = str_replace('&', 'and', $platformImageName);
                                if($platformImageName == 'co-op'):
                                $platformImageName = 'cooperative';
                                endif;
                                @endphp
                                <div class="sc-svwmk5-4 epXOGW uk-width-1-6@l uk-width-1-2">
                                    <a class="sc-svwmk5-2 dMWgxv">
                                        <a href="{{ route('frontend.store.list') }}?category={{ $platform }}">
                                            @if($platformImageName == 'psn-card'):
                                            <img src="https://static.kinguin.net/media/wysiwyg/genres/{{ $platformImageName }}.jpg" alt="{{ $platform }}">@elseif($platformImageName == 'bethesda'):
                                            <img src="https://static.kinguin.net/media/images/other/_bethesda.png" alt="{{ $platform }}">@elseif($platformImageName == 'mog-station'):
                                            <img src="https://static.kinguin.net/media/images/other/_mogstation_light_grey.png" alt="{{ $platform }}">
                                            @elseif(str_contains($platformImageName, 'xbox')):
                                            <img src="https://static.kinguin.net/media/wysiwyg/platforms/xbox-one.png" alt="{{ $platform }}">
                                            @elseif(str_contains($platformImageName, 'epic')):
                                            <img src="https://static.kinguin.net/media/wysiwyg/platforms/epic.png" alt="{{ $platform }}">
                                            @elseif(str_contains($platformImageName, 'playstation')):
                                            <img src="https://static.kinguin.net/media/wysiwyg/platforms/playstation-4.png" alt="{{ $platform }}">
                                            @elseif(str_contains($platformImageName, 'rockstar')):
                                            <img src="https://static.kinguin.net/media/images/other/_rockstar.png" alt="{{ $platform }}">
                                            @elseif(str_contains($platformImageName, 'steam')):
                                            <img src="https://static.kinguin.net/media/wysiwyg/platforms/steam.jpg" alt="{{ $platform }}">
                                            @elseif(str_contains($platformImageName, 'windows')):
                                            <img src="https://static.kinguin.net/media/wysiwyg/genres/software.jpg" alt="{{ $platform }}">
                                            @else
                                            <img src="https://static.kinguin.net/media/wysiwyg/platforms/{{ $platformImageName }}.png" alt="{{ $platform }}">
                                            @endif
                                            
                                            <div class="sc-svwmk5-3 gNfMiK">{{ $platform }}</div>
                                        </a>
                                    </a>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</main>
@endblock

@block('scripts')
<script>
    $(document).ready(function() {
        // tabs

        var tabLinks = document.querySelectorAll(".tablinks");
        var tabContent = document.querySelectorAll(".tabcontent");


        tabLinks.forEach(function(el) {
        el.addEventListener("click", openTabs);
        });


        function openTabs(el) {
        var btnTarget = el.currentTarget;
        var country = btnTarget.dataset.country;
        console.log(country)

        tabContent.forEach(function(el) {
            el.classList.remove("active");
        });

        tabLinks.forEach(function(el) {
            el.classList.remove("active");
        });

        document.querySelector("#" + country).classList.add("active");
        
        btnTarget.classList.add("active");
        }
    })

</script>
@endblock