$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

let todaysDate = moment().format('YYYY-MM-DD');

let loggedInUserDetails = $.ajax({
    async: false,
    type: 'post',
    url: 'getLoggedInUser',
    success: function (data) {
        return data;
    }
});

loggedInUserDetails = loggedInUserDetails.responseJSON;

let defaultDate = $('.defaultDate').val();

let calendarSettings = $.ajax({
    async: false,
    url: 'getCalendarSettings',
    data: {
        'defaultDate': defaultDate
    },
    success: function (data) {
        return data;
    }
});

calendarSettings = calendarSettings.responseJSON;

$("#booking_date").flatpickr({
    enableTime: true,
    dateFormat: "Y-m-d",
    minDate: calendarSettings.startDate,
    "disable": [
        function (date) {
            if (calendarSettings.weekends == false) {
                return (date.getDay() === 0 || date.getDay() === 6);  // disable weekends
            }
        }
    ],
});

function endDatePicker(status) {
    $("#end_date").flatpickr({
        enableTime: true,
        dateFormat: "Y-m-d",
        clickOpens: status,
        minDate: calendarSettings.startDate,
        "disable": [
            function (date) {
                if (calendarSettings.weekends == false) {
                    return (date.getDay() === 0 || date.getDay() === 6);  // disable weekends
                }
            }
        ],
    });
}

endDatePicker(false);

let resources = $.ajax({
    async: false,
    url: 'getAllRoomsResource',
    success: function (data) {
        return data;
    }
});

resources = resources.responseJSON;

let urlParams = new URLSearchParams(location.search);
let dateFilter = urlParams.get('date');
let myMeetings = urlParams.get('myMeetings');

let meetingsEvents = $.ajax({
    async: false,
    method: 'post',
    url: 'getMeetingsEvents',
    data: {
        dateFilter: dateFilter,
        myMeetings: myMeetings,
    },
    success: function (data) {
        return data;
    }
});

meetingsEvents = meetingsEvents.responseJSON;

let checkForUpdateArr = [];

let datePickerDisplayText = new Date(calendarSettings.defaultDate).toDateString();

let calendarEl = document.getElementById('calendar');

