@extends('layouts.app')

   

@section('content')

<div style="margin-top:2rem">
<div class="row">
               <?php
 $page = Session::get('page');
?>
              <div class="col-3">
                @if($page==null)

                   <a  class="btn btn-warning"  href="{{route('leads.list')}}">
                    <i class="fa fa-arrow-left"></i>
                    </a>
                    @else
                       <a  class="btn btn-warning"  href="{{route('leads.list', ['page' => $page])}}">
                    <i class="fa fa-arrow-left"></i>
                    </a>
                    @endif
                  </div>
</div>
 <div class="row">
     
              <div class="col-12">
                <?php
                $lead = \App\LeadsVer1::where('id',request()->id)->first();
                $category = \App\cfgCategory::where('id',$lead->category)->first();
              ?>
              
              <h3>{{$lead->name}} , {{$category->category}}</h3><br>
               <table class="table table-bordered" id="leads_list">
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
                            $FollowUpDesc = \App\FollowUpsVer1::where('leads_id',$lead->id)->orderby('id','desc')->first();
                             $FollowUpCount = \App\FollowUpsVer1::where('leads_id',$lead->id)->orderby('id','desc')->count();
                             $status = \App\CfgStatus::where('id',$lead->status)->first();
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
                
                <div class="add-button" style="float:right;">
                   <button type="button" class="btn btn-primary"  data-toggle="modal" data-target="#CallModal">
                    <i class="fa fa-phone"></i>&nbsp;Call
                    </button>
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#MessageModal">
                    <i class="fa fa-comment-o"></i>&nbsp;Messsage
                    </button>
                     <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#MailModal">
                    <i class="fa fa-envelope"></i>&nbsp;Mail
                    </button>
                   
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#DirectVisitModal">
                    <i class="fa fa-location-arrow"></i>&nbsp;Direct Visit
                    </button>
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
                    <i class="fa fa-laptop"></i>&nbsp;Demo
                    </button>
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#MeetingModal">
                    <i class="fa fa-laptop"></i>&nbsp;Meeting
                    </button>
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#DocumentationModal">
                      <i class="fa fa-book"></i>&nbsp;Documentation
                    </button>
                      <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#ConversionModal">
                      <i class="fa fa-book"></i>&nbsp;Conversion
                    </button>

                </div>
               <br><br><br><br>

               
                 @if (\Session::has('success'))
                    <div class="alert alert-success" >
                        
                            {!! \Session::get('success') !!}
                    </div>
                @endif
                   
                    <div class="table-responsive">
                      <table class="table table-striped table-bordered" id="leads_list">
                        <thead>
                          <tr>
                            <th class="text-center">
                              #
                            </th>
                            <th>Date</th>
                            <th>Remarks</th>
                            <th>Method</th>
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
                            <td>{{$FollowUp->date}}</td>
                           
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
                            @if($FollowUp!=null)
                            <?php
                             $Followupstatuses = \App\CfgStatus::where('id',$FollowUp->status)->first();
                            
                            ?>
                                @if($Followupstatuses!=null)
                                    @if($FollowUp->status=="9")
                                        <td style="color:#4549b1;font-weight:bold">{{$Followupstatuses->name}}</td>
                                    @elseif($FollowUp->status=="4")
                                        <td style="color:#b45f44;font-weight:bold">{{$Followupstatuses->name}}</td>
                                    @elseif($FollowUp->status=="6")
                                        <td style="color:#ff0f00;font-weight:bold">{{$Followupstatuses->name}}</td>
                                    @elseif($FollowUp->status=="2")
                                        <td style="color:#424e13;font-weight:bold">{{$Followupstatuses->name}}</td>
                                     @elseif($FollowUp->status=="8")
                                        <td style="color:#68bdc6;font-weight:bold">{{$Followupstatuses->name}}</td>
                                        
                                     @elseif($FollowUp->status=="7")
                                        <td style="color:#980f2f;font-weight:bold">{{$Followupstatuses->name}}</td>
                                        @elseif($FollowUp->status=="5")
                                        <td style="color:#9f2947;font-weight:bold">{{$Followupstatuses->name}}</td>
                                        
                                         @else
                                          <td style="color: orange;font-weight:bold">{{$Followupstatuses->name}}</td>
                                      
                                        @endif
                                      @else
                                          <td style="color: orange;font-weight:bold"></td>
                                    @endif
                            @elseif($lead->status!=null)
                            <td style="color: #992600;font-weight:bold">{{$statuses->name}}</td>
                           @else
                           <td style="color: #992600;font-weight:bold">{{$lead->status}}</td>
                           @endif
                            <td><a href="{{ route('followup.edit', ['id' => $FollowUp->id ]) }}"><span class="badge bg-info"><i class="fa fa-edit" style="color: white;"></i></span></a> 
                            <a  class="categoryconfirmation"  href="{{ route('followup.delete', ['id' => $FollowUp->id ]) }}"><span class="badge bg-danger"><i class="fa fa-trash" style="color: white;"></i></span></a>
                            </td>
                          </tr>
                          @endforeach
                         
                         
                          
                        </tbody>
                      </table>
                </div>
                 
              </div>
            </div>
