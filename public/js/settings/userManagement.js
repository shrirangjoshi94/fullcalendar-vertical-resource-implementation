$(document).ready(function () {
    /**
     * Populate Data-table
     */
    var table = $('#userTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '/getAllUsers',
        },
        columns: [
            {
                data: 'id', //user id required while updating
                visible: false
            }, 
            {
                data: 'DT_RowIndex', // coloumn index
                name: 'DT_RowIndex',
                orderable: false,
                searchable: false
            },
            {
                data: 'username'
            },
            {
                data: 'email'
            },
            {
                data: 'role.display_name'
            },
            {
                data: 'action'
            }
        ],
        columnDefs: [{
            "orderable": false, //action button ordering false
            "targets": 5
        }]
    });

    /**  
    * Action after clicking Edit Button
    * Showing edit & cancel button & loading Role dropdown 
    */
    $('#userTable').on('click', '.editClass', function () {
        var selectedTd = $(this).closest('td').prev('td');
        var selectedRole =  selectedTd.html();

        $(this).html('<a class="updateButton text-success" href="#" ><i class="fa fa-check"></i></a>' +
        '<a class="cancelButton text-danger pl-3" href="#"><i class="fa fa-ban"></i></a>') // display update & cancel button
        
        $.ajax({
            url: 'getAllRoles',
            type: 'GET',
            success: function (responseData) {
                selectedTd.html('<select class="form-control selected"></select>');
                $.each(responseData.data, function (key, value) { // load dropdown with values
                    selectedTd.find(".selected").append($("<option value=" + value.id + ">" + value.display_name + "</option>"));
                    selectedTd.find(".selected option").filter(function () {
                        return selectedRole == value.display_name;
                    }).attr('selected', true);
                });
            }
        });
    });

    /**
    * Update User record after clicking update button
    */
    $('#userTable').on('click', '.updateButton', function () {
        var selectedRoleId = $(this).closest('td').prev('td').find('.selected').val();
        var row = $(this).closest('tr');
        var data = $('#userTable').dataTable().fnGetData(row);

        $.ajax({
            url: '/updateUserRole',
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                "userId": data['id'],
                "roleId": selectedRoleId
            },
            success: function (responseData) {
                swal({
                    icon: 'success',
                    text: "User Role has been updated successfully",
                    timer: 2000
                })
                table.ajax.reload(); // on success reload the table
            }
        });
    });

    /**
    * Reload Datatable after clicking cancel button
    */
    $('#userTable').on('click', '.cancelButton', function () {
        table.ajax.reload(); // reload the table
    });
});