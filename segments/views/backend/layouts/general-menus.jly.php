<li class="nav-item {{ (Bones\Str::contains(request()->currentPage(), '/units/')) ? 'active' : '' }}">
    <a class="nav-link" data-toggle="collapse" href="#units" aria-expanded="false" aria-controls="units">
        <i class="ti-view-list menu-icon"></i>
        <span class="menu-title">Unit</span>
        <i class="menu-arrow"></i>
    </a>
    <div class="collapse {{ (Bones\Str::contains(request()->currentPage(), '/units/')) ? 'show' : '' }}" id="units">
        <ul class="nav flex-column sub-menu">
        <li class="nav-item"> <a class="nav-link" href="{{ route('units.create') }}"> Add </a></li>
        <li class="nav-item"> <a class="nav-link" href="{{ route('units.list') }}"> Units </a></li>
        </ul>
    </div>
</li>