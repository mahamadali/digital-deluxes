@extends('backend/app')

@block("title") {{ setting('app.title', 'Quotations') }} @endblock

@block("styles")
<link
  rel="stylesheet"
  href="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.css"
/>
<style>
    .gallery {
        position: relative;
        padding: 15px;
        border-radius: 5px;
        background: #fff;
    }
</style>
@endblock

@block("content")

<div class="row">
  <div class="col-md-12 grid-margin stretch-card">
    <h4 class="card-title">{{ $product->name }}</h4>
  </div>

    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="card__info">
                    <h4 class="card-title" href="#">Product Info</h4>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="order-info-label"><span>{{ $product->name }} </span></div>
                        </div>
                        <div class="col-md-12 mt-2">
                            <p class="order-info-label"><span>{{ $product->description }} </span></p>
                        </div>
                        <div class="col-md-12 mt-2">
                            <h4 class="order-info-label"><span>€{{ $product->price }} </span></h4>
                        </div>
                    </div>

                    
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="card__info">
                    <h4 class="card-title" href="#">Gallery</h4>

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
                                <!-- If we need pagination -->
                                <div class="swiper-pagination"></div>

                                <!-- If we need navigation buttons -->
                                <div class="swiper-button-prev"></div>
                                <div class="swiper-button-next"></div>

                                <!-- If we need scrollbar -->
                                <div class="swiper-scrollbar"></div>
                            </div>
                        </div>
                        
                    </div>
                    
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="card__info">
                    <h4 class="card-title" href="#">Product Info</h4>

                    <ul class="game-profile-card__list">
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
            </div>
        </div>
    </div>

</div>

@endblock

@block('scripts')
<script src="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.js"></script>
<script>
    
    const swiper = new Swiper('.swiper', {
    // Optional parameters
    
    loop: true,
    autoHeight: true,

    // If we need pagination
    pagination: {
        el: '.swiper-pagination',
    },

    // Navigation arrows
    navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
    },

    // And if we need scrollbar
    scrollbar: {
        el: '.swiper-scrollbar',
    },
    });
</script>
@endblock