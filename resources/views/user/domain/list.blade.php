@extends('layouts.app')

   

@section('content')

<div style="margin-top:1rem">

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
                            <a  class="btn btn-primary btn-md" href="{{route('category.add')}}">Add</a>
                        </div>
                        <br><br><br>
                    <div class="table-responsive">
                      <table class="table table-bordered" id="leads_list">
                        <thead>
                          <tr>
                            <th class="text-center">
                              #
                            </th>
                            <th>For</th>
                            <th>Name</th>
                            <th>Status</th>
                            <th>Action</th>
                          </tr>
                        </thead>
                        <tbody>
                            @foreach($categories as $i=>$category)
                          
                          <tr>
                            <td>
                              {{$i+1}}
                            </td>
                            @if($category->for=="P")
                            <td ><span class="badge bg-success" style="color:white">Product</span></td>
                            @else
                            <td ><span class="badge bg-info" style="color:white">Service</span></td>
                            @endif
                           <td>{{$category->category}}</td>
                           @if($category->status=="Y")
                            <td ><span class="badge bg-success" style="color:white">Active</span></td>
                            @else
                            <td ><span class="badge bg-danger" style="color:white">Inactive</span></td>
                            @endif
                           
                            <td><a href="{{ route('category.edit', ['id' => $category->id ]) }}"><span class="badge bg-info"><i class="fa fa-edit" style="color: white;"></i></span></a> 
                              
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