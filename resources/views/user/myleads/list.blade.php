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
                     
                    
                   
                        <br>
                      </div>
                    <div  id="leads" class="d-sm-none ">
                      
                           @include('user.myleads.components.filter')
                          
                        
                    </div>
                     <div  id="leads_mobile" class="d-md-none d-sm-block ">
                      
                           @include('user.myleads.components.filter_mobile')
                          
                        
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
          url : '{{route('leads.ver2.myleads')}}',
          data : {'month':month,'status':status,'name':name,'area':area,'website':website,'sort':sort,'search':'Y','page':page,'segment':segment,'domain':domain,'start_date':start_date,'end_date':end_date,'statusIn':statusIn,'statusNotIn':statusNotIn},
          success:function(data){
            console.log(data,"data");
           $('#leads').empty();
           $('#leads_mobile').empty();
           $('#leads').html(data['leads']);
           $('#leads_mobile').html(data['leads_mobile']);
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

function reset()
{
       
        $.ajax({
          type : 'get',
          url : '{{route('leads.ver2.filter.reset')}}',
          success:function(data){
            console.log(data);
            location.reload(); 
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

function Delete(id)
{
    console.log(id,"id");
       
        $.ajax({
          type : 'get',
          url : 'leads/edit'+"/"+id,
          success:function(data){
            console.log(data);
            location.reload(); 
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

