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
                    <td style="white-space: nowrap;">{{ \Carbon\Carbon::parse($task->assignedDate)->format('d-m-Y') }}</td>
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

@if ($tasks->hasPages()) <div style="width: 100%; margin: 15px 0;">

    {{-- TOP: Showing --}}
    <div style="display: flex; justify-content: flex-end; margin-bottom: 5px; font-size: 13px; color: #777;">
        Showing {{ $tasks->firstItem() }}–{{ $tasks->lastItem() }} of {{ $tasks->total() }}
    </div>

    {{-- BOTTOM: Rows + Pagination --}}
    <div style="display: flex; justify-content: flex-end; align-items: center;">

        {{-- Rows --}}
        <form method="GET" id="perPageForm" style="display: flex; align-items: center; margin-right: 15px;">
            <label style="margin-right: 5px; margin-bottom: 0;">Rows:</label>
            <select name="per_page" class="form-control form-control-sm" style="width:auto;">
                <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                <option value="50" {{ request('per_page', 50) == 50 ? 'selected' : '' }}>50</option>
                <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                <option value="200" {{ request('per_page') == 200 ? 'selected' : '' }}>200</option>
            </select>
        </form>

        {{-- Pagination --}}
        <ul class="pagination" style="margin: 0;">

            {{-- Prev --}}
            @if ($tasks->onFirstPage())
                <li class="page-item disabled"><span class="page-link">Prev</span></li>
            @else
                <li class="page-item">
                    <a class="page-link pagination-link" href="{{ $tasks->previousPageUrl() }}">Prev</a>
                </li>
            @endif

            @php
                $start = max($tasks->currentPage() - 2, 1);
                $end = min($tasks->currentPage() + 2, $tasks->lastPage());
            @endphp

            @if ($start > 1)
                <li class="page-item"><a class="page-link pagination-link" href="{{ $tasks->url(1) }}">1</a></li>
                @if ($start > 2)
                    <li class="page-item disabled"><span class="page-link">...</span></li>
                @endif
            @endif

            @for ($i = $start; $i <= $end; $i++)
                <li class="page-item {{ $tasks->currentPage() == $i ? 'active' : '' }}">
                    <a class="page-link pagination-link" href="{{ $tasks->url($i) }}">{{ $i }}</a>
                </li>
            @endfor

            @if ($end < $tasks->lastPage())
                @if ($end < $tasks->lastPage() - 1)
                    <li class="page-item disabled"><span class="page-link">...</span></li>
                @endif
                <li class="page-item">
                    <a class="page-link pagination-link" href="{{ $tasks->url($tasks->lastPage()) }}">{{ $tasks->lastPage() }}</a>
                </li>
            @endif

            {{-- Next --}}
            @if ($tasks->hasMorePages())
                <li class="page-item">
                    <a class="page-link pagination-link" href="{{ $tasks->nextPageUrl() }}">Next</a>
                </li>
            @else
                <li class="page-item disabled"><span class="page-link">Next</span></li>
            @endif

        </ul>

    </div>
</div>

@endif
