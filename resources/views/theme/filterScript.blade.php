<script type="text/javascript">
  $(document).ready(function(){
          // filters();
  });
   $(function () {
      $("#filters").change(filters);
    });

   function filters() {
       
     var projectFilter = $("#projectFilter option:selected").val();
     var activityFilter = $("#activityFilter option:selected").val();
     var employeeFilter = $("#employeeFilter option:selected").val();
     var statusFilter = $("#statusFilter option:selected").val();
     var assignedDateFilter = $("#assignedDateFilter").val();
     var takenDateFilter = $("#takenDateFilter").val();
     var fromDateFilter = $("#fromDateFilter").val();
     var toDateFilter = $("#toDateFilter").val();
     console.log(window.location.pathname)
     window.location.href=window.location.pathname+'?project='+projectFilter+'&activity='+activityFilter+'&employee='+employeeFilter+'&status='+statusFilter+'&assignedDate='+assignedDateFilter+'&takenDate='+takenDateFilter+'&fromDate='+fromDateFilter+'&toDate='+toDateFilter ;
    
   
   }
   
   function resetFilter()
   {
     window.location.href='/Admin/Report';
   
   
   }
   function resetFilterUser()
   {
     window.location.href='/Report';
   
   
   }

</script>