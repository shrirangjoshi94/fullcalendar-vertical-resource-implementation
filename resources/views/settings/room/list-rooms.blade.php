@extends('layouts.app')

@section('content')
    <div class="container my-3">
        <div class="row justify-content-center">
            <div class="success-messgage col-md-8">
                <header class="page__header container">
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <h1 class="page__title"><strong>Room Manager</strong></h1>
                        </div>
                    </div>
                </header>
            </div>
        </div>
    </div>

    <div class="page__content container">
        <h3 class="saved-room__title"><strong>Saved Rooms</strong></h3>
        <div class="row">
            <div class="col-6">
                <div class="saved-room">
                    <ul class="saved-room__list">
                        @forelse($rooms as $room)
                            <li class="saved-room-list__item">
                                <a href="{{ url('roomManager?roomId='.$room->id) }}"
                                   class="d-block saved-room-list__item__link text-capitalize">
                                    {{ $room->room_name }}
                                </a>
                            </li>
                        @empty
                        @endforelse
                    </ul>
                </div>
            </div>

            <div class="col-6">
                @isset($roomDetails->id)
                    <div class="card saved-room__edit-form">
                        <div class="card-body">

                            <form method="POST" id="roomDetailsUpdateFrm" action="{{ url('roomManager/'.$roomDetails->id ) }}">

                                {{csrf_field()}}
                                {{ method_field('PATCH') }}

                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label for="roomName"> {{ __('Room Name') }}
                                            <span class="text-danger">*</span>
                                        </label>
                                        <input id="roomName"
                                               type="text"
                                               value="{{ old('room_name', $roomDetails->room_name) }}"
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
                                        <label for="maximumCapacity">{{ __('Maximum Capacity') }}
                                            <span class="text-danger">*</span>
                                        </label>
                                        <input id="maximumCapacity"
                                               type="number"
                                               value="{{ old('maximum_capacity', $roomDetails->maximum_capacity) }}"
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

                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label for="roomDescription">{{ __('Room Description') }}
                                            <span class="text-danger">*</span>
                                        </label>
                                        <input id="roomDescription"
                                               type="text"
                                               value="{{ old('room_description', $roomDetails->room_description) }}"
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
                                    <div class="form-check">
                                        @forelse($utilities as $utility)
                                            <div class="form-check">
                                                <input type="checkbox"
                                                       class="form-check-input"
                                                       id="checkboxUtilities"
                                                       name="utility[]"
                                                       value="{{ $utility->id }}"
                                                       @forelse($roomDetails->roomUtilities as $roomUtilities)
                                                       @if($roomUtilities->utility_id == $utility->id) checked @endif
                                                @empty
                                                        @endforelse
                                                >
                                                <label>
                                                    <span>{{ $utility->utility_name }} </span>
                                                </label>
                                            </div>
                                        @empty
                                        @endforelse
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="form-check col-sm-6">

                                        <label for="status"> {{ __('Status') }}:
                                            <span class="text-danger">*</span>
                                        </label>


                                        <input type="radio" value="1"
                                               @if(old('status', $roomDetails->status) == '1') checked @endif
                                               name="status"> Active
                                        <input type="radio" value="0"
                                               @if(old('status', $roomDetails->status) == '0') checked @endif
                                               name="status"> Inactive

                                        @if ($errors->has('status'))
                                            <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('status') }}</strong>
                                                </span>
                                        @endif
                                    </div>
                                </div>
                            </form>

                            <div class="row justify-content-center ">
                                <button type="submit" id="save-btn" class="btn btn-secondary btn-lg mr-3 updateRooms">
                                    {{ __('Update') }}
                                </button>


                                <form method="POST" id="deleteRoomForm" action="{{ url('roomManager/'.$roomDetails->id) }}">
                                    {{csrf_field()}}
                                    {{ method_field('DELETE') }}
                                    <button type="submit" class="btn btn-tertiary btn-lg deleteRooms">
                                        {{ __('Delete') }}
                                    </button>
                                </form>


                            </div>
                        </div>
                    </div>

                @endisset
            </div>
        </div>

        <footer class="text-center">
            <div class="add-form__footer">
                <a class="text-white btn btn-secondary btn-lg btn-block" href="{{ url('roomManager/createRoom') }}">Add Room</a>
            </div>

    </footer>


    @push('room-settings-scripts')
        <script src="{{ asset('js/libraries/sweet-alert.js') }}" defer></script>
        <script src="{{ asset('js/settings/room.js') }}" defer></script>
    @endpush

@endsection
