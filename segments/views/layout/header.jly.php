<header class="page-header">
   <div class="page-header__inner">
         <div class="page-header__sidebar">
            <div class="page-header__menu-btn"><button class="menu-btn ico_menu is-active"></button></div>
            <div class="page-header__logo"><img src="{{ url('assets/img/2.png') }}" alt="logo"><span class="page-header__logo_text">{{ setting('app.title') }}</span></div>
         </div>
         <div class="page-header__content">
        
            <div class="page-header__search wrapper">
               <form action="{{ route('frontend.store.list') }}">
                  <div class="search search-input">
                        <div class="search__input"><i class="ico_search"></i><input type="search" name="name" placeholder="{{ trans('store.search') }}" value="{{ $_GET['name'] ?? '' }}"></div>
                        <!-- <div class="search__btn"><button type="button"><i class="ico_search"></i></button></div> -->
                  </div>
               </form>     
            </div>
          
            <div class="page-header__action">
            <ul class="uk-subnav uk-nav-lang  uk-subnav-pill nav-lang" uk-margin="">
               <li class="uk-first-column">
                  <a href="#" aria-expanded="false">
                     @if(session()->getLanguage() == 'en' || session()->getLanguage() == ''):
                     <img src="{{ url('assets/frontend/img/united-kingdom.png') }}" alt="profile" class="profile">
                     English
                     <span uk-icon="icon: triangle-down" class="uk-icon"><svg width="20" height="20" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><polygon points="5 7 15 7 10 12"></polygon></svg></span>
                     @endif

                     @if(session()->getLanguage() == 'es'):
                     <img src="{{ url('assets/frontend/img/spain.png') }}" alt="profile" class="profile">
                     Spain
                     <span uk-icon="icon: triangle-down" class="uk-icon"><svg width="20" height="20" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><polygon points="5 7 15 7 10 12"></polygon></svg></span>
                     @endif
                  </a>
                  <div uk-dropdown="mode: hover" class="uk-dropdown uk-dropdown-bottom-left" style="left: 50px; top: 42px;">
                     <ul class="uk-nav uk-dropdown-nav">
                           <li><a href="{{ route('set-lang', ['lang' => 'en']) }}"> <img src="{{ url('assets/frontend/img/united-kingdom.png') }}" alt="profile" class="profile">English</a></li>
                           <li><a href="{{ route('set-lang', ['lang' => 'es']) }}"> <img src="{{ url('assets/frontend/img/spain.png') }}" alt="profile" class="profile">Spain</a></li>

                     </ul>
                  </div>
               </li>
            </ul>
               <!-- <a class="action-btn" href="{{ route('frontend.cart.index') }}"><i class="ico_shopping-cart"></i><span class="animation-ripple-delay1">{{ count(auth()->cart_items()) }}</span></a> -->
               <!-- <a class="action-btn" href="07_friends.html"><i class="ico_notification"></i><span class="animation-ripple-delay2"></span></a> -->
               

               <ul class="uk-subnav uk-subnav-pill nav-user-options" uk-margin="">
                  <li class="uk-first-column">
                     <a href="#" aria-expanded="false">
                           @if(!empty(user()->profile_image)):
                              <img src="{{ url(user()->profile_image) }}" alt="profile" class="profile">
                           @else
                           <img src="{{ url('assets/frontend/img/profile.png') }}" alt="profile" class="profile">
                           @endif
                           <span class="nav-user_name">Hi, {{ user()->fullName }}</span>
                           <span uk-icon="icon: triangle-down" class="uk-icon"><svg width="20" height="20" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><polygon points="5 7 15 7 10 12"></polygon></svg></span></a>
                           <div uk-dropdown="mode: click" class="uk-dropdown uk-dropdown-bottom-right" style="left: -54.9876px; top: 42px;">
                                 <ul class="uk-nav uk-dropdown-nav">
                                    <li><a href="{{ route('frontend.profile.index') }}">My Account</a></li>
                                    <li class="uk-nav-divider"></li>
                                    <li><a href="{{ route('frontend.logout') }}">Log Out</a></li>
                                 </ul>
                           </div>
                  </li>
               </ul>
            </div>
         </div>
   </div>
</header>