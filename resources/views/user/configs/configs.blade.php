@extends('layouts.app')

   

@section('content')
<style>
    .bg-success {
  background-color: #28a745 !important;
  color: white !important;
}
.bg-warning {
  color: #212529;
  background-color: #ffc107;
}
.bg-info {
  color: #fff;
  background-color: #17a2b8;
}
.bg-danger {
 color: #fff;
background-color: #dc3545;
}
</style>
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
                   <ul class="nav nav-tabs">
  <li class="active"><a data-toggle="tab" href="#home">Category</a></li>
  <li><a data-toggle="tab" href="#menu2">Domain</a></li>
  <li><a data-toggle="tab" href="#menu1">Segment</a></li>
    <li><a data-toggle="tab" href="#source">Source</a></li>

</ul>

<div class="tab-content">
  <div id="home" class="tab-pane fade in active"><br>
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
                            <th>Category Id</th>
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
                             <td>
                              {{$category->id}}
                            </td>
                            
                           <td>{{$category->category}}</td>
                           @if($category->status=="Y")
                            <td ><span class="badge bg-success" style="color:white">Active</span></td>
                            @else
                            <td ><span class="badge bg-danger" style="color:white">Inactive</span></td>
                            @endif
                           
                            <td><a href="{{ route('category.edit', ['id' => $category->id ]) }}"><span class="badge bg-info"><i class="fa fa-edit" style="color: white;"></i></span></a> 
                             <a  class="categoryconfirmation"  href="{{ route('category.delete', ['id' => $category->id ]) }}"><span class="badge bg-danger"><i class="fa fa-trash" style="color: white;"></i></span></a> </td>
                              
                          </tr>
                          @endforeach
                         
                         
                          
                        </tbody>
                      </table>
                    </div>
  </div>
  <div id="menu1" class="tab-pane fade"><br>
    <div class="add-button" style="float:right">
                            <a  class="btn btn-primary btn-md" href="{{route('segment.add')}}">Add</a>
                        </div>
                        <br><br><br>
                    <div class="table-responsive">
                      <table class="table table-bordered" id="segment_list">
                        <thead>
                          <tr>
                            <th class="text-center">
                              #
                            </th>
                            <th>Name</th>
                            <th>Status</th>
                            <th>Action</th>
                          </tr>
                        </thead>
                        <tbody>
                            @foreach($segments as $i=>$segment)
                          
                          <tr>
                            <td>
                              {{$i+1}}
                            </td>
                           
                           <td>{{$segment->name}}</td>
                           @if($segment->status=="Y")
                            <td ><span class="badge bg-success" style="color:white">Active</span></td>
                            @else
                            <td ><span class="badge bg-danger" style="color:white">Inactive</span></td>
                            @endif
                           
                            <td><a href="{{ route('segment.edit', ['id' => $segment->id ]) }}"><span class="badge bg-info"><i class="fa fa-edit" style="color: white;"></i></span></a> 
                             <a  class="Segmentconfirmation"  href="{{ route('segment.delete', ['id' => $segment->id ]) }}"><span class="badge bg-danger"><i class="fa fa-trash" style="color: white;"></i></span></a> </td>
                              
                          </tr>
                          @endforeach
                         
                         
                          
                        </tbody>
                      </table>
                    </div>
  </div>
  <div id="menu2" class="tab-pane fade">
    <br>
    <div class="add-button" style="float:right">
                            <a  class="btn btn-primary btn-md" href="{{route('domain.add')}}">Add</a>
                        </div>
                        <br><br><br>
                    <div class="table-responsive">
                      <table class="table table-bordered" id="segment_list">
                        <thead>
                          <tr>
                            <th class="text-center">
                              #
                            </th>
                            <th>Name</th>
                            <th>Status</th>
                            <th>Action</th>
                          </tr>
                        </thead>
                        <tbody>
                            @foreach($domains as $i=>$domain)
                          
                          <tr>
                            <td>
                              {{$i+1}}
                            </td>
                           
                           <td>{{$domain->name}}</td>
                           @if($domain->status=="Y")
                            <td ><span class="badge bg-success" style="color:white">Active</span></td>
                            @else
                            <td ><span class="badge bg-danger" style="color:white">Inactive</span></td>
                            @endif
                           
                            <td><a href="{{ route('domain.edit', ['id' => $domain->id ]) }}"><span class="badge bg-info"><i class="fa fa-edit" style="color: white;"></i></span></a> 
                              <a  class="Domainconfirmation"  href="{{ route('domain.delete', ['id' => $domain->id ]) }}"><span class="badge bg-danger"><i class="fa fa-trash" style="color: white;"></i></span></a> </td>
                              
                          </tr>
                          @endforeach
                         
                         
                          
                        </tbody>
                      </table>
                    </div>
  </div>
  
    <div id="source" class="tab-pane fade">
    <br>
    <div class="add-button" style="float:right">
                            <a  class="btn btn-primary btn-md" href="{{route('source.add')}}">Add</a>
                        </div>
                        <br><br><br>
                    <div class="table-responsive">
                      <table class="table table-bordered" id="segment_list">
                        <thead>
                          <tr>
                            <th class="text-center">
                              #
                            </th>
                            <th>Name</th>
                            <th>Status</th>
                            <th>Action</th>
                          </tr>
                        </thead>
                        <tbody>
                            @foreach($sources as $i=>$source)
                          
                          <tr>
                            <td>
                              {{$i+1}}
                            </td>
                           
                           <td>{{$source->name}}</td>
                           @if($source->status=="Y")
                            <td ><span class="badge bg-success" style="color:white">Active</span></td>
                            @else
                            <td ><span class="badge bg-danger" style="color:white">Inactive</span></td>
                            @endif
                           
                            <td><a href="{{ route('source.edit', ['id' => $source->id ]) }}"><span class="badge bg-info"><i class="fa fa-edit" style="color: white;"></i></span></a> 
                            
                            <a  class="confirmation"  href="{{ route('source.delete', ['id' => $source->id ]) }}"><span class="badge bg-danger"><i class="fa fa-trash" style="color: white;"></i></span></a> </td>
                              
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
            </div>
</div>
<script type="text/javascript">
    var elems = document.getElementsByClassName('confirmation');
    var confirmIt = function (e) {
        if (!confirm('Are you sure you want to delete source?')) e.preventDefault();
    };
    for (var i = 0, l = elems.length; i < l; i++) {
        elems[i].addEventListener('click', confirmIt, false);
    }
</script>

<script type="text/javascript">
    var elems = document.getElementsByClassName('categoryconfirmation');
    var confirmIt = function (e) {
        if (!confirm('Are you sure you want to delete category?')) e.preventDefault();
    };
    for (var i = 0, l = elems.length; i < l; i++) {
        elems[i].addEventListener('click', confirmIt, false);
    }
</script>

<script type="text/javascript">
    var elems = document.getElementsByClassName('Domainconfirmation');
    var confirmIt1 = function (e) {
        if (!confirm('Are you sure you want to delete domain?')) e.preventDefault();
    };
    for (var i = 0, l = elems.length; i < l; i++) {
        elems[i].addEventListener('click', confirmIt1, false);
    }
</script>

<script type="text/javascript">
    var elems = document.getElementsByClassName('Segmentconfirmation');
    var confirmIt2 = function (e) {
        if (!confirm('Are you sure  you want to delete segment?')) e.preventDefault();
    };
    for (var i = 0, l = elems.length; i < l; i++) {
        elems[i].addEventListener('click', confirmIt2, false);
    }
</script>
@endsection