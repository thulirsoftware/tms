@extends('layouts.app')

   

@section('content')

<div    style="margin-top:1rem">

 <div class="row">
              <div class="col-12">
                    <div class="panel panel-default">
                      
                  <div class="panel-heading">
                    <h4>Create Status</h4>
                  </div>
                  <div class="panel-body">
                    <form method="POST" action="{{ route('status.update') }}">
                         {{ csrf_field() }}
                         <input type="hidden" name="id" class="form-control" value="{{ $status->id }}">
                   <div class="row">
                     <div class="col-md-6 form-group">
                      <label>Name</label>
                        <input type="text" name="name" class="form-control" value="{{$status->name}}" required>
                    </div>
                    
                     <div class="col-md-6 form-group">
                      <label>Status</label>
                        <select  name="active" class="form-control">
                          <option value="Y" {{ $status->active == 'Y' ? 'selected' : ''}}>Y</option>
                          <option value="N" {{ $status->active == 'N' ? 'selected' : ''}}>N</option>
                        </select>
                    </div>
                  </div>
                   <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-md">
                      Create
                    </button>
                  </div>
                </form>
                    
                   
                   
                  </div>
                </div>
              </div>
            </div>
</div>

@endsection