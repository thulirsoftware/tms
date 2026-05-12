<script type="text/javascript">

    $(document).ready(function () {
        // no auto filtering
    });

    function filters() {

        var projectFilter = $("#projectFilter").val();
        var activityFilter = $("#activityFilter").val();
        var employeeFilter = $("#employeeFilter").val();
        var statusFilter = $("#statusFilter").val();
        var assignedDateFilter = $("#assignedDateFilter").val();
        var takenDateFilter = $("#takenDateFilter").val();
        var fromDateFilter = $("#fromDateFilter").val();
        var toDateFilter = $("#toDateFilter").val();

        window.location.href =
            window.location.pathname +
            '?project=' + projectFilter +
            '&activity=' + activityFilter +
            '&employee=' + employeeFilter +
            '&status=' + statusFilter +
            '&assignedDate=' + assignedDateFilter +
            '&takenDate=' + takenDateFilter +
            '&fromDate=' + fromDateFilter +
            '&toDate=' + toDateFilter;
    }

    function resetFilter() {
        window.location.href = '/Admin/Report';
    }

    function resetFilterUser() {
        window.location.href = '/Report';
    }

</script>