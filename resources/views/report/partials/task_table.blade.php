<div class="table-responsive shadow rounded bg-white p-3">
    <table class="table table-striped table-bordered align-middle">
        <thead class="table-dark">
            <tr>
                <th>Date</th>
                <th>Assigned By</th>
                <th>Employee</th>
                <th>Project</th>
                <th>Activity</th>
                <th>Instruction</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($tasks as $task)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($task->assignedDate)->format('d-m-Y') }}</td>
                    <td>{{ $task->assigned_by_name ?? 'N/A' }}</td>
                    <td>{{ $task->assigned_to_name ?? 'N/A' }}</td>
                    <td>{{ $task->project->projectName ?? 'N/A' }}</td>
                    <td>{{ $task->activity->name ?? 'N/A' }}</td>
                    <td>{{ $task->instruction }}</td>
                    <td>
                        <span class="badge 
                            @if($task->status == 1) bg-warning 
                            @elseif($task->status == 2) bg-info 
                            @elseif($task->status == 3) bg-success 
                            @elseif($task->status == 4) bg-secondary 
                            @endif">
                            {{ $task->state->name ?? 'Unknown' }}
                        </span>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="text-center text-muted">No tasks found</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