let calendar = new FullCalendar.Calendar(calendarEl, {

    schedulerLicenseKey: 'GPL-My-Project-Is-Open-Source', //to hide the license key anchor tag
    plugins: ['resourceTimeGrid', 'interaction'],
    height: 700, //to reduce the calendar height
    nowIndicator: true,
    now: moment().format('YYYY-MM-DD HH:mm:ss'),
    displayEventTime: false,

    customButtons: {
        addNewBookingBtn: {
            text: 'New Booking',
            click: function () {

                let bookingDate = changeDateFormat(calendar.getDate());
                $('#booking_date').val(bookingDate);

                selectFirstTimeSlots();

                $('#addNewBookingModal').modal({
                    show: true
                });
            }
        },

        calendarNavigation: {
            text: datePickerDisplayText,
            click: function () {

                let btnCustom = $('.fc-calendarNavigation-button'); // name of custom  button in the generated code
                btnCustom.after('<input type="hidden" class="calendarNavigationDatePicker" />');

                $(".calendarNavigationDatePicker").flatpickr({
                    enableTime: true,
                    dateFormat: "Y-m-d",
                    "disable": [
                        function (date) {
                            if (calendarSettings.weekends == false) {
                                return (date.getDay() === 0 || date.getDay() === 6);  // disable weekends
                            }
                        }
                    ],
                    onChange: function (dateFilter) {
                        this.close();
                        dateFilter = changeDateFormat(dateFilter);
                        navigateCalendar(dateFilter);
                    }
                });

                $(".calendarNavigationDatePicker").show().focus().hide();
            }
        },
    },

    header: {
        right: ' addNewBookingBtn ,today ',
        left: ' ',
        center: 'prev,  , calendarNavigation ,  next',
    },

    allDaySlot: false,
    slotEventOverlap: false,

    defaultDate: calendarSettings.defaultDate,
    weekends: calendarSettings.weekends,
    slotDuration: calendarSettings.slotDuration,
    minTime: calendarSettings.minTime,
    maxTime: calendarSettings.maxTime,

    timeZone: 'UTC',
    defaultView: 'resourceTimeGridDay',

    resources: resources,

    events: meetingsEvents,

    eventColor: calendarSettings.eventColor,

    eventClick: function (info) {

        let meetingId = info.event.id;

        if ((loggedInUserDetails.id == info.event.extendedProps.booked_by_user_id) && checkDateValidity()) {

            $('.deleteMeetings').remove();
            let meetingDeleteBtn = $('<button />');
            $(meetingDeleteBtn).attr('class', "deleteMeetings btn btn-danger").html('Delete').appendTo('.addNewBookingFooter');

            $(meetingDeleteBtn).data('meetingId', meetingId);
            $.each(info.event.extendedProps, function (index, value) {
                $(meetingDeleteBtn).data(index, value);
            });

            editMeetings(info.event.extendedProps, meetingId);

            if ($('#start_time option:selected').prop('disabled') == true) {
                $('#start_time').find(':selected').prop('disabled', false);
            }

            if ($('#end_time option:selected').prop('disabled') == true) {
                $('#end_time').find(':selected').prop('disabled', false);
            }
        }
    },

    eventRender: function (info) {

        $(".popover").remove();

        let title = $(info.el).find('.fc-title');

        $(title).before('<b>' + info.event.extendedProps.project_name + ':</b> ');

        let content = '';
        content += 'Project name: ' + info.event.extendedProps.project_name;
        content += '| Meeting title: ' + info.event.extendedProps.meeting_title;
        content += '| Meeting description: ' + info.event.extendedProps.meeting_description;
        content += '| Meeting organised by: ' + info.event.extendedProps.user_details.username;
        content += '| Meeting start time: ' + info.event.extendedProps.start_time;
        content += '| Meeting end time: ' + info.event.extendedProps.end_time;

        $(info.el).popover({
            title: info.event.extendedProps.project_name,
            content: content,
            trigger: 'hover',
            placement: 'top',
            container: 'body',
        });
    },

    resourceRender: function (info) {

        if (info.resource.extendedProps.status) {

            let content = 'Maximum capacity : ' + info.resource.extendedProps.maximum_capacity;

            if (info.resource.extendedProps.room_description) {
                content += ' | Description : ' + info.resource.extendedProps.room_description;
            }

            let roomUtilities = info.resource.extendedProps.room_utilities;
            let avaliableRoomUtilities = '';
            if (roomUtilities.length > 0) {
                for (let i = 0; i < roomUtilities.length; i++) {
                    if (i > 0) {
                        avaliableRoomUtilities += ' & '
                    }
                    avaliableRoomUtilities += roomUtilities[i].utilities.utility_name;
                }

                content += '| Avaliable resources : ' + avaliableRoomUtilities;
            }

            $(info.el).popover({
                title: info.resource.title,
                content: content,
                trigger: 'hover',
                placement: 'top',
                container: 'body',
                backgroundColor: 'yellow',
            });
        } else {
            info.el.style.backgroundColor = '#d7d7d7';
        }
    },

    //after this related to the drag and drop functionality
    editable: true,
    droppable: false, //for external dragging and drop of events
    eventResizableFromStart: true,
    eventResourceEditable: true, //if set to false the user will not be able to switch the meetings between rooms
    eventOverlap: false,

    eventDrop: function (eventDropInfo) {

        let meetingId = eventDropInfo.event.id;

        let newResourceId = 0;
        if (eventDropInfo.newResource) {
            newResourceId = eventDropInfo.newResource._resource.id;
        }

        let newStartTime = moment.utc(eventDropInfo.event.start).format('HH:mm:00');
        let newEndTime = moment.utc(eventDropInfo.event.end).format('HH:mm:00');

        editMeetings(eventDropInfo.event.extendedProps, meetingId);

        editMeetingsNewValues(newEndTime, newStartTime, newResourceId);
    },

    eventResize: function (eventResizeInfo) {

        let meetingId = eventResizeInfo.event.id;
        let newEndTime = moment.utc(eventResizeInfo.event.end).format('HH:mm:00');

        editMeetings(eventResizeInfo.event.extendedProps, meetingId);

        editMeetingsNewValues(newEndTime);
    },

    eventAllow: function (dropLocation, draggedEvent) {

        let returnValue;

        if ((draggedEvent.extendedProps.booked_by_user_id == loggedInUserDetails.id)
            && draggedEvent.extendedProps.room_details.status
            && checkDateValidity()) {

            /**
             * Condition when booking_date is of today.
             */
            if (dateFilter == null) {

                let newStartTime = moment.utc(dropLocation.start).format('HH:mm:00');
                let newEndTime = moment.utc(dropLocation.end).format('HH:mm:00');
                let currentTime = moment().format('HH:mm:ss');

                if (currentTime >= newEndTime) {
                    returnValue = false;
                } else {
                    returnValue = true;
                }
            } else {
                returnValue = true;
            }
        }

        return returnValue;
    },

    //after this related to time slot selection functionality
    selectable: true,
    selectOverlap: false,
    selectAllow: function (selectInfo) {

        if (!selectInfo.resource.extendedProps.status) {
            return false;
        }

        if ((moment.utc(selectInfo.end).format('HH:mm:ss') < moment().format('HH:mm:ss')) &&
            (moment().format('YYYY-MM-DD') == moment(calendar.getDate()).format('YYYY-MM-DD'))) {
            return false;
        }

        return true;
    },

    select: function (selectionInfo) {

        $('#room_id').val(selectionInfo.resource.id);
        $('#booking_date').val(changeDateFormat(calendar.getDate()));
        $('#start_time').val(moment.utc(selectionInfo.start).format('HH:mm:ss'));
        $('#end_time').val(moment.utc(selectionInfo.end).format('HH:mm:ss'));

        $('#addNewBookingModal').modal({
            show: true
        });
    }
});

