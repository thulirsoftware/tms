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
   
  min-width: 1000px;
  }

</style>
<div class="row">
    <div class="col-md-10">
        
    </div>
     
</div>
<div style="margin-top:1rem" >
        
<form method="get" action=""  style="margin-top:3rem;margin-bottom:3rem" >
     <div class="col-md-10">
        <label>Search</label>
          <div class="input-group">
            <div class="testing">
           <input type="text" name="search"  value="{{ app('request')->input('search') }}"  class="form-control" >
        </div>

     <div class="input-group-btn">
          <div class="dropdown" id="dropdown_click">
            <button class="btn btn-default dropdown-toggle" type="button"   onclick="openDropdownMenu()" style="border: 1px solid #ccc;" ><span class="caret"></span></button>
            <div class="dropdown-menu dropdown-menu-right " aria-labelledby="dropdownReportMenuButton">
                <div class="col-md-12" style="margin-top:10px">
                    <div class="row">
                      <label for="name" class="col-md-4 col-form-label text-md-right">Name:</label>
                         <div class="col-md-8">
                    <input type="text" class="form-control " name="name"   placeholder="Enter name" id="name" value="{{ app('request')->input('name') }}">
                </div>
                </div>
     
          </div>
          <div class="col-md-12" style="margin-top:10px">
                    <div class="row">
                      <label for="name" class="col-md-4 col-form-label text-md-right">Mobile:</label>
                         <div class="col-md-8">
                   <input type="text" class="form-control " name="mobile_no"  placeholder="Enter Mobile No" id="mobile_no"  value="{{ app('request')->input('mobile_no') }}">
                </div>
                </div>
     
          </div>
           <div class="col-md-12" style="margin-top:10px">
                    <div class="row">
                      <label for="name" class="col-md-4 col-form-label text-md-right">Location:</label>
                         <div class="col-md-8">
                    <input type="text" class="form-control " name="location"  placeholder="Enter location" id="location"  value="{{ app('request')->input('location') }}">
                </div>
                </div>
     
          </div>
          <div class="col-md-12" style="margin-top:10px">
                    <div class="row">
                      <label for="name" class="col-md-4 col-form-label text-md-right">Status:</label>
                         <div class="col-md-8">
                     <select multiple placeholder="Choose Status" name="status[]" data-allow-clear="1" id="mySelect2" style="width: 635px;">
                         <?php 
                            $statusrow = \App\CfgStatus::get();
                         ?>
                         @foreach($statusrow as $statusrow)
                         
                             <option value="{{$statusrow->id}}">{{$statusrow->name}}</option>
                          @endforeach
                      </select>
                </div>
                </div>
     
          </div>
           <div class="col-md-12" style="margin-top:10px">
                    <div class="row">
                      <label for="name" class="col-md-4 col-form-label text-md-right">Date:</label>
                         <div class="col-md-8">
                             <input type="text" class="form-control " name="daterange" value="{{ app('request')->input('daterange') }}" readonly />
                        </div>
                    </div>
           </div>
     
            <div class="col-md-1" style="margin-left: 20px;padding-bottom: 15px;margin-top: 25px;">
                <input type="submit" class="btn btn-success" value="Search">
            </div>
            <?php 
            use Illuminate\Support\Arr;
                $oldQuery = request()->query(); 
                
                $newQuery = Arr::except($oldQuery, ['name','mobile_no','status','location','daterange']);
                  $reset_url = \Request::url();
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
                <input type="text" name="segment" class="form-control" value="{{ app('request')->input('segment') }}">
                 <input type="hidden" id="status_selected" class="form-control" value=" {{$tags}}" >
                 @else
                 <input type="hidden" id="status_selected" class="form-control" value="  " >
                 @endif
                <input type="hidden" name="area" class="form-control" value="{{ app('request')->input('area') }}" >
                <input type="hidden" name="domain" class="form-control" value="{{ app('request')->input('domain') }}">
                <input type="hidden" name="segment" class="form-control" value="{{ app('request')->input('segment') }}">
     </div>
  
     
    </form>
  

 <div class="row">
               
                    <div  id="leads">
                      
                       @include('user.reports.components.filter')
                          
                        
                    </div>

                  </div>
                </div>
              </div>
            </div>
</div>
 

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
function getFile() {
  document.getElementById("upfile").click();
}

function sub(obj) {
  var file = obj.value;
  var fileName = file.split("\\");
  document.getElementById("yourBtn").innerHTML = fileName[fileName.length - 1];
 
   var files = document.getElementById("upfile").files;

    if(files.length > 0 ){

         var formData = new FormData();
         formData.append("file", files[0]);
}
 
$.ajax({
   type: "POST",
    data: formData,
   processData: false,
    contentType: false,
    error: function (xhr, status, error) {
        if (xhr.status === 419) {
            alert('CSRF token mismatch');
            location.reload(true);
        } else {
            console.error("Error: " + error);
            alert('An error occurred. Please try again later.');
        }
    },
   success : function (result) {
      console.log('success', result)
   }
});
  //document.myForm.submit();
 // event.preventDefault();
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

@endsection

