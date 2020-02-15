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

    // Zipcodes
    Route::delete('zipcodes/destroy', 'ZipcodeController@massDestroy')->name('zipcodes.massDestroy');
    Route::resource('zipcodes', 'ZipcodeController');

    Route::post('connect', 'ZipcodeController@connect')->name('zipcodes.connect');
    // Route::post('test/*', ['as' => 'test', 'uses' => 'PostConfigurationsController@test']);

});



Route::get('/r', function () {
    function philsroutes()
    {
        $routeCollection = Route::getRoutes();
        echo '<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">';
        echo "<div class='container'><div class='col-md-12'><table class='table table-striped' style='width:100%'>";
        echo '<tr>';
        //  echo '<td><h4>Domain</h4></td>';
        echo "<td width='10%'><h4>HTTP Method</h4></td>";
        echo "<td width='30%'><h4>URL</h4></td>";
        echo "<td width='30%'><h4>Route</h4></td>";
        echo "<td width='30%'><h4>Corresponding Action</h4></td>";
        echo '</tr>';

        foreach ($routeCollection as $value) {
            echo '<tr>';
            //    echo '<td>lcadashboard.com</td>';
            echo '<td>' . $value->methods()[0] . '</td>';
            echo "<td><a href='" . $value->uri() . "' target='_blank'>" . $value->uri() . '</a> </td>';
            echo '<td>' . $value->getName() . '</td>';
            echo '<td>' . $value->getActionName() . '</td>';
            echo '</tr>';
        }

        echo '</table></div></div>';
        echo '<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>';
    }

    return philsroutes();
})->name('assigned-routes');
