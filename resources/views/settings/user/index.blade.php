@extends('layouts.app')

@section('custom_css')
    <link rel="stylesheet" href="{{asset('css/jquery.dataTables.min.css')}}">
@endsection

@section('content')
<div class="">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">User Management</div>
                
                <div class="card-body">
                    <table id="userTable" class="table table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>#</th>
                                <th>User Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('custom_scripts')
    <script src="{{asset('js/jquery.dataTables.min.js')}}" defer> </script>
    <script src="{{ asset('js/libraries/sweet-alert.js') }}" defer></script>
    <script src="{{asset('js/settings/userManagement.js')}}" defer></script> {{-- load custom css --}}
@endsection
