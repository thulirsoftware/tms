@extends('layouts.app')

   

@section('content')
<div class="row">
    <div class="col-md-10">
        
    </div>
     
</div>
<div style="margin-top:1rem" >
          <div class="row collapse" id="collapseExample">
              <div class="col-12">
                <div class="panel panel-default">
                  <div class="panel-body">
                    <div class="row">
                       <?php 
                            $statusSession = Session::get('status');
                            $monthSession = Session::get('month');
                            $nameSession = Session::get('name');
                            $websiteSession = Session::get('website');
                            $areaSession = Session::get('area');
                            $sortSession = Session::get('sort');
                            $segmentSession = Session::get('segment');
                            $domainSession = Session::get('domain');
                            $start_dateSession = Session::get('start_date');
                            $end_dateSession = Session::get('end_date');

                            $statusInSession = Session::get('statusIn');
                            $statusNotINSession = Session::get('statusNotIn');
                          ?>
                          <input type="hidden" id="page" value="{{app('request')->input('page')}}">
                          <input type="hidden" id="segment_selected" name="segment_selected" class="form-control" value="{{$segmentSession}}">
                  
                      <div class="col-md-3">
                        <div class="form-group">
                          <label>Month</label>

                            <select id="month" name="month" class="form-control">

                              <option value="">--- Select Month ---</option>
                                <option value="1" {{ $monthSession == '1' ? 'selected' : ''}}>January</option>
                                <option value="2"  {{ $monthSession == '2' ? 'selected' : ''}}>February</option>
                                <option value="3"  {{ $monthSession == '3' ? 'selected' : ''}}>March</option>
                                <option value="4"  {{ $monthSession == '4' ? 'selected' : ''}}>April</option>
                                <option value="5"  {{ $monthSession == '5' ? 'selected' : ''}}>May</option>
                                <option value="6"  {{ $monthSession == '6' ? 'selected' : ''}}>June</option>
                                <option value="7"  {{ $monthSession == '7' ? 'selected' : ''}}>July</option>
                                <option value="8"  {{ $monthSession == '8' ? 'selected' : ''}}>August</option>
                                <option value="9"  {{ $monthSession == '9' ? 'selected' : ''}}>September</option>
                                <option value="10"  {{ $monthSession == '10' ? 'selected' : ''}}>October</option>
                                <option value="11"  {{ $monthSession == '11' ? 'selected' : ''}}>Novermber</option>
                                <option value="12"  {{ $monthSession == '12' ? 'selected' : ''}}>December</option>
                          </select>
                        </div>
                      </div>
                       <div class="col-md-3">
                          <div class="form-group">
                          <label>Status</label>
                            <select id="status" name="status" class="form-control">
                              <option value="">--- Select status ---</option>
                              @foreach ($statuses as $status)
                             
                                  <option value="{{ $status->id }}" {{ $status->id == $statusSession ? 'selected' : ''}}>{{ $status->name }}</option>
                              @endforeach
                          </select>
                        </div>
                      </div>
                       <div class="col-md-3">
                        <div class="form-group">
                          <label>Name</label>
                            <input type="text" id="name" name="name" class="form-control" value="{{ $nameSession}}">
                              
                        </div>
                      </div>
                       <div class="col-md-3">
                        <div class="form-group">
                          <label>Area</label>
                            <select id="area" name="area" class="form-control">
                              <option value="">--- Select Area ---</option>
                              @foreach ($areas as $area)
                                  <option value="{{ $area->area }}" {{ $area->area  == $areaSession ? 'selected' : ''}}>{{ $area->area }}</option>
                              @endforeach
                          </select>
                        </div>
                      </div>
                       <div class="col-md-3">
                        <div class="form-group">
                          <label>Website Status</label>
                            <select id="website" name="website" class="form-control">
                              <option value="">--- Select Status ---</option>
                               <option value="Available" {{ $websiteSession == 'Available' ? 'selected' : ''}}>Available</option>
                               <option value="Not Available" {{ $websiteSession == 'Not Available' ? 'selected' : ''}}>Not Available</option>
                          </select>
                        </div>
                      </div>
                      <?php 
                        $domains = \App\CfgDomain::where('status','Y')->get();
                      ?>
                      <div class="col-md-3">
                          <div class="form-group">
                          <label>Domain</label>
                            <select  onchange="getSegment(this.value)" id="domain" name="domain" class="form-control">
                              <option value="">--- Select Domain ---</option>
                              @foreach ($domains as $domain)
                             
                                  <option value="{{ $domain->id }}" {{ $domain->id == $domainSession ? 'selected' : ''}}>{{ $domain->name }}</option>
                              @endforeach
                          </select>
                        </div>
                      </div>
                       <div class="col-md-3">
                          <div class="form-group">
                          <label>Segment</label>
                            <select id="segment" name="segment" class="form-control">
                              <option value="">--- Select Segment ---</option>
                             
                          </select>
                        </div>
                      </div>
                         <div class="col-md-3">
                        <div class="form-group">
                          <label>Follow up Start Date</label>
                            <input type="date" name="start_date" id="start_date"  class="form-control" value="{{$start_dateSession}}">
                              
                        </div>
                      </div>
                       <div class="col-md-3">
                        <div class="form-group">
                          <label>Follow up End Date</label>
                            <input type="date" name="end_date" id="end_date"  class="form-control"  value="{{$end_dateSession}}">
                              
                        </div>
                      </div>
                       <div class="col-md-3">
                      <div class="form-group">
                          <strong>Status In:</strong>
                          <select class="form-control" id='myselect' multiple>
                            @foreach ($statuses as $status)
                                  <option value="{{ $status->id }}">{{ $status->name }}</option>
                              @endforeach
                          </select>
                          <?php 
                        $statusInSessions1 = \App\CfgStatus::where('id', $statusInSession)->pluck('name');
                       
                      ?>
                       @foreach($statusInSessions1 as $status)
                          <p>{{$status}}</p>
                            
                        @endforeach
                      </div>
                      </div>
                      <div class="col-md-3">
                      <div class="form-group">
                          <strong>Status Not In:</strong>
                          <select class="form-control" id='myselectNotIN' multiple>
                           @foreach ($statuses as $status)
                                  <option value="{{ $status->id }}"  {{ $status->id == $statusNotINSession ? 'selected' : ''}}>{{ $status->name }}</option>
                              @endforeach
                          </select>
                          <?php 
                        $statusNotINSession1 = \App\CfgStatus::where('id', $statusNotINSession)->pluck('name');
                       
                      ?>
                       @foreach($statusNotINSession1 as $status1)
                          <p>{{$status1}}</p>
                            
                        @endforeach
                      </div>
                      </div>
                       <div class="col-md-1" style="padding-top:30px;width:10.33333333%">
                         <button type="button" class="btn btn-primary" onclick="search()">
                      <i class="fa fa-search"></i>&nbsp;Search
                    </button>
                      </div>
                      <div class="col-md-1" style="padding-top:30px">
                         <a   class="btn btn-primary" href="{{route('leads.filter.reset')}}">
                      <i class="fa fa-refresh"></i>&nbsp;
                    </a>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

  

 <div class="row">
              <div class="col-12">
                <div class="">
                  @if (\Session::has('success'))
                    <div class="alert alert-success">
                        
                            {!! \Session::get('success') !!}
                    </div>
                @endif
                  <div class="row">
                    <div class="col-md-1" style="width:4.33333333%;padding-top:4px">
                       <p>Show</p>
                    </div>
                    <div class="col-md-1" style="width:10.33333333%">
                      <select id="sort" class="form-control input-sm" onchange="search()">
                        <option value="10"  {{ $sortSession == '10' ? 'selected' : ''}}>10</option><option value="25" {{ $sortSession == '25' ? 'selected' : ''}}>25</option><option value="50" {{ $sortSession == '50' ? 'selected' : ''}}>50</option><option value="100" {{ $sortSession == '100' ? 'selected' : ''}}>100</option></select>
                    </div>
                    <div class="add-button" style="float:right;margin-bottom:20px;margin-right:20px">
                            <a  class="btn btn-primary btn-md" href="{{route('leads.add')}}">Create Leads</a>
                             <a class="btn btn-primary" data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
    Filter
  </a>
                        </div>
                         <div class="col-md-2">
         <input type="button" value="Download as Excel" class="btn btn-primary mt-2 float-right"  onclick="exportBiometricToExcel('leads_list','leads_list')" >
    </div>
                        <br>
                      </div>
                    <div  id="leads">
                      
                           @include('user.leads.components.filter')
                          
                        
                    </div>

                  </div>
                </div>
              </div>
            </div>