</div>


<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form method="POST" action="{{ route('demo.create') }}">
                         {{ csrf_field() }}
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Add Demo</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
           <div class="row">
                     <div class="col-md-12 form-group">
                      <label>Date</label>
                        <input type="date" name="date" class="form-control">
                    </div>
                    <div class="col-md-12 form-group">
                      <label>Time&nbsp;<span style="color:red">*</span></label>
                        <input type="time"  name="time" class="form-control" required>
                    </div>
                     <div class="col-md-12 form-group">
                      <label>Followup Date</label>
                        <input type="date"  name="followup_date" class="form-control">
                    </div>
                     
                    
                    
                     <div class="col-md-12 form-group">
                      <label>Status&nbsp;<span style="color:red">*</span></label>
                        <select id="status" name="status" class="form-control" required>
                          <option value="">--- Select status ---</option>
                          @foreach ($statuses as $status)
                              <option value="{{ $status->id }}" {{ old('status') == $status->id ? 'selected' : ''}}>{{ $status->name }}</option>
                          @endforeach
                      </select>
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

<!-- Modal -->
<div class="modal fade" id="DocumentModal" tabindex="-1" role="dialog" aria-labelledby="DocumentModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form method="POST" action="{{ route('documentation.create') }}" enctype="multipart/form-data">
                         {{ csrf_field() }}
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="DocumentModalLabel">Add Documentation</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
           <div class="row">
             <div class="col-md-12 form-group">
                      <label>Date&nbsp;<span style="color:red">*</span></label>
                        <input type="date" name="date" class="form-control" required>
                    </div>
                    
                  
                     <div class="col-md-12 form-group">
                      <label>Status&nbsp;<span style="color:red">*</span></label>
                        <select id="status" name="status" class="form-control" required>
                          <option value="">--- Select status ---</option>
                          @foreach ($statuses as $status)
                              <option value="{{ $status->id }}" {{ old('status') == $status->id ? 'selected' : ''}}>{{ $status->name }}</option>
                          @endforeach
                      </select>
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

<div class="modal fade" id="AddModal" tabindex="-1" role="dialog" aria-labelledby="AddModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="followup">
    <form method="POST" action="{{ route('followup.create') }}" enctype="multipart/form-data">
      {{ csrf_field() }}
     <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="AddModalLabel">Add Followup</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
         <input type="hidden" name="leads_id" class="form-control" value="{{ request()->id }}">
                   <div class="row">
                     <div class="col-md-12 form-group">
                      <label>Date&nbsp;<span style="color:red">*</span></label>
                        <input type="date" name="date" class="form-control" required>
                    </div>
                     
                  
                    
                     <div class="col-md-12 form-group">
                      <label>Status&nbsp;<span style="color:red">*</span></label>
                        <select id="status" name="status" class="form-control" required>
                          <option value="">--- Select status ---</option>
                          @foreach ($statuses as $status)
                              <option value="{{ $status->id }}" {{ old('status') == $status->id ? 'selected' : ''}}>{{ $status->name }}</option>
                          @endforeach
                      </select>
                    </div>
                      <div class="col-md-12 form-group">
                      <label>Remarks&nbsp;<span style="color:red">*</span></label>
                        <textarea  name="description" class="form-control" required></textarea>
                    </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Add</button>
      </div>
    </div>
    </div>
   </form>
  </div>
