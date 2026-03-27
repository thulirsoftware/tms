@extends('theme.default')
@section('content')
    <style>
        .space {
            margin-bottom: 20px;
        }
    </style>
    <div class="container-fluid">
        <h2 class="mb-4">List of Roles</h2>

        {{-- Error Messages --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Action Buttons --}}
        <div class="d-flex m-3" style="text-align:end;padding:10px;">
            <a href="{{ route('roles.create') }}" class="btn btn-info">
                <i class="fa fa-plus"></i> Add Role
            </a>
            <button class="btn btn-primary ms-2" onclick="location.reload()">
                <i class="fa fa-refresh"></i>
            </button>
        </div>

        {{-- Roles Table --}}
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th style="width: 50px;">ID</th>
                        <th>Name</th>
                        <th>Permissions</th>
                        <th style="width: 180px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($roles as $i => $role)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $role->name }}</td>
                            <td>
                                @forelse($role->permissions as $perm)
                                    <span class="badge bg-secondary">{{ $perm->name }}</span>
                                @empty
                                    <span class="text-muted">No Permissions</span>
                                @endforelse
                            </td>
                            <td>
                                <p class="d-flex"><a href="{{ route('roles.edit', $role->id) }}" class="">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                <form action="{{ route('roles.destroy', $role->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-danger" onclick="return confirm('Are you sure?')">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </form>

                                </p>

                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted">No Roles Found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection