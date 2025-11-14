<div class="navbar-default sidebar" role="navigation">
    <div class="sidebar-nav navbar-collapse collapse">
        <ul class="nav" id="side-menu">
            <li class="sidebar-search">
                <div class="input-group custom-search-form">
                    <input type="text" class="form-control" placeholder="Search...">
                    <span class="input-group-btn">
                    <button class="btn btn-default" type="button">
                        <i class="fa fa-search"></i>
                    </button>
                </span>
                </div>
                <!-- /input-group -->
            </li>
             <li>
                <a class="nav-link" href="{{route('dashboard')}}"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a>
            </li>
             <li>
                <a href="{{route('leads.search')}}" class="nav-link"><span><i class="fa fa-search"></i>  Bulk Upload</span></a>
            </li>
            <li>
                <a class="nav-link" href="{{route('leads.ver2.myleads')}}"><i class="fa fa-square"></i> <span>Today Leads</span></a>
            </li>
            
             <li>
                <a class="nav-link" href="{{route('leads.ver2.list')}}"><i class="fa fa-square"></i> <span>Leads 2.0</span></a>
            </li>
            <li>
                 <a class="nav-link " data-toggle="collapse" href="#Report20Example1" role="button" aria-expanded="false" aria-controls="collapseExample">
                    <i class="fa fa-search"></i>  Reports 2.0 
                  </a>
  
               
            </li>
            <div class="collapse show" id="Report20Example1">
                  <div class="card card-body" style="padding-top: 10px;padding-left: 20px;padding-bottom: 10px;border-bottom: 1px solid #e7e7e7;">
                     <a href="{{route('reportsver2.areas.list')}}" class="nav-link" style="padding-left: 20px;"><span><i class="fa fa-map-marker"></i>  Areas</span></a>
                  </div>
                   <div class="card card-body" style="padding-top: 10px;padding-left: 20px;padding-bottom: 10px;border-bottom: 1px solid #e7e7e7;">
                     <a href="{{route('reportsver2.category.list')}}" class="nav-link" style="padding-left: 20px;"><span><i class="fa fa-server"></i>  Category</span></a>
                  </div>
                  <div class="card card-body" style="padding-top: 10px;padding-left: 20px;padding-bottom: 10px;border-bottom: 1px solid #e7e7e7;">
                     <a href="{{route('reportsver2.month.list')}}" class="nav-link" style="padding-left: 20px;"><span><i class="fa fa-calendar"></i>  Months</span></a>
                  </div>
                   <div class="card card-body" style="padding-top: 10px;padding-left: 20px;padding-bottom: 10px;border-bottom: 1px solid #e7e7e7;">
                     <a href="{{route('reportsver2.prospect.list')}}" class="nav-link" style="padding-left: 20px;"><span><i class="fa fa-calendar"></i>  Prospects</span></a>
                  </div>
            </div>
            <li>
                <a class="nav-link" href="{{route('leads.list')}}"><i class="fa fa-square"></i> <span>Leads1.0</span></a>
            </li>
            <li>
                <a href="{{route('status.list')}}" class="nav-link"><span><i class="fa fa-book"></i>  Status</span></a>
            </li>
           
            <li>
                 <a class="nav-link " data-toggle="collapse" href="#collapseExample1" role="button" aria-expanded="false" aria-controls="collapseExample">
                    <i class="fa fa-search"></i>  Reports 1.0 
                  </a>
  
               
            </li>
            <div class="collapse show" id="collapseExample1">
                  <div class="card card-body" style="padding-top: 10px;padding-left: 20px;padding-bottom: 10px;border-bottom: 1px solid #e7e7e7;">
                     <a href="{{route('areas.list')}}" class="nav-link" style="padding-left: 20px;"><span><i class="fa fa-map-marker"></i>  Areas</span></a>
                  </div>
                   <div class="card card-body" style="padding-top: 10px;padding-left: 20px;padding-bottom: 10px;border-bottom: 1px solid #e7e7e7;">
                     <a href="{{route('domain.list')}}" class="nav-link" style="padding-left: 20px;"><span><i class="fa fa-server"></i>  Domain</span></a>
                  </div>
                  <div class="card card-body" style="padding-top: 10px;padding-left: 20px;padding-bottom: 10px;border-bottom: 1px solid #e7e7e7;">
                     <a href="{{route('segment.list')}}" class="nav-link" style="padding-left: 20px;"><span><i class="fa fa-road"></i>  Segment</span></a>
                  </div>
 
            </div>
            
            
            <li>
                <a href="{{route('configs')}}" class="nav-link"><span><i class="fa fa-book"></i> Configurations</span></a>
            </li>
            <li>
                <a class="nav-link" data-toggle="modal" data-target="#systemModal" style="cursor: pointer"><i class="fa fa-desktop"></i> &nbsp;Switch System
                </a>
            </li>
            
            
            <li>
                <a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault();
                      document.getElementById('logout-form').submit();"><i class="fa fa-sign-out"></i> <span> Logout</span></a></li>
                     <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        {{ csrf_field() }}
                  </form>
            </li>

            
<!--             <li>
                <a href="forms.html"><i class="fa fa-edit fa-fw"></i> Forms</a>
            </li>
            <li>
                <a href="#"><i class="fa fa-wrench fa-fw"></i> UI Elements<span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                    <li>
                        <a href="panels-wells.html">Panels and Wells</a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="#"><i class="fa fa-files-o fa-fw"></i> Sample Pages<span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                    <li>
                        <a href="blank.html">Blank Page</a>
                    </li>
                    <li>
                        <a href="login.html">Login Page</a>
                    </li>
                </ul>
            </li> -->
        </ul>
    </div>
    <!-- /.sidebar-collapse -->
</div>
<!-- /.navbar-static-side -->