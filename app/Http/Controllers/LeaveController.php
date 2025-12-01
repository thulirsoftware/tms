<?php

namespace App\Http\Controllers;


use App\LeavePermission;
use Auth;
use App\Leave;
use App\Task;
use App\Employee;
use App\Project;
use App\CfgActivity;
use App\CfgDesignations;
use App\CfgTaskStatus;
use App\CfgLeaveType;
use Illuminate\Http\Request;
use App\EmployeesLeave;
use DB;
use Carbon\Carbon;

class LeaveController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    private $leaveTypes;
    private $priorities;
    public function __construct()
    {

        $this->leaveTypes = CfgLeaveType::getLeaveTypeList();
        $this->priorities = Leave::getLeavePriorities();
        $this->middleware('auth');

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Leaves
        $leavesQuery = Leave::orderBy('id', 'DESC');
        if ($request->employee != "") {
            $leavesQuery->where('empId', $request->employee);
        }
        $leaves = $leavesQuery->take(25)->get();

        // Employees
        $employees = Employee::where('empStatus', null)->get();

        // Permissions (with employee relation)
        $permissionsQuery = LeavePermission::with('employee')->orderBy('id', 'DESC');
        if ($request->employee != "") {
            $emp = Employee::find($request->employee);  // find by id
            if ($emp) {
                $permissionsQuery->where('empId', $emp->empId); // match against business key
            }
        }
        $permissions = $permissionsQuery->take(25)->get();
        $active_tab = $request->active_tab ?? 'leave';

        return view('leave.index', [
            'leave' => $leaves,
            'permissions' => $permissions,
            'employees' => $employees,
            'active_tab' => $active_tab
        ]);
    }


    public function leavehome()
    {
        $employees = Employee::where('empStatus', null)->get();
        $designations = CfgDesignations::getDesignation();
        $currentTasks = Task::getAllCurrentTasks();
        $projects = Project::getProjects();
        $activities = CfgActivity::getActivities();

        if (Auth::user()->type == 'admin' || Auth::user()->hasPermission('Leave')) {
            return view('leave.leavehome', compact('employees', 'designations', 'currentTasks', 'projects', 'activities'));
        } else {
            $employee = Employee::where('empId', Auth::user()->empId)->first();
            return redirect()->route('leave_show', ['employee' => $employee->id]);
        }
    }
    public function leaveRequest()
    {
        $employee = Employee::where('empId', Auth::user()->empId)->first();
        return redirect()->route('leave_show', ['employee' => $employee->id]);

    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($employee = '')
    {
        $leave = new Leave;
        $leaveTypes = $this->leaveTypes;
        $priorities = $this->priorities;
        $availLeave = EmployeesLeave::where('email', Auth::user()->email)->first();
        if ($availLeave == null) {
            $new = new EmployeesLeave();
            $new->empId = Auth::user()->empId;
            $new->name = Auth::user()->name;
            $new->email = Auth::user()->email;
            $new->save();

            $availLeave = EmployeesLeave::where('email', Auth::user()->email)->first();
            $usedLeave = DB::table('leaves')->where('empId', $availLeave['empId'])->where('approval', 'yes')->where('leaveTypeId', '!=', 2)->sum('totalLeaveDays');
            $availLeaveDays = $availLeave['el'] + $availLeave['al'] - $usedLeave;
        } else {
            $usedLeave = DB::table('leaves')->where('empId', $availLeave['empId'])->where('approval', 'yes')->where('leaveTypeId', '!=', 2)->sum('totalLeaveDays');

            $availLeaveDays = $availLeave['el'] + $availLeave['al'] - $usedLeave;
        }


        return view('leave.create', compact('leave', 'leaveTypes', 'employee', 'priorities', 'availLeaveDays'));
    }
    public function createPermission($employee = '')
    {
        $permission = new LeavePermission(); // assuming you have a Permission model

        $priorities = $this->priorities; // if you are also using priorities
        $employeeData = Auth::user();

        // You may not need leave balance for permission, but if you want to restrict per month,
        // you could calculate total hours already taken here.
        $usedPermissionHours = DB::table('leave_permissions')
            ->where('empId', $employeeData->empId)
            ->where('approval', 'yes')
            ->whereMonth('permissionDate', now()->month) // current month
            ->sum('totalHours');

        // Example: you allow max 8 hours of permission per month
        $maxPermissionHours = 8;
        $availPermissionHours = $maxPermissionHours - $usedPermissionHours;

        return view('leave.createpermission', compact(
            'permission',
            'employee',
            'priorities',
            'availPermissionHours'
        ));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Leave $leave)
    {

        if (!$leave->exists) {
            $request['empId'] = Employee::where('empId', Auth::user()->empId)->first()->id;
            // dd($request->all());
            $leave = Leave::create($request->all());
            session()->flash('success', 'Leave Request Sent Succesfully');
        } else {
            session()->flash('success', 'Leave Request Failed');
        }
        // if(Auth::user()->type=='admin'){
        //     return redirect('/Admin/Leave/');
        // }
        return redirect('/Leaverequest');
    }
    public function storePermission(Request $request)
    {
        $request->validate([
            'permissionDate' => 'required|date',
            'fromTime' => 'required',
            'toTime' => 'required|after:fromTime',
            'reason' => 'required|string|max:255',
        ]);
        $employee = Employee::where('email', Auth::user()->email)->first();

        $from = strtotime($request->fromTime);
        $to = strtotime($request->toTime);
        $hours = round(($to - $from) / 3600, 2);

        LeavePermission::create([
            'empId' => Auth::user()->empId,
            'name' => Auth::user()->name,
            'email' => Auth::user()->email,
            'requestDate' => now()->format('Y-m-d'),
            'permissionDate' => $request->permissionDate,
            'fromTime' => $request->fromTime,
            'toTime' => $request->toTime,
            'totalHours' => $hours,
            'reason' => $request->reason,
            'approval' => 'pending',
        ]);

        return redirect()->route('leave_show', ['employee' => $employee->id])->with('success', 'Permission request submitted successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Leave  $leave
     * @return \Illuminate\Http\Response
     */
    public function show(Employee $employee)
    {
        $priorities = $this->priorities;
        $leaveTypes = $this->leaveTypes;

        if (!$employee->exists) {
            $employee = Employee::where('empId', Auth::user()->empId)->first();
        }

        // Extra leave balance
        $extraLeave = EmployeesLeave::where('empId', $employee->id)->first();

        // Normal leave requests
        $leaves = Leave::where('empId', $employee->id)
            ->orderBy('id', 'DESC')
            ->get();

        // Permission requests
        $permissions = LeavePermission::where('empId', $employee->empId)
            ->orderBy('id', 'DESC')
            ->get();


        return view('leave.view', compact(
            'employee',
            'leaves',
            'permissions',
            'priorities',
            'leaveTypes',
            'extraLeave'
        ));
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Leave  $leave
     * @return \Illuminate\Http\Response
     */
    public function edit(Leave $leave)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Leave  $leave
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Leave $leave)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Leave  $leave
     * @return \Illuminate\Http\Response
     */
    public function destroy(Leave $leave)
    {
        //
    }




    public function searchEmp(Request $request)
    {
        if ($request->ajax()) {
            $output = "";
            if ($request->search != "") {
                $leaveEmp = Leave::where('empId', $request->search)->orderBy('id', 'DESC')->get()->toArray();
            }
            // else{
            //     $districts=DB::table('districts')->get();
            // }

            if ($leaveEmp) {

                $i = 1;

                foreach ($leaveEmp as $key => $lEmp) {

                    $eid = $lEmp["empId"];
                    $lid = $lEmp["leaveTypeId"];
                    $name = Employee::where('id', $eid)->first();
                    $leavetype = CfgLeaveType::where('id', $lid)->first();

                    $output .= '<tr>' .

                        '<td>' . $i++ . '</td>' .

                        '<td>' . $lEmp["requestDate"] . '</td>' .
                        '<td>' . $name["name"] . '</td>' .
                        '<td>' . $leavetype["name"] . '</td>' .
                        '<td>' . $lEmp["leaveFromDate"] . '</td>' .
                        '<td>' . $lEmp["leaveToDate"] . '</td>' .
                        '<td>' . $lEmp["totalLeaveDays"] . '</td>';
                    '<td>' . $lEmp["totalLeaveDays"] . '</td>';
                    if ($lEmp["approval"] == 'no' || $lEmp["approval"] == '') {

                        $output .= '<td><i id="declineTask<?=$lEmp->id?>" onclick="approveTask(' . $lEmp["id"] . ',' . $lEmp["empId"] . ')" class="fa fa-thumbs-up" style="font-size:25px;color:green"></i></td>';
                    } else {
                        $output .= '<td><i id="approveTask<?=$leave->id?>" onclick="declineTask(' . $lEmp["id"] . ',' . $lEmp["empId"] . ')" class="fa fa-thumbs-down" style="font-size:25px;color:pink"></i></td>';
                    }



                    '</tr>';
                }
            }

            return Response($output);

        }
    }



    public function leaveReport(Request $request)
    {

        $now = Carbon::now();

        $month['month'] = date('M Y');
        return view('leave.leave_report', $month);
    }
    public function approvePermission(Request $request)
    {
        $permission = LeavePermission::find($request->permissionId);
        if ($permission) {
            $permission->approval = 'yes';
            $permission->save();
            return response()->json(['status' => true]);
        }
        return response()->json(['status' => false]);
    }

    public function declinePermission(Request $request)
    {
        $permission = LeavePermission::find($request->permissionId);
        if ($permission) {
            $permission->approval = 'no';
            $permission->save();
            return response()->json(['status' => true]);
        }
        return response()->json(['status' => false]);
    }



}
