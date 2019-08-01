<div class="modal fade" data-backdrop="static" data-keyboard="false" id="addNewBookingModal" tabindex="-1" role="dialog"
     aria-labelledby="addNewBookingModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modalAddNewBooking" role="document">
        <div class="modal-content">

            <div class="addNewMeetingDisplayErrors">
            </div>

            <div class="modal-header">
                <h5 class="modal-title" id="addNewBookingModalLabel">{{ __('Add New Meeting') }}</h5>

                <button type="button" class="close closeAddNewBookingRoomModal" data-backdrop="static"
                        data-keyboard="false" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="addNewBookingForm" method="post" action="{{ url('dashboard') }}" novalidate>
                    {{ csrf_field() }}

                    <input type="hidden" class="defaultDate" value="{{ $calendarSettings['defaultDate'] }}">
                    <input type="hidden" id="reference_booking_id" name="reference_booking_id">

                    <div class="row">

                        <div class="form-group col-md-6">
                            <label for="booking_date"
                                   class="col-md-5 col-form-label text-md-right">{{ __('Booking Date') }}
                                <span class="text-danger">*</span>
                            </label>
                            <div class="col-md-6">
                                <input id="booking_date"
                                       type="date"
                                       class="form-control"
                                       name="booking_date"
                                       value="{{ old('booking_date') }}"
                                       min="{{ $calendarSettings['startDate'] }}"
                                       placeholder="YYYY-MM-DD"
                                       required>
                            </div>
                        </div>

                        <div class="form-group col-md-6">
                            <label for="end_date"
                                   class="col-md-4 col-form-label text-md-right">{{ __('End Date') }}
                            </label>
                            <div class="col-md-6">
                                <input id="end_date"
                                       type="date"
                                       class="form-control"
                                       name="end_date"
                                       value="{{ old('end_date') }}"
                                       min="{{ $calendarSettings['startDate'] }}"
                                       placeholder="YYYY-MM-DD"
                                       disabled>
                            </div>
                        </div>

                    </div>

                    <div class="form-group row">
                        <label for="custom_days"
                               class="col-md-4 col-form-label text-md-right">{{ __('Select custom days') }}
                        </label>
                        <div class="col-md-6">
                            @forelse($calendarSettings['dayList'] as $key => $value)
                                <input type="checkbox" class="customDays" name="custom_days[]" value="{{ $key }}"
                                       disabled> {{ $value }}
                            @empty
                            @endforelse

                            <input type="text" id="custom_days" style="display: none">

                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="customOptionSelection"
                               class="col-md-4 col-form-label text-md-right">{{ __('Select option') }}
                        </label>
                        <div class="col-md-6">
                            
                            <input type="radio"
                                   class="customOptionSelection"
                                   value="occurrence"
                                   name="custom_selection"
                                   checked
                                   disabled>With Occurrence

                            <input type="radio"
                                   class="customOptionSelection"
                                   value="endDate"
                                   name="custom_selection"
                                   disabled>With End Date

                            <div id="customOptionSelection"></div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="repeat_type"
                                   class="col-md-4 col-form-label text-md-right">{{ __('Repeat') }}
                            </label>
                            <div class="col-md-6">
                                <select id="repeat_type" name="repeat_type" class="form-control">
                                    <option value="">{{ __('None') }}</option>

                                    @forelse($calendarSettings['repeatTypes'] as $key => $value)
                                        <option value="{{ $key.$calendarSettings['delimeter'].$value }}">{{ ucfirst($key) }}</option>
                                    @empty
                                    @endforelse

                                </select>
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="occurrence"
                                   class="col-md-4 col-form-label text-md-right">{{ __('Occurrence') }}
                            </label>

                            <div class="col-md-6">
                                <input id="occurrence"
                                       type="number"
                                       class="form-control"
                                       name="occurrence"
                                       min="1"
                                       value="{{ old('occurrence') }}"
                                       disabled>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="start_time" class="col-md-4 col-form-label text-md-right">{{ __('Start Time') }}
                                <span class="text-danger">*</span>
                            </label>
                            <div class="col-md-6">
                                <select
                                        id="start_time"
                                        name="start_time"
                                        class="form-control"
                                        required>

                                    @forelse($calendarSettings['start_time'] as $key => $value)
                                        <option value="{{ $value }}"
                                                @isset($calendarSettings['current_active_time'])
                                                @if(strtotime($calendarSettings['current_active_time']) > (strtotime($value) + $calendarSettings['slotDurationMinutes']*60))
                                                disabled
                                                @endif
                                                @endisset
                                        >{{ $value }}</option>
                                    @empty
                                    @endforelse
                                </select>
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="end_time" class="col-md-4 col-form-label text-md-right">{{ __('End Time') }}
                                <span class="text-danger">*</span>
                            </label>
                            <div class="col-md-6">
                                <select
                                        id="end_time"
                                        name="end_time"
                                        class="form-control"
                                        required>

                                    @forelse($calendarSettings['end_time'] as $key => $value)
                                        <option value="{{ $value }}"
                                                @isset($calendarSettings['current_active_time'])
                                                @if(strtotime($calendarSettings['current_active_time']) >= strtotime($value))
                                                disabled
                                                @endif
                                                @endisset
                                        >{{ $value }}</option>
                                    @empty
                                    @endforelse

                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-6">

                            <label for="booking_type_id"
                                   class="col-md-5 col-form-label text-md-right">{{ __('Booking Type') }}
                                <span class="text-danger">*</span>
                            </label>

                            <div class="col-md-6">
                                <select id="booking_type" name="booking_type"
                                        class="form-control bookingTypeSelection" required>
                                    <option value=""
                                            data-booking-type-key="">
                                        {{ __('Please select a booking type') }}
                                    </option>
                                    @forelse($bookingTypes as $bookingType)
                                        <option
                                                value="{{ $bookingType->booking_type_key }}"
                                                data-booking-type-key="{{ $bookingType->booking_type_key  }}">
                                            {{ $bookingType->booking_type}}
                                        </option>
                                    @empty
                                    @endforelse
                                </select>

                                <input id="booking_type_other"
                                       type="text"
                                       class="form-control bookingTypeOther"
                                       name="booking_type_other"
                                       required
                                       disabled
                                       hidden>

                            </div>

                        </div>

                        <div class="form-group col-md-6">
                            <label for="room_id"
                                   class="col-md-4 col-form-label text-md-right">{{ __('Room Name') }}
                                <span class="text-danger">*</span>
                            </label>

                            <div class="col-md-6">
                                <select id="room_id" name="room_id" class="form-control roomNames" required>
                                    <option value="">Select Room</option>

                                    @forelse($rooms as $key => $room)

                                        <option
                                                @if(!$room->status)
                                                disabled
                                                @endif
                                                value="{{ $room->id }}">
                                            {{ $room->room_name }}
                                        </option>
                                    @empty
                                    @endforelse
                                </select>

                                @forelse($rooms as $key => $room)
                                    <div class="roomDetails" id="roomFeatures{{ $room->id }}">

                                        <div>
                                            {{ __('Maximum capacity:') }} {{ $room->maximum_capacity }}
                                            @if (isset($room->roomUtilities) && !empty($room->roomUtilities))
                                                @php($count = 0)
                                                @foreach($room->roomUtilities as $roomUtility)
                                                    @if($count > 0)
                                                        {{ __('&') }}
                                                    @endif
                                                    {{ $roomUtility->utilities->utility_name }}
                                                    @php($count++)
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                @empty
                                @endforelse

                            </div>
                        </div>
                    </div>

                    <div class="row">


                        <div class="form-group col-md-6">
                            <label for="project_name"
                                   class="col-md-5 col-form-label text-md-right"> {{ __('Project Name') }}
                                <span class="text-danger">*</span>
                            </label>

                            <div class="col-md-6">
                                <input id="project_name"
                                       type="text"
                                       class="form-control"
                                       name="project_name"
                                       value="{{ old('project_name') }}"
                                       required>
                            </div>
                        </div>

                        <div class="form-group col-md-6">
                            <label for="meeting_title"
                                   class="col-md-4 col-form-label text-md-right"> {{ __('Meeting Title') }}
                                <span class="text-danger">*</span>
                            </label>

                            <div class="col-md-6">
                                <input id="meeting_title"
                                       type="text"
                                       class="form-control"
                                       name="meeting_title"
                                       value="{{ old('meeting_title') }}" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="meeting_description"
                               class="col-md-3 col-form-label text-md-right">{{ __('Agenda') }}
                            <span class="text-danger">*</span>
                        </label>

                        <div class="col-md-6">
                                <textarea id="meeting_description"
                                          type="text"
                                          class="form-control"
                                          name="meeting_description"
                                          value="{{ old('meeting_description') }}" required>
                                </textarea>
                        </div>
                    </div>

                    <div class="modal-footer addNewBookingFooter">
                        <button type="button" class="btn btn-secondary cancelBtnAddNewBookingForm"
                                data-dismiss="modal">{{ __('Cancel') }}</button>
                        <button type="submit" id="addNewBooking" class="btn btn-info">
                            {{ __('Save') }}
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>

</div>
