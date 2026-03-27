@extends('layouts.app')

   

@section('content')

<div style="margin-top:1rem">
    <div class="row">
              <div class="col-3">
                   <a  class="btn btn-warning"  href="{{ route('followup.add', ['id' => $FollowUp->leads_id ]) }}">
                    <i class="fa fa-arrow-left"></i>
                    </a>
                  </div>
</div>
     <?php
                $lead = \App\Leads::where('id',$FollowUp->leads_id)->first();
              ?>
               <h3>{{$lead->name}}</h3><br>
<table class="table table-bordered" id="leads_list">
                        <thead>
                          <tr>
                            <th>Name</th>
                            <th>Date</th>
                            <th>Mobile</th>
                            <th>Area</th>
                            <th>Location</th>
                            <th style="width:20px">Website</th>
                            <th>Status</th>
                            <th>Remarks</th>
                          </tr>
                        </thead>
                        <tbody id="leads">
                             <?php
                            $FollowUps = \App\FollowUps::where('leads_id',$lead->id)->orderby('id','desc')->first();
                             $FollowUpCount = \App\FollowUps::where('leads_id',$lead->id)->orderby('id','desc')->count();
                             $status = \App\CfgStatus::where('id',$FollowUp->status)->first();

                          ?>
                          <tr>
                            <td>{{$lead->name}}</td>
                            <td>{{$lead->created_at->toDateString()}}</td>
                           
                            <td>{{$lead->mobile_no}}</td>
                            <td>{{$lead->area}}</td>
                            <td>{{$lead->location}}</td>
                            @if($lead->website!=null && $lead->website!="Nil")
                                <td style="color:green;font-weight:bolder"><a href="{{$lead->website}}" target="_blank" style="color:green;font-weight:bolder">Open</a></td>
                            @elseif($lead->website=="Nil")
                                <td style="color:red;font-weight:bolder">N.A</td>
                            @else
                                <td style="color:red;font-weight:bolder">N.A</td>
                            @endif
                           
                          
                            <td>{{$lead->remarks}}</td>
                           
                          </tr>
                        </tbody>
                      </table>
 <div class="row">
              <div class="col-12">
                
                    <div class="panel panel-default">
                       @if (\Session::has('success'))
                    <div class="alert alert-success">
                        
                            {!! \Session::get('success') !!}
                    </div>
                @endif
                  <div class="panel-heading">
                    <h4>Edit Followup</h4>                 
                    </div>
                  <div class="panel-body">
                    <form method="POST" action="{{ route('followup.update') }}">
                         {{ csrf_field() }}
                        <input type="hidden" name="id" class="form-control" value="{{ request()->id }}">
                   <div class="row">
                     <div class="col-md-12 form-group">
                      <label>Date&nbsp;<span style="color:red">*</span></label>
                        <input type="date" name="date" class="form-control" value="{{$FollowUp->date}}" required>
                    </div>
                     <?php
                     $FollowUps = \App\FollowUps::where('id',request()->id)->orderby('id','desc')->first();
                $lead = \App\Leads::where('id',$FollowUps->leads_id)->first();
              ?>
                   <div class="col-md-12 form-group">
                      <label>Followup Date</label>
                        <input type="date"  name="followup_date" class="form-control" value="{{$lead->followup_date}}">

                        </select>
                    </div>
                    <div class="col-md-12 form-group">
                      <label>Method&nbsp;<span style="color:red">*</span></label>
                       <select id="method" name="method" class="form-control" required>
                        <option value="">Select method</option>
                         @foreach ($methods as $method)
                              <option value="{{ $method->id}}" {{$method->id == $FollowUp->followup_method ? 'selected' : ''}}>{{ $method->name }}</option>
                          @endforeach
                        </select>
                    </div>
                    
                     <div class="col-md-12 form-group">
                      <label>Status&nbsp;<span style="color:red">*</span></label>
                       <select id="status" name="status" class="form-control" required>
                        <option value="">Select Status</option>
                         @foreach ($statuses as $status)
                              <option value="{{ $status->id }}" {{ $FollowUp->status == $status->id ? 'selected' : ''}}>{{ $status->name }}</option>
                          @endforeach
                        </select>
                    </div>
                     <div class="col-md-12 form-group">
                      <label>Remarks&nbsp;<span style="color:red">*</span></label>
                        <textarea  name="description" class="form-control" required>{{$FollowUp->remarks}}</textarea>
                    </div>
                  </div>
                   <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-md ">
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