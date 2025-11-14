@extends('layouts.app')

   

@section('content')

<div style="margin-top:1rem">

 <div class="row">
              <div class="col-12">
                    <div class="panel panel-default">
                      
                  <div class="panel-heading">
                    <h4>Update Category</h4>
                  </div>
                  <div class="panel-body">
                    <form method="POST" action="{{ route('category.update') }}">
                         {{ csrf_field() }}
                         <input type="hidden" name="id" class="form-control" value="{{ $category->id }}">
                   <div class="row">
                    <div class="col-md-6 form-group">
                      <label>For</label>
                        <select  name="for" class="form-control">
                          <option value="S"  {{ $category->active == 'S' ? 'selected' : ''}}>Service</option>
                          <option value="P"  {{ $category->active == 'P' ? 'selected' : ''}}>Product</option>
                        </select>
                    </div>
                     <div class="col-md-6 form-group">
                      <label>Category</label>
                        <input type="text" name="category" class="form-control"  value="{{$category->category}}" required>
                    </div>
                    
                    
                     <div class="col-md-6 form-group">
                      <label>Status</label>
                        <select  name="status" class="form-control">
                          <option value="Y" {{ $category->status == 'Y' ? 'selected' : ''}}>Y</option>
                          <option value="N" {{ $category->status == 'N' ? 'selected' : ''}}>N</option>
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