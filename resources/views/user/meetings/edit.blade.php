@extends('layouts.user')

   

@section('content')

<div class="container">

 <div class="row">
              <div class="col-12">
                
                    <div class="panel panel-default">
                       @if (\Session::has('success'))
                    <div class="alert alert-success" style="color:white">
                        
                            {!! \Session::get('success') !!}
                    </div>
                @endif
                  <div class="panel-heading">
                    <h4>Edit Meeting</h4>                  </div>
                  <div class="panel-body">
                    <form method="POST" action="{{ route('meeting.update') }}">
                         {{ csrf_field() }}
                        <input type="hidden" name="id" class="form-control" value="{{ request()->id }}">
                   <div class="row">
                     <div class="col-md-12 form-group">
                      <label>Date</label>
                        <input type="date" name="date" class="form-control" value="{{$meeting->date}}" required>
                    </div>
                    <div class="col-md-12 form-group">
                      <label>Description</label>
                        <textarea  name="description" class="form-control" required>{{$meeting->description}}</textarea>
                    </div>
                    
                     <div class="col-md-12 form-group">
                      <label>Status</label>
                        <input type="text" name="status" class="form-control" value="{{$meeting->status}}" required>
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