calendar.render();

let currentCalendarDate = moment(calendar.getDate()).format('YYYY-MM-DD');

function afterDashboardLoaded() {
    if (currentCalendarDate < todaysDate) {

        $('.fc-addNewBookingBtn-button').prop('disabled', true);
    }
}

afterDashboardLoaded();

function checkDateValidity() {
    let dateValitityStatus = true;
    if (currentCalendarDate < todaysDate) {
        dateValitityStatus = false;
    }
    return dateValitityStatus;
}


$('.fc-next-button, .fc-prev-button').click(function () {

    let dateFilter = calendar.getDate();
    dateFilter = changeDateFormat(dateFilter);

    navigateCalendar(dateFilter);
});

$('.fc-today-button').click(function () {
    window.location.href = window.location.pathname;
});

$('.customOptionSelection').change(function () {

    let endDateEnable = false;
    if ($(this).val() == 'occurrence') {
        endDateEnable = false;
    } else if ($(this).val() == 'endDate') {
        endDateEnable = true;
    }

    endDatePicker(endDateEnable);
    $('#end_date').prop('disabled', !endDateEnable);
    $('#end_date').prop('readonly', !endDateEnable);
    $('#occurrence').prop('disabled', endDateEnable);
});

$('#booking_date').change(function () {

    let selectedDate = $(this).val();
    let currentTime = moment().format('HH:mm:ss');

    $('#start_time > option').each(function () {

        if (selectedDate == calendarSettings.startDate) {

            let newTimeSlot = moment($(this).val(), 'HH:mm:ss');
            newTimeSlot.add(calendarSettings.slotDurationMinutes, 'minutes');
            newTimeSlot = newTimeSlot.format('HH:mm:ss');

            if (currentTime >= newTimeSlot) {
                $(this).prop('disabled', true);
            } else {
                $(this).prop('disabled', false)
            }
        } else {
            $(this).prop('disabled', false);
        }
    });

    $('#end_time > option').each(function () {

        if (selectedDate == calendarSettings.startDate) {

            if (currentTime >= $(this).val()) {
                $(this).prop('disabled', true);
            } else {
                $(this).prop('disabled', false)
            }
        } else {
            $(this).prop('disabled', false);
        }
    });

    selectFirstTimeSlots();
});

