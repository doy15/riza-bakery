<aside id="sidebar-wrapper">
    <div class="sidebar-brand">
        <a href="{{ route('index') }}">Riza Bakery</a>
    </div>
    <div class="sidebar-brand sidebar-brand-sm">
        <a href="{{ route('index') }}">RB</a>
    </div>
    <ul class="sidebar-menu">
        <li class="menu-header">Dashboard</li>
        {{-- <li class="dropdown active">
            <a href="#" class="nav-link has-dropdown"><i class="fas fa-fire"></i><span>Dashboard</span></a>
            <ul class="dropdown-menu">
                <li><a class="nav-link" href="index-0.html">General Dashboard</a></li>
                <li class=active><a class="nav-link" href="index.html">Ecommerce Dashboard</a></li>
            </ul>
        </li> --}}
        <li class="{{ Request::routeIs('index') ? 'active' : '' }}"><a class="nav-link" href="{{ route('index') }}"><i
                    class="fas fa-fire"></i> <span>Dashboard</span></a>
        </li>

        @if (Auth::user()->role == 'admin')
            <li class="menu-header">Pages</li>
            <li class="{{ Request::routeIs('material.index', 'material.create', 'material.edit') ? 'active' : '' }}"><a
                    class="nav-link" href="{{ route('material.index') }}"><i class="fas fa-flask"></i>
                    <span>Material</span></a></li>
            <li class="{{ Request::routeIs('line.index', 'line.create', 'line.edit') ? 'active' : '' }}"><a
                    class="nav-link" href="{{ route('line.index') }}"><i class="fas fa-cog"></i>
                    <span>Lines</span></a></li>
            <li class="{{ Request::routeIs('shift.index', 'shift.create', 'shift.edit') ? 'active' : '' }}"><a
                    class="nav-link" href="{{ route('shift.index') }}"><i class="fas fa-calendar"></i>
                    <span>Shift</span></a></li>
            <li class="{{ Request::routeIs('user.index', 'user.create', 'user.edit') ? 'active' : '' }}"><a
                    class="nav-link" href="{{ route('user.index') }}"><i class="fas fa-users"></i>
                    <span>Users</span></a></li>
        @endif
    </ul>
</aside>
