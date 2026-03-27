@extends('layouts.app')

   

@section('content')
 
<div >

 <div class="row"  style="margin-top:3rem">
              <div class="col-12">
                <div class="">
                  @if (\Session::has('success'))
                    <div class="alert alert-success">
                        
                            {!! \Session::get('success') !!}
                    </div>
                @endif
                  
                    <div  id="leads">
                      
                                                  <table class="table table-striped table-bordered" id="leads_list">
                        <thead>
                          <tr>
                            <th>S.No</th>
                             <th>Category</th>
                             <th>No Of Leads</th>
                             <th>Action</th>
                          </tr>
                        </thead>
                        <tbody>
                        @foreach($leads as $i=>$lead)
                        <?php 
                          $category = \App\cfgCategory::where('id',$lead->category)->first();
                        ?> 

                          <tr>
                             <td>{{$i+1}}</td>
                            @if($category!=null)
                            <td>{{$category->category}}</td>
                            @else
                              <td>-</td>
                            @endif
                            <td>{{$lead->total}}</td>
                           <td><a href="{{ route('leads.reportsver2.filter',  ['search' => '','category' =>  $lead->category , 'area' => '', 'month' => ''] ) }}"><span class="badge bg-info" style="background-color:#17a2b8"><i class="fa fa-eye" style="color: white;"></i></span>
                                </a> </td>
                            
                             
                            
                           
                            
                               
                           
                            
                            
                          </tr>
                          @endforeach
                          </tbody>
                      </table>
                      
                        {!! $leads->links() !!}   
 

                          
                        
                    </div>

                  </div>
                </div>
              </div>
            </div>
     <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>

 
@endsection

