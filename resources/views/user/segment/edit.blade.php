@extends('layouts.app')

   

@section('content')

<div style="margin-top:1rem">

 <div class="row">
              <div class="col-12">
                    <div class="panel panel-default">
                      
                  <div class="panel-heading">
                    <h4>Update Segment</h4>
                  </div>
                  <div class="panel-body">
                    <form method="POST" action="{{ route('segment.update') }}">
                         {{ csrf_field() }}
                         <input type="hidden" name="id" class="form-control" value="{{ $segment->id }}">
                   <div class="row">
                    <?php
                        $domains = \App\CfgDomain::where('status','Y')->get();
                       ?>
                    <div class="col-md-6 form-group">
                      <label>Domain</label>
                        <select  name="domain" class="form-control">
                            
                          <option value="">Select Domain</option>
                         @foreach($domains as $domain)
                          <option value="{{$domain->id}}" {{ $segment->domain_id ==$domain->id ? 'selected' : ''}}>{{$domain->name}}</option>
                          @endforeach
                        </select>
                    </div>
                     <div class="col-md-6 form-group">
                      <label>Name</label>
                        <input type="text" name="name" class="form-control"  value="{{$segment->name}}" required>
                    </div>
                    
                    
                     <div class="col-md-6 form-group">
                      <label>Status</label>
                        <select  name="status" class="form-control">
                          <option value="Y" {{ $segment->status == 'Y' ? 'selected' : ''}}>Y</option>
                          <option value="N" {{ $segment->status == 'N' ? 'selected' : ''}}>N</option>
                        </select>
                    </div>
                  </div>
                   <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-md">
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