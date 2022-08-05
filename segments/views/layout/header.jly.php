<header class="page-header">
   <div class="page-header__inner">
         <div class="page-header__sidebar">
            <div class="page-header__menu-btn"><button class="menu-btn ico_menu is-active"></button></div>
            <div class="page-header__logo"><img src="{{ url('assets/img/2.png') }}" alt="logo"><span class="page-header__logo_text">{{ setting('app.title') }}</span></div>
         </div>
         <div class="page-header__content">
            <div class="page-header__search">
               <div class="search">
                     <div class="search__input"><i class="ico_search"></i><input type="search" name="search" placeholder="Search"></div>
                     <div class="search__btn"><button type="button"><i class="ico_microphone"></i></button></div>
               </div>
            </div>
            <div class="page-header__action">
               <a class="action-btn" href="06_chats.html"><i class="ico_message"></i><span class="animation-ripple-delay1"></span></a>
               <a class="action-btn" href="07_friends.html"><i class="ico_notification"></i><span class="animation-ripple-delay2"></span></a>
               <a class="profile" href="08_wallet.html"><img src="{{ url('assets/frontend/img/profile.png') }}" alt="profile"></a>
            </div>
         </div>
   </div>
</header>