<?php

use App\Http\Controllers\LeaveController;
use App\Http\Controllers\NotificationController;

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
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ClientProjectController;
use App\Http\Controllers\ClientServiceController;

Route::prefix('clients')->group(function () {
    Route::get('/', [ClientController::class, 'index'])->name('clients.index');
    Route::post('/store', [ClientController::class, 'store'])->name('clients.store');
    Route::get('/{id}/view', [ClientController::class, 'view'])->name('clients.view');
    Route::post('/{id}/update', [ClientController::class, 'update'])->name('clients.update');
    Route::delete('/{id}', [ClientController::class, 'destroy'])->name('clients.destroy');

    // Projects
    Route::post('/{client}/projects/store', [ClientProjectController::class, 'store'])->name('clients.projects.store');
    Route::post('/projects/{id}/update', [ClientProjectController::class, 'update'])->name('clients.projects.update');
    Route::delete('/projects/{id}', [ClientProjectController::class, 'destroy'])->name('clients.projects.destroy');

    // Services
    Route::post('/{client}/services/store', [ClientServiceController::class, 'store'])->name('clients.services.store');
    Route::post('/services/{id}/update', [ClientServiceController::class, 'update'])->name('clients.services.update');
    Route::delete('/services/{id}', [ClientServiceController::class, 'destroy'])->name('clients.services.destroy');
});


Route::get('/', 'HomeController@myHome');


Auth::routes();


Route::post('/login', 'Auth\LoginController@login')->name('user.login');

Route::post('/logout', 'Auth\LoginController@logout')->name('logout');
#Employees
Route::get('/home', 'EmployeeController@index');

Route::get('/Employee', 'EmployeeController@employeeIndex')->name('Employee');
// Route::get('/Employee/create', 'EmployeeController@create');
Route::post('/Employee/store', 'EmployeeController@store');
Route::get('/Employee/{employee}/edit', 'EmployeeController@edit');
Route::get('/Employee/{employee}/edit-employee', 'EmployeeController@editEmployee');
Route::post('/Employee/{employee}/update', 'EmployeeController@update');
// Route::get('/Employee/{employee}/destroy', 'EmployeeController@destroy');

#Tasks
Route::get('/Task', 'TaskController@show');
Route::get('/Task/{employee}/create', 'TaskController@create')->name('task_create');
Route::post('/Task/store', 'TaskController@store');
Route::get('/Task/{task}/edit', 'TaskController@edit');
Route::post('/Task/{task}/update', 'TaskController@update');
Route::delete('/Task/{task}/destroy', 'TaskController@destroy')->name('tasks.destroy');


#TeamTasks
Route::get('/TeamTask', 'TaskController@teamShow')->name('teamTask');
Route::get('/TeamTask/{employee}', 'TaskController@teamTaskShow')->name('teamTaskShow');

#Regular Tasks
Route::get('/RegularTask', 'RegularTaskController@show');
Route::get('/RegularTask/{employee}/create', 'RegularTaskController@create');
Route::post('/RegularTask/store', 'RegularTaskController@store');
Route::get('/RegularTask/{task}/edit', 'RegularTaskController@edit');
Route::post('/RegularTask/{task}/update', 'RegularTaskController@update');
Route::get('/RegularTask/{task}/destroy', 'RegularTaskController@destroy');


// Route::get('/Task/{employee}/{purpose}/lunch', 'TaskController@interruptTask');
// Route::get('/Task/{employee}/{purpose}/break', 'TaskController@interruptTask');
// Route::get('/Task/{employee}/{purpose}/meeting', 'TaskController@interruptTask');

#Leave
Route::get('/Leave', 'LeaveController@leavehome');
Route::get('/Leaverequest', 'LeaveController@leaveRequest');
Route::get('/Leave/{employee}/show', 'LeaveController@show')->name('leave_show');
Route::get('/Leave/{employee}/create', 'LeaveController@create');
Route::get('/Leave-permission/{employee}/create-permission', 'LeaveController@createPermission');
Route::post('/Leave/store', 'LeaveController@store');
Route::post('/permission/store', 'LeaveController@storepermission');
Route::get('/Leave/{leave}/edit', 'LeaveController@edit');
Route::post('/Leave/{leave}/update', 'LeaveController@update');
Route::get('/Leave/{leave}/destroy', 'LeaveController@destroy');
Route::post('/permission/approve', [LeaveController::class, 'approvePermission'])->name('ApprovePermission');
Route::post('/permission/decline', [LeaveController::class, 'declinePermission'])->name('DeclinePermission');


