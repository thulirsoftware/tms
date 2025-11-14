@extends('layouts.report')

   

@section('content')
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
   <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

<style type="text/css">
    #yourBtn {
  position: relative;
   font-family: calibri;
  width: 150px;
  padding: 10px;
  -webkit-border-radius: 5px;
  -moz-border-radius: 5px;
  border: 1px dashed #BBB;
  text-align: center;
  background-color: #DDD;
  cursor: pointer;
}
.testing {
  position: relative;
}
.input-group-btn .dropdown-menu {
   
  min-width: 790px;
  }

</style>
<div class="row">
    <div class="col-md-10">
        
    </div>
     
</div>
<div style="margin-top:1rem" >
        
<form method="get" action=""  style="margin-top:3rem;margin-bottom:3rem" >
     <div class="col-md-8">
        <label>Search</label>
          <div class="input-group">
            <div class="testing">
           <input type="text" name="search"  value="{{ app('request')->input('search') }}"  class="form-control" >
        </div>

     <div class="input-group-btn">
          <div class="dropdown  " id="dropdown_click">
            <button class="btn btn-default " type="button" style="border: 1px solid #ccc;"  onclick="openDropdownMenu()"><span class="caret"></span></button>
            <div class="dropdown-menu dropdown-menu-right " aria-labelledby="dropdownReportMenuButton">
                 
           <div class="col-md-12" style="margin-top:10px">
            <input type="hidden" name="page" value="{{app('request')->input('page')}}">
                    <div class="row">
                        <label for="name" class="col-md-4 col-form-label text-md-right">Category:</label>
                        <div class="col-md-8">
                            <select name="category" id="category" class="form-control"    onchange="loadDistricts()">
                                <option value="">Select Category</option>
                                    @foreach($categories as $category)
                                        <option value="{{$category->id}}"  {{$category->id == app('request')->input('category') ? 'selected' : ''}}>{{$category->category}}</option>
                                    @endforeach
                            </select>
                        </div>
                    </div>
     
          </div>
           <div class="col-md-12" style="margin-top:10px">
                    <div class="row">
                        <label for="name" class="col-md-4 col-form-label text-md-right">District:</label>
                        <div class="col-md-8">
                            <select name="district" id="district" class="form-control"   onchange="loadAreas()">
                                <option value="">Select District</option>
                                    
                            </select>
                        </div>
                    </div>
     
          </div>
          <div class="col-md-12" style="margin-top:10px">
                    <div class="row">
                        <label for="name" class="col-md-4 col-form-label text-md-right">Area:</label>
                        <div class="col-md-8">
                            <select name="area" id="area" class="form-control"  >
                                <option value="">Select Area</option>
                                    
                            </select>
                        </div>
                    </div>
     
          </div>
          
          <div class="col-md-12" style="margin-top:10px">
                    <div class="row">
                      <label for="name" class="col-md-4 col-form-label text-md-right">Follow Up Status:</label>
                         <div class="col-md-8">
                     <select multiple placeholder="Choose Status" name="status[]" data-allow-clear="1" id="mySelect2" style="width: 500px;">
                         <?php 
                            $statusrow = \App\CfgStatus::where('active','Y')->get();
                         ?>
                         @foreach($statusrow as $statusrow)
                         
                             <option value="{{$statusrow->id}}">{{$statusrow->name}}</option>
                          @endforeach
                      </select>
                </div>
                </div>
     
          </div>
          <?php 
             
            if(app('request')->input('daterange') ==null)
            {
                $date = null;
            }
            else
            {
                $date = app('request')->input('daterange') ;
            }
             
          ?>
          <div class="col-md-12" style="margin-top:10px">
                    <div class="row">
                      <label for="name" class="col-md-4 col-form-label text-md-right">Call Date:</label>
                         <div class="col-md-8">
                             <div class="row">
                                 <div class="col-md-6">
                                     <label>From Date</label>
                                       <input type="date" class="form-control " name="from_call_date" value="{{ app('request')->input('from_call_date') }}"   />
                                 </div>
                                   <div class="col-md-6">
                                     <label>To Date</label>
                                       <input type="date" class="form-control " name="from_to_date" value="{{ app('request')->input('from_to_date') }}"   />
                                 </div>
                             </div>
                           
                            
                        </div>
                    </div>
           </div>
            
      <input type="hidden" name="month"  value="{{ app('request')->input('month') }}"  class="form-control" >
            <div class="col-md-1" style="margin-left: 20px;padding-bottom: 15px;margin-top: 25px;">
                <input type="submit" class="btn btn-success" value="Search">
            </div>
            <?php 
            use Illuminate\Support\Arr;
                $oldQuery = request()->query(); 
                
                $newQuery = Arr::except($oldQuery, ['category','district','status','area','location', 'month']);
                  $reset_url = \Request::url();
                  $reset_url1 = null;
                  foreach($newQuery as $i=>$query)
                 {
                    if($i=='search')
                    {
                        $reset_url = $reset_url.'?'.$i.'='.$query;
                      }
                    else
                    {
                        $reset_url = $reset_url.'&'.$i.'='.$query;
                    }
                    
                 }
              ?>
            <div class="col-md-1" style="margin-left: 20px;padding-bottom: 15px;;margin-top: 25px">
                <a href="{{$reset_url}}" class="btn btn-warning" value=""> <i class="fa fa-refresh" aria-hidden="true"></i></a>
            </div>
             <div class="col-md-3" style="margin-left: 20px;padding-bottom: 15px;;margin-top: 25px">
                

  <!--<div id="yourBtn" onclick="getFile()">Upload Excel File</div>
   this is your file input tag, so i hide it!-->
  <!-- i used the onchange event to fire the form submission
  <div style='height: 0px;width: 0px; overflow:hidden;'><input id="upfile" type="file" value="upload" onchange="sub(this)" accept=".xlsx" /></div>-->
 

            </div>
            </div>
          </div>
        </div>
   
    </div><!-- /input-group -->

              
         @if(app('request')->input('status')!=null)
                <?php 
                 $tags = implode(', ',app('request')->input('status'));
                ?>
                  <input type="hidden" id="status_selected" class="form-control" value=" {{$tags}}" >
                 @else
                 <input type="hidden" id="status_selected" class="form-control" value="  " >
                 @endif
       </div>
      <div class="col-md-2">
         <div class="col-md-12" style="margin-left: 20px;padding-bottom: 15px;margin-top: 25px;">
               <button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#chartModel">Chart</button>
            </div>
      </div>
        <div class="col-md-2">
         <div class="col-md-12" style="margin-left: 20px;padding-bottom: 15px;margin-top: 25px;">
               <button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#chartModel2">Chart 2</button>
            </div>
      </div>
  
     
    </form>
  