</div>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>

<script>
  $(document).ready(function() {
           var domain = document.getElementById('domain').value;
  var segment_selected = document.getElementById('segment_selected').value;

  var substateArray1 =  @json($segments);
    var filteredArray1 = substateArray1.filter(x => x.domain_id == domain);
    $('#segment').empty();
    $('#segment').append('<option value="">Select Segment</option>');
     var options = filteredArray1.forEach( function(item, index){
      if(item.id == segment_selected)
      {
          $('#segment').append('<option value="'+item.id+'" selected>'+item.name+'</option>');
      }
      else
      {
         $('#segment').append('<option value="'+item.id+'">'+item.name+'</option>');
      }
      });
      
   search();
   
});
function search()
{
        var month = document.getElementById("month");
        month = month.value;
        var status = document.getElementById("status");
        status = status.value;
        var name = document.getElementById("name");
        name = name.value;
        var area = document.getElementById("area");
        area = area.value;
        var website = document.getElementById("website");
        website = website.value;
        var sort = document.getElementById("sort");
        sort = sort.value;
        var page = document.getElementById("page");
        page = page.value;
         var segment = document.getElementById("segment");
        segment = segment.value;
        var domain = document.getElementById("domain");
        domain = domain.value;
        var start_date = document.getElementById("start_date");
        start_date = start_date.value;
        var end_date = document.getElementById("end_date");
        end_date = end_date.value;

        var statusGetIn = window.localStorage.getItem('statusIn');
        var statusGetNotIn = window.localStorage.getItem('statusNotIn');
        console.log(statusGetIn,statusGetNotIn);
        var statusIn = $("#myselect").val();
        window.localStorage.setItem('statusIn',statusIn);
        
        var statusNotIn = $("#myselectNotIN").val();
        window.localStorage.setItem('statusNotIn',statusNotIn);
        
        $.ajax({
          type : 'get',
          url : '{{route('leads.list')}}',
          data : {'month':month,'status':status,'name':name,'area':area,'website':website,'sort':sort,'search':'Y','page':page,'segment':segment,'domain':domain,'start_date':start_date,'end_date':end_date,'statusIn':statusIn,'statusNotIn':statusNotIn},
          success:function(data){
            console.log(data,"data");
           $('#leads').empty();
           $('#leads').html(data['leads']);
         } 
       });
}