#Report
Route::get('/Report', 'ReportController@index');
Route::get('/Report/{employee}/show', 'ReportController@show');
Route::get('/Report/email', 'ReportController@email');
Route::post('/Report/store', 'ReportController@store');
Route::get('/Report/{leave}/edit', 'ReportController@edit');
Route::post('/Report/{leave}/update', 'ReportController@update');
Route::get('/Report/{leave}/destroy', 'ReportController@destroy');

Route::get('/Report/Filter', 'ReportController@ReportsFilter');

#Ajax Route
Route::post('/Ajax/SetStartTime', 'AjaxController@setStartTime')->name('SetStartTime');
Route::post('/Ajax/SetEndTime', 'AjaxController@setEndTime')->name('SetEndTime');
Route::post('/Ajax/ApproveTask', 'AjaxController@approveTask')->name('ApproveTask');
Route::post('/Ajax/DeclineTask', 'AjaxController@declineTask')->name('DeclineTask');
Route::post('/Ajax/SetInterrupt', 'AjaxController@setInterrupt')->name('SetInterrupt');
Route::post('/Ajax/SetMeetingInterrupt', 'AjaxController@SetMeetingInterrupt')->name('SetMeetingInterrupt');
Route::post('/Ajax/SetFilter', 'AjaxController@setFilter')->name('SetFilter');
Route::get('/Ajax/ResetFilter', 'AjaxController@resetFilter')->name('ResetFilter');

Route::get('/Ajax/CheckNewNotification', 'AjaxController@checkNewNotification')->name('CheckNewNotification');


Route::post('/Ajax/ApproveLeave', 'AjaxController@approveLeave')->name('ApproveLeave');
Route::post('/Ajax/DeclineLeave', 'AjaxController@declineLeave')->name('DeclineLeave');
Route::post('/Ajax/DeclineLeave1', 'AjaxController@declineLeave1')->name('DeclineLeave1');
Route::post('/Ajax/AddLeave', 'AjaxController@addLeave')->name('AddLeave');
Route::post('/Ajax/ReduceLeave', 'AjaxController@reduceLeave')->name('ReduceLeave');
Route::post('/Ajax/CasualLeave', 'AjaxController@casualLeave')->name('CasualLeave');

Route::post('/Ajax/addCL', 'AjaxController@addCL')->name('addCL');
Route::post('/Ajax/decCL', 'AjaxController@decCL')->name('decCL');


//leave module filters
Route::post('/Ajax/SetEmployeeLeave', 'AjaxController@SetEmployeeLeave')->name('SetEmployeeLeave');

Route::get('/employeeLeave', 'LeaveController@searchEmp');



Route::delete('/notifications/{id}', 'AjaxController@deleteNotification')->name('notifications.destroy');
Route::delete('/notifications/deleteAll', 'AjaxController@deleteNotificationAll')->name('notifications.markAllRead');

Route::get('/Links', 'LinksController@list')->name('links.list');
Route::get('/Link/add', 'LinksController@add')->name('links.add');
Route::post('/Link/create', 'LinksController@create');
Route::get('/Link/{id}/edit', 'LinksController@edit');
Route::post('/Link/update', 'LinksController@update');

Route::get('/Link/{id}/delete', 'LinksController@delete');
Route::get('/Link/search', 'LinksController@search')->name('links.search');
Route::get('/Link/reset', 'LinksController@resetfilter')->name('links.reset');





Route::get('/Echo/Session', function () {
    dd(Session::get('task'));
});
Route::get('/user/remove/Session', function () {
    Session::put('segment', null);
    Session::put('domain', null);
    return redirect('/user/dashboard');
});
Route::get('/clear', function () {
    Artisan::call('view:clear');
    Artisan::call('config:cache');
    Artisan::call('config:clear');
    Artisan::call('cache:clear');
    echo 'done';
});
Route::get('test', function () {
    event(new App\Events\TaskTracker('Bala', 'added New Task Plz Accept'));
    return "Event has been sent!";
});

Route::get('check', function () {
    return view('notify');
});
Route::get('/MarkAsRead', function () {
    Auth::user()->unreadNotifications->markAsRead();
    Session::put('notificationCount', 0);
})->name('MarkAsRead');



