$(document).ready(function () {
    var table = $('#roleTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '/getAllRoles'
        },
        columns: [
            {
                data: 'id',
                name: 'id',
                visible: false
            },
            {
                data: 'DT_RowIndex',
                name: 'DT_RowIndex',
                orderable: false,
                searchable: false
            },
            {
                data: 'display_name',
                name: 'display_name'
            },
            {
                data: 'action'
            }
        ],
        columnDefs: [{
            "orderable": false, //action button ordering false
            "targets": 3,
            
        }],
    });
    
    /**  
    * Action after clicking Edit Button
    * Showing edit & cancel button & loading Role dropdown 
    */
    $('#roleTable').on('click', '.editClass', function () {
        var selectedTdNameColoumn = $(this).closest('td').prev('td');
        var selectedRoleText = selectedTdNameColoumn.html();
        
        $(this).closest('td').html('<a class="updateButton btn btn-outline-success" href="#" ><i class="fa fa-check"></i></a>' +
        '<a class="cancelButton btn btn-outline-danger ml-2" href="#"><i class="fa fa-ban"></i></a>') // display update & cancel button
        selectedTdNameColoumn.html('<input type="text" class="form-control selected" name="roleName" value="'+selectedRoleText+'">');
    });
    
    /**
    * Update Role record after clicking update button
    */
    $('#roleTable').on('click', '.updateButton', function () {
        var selectedRoleName = $(this).closest('td').prev('td').find('.selected').val();
        var row = $(this).closest('tr');
        var data = $('#roleTable').dataTable().fnGetData(row);
        
        $.ajax({
            url: '/updateRole',
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                "roleId": data['id'],
                "roleName": selectedRoleName
            },
            success: function (responseData) {
                sweetAlertPositive("Role has been updated successfully") // sweet alert for positive message 
                table.ajax.reload(); // on success reload the table
            },
            error: function (json) {
                sweetAlertError(json);
            }
        });
    });
    
    /**
    * Reload Datatable after clicking cancel button
    */
    $('#roleTable').on('click', '.cancelButton', function () {
        table.ajax.reload(); // reload the table
    });
    
    // Adds a new empty row at top for adding new Role
    $('#addRoleButton').click(function () {
        $('#roleTable tbody tr:first').before('<tr>'+
        '<td>#</td>'+
        '<td><input type="text" placeholder="Enter Role Name" id="roleName" name="roleName"></td>'+
        '<td><a class="saveRoleButton btn btn-outline-success" href="#" ><i class="fa fa-save"></i></a>' +
        '<a class="cancelButton btn btn-outline-danger ml-2" href="#"><i class="fa fa-close"></i></a></td>'+
        '</tr>');
    });
    
    /**
    * Save Role record after clicking save button
    */
    $('#roleTable').on('click', '.saveRoleButton', function () {
        $.ajax({
            url: '/saveRole',
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                "roleName": $('#roleName').val(),
            },
            success: function (responseData) {
                sweetAlertPositive("Role has been saved successfully") // sweet alert for positive message 
                table.ajax.reload();
            },
            error: function (json) {
                sweetAlertError(json);
            }
        });
    });
    
    /** 
    * Confirmation box before Role Delete
    */
    $(document).on('click', '.deleteRoleButton', function (event) {
        event.preventDefault();
        swal({
            text: 'Are you sure you want to delete this role?',
            closeOnClickOutside: false,
            buttons: {
                cancel: true,
                confirm: "Confirm",
            },
        }).then((willDelete) => {
            if (willDelete) {
                $(this).parents('form').submit();
            }
        });
    });
    
    /**
    * Sweet alert for laravel validation error's 
    */
    function sweetAlertError(json){
        if (json.status === 422) { // validation error from Laravel Form request
            var errors = json.responseJSON;
            $.each(errors.errors, function (key, value) {
                swal({
                    icon: 'error',
                    title: value,
                })
            });
        } else {
            swal({
                icon: 'error',
                title: "Oops there's an issue. Please try again.",
            })
        }
    }
    
    /**
    * Sweet alert for Success operation's
    */
    function sweetAlertPositive(message) {
        swal({
            icon: 'success',
            text: message,
            showConfirmButton: false,
            timer: 2000
        })
    }
});