<aside class="sidebar is-show" id="sidebar">
    <div class="sidebar-box">
        <ul class="uk-nav">
            <li class="{{ (request()->matchesTo('/home') || request()->matchesTo('/')) ? 'uk-active' : '' }}"><a href="{{ route('frontend.home') }}"><i class="ico_home"></i><span>{{ trans('menu.home') }}</span></a></li>
            <li class="uk-nav-header">{{ trans('menu.account') }}</li>
            <li class="{{ (request()->matchesTo('/profile')) ? 'uk-active' : '' }}"><a href="{{ route('frontend.profile.index') }}"><i class="ico_profile"></i><span>{{ trans('menu.profile') }}</span></a></li>
            <li class="{{ (request()->matchesTo('/wishlist')) ? 'uk-active' : '' }}"><a href="{{ route('frontend.wishlist') }}"><i class="ico_favourites"></i><span>{{ trans('menu.wishlist') }}</span></a></li>
            <li class="{{ (request()->matchesTo('/profile/orders')) ? 'uk-active' : '' }}"><a href="{{ route('frontend.profile.orders') }}"><i class="ico_shopping-cart"></i><span>{{ trans('menu.orders') }}</span></a></li>
            <li><a href="{{ route('frontend.logout') }}"><i class="ico_arrow-circle-left"></i><span>{{ trans('menu.logout') }}</span></a></li>
            <!-- <li><a href="05_favourites.html"><i class="ico_favourites"></i><span>Favourites</span><span class="count">15</span></a></li>
            <li><a href="06_chats.html"><i class="ico_chats"></i><span>Chats</span></a></li>
            <li><a href="07_friends.html"><i class="ico_friends"></i><span>Friends</span></a></li>
            <li><a href="08_wallet.html"><i class="ico_wallet"></i><span>Wallet</span></a></li> -->
            <li class="uk-nav-header">{{ trans('menu.main') }}</li>
            <li class="{{ (request()->matchesTo('/store')) ? 'uk-active' : '' }}"><a href="{{ route('frontend.store.list') }}"><i class="ico_store"></i><span>{{ trans('menu.store') }}</span></a></li>
            <!-- <li><a href="11_market.html"><i class="ico_market"></i><span>Market</span></a></li>
            <li><a href="12_streams.html"><i class="ico_streams"></i><span>Streams</span></a></li>
            <li><a href="13_community.html"><i class="ico_community"></i><span>Community</span></a></li> -->
            <!-- <li class="uk-nav-header">Support</li>
            <li><a href="#modal-report" data-uk-toggle><i class="ico_report"></i><span>Report</span></a></li>
            <li><a href="#modal-help" data-uk-toggle><i class="ico_help"></i><span>Help</span></a></li> -->
        </ul>
    </div>
</aside>