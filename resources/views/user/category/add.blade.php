@extends('layouts.app')

   

@section('content')

<div style="margin-top:1rem">

 <div class="row">
              <div class="col-12">
                    <div class="panel panel-default">
                      
                  <div class="panel-heading">
                    <h4>Create Category</h4>
                  </div>
                  <div class="panel-body">
                    <form method="POST" action="{{ route('category.create') }}">
                         {{ csrf_field() }}
                   <div class="row">

                     <div class="col-md-6 form-group">
                      <label>For</label>
                        <select  name="for" class="form-control">
                          <option value="S">Service</option>
                          <option value="P">Product</option>
                        </select>
                    </div>
                     <div class="col-md-6 form-group">
                      <label>Category</label>
                        <input type="text" name="category" class="form-control" required>
                    </div>
                    
                     <div class="col-md-6 form-group">
                      <label>Status</label>
                        <select  name="status" class="form-control">
                          <option value="Y">Y</option>
                          <option value="N">N</option>
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