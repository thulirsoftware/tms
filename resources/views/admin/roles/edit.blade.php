@extends('theme.default')
@section('content')

<div class="container">
    <div class="d-flex justify-content-between align-items-center space">
        <h2 class="fw-bold text-primary">
            <i class="fa fa-edit text-warning"></i> Edit Role
        </h2>
        
    </div>

    {{-- Error Messages --}}
    @if ($errors->any())
        <div class="alert alert-danger shadow-sm rounded">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Form Card --}}
    <div class="card shadow-lg border-0 rounded-3">
        <div class="card-body p-4">

            <form action="{{ route('roles.update', $role->id) }}" method="POST">
                @csrf
                @method('PUT')

                {{-- Role Name --}}
                <div class="space">
                    <label class="form-label fw-semibold">Role Name</label>
                    <input type="text" name="name" 
                           class="form-control form-control-lg rounded-pill" 
                           value="{{ $role->name }}" required>
                </div>

                {{-- Permissions --}}
                <div class="space">
                    <label class="form-label fw-semibold">Assign Permissions</label>
                    <div class="row g-3">
                        @foreach($permissions as $permission)
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" 
                                           name="permissions[]" value="{{ $permission->id }}" 
                                           id="perm{{ $permission->id }}"
                                           {{ in_array($permission->id, $rolePermissions) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="perm{{ $permission->id }}">
                                        <span class="badge bg-light text-dark border px-3 py-2">
                                            {{ $permission->name }}
                                        </span>
                                    </label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Buttons --}}
                <div class="d-flex justify-content-end gap-3">
                    <a href="{{ route('roles.index') }}" class="btn btn-outline-secondary rounded-pill btn-animated">
                        <i class="fa fa-times"></i> Cancel
                    </a>
                    <button type="submit" class="btn btn-warning rounded-pill px-4 btn-animated">
                        Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