</div>
<!-- Call Modal -->
<div class="modal fade" id="CallModal">
  <div class="modal-dialog" role="Call">
    <form method="POST" action="{{ route('call.create') }}" enctype="multipart/form-data">
                         {{ csrf_field() }}
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="CallModalLabel">Add Call</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
           <div class="row">
             <div class="col-md-12 form-group">
                      <label>Date&nbsp;<span style="color:red">*</span></label>
                        <input type="date" name="date" value="{{date('Y-m-d')}}" class="form-control" required>
                    </div>
                      <div class="col-md-12 form-group">
                      <label>Followup Date</label>
                        <input type="date"  name="followup_date" class="form-control">

                        </select>
                    </div>
                    <input type="hidden" name="leads_id" value="{{request()->id}}">
                  

                     <div class="col-md-12 form-group">
                      <label>Status&nbsp;<span style="color:red">*</span></label>
                        <select id="status" name="status" class="form-control" required>
                          <option value="">--- Select status ---</option>
                          @foreach ($statuses as $status)
                              <option value="{{ $status->id }}" {{ old('status') == $status->id ? 'selected' : ''}}>{{ $status->name }}</option>
                          @endforeach
                      </select>
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


<!-- Message Modal -->
<div class="modal fade" id="MessageModal">
  <div class="modal-dialog" role="Call">
    <form method="POST" action="{{ route('message.create') }}" enctype="multipart/form-data">
                         {{ csrf_field() }}
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="MessageModalLabel">Add Message</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
           <div class="row">
             <div class="col-md-12 form-group">
                      <label>Date&nbsp;<span style="color:red">*</span></label>
                        <input type="date" name="date"  value="{{date('Y-m-d')}}" class="form-control" required>
                    </div>
                    <div class="col-md-12 form-group">
                      <label>Followup Date</label>
                        <input type="date"  name="followup_date" class="form-control">

                        </select>
                    </div>
                    <input type="hidden" name="leads_id" value="{{request()->id}}">
                  

                     <div class="col-md-12 form-group">
                      <label>Status&nbsp;<span style="color:red">*</span></label>
                        <select id="status" name="status" class="form-control" required>
                          <option value="">--- Select status ---</option>
                          @foreach ($statuses as $status)
                              <option value="{{ $status->id }}" {{ old('status') == $status->id ? 'selected' : ''}}>{{ $status->name }}</option>
                          @endforeach
                      </select>
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

<!-- DirectVisit Modal -->
<div class="modal fade" id="DirectVisitModal">
  <div class="modal-dialog" role="DirectVisit">
    <form method="POST" action="{{ route('directVisit.create') }}" enctype="multipart/form-data">
                         {{ csrf_field() }}
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="DirectVisitModalLabel">Add Direct Visit</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
           <div class="row">
             <div class="col-md-12 form-group">
                      <label>Date&nbsp;<span style="color:red">*</span></label>
                        <input type="date" name="date"  value="{{date('Y-m-d')}}" class="form-control" required>
                    </div>
                    <div class="col-md-12 form-group">
                      <label>Followup Date</label>
                        <input type="date"  name="followup_date" class="form-control">

                        </select>
                    </div>
                    <input type="hidden" name="leads_id" value="{{request()->id}}">
               

                     <div class="col-md-12 form-group">
                      <label>Status&nbsp;<span style="color:red">*</span></label>
                        <select id="status" name="status" class="form-control" required>
                          <option value="">--- Select status ---</option>
                          @foreach ($statuses as $status)
                              <option value="{{ $status->id }}" {{ old('status') == $status->name ? 'selected' : ''}}>{{ $status->name }}</option>
                          @endforeach
                      </select>
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


<!-- Mail Modal -->
<div class="modal fade" id="MailModal">
  <div class="modal-dialog" role="Mail">
    <form method="POST" action="{{ route('mail.create') }}" enctype="multipart/form-data">
                         {{ csrf_field() }}
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="MailModalLabel">Add Mail</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
           <div class="row">
             <div class="col-md-12 form-group">
                      <label>Date&nbsp;<span style="color:red">*</span></label>
                        <input type="date"  value="{{date('Y-m-d')}}" name="date" class="form-control" required>
                    </div>
                    <div class="col-md-12 form-group">
                      <label>Followup Date</label>
                        <input type="date"  name="followup_date" class="form-control">
                    </div>
                    <input type="hidden" name="leads_id" value="{{request()->id}}">
                  

                     <div class="col-md-12 form-group">
                      <label>Status&nbsp;<span style="color:red">*</span></label>
                        <select id="status" name="status" class="form-control" required>
                          <option value="">--- Select status ---</option>
                          @foreach ($statuses as $status)
                              <option value="{{ $status->id }}" {{ old('status') == $status->name ? 'selected' : ''}}>{{ $status->name }}</option>
                          @endforeach
                      </select>
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

