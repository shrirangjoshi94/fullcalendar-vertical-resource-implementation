<aside class="app-main__sidebar bg-primary">
    <ul class="sidebar-menu list-unstyled">
        <li class="sidebar-menu__item active">
            <a href="{{ url('/') }}" class="sidebar-menu__item-link sidebar-link--js ">
                <i class="fa fa-2x fa-home"></i>
                <span class="sr-only">(current)</span>
            </a>
        </li>
        <li class="dropdown sidebar-menu sidebar-list__item">
            <a href="#" class="sidebar-menu__item-link" data-toggle="dropdown">
                <i class="fa fa-2x fa-list"></i>
            </a>
            <ul class="dropdown-menu sidebar-menu__item__submenu">
                <li class="dropdown-item">
                    <a href="{{ url('dashboard?myMeetings=show') }}">My Meeting</a>
                </li>
                @can('admin-role')
                    <li class="dropdown-item">
                        <a href="{{ route('users.index') }}">Manange Users</a>
                    </li>
                    <li class="dropdown-item">
                        <a href="{{ route('roles.index') }}">Manange Roles</a>
                    </li>
                @endcan
            </ul>
        </li>
        @can('admin-role')
            <li class="sidebar-menu__item">
                <a href="{{ url('roomManager') }}" class="sidebar-menu__item-link">
                    <i class="fa fa-2x  fa-wrench"></i>
                </a>
            </li>

        @endcan
        <li class="sidebar-icon__bottom sidebar-list__item">
            <a 
                class="sidebar-menu__item-link" 
                href="{{ route('logout') }}" 
                onclick="event.preventDefault();
                document.getElementById('logout-form').submit();">
                <i class="fa fa-2x fa-power-off "></i>
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST"
                  style="display: none;">
                @csrf
            </form>
        </li>
    </ul>
</aside>
