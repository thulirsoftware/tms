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
                  <div class="panel-body">
                    
                    <div class="table-responsive">
                      <table class="table table-bordered" id="leads_list">
                        <thead>
                          <tr>
                            <th class="text-center">
                              #
                            </th>
                            <th>Lead</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Action</th>
                          </tr>
                        </thead>
                        <tbody>
                            @foreach($demos as $i=>$demo)
                            <?php
                            $lead = \App\Leads::where('id',$demo->leads_id)->first();

                          ?>
                          <tr>
                            <td>
                              {{$i+1}}
                            </td>
                            
                            <td>{{$lead->name}}</td>
                           
                            <td>{{$demo->date}}</td>
                            <td>{{$demo->status}}</td>
                            <td><a href="{{ route('demo.edit', ['id' => $demo->id ]) }}"><span class="badge bg-info"><i class="fas fa-edit" style="color: white;"></i></span></a> 
                                </td>
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