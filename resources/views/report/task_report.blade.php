@extends('theme.default')
@section('content')

    <div class="container-fluid">
        <h3 class="mb-4">Task Report</h3>

        <!-- Filter Section -->
        <form id="filterForm" class="row g-3 mb-4 align-items-end">
            <div class="col-md-3">
                <label>Assigned By</label>
                <select name="assignedBy" id="assignedBy" class="form-control auto-filter">
                    <option value="">All</option>
                    @foreach($assignedByList as $id => $name)
                        <option value="{{ $id }}">{{ $name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3">
                <label>Employee</label>
                <select name="employee" id="employee" class="form-control auto-filter">
                    <option value="">All</option>
                    @foreach($employees as $id => $name)
                        <option value="{{ $id }}">{{ $name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2">
                <label>From Date</label>
                <input type="date" name="from_date" class="form-control auto-filter">
            </div>

            <div class="col-md-2">
                <label>To Date</label>
                <input type="date" name="to_date" class="form-control auto-filter">
            </div>
        </form>

        <!-- Task Table -->
        <div id="taskTableContainer">
            @include('report.partials.task_table', ['tasks' => $tasks])
        </div>
    </div>
    <script>
        $(document).ready(function () {
            $('.auto-filter').on('change', function () {
                fetchFilteredTasks();
            });

            function fetchFilteredTasks() {
                $.ajax({
                    url: "{{ route('task.report') }}",
                    type: 'GET',
                    data: $('#filterForm').serialize(),
                    beforeSend: function () {
                        $('#taskTableContainer').html('<div class="text-center p-3"><b>Loading...</b></div>');
                    },
                    success: function (response) {
                        // ✅ Update table
                        $('#taskTableContainer').html(response.table);

                        // ✅ Rebuild dropdowns
                        let assignedBySelect = $('select[name="assignedBy"]');
                        let employeeSelect = $('select[name="employee"]');

                        let selectedBy = assignedBySelect.val();
                        let selectedEmp = employeeSelect.val();

                        assignedBySelect.empty().append('<option value="">All</option>');
                        $.each(response.assignedByList, function (id, name) {
                            assignedBySelect.append('<option value="' + id + '">' + name + '</option>');
                        });
                        if (selectedBy) assignedBySelect.val(selectedBy);

                        employeeSelect.empty().append('<option value="">All</option>');
                        $.each(response.employees, function (id, name) {
                            employeeSelect.append('<option value="' + id + '">' + name + '</option>');
                        });
                        if (selectedEmp) employeeSelect.val(selectedEmp);
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
        });
    </script>


@endsection