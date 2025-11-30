<?php

namespace App\Http\Controllers;

use App\Role;
 use App\Employee;
use App\User;
use App\CfgDesignations;
use Hash;
use Illuminate\Http\Request;
use App\EmployeesLeave;
use  Illuminate\Support\Facades\Auth;

class EmployeeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Fetch designations
        $designations = CfgDesignations::getDesignation();
        $default_nav = 'employees';
        if (Auth::user()->type == "admin") {
            // Fetch employees with null empStatus
            $employees = Employee::where('empStatus', null)
                ->whereHas('user', function ($q) {
                    $q->where('type', 'employee'); // only real employees
                })
                ->get();
            // Fetch interns
            $interns = User::where('type', 'intern')
                ->whereHas('employee', function ($query) {
                    $query->where('empStatus', null);
                })
                ->get();
            if($request->has('redirected_nav') && !empty($request->get('redirected_nav'))){
                $default_nav = $request->get('redirected_nav');
            }
            // Pass both employees and interns to the view
            return view('admin.employee.index', compact('employees', 'default_nav','designations', 'interns'));
        } else {
            // For non-admin users, fetch only their employee record
            $employee = Auth::user()->employee;
            return view('employee.index', compact('designations', 'employee'));
        }
    }
    public function employeeIndex()
    {
        $designations = CfgDesignations::getDesignation();
        if (Auth::user()->type == "admin") {
            // Fetch employees with null empStatus
            $employees = Employee::where('empStatus', null)
                ->whereHas('user', function ($q) {
                    $q->where('type', 'employee'); // only real employees
                })
                ->get();
            // Fetch interns
            $interns = User::where('type', 'intern')
                ->whereHas('employee', function ($query) {
                    $query->where('empStatus', null);
                })
                ->get();

            // Pass both employees and interns to the view
            return view('admin.employee.index', compact('employees', 'designations', 'interns'));
        } else {
            // For non-admin users, fetch only their employee record
            $employee = Auth::user()->employee;
            return view('employee.index', compact('designations', 'employee'));
        }
    }



    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (Auth::user()->type == "admin" || Auth::user()->hasPermission('Employees')) {
            $designation = CfgDesignations::getDesignation();
            $roles = Role::pluck('name', 'id');
            $employee = new Employee;
            return view('admin.employee.create', compact('designation', 'employee', 'roles'));
        } else {
            return redirect('/Employee');
        }
    }

    public function createintern()
    {
        if (Auth::user()->type == "admin" || Auth::user()->hasPermission('Employees')) {
            $designation = CfgDesignations::getDesignation();
            $roles = Role::pluck('name', 'id');
            $intern = new Employee;
            return view('admin.employee.createintern', compact('designation', 'intern', 'roles'));
        } else {
            return redirect('/Employee');
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'nullable|email|unique:users,email',
                'password' => 'required|min:6|confirmed',
                'role_id' => 'required|exists:roles,id',
            ]);

            // Prepare additional fields
            $request['regDate'] = date('Y-m-d');
            $request['joinDate'] = date('Y-m-d');
            $request['resignDate'] = null;

            // Generate unique empId efficiently
            $lastEmp = Employee::orderBy('id', 'desc')->first();
            $request['empId'] = $lastEmp ? $lastEmp->empId + 1 : 1001;

            // Create employee
            $employee = Employee::create($request->all());

            // Create user
            $user = User::create([
                'name' => $request['name'],
                'type' => $request['type'], // (can remove later if using roles fully)
                'email' => $request['email'],
                'empId' => $employee->empId,
                'password' => bcrypt($request['password']),
            ]);

            // Attach role to user
            $user->roles()->attach($request->role_id);

            // Success message
            session()->flash('success', 'Employee Added Successfully');
            if (Auth::user()->type == 'admin' || Auth::user()->hasPermission('Employees')) {
                if ($employee->email != Auth::user()->email) {
                    // Assigned to someone else
                    if($request->expLevel == '2'){
                        return redirect('/Admin/Employee?redirected_nav=interns')->with('success', 'Employee Added successfully.');
                    } else {
                      return redirect('/Admin/Employee?redirected_nav=employees')->with('success', 'Employee Added successfully.');

                    }
                } else {
                    // Assigned to themselves
                    return redirect('/Employee')->with('success', 'Employee Added successfully.');
                }

            } else {
                return redirect('/Employee')->with('success', 'Employee updated successfully.');
            }

        } catch (\Illuminate\Validation\ValidationException $ve) {
            return back()->withInput()->withErrors($ve->errors());
        } catch (\Exception $e) {
            return back()->withInput()->withErrors([
                'custom_error' => 'Failed to add employee. Please check the data and try again.'
            ]);
        }
    }






    /**
     * Display the specified resource.
     *
     * @param  \App\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function show(Employee $employee)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function edit(Employee $employee)
    {

        if ($employee = Employee::find($employee->id)) {
            $designation = CfgDesignations::getDesignation();
           $roles = Role::pluck('name', 'id'); // Add this line to fetch roles

            $update_label = 'Employee';
            if($employee->user()->first() != 'null'){
                $update_label = ucfirst($employee->user()->first()->type);
            }
                 
            if (Auth::user()->type == 'admin' || Auth::user()->hasPermission('Employees')) {
                return view('admin.employee.edit', compact('update_label','employee', 'designation', 'roles'));
            } else {
                return view('employee.edit', compact('update_label','employee', 'designation', 'roles'));
            }
        }
    }
    public function editEmployee(Employee $employee)
    {
        if ($employee = Employee::find($employee->id)) {

            $designation = CfgDesignations::getDesignation();
            $roles = Role::pluck('name', 'id'); // Add this line to fetch roles
            return view('employee.edit', compact('employee', 'designation', 'roles'));
        }
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Employee  $employee
     * @return \Illuminate\Http\Response
     */


    public function update(Request $request, Employee $employee)
    {

        // Validate general employee fields
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'designation' => 'required|string|max:255',
            'dob' => 'nullable|date',
            'gender' => 'nullable|in:male,female',
            'email' => 'nullable|email|unique:users,email,' . optional($employee->user)->id,
            'mobile' => 'nullable|string|max:20',
            'motherTongue' => 'nullable|string|max:100',
            'qualification' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'expLevel' => 'nullable|in:0,1,2',
            'expYear' => 'nullable|numeric|min:0',
            'expMonth' => 'nullable|numeric|min:0|max:11',
            'bankAccountName' => 'nullable|string|max:255',
            'bankAccountNo' => 'nullable|string|max:50',
            'bankName' => 'nullable|string|max:255',
            'bankIfscCode' => 'nullable|string|max:20',
            'bankBranch' => 'nullable|string|max:255',
            'role_id' => 'required|exists:roles,id',
            'password' => 'nullable|string|min:6|confirmed',
            'current_password' => 'nullable|string'
        ]);

        // Update employee data
        $employee->fill([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'designation' => $validatedData['designation'],
            'dob' => $validatedData['dob'],
            'gender' => $validatedData['gender'],
            'mobile' => $validatedData['mobile'],
            'motherTongue' => $validatedData['motherTongue'],
            'qualification' => $validatedData['qualification'],
            'address' => $validatedData['address'],
            'city' => $validatedData['city'],
            'state' => $validatedData['state'],
            'expLevel' => $validatedData['expLevel'],
            'expYear' => $validatedData['expYear'] ?? 0,
            'expMonth' => $validatedData['expMonth'] ?? 0,
            'bankAccountName' => $validatedData['bankAccountName'],
            'bankAccountNo' => $validatedData['bankAccountNo'],
            'bankName' => $validatedData['bankName'],
            'bankIfscCode' => $validatedData['bankIfscCode'],
            'bankBranch' => $validatedData['bankBranch']
        ]);
        $employee->save();
        $expLevel = $employee->expLevel;
        // Update user's email if provided
        if (!empty($validatedData['email']) && $employee->user) {
            $employee->user->email = $validatedData['email'];
            $employee->user->save();
        }

        // Update role if user exists
        if ($employee->user) {
            $employee->user->roles()->sync([$validatedData['role_id']]);
        }

        // Update password if provided and confirmation matches
        if ($employee->user && !empty(trim($validatedData['password'] ?? ''))) {
            $employee->user->password = Hash::make($validatedData['password']);
            $employee->user->save();
        }
        if (Auth::user()->type == 'admin' || Auth::user()->hasPermission('Employees')) {
            if ($employee->email != Auth::user()->email) {
                // Assigned to someone else
                  if($expLevel == '2'){
                        return redirect('/Admin/Employee?redirected_nav=interns')->with('success', 'Employee updated successfully.');
                    } else {
                      return redirect('/Admin/Employee?redirected_nav=employees')->with('success', 'Employee updated successfully.');

                    }
            } else {
                // Assigned to themselves
                return redirect('/Employee')->with('success', 'Employee updated successfully.');
            }

        } else {
            return redirect('/Employee')->with('success', 'Employee updated successfully.');
        }



    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function destroy(Employee $employee)
    {
        $employee = Employee::find($employee->id);
      
        $expLevel = $employee->expLevel;
        if ($employee) {
            $user = User::where('empId', $employee->empId)->first();
            if ($user) {
                $user->forceDelete(); // 🚀 permanently deletes user
            }
            $employee->forceDelete(); // 🚀 permanently deletes employee
        }

        if (Auth::user()->type == 'admin' || Auth::user()->hasPermission('Employees')) {
            if ($employee->email != Auth::user()->email) {
                // Assigned to someone else
                 if($expLevel == '2'){
                        return redirect('/Admin/Employee?redirected_nav=interns')->with('success', 'Employee deleted successfully.');
                    } else {
                      return redirect('/Admin/Employee?redirected_nav=employees')->with('success', 'Employee deleted successfully.');

                    }
            } else {
                // Assigned to themselves
                return redirect('/Employee')->with('success', 'Employee deleted successfully.');
            }

        } else {
            return redirect('/Employee')->with('success', 'Employee updated successfully.');
        }
    }




}