function reset()
{
       
        $.ajax({
          type : 'get',
          url : '{{route('leads.filter.reset')}}',
          success:function(data){
            console.log(data);
            location.reload(); 
         } 
       });
}

function Delete(id)
{
    console.log(id,"id");
       
        $.ajax({
          type : 'get',
          url : 'leads/edit'+"/"+id,
          success:function(data){
            console.log(data);
            location.reload(); 
         } 
       });
}

function getSegment(value)
    {
         var substateArray =  @json($segments);
        var filteredArray = substateArray.filter(x => x.domain_id == value);
        $('#segment').empty();
        $('#segment').append('<option value="">Select Segment</option>');
         var options = filteredArray.forEach( function(item, index){
                $('#segment').append('<option value="'+item.id+'">'+item.name+'</option>');
            });
    }
  </script>
  <script src="https://cdn.jsdelivr.net/gh/linways/table-to-excel@v1.0.4/dist/tableToExcel.js"></script>
<script type="text/javascript">
$(document).ready(function(){
 $('[data-toggle="tooltip"]').tooltip();  
});
$('.clicker').click(function(){

  $(this).nextUntil('.clicker').slideToggle('normal');
});

   function viewEmployee()
   {
      var empId = $("#employeeFilter option:selected").val();
      window.location.href = "{{URL::to('/Admin/Report')}}/"+empId;
   
   
   }
</script>
<script type="text/javascript">
   

function exportTableToExcel(tableID, filename, fn, dl) {
       var elt = document.getElementById(tableID);
       var wb = XLSX.utils.table_to_book(elt, { sheet: "sheet1" });
       return dl ?
         XLSX.write(wb, { bookType: 'xlsx', bookSST: true, 'xlsx': 'base64' }):
         XLSX.writeFile(wb, fn || (filename+'.xlsx' ));
    }

    function exportBiometricToExcel(tableID)
    {
       TableToExcel.convert(document.getElementById(tableID));
    
    }
</script>
 
@endsection

