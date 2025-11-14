@extends('layouts.user')

   

@section('content')

<div style="margin-top:1rem">

 <div class="row">
              <div class="col-12">
                
                    <div class="panel panel-default">
                       @if (\Session::has('success'))
                    <div class="alert alert-success" style="color:white">
                        
                            {!! \Session::get('success') !!}
                    </div>
                @endif
                  <div class="panel-heading">
                    <h4>Edit Documentation</h4>                  </div>
                  <div class="panel-body">
                    <form method="POST" action="{{ route('documentation.update') }}" enctype="multipart/form-data">
                         {{ csrf_field() }}
                        <input type="hidden" name="id" class="form-control" value="{{ request()->id }}">
                   <div class="row">
                     <div class="col-md-12 form-group">
                      <label>Date</label>
                        <input type="date" name="date" class="form-control" value="{{$documentation->date}}" required>
                    </div>
                    <div class="col-md-12 form-group">
                      <label>Description</label>
                        <textarea  name="description" class="form-control" required>{{$documentation->description}}</textarea>
                    </div>
                    <div class="col-md-12 form-group">
                      <label>Upload</label>
                    <div class="custom-file">
                      <input type="file" class="custom-file-input" name="document" id="document" onchange="showname()">
                      <label class="custom-file-label" for="document">Choose file</label>
                    </div><br><br>
                    {{env('APP_URL') . $documentation->document_url}}
                    <p id="selected"> </p>
                  </div>
                        <input type="hidden" name="old_document" class="form-control" value="{{$documentation->document_url}}" required>
                    
                     <div class="col-md-12 form-group">
                      <label>Status</label>
                        <input type="text" name="status" class="form-control" value="{{$documentation->status}}" required>
                    </div>
                  </div>
                   <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-lg ">
                      Update
                    </button>
                  </div>
                </form>
                    
                   
                   
                  </div>
                </div>
              </div>
            </div>
</div>

@endsection
<script>
 function showname () {
      var name = document.getElementById('document'); 
        var output = document.getElementById('selected');
         output.innerHTML = '<b>Selected file : </b>' +  name.files.item(0).name ;
    };
  </script>