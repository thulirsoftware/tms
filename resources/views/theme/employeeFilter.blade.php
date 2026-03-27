@include('theme.filterScript')
 <div class="well" id="filters">
   <div class="row" >
      
      @if(Auth::user()->type=='admin' || Auth::user()->hasPermission('Report')) 
      <div class="col-md-2">
         <div class="form-group">
            <label>Employees</label>
               <select class="form-control" id="employeeFilter">
               <option value="">Select one</option>
               <?php $employees=App\Employee::all();?>
               @foreach($employees as $key => $emp)           
               <option value="{{$emp->id}}"<?=($emp->id==request()->get('employee'))?'selected':''?>>{{$emp->name}}</option>
               @endforeach
            </select>
         </div>
      </div>
      @endif
      <div class="col-md-1">
      <br>
         <div class="form-group">
            <a class="btn btn-primary" onclick="resetFilterUser()"><i class="fa fa-refresh" aria-hidden="true"   title="Reset Filter" ></i></a>
         </div>
      </div>
   </div>
   <div class="row" >
         <div class="col-md-3">
         <div class="form-group">
            <label>Assigned Date</label>
            <input placeholder="Assigned Date" type="date" onfocus="(this.type='date')" onblur="(this.type='text')" name="assignedDateFilter" id="assignedDateFilter" value="<?=request()->get('assignedDate')?>" class="form-control">
         </div>
      </div>
      <div class="col-md-3">
         <div class="form-group">
            <label>Taken Date</label>
            <input placeholder="Taken Date" type="date" name="takenDateFilter" id="takenDateFilter" value="<?=request()->get('takenDate')?>" class="form-control">
         </div>
      </div>

      <div class="col-md-3">
         <div class="form-group">
            <label>From Date</label>
            <input placeholder="From Date" type="date"  name="fromDateFilter" id="fromDateFilter" value="<?=request()->get('fromDate')?>" class="form-control">
         </div>
      </div>

      <div class="col-md-3">
         <div class="form-group">
            <label>To Date</label>
            <input placeholder="To Date" type="date"  name="toDateFilter" id="toDateFilter" value="<?=request()->get('toDate')?>" class="form-control">
         </div>
      </div>

   </div>
</div>