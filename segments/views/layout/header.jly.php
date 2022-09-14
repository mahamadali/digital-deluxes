<header class="page-header">
   <div class="page-header__inner">
         <div class="page-header__sidebar">
            <div class="page-header__menu-btn"><button class="menu-btn ico_menu is-active"></button></div>
            <div class="page-header__logo">
               <img src="{{ url('assets/img/2.png') }}" alt="logo">
               <!-- <span class="page-header__logo_text">{{ setting('app.title') }}</span> -->
            </div>
         </div>
         <div class="page-header__content">

            <div class="page-header__search wrapper">
               <form method="GET" action="{{ route('frontend.store.list') }}">
                  <div class="search search-input">
                     <select class="search-category-input" name="category">
                        <option value="">{{ trans('store.choose') }}</option>
                        @foreach(platforms() as $productPlatform):
                           <option value="{{ $productPlatform['platform'] }}" {{ (isset($_GET["category"]) && $_GET["category"] == $productPlatform['platform']) ? 'selected' : '' }}>{{ $productPlatform['platform'] }}</option>
                        @endforeach
                     </select>
                        <div class="search__input"><i class="ico_search"></i><input type="search" name="name" placeholder="{{ trans('store.search') }}" value="{{ $_GET['name'] ?? '' }}"></div>
                        <div class="search__btn"><button type="submit"><i class="ico_search"></i></button></div>
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
                     Español
                     <span uk-icon="icon: triangle-down" class="uk-icon"><svg width="20" height="20" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><polygon points="5 7 15 7 10 12"></polygon></svg></span>
                     @endif
                  </a>
                  <div uk-dropdown="mode: hover" class="uk-dropdown uk-dropdown-bottom-left" style="left: 50px; top: 42px;">
                     <ul class="uk-nav uk-dropdown-nav translation-links">
                           <li><a href="Javascript:void(0);" class="english" data-lang="English"> <img src="{{ url('assets/frontend/img/united-kingdom.png') }}" alt="profile" class="profile">English</a></li>
                           <li><a href="Javascript:void(0);" class="spanish" data-lang="Spanish"> <img src="{{ url('assets/frontend/img/spain.png') }}" alt="profile" class="profile">Español</a></li>

                     </ul>
                  </div>
               </li>

               <li class="uk-first-column currency-section">
                  <a href="#" aria-expanded="false">
                     @if(session()->get('platform_currency') == 'cop' || session()->get('platform_currency') == ''):
                     <img src="{{ url('assets/frontend/img/cop-flag.png') }}" alt="profile" class="profile">
                     COP
                     <span uk-icon="icon: triangle-down" class="uk-icon"><svg width="20" height="20" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><polygon points="5 7 15 7 10 12"></polygon></svg></span>
                     @endif
                     @if(session()->get('platform_currency') == 'eur'):
                     <img src="{{ url('assets/frontend/img/eur-flag.png') }}" alt="profile" class="profile">
                     EUR
                     <span uk-icon="icon: triangle-down" class="uk-icon"><svg width="20" height="20" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><polygon points="5 7 15 7 10 12"></polygon></svg></span>
                     @endif

                     @if(session()->get('platform_currency') == 'usd'):
                     <img src="{{ url('assets/frontend/img/usd-flag.png') }}" alt="profile" class="profile">
                     USD
                     <span uk-icon="icon: triangle-down" class="uk-icon"><svg width="20" height="20" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><polygon points="5 7 15 7 10 12"></polygon></svg></span>
                     @endif
                  </a>
                  <div uk-dropdown="mode: hover" class="uk-dropdown uk-dropdown-bottom-left" style="left: 50px; top: 42px;">
                     <ul class="uk-nav uk-dropdown-nav">
                           <li><a href="{{ route('set-currency', ['currency' => 'cop']) }}"> <img src="{{ url('assets/frontend/img/cop-flag.png') }}" alt="profile" class="profile">COP</a></li>
                           <li><a href="{{ route('set-currency', ['currency' => 'eur']) }}"> <img src="{{ url('assets/frontend/img/eur-flag.png') }}" alt="profile" class="profile">EUR</a></li>
                           <li><a href="{{ route('set-currency', ['currency' => 'usd']) }}"> <img src="{{ url('assets/frontend/img/usd-flag.png') }}" alt="profile" class="profile">USD</a></li>
                     </ul>
                  </div>
               </li>
            </ul>
               

               <ul class="uk-subnav uk-subnav-pill nav-user-options" uk-margin="">
                  <li class="uk-first-column">

                     @if(!empty(auth()->id)):
                        <a href="#" aria-expanded="false">
                           @if(!empty(user()->profile_image)):
                              <img src="{{ url(user()->profile_image) }}" alt="profile" class="profile">
                           @else
                           <img src="{{ url('assets/frontend/img/profile.png') }}" alt="profile" class="profile">
                           @endif
                           <span class="nav-user_name">Hi, {{ user()->fullName }}</span>
                           <span uk-icon="icon: triangle-down" class="uk-icon"><svg width="20" height="20" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><polygon points="5 7 15 7 10 12"></polygon></svg></span>
                           <div uk-dropdown="mode: click" class="uk-dropdown uk-dropdown-bottom-right" style="left: -54.9876px; top: 42px;">
                                 <ul class="uk-nav uk-dropdown-nav">
                                    <li><a href="{{ route('frontend.profile.index') }}">{{ trans('menu.my_account') }}</a></li>
                                    <li class="uk-nav-divider"></li>
                                    <li><a href="{{ route('frontend.logout') }}">{{ trans('menu.logout') }}</a></li>
                                 </ul>
                           </div>
                        </a>
                     @else
                        <a href="{{ route('frontend.auth.login') }}" class="login-btn" aria-expanded="false">{{ trans('menu.login') }}</a>
                     @endif
                     
                  </li>
               </ul>
            </div>
         </div>
   </div>
</header>