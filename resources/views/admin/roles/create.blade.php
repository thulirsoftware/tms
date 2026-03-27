@extends('theme.default')
@section('content')


<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center space">
        <h2 class="fw-bold">
            Create New Role
        </h2>
        <a href="{{ route('roles.index') }}" class="btn btn-link text-decoration-none">
            <i class="fa fa-arrow-left"></i>
        </a>
    </div>
<style>
    .space{
        margin-bottom: 20px;
    }
</style>
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

            <form action="{{ route('roles.store') }}" method="POST">
                @csrf

                {{-- Role Name --}}
                <div class="space">
                    <label class="form-label fw-semibold">Role Name</label>
                    <input type="text" name="name" 
                           class="form-control form-control-lg rounded-pill" 
                           value="{{ old('name') }}" 
                           placeholder="Enter role name" required>
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
                                           id="perm{{ $permission->id }}">
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
                    <button type="reset" class="btn btn-outline-secondary rounded-pill btn-animated">
                        <i class="fa fa-undo"></i> Reset
                    </button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 btn-animated">
                        </i> Create Role
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
