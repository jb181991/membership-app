<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect()->route('voyager.login');
});


Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();

    /* Export to PDF  */
    Route::get('/export-customers', 'Voyager\ExportController@exportCustomers');
    Route::get('/export-orders', 'Voyager\ExportController@exportOrders');

    /* Reports */
    Route::get('/reports', 'Voyager\ReportsController@index');
    Route::post('/get-reports-data', 'Voyager\ReportsController@getReportsData');

    /* Import */
    Route::post('/import', 'Voyager\ImportController@import');
});

// Route::get('/admin', 'Voyager\ReportsController@getDashboard');