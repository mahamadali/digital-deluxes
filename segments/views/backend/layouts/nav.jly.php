<nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
  <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
    <a class="navbar-brand brand-logo mr-5" href="{{ route('admin.dashboard') }}"><img src="{{ url('assets/img/2.png') }}" class="mr-2" alt="{{ setting('app.title') }}" title="{{ setting('app.title') }}" />{{ setting('app.title') }}</a>
    <a class="navbar-brand brand-logo-mini" href="{{ route('admin.dashboard') }}"><img src="{{ url('assets/img/2.png') }}" alt="{{ setting('app.title') }}" title="{{ setting('app.title') }}" /></a>
  </div>
  <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
    <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
      <span class="icon-menu"></span>
    </button>
    <ul class="navbar-nav navbar-nav-right">
      <li class="nav-item nav-profile dropdown">
        <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" id="profileDropdown">
          <i class="ti-user text-primary"></i> {{ auth()->first_name." ".auth()->last_name }}
        </a>
        <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown">

          <a class="dropdown-item" href="{{ route('frontend.home') }}">
            <i class="ti-globe text-primary"></i>
            Go to Site
          </a>
          <a class="dropdown-item" href="{{ route('auth.logout') }}">
            <i class="ti-power-off text-primary"></i>
            Logout 
          </a>
        </div>
      </li>
    </ul>
    <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
      <span class="icon-menu"></span>
    </button>
  </div>
</nav>