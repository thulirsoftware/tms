@extends('layouts.app')

   

@section('content')
<style type="text/css">
  .d-sm-none{
    display: table;
  }
  /* On screens that are 992px or less, set the background color to blue */
@media screen and (max-width: 1200px) {
 .d-sm-none{
    display: none;
  }
  .d-sm-block{
    display: block !important;
   }
}
.d-md-none{
    display: none;
  }
</style>
<div style="padding-top:2rem">
<div class="row">
               <?php
 $page = Session::get('page');
?>
              <div class="col-md-3">
                @if($page==null)

                   <a  class="btn btn-warning"  href="{{route('reportsver2.prospect.list')}}">
                    <i class="fa fa-arrow-left"></i>
                    </a>
                    @else
                       <a  class="btn btn-warning"  href="{{route('reportsver2.prospect.list')}}">
                    <i class="fa fa-arrow-left"></i>
                    </a>
                    @endif
                  </div>
</div>
 <div class="row">
     
              <div class="col-md-12">
                <?php
                $lead = \App\Leads::where('id',request()->id)->first();
                if($lead->category!="")
                {
                    $category = \App\cfgCategory::where('id',$lead->category)->first();
                }
                 
              ?>
              
              <h3>{{$lead->name}} ,  @if($lead->category!="")
                 {{$category->category}} @endif</h3><br>
               <table class="table table-bordered d-sm-none " id="leads_list">
                        <thead>
                          <tr>
                            <th>Name</th>
                            <th>Date</th>
                            <th>Mobile</th>
                            <th>Area</th>
                            <th>Location</th>
                            <th style="width:20px">Website</th>
                            <th style="width:20px">Whatsapp</th>
                            <th>Status</th>
                          </tr>
                        </thead>
                        <tbody id="leads">
                            <?php
                                $FollowUpDesc = \App\FollowUps::where('leads_id',$lead->id)->orderby('id','desc')->first();
                                $FollowUpCount = \App\FollowUps::where('leads_id',$lead->id)->orderby('id','desc')->count();
                                $status = \App\CfgStatus::where('id',$lead->status)->first();
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
                              @if($lead->whatsapp!=null && $lead->whatsapp!="Nil")
                                <td style="color:green;font-weight:bolder"><a href="{{$lead->whatsapp}}" target="_blank" style="color:green;font-weight:bolder">Open</a></td>
                            @elseif($lead->whatsapp=="Nil")
                                <td style="color:red;font-weight:bolder">N.A</td>
                            @else
                                <td style="color:red;font-weight:bolder">N.A</td>
                            @endif
                           
                           

                          </tr>
                        </tbody>
                      </table>
                      <div class="d-md-none d-sm-block ">
                <div class="panel panel-default col-md-12" style="margin-right: 20px;margin-left: 20px;">
    <div class="panel-body">
        <h4><b>Name : {{$lead->name}}</b></h4>
        <p>Date : {{$lead->next_followup_date}}</p>
        <p data-toggle="tooltip" data-placement="top" class="red-tooltip" title="{{$lead->mobile_no}}"> Mobile No :{{  $lead->mobile_no}}</p>
        <p data-toggle="tooltip" data-placement="top" class="red-tooltip" title="{{$lead->location}}">Address: {{  $lead->location}}</p>
          @if($lead->website!=null && $lead->website!="Nil")
                                <p style="color:green;font-weight:bolder">Website : <a href="{{$lead->website}}" target="_blank" style="color:green;font-weight:bolder">Open</a></p>
                            
                            @endif
                            
                             @if($lead->whatsapp!=null && $lead->whatsapp!="Nil")
                                <p style="color:green;font-weight:bolder">Whatsapp : <a href="{{$lead->whatsapp}}" target="_blank" style="color:green;font-weight:bolder">Open</a></p>
                           
                            @endif
                              
                                @if($lead->status=='S')
                                <p style="color: green;font-weight:bold">Status : Initial</p>
                                @elseif($lead->status=='I')
                                 <p style="color: #ff9b00;font-weight:bold">Status : Inprogress</p>
                                  @elseif($lead->status=='N')
                                 <p style="color: red;font-weight:bold">Status : No Need</p>
                                @elseif($lead->status=='P')
                                 <p style="color: orange;font-weight:bold">Status : Pending</p>
                                @else 
                                <p style="color: green;font-weight:bold">Status : Converted</p>
                                @endif
                            
                             
                              
     </div>
</div>
</div>

                <div class="add-button" style="float:right;">
                   <button type="button" class="btn btn-primary"  data-toggle="modal" data-target="#CallModal">
                    <i class="fa fa-plus"></i>&nbsp;Add Followups
                    </button>
                    

                </div>
               <br><br><br><br>

               
                 @if (\Session::has('success'))
                    <div class="alert alert-success" >
                        
                            {!! \Session::get('success') !!}
                    </div>
                @endif
                   
                    <div class="table-responsive">
                      <table class="table table-striped table-bordered d-sm-none " id="leads_list">
                        <thead>
                          <tr>
                            <th class="text-center">
                              #
                            </th>
                            <th>Call Date</th>
                            <th>Remarks</th>
                            <th>Method</th>
                            <th>Assigned By</th>
                            <th>Assigned To</th>
                            <th>Status</th>
                            <th>Action</th>
                          </tr>
                        </thead>
                        <tbody>
                            @foreach($FollowUps as $i=>$FollowUp)
                          <tr>
                            <td>
                              {{$i+1}}
                            </td>
                            <td>{{$FollowUp->call_date}}</td>
                           
                            <td>{{$FollowUp->remarks}}</td>
                            <?php

                            $method = \App\CfgFollowupMethods::where('id',$FollowUp->followup_method)->first();
                            $status = \App\CfgStatus::where('id',$FollowUp->status)->first();
                          ?>
                          @if($method!=null)
                          <td>{{$method->name}}</td>
                          @else
                          <td></td>
                          @endif
                          <?php

                            $assigned_to = \App\User::where('type','!=','admin')->where('id',$FollowUp->assigned_to)->first();
                            $assigned_by = \App\User::where('type','!=','admin')->where('id',$FollowUp->assigned_by)->first();
                           ?>

                           @if($assigned_by!=null)
                          <td>{{$assigned_by->name}}</td>
                          @else
                          <td></td>
                          @endif
                           @if($assigned_to!=null)
                          <td>{{$assigned_to->name}}</td>
                          @else
                          <td></td>
                          @endif
                            @if($FollowUp!=null)
                            <?php
                             $Followupstatuses = \App\CfgStatus::where('id',$FollowUp->status)->first();
                            
                            ?>
                                @if($Followupstatuses!=null)
                                    
                                          <td style="color: orange;font-weight:bold">{{$Followupstatuses->name}}</td>
                                      
                                       @else
                                          <td style="color: orange;font-weight:bold"></td>
                                    @endif
                           
                            <td><a href="{{ route('followup.ver2.edit', ['id' => $FollowUp->id ]) }}"><span class="badge bg-info"><i class="fa fa-edit" style="color: white;"></i></span></a> 
                            <a  class="categoryconfirmation"  href="{{ route('followup.ver2.delete', ['id' => $FollowUp->id ]) }}"><span class="badge bg-danger"><i class="fa fa-trash" style="color: white;"></i></span></a>
                            </td>
                          </tr>
                          @endif
                          @endforeach
                         
                         
                          
                        </tbody>
                      </table>
                </div>
                 <div class="row d-md-none d-sm-block ">
                   @foreach($FollowUps as $i=>$FollowUp)
                <div class="panel panel-default col-md-4" style="margin-right: 20px;margin-left: 20px;">
    <div class="panel-body">
        <p>{{$FollowUp->date}}</p>
                           
                            <p>{{$FollowUp->remarks}}</p>
                            <?php

                            $method = \App\CfgFollowupMethods::where('id',$FollowUp->followup_method)->first();
                            $status = \App\CfgStatus::where('id',$FollowUp->status)->first();
                          ?>
                          @if($method!=null)
                          <p>{{$method->name}}</p>
                          @else
                          <p></p>
                          @endif
                          <?php

                            $assigned_to = \App\User::where('type','!=','admin')->where('id',$FollowUp->assigned_to)->first();
                            $assigned_by = \App\User::where('type','!=','admin')->where('id',$FollowUp->assigned_by)->first();
                           ?>

                           @if($assigned_by!=null)
                          <p>{{$assigned_by->name}}</p>
                          @else
                          <p></p>
                          @endif
                           @if($assigned_to!=null)
                          <p>{{$assigned_to->name}}</p>
                          @else
                          <p></p>
                          @endif
                            @if($FollowUp!=null)
                            <?php
                             $Followupstatuses = \App\CfgStatus::where('id',$FollowUp->status)->first();
                            
                            ?>
                                  @if($Followupstatuses!=null)
                                    <p style="color: orange;font-weight:bold">{{$Followupstatuses->name}}</p>
                                      
                                  @else
                                    <p style="color: orange;font-weight:bold"></p>
                                 @endif
                           
                            <p><a href="{{ route('followup.ver2.edit', ['id' => $FollowUp->id ]) }}"><span class="badge bg-info"><i class="fa fa-edit" style="color: white;"></i></span></a> 
                            <a  class="categoryconfirmation"  href="{{ route('followup.ver2.delete', ['id' => $FollowUp->id ]) }}"><span class="badge bg-danger"><i class="fa fa-trash" style="color: white;"></i></span></a>
                            </p>
                          </tr>
                          @endif
                            
                             
                              
     </div>
