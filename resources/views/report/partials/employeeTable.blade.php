<table id="employeeReportTable" class="table table-striped table-bordered">
    <thead>
        <tr>
            <th>Date</th>
            <th>Employee</th>
            <th>Designation</th>
            <th>Total Hours</th>
            <th>Working Hours</th>
        </tr>
    </thead>
    <tbody>
        @forelse($reportData as $empId => $dates)
            @foreach($dates as $date => $data)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($date)->format('M d, Y') }}</td>
                    <td>{{ $data['employeeName'] }}</td>
                    <td>{{ $data['designation'] }}</td>
                    <td>{{ $data['totalHours'] }}</td>
                    <td class="workHours">{{ $data['workHours'] }}</td>
                </tr>
            @endforeach
        @empty
            <tr>
                <td colspan="5" class="text-center">No Records Found</td>
            </tr>
        @endforelse
    </tbody>
</table>
<script>
    function sumWorkingHoursTop() {
        var totalMinutes = 0;

        $('#employeeReportTable tbody tr').each(function () {
            var work = $(this).find('.workHours').text();
            if (!work) return;

            var parts = work.split(':');
            var hours = parseInt(parts[0]) || 0;
            var minutes = parseInt(parts[1]) || 0;

            totalMinutes += hours * 60 + minutes;
        });

        var totalHours = Math.floor(totalMinutes / 60);
        var totalMins = totalMinutes % 60;

        $('#totalWorkingHoursTop').text(
            String(totalHours).padStart(2, '0') + ':' + String(totalMins).padStart(2, '0')
        );
    }

    // Call after page load and after AJAX table update
    $(document).ready(function () {
        sumWorkingHoursTop();
    });

    $(document).ajaxComplete(function () {
        sumWorkingHoursTop();
    });

</script>