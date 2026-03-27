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
   
  min-width: 810px;
  }

</style>
<div  style="margin-top:3rem">
<div class="row"  style="margin-top:3rem">
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
                            <select name="category" id="category" class="form-control"  onchange="loadDistricts()">
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
          
         
          
          
     
            <div class="col-md-1" style="margin-left: 20px;padding-bottom: 15px;margin-top: 25px;">
                <input type="submit" class="btn btn-success" value="Search">
            </div>
            <?php 
            use Illuminate\Support\Arr;
                $oldQuery = request()->query(); 
                
                $newQuery = Arr::except($oldQuery, ['category','district','area','search']);
                  $reset_url = \Request::url();
                 foreach($newQuery as $i=>$query)
                 {
                    if($i=='page')
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
 

            </div>
            </div>
          </div>
        </div>
   
    </div><!-- /input-group -->

     
       </div>
   
     
    </form>

 
</div>
 <div class="row"  style="margin-top:3rem">
              <div class="col-12">
                <div class="">
                  @if (\Session::has('success'))
                    <div class="alert alert-success">
                        
                            {!! \Session::get('success') !!}
                    </div>
                @endif
                  
                    <div  id="leads">
                      
                 <table class="table table-striped table-bordered">
                        <thead>
                          <tr>
                            <th>S.No</th>
                            <th>Category</th>
                             <th>District</th>
                             <th>Area</th>
                             <th>No Of Leads</th>
                             <th>Action</th>
                          </tr>
                        </thead>
                        @if(app('request')->input('category')!=null)
                        <tbody id="areas_list">
                        @foreach($leads as $i=>$lead)
                         <?php 
                            $category = \App\cfgCategory::where('id',$lead->category)->first();

                          
                          ?>

                           <tr>
                             <td>{{$i+1}}</td>
                             @if($category!=null)
                              <td>{{$category->category}}</td>
                              @else
                              <td>-</td>
                              @endif
                            <td>{{$lead->district}}</td>
                             <td>{{$lead->area}}</td>
                             <td>{{$lead->total}}</td>
                              <td><a href="{{ route('leads.reportsver2.filter',  [ 'search' => '','area' =>  $lead->area , 'category' => $lead->category, 'month' => '', 'district' => $lead->district] ) }}"><span class="badge bg-info" style="background-color:#17a2b8"><i class="fa fa-eye" style="color: white;"></i></span>
                                </a> </td>
                         </tr>
                         @endforeach
                          </tbody>
                          @endif
                      </table>
               @if(app('request')->input('category')!=null)        
{{ $leads->appends($_GET)->links() }}
 @endif

                          
                        
                    </div>

                  </div>
                </div>
              </div>
            </div>
     <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>

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
               
             },
          error: function (xhr, status, error) {
              if (xhr.status === 419) {
                  alert('CSRF token mismatch');
                  location.reload(true);
              } else {
                  console.error("Error: " + error);
                  alert('An error occurred. Please try again later.');
              }
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
               
             },
          error: function (xhr, status, error) {
              if (xhr.status === 419) {
                  alert('CSRF token mismatch');
                  location.reload(true);
              } else {
                  console.error("Error: " + error);
                  alert('An error occurred. Please try again later.');
              }
          }  
       });
       
    }

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
</script>
@endsection

