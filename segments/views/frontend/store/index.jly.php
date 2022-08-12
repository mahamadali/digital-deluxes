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
            <h3 class="uk-text-lead">Games Store</h3>
        </div>
        <div class="widjet__body">
        <form>
            <div class="uk-grid uk-child-width-1-6@xl uk-child-width-1-3@l uk-child-width-1-2@s uk-flex-middle uk-grid-small" data-uk-grid>

                
                <div class="uk-width-1-1">
                    <div class="search">
                        <div class="search__input"><i class="ico_search"></i>
                        <input type="search" name="name" placeholder="Search" value="{{ $_GET['name'] ?? '' }}"></div>
                    </div>
                </div>

                <div class="uk-width-1-3">
                    <div class="search">
                        <div class="search__input">
                        <input type="text" name="min_price" placeholder="Min Price" value="{{ $_GET['min_price'] ?? '' }}"></div>
                    </div>
                </div>

                <div class="uk-width-1-3">
                    <div class="search">
                        <div class="search__input">
                        <input type="text" name="max_price" placeholder="Max Price" value="{{ $_GET['max_price'] ?? '' }}"></div>
                    </div>
                </div>

                
                <div class="uk-width-1-3">
                    <div class="">
                             <button class="search_btn" type="submit">Search</button>
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
                    <div class="game-card__info"><a class="game-card__title" href="{{ url('frontend/store/view/'.$product->id) }}"> {{ $product->name }}</a>
                        <div class="game-card__genre">{{ $product->platform }}</div>
                        <div class="game-card__rating-and-price">
                            <div class="game-card__price"><span>${{ $product->price }} </span></div>
                            <div class="game-card__rating add_to_cart" ><a href="{{ route('frontend.cart.add',[$product->id]) }}"><i class="ico_shopping-cart"></i></a></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach

        

        @php

        $limit = $product_limit;
        $total_pages = $products__pagination->totalPages;
        $last       = $products__pagination->totalPages;
        $page = !empty($_GET['page']) ? $_GET['page'] : 1;
        $pn = $page;
        $start      = ( ( $pn - $limit ) > 0 ) ? $pn - $limit : 1;
        $end        = ( ( $pn + $limit ) < $last ) ? $pn + $limit : $last;

        if($start == $end) {
          $pagination = '';
        }
        else
        {
          if(isPageWithQueryString()) {
              $jointAs = currentPageFullURL(['store','page']).'&';
          } else {
              $jointAs = '?';
          }
          $pagination = '<div class="text-center">';
          $pagination.= '<ul class="pagination pagination-sm mt-2" style="display: inline-block;">';
          $class      = ( $pn == 1 ) ? "disabled" : "";
          $pagination       .= '<li class="' . $class . '">';
          if($class != 'disabled') {
            $pagination       .= '<li class="' . $class . '"><a href="'.$jointAs.'page=' . ( $pn - 1 ) . '">&laquo;</a></li>';
          } else {
            $pagination .= '<a href="Javascript:void(0);">&laquo;</a>'; 
          }
          $pagination       .= '</li>';
        
        
          if ( $start > 1 ) {
            $pagination   .= '<li><a href="'.$jointAs.'page=1">1</a></li>';
            $pagination   .= '<li class="disabled"><span>...</span></li>';
          }
        
          for ( $i = $start ; $i <= $end; $i++ ) {
            $class  = ( $pn == $i ) ? "active" : "";
            $pagination   .= '<li class="' . $class . '"><a href="'.$jointAs.'page=' . $i . '">' . $i . '</a></li>';
          }
        
          if ( $end < $last ) {
            $pagination   .= '<li class="disabled"><span>...</span></li>';
            $pagination   .= '<li><a href="'.$jointAs.'page=' . $last . '">' . $last . '</a></li>';
          }
        
          $class      = ( $pn == $last ) ? "disabled" : "";
          $pagination       .= '<li class="' . $class . '">';
          if($class != 'disabled') {
            $pagination .= '<a href="'.$jointAs.'page=' . ( $pn + 1 ) . '">&raquo;</a>'; 
          } else {
            $pagination .= '<a href="Javascript:void(0);">&raquo;</a>'; 
          }
          $pagination       .= '</li>';
          $pagination       .= '</ul>';
          $pagination .= '</div>';
        }
        

        @endphp


    </div>

    <div class="pagination_section">
        <?php echo $pagination; ?>
    </div>
   
</main>
@endblock