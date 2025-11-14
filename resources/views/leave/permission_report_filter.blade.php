@foreach($permissions as $permission)

    <tr>
        <td>{{ $loop->iteration }}</td>

        <td>{{ $permission->employee?->name ?? 'n' }}</td>
        <td>{{ $permission->requestDate }}</td>
        <td>{{ $permission->permissionDate }}</td>
        <td>{{ $permission->fromTime }}</td>
        <td>{{ $permission->toTime }}</td>
        <td>
            @php
                $totalHours = $permission->totalHours; // e.g., 2.33
                $hours = floor($totalHours);
                $minutes = round(($totalHours - $hours) * 60);
            @endphp
            {{ sprintf('%02d', $hours) }}hrs {{ sprintf('%02d', $minutes) }}mins
        </td>
        <td>
            <span class="right badge 
                                    @if($permission->approval == 'pending') badge-danger
                                    @elseif($permission->approval == 'no') badge-warning
                                    @else badge-success @endif">
                @if($permission->approval == 'pending') Not Approved
                @elseif($permission->approval == 'no') Rejected
                @else Approved
                @endif
            </span>
        </td>
        <td>
            <select name="status" class="form-control" onChange="changePermissionStatus({{ $permission->id }}, this.value)">
                <option value="">Select</option>
                <option value="yes" @if($permission->approval == 'yes') selected @endif>Approved</option>
                <option value="no" @if($permission->approval == 'no') selected @endif>Reject</option>
            </select>
        </td>
    </tr>
@endforeach

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    function changePermissionStatus(permissionId, status) {
        if (!status) return;

        // Optional: confirm for rejection
        if (status === 'no' && !confirm('Are you sure you want to reject this permission?')) return;

        $.ajaxSetup({
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
        });

        let url = status === 'no'
            ? "{{ route('DeclinePermission') }}"
            : "{{ route('ApprovePermission') }}";

        $.ajax({
            url: url,
            type: "POST",
            data: { permissionId: permissionId, status: status },
            success: function (response) {
                if (response.status === true) {
                    let message = status === 'no'
                        ? 'Permission request has been rejected!'
                        : 'Permission request approved!';
                    alert(message);
                    location.reload();
                } else {
                    alert('Failed! ' + (response.message || ''));
                }
            },
            error: function (err) {
                console.error(err);
                alert('Error occurred!');
            }
        });
    }
</script>