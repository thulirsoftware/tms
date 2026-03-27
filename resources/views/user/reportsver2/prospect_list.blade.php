@extends('layouts.report')

   

@section('content')
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
   <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

<style type="text/css">
    #yourBtn {
  position: relative;
   font-family: calibri;
  width: 150px;
  padding: 10px;
  -webkit-border-radius: 5px;
  -moz-border-radius: 5px;
  border: 1px dashed #BBB;
  text-align: center;
  background-color: #DDD;
  cursor: pointer;
}
.testing {
  position: relative;
}
.input-group-btn .dropdown-menu {
   
  min-width: 790px;
  }

</style>
<div class="row">
    <div class="col-md-10">
        
    </div>
     
</div>
<div style="margin-top:1rem" >
 
 
 <div class="row">
               
                    <div  id="leads">
                      
                       @include('user.reportsver2.components.prospect_list')
                          
                        
                    </div>

                  </div>
                 
                </div>
              </div>
            </div>
</div>
 
 
 @endsection

