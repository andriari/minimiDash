@extends('layouts.app')

@section('title', 'Admin')

@section('content')
@include('layouts.alert')
<div class="page-header">
    <h3 class="page-title"> Roles </h3>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Admin</a></li>
            <li class="breadcrumb-item active" aria-current="page">Roles</li>
        </ol>
    </nav>
</div>
<div class="row mb-3">
    <div class="col-lg">
        <button type="button" class="btn btn-sm btn-gradient-primary btn-icon-text" data-toggle="modal" data-target="#addRoleModal">
            <i class="mdi mdi-plus btn-icon-prepend"></i> Add
        </button>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Role</th>
                                <th>Permissions</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data as $row)
                            <tr>
                                <td class="align-baseline">{{ $row->role_name }}</td>
                                <td>
                                    <ul>
                                        @foreach ($row->menus as $menu)
                                        <li>{{ $menu }}</li>
                                        @endforeach
                                    </ul>
                                </td>
                                <td class="align-baseline"><a class="btn btn-success btn-sm" href="{{ url('admin/role/'.$row->role_id) }}"><i class="mdi mdi-pencil-box-outline btn-icon-prepend"></i> Edit</a></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="addRoleModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Role</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ url('admin/role') }}" method="POST" id="addForm">
                            @csrf
                            <div class="form-group">
                                <label for="role_name">Role Name</label>
                                <input type="text" class="form-control" id="role_name" name="role_name" placeholder="Role Name">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" form="addForm">Save</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
<script>
    $(document).ready(function() {});
</script>
@endpush