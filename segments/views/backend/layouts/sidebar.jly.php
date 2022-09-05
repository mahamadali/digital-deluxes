<nav class="sidebar sidebar-offcanvas" id="sidebar">
  <ul class="nav">
    @if (auth()->role->name == 'admin'):
      <li class="nav-item {{ (request()->matchesTo('/admin/dashboard')) ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('admin.dashboard') }}">
          <i class="icon-grid menu-icon"></i>
          <span class="menu-title">Dashboard</span>
        </a>
      </li>

      <li class="nav-item {{ (request()->matchesTo('/admin/users/*')) ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('admin.users.list') }}">
          <i class="icon-head menu-icon"></i>
          <span class="menu-title">Members</span>
        </a>
      </li>

      <li class="nav-item {{ (request()->matchesTo('/admin/products/*')) ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('admin.products.index') }}">
          <i class="ti-list menu-icon"></i>
          <span class="menu-title">Products</span>
        </a>
      </li>

      <li class="nav-item {{ (request()->matchesTo('/admin/orders/*')) ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('admin.orders.index') }}">
          <i class="ti-shopping-cart menu-icon"></i>
          <span class="menu-title">Orders</span>
        </a>
      </li>

      <li class="nav-item {{ (request()->matchesTo('/admin/transactions/*')) ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('admin.transactions.index') }}">
          <i class="ti-shopping-cart menu-icon"></i>
          <span class="menu-title">Transactions</span>
        </a>
      </li>

      <!-- <li class="nav-item {{ (request()->matchesTo('/admin/settings/*')) ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('admin.settings.list') }}">
          <i class="ti-settings menu-icon"></i>
          <span class="menu-title">Settings</span>
        </a>
      </li> -->

      <li class="nav-item {{ (Bones\Str::contains(request()->currentPage(), '/admin/settings/')) ? 'active' : '' }}">
        <a class="nav-link" data-toggle="collapse" href="#auth" aria-expanded="false" aria-controls="auth">
          <i class="icon-head menu-icon"></i>
          <span class="menu-title">Settings</span>
          <i class="menu-arrow"></i>
        </a>
        <div class="collapse {{ (Bones\Str::contains(request()->currentPage(), '/admin/settings/')) ? 'show' : '' }}" id="auth">
          <ul class="nav flex-column sub-menu">
            <li class="nav-item"> <a class="nav-link" href="{{ route('admin.settings.price-profits') }}"> Price Profits </a></li>
            <li class="nav-item"> <a class="nav-link" href="{{ route('admin.settings.list') }}"> Configurations </a></li>
            <li class="nav-item"> <a class="nav-link" href="{{ route('admin.settings.platform-logos.index') }}"> Platform Logos </a></li>
            <li class="nav-item"> <a class="nav-link" href="{{ route('admin.settings.payment-methods.index') }}"> Payment methods </a></li>
          </ul>
        </div>
      </li>

      <li class="nav-item {{ (request()->matchesTo('/admin/cms/*')) ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('admin.cms.create') }}">
          <i class="ti-list menu-icon"></i>
          <span class="menu-title">CMS</span>
        </a>
      </li>

      <li class="nav-item {{ (request()->matchesTo('/admin/blogs/*')) ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('admin.blogs.index') }}">
          <i class="ti-list menu-icon"></i>
          <span class="menu-title">Blogs</span>
        </a>
      </li>

      <li class="nav-item {{ (request()->matchesTo('/admin/support-tickets/*')) ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('admin.support-tickets.index') }}">
          <i class="ti-list menu-icon"></i>
          <span class="menu-title">Tickets</span>
        </a>
      </li>

    @endif 

    <li class="nav-item">
      <a class="nav-link" href="{{ route('auth.logout') }}">
        <i class="icon-power menu-icon"></i>
        <span class="menu-title">Logout</span>
      </a>
    </li>
  </ul>
</nav>