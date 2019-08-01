<?php

Route::get('/', function () {
    return redirect('dashboard');
});

Auth::routes(['register' => false, 'password.request' => false, 'reset' => false]);

Route::get('/home', 'HomeController@index')->name('home');

Route::middleware(['auth'])->group(function() {

    Route::resource('/dashboard', 'Dashboard\RoomBookingController', [
        'only' => ['index', 'update', 'store', 'destroy']
    ]);

    Route::get('getAllRoomsResource', 'Settings\RoomController@getAllRoomsResource');

    Route::get('getCalendarSettings', 'Settings\CalendarSettingsController@getCalendarSettings');

    Route::post('getMeetingsEvents', 'Dashboard\RoomBookingController@getMeetingsEvents');

    Route::post('getLoggedInUser', 'Settings\UserController@getLoggedInUser');

    /* User Routes */
    Route::get('users', 'Settings\UserController@index')->name('users.index');
    Route::get('getAllUsers', 'Settings\UserController@getAllUsers')->name('getAllUsers');
    Route::post('updateUserRole', 'Settings\UserController@update')->name('updateUserRole');

    /*Roles Management*/
    Route::get('roles', 'Settings\RoleController@index')->name('roles.index');
    Route::get('getAllRoles', 'Settings\RoleController@getAllRoles')->name('getAllRoles');
    Route::post('saveRole', 'Settings\RoleController@store')->name('role.store');
    Route::post('updateRole', 'Settings\RoleController@update')->name('role.edit');
    Route::delete('deleteRole/{role}', 'Settings\RoleController@destroy')->name('role.destroy');

    Route::group(['middleware' => ['can:admin-role']], function () {
        Route::resource('roomManager', 'Settings\RoomController', [
            'names' => [
                'create' => 'roomManager/createRoom',
            ]
        ]);
    });


});
