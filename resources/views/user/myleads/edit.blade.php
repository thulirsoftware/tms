@extends('layouts.app')

   

@section('content')

<div style="margin-top:1rem">

 <div class="row">
              <div class="col-12">
                    <div class="panel panel-default">
                       @if (\Session::has('success'))
                    <div class="alert alert-success">
                        
                            {!! \Session::get('success') !!}
                        </ul>
                    </div>
                @endif
                  <div class="panel-heading">
                    <h4>Update Leads</h4>
                  </div>
              <div class="panel-body">
               <form method="POST" action="{{ route('leads.ver2.update') }}">
                        {{ csrf_field() }}
                        
                 <input type="hidden" name="id" class="form-control" value="{{$id}}">
                  <input type="hidden" id="category_selected" name="category_selected" class="form-control" value="{{$lead->category}}">
                  
                   
                   <div class="row">
                     
                    <div class="col-md-6 form-group">
                      <label>Category&nbsp;<span style="color:red">*</span></label>
                        <select name="category" id="category" class="form-control" required>
                          <option value="">Select Category</option>
                         @foreach($categories as $category)
                            <option value="{{$category->id}}" {{$category->id == $lead->category ? 'selected' : ''}}>{{$category->category}}</option>
                          @endforeach
                        </select>
                    </div>
                      <!--- <div class="col-md-6 form-group">
                      <label>Segment</label>
                        <input type="text" name="segment" value="{{$lead->segment}}" class="form-control" required >
                    </div>-->
                     <div class="col-md-6 form-group">
                      <label>Name</label>
                        <input type="text" name="name" class="form-control" value="{{$lead->name}}" required>
                    </div>
                    <div class="col-md-6 form-group">
                      <label>Mobile Number</label>
                        <input type="text" name="mobile_number" class="form-control phone-number" value="{{$lead->mobile_no}}" required>
                    </div>
                     <div class="col-md-6 form-group">
                      <label>Area</label>
                        <input type="text" name="area" class="form-control" value="{{$lead->area}}"  >
                    </div>
                     <div class="col-md-12 form-group">
                      <label>Location</label>
                        <textarea  name="location" class="form-control"  required> {{$lead->location}}</textarea>
                    </div>
                     <div class="col-md-6 form-group">
                      <label>Website</label>
                        <input type="text" name="website" class="form-control" value="{{$lead->website}}">
                    </div>
                    <div class="col-md-6 form-group">
                      <label>Whatsapp</label>
                        <input type="text" name="whatsapp" class="form-control" value="{{$lead->whatsapp}}">
                    </div>
                     
                      
                    
                    <div class="col-md-6 form-group">

                      <label>Status</label>
                        <select  name="status" class="form-control" required>
                          <option value="">Select</option>
                           <option value="S" {{"S" == $lead->status ? 'selected' : ''}}>Initial</option>
                             <option value="I" {{"I" == $lead->status ? 'selected' : ''}}>Inprogress</option>
                             <option value="N" {{"N" == $lead->status ? 'selected' : ''}}>No Need</option>
                             <option value="P" {{"P"  == $lead->status ? 'selected' : ''}}>Pending</option>
                             <option value="C" {{"C"  == $lead->status ? 'selected' : ''}}>Converted</option>
                          
                        </select>
                    </div>
                     <div class="col-md-6 form-group">
                      <label>Source</label>
                      <?php
                            $sources = DB::table('cfg_sources')->get();
                      ?>
                       <select  name="source" class="form-control">
                          <option value="">Select Source</option>
                          @foreach($sources as $source)
                            <option value="{{$source->id}}" {{$source->id == $lead->source ? 'selected' : ''}}>{{$source->name}}</option>
                          @endforeach
                        </select>
                        
                    </div>
                       <div class="col-md-6 form-group">
                      <label>Remarks</label>
                        <textarea  name="remarks" class="form-control" >{{$lead->remarks}}</textarea>
                    </div>
                  </div>
                   <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-md ">
                      Update
                    </button>
                  </div>
                </form>
                    
                   
                   
                  </div>
                </div>
              </div>
            </div>
</div>
  <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>

<script type="text/javascript">
$(document).ready( function(){
  var value = document.getElementById('for').value;
  var category_selected = document.getElementById('category_selected').value;

  var substateArray =  @json($categories);
    var filteredArray = substateArray.filter(x => x.for == value);
    $('#category').empty();
    $('#category').append('<option value="">Select Category</option>');
     var options = filteredArray.forEach( function(item, index){
      if(item.id == category_selected)
      {
          $('#category').append('<option value="'+item.id+'" selected>'+item.category+'</option>');
      }
      else
      {
         $('#category').append('<option value="'+item.id+'">'+item.category+'</option>');
      }
      });
      
      
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
});
</script>
<script>
  function getCategory(value){
    var substateArray =  @json($categories);
    var filteredArray = substateArray.filter(x => x.for == value);
    $('#category').empty();
    $('#category').append('<option value="">Select Category</option>');
     var options = filteredArray.forEach( function(item, index){
            $('#category').append('<option value="'+item.category+'">'+item.category+'</option>');
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
@endsection