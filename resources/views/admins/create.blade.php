@extends('layouts.app')

@section('title', 'Admin | Add')

@section('content')
@include('layouts.alert')
<div class="page-header">
    <h3 class="page-title"> Admin </h3>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Admin</a></li>
            <li class="breadcrumb-item active" aria-current="page">Add</li>
        </ol>
    </nav>
</div>
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Add Admin</h4>
                <form method="POST" action="{{ url('admin') }}" class="forms-sample" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="username">User Name</label>
                        <input type="text" class="form-control" id="username" name="username" placeholder="User Name" value="{{ old('username') }}">
                    </div>
                    <div class="form-group">
                        <label for="role_id">Role</label>
                        {{ Form::select('role_id', $roles, old('role_id'), ['class' => 'form-control', 'id' => 'role_id']) }}
                    </div>
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select name="status" class="form-control" id="status">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="password"><strong>Password</strong></label>
                        <input type="text" readonly class="form-control-plaintext" id="password" value="123456">
                    </div>
                    <button type="submit" class="btn btn-gradient-primary mr-2">Submit</button>
                    <a href="{{ route('admin.index') }}" class="btn btn-light">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
<script>
</script>
@endpush