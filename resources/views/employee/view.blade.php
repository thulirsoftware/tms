@extends('theme.default')

@section('content')
<style type="text/css">
   .dp,.info,.personal ,.qualify ,.address ,.salary ,.bank_info ,.achieve
   {
   box-shadow: 0px 0px 6px -2px #000;
   }
   .form-horizontal { padding:8px 15px; }
   .col-md-12 { margin-bottom: 20px; }
   .col-md-12 .fa
   {
   font-size: 18px;
   margin: 22px 0px;
   }
   .fa-edit { color: #003EF7; }
   .fa-check { color: #FF0000; }
</style>
<div class="row">
   <div class="col-md-12">
      <div class="col-md-5 dp">
         <img src="http://software.rubikscube.info/icube/icube.php?stickers=yyyyggygrrygryyrrryrgrrgggg&size=200" class="img-responsive">
      </div>
      <div class="col-md-5 col-md-offset-1 info">
      <div class="row">
            <div class="col-md-8">
               <h3 >Emp Info </h3>
               <hr>
            </div>
            <div class="col-md-1">
               <i class="fa fa-edit" id="edit_emp" onclick="edit_emp()"></i>
            </div>
            <div class="col-md-1">
               <i class="fa fa-check" id="update_emp" onclick="update_emp()"></i> 
            </div>
         </div>
         <form class="form-horizontal" id="emp_form" action="/action_page.php">
            <div class="form-group">
               <label class="control-label col-md-3" for="name">Name:</label>
               <div class="col-md-8">
                  <input type="text" name="" class="form-control" value="demo123" disabled="">   
               </div>
            </div>
            <div class="form-group">
               <label class="control-label col-md-3" for="emp_id">Emp ID:</label>
               <div class="col-md-8"> 
                  <input type="text" name="" class="form-control" value="demo123" disabled="">   
               </div>
            </div>
            <div class="form-group">
               <label class="control-label col-md-3" for="contact">Contact:</label>
               <div class="col-md-8">
                  <input type="text" id="emp_contact" name="" class="form-control" value="demo123" disabled="">   
               </div>
            </div>
            <div class="form-group">
               <label class="control-label col-md-3" for="email">Mail ID:</label>
               <div class="col-md-8">
                  <input type="text" id="emp_mail" name="" class="form-control" value="demo123" disabled="">   
               </div>
            </div>
         </form>
      </div>
   </div>
   <div class="col-md-12">
      <div class="col-md-5 personal">
         <div class="row">
            <div class="col-md-8">
               <h3 >Personal </h3>
               <hr>
            </div>
            <div class="col-md-1">
               <i class="fa fa-edit"></i>
            </div>
            <div class="col-md-1">
               <i class="fa fa-check"></i> 
            </div>
         </div>
         <div class="row">
            <form class="form-horizontal">
               <div class="form-group">
                  <label class="control-label col-md-4" for="fname">First Name:</label>
                  <div class="col-md-8">
                     <input type="text" name="" class="form-control" disabled="">   
                  </div>
               </div>
               <div class="form-group">
                  <label class="control-label col-md-4" for="lname">Last Name:</label>
                  <div class="col-md-8">
                     <input type="text" name="" class="form-control" disabled="">   
                  </div>
               </div>
               <div class="form-group">
                  <label class="control-label col-md-4" for="father_name">Father Name:</label>
                  <div class="col-md-8">
                     <input type="text" name="" class="form-control" disabled="">   
                  </div>
               </div>
               <div class="form-group">
                  <label class="control-label col-md-4" for="date">Date Of Birth:</label>
                  <div class="col-md-8">
                     <input type="date" name="" class="form-control" disabled="">   
                  </div>
               </div>
               <div class="form-group">
                  <label class="control-label col-md-4" for="gender">Gender:</label>
                  <div class="col-md-8">
                     <select class="form-control" disabled="">
                        <option>Select Gender</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                     </select>
                  </div>
               </div>
            </form>
         </div>
         <br>
      </div>
      <div class="col-md-5 col-md-offset-1 qualify">
         <div class="row">
            <div class="col-md-8">
               <h3 >qualification </h3>
               <hr>
            </div>
            <div class="col-md-1">
               <i class="fa fa-edit"></i>
            </div>
            <div class="col-md-1">
               <i class="fa fa-check"></i> 
            </div>
         </div>
         <table class="table table-bordered">
            <tr>
               <th>Course</th>
               <th>Marks</th>
               <th>Percentage</th>
            </tr>
            <tbody>
               <tr>
                  <td>UG</td>
                  <td><input type="text" name="" class="form-control" value="75" disabled=""></td>
                  <td><input type="text" name="" class="form-control" value="75" disabled=""></td>
               </tr>
               <tr>
                  <td>PG</td>
                  <td><input type="text" name="" class="form-control" value="75" disabled=""></td>
                  <td><input type="text" name="" class="form-control" value="75" disabled=""></td>
               </tr>
               <tr>
                  <td>High School</td>
                  <td><input type="text" name="" class="form-control" value="75" disabled=""></td>
                  <td><input type="text" name="" class="form-control" value="75" disabled=""></td>
               </tr>
            </tbody>
         </table>
      </div>
   </div>
   <div class="col-md-12">
      <div class="col-md-5 address">
         <div class="row">
            <div class="col-md-8">
               <h3 >Address </h3>
               <hr>
            </div>
            <div class="col-md-1">
               <i class="fa fa-edit" id="edit_address" onclick="edit_address()"></i>
            </div>
            <div class="col-md-1">
               <i class="fa fa-check" id="update_address" onclick="update_address()"></i> 
            </div>
         </div>
         <textarea rows="5" class="form-control" id="address_info" disabled="">
         Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo
     </textarea>
         <br>
      </div>
      <div class="col-md-5 col-md-offset-1 salary">
         <div class="row">
            <div class="col-md-8">
               <h3 >Salary </h3>
               <hr>
            </div>
            <div class="col-md-1">
               <i class="fa fa-edit"></i>
            </div>
            <div class="col-md-1">
               <i class="fa fa-check"></i> 
            </div>
         </div>
         <table class="table table-bordered">
            <tr>
               <th>Year</th>
               <th>Month</th>
               <th>Salary</th>
            </tr>
            <tbody>
               <tr>
                  <td><input type="text" name="" class="form-control" value="2017" disabled=""></td>
                  <td><input type="text" name="" class="form-control" value="01" disabled=""></td>
                  <td><input type="text" name="" class="form-control" value="10000" disabled=""></td>
               </tr>
            </tbody>
         </table>
      </div>
   </div>
   <div class="col-md-12">
      <div class="col-md-5 bank_info">
         <div class="row">
            <div class="col-md-8">
               <h3 >Bank Details </h3>
               <hr>
            </div>
            <div class="col-md-1">
               <i class="fa fa-edit" id="edit_bank" onclick="edit_bank()"></i>
            </div>
            <div class="col-md-1">
               <i class="fa fa-check" id="update_bank" onclick="update_bank()" ></i> 
            </div>
         </div>
         <form class="form-horizontal" id="bank">
            <div class="form-group">
               <label class="control-label col-md-5" for="bank_name">Bank Name:</label>
               <div class="col-md-7">
                  <input type="text" name="" class="form-control" value="demo123" disabled="">   
               </div>
            </div>
            <div class="form-group">
               <label class="control-label col-md-5" for="account_name">Account Name:</label>
               <div class="col-md-7">
                  <input type="text" name="" class="form-control" value="demo123" disabled="">   
               </div>
            </div>
            <div class="form-group">
               <label class="control-label col-md-5" for="account_no">Account No:</label>
               <div class="col-md-7">
                  <input type="text" name="" class="form-control" value="demo123" disabled="">   
               </div>
            </div>
            <div class="form-group">
               <label class="control-label col-md-5" for="ifsc">IFSC Code:</label>
               <div class="col-md-7">
                  <input type="text" name="" class="form-control" value="demo123" disabled="">   
               </div>
            </div>
         </form>
      </div>
      <div class="col-md-5 col-md-offset-1 achieve">
         <div class="row">
            <div class="col-md-8">
               <h3 >Achievements </h3>
               <hr>
            </div>
            <div class="col-md-1">
               <i class="fa fa-edit" id="edit_achieve" onclick="edit_achieve()"></i>
            </div>
            <div class="col-md-1">
               <i class="fa fa-check" id="update_achieve" onclick="update_achieve()"></i> 
            </div>
         </div>
         <textarea rows="5" id="achieve_info" class="form-control" disabled="">
      Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo
      </textarea>
         <br>
      </div>
   </div>
</div>
<br><br>
 
<script type="text/javascript"> 
function edit_emp() {
    $( "#edit_emp" ).prop( "hidden", true );
    $( "#update_emp" ).prop( "hidden", false );
   $( "#emp_form #emp_contact" ).prop( "disabled", false );
   $( "#emp_form #emp_mail" ).prop( "disabled", false );
}
function update_emp() {
    $( "#update_emp" ).prop( "hidden", true );
     $( "#edit_emp" ).prop( "hidden", false );
    $( "#emp_form #emp_contact" ).prop( "disabled", true );
    $( "#emp_form #emp_mail" ).prop( "disabled", true );
}

function edit_address() {
    $( "#edit_address" ).prop( "hidden", true );
    $( "#update_address" ).prop( "hidden", false );
   $( "#address_info" ).prop( "disabled", false );
}
function update_address() {
    $( "#update_address" ).prop( "hidden", true );
     $( "#edit_address" ).prop( "hidden", false );
    $( "#address_info" ).prop( "disabled", true );
}

function edit_bank() {
    $( "#edit_bank" ).prop( "hidden", true );
    $( "#update_bank" ).prop( "hidden", false );
   $( "#bank input" ).prop( "disabled", false );
}
function update_bank() {
    $( "#update_bank" ).prop( "hidden", true );
     $( "#edit_bank" ).prop( "hidden", false );
    $( "#bank input" ).prop( "disabled", true );
}
function edit_achieve() {
    $( "#edit_achieve" ).prop( "hidden", true );
    $( "#update_achieve" ).prop( "hidden", false );
   $( "#achieve_info" ).prop( "disabled", false );
}
function update_achieve() {
    $( "#update_achieve" ).prop( "hidden", true );
     $( "#edit_achieve" ).prop( "hidden", false );
    $( "#achieve_info" ).prop( "disabled", true );
}
</script>

@endsection