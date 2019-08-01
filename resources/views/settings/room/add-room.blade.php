@extends('layouts.app')

@section('content')
    <div class="app-saved-form">
        <div class="container">
            <div class="row justify-content-center">
                <div class="card add-form">
                    <div class="card-header  add-form__header text-center">
                        <h3><strong>{{ __('Add Room') }}</strong></h3>
                    </div>

                    <div class="card-body add-form__body">

                        <form method="POST" action="{{route('roomManager.store') }}">
                            @csrf

                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="room_name"> {{ __('Name') }}
                                        <span class="text-danger">*</span>
                                    </label>


                                    <input id="room_name"
                                           type="text"
                                           value="{{ old('room_name') }}"
                                           class="form-control{{ $errors->has('room_name') ? ' is-invalid' : '' }}"
                                           name="room_name"
                                           required autofocus>

                                    @if ($errors->has('room_name'))
                                        <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('room_name') }}</strong>
                                                </span>
                                    @endif
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="maximum_capacity"> {{ __('Maximum Capacity') }}
                                        <span class="text-danger">*</span>
                                    </label>


                                    <input id="maximum_capacity"
                                           type="number"
                                           value="{{ old('maximum_capacity') }}"
                                           class="form-control{{ $errors->has('maximum_capacity') ? ' is-invalid' : '' }}"
                                           name="maximum_capacity"
                                           min="1"
                                           required>

                                    @if ($errors->has('maximum_capacity'))
                                        <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('maximum_capacity') }}</strong>
                                                </span>
                                    @endif
                                </div>
                            </div>

                            <div class=" row">
                                <div class="form-group col-md-6">
                                    <label for="room_description"> {{ __('Room Description') }}
                                        <span class="text-danger">*</span>
                                    </label>


                                    <input id="room_description"
                                           type="text"
                                           value="{{ old('room_description') }}"
                                           value=""
                                           class="form-control{{ $errors->has('room_description') ? ' is-invalid' : '' }}"
                                           name="room_description"
                                           required>

                                    @if ($errors->has('room_description'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('room_description') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-check col-sm-6">
                                    @foreach($utilities as $utility)
                                        <div class="checkbox">
                                            <input type="checkbox" id="checkboxUtilities" name="utility[]"
                                                   value="{{ $utility->id }}">
                                            <label><span>{{ $utility->utility_name }} </span></label>
                                        </div>
                                    @endforeach

                                </div>
                            </div>


                            <div class="text-center">
                                <button type="submit" id="save-btn" class="btn btn-primary btn-lg mr-3">
                                    {{ __('Save') }}
                                </button>
                                <a href="{{ url('roomManager') }}" class="text-white btn btn-tertiary btn-lg">Cancel</a>

                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
