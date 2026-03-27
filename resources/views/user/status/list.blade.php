@extends('layouts.app')

   

@section('content')

<div  style="margin-top:1rem">

 <div class="row">
              <div class="col-12">
                <div class="panel panel-default">
                  @if (\Session::has('success'))
                    <div class="alert alert-success">
                        
                            {!! \Session::get('success') !!}
                    </div>
                @endif
                  <div class="panel-body">
                    <div class="add-button" style="float:right">
                            <a  class="btn btn-primary btn-md" href="{{route('status.add')}}">Add</a>
                        </div>
                        <br> <br> <br>
                    <div class="">
                      <table class="table table-striped table-bordered table-hover">
                        <thead>
                          <tr>
                            <th>
                              #
                            </th>
                            <th>Status Id</th>
                            <th>Name</th>
                            <th>Status</th>
                            <th>Action</th>
                          </tr>
                        </thead>
                        <tbody>
                            @foreach($statuses as $i=>$status)
                          
                          <tr>
                            <td>
                              {{$i+1}}
                            </td>
                            <td>{{$status->id}}</td>
                            <td>{{$status->name}}</td>
                           
                             @if($status->active=="Y")
                                <td style="color:green;font-weight:bold">Active</td>
                            @else
                                 <td style="color:red;font-weight:bold">Inactive</td>
                            @endif
                           
                            <td><a href="{{ route('status.edit', ['id' => $status->id ]) }}"><span class="badge bg-info"><i class="fa fa-edit" style="color: white;"></i></span></a> 
                              
                          </tr>
                          @endforeach
                         
                         
                          
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            </div>
</div>

@endsection