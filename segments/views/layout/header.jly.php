<header class="page-header">
   <div class="page-header__inner">
         <div class="page-header__sidebar">
            <div class="page-header__menu-btn"><button class="menu-btn ico_menu is-active"></button></div>
            <div class="page-header__logo"><img src="{{ url('assets/img/2.png') }}" alt="logo"><span class="page-header__logo_text">{{ setting('app.title') }}</span></div>
         </div>
         <div class="page-header__content">
        
            <div class="page-header__search">
               <form action="{{ route('frontend.store.list') }}">
                  <div class="search">
                        <div class="search__input"><i class="ico_search"></i><input type="search" name="name" placeholder="Search" value="{{ $_GET['name'] ?? '' }}"></div>
                        <!-- <div class="search__btn"><button type="button"><i class="ico_search"></i></button></div> -->
                  </div>
               </form>     
            </div>
          
            <div class="page-header__action">
               <!-- <a class="action-btn" href="{{ route('frontend.cart.index') }}"><i class="ico_shopping-cart"></i><span class="animation-ripple-delay1">{{ count(auth()->cart_items()) }}</span></a> -->
               <a class="action-btn" href="07_friends.html"><i class="ico_notification"></i><span class="animation-ripple-delay2"></span></a>
               <a class="profile" href="{{ route('frontend.profile.index') }}">
               @if(!empty($user->profile_image)):
                  <img src="{{ url($user->profile_image) }}" alt="profile">
               @else
               <img src="{{ url('assets/frontend/img/profile.png') }}" alt="profile">
               @endif
                  
               </a>
            </div>
         </div>
   </div>
</header>