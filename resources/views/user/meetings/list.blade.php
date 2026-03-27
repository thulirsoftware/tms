@extends('layouts.user')

   

@section('content')

<div class="container">

 <div class="row">
              <div class="col-12">
                <div class="panel panel-default">
                  @if (\Session::has('success'))
                    <div class="alert alert-success">
                        
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
                            @foreach($meetings as $i=>$meeting)
                            <?php
                            $lead = \App\Leads::where('id',$meeting->leads_id)->first();

                          ?>
                          <tr>
                            <td>
                              {{$i+1}}
                            </td>
                            <td>{{$lead->name}}</td>
                           
                            <td>{{$meeting->date}}</td>
                            <td>{{$meeting->status}}</td>
                            <td><a href="{{ route('meeting.edit', ['id' => $meeting->id ]) }}"><span class="badge bg-info"><i class="fas fa-edit" style="color: white;"></i></span></a> 
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