<div id="chartModel" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"> </h4>
      </div>
      <div class="modal-body">
        @if($chart!=null && count($chart->datasets)!=0)
          {!! $chart->container() !!}
          @endif
      </div>
       
    </div>

  </div>
</div>
<div id="chartModel2" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
               <h4 class="modal-title" ><p style="font-weight:bold">No of Leads : {{$leads->total()}}</p> </h4>

      </div>
      <div class="modal-body">
          
         @if($status_chart!=null && count($status_chart->datasets)!=0)
          {!! $status_chart->container() !!}
          @endif
      </div>
       
    </div>

  </div>
</div>
 <div class="row">
               
                    <div  id="leads">
                      
                       @include('user.reportsver2.components.filter')
                          
                        
                    </div>

                  </div>
                 
                </div>
              </div>
            </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script>
@if($chart!=null && count($chart->datasets)!=0)
{!! $chart->script() !!}
@endif
@if($status_chart!=null && count($status_chart->datasets)!=0)
{!! $status_chart->script() !!}
@endif
 
  <script type="text/javascript">
 
    var status_selected = document.getElementById('status_selected').value;
    const array = status_selected.split(',');
    const trimArray = array.map(element => {
  return element.trim();
});
              $('#mySelect2').val( trimArray );
            
        </script>
 
   
 
 <script type="text/javascript">
$(document).ready(function(){
 

  $(function() {
    $('input[name="daterange"]').daterangepicker({
       opens: 'center',
      autoUpdateInput:false,
      locale: {
        format: 'YYYY/MM/DD'
      }
    });
      $('input[name="daterange"]').on('apply.daterangepicker', function(ev, picker) {
      $(this).val(picker.startDate.format('YYYY/MM/DD') + ' - ' + picker.endDate.format('YYYY/MM/DD'));
  });

  $('input[name="daterange"]').on('cancel.daterangepicker', function(ev, picker) {
      $(this).val('');
  });
  });
});
 

function  openDropdownMenu( ) {
    const box1 = document.querySelector('#dropdown_click');
    box1.classList.contains('open'); 
     if(!box1.classList.contains('open'))
    {

      var element = document.getElementById("dropdown_click");
      element.classList.add("open");
    }
    else
    {
        var element = document.getElementById("dropdown_click");
        element.classList.remove("open");
    }
  
}
</script>
<script>
  console.log($('#category').val());
    if($('#category').val()!="")
    {
        const urldistrictParams = new URLSearchParams(window.location.search);
        const districtparam = urldistrictParams.get('district');
         loadDistricts();
    }
     

    function loadDistricts(){
        
         var category_id = $('#category').val();
         $.ajax({
            type : 'get',
            url: "{{route('reportsver2.search.district')}}",
            data : {'category':category_id},
            success:function(data){
                $('#district').empty();
                $('#district').append('<option value="">Select District</option>');
                var options = data.forEach( function(item, index){
                    const urldistrictParams = new URLSearchParams(window.location.search);
                    if(urldistrictParams.has('district'))
                    {
                         loadAreas();

                        const districtparam = urldistrictParams.get('district');
                        if(districtparam==item.district)
                        {
                            $('#district').append('<option value="'+item.district+'" selected>'+item.district+'</option>');
                        }
                        else
                        {
                            $('#district').append('<option value="'+item.district+'">'+item.district+'</option>');
                        }
                        
                    }
                    else
                    {
                        $('#district').append('<option value="'+item.district+'">'+item.district+'</option>');
                    }
        

                 });
               
             } 
       });
       
     }

  function loadAreas(){
          var district = $('#district').val();
         var category_id = $('#category').val();
         $.ajax({
            type : 'get',
            url: "{{route('reportsver2.search.area')}}",
            data : {'district':district,'category':category_id},
            success:function(data){
                  $('#area').empty();
                 $('#area').append('<option value="">Select Area</option>');
                var options = data.forEach( function(item, index){
                    const urlareaParams = new URLSearchParams(window.location.search);
                    if(urlareaParams.has('area'))
                    {
                        const areaparam = urlareaParams.get('area');
                        if(areaparam==item.area)
                        {
                            $('#area').append('<option value="'+item.area+'" selected>'+item.area+'</option>');
                        }
                        else
                        {
                            $('#area').append('<option value="'+item.area+'">'+item.area+'</option>');
                        }
                        
                    }
                    else
                    {
                        $('#area').append('<option value="'+item.area+'">'+item.area+'</option>');
                    }

                 });
               
             } 
       });
       
    }
    </script>
@endsection

