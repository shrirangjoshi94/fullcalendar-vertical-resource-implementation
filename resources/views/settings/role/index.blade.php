@extends('layouts.app')

@section('custom_css')
    <link rel="stylesheet" href="{{asset('css/jquery.dataTables.min.css')}}">
@endsection

@section('content')
    @if(session()->has('message'))
    <div class="alert alert-success" id="wrap-message">
        {{ session()->get('message') }}
    </div>
    @endif
    @if(session()->has('error'))
    <div class="alert alert-danger" id="wrap-message">
        {{ session()->get('error') }}
    </div>
    @endif
    <div class="row justify-content-center" id="test">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    {{ __('Role Management')}} 
                    <span class="pull-right">
                        <button type="button" id="addRoleButton" class="btn btn-outline-secondary">
                             <i class="fa fa-user-plus"></i> {{ __('Add Role')}}
                        </button>
                    </span>
                </div>
                
                <div class="card-body">
                    <table id="roleTable" class="table table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th>{{ __('Id')}} </th>
                                <th>{{ __('#')}} </th>
                                <th>{{ __('Name')}} </th>
                                <th>{{ __('Actions')}} </th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('custom_scripts')
    <script src="{{asset('js/jquery.dataTables.min.js')}}" defer> </script>
    <script src="{{ asset('js/libraries/sweet-alert.js') }}" defer></script>
    <script src="{{asset('js/settings/roleManagement.js')}}" defer></script> {{-- load custom css --}}
@endsection
