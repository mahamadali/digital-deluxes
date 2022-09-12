
<div class="mb-top-search-section widjet__body">
    <form method="GET" action="{{ route('frontend.store.list') }}">
        <div class="search search-input">
            <select class="search-category-input" name="category">
            <option value="">{{ trans('store.choose') }}</option>
            @foreach(platforms() as $productPlatform):
                <option value="{{ $productPlatform['platform'] }}" {{ (isset($_GET["category"]) && $_GET["category"] == $productPlatform['platform']) ? 'selected' : '' }}>{{ $productPlatform['platform'] }}</option>
            @endforeach
            </select>
            <div class="search__input"><input type="search" class="search_name_mb" name="name" placeholder="{{ trans('store.search') }}" value="{{ $_GET['name'] ?? '' }}"></div>
            <div class="search__btn"><button type="submit"><i class="ico_search"></i></button></div>
        </div>
    </form>
</div>