@extends('layouts.app')

@section('content')
    <div class="container" style="padding-top:30px">
        <form method="POST" action="{{route('create.availableleads')}}">
           {{ csrf_field() }}
        <div class="row">
            <div class="col-md-12 ">
                <div class="panel panel-default">
                    <div class="panel-heading">Available Leads</div>

                    <div class="panel-body">
                        <table class="table table-bordered">
                            <thead>
                                <th>#</th>
                                <th>Name</th>
                                <th>Mobile No</th>
                                <th>Status</th>
                            </thead>
                            <tbody>
                                  @foreach($data  as $i=>$arr)
                                  <input type="hidden" name="name[]" value="{{$arr['name']}}">
                                  <input type="hidden" name="mobile_no[]" value="{{$arr['mobile_no']}}">
                                  <input type="hidden" name="available_status[]" value="{{$arr['available_status']}}">
                                  <input type="hidden" name="category[]" value="{{$arr['category']}}">
                                  <input type="hidden" name="date[]" value="{{$arr['date']}}">

                                    <input type="hidden" name="approach_status[]" value="{{$arr['approach_status']}}">
                                    <input type="hidden" name="area[]" value="{{$arr['area']}}">
                                    <input type="hidden" name="district[]" value="{{$arr['district']}}">
                                    <input type="hidden" name="location[]" value="{{$arr['location']}}">
                                    <input type="hidden" name="status[]" value="{{$arr['status']}}">
                               <tr>
                                <td>{{$i+1}}</td>
                                 <td>{{$arr['name']}}</td>
                                  <td>{{$arr['mobile_no']}}</td>
                                  @if($arr['available_status']=="Y")
                                   <td style="color:red">Available</td>
                                   @else
                                    <td style="color:green">Not Available</td>
                                   @endif
                               </tr>
                            @endforeach
                            </tbody>
                        </table>
                      
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12" style="align-content: center;justify-content: center;display: flex;margin-bottom: 30px;">
                        <input type="submit" value="Upload" class="btn btn-primary @if(count($data)==0) disabled @endif"  >
                    </div>
                </div>
            </div>

        </div>

    </form>
    </div>
@endsection