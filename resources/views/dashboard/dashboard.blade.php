@extends('layouts.app')

@section('content')
    <div class="container-fluid">

        <div id='calendar'>
        </div>
    </div>

    @push('fullcalendar-styles')
        <link href="{{ asset('css/fullcalendar/core-main.min.css') }}" rel="stylesheet">
        <link href="{{ asset('css/fullcalendar/timegrid-main.min.css') }}" rel="stylesheet">
        <link href="{{ asset('css/libraries/flatpickr.css') }}" rel="stylesheet">
        <link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
    @endpush

    @push('fullcalendar-scripts')
        <script src="{{ asset('js/libraries/sweet-alert.js') }}" defer></script>
        <script src="{{ asset('js/libraries/jquery-ui.js') }}" defer></script>
        <script src="{{ asset('js/libraries/popper.min.js') }}" defer></script>
        <script src="{{ asset('js/libraries/moment.js') }}" defer></script>
        <script src="{{ asset('js/libraries/flatpickr.js') }}" defer></script>

        <script src="{{ asset('js/fullcalendar/core-main.min.js') }}" defer></script>
        <script src="{{ asset('js/fullcalendar/interaction.main.min.js') }}" defer></script>
        <script src="{{ asset('js/fullcalendar/daygrid-main.min.js') }}" defer></script>
        <script src="{{ asset('js/fullcalendar/timegrid-main.min.js') }}" defer></script>
        <script src="{{ asset('js/fullcalendar/resource-common-main.min.js') }}" defer></script>
        <script src="{{ asset('js/fullcalendar/resource-daygrid-main.min.js') }}" defer></script>
        <script src="{{ asset('js/fullcalendar/resource-timegrid-main.min.js') }}" defer></script>

        <script src="{{ asset('js/fullcalendar/calendar-display-logic.js') }}" defer></script>
        <script src="{{ asset('js/fullcalendar/dashboard.js') }}" defer></script>
    @endpush

    @include('dashboard.booking.addNewBooking')

@endsection

