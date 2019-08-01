
$(document).on('click', '.deleteRooms', function (event) {

    event.preventDefault();

    swal({
        title: 'Are you sure you want to delete this room?',
        closeOnClickOutside: false,
         buttons: {
        cancel: true,
            confirm: "Confirm",
        },

    }).then((willDelete) => {
        if (willDelete) {
            $('#deleteRoomForm').submit();
        }
    });
});

$(document).on('click', '.updateRooms', function (event) {

    event.preventDefault();

    swal({
        title: 'Are you sure you want to update room details?',
        closeOnClickOutside: false,
        buttons: {
            cancel: true,
            confirm: "Confirm",
        },

    }).then((willUpdate) => {
        if (willUpdate) {
            $('#roomDetailsUpdateFrm').submit();
        }
    });
});