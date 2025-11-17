@extends('layouts.app')

   

@section('content')

<div style="margin-top:1rem">

 <div class="row">
              <div class="col-12">
                    <div class="panel panel-default">
                      
                  <div class="panel-heading">
                    <h4>Create Leads</h4>
                  </div>
                  <div class="panel-body">
                    <form method="POST" action="{{ route('leads.create') }}">
                        {{ csrf_field() }}
                   <div class="row">
                     <div class="col-md-6 form-group">
                      <label>For&nbsp;<span style="color:red">*</span></label>
                        <select  name="for" class="form-control" onchange="getCategory(this.value)" required>
                          <option value="">Select</option>
                          <option value="S">Service</option>
                          <option value="P">Product</option>
                        </select>
                    </div>
                    <div class="col-md-6 form-group">
                      <label>Category&nbsp;<span style="color:red">*</span></label>
                        <select name="category" id="category" class="form-control" required>
                          <option value="">Select Category</option>
                         
                        </select>
                    </div>
                      <!---<div class="col-md-6 form-group">
                      <label>Segment</label>
                        <input type="text" name="segment" class="form-control" required >
                    </div>-->
                     <div class="col-md-6 form-group">
                      <label>Name&nbsp;<span style="color:red">*</span></label>
                      <div class="input-group">
                        <input type="text" class="form-control" placeholder="Enter name" name="name" id="name"  required>
                        <span class="input-group-btn">
                          <button class="btn btn-default" type="button" onclick="getData()">Get</button>
                        </span>
                      </div>
                    </div>
                    <div class="col-md-6 form-group">
                      <label>Contact Details&nbsp;<span style="color:red">*</span></label>
                      <div class="input-group">
                        <input type="text" class="form-control" placeholder="Enter Contact Details" name="mobile_number" id="mobile_number"  required>
                        <span class="input-group-btn">
                          <button class="btn btn-default" type="button" onclick="checkduplicate()"><i class="fa fa-search"></i></button>
                        </span>
                      </div>

                    </div>
                     <div class="col-md-6 form-group">
                      <label>Area&nbsp;<span style="color:red">*</span></label>
                        <input type="text" name="area" class="form-control" required>
                    </div>
                     <div class="col-md-6 form-group">
                      <label>Location&nbsp;<span style="color:red">*</span></label>
                        <textarea  name="location" class="form-control" required></textarea>
                    </div>
                     <div class="col-md-6 form-group">
                      <label>Website</label>
                        <input type="text" name="website" class="form-control" >
                    </div>
                     <div class="col-md-6 form-group">
                      <label>Whatsapp</label>
                        <input type="text" name="whatsapp" class="form-control" >
                    </div>
                    <?php
                          $status = \App\CfgStatus::get();
                          $segments = \App\CfgSegment::where('status','Y')->get();
                          $domains = \App\CfgDomain::where('status','Y')->get();
                      ?>
                      <div class="col-md-6 form-group">
                      <label>Domain&nbsp;<span style="color:red">*</span></label>
                      <select  name="domain" class="form-control"  onchange="getSegment(this.value)"  required>
                          <option value="">Select Domain</option>
                          @foreach($domains as $domain)
                            <option value="{{$domain->id}}">{{$domain->name}}</option>
                          @endforeach
                        </select>
                    </div>
                      <div class="col-md-6 form-group">
                      <label>Segment&nbsp;<span style="color:red">*</span></label>
                        <select  name="segment" class="form-control" id="segment" required>
                          <option value="">Select Segment</option>
                        
                        </select>
                    </div>
                  
                    
                     <div class="col-md-6 form-group">
                         
                      <label>Status&nbsp;<span style="color:red">*</span></label>
                        <select  name="status" class="form-control">
                          <option value="">Select</option>
                          @foreach($status as $status)
                            <option value="{{$status->id}}">{{$status->name}}</option>
                          @endforeach
                        </select>
                    </div>
                       <div class="col-md-6 form-group">
                      <label>Source&nbsp;<span style="color:red">*</span></label>
                      <?php
                            $sources = DB::table('cfg_sources')->get();
                      ?>
                       <select  name="source" class="form-control" required>
                          <option value="">Select Source</option>
                          @foreach($sources as $source)
                            <option value="{{$source->id}}">{{$source->name}}</option>
                          @endforeach
                        </select>
                        
                    </div>
                    <div class="col-md-6 form-group">
                      <label>Remarks</label>
                        <textarea  name="remarks" class="form-control"></textarea>
                    </div>
                   
                    
                  </div>
                   <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-md ">
                      Create
                    </button>
                  </div>
                </form>
                    
                   
                   
                  </div>
                </div>
              </div>
            </div>
</div>
<div id="dataModal" class="modal fade" role="dialog">
  <div class="modal-dialog  modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title" id="jewellery_name"></h4>
      </div>
      <div class="modal-body">
         <table class="table table-bordered" id="leads_list">
                        <thead>
                          <tr>
                            <th>S.No</th>
                            <th>Name</th>
                            <th>Date</th>
                            <th>Mobile</th>
                            <th>Area</th>
                            <th>Location</th>
                            <th>Website</th>
                            <th>Status</th>
                          </tr>
                        </thead>
                        <tbody id="leads">
                           
                          
                        </tbody>
                      </table>
      </div>
     
    </div>

  </div>
</div>
 <script src="https://code.jquery.com/jquery-1.11.1.min.js"></script>
 
<script src="https://code.jquery.com/ui/1.11.1/jquery-ui.min.js"></script>
<script>
  function getCategory(value){
    var substateArray =  @json($categories);
    var filteredArray = substateArray.filter(x => x.for == value);
    $('#category').empty();
    $('#category').append('<option value="">Select Category</option>');
     var options = filteredArray.forEach( function(item, index){
            $('#category').append('<option value="'+item.id+'">'+item.category+'</option>');
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
  function getData()
  {
      var name = document.getElementById('name').value;
      if(name=="")
      {
          alert('Must enter name');
      }
      else
      {
          $.ajax({
            type : 'get',
            url : '{{route('leads.getdata')}}',
            data : {'lead_name':name,'mobile_no':null},
            success:function(data){
              console.log(data);
             $('#leads').empty();
             $('#leads').html(data['leads']);
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
          $('#dataModal').modal('show'); 
      }
  }
  function checkduplicate()
  {
    var mobileNo = document.getElementById('mobile_number').value;
    if(mobileNo=="")
    {
      alert('Must enter contact detail');
    }
    else
    {
        $.ajax({
            type : 'get',
            url : '{{route('leads.getdata')}}',
            data : {'lead_name':null,'mobile_no':mobileNo},
            success:function(data){
              console.log(data);
             $('#leads').empty();
             $('#leads').html(data['leads']);
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
          $('#dataModal').modal('show'); 
    }
    


  }
</script>
@endsection