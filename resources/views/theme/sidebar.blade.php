<div class="navbar-default sidebar" role="navigation">
    <div class="sidebar-nav navbar-collapse">
        <ul class="nav" id="side-menu">

            {{-- Search (hidden) --}}
            <li class="sidebar-search" style="display:none;">
                <div class="input-group custom-search-form">
                    <input type="text" class="form-control" placeholder="Search...">
                    <span class="input-group-btn">
                        <button class="btn btn-default" type="button">
                            <i class="fa fa-search"></i>
                        </button>
                    </span>
                </div>
            </li>

            {{-- ADMIN SIDEBAR --}}
            @if(Auth::user()->type == 'admin')
                @if(Auth::user()->hasPermission('Dashboard'))
                    <li><a class="{{ Request::is('Admin') ? 'active' : '' }}" href="{{ url('/') }}"><i
                                class="fa fa-dashboard fa-fw"></i> Dashboard</a></li>
                @endif

                @if(Auth::user()->hasPermission('Employees'))
                    <li><a class="{{ Request::is('Admin/Employee*') ? 'active' : '' }}" href="{{ url('/Admin/Employee') }}"><i
                                class="fa fa-users fa-fw"></i> Employees</a></li>
                @endif

                @if(Auth::user()->hasPermission('Roles'))
                    <li><a class="{{ Request::is('Admin/roles*') ? 'active' : '' }}" href="{{ url('Admin/roles') }}"><i
                                class="fa fa-users fa-fw"></i> Roles</a></li>
                @endif

                @if(Auth::user()->hasPermission('Tasks'))
                    <li><a class="{{ Request::is('Admin/Task*', 'Task*') ? 'active' : '' }}" href="{{ url('/Admin/Task') }}"><i
                                class="fa fa-tasks fa-fw"></i>Tasks</a></li>
                @endif

                @if(Auth::user()->hasPermission('Leave'))
                    <li><a class="{{ Request::is('Admin/Leave*', 'Admin/Leavehome') ? 'active' : '' }}"
                            href="{{ url('/Admin/Leave') }}"><i class="fa fa-envelope fa-fw"></i> A Leave</a></li>

                @endif

                @if(Auth::user()->hasPermission('Links'))
                    <li><a class="{{ Request::is('Admin/Links*') ? 'active' : '' }}" href="{{ url('/Admin/Links') }}"><i
                                class="fa fa-link"></i> &nbsp;Links</a></li>
                @endif




                @if(Auth::user()->hasPermission('SnapShot'))
                    <li><a class="{{ Request::is('Admin/snapchat*') ? 'active' : '' }}" href="{{ url('/Admin/snapchat') }}"><i
                                class="fa fa-bar-chart"></i> &nbsp;SnapShot</a></li>
                @endif

                @if(Auth::user()->hasPermission('Config Tables'))
                    <li><a class="{{ Request::is('Admin/ConfigTable*', 'clients*') ? 'active' : '' }}"
                            href="{{ url('/Admin/ConfigTable') }}"><i class="fa fa-table"></i> Config Tables</a></li>
                @endif
                @if(Auth::user()->hasPermission('Report'))
                    <li class="dropdown {{ Request::is('Admin/Report*') ? 'active' : '' }}" style="position: relative;">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <i class="fa fa-bar-chart"></i> <span>Report</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-down pull-right"></i>
                            </span>
                        </a>

                        <ul class="dropdown-menu"
                            style="background: #fff; border-radius: 6px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); width: 220px;">
                            <li>
                                <a href="{{ url('/Admin/Report') }}"
                                    class="{{ Request::is('Admin/Report/Attendance*') ? 'active' : '' }}"
                                    style="padding: 10px 15px; display: block; color: #333;">
                                    All Reports
                                </a>
                            </li>
                            <li>
                                <a href="{{ url('/Admin/Report/Project-report') }}"
                                    class="{{ Request::is('Admin/Report/Project*') ? 'active' : '' }}"
                                    style="padding: 10px 15px; display: block; color: #333;">
                                    Project Based
                                </a>
                            </li>
                            <li>
                                <a href="{{ url('/Admin/Report/Employee-Report') }}"
                                    class="{{ Request::is('/Report/Employee-Report*') ? 'active' : '' }}"
                                    style="padding: 10px 15px; display: block; color: #333;">
                                    Employee Based
                                </a>
                            </li>
                            <li>
                                <a href="{{ url('/Admin/Report/task-report') }}"
                                    class="{{ Request::is('/Report/task-report*') ? 'active' : '' }}"
                                    style="padding: 10px 15px; display: block; color: #333;">
                                    Task Based
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif




                {{-- EMPLOYEE SIDEBAR --}}
            @else

                @if(Auth::user()->hasPermission('Dashboard'))
                    <li><a class="{{ Request::is('Admin') ? 'active' : '' }}" href="{{ route('adminHome')}}"><i
                                class="fa fa-dashboard fa-fw"></i> Dashboard</a></li>
                @else
                    <li><a class="{{ Request::is('/') ? 'active' : '' }}" href="{{ url('/')}}"><i
                                class="fa fa-dashboard fa-fw"></i> Dashboard</a></li>

                @endif

                <li><a class="{{ Request::is('Employee*') ? 'active' : '' }}" href="{{ route('Employee') }}"><i
                            class="fa fa-user fa-fw"></i> {{ Auth::user()->name }} Profile</a>
                </li>
                @if(Auth::user()->hasPermission('Employees'))
                    <li><a class="{{ Request::is('Admin/Employee*') ? 'active' : '' }}" href="{{ url('/Admin/Employee') }}"><i
                                class="fa fa-users fa-fw"></i> Employees</a></li>
                @endif

                @if(Auth::user()->hasPermission('Roles'))
                    <li><a class="{{ Request::is('Admin/roles*') ? 'active' : '' }}" href="{{ url('Admin/roles') }}"><i
                                class="fa fa-users fa-fw"></i> Roles</a></li>
                @endif
                @if(Auth::user()->hasPermission('Tasks'))
                    <li><a class="{{ Request::is('Admin/Task*', 'RegularTask*') ? 'active' : '' }}"
                            href="{{ url('Admin/Task') }}"><i class="fa fa-tasks fa-fw"></i>A Tasks</a></li>
                @endif
                <li><a class="{{ Request::is('Task*') ? 'active' : '' }}" href="{{ url('/Task') }}"><i
                            class="fa fa-tasks fa-fw"></i> Tasks</a></li>

                @if(Auth::user()->type != 'intern')
                    @if(!Auth::user()->hasPermission('Tasks'))
                        <li><a class="{{ Request::is('Task*') ? 'active' : '' }}" href="{{ route('teamTask') }}"><i
                                    class="fa fa-users fa-fw"></i> Team</a></li>
                    @endif
                    @if(Auth::user()->hasPermission('Leave'))
                        <li><a class="{{ Request::is('Admin/Leave*', 'Admin/Leavehome') ? 'active' : '' }}"
                                href="{{ url('/Admin/Leave') }}"><i class="fa fa-envelope fa-fw"></i> A Leave</a></li>
                    @endif
                    <li><a class="{{ Request::is('Leave*') ? 'active' : '' }}" href="{{ url('/Leaverequest') }}"><i
                                class="fa fa-envelope fa-fw"></i> Leave</a></li>
                @endif
                @if(Auth::user()->hasPermission('Config Tables'))
                    <li><a class="{{ Request::is('Admin/ConfigTable*', 'clients*') ? 'active' : '' }}"
                            href="{{ url('/Admin/ConfigTable') }}"><i class="fa fa-table"></i> Config Tables</a></li>
                @endif
                @if(Auth::user()->hasPermission('Report'))
                    <li><a class="{{ Request::is('Admin/Report*') ? 'active' : '' }}" href="{{ url('/Admin/Report') }}"><i
                                class="fa fa-bar-chart"></i> Report</a></li>
                @else
                    <li><a class="{{ Request::is('Report*') ? 'active' : '' }}" href="{{ url('/Report') }}"><i
                                class="fa fa-bar-chart"></i> Report</a></li>
                @endif
                @if(Auth::user()->hasPermission('SnapShot'))
                    <li><a class="{{ Request::is('Admin/snapchat*') ? 'active' : '' }}" href="{{ url('/Admin/snapchat') }}"><i
                                class="fa fa-bar-chart"></i> &nbsp;SnapShot</a></li>
                @endif


                <li><a class="{{ Request::is('Link*') ? 'active' : '' }}" href="{{ url('/Links') }}"><i
                            class="fa fa-link"></i> &nbsp;Links</a></li>

            @endif
        </ul>
    </div>
</div>
<style>
    .sidebar ul li a.active {
        background-color: #296698;
        color: white;
    }
</style>