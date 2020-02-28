<?php

Route::redirect('/', '/login');
Route::get('/home', function () {
    if (session('status')) {
        return redirect()->route('admin.home')->with('status', session('status'));
    }

    return redirect()->route('admin.home');
});

Auth::routes(['register' => false]);
// Admin

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'namespace' => 'Admin', 'middleware' => ['auth']], function () {
    Route::get('/', 'HomeController@index')->name('home');
    // Permissions
    Route::delete('permissions/destroy', 'PermissionsController@massDestroy')->name('permissions.massDestroy');
    Route::resource('permissions', 'PermissionsController');

    // Roles
    Route::delete('roles/destroy', 'RolesController@massDestroy')->name('roles.massDestroy');
    Route::resource('roles', 'RolesController');

    // Users
    Route::delete('users/destroy', 'UsersController@massDestroy')->name('users.massDestroy');
    Route::resource('users', 'UsersController');

    // Time Entries
    Route::delete('time-entries/destroy', 'TimeEntriesController@massDestroy')->name('time-entries.massDestroy');
    Route::get('time-entries/show-current', 'TimeEntriesController@showCurrent')->name('time-entries.showCurrent');
    Route::post('time-entries/update-current', 'TimeEntriesController@updateCurrent')->name('time-entries.updateCurrent');
    Route::resource('time-entries', 'TimeEntriesController');

    // Reports
    Route::get('reports', 'ReportsController@index')->name('reports.index');
});
