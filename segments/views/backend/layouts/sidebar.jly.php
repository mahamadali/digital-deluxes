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

      <li class="nav-item {{ (request()->matchesTo('/admin/settings/*')) ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('admin.settings.list') }}">
          <i class="ti-settings menu-icon"></i>
          <span class="menu-title">Settings</span>
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