Route::group(['prefix' => 'user'], function () {
    Route::get('/dashboard', 'User\DashboardController@list')->name('dashboard');

    Route::get('/dashboard/filter', 'User\DashboardController@filter')->name('dashboard.filter');

    Route::get('/getData', 'User\LeadsController@getData')->name('leads.getdata');

    Route::get('/configs', 'User\CommonController@configs')->name('configs');

    Route::get('/leadsvers2/getData', 'User\LeadsVersion2Controller@getData')->name('leads.ver2.getdata');

    Route::group(['prefix' => 'leads'], function () {


        Route::get('/list', 'User\LeadsController@list')->name('leads.list');
        Route::get('/add', 'User\LeadsController@add')->name('leads.add');
        Route::post('/create', 'User\LeadsController@create')->name('leads.create');
        Route::get('/edit/{id}', 'User\LeadsController@edit')->name('leads.edit');
        Route::post('/update', 'User\LeadsController@update')->name('leads.update');

        Route::get('/resetfilter', 'User\LeadsController@resetfilter')->name('leads.filter.reset');

        Route::get('/delete/{id}', 'User\LeadsController@delete')->name('leads.delete');

    });

    Route::group(['prefix' => 'followup'], function () {

        Route::get('/list', 'User\FollowUpController@list')->name('followup.list');
        Route::get('/add/{id}', 'User\FollowUpController@add')->name('followup.add');
        Route::post('/create', 'User\FollowUpController@create')->name('followup.create');
        Route::get('/edit/{id}', 'User\FollowUpController@edit')->name('followup.edit');
        Route::post('/update', 'User\FollowUpController@update')->name('followup.update');

        Route::get('/delete/{id}', 'User\FollowUpController@delete')->name('followup.delete');
    });

    Route::group(['prefix' => 'leads/ver2'], function () {


        Route::get('/list', 'User\LeadsVersion2Controller@list')->name('leads.ver2.list');
        Route::get('/add', 'User\LeadsVersion2Controller@add')->name('leads.ver2.add');
        Route::post('/create', 'User\LeadsVersion2Controller@create')->name('leads.ver2.create');
        Route::get('/edit/{id}', 'User\LeadsVersion2Controller@edit')->name('leads.ver2.edit');
        Route::post('/update', 'User\LeadsVersion2Controller@update')->name('leads.ver2.update');

        Route::get('/resetfilter', 'User\LeadsVersion2Controller@resetfilter')->name('leads.ver2.filter.reset');

        Route::get('/delete/{id}', 'User\LeadsVersion2Controller@delete')->name('leads.ver2.delete');

        Route::get('/myleads', 'User\LeadsVersion2Controller@myleadslist')->name('leads.ver2.myleads');


    });

    Route::group(['prefix' => 'followup/ver2'], function () {

        Route::get('/list', 'User\FollowUpVersion2Controller@list')->name('followup.ver2.list');
        Route::get('/add/{id}', 'User\FollowUpVersion2Controller@add')->name('followup.ver2.add');
        Route::post('/create', 'User\FollowUpVersion2Controller@create')->name('followup.ver2.create');
        Route::get('/edit/{id}', 'User\FollowUpVersion2Controller@edit')->name('followup.ver2.edit');
        Route::post('/update', 'User\FollowUpVersion2Controller@update')->name('followup.ver2.update');

        Route::get('/delete/{id}', 'User\FollowUpVersion2Controller@delete')->name('followup.ver2.delete');
    });


    Route::group(['prefix' => 'demo'], function () {

        Route::get('/list', 'User\DemoController@list')->name('demo.list');
        Route::get('/add/{id}', 'User\DemoController@add')->name('demo.add');
        Route::post('/create', 'User\DemoController@create')->name('demo.create');
        Route::get('/edit/{id}', 'User\DemoController@edit')->name('demo.edit');
        Route::post('/update', 'User\DemoController@update')->name('demo.update');

    });


    Route::group(['prefix' => 'documentation'], function () {

        Route::get('/list', 'User\DocumentationController@list')->name('documentation.list');
        Route::get('/add/{id}', 'User\DocumentationController@add')->name('documentation.add');
        Route::post('/create', 'User\DocumentationController@create')->name('documentation.create');
        Route::get('/edit/{id}', 'User\DocumentationController@edit')->name('documentation.edit');
        Route::post('/update', 'User\DocumentationController@update')->name('documentation.update');

    });

    Route::group(['prefix' => 'call'], function () {

        Route::post('/create', 'User\CommonController@callCreate')->name('call.create');
    });

    Route::group(['prefix' => 'message'], function () {

        Route::post('/create', 'User\CommonController@messageCreate')->name('message.create');

    });

    Route::group(['prefix' => 'directVisit'], function () {

        Route::post('/create', 'User\CommonController@visitCreate')->name('directVisit.create');

    });

    Route::group(['prefix' => 'mail'], function () {

        Route::post('/create', 'User\CommonController@mailCreate')->name('mail.create');

    });

    Route::group(['prefix' => 'meeting'], function () {

        Route::post('/create', 'User\CommonController@meetingCreate')->name('meeting.create');

    });
    Route::group(['prefix' => 'document'], function () {

        Route::post('/create', 'User\CommonController@documentCreate')->name('document.create');

    });

    Route::group(['prefix' => 'conversion'], function () {

        Route::post('/create', 'User\CommonController@conversionCreate')->name('conversion.create');

    });

    Route::group(['prefix' => 'status'], function () {

        Route::get('/list', 'User\StatusController@list')->name('status.list');
        Route::get('/add', 'User\StatusController@add')->name('status.add');
        Route::post('/create', 'User\StatusController@create')->name('status.create');
        Route::get('/edit/{id}', 'User\StatusController@edit')->name('status.edit');
        Route::post('/update', 'User\StatusController@update')->name('status.update');
        Route::get('/delete/{id}', 'User\StatusController@delete')->name('status.delete');
    });

    Route::group(['prefix' => 'category'], function () {

        Route::get('/list', 'User\CategoryController@list')->name('category.list');
        Route::get('/add', 'User\CategoryController@add')->name('category.add');
        Route::post('/create', 'User\CategoryController@create')->name('category.create');
        Route::get('/edit/{id}', 'User\CategoryController@edit')->name('category.edit');
        Route::post('/update', 'User\CategoryController@update')->name('category.update');

        Route::get('/delete/{id}', 'User\CategoryController@delete')->name('category.delete');
    });

    Route::group(['prefix' => 'segment'], function () {

        Route::get('/list', 'User\SegmentController@list')->name('segment.list');
        Route::get('/add', 'User\SegmentController@add')->name('segment.add');
        Route::post('/create', 'User\SegmentController@create')->name('segment.create');
        Route::get('/edit/{id}', 'User\SegmentController@edit')->name('segment.edit');
        Route::post('/update', 'User\SegmentController@update')->name('segment.update');
        Route::get('/delete/{id}', 'User\SegmentController@delete')->name('segment.delete');
    });

    Route::group(['prefix' => 'domain'], function () {

        Route::get('/list', 'User\DomainController@list')->name('domain.list');
        Route::get('/add', 'User\DomainController@add')->name('domain.add');
        Route::post('/create', 'User\DomainController@create')->name('domain.create');
        Route::get('/edit/{id}', 'User\DomainController@edit')->name('domain.edit');
        Route::post('/update', 'User\DomainController@update')->name('domain.update');

        Route::get('/delete/{id}', 'User\DomainController@delete')->name('domain.delete');
    });

    Route::group(['prefix' => 'source'], function () {

        Route::get('/list', 'User\SourceController@list')->name('source.list');
        Route::get('/add', 'User\SourceController@add')->name('source.add');
        Route::post('/create', 'User\SourceController@create')->name('source.create');
        Route::get('/edit/{id}', 'User\SourceController@edit')->name('source.edit');
        Route::post('/update', 'User\SourceController@update')->name('source.update');

        Route::get('/delete/{id}', 'User\SourceController@delete')->name('source.delete');
    });
    Route::group(['prefix' => 'reports'], function () {

        Route::get('/areas/list', 'User\ReportsController@areas')->name('areas.list');
        Route::get('/filter', 'User\ReportsController@filter')->name('leads.reports.filter');
        Route::get('/segment/list', 'User\ReportsController@segments')->name('segment.list');
        Route::get('/domain/list', 'User\ReportsController@domains')->name('domain.list');

    });

    Route::group(['prefix' => 'reportsver2'], function () {

        Route::get('/filter', 'User\ReportsVersion2Controller@filter')->name('leads.reportsver2.filter');

        Route::get('/search', 'User\ReportsVersion2Controller@search')->name('leads.search');
        Route::get('/view/availableleads', 'User\ReportsVersion2Controller@availableleads')->name('leads.availableleads');
        Route::post('/bulk/update', 'User\ReportsVersion2Controller@importcsv')->name('product.BulkUpdate');

        Route::post('/create/availableleads', 'User\ReportsVersion2Controller@uploadavailableleads')->name('create.availableleads');
        Route::get('/areas/list', 'User\ReportsVersion2Controller@areas')->name('reportsver2.areas.list');
        Route::get('/category/list', 'User\ReportsVersion2Controller@category')->name('reportsver2.category.list');
        Route::get('/month/list', 'User\ReportsVersion2Controller@month')->name('reportsver2.month.list');

        Route::get('/prospect/list', 'User\ReportsVersion2Controller@prospects')->name('reportsver2.prospect.list');

        Route::get('/search/area', 'User\ReportsVersion2Controller@loadAreas')->name('reportsver2.search.area');

        Route::get('/search/district', 'User\ReportsVersion2Controller@loadDistricts')->name('reportsver2.search.district');
    });
});

include "adminRoutes.php";