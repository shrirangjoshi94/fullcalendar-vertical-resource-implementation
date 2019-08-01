<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <!-- CSS -->
    @include('layouts.partials.css')
</head>

<body>
<div id="app">
    <header class="app__header">
        @include('layouts.partials.nav')
    </header>
    <main class="app__main">
        @if(Auth::check())
            <ul class="nav navbar-nav">
                @include('layouts.partials.sidebar')
            </ul>
        @endif
        <section class="app__page">
            <div class="row justify-content-center">
                <div class="col-md-10">
                    @if (session('success_message'))
                        <div class="alert alert-success" role="alert">
                            <b>{{ __('Message') }} </b>{{ session('success_message') }}
                        </div>
                    @endif
                    @if (session('error_message'))
                        <div class="alert alert-danger" role="alert">
                            <b>{{ __('Message') }} </b>{{ session('error_message') }}
                        </div>
                    @endif
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                </div>
            </div>
            @yield('content')
        </section>
    </main>
</div>
<!-- Scripts -->
@include('layouts.partials.scripts')
</body>

</html>