@extends('app')

@block("title") {{ setting('app.title', 'Ali Rocks!') }} @endblock
@block("content")
<main class="page-main">
    <div class="uk-grid" data-uk-grid>
        <div class="uk-width">
            <div class="widjet --filters">
                <div class="widjet__head">
                    <h3 class="uk-text-lead">{{ trans('wishlist.my_wishlists') }}</h3>
                </div>
            </div>
            @foreach($wishlists as $wishlist):
            <div class="game-card --horizontal favourites-game">
                <div class="game-card__box">
                    <div class="game-card__media"><a href="{{ url('store/view/'.$wishlist->product->id) }}"><img src="{{ $wishlist->product->coverImageOriginal }}" alt="{{ $wishlist->product->name }}" /></a></div>
                    <div class="game-card__info"><a class="game-card__title" href="{{ url('store/view/'.$wishlist->product->id) }}"> {{ $wishlist->product->name }}</a>
                        <div class="game-card__genre">{{ $wishlist->product->platform }} </div>
                        
                        <div class="game-card__bottom">
                            <div class="game-card__rating-and-price">
                                <div class="game-card__rating"><i class="ico_star"></i> <span>3.9</span></div>
                                <div class="game-card__price"><span>${{ $wishlist->product->price }} </span></div>
                            </div>
                        </div>
                    </div>
                    <div class="game-card__more">
                        <a href="Javascript:void(0);" class="remove_from_fav" data-url="{{ route('frontend.store.remove-from-fav', ['product' => $wishlist->product->id]) }}" title="Remove From Wishlist"><i class="ico_trash"></i></a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</main>
@endblock

@block('scripts')
<script>
    $(document).ready(function() {
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
                    $(obj).parent().parent().parent().slideUp(500);
                }
            });
        });
    });
</script>
@endblock