</div>
@endforeach
</div>
              </div>
            </div>
</div>

 
 
<div class="modal fade" id="CallModal">
  <div class="modal-dialog" role="Call">
    <form method="POST" action="{{ route('followup.ver2.create') }}" enctype="multipart/form-data">
                         {{ csrf_field() }}
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="CallModalLabel">Add Followup</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
           <div class="row">
             <div class="col-md-12 form-group">
                      <label>Call Date&nbsp;<span style="color:red">*</span></label>
                        <input type="date" name="date" value="{{date('Y-m-d')}}" class="form-control" readonly>
                    </div>
                      
                     <?php

                            $Followupmethods = \App\CfgFollowupMethods::get();
                           ?>
                     <div class="col-md-12 form-group">
                      <label>Followup Method&nbsp;<span style="color:red">*</span></label>
                        <select id="followup_method" name="followup_method" class="form-control" required>
                          <option value="">--- Select Followup Method ---</option>
                          @foreach ($Followupmethods as $status)
                              <option value="{{ $status->id }}"  >{{ $status->name }}</option>
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
                              <option value="{{ $User->id }}"  >{{ $User->name }}</option>
                          @endforeach
                      </select>
                    </div>
                    <input type="hidden" name="leads_id" value="{{request()->id}}">
                  

                     <div class="col-md-12 form-group">
                      <label>Status&nbsp;<span style="color:red">*</span></label>
                        <select id="status" name="status" class="form-control" onchange="nextFollowup(this.value)" required>
                          <option value="">--- Select status ---</option>
                          @foreach ($statuses as $status)
                              <option value="{{ $status->id }}" {{ old('status') == $status->id ? 'selected' : ''}}>{{ $status->name }}</option>
                          @endforeach
                      </select>
                    </div>
                    <div class="col-md-12 form-group">
                      <label>Followup Date&nbsp;<span style="color:red;display:none" id="next_followup_required">*</span> </label>
                        <input type="date" name="next_followup_date" id="next_followup_date"  class="form-control"  >
                    </div>
                     <div class="col-md-12 form-group">
                      <label>Remarks&nbsp;<span style="color:red">*</span></label>
                        <textarea  name="description" class="form-control" required></textarea>
                    </div>
                  </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Add</button>
      </div>
    </div>
  </form>
  </div>
</div>


 
 
  
  <script type="text/javascript">
    var elems = document.getElementsByClassName('categoryconfirmation');
    var confirmIt = function (e) {
        if (!confirm('Are you sure you want to delete followup?')) e.preventDefault();
    };
    for (var i = 0, l = elems.length; i < l; i++) {
        elems[i].addEventListener('click', confirmIt, false);
    }
</script>
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
