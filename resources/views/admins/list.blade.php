@extends('layouts.app')

@section('title', 'Admin')

@section('content')
@include('layouts.alert')
<div class="page-header">
    <h3 class="page-title"> Admin </h3>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Admin</a></li>
            <li class="breadcrumb-item active" aria-current="page">Admin</li>
        </ol>
    </nav>
</div>
<div class="row mb-3">
    <div class="col-lg">
        <a href="{{ route('admin.create') }}" class="btn btn-sm btn-gradient-primary btn-icon-text">
            <i class="mdi mdi-plus btn-icon-prepend"></i> Add </a>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <table class="table table-hover" id="adminTable">
                    <thead>
                        <tr>
                            <th>Username</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data as $row)
                        <tr>
                            <td>{{ $row->username }}</td>
                            <td>
                                @if ($row->role_id == 1)
                                <span class="badge badge-primary">{{ $row->role_name }}</span>
                                @else
                                {{ $row->role_name }}
                                @endif
                            </td>
                            <td>
                                @if ($row->status == 1)
                                <span class="badge badge-success">Active</span>
                                @else
                                <span class="badge badge-danger">Inactive</span>
                                @endif
                            </td>
                            <td>
                                @if($row->admin_id != 1)
                                <a class="btn btn-success btn-sm" href="{{ route('admin.edit', ['admin'=>$row->admin_id]) }}"><i class="mdi mdi-pencil-box-outline btn-icon-prepend"></i> Edit</a>
                                <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteAdminModal" data-id="{{ $row->admin_id }}" data-title="{{ $row->username }}"><i class="mdi mdi-delete btn-icon-prepend"></i> Delete</button>
                                <button type="button" class="btn btn-secondary btn-sm" data-toggle="modal" data-target="#resetModal" data-id="{{ $row->admin_id }}" data-title="{{ $row->username }}"><i class="mdi mdi-key-variant btn-icon-prepend"></i> Reset Password</button>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="deleteAdminModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete Admin</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <form class="form-horizontal" method="POST" id="deleteAdminForm">
                    @csrf
                    @method('DELETE')
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary" form="deleteAdminForm">Yes</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="resetModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Reset Password</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <form class="form-horizontal" method="POST" id="resetForm">
                    @csrf
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary" form="resetForm">Yes</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
<script>
    $(document).ready(function() {
		$('#adminTable').DataTable();
    });

    $('#deleteAdminModal').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget)
        var id = button.data('id')
        var title = button.data('title')
        var modal = $(this)
        modal.find('.modal-title').text('Delete ' + title + '?')
        $('#deleteAdminForm').attr('action', '{{ url("admin") }}/' + id);
    });

    $('#resetModal').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget)
        var id = button.data('id')
        var title = button.data('title')
        var modal = $(this)
        $("#resetForm").attr("action", "{{url('admin/reset_password')}}/" + id);
        modal.find('.modal-header .modal-title').text("Are you sure you want to reset password's " + title + "?")
    });
</script>
@endpush