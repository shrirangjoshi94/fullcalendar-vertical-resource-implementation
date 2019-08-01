<!-- Scripts -->
  <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script src="{{ asset('js/style.js') }}" defer></script>
    <script src="{{ asset('js/common.js') }}" defer></script>
@stack('fullcalendar-scripts')
@stack('room-settings-scripts')
@yield('custom_scripts')