<!-- meeting Modal -->
<div class="modal fade" id="MeetingModal">
  <div class="modal-dialog" role="Meeting">
    <form method="POST" action="{{ route('meeting.create') }}" enctype="multipart/form-data">
                         {{ csrf_field() }}
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="MeetingModalLabel">Add Meeting</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
           <div class="row">
             <div class="col-md-12 form-group">
                      <label>Date&nbsp;<span style="color:red">*</span></label>
                        <input type="date" name="date" class="form-control" required>
                    </div>
                     <div class="col-md-12 form-group">
                      <label>Followup Date</label>
                        <input type="date"  name="followup_date" class="form-control">
                    </div>
                
                     <div class="col-md-12 form-group">
                      <label>Status&nbsp;<span style="color:red">*</span></label>
                        <select id="status" name="status" class="form-control" required>
                          <option value="">--- Select status ---</option>
                          @foreach ($statuses as $status)
                              <option value="{{ $status->id }}" {{ old('status') == $status->name ? 'selected' : ''}}>{{ $status->name }}</option>
                          @endforeach
                      </select>
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
<!-- Documentation Modal -->
<div class="modal fade" id="DocumentationModal">
  <div class="modal-dialog" role="document">
    <form method="POST" action="{{ route('document.create') }}" enctype="multipart/form-data">
                         {{ csrf_field() }}
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="MeetingModalLabel">Add Document</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
           <div class="row">
             <div class="col-md-12 form-group">
                      <label>Date&nbsp;<span style="color:red">*</span></label>
                        <input type="date" name="date" class="form-control" required>
                    </div>
                     <div class="col-md-12 form-group">
                      <label>Followup Date</label>
                        <input type="date"  name="followup_date" class="form-control">
                    </div>
                    <div class="col-md-12 form-group">
                      <label>Type of Document&nbsp;<span style="color:red">*</span></label>
                        <input type="text" name="type_of_document" class="form-control" required>
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

<!-- meeting Modal -->
<div class="modal fade" id="ConversionModal">
  <div class="modal-dialog" role="Conversion">
    <form method="POST" action="{{ route('conversion.create') }}" enctype="multipart/form-data">
                         {{ csrf_field() }}
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="ConversionModalLabel">Add Conversion</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
           <div class="row">
               <div class="col-md-12 form-group">
                      <label>For&nbsp;<span style="color:red">*</span></label>
                        <select  name="for" class="form-control" onchange="getCategory(this.value)" required>
                          <option value="">Select</option>
                          <option value="S">Service</option>
                          <option value="P">Product</option>
                        </select>
                    </div>
                     <div class="col-md-12 form-group">
                      <label>Category&nbsp;<span style="color:red">*</span></label>
                        <select name="category" id="category" class="form-control" required>
                          <option value="">Select Category</option>
                         
                        </select>
                    </div>
             <div class="col-md-12 form-group">
                      <label>Date&nbsp;<span style="color:red">*</span></label>
                        <input type="date" name="date" class="form-control" required>
                    </div>
                    
                
                  
                       <div class="col-md-12 form-group">
                      <label>Description&nbsp;<span style="color:red">*</span></label>
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
<script>
  function getCategory(value){
    var substateArray =  @json($categories);
    var filteredArray = substateArray.filter(x => x.for == value);
    $('#category').empty();
    $('#category').append('<option value="">Select Category</option>');
     var options = filteredArray.forEach( function(item, index){
            $('#category').append('<option value="'+item.id+'">'+item.category+'</option>');
        });

  }
  </script>
  
  <script type="text/javascript">
    var elems = document.getElementsByClassName('categoryconfirmation');
    var confirmIt = function (e) {
        if (!confirm('Are you sure you want to delete followup?')) e.preventDefault();
    };
    for (var i = 0, l = elems.length; i < l; i++) {
        elems[i].addEventListener('click', confirmIt, false);
    }
</script>
@endsection