$(document).on('click', '.deleteMeetings', function (event) {
    event.preventDefault();

    let referenceBookingId = $('#reference_booking_id').val();
    let deleteBtn = $(this);

    swal({
        closeOnClickOutside: false,
        title: 'Are you sure you want to delete this meeting?',
        buttons: {
            cancel: true,
            confirm: "Confirm",
        },
    }).then((willDelete) => {
        if (willDelete) {
            if (referenceBookingId) {
                swal({
                    closeOnClickOutside: false,
                    title: 'Do you want to delete it\'s reference meeting also?',
                    buttons: {
                        cancel: true,
                        confirm: "Confirm",
                    },
                }).then((deleteAllMeetings) => {
                    if (deleteAllMeetings) {
                        deleteMeetings($(deleteBtn).data('meetingId'), referenceBookingId);
                    } else {
                        referenceBookingId = 0;
                        deleteMeetings($(deleteBtn).data('meetingId'), referenceBookingId);
                    }
                });
            } else {
                referenceBookingId = 0;
                deleteMeetings($(deleteBtn).data('meetingId'), referenceBookingId);
            }
        }
    });
});

function navigateCalendar(dateFilter) {
    let queryStringObj = {
        date: dateFilter,
        myMeetings: myMeetings
    };
    let redirectUrl = window.location.pathname + '?' + $.param(queryStringObj);
    window.location.href = redirectUrl;
}

function deleteMeetings(meetingId, referenceBookingId) {
    $.ajax({
        url: 'dashboard/' + meetingId,
        type: 'DELETE',
        data: {
            'id': meetingId,
            'referenceBookingId': referenceBookingId,
        },
        success: function (data) {
            location.reload();
        }
    });
}

function editMeetings(properties, meetingId) {

    fillDetailsAddNewBookingModal(properties);

    $('#addNewBookingForm').attr('action', $('#addNewBookingForm').attr('action') + '/' + meetingId);
    $('#addNewBookingForm').attr('method', 'PATCH');

    $('#addNewBookingModal').modal({
        show: true
    });

    let status;
    if (properties.booking_type == 'other') {
        status = false;
    } else {
        status = true;
    }

    bookingTypeOther(status);
}

function editMeetingsNewValues(newEndTime, newStartTime, newResourceId) {

    $('#end_time').val(newEndTime);

    if (newStartTime) {
        $('#start_time').val(newStartTime);
    }

    if (newResourceId) {
        $('#room_id').val(newResourceId);
    }

    if ($('#start_time option:selected').prop('disabled') == true) {
        $('#start_time').find(':selected').prop('disabled', false);
    }
}

/**
 *Function to pre-fill the modal while editing.
 */
function fillDetailsAddNewBookingModal(properties) {

    let status = false;

    if (properties.end_date) {
        endDatePicker(!status);
        $('#end_date').prop('disabled', status);
        $('#end_date').prop('readonly', status);
    }

    if (properties.occurrence) {
        $('#occurrence').prop('disabled', status);
        $('#occurrence').prop('readonly', status);
    }

    if (properties.custom_selection) {
        $('.customOptionSelection').prop('disabled', status);
        $('.customOptionSelection').prop('readonly', status);

        $("input[name=custom_selection][value=" + properties.custom_selection + "]").attr('checked', 'checked');
    }

    if (properties.custom_days) {

        $('.customDays').each(function () {
            if ($.inArray($(this).val(), properties.custom_days) !== -1) {
                $(this).prop('checked', true);
            }
            $(this).prop('disabled', false);
        });
    }

    $('#addNewBookingForm :input').each(function (index, elm) {

        $.each(properties, function (key, value) {
            if ((key == elm.name) && (key != 'repeat_type') && (key != 'custom_selection')) {

                $(elm).val(value);
            } else if (key == elm.name && key == 'repeat_type') {
                $.each($(elm).children(), function (key1, value1) {
                    if ($(value1).val() != '') {

                        let optionValue = $(value1).val();
                        let repeatType = optionValue.split(calendarSettings.delimeter);
                        if (value == repeatType[0]) {
                            $(value1).prop('selected', true);
                        }
                    }
                });
            }
        });
    });

    checkForUpdateArr = properties;
}

/**
 * Function to convert date into the required format.
 */
function changeDateFormat(dateFilter) {

    let d = new Date(dateFilter),
        month = '' + (d.getMonth() + 1),
        day = '' + d.getDate(),
        year = d.getFullYear();

    if (month.length < 2) month = '0' + month;
    if (day.length < 2) day = '0' + day;

    return [year, month, day].join('-');
}

/**
 * Function to select fist time slots
 */
function selectFirstTimeSlots() {
    $("#start_time").val($("#start_time option:not([disabled]):first").val());
    $("#end_time").val($("#end_time option:not([disabled]):first").val());
}

$('#repeat_type').change(function () {

    let checkBoxStatus;

    if ($(this).val() != '') {
        $('#occurrence').prop('disabled', false);
        $('#occurrence').val(1);

        let repeatType = $(this).val().split(calendarSettings.delimeter);

        if (repeatType[0] == 'custom') {
            checkBoxStatus = false;
            $('#custom_days').prop('disabled', checkBoxStatus);
            $('.customOptionSelection').prop('disabled', checkBoxStatus);

            let endDateStatus = false;
            if ($('.customOptionSelection:checked').val() == 'endDate') {
                endDateStatus = false;
            } else {
                endDateStatus = true;
            }

            endDatePicker(endDateStatus);
            $('#end_date').prop('disabled', endDateStatus);
            $('#end_date').prop('readonly', endDateStatus);
        } else {
            checkBoxStatus = true;
            $('#custom_days').prop('disabled', checkBoxStatus);
            $('.customOptionSelection').prop('disabled', checkBoxStatus);

            endDatePicker(checkBoxStatus);
            $('#end_date').prop('disabled', checkBoxStatus);
            $('#end_date').prop('readonly', checkBoxStatus);
        }

        $('.customDays').each(function () {
            $(this).prop('disabled', checkBoxStatus);
            if (checkBoxStatus) {
                $(this).prop('checked', !checkBoxStatus);
            }
        });

    } else {
        $('#occurrence').prop('disabled', true);
        $('#occurrence').val('');

        checkBoxStatus = true;
        $('#custom_days').prop('disabled', checkBoxStatus);
        $('.customDays').each(function () {
            $(this).prop('disabled', checkBoxStatus);
            if (checkBoxStatus) {
                $(this).prop('checked', !checkBoxStatus);
            }
        });
        $('.customOptionSelection').prop('disabled', checkBoxStatus);

        endDatePicker(checkBoxStatus);
        $('#end_date').prop('disabled', checkBoxStatus);
        $('#end_date').prop('readonly', checkBoxStatus);
    }
});

//below code is to display the room details on the booking form page when the user selects the rooms.
$('#room_id').change(function () {

    $('.roomDetails').css('display', 'none');
    if ($(this).val() != '') {
        $('#roomFeatures' + $(this).val()).css('display', 'block');
    }
});

//below code is to display the custom input box on selection of the other field in the booking type drop down.
$('.bookingTypeSelection').change(function () {

    let status;
    if ($(this).find(':selected').data('booking-type-key') == 'other') {
        status = false;
    } else {
        status = true;
    }

    bookingTypeOther(status);
});

function bookingTypeOther(status) {
    $('#booking_type_other').prop('disabled', status);
    $('#booking_type_other').prop('hidden', status);
}

