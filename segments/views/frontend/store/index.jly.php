@extends('app')

@block("title") {{ setting('app.title', 'Ali Rocks!') }} @endblock
<link rel="stylesheet" href="{{ url('assets/frontend/css/pagination.css') }}">
<style>
    .search_btn{
        background-color: #f46119;
        color: white;
        padding: 10px;
        border: 1px solid #f46119;
        border-radius: 5%;
    }
    .add_to_cart{
        cursor: pointer;
    }
    
</style>
@block("content")
<main class="page-main">
    <div class="widjet --filters">
        <div class="widjet__head">
            <h3 class="uk-text-lead">{{ trans('store.game_store') }} {{ !empty($category) ? "(".$category.")" : '' }}</h3>
        </div>
        <div class="widjet__body">
        <form>
        <div class="uk-grid uk-child-width-1-6@xl uk-child-width-1-3@l uk-child-width-1-2@s uk-flex-middle uk-grid-small" data-uk-grid>
            <div class="uk-width-1-5">
                <select class="search-category-input-store" name="category">
                    <option value="">{{ trans('store.choose') }}</option>
                    @foreach(platforms() as $productPlatform):
                        <option value="{{ $productPlatform['platform'] }}" {{ (isset($_GET["category"]) && $_GET["category"] == $productPlatform['platform']) ? 'selected' : '' }}>{{ $productPlatform['platform'] }}</option>
                    @endforeach
                </select>
            </div>
            <div class="uk-width-1-2">
                <div class="search">
                    <div class="search__input"><i class="ico_search"></i>
                    <input type="text" class="search_name" name="name" placeholder="{{ trans('store.search') }}" value="{{ $_GET['name'] ?? '' }}"></div>
                </div>
            </div>
            <div class="uk-width-1-5" style="display: flex;">
                <select class="search-category-input-store" name="system">
                    <option value="">{{ trans('store.operating_system') }}</option>
                    @foreach($operatingSystems as $operatingSystem):
                        @if(!empty($operatingSystem->system)):
                        <option value="{{ $operatingSystem->system }}" {{ (isset($_GET["system"]) && $_GET["system"] == $operatingSystem->system) ? 'selected' : '' }}>{{ $operatingSystem->system }}</option>
                        @endif
                    @endforeach
                </select>

                <select class="search-category-input-store" name="sort_by" style="min-width: 110px;">
                    <option value="">{{ trans('store.sort_by') }}</option>
                    <option {{ (isset($_GET["sort_by"]) && $_GET["sort_by"] == "Best Match") ? 'selected' : '' }} value="Best Match">{{ trans('store.best_match') }}</option>
                    <option {{ (isset($_GET["sort_by"]) && $_GET["sort_by"] == "Newest") ? 'selected' : '' }} value="Newest">{{ trans('store.newest') }}</option>
                    <option {{ (isset($_GET["sort_by"]) && $_GET["sort_by"] == "Price Lowest") ? 'selected' : '' }} value="Price Lowest">{{ trans('store.price_lowest') }}</option>
                    <option {{ (isset($_GET["sort_by"]) && $_GET["sort_by"] == "Price Highest") ? 'selected' : '' }} value="Price Highest">{{ trans('store.price_highest') }}</option>
                </select>
            </div>
        </div>
            <div class="uk-grid uk-child-width-1-6@xl uk-child-width-1-3@l uk-child-width-1-2@s uk-flex-middle uk-grid-small" data-uk-grid>

                
               
                <div class="uk-width-1-5">
                    <select class="search-category-input-store" name="language">
                        <option value="">{{ trans('store.language') }}</option>
                        @foreach(productLanguages() as $productLanguageEach):
                            <option value="{{ $productLanguageEach }}" {{ (isset($_GET["language"]) && $_GET["language"] == $productLanguageEach) ? 'selected' : '' }}>{{ $productLanguageEach }}</option>
                        @endforeach
                    </select>
                </div>
                
                

                @if(count(productGenres()) != 0):
                <div class="uk-width-1-4">
                    <select class="search-category-input-store" name="genre">
                        <option value="">{{ trans('store.genre') }}</option>
                        @foreach(productGenres() as $productGenreEach):
                            <option value="{{ $productGenreEach }}" {{ (isset($_GET["genre"]) && $_GET["genre"] == $productGenreEach) ? 'selected' : '' }}>{{ $productGenreEach }}</option>
                        @endforeach
                    </select>
                </div>
                @endif

                <div class="uk-width-1-6 d-w-38" style="display: flex;">
                    <div class="search">
                        <div class="search__input">
                        <input type="text" name="min_price" placeholder="{{ trans('store.min_price') }}" value="{{ $_GET['min_price'] ?? '' }}"></div>
                    </div>
                    <div class="search" style="margin-left: 10px;">
                        <div class="search__input">
                        <input type="text" name="max_price" placeholder="{{ trans('store.max_price') }}" value="{{ $_GET['max_price'] ?? '' }}"></div>
                    </div>
                </div>
                
                <div class="uk-width-1-6">
                    <div class="">
                        <button class="search_btn uk-button" type="submit">{{ trans('store.search') }}</button>
                        <a class="" href="{{ route('frontend.store.list') }}">{{ trans('store.clear') }}</a>
                    </div>
                </div>
            <div>
            </form>
     
               
            </div>
            </div>
        </div>
    </div>
    <div class="uk-grid uk-child-width-1-6@xl uk-child-width-1-4@l uk-child-width-1-3@s uk-flex-middle uk-grid-small" data-uk-grid>
        @foreach($products as $product):
        <div>
            <div class="game-card">
                <div class="game-card__box">
                    <div class="game-card__media"><a href="{{ url('store/view/'.$product->id) }}"><img src="{{ $product->coverImageOriginal }}" alt="{{ $product->name }}" /></a></div>
                    <div class="game-card__info"><a class="game-card__title" href="{{ route('frontend.store.view', ['product' => $product->id]) }}"> {{ $product->name }}</a>
                        <div class="game-card__genre">
                            {{ $product->platform }}
                            @foreach($product->platform_logos()->get() as $logo):
                                <img class="platform-logos" src="{{ url($logo->path) }}">
                            @endforeach
                        </div>
                        <div class="game-card__rating-and-price">
                            <div class="game-card__price"><span>{{ currencySymbol() }}{{ $product->price }} </span></div>
                            <div class="game-card__rating add_to_cart" ><a href="{{ route('frontend.cart.add',[$product->id]) }}"><i class="ico_shopping-cart"></i></a></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="pagination_section">
        {{ $products__pagination->addClass('centered pagination-sm mt-2')->links() }}
    </div>

    
   
</main>
@endblock

@section('scripts')
<script>
    $('select[name="sort_by"]').change(function() {
        $('#page-preloader').show();
        $(this).closest('form').submit();
    });
</script>
@endsection