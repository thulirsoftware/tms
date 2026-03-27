<?php

use App\Http\Controllers\RoleController;

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


Route::group(['middleware' => ['checkRoute'], 'prefix' => 'Admin', 'name' => 'admin'], function () {
    #Employees
    Route::get('/', 'HomeController@adminHome')->name('adminHome');
    Route::get('/Employee', 'EmployeeController@index');
    Route::get('/Employee/create', 'EmployeeController@create')->name('admin.employee.create');
    Route::get('/Employee/createintern', 'EmployeeController@createintern')->name('admin.intern.create');
    Route::post('/Employee/store', 'EmployeeController@store');
    Route::get('/Employee/{employee}/edit', 'EmployeeController@edit')->name('admin.employee.edit');
    Route::post('/Employee/{employee}/update', 'EmployeeController@update')->name('admin.employee.update');
    Route::delete('/Employee/{employee}/destroy', 'EmployeeController@destroy')->name('admin.employee.destroy');

    #Tasks
    Route::get('/Task', 'TaskController@index');
    Route::get('/Task/{employee}', 'TaskController@adminShow');
    Route::get('/Task/create/{employee}', 'TaskController@create');
    Route::post('/Task/store', 'TaskController@store');
    Route::get('/Task/{task}/edit', 'TaskController@edit');
    Route::post('/Task/{task}/update', 'TaskController@update');
    Route::delete('/Task/{task}/destroy', 'TaskController@destroy');


    #Leave
    Route::get('/Leave', 'LeaveController@index');
    Route::get('/Leavehome', 'LeaveController@leavehome')->name('admin.leavehome');
    Route::get('/Leave/{employee}/show', 'LeaveController@show');
    Route::get('/Leave/{employee}/create', 'LeaveController@create');
    Route::post('/Leave/store', 'LeaveController@store');
    Route::get('/Leave/{leave}/edit', 'LeaveController@edit');
    Route::post('/Leave/{leave}/update', 'LeaveController@update');
    Route::get('/Leave/{leave}/destroy', 'LeaveController@destroy');

    #Report
    Route::get('/Report', 'ReportController@index');
    Route::get('/Report/Employee-Report', 'ReportController@employeeReport');
    Route::get('/Report/Employee-Report-Ajax', 'ReportController@employeeReportAjax');
    Route::get('/Report/Employee-Report-Download', 'ReportController@employeeReportDownload');


    Route::get('/Report/Project-report', 'ReportController@projectReport');
    Route::get('/Report/task-report', 'ReportController@taskReport')->name('task.report');

    Route::get('/Report/{employee}/show', 'ReportController@show');
    Route::get('/Report/{employee}/create', 'ReportController@create');
    Route::post('/Report/store', 'ReportController@store');
    Route::get('/Report/{leave}/edit', 'ReportController@edit');
    Route::post('/Report/{leave}/update', 'ReportController@update');
    Route::get('/Report/{leave}/destroy', 'ReportController@destroy');
    Route::get('/snapchat', 'SnapchatController@showReport');

    #Config TAble for AJAX call
    Route::get('/ConfigTable', 'ConfigController@index');
    Route::get('/ConfigTable/{table}/show', 'ConfigController@show');
    Route::get('/ConfigTable/{table}/create', 'ConfigController@create');
    Route::post('/ConfigTable/store', 'ConfigController@store')->name('admin.configTable.store');
    Route::get('/ConfigTable/edit', 'ConfigController@edit');
    Route::post('/ConfigTable/update', 'ConfigController@update')->name('admin.configTable.update');
    Route::post('/ConfigTable/destroy', 'ConfigController@destroy')->name('admin.configTable.destroy');






    Route::get('/leave/empDetails', 'LeaveController@employeeDetails');
    Route::get('/Leave/leaveReport', 'LeaveController@leaveReport');

    Route::get('/Links', 'LinksController@ListLinks')->name('admin.links.list');
    Route::get('/Link/add', 'LinksController@Adminadd');
    Route::post('/Link/create', 'LinksController@Admincreate');
    Route::get('/Link/{id}/edit', 'LinksController@Adminedit');
    Route::post('/Link/update', 'LinksController@Adminupdate');
    Route::get('/Link/reset', 'LinksController@resetfilterAdmin')->name('admin.links.reset');

    Route::get('roles', [RoleController::class, 'index'])->name('roles.index');
    Route::get('roles/create', [RoleController::class, 'create'])->name('roles.create');
    Route::post('roles', [RoleController::class, 'store'])->name('roles.store');
    Route::get('roles/{role}/edit', [RoleController::class, 'edit'])->name('roles.edit');
    Route::put('roles/{role}', [RoleController::class, 'update'])->name('roles.update');
    Route::delete('roles/{role}', [RoleController::class, 'destroy'])->name('roles.destroy');


});