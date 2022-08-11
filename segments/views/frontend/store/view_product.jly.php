@extends('app')

@block("title") {{ setting('app.title', 'Ali Rocks!') }} @endblock

@block("content")
<main class="page-main">
                <ul class="uk-breadcrumb">
                    <li><a href="09_games-store.html"><span data-uk-icon="chevron-left"></span><span>Back to Store</span></a></li>
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
                                    <div>Release date:</div>
                                    <div>{{ $product->releaseDate ? date("d M,Y", strtotime($product->releaseDate)) : 'N/A' }}</div>
                                </li>
                                <li>
                                    <div>Developer:</div>
                                    <div>{{ $product->developers ? implode(', ',json_decode($product->developers)) : 'N/A' }}</div>
                                </li>

                                <li>
                                    <div>Publisher:</div>
                                    <div>{{ $product->publishers ? implode(', ',json_decode($product->publishers)) : 'N/A' }}</div>
                                </li>


                                <li>
                                    <div>Genres:</div>
                                    <div>{{ $product->genres ? implode(', ',json_decode($product->genres)) : 'N/A' }}</div>
                                </li>

                                <li>
                                    <div>Merchant Name:</div>
                                    <div>{{ $product->merchantName ? implode(', ',json_decode($product->merchantName)) : 'N/A' }}</div>
                                </li>

                                <li>
                                    <div>Platforms:</div>
                                    <div class="game-card__platform">{{ $product->platform }}</div>
                                </li>

                                <li>
                                    <div>Activation Details:</div>
                                    <div>{{ $product->activationDetails }}</div>
                                </li>
                                

                               
                            </ul>
                            
                            @if(!empty($product->systemRequirements())):
                            <div>System Requirement:</div>

                            <ul class="game-profile-card__type">
                                <li>
                                    @foreach($product->systemRequirements() as $system_requirement):
                                    <div class="game-card__platform">{{ $system_requirement->system }}</div>
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
                            <div class="game-profile-price__value">${{ $product->price }} USD</div><button class="uk-button uk-button-danger uk-width-1-1" type="button"><span class="ico_shopping-cart"></span><span>Buy Now</span></button><button class="uk-button uk-button-primary uk-width-1-1" type="button"><span class="ico_add-square"></span><span>Add to Favourites</span></button>
                        </div>
                    </div>
                </div>
            </main>
@endblock