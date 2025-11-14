@extends('layouts.app')

   

@section('content')
<style type="text/css">
  
  /* On screens that are 992px or less, set the background color to blue */
@media screen and (max-width: 1200px) {
 .d-sm-none{
    display: none;
  }
  .d-sm-block{
    display: block !important;
   }
}
.d-md-none{
    display: none;
  }
</style>
<div class="row">
    <div class="col-md-10">
        
    </div>
     
</div>
<div style="margin-top:1rem" >
          <div class="row collapse" id="collapseExample">
            <form method="get" action=""  style="margin-top:3rem;margin-bottom:3rem" >

              <div class="col-md-12">
                <div class="panel panel-default">
                  <div class="panel-body">
                    <div class="row">
                       <?php 
                            $statusSession =   app('request')->input('status') ;
                             $monthSession = app('request')->input('month');
                            $nameSession = app('request')->input('name');
                            $websiteSession = app('request')->input('website');
                            $areaSession = app('request')->input('district');
                            $sortSession = app('request')->input('sort');
                             $start_dateSession = app('request')->input('start_date');
                            $end_dateSession = app('request')->input('end_date');

                            $statusInSession = app('request')->input('statusIn');
                            $statusNotINSession = app('request')->input('statusNotIn');
                          ?>
                          <input type="hidden" id="page" value="{{app('request')->input('page')}}">
                   
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
                               <option value="S"  {{ $statusSession == 'S' ? 'selected' : ''}}>Initial</option>
                             <option value="I" {{ $statusSession == 'I' ? 'selected' : ''}}>Inprogress</option>
                             <option value="N" {{ $statusSession == 'N' ? 'selected' : ''}}>No Need</option>
                             <option value="P" {{ $statusSession == 'P' ? 'selected' : ''}}>Pending</option>
                             <option value="C" {{ $statusSession == 'C' ? 'selected' : ''}}>Converted</option>
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
                          <label>District</label>
                            <select id="district" name="district" class="form-control">
                              <option value="">--- Select  District ---</option>
                              @foreach ($areas as $area)
                                  <option value="{{ $area->area }}" {{ $area->area  == $areaSession ? 'selected' : ''}}>{{ $area->area }}</option>
                              @endforeach
                          </select>
                        </div>
                      </div>
                      
                         <div class="col-md-3">
                        <div class="form-group">
                          <label>Call From Date</label>
                            <input type="date" name="start_date" id="start_date"  class="form-control" value="{{$start_dateSession}}">
                              
                        </div>
                      </div>
                       <div class="col-md-3">
                        <div class="form-group">
                          <label>Call To Date</label>
                            <input type="date" name="end_date" id="end_date"  class="form-control"  value="{{$end_dateSession}}">
                              
                        </div>
                      </div>
                        
                      
                       <div class="col-md-1" style="padding-top:30px;width:10.33333333%">
                         <button type="submit" class="btn btn-primary"  >
                      <i class="fa fa-search"></i>&nbsp;Search
                    </button>
                      </div>
                      <div class="col-md-1" style="padding-top:30px">
                         <a   class="btn btn-primary" href="{{route('leads.ver2.filter.reset')}}">
                      <i class="fa fa-refresh"></i>&nbsp;
                    </a>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </form>
            </div>

  

 <div class="row">
              <div class="col-md-12">
                <div class="">
                  @if (\Session::has('success'))
                    <div class="alert alert-success">
                        
                            {!! \Session::get('success') !!}
                    </div>
                @endif
                  <div class="row">
                     
                    <div class="col-md-2"  >
                       
                    </div>
                    <div class="add-button" style="float:right;margin-bottom:20px;margin-right:20px">
                            <a  class="btn btn-primary btn-md" href="{{route('leads.ver2.add')}}">Create Leads</a>
                             <a class="btn btn-primary" data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
    Filter
  </a>
                        </div>
                         <div class="col-md-2">
          
    </div>
                        <br>
                      </div>
                    <div  id="leads" class="d-sm-none ">
                      
                           @include('user.leadsver2.components.filter')
                          
                        
                    </div>
                     <div  id="leads_mobile" class="d-md-none d-sm-block ">
                      
                           @include('user.leadsver2.components.filter_mobile')
                          
                        
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

         
        
        $.ajax({
          type : 'get',
          url : '{{route('leads.ver2.list')}}',
          data : {'month':month,'status':status,'name':name,'area':area,'website':website,'sort':sort,'search':'Y','page':page,'segment':segment,'domain':domain,'start_date':start_date,'end_date':end_date},
          success:function(data){
            console.log(data,"data");
           $('#leads').empty();
           $('#leads_mobile').empty();
           $('#leads').html(data['leads']);
           $('#leads_mobile').html(data['leads_mobile']);
         } 
       });
}

function reset()
{
       
        $.ajax({
          type : 'get',
          url : '{{route('leads.ver2.filter.reset')}}',
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

