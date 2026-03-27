@extends('theme.default')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Dashboard</h1>
    </div>
</div>

<form method="GET" action="{{ route('adminHome') }}">
    <div class="row">
        <div class="col-md-3">
            <div class="form-group">
                <label for="start_date">From:</label>
                <input type="date" name="start_date" id="start_date" class="form-control" value="{{ $startDate }}">
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label for="end_date">To:</label>
                <input type="date" name="end_date" id="end_date" class="form-control" value="{{ $endDate }}">
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label for="employee_id">Employee:</label>
                <select name="employee_id" id="employee_id" class="form-control">
                    <option value="">All</option>
                    @foreach($employees as $employee)
                        <option value="{{ $employee->id }}" {{ $employeeId == $employee->id ? 'selected' : '' }}>
                            {{ $employee->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label>&nbsp;</label>
                <button type="submit" class="btn btn-primary btn-block">Filter</button>
            </div>
        </div>
    </div>
</form>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>

@if (count($employeeName) == 1)
    @php 
        $employeeIdKey = array_key_first($employeeName);
    @endphp
    <div class="row">
        <!-- Chart Column -->
        <div class="col-md-6">
            <div class="chart-container" style="border: 2px solid #d1d1d1; border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); background-color: #f9f9f9; margin: 10px; padding: 15px; transition: all 0.3s ease; height: auto;">
                <div class="chart-title" style="text-align: center; font-weight: bold; margin-bottom: 10px;">
                    {{ $employeeName[$employeeIdKey] }}
                </div>
                <canvas id="myChart0" style="max-height: 300px;"></canvas>
            </div>
        </div>
        <!-- Table Column -->
        <div class="col-md-6">
            <table class="table table-bordered table-striped table-hover">
                <thead class="thead-light">
                    <tr>
                        <th>Project Name</th>
                        <th>Activity</th>
                        <th>Total Hours</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($calculatedTaskInfos as $info)
                        <tr>
                            <td>{{ $info['project']->projectName }}</td>
                            <td>{{ $info['activity']->name }}</td>
                            <td>{{ $info['totalHours'] }} : {{ $info['totalMinutes'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <script>
        var xValues0 = @json($projectName[$employeeIdKey]);
        var yValues0 = @json($totalHours[$employeeIdKey]);
        var barColors0 = [
            "#ff6384", "#36a2eb", "#ffce56", "#4bc0c0", "#9966ff", "#ff9f40", "#ff6384", "#36a2eb", "#ffce56", "#4bc0c0"
        ];

        new Chart("myChart0", {
            type: "pie",
            data: {
                labels: xValues0,
                datasets: [{
                    backgroundColor: barColors0,
                    data: yValues0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            boxWidth: 20,
                            font: { size: 14 }
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                return tooltipItem.label + ': ' + tooltipItem.raw + ' hours';
                            }
                        }
                    }
                }
            }
        });
    </script>
@else
    <div class="row">
        @foreach ($employeeName as $i => $employee)
            <div class="col-md-4">
                <div class="chart-container" style="border: 2px solid #d1d1d1; border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); background-color: #f9f9f9; margin: 10px; padding: 15px; transition: all 0.3s ease; width: 100%; max-width: 400px; height: auto;">
                    <div class="chart-title" style="text-align: center; font-weight: bold; margin-bottom: 10px;">
                        {{ $employee }}
                    </div>
                    <canvas id="myChart{{ $i }}" style="max-height: 300px;"></canvas>
                </div>
            </div>

            <script>
                var xValues{{ $i }} = @json($projectName[$i]);
                var yValues{{ $i }} = @json($totalHours[$i]);
                var barColors{{ $i }} = [
                    "#ff6384", "#36a2eb", "#ffce56", "#4bc0c0", "#9966ff", "#ff9f40", "#ff6384", "#36a2eb", "#ffce56", "#4bc0c0"
                ];

                new Chart("myChart{{ $i }}", {
                    type: "pie",
                    data: {
                        labels: xValues{{ $i }},
                        datasets: [{
                            backgroundColor: barColors{{ $i }},
                            data: yValues{{ $i }}
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'top',
                                labels: {
                                    boxWidth: 20,
                                    font: { size: 14 }
                                }
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(tooltipItem) {
                                        return tooltipItem.label + ': ' + tooltipItem.raw + ' hours';
                                    }
                                }
                            }
                        }
                    }
                });
            </script>
        @endforeach
    </div>

    <!-- Task Table -->
    <div class="row">
        <div class="col-md-12">
            <table class="table table-bordered table-striped table-hover">
                <thead class="thead-light">
                    <tr>
                        <th>Project Name</th>
                        <th>Employee Name</th>
                        <th>Activity</th>
                        <th>Total Hours</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($calculatedTaskInfos as $info)
                        <tr>
                            <td>{{ $info['project']->projectName }}</td>
                            <td>{{ $info['employee']->name }}</td>
                            <td>{{ $info['activity']->name }}</td>
                            <td>{{ $info['totalHours'] }} : {{ $info['totalMinutes'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endif
@endsection
