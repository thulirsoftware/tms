@extends('layouts.app')

@section('content')

<div class="row">
    <div class="col-lg-2">
        <h3 class="page-header">Dashboard</h3>
    </div>
   
    <div class="col-lg-3" style="padding-top:30px">
       <?php 
                            $filterText = Session::get('filterText');
                            $segmentId = Session::get('segment');
                            $domainId = Session::get('domain');
                          ?>
                          <input type="hidden" id="segmentAdd" value="{{$segmentId}}">
       <div class="form-group">
             <label>Select Domain</label>
            <select class="form-control" onchange="getSegment(this.value)" id="domain">
                <option value="">Select Domain</option>
                  @foreach($domains as $domain)
                  <option value="{{$domain->id}}" {{ $domainId == $domain->id? 'selected' : ''}}>{{$domain->name}}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-lg-3" style="padding-top:30px">
       <div class="form-group">
             <label>Select Segment</label>
            
                   <select class="form-control"  id="segment">
                    <option value="">Select Segment</option>
                    
              </select>

            
        </div>
    </div>
    <div class="col-lg-2" style="padding-top:30px">
        <div class="form-group">
             <label>Select range</label>
            <select class="form-control"  id="range">
                 <option value="">Select</option>
                <option value="Today" {{ $filterText == "Today"? 'selected' : ''}}>Today</option>
                <option value="Yesterday" {{ $filterText == "Yesterday"? 'selected' : ''}}>Yesterday</option>
                <option value="This Week" {{ $filterText == "This Week"? 'selected' : ''}}>This Week</option>
                <option value="This Month" {{ $filterText == "This Month"? 'selected' : ''}}>This Month</option>
                 <option value="All Time" {{ $filterText == "All Time"? 'selected' : ''}}>All Time</option>
            </select>
        </div>
        
    </div>
     <div class="col-lg-1" style="padding-top:50px">
        <div class="form-group">
             <button class="btn btn-primary" onclick="filter()">
                 Search
             </button>
           
        </div>
        
    </div>
    <div class="col-lg-1" style="padding-top:50px">
        <div class="form-group">
             <a class="btn btn-primary" href="{{url('/user/remove/Session')}}">
                  <i class="fa fa-refresh"></i>&nbsp;
             </a>
           
        </div>
        
    </div>
    <!-- /.col-lg-12 -->
</div>
 
<div class="row">
    <div class="col-md-12" id="dashboard">
        @include('user.filter')
    </div>

   
         
</div>
<div class="row">
  <div class="col-md-6"  id="chart">
        <canvas id="barChart" width="1000" height="1000"></canvas>
    </div>
    <div class="col-md-6"  id="chart">
      
        <table class="table table-bordered">
          <tbody>
            @foreach($groupBY as $i=>$groupbY)
            <?php 
              $domain = App\CfgDomain::where('id',$groupbY->domain)->first();
               $segment = App\CfgSegment::where('id',$groupbY->segment)->first();
               //dd($segments);
             ?>
             <tr>
              <td>{{$i+1}}</td>
              @if($domain!=null)
              <td>{{$domain->name}}</td>
              @else
              <td></td>
              @endif
               @if($segment!=null)     
                    <td>{{$segment->name}}</td>
                    @else
                    <td></td>
                    @endif
                    <td>{{$groupbY->total}}</td>
            </tr>
             @endforeach



          </tbody>
        </table>
    </div>
</div>
 <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@0.7.0"></script>

    <script>
    $(function() {
        var domain = document.getElementById('domain').value;
         getSegment(domain);
         filterLoad();
});
function getSegment(value)
    {
          var segment = document.getElementById('segmentAdd').value;
         var substateArray =  @json($segments);
        var filteredArray = substateArray.filter(x => x.domain_id == value);
        $('#segment').empty();
        $('#segment').append('<option value="">Select Segment</option>');
         var options = filteredArray.forEach( function(item, index){
             if(segment==item.id)
             {
                  $('#segment').append('<option value="'+item.id+'" selected>'+item.name+'</option>');
             }
             else
             {
                  $('#segment').append('<option value="'+item.id+'">'+item.name+'</option>');
             }
            });
    }
function filter()
{
    var value = document.getElementById('range').value;
    var domain = document.getElementById('domain').value;
    var segment = document.getElementById('segment').value;
       
        $.ajax({
          type : 'get',
          url : '{{route('dashboard.filter')}}',
          data : {'filterText':value,'search':'Y','domain':domain,'segment':segment},
          success:function(data){
          window.location.reload();
          

         },
        error: function (xhr, status, error) {
            if (xhr.status === 419) {
                alert('CSRF token mismatch');
                location.reload(true);
            } else {
                console.error("Error: " + error);
                alert('An error occurred. Please try again later.');
            }
        }  
       });
}
function filterLoad()
{
    var value = document.getElementById('range').value;
    var domain = document.getElementById('domain').value;
    var segment = document.getElementById('segment').value;
       console.log(value,"value");
        $.ajax({
          type : 'get',
          url : '{{route('dashboard')}}',
          data : {'filterText':value,'search':'Y','domain':domain,'segment':segment},
          success:function(data){
            
         },
        error: function (xhr, status, error) {
            if (xhr.status === 419) {
                alert('CSRF token mismatch');
                location.reload(true);
            } else {
                console.error("Error: " + error);
                alert('An error occurred. Please try again later.');
            }
        }  
       });
}

  </script>
<script>
  var app = @json($chart);
  var length = app.length;
  console.log(length);
  
  const countsArray = [];
  const nameArray = [];

  for (let i = 0; i < length; i++) {

      nameArray.push(app[i]["name"]);
      countsArray.push(app[i]["count"]);


  }
  console.log(countsArray);
 
    var data = [{
  data: countsArray,
  backgroundColor: [
   '#f07b72','#46bdc6',"#fbbc04","#7baaf7","#ea4335","#34a853","#ff6d01","#4285f4"
  ],
  borderColor: ['#f07b72','#46bdc6',"#fbbc04","#7baaf7","#ea4335","#34a853","#ff6d01","#4285f4"]
}];

var options = {
  tooltips: {
    enabled: true
  },
  plugins: {
    datalabels: {
      formatter: (value, ctx) => {
        console.log(ctx.dataset._meta);
        let sum = "";
        if(ctx.dataset._meta[0]!=undefined)
        {
             sum = ctx.dataset._meta[0].total;
        }
        else
        {
             sum = ctx.dataset._meta[0].total;
        }
        
        let percentage = (value * 100 / sum).toFixed(2) + "%";
        return percentage;


      },
      color: '#fff',
    }
  }
};

var ctx = document.getElementById("barChart").getContext('2d');
ctx.width = 2000;
ctx.height = 2000;
var myChart = new Chart(ctx, {
  type: 'pie',
  data: {
  labels: nameArray,
    datasets: data
  },
  options: options
});
myChart.update();
  </script>
  
@endsection