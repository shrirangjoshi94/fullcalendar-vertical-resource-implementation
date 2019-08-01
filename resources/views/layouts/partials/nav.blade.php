<nav class="navbar navbar-expand-md navbar-info bg-white shadow-sm fixed-top app-header__navbar">
    {{--<div class="app-header__logo col-md-5">--}}
        {{--<img src="./../assets/images/header-logo.jpeg" alt="logo"/>--}}
    {{--</div>--}}
    <div class="col-md-6">
        <a class="app-header__title" href="{{ url('/') }}">
            <h2><strong> {{ ('Meeting Portal') }}</strong></h2>
        </a>
    </div>
    <button class="navbar-toggler" type="button" data-toggle="collapse"
            data-target="#portalNavbar" aria-controls="portalNavbar" aria-expanded="false"
            aria-label="{{ __('Toggle navigation') }}">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="portalNavbar">
        <!-- Left Side Of Navbar -->
        <!-- Right Side Of Navbar -->
        <ul class="navbar-nav ml-auto">
            <!-- Authentication Links -->
            @guest
                <li class="nav-item">
                    <a class="nav-link " href="{{ route('login') }}"> {{ __('Login') }}</a>
                </li>
            @else
                <li class="nav-item dropdown">
                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                       data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                        {{ Auth::user()->username }} <span class="caret"></span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                            document.getElementById('logout-form').submit();">
                            {{ __('Logout') }}
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST"
                              style="display: none;">
                            @csrf
                        </form>
                    </div>
                </li>
            @endguest
        </ul>
    </div>
</nav>

