@extends('layouts.app')

@section('title', 'User')

@section('content')
@include('layouts.alert')
<div class="page-header">
    <h3 class="page-title"> User </h3>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">User</a></li>
            <li class="breadcrumb-item active" aria-current="page">User</li>
        </ol>
    </nav>
</div>
<div class="row mb-3">
    <div class="col-lg">
        <form class="form-inline" action="{{ url('export/excel') }}" method="POST" id="exportForm">
            @csrf
            <input type="hidden" id="menu" name="menu" value="user">
            <button type="submit" class="btn btn-sm btn-gradient-primary btn-icon-text"><i class="mdi mdi-file-export btn-icon-prepend"></i> Export Excel</button>
        </form>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover" id="userTable">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Point</th>
                                <th>Sign Up Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
<script>
    $(document).ready(function() {
        $('#userTable').DataTable({
            processing: true,
            serverSide: true,
            searching: true,
            ajax: "{{ url('user/getDtRowData') }}",
            columns: [{
                    data: 'fullname'
                },
                {
                    data: 'email',
                },
                {
                    data: 'point_count',
                },
                {
                    data: 'created_at',
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },
            ]
        });
    });
</script>
@endpush