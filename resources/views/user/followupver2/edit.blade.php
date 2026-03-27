@extends('layouts.app')

   

@section('content')

<div style="margin-top:1rem">
    <div class="row">
              <div class="col-3">
                   <a  class="btn btn-warning"  href="{{ route('followup.ver2.add', ['id' => $FollowUp->leads_id ]) }}">
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
                            <td>{{$lead->latest_call_date}}</td>
                           
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
                           
                              @if($lead->status=='S')
                                <td style="color: green;font-weight:bold">Initial</td>
                                @elseif($lead->status=='I')
                                 <td style="color: #ff9b00;font-weight:bold">Inprogress</td>
                                  @elseif($lead->status=='N')
                                 <td style="color: red;font-weight:bold">No Need</td>
                                @elseif($lead->status=='P')
                                 <td style="color: orange;font-weight:bold">Pending</td>
                                @else 
                                <td style="color: green;font-weight:bold">Converted</td>
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
                    <form method="POST" action="{{ route('followup.ver2.update') }}">
                         {{ csrf_field() }}
                        <input type="hidden" name="id" class="form-control" value="{{ request()->id }}">
                   <div class="row">
                    
                     <?php
                     $FollowUps = \App\FollowUps::where('id',request()->id)->orderby('id','desc')->first();
                $lead = \App\Leads::where('id',$FollowUps->leads_id)->first();
              ?>
              <input type="hidden" name="leads_id" class="form-control" value="{{$lead->id }}">
              <div class="col-md-12 form-group">
                      <label>Call Date&nbsp;<span style="color:red">*</span></label>
                        <input type="date" name="date" value="{{$FollowUp->call_date}}" class="form-control" readonly>
                    </div>
                  
                    <div class="col-md-12 form-group">
                      <label>Method </label>
                       <select id="method" name="method" class="form-control"  >
                        <option value="">Select method</option>
                         @foreach ($methods as $method)
                              <option value="{{ $method->id}}" {{$method->id == $FollowUp->followup_method ? 'selected' : ''}}>{{ $method->name }}</option>
                          @endforeach
                        </select>
                    </div>
                      <?php

                            $Users = \App\User::where('type','!=','admin')->get();
                           ?>
                    <div class="col-md-12 form-group">
                      <label>Assigned To&nbsp;<span style="color:red">*</span></label>
                        <select id="assigned_to" name="assigned_to" class="form-control" required>
                          <option value="">--- Select   ---</option>
                          @foreach ($Users as $User)
                              <option value="{{ $User->id }}" {{$User->id == $FollowUp->assigned_to ? 'selected' : ''}} >{{ $User->name }}</option>
                          @endforeach
                      </select>
                    </div>
                     <div class="col-md-12 form-group">
                      <label>Status&nbsp;<span style="color:red">*</span></label>
                       <select id="status" name="status" class="form-control" onchange="nextFollowup(this.value)" required>
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
                    <div class="col-md-12 form-group">
                      <label>Followup Date&nbsp;<span style="color:red;display:none" id="next_followup_required">*</span> </label>
                        <input type="date" name="next_followup_date" id="next_followup_date"  class="form-control"   value="{{$FollowUp->next_followup_date}}">
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
<script type="text/javascript">
  function  nextFollowup(argument) {
     if(argument=="22")
    {
        document.getElementById('next_followup_required').style.display="inline";
        document.getElementById('next_followup_date').required = true;
    }
    else if(argument=="20")
    {
        document.getElementById('next_followup_required').style.display="inline";
        document.getElementById('next_followup_date').required = true;
    }
    else if(argument=="19")
    {
        document.getElementById('next_followup_required').style.display="inline";
        document.getElementById('next_followup_date').required = true;
    }
    else if(argument=="17")
    {
        document.getElementById('next_followup_required').style.display="inline";
        document.getElementById('next_followup_date').required = true;
      
    }
    else if(argument=="4")
    {
        document.getElementById('next_followup_required').style.display="inline";
        document.getElementById('next_followup_date').required = true;
    }
    else
    {
        document.getElementById('next_followup_required').style.display="none";
        document.getElementById('next_followup_date').required = false;
    }
  }
</script>
@endsection