/**
 * Below code is written to submit the add new meeting popup.
 */
$('#addNewBookingForm').submit(function (e) {

    e.preventDefault();

    clearErrors();

    $('.updateAll').remove();

    let referenceBookingId = $('#reference_booking_id').val();
    let updateAll = 0;

    function createUpdateMeetings() {

        $('<input />').attr('type', 'hidden')
            .attr('name', 'updateAll')
            .attr('value', updateAll)
            .attr('class', 'updateAll')
            .appendTo('#addNewBookingForm');

        $.ajax({
            url: $('#addNewBookingForm').attr('action'),
            method: $('#addNewBookingForm').attr('method'),
            data: $('#addNewBookingForm').serialize(),

            error: function (data) {
                if (data.status === 422) {

                    if (data.responseJSON.errors != undefined) {
                        printErrorMsg(data.responseJSON.errors);
                    } else {
                        printErrorMsgAddNewRoom(data.responseJSON)
                    }
                }
            },
            success: function (data) {
                location.reload();
            }
        });

    }

    if (checkForUpdate(checkForUpdateArr)) {
        updateAll = 1;
        createUpdateMeetings(updateAll);
    } else {
        if (referenceBookingId) {
            swal({
                closeOnClickOutside: false,
                title: 'Update Meetings',
                buttons: {
                    cancel: 'No',
                    confirm: "Yes",
                },
                text: "Click on yes to update all meetings of this series or click on no to update just this meeting",
                icon: "warning",
                // dangerMode: true,
            }).then((willUpdate) => {
                if (willUpdate) {
                    updateAll = 1;
                    createUpdateMeetings(updateAll);
                } else {
                    updateAll = 0;
                    createUpdateMeetings(updateAll);
                }
            });
        } else {
            updateAll = 0;
            createUpdateMeetings(updateAll);
        }
    }
});

function checkForUpdate(checkForUpdateArr) {

    let repeatType = $('#repeat_type').val().split(calendarSettings.delimeter);
    repeatType = repeatType[0];

    //this is to adjust to the repeat_type none condition
    if (repeatType == '') {
        repeatType = null;
    }

    if (checkForUpdateArr.repeat_type != repeatType) {
        return true;
    }

    if ($('#repeat_type').val() == 'custom___custom') {

        // if (checkForUpdateArr.custom_selection == $("input[name='custom_selection']:checked").val()) {
        //     return true;
        // }

        $('.customDays:checked').each(function () {
            if ($.inArray($(this).val(), checkForUpdateArr.custom_days) == -1) {
                return true;
            }
        });
    }

    if (!$('#occurrence').prop('disabled')) {
        if (checkForUpdateArr.occurrence != $('#occurrence').val()) {
            return true;
        }
    }

    if (($('#end_date').val() != '') && !$('#end_date').prop('disabled')) {
        if (checkForUpdateArr.end_date != $('#end_date').val()) {
            return true;
        }
    }

    return false;
}

function printErrorMsgAddNewRoom(errors) {
    let errorMsg = '';
    for (let error in errors) {
        for (i in error) {
            if (errors[error][i] != undefined) {
                errorMsg += '<li class="alert alert-danger">' + errors[error][i] + '</li>';
            }
        }
    }

    $('.addNewMeetingDisplayErrors').show();
    $('.addNewMeetingDisplayErrors').append(errorMsg);
}

/**
 * TO display the validation error messages.
 */
function printErrorMsg(errors) {

    for (let i in errors) {

        let objField = $('#' + i);

        if (objField.hasClass('is-invalid')) {
            objField.next('span').remove();
        }

        objField.addClass('is-invalid');
        $(prepareErrorElement(errors[i][0])).insertAfter(objField).css('display', 'block');
    }
}

function prepareErrorElement(message) {
    return '<span class="invalid-feedback" role="alert"><strong>' + message + '</strong></span>';
}

function clearErrors() {

    $('.addNewMeetingDisplayErrors').empty();
    /**
     * To remove the previous validation error message
     */
    $('form :input').each(function (index, elm) {

        if ($(elm).hasClass('is-invalid')) {
            $(elm).next('span').remove();
            $(elm).removeClass('is-invalid');
        }
    });
}

$('.closeAddNewBookingRoomModal,.cancelBtnAddNewBookingForm').on('click', function () {

    window.location.reload();
});
