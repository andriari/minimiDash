@extends('layouts.app')

@section('title', 'Content')

@section('content')
<div class="page-header">
    <h3 class="page-title"> Content </h3>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Content</a></li>
            <li class="breadcrumb-item active" aria-current="page">Post</li>
        </ol>
    </nav>
</div>
<div class="row mb-3">
    <div class="col-lg">
        <form class="form-inline" action="{{ url('export/excel') }}" method="POST" id="exportForm">
            @csrf
            <input type="hidden" id="menu" name="menu" value="content_post">
            <button type="submit" class="btn btn-sm btn-gradient-primary btn-icon-text"><i class="mdi mdi-file-export btn-icon-prepend"></i> Export Excel</button>
        </form>
    </div>
</div>
@include('layouts.alert')
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover" id="contentTable">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>User</th>
                                <!-- <th>Title</th> -->
                                <th>Rating</th>
                                <th>Type</th>
                                <th>Curated</th>
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
        $('#contentTable').DataTable({
            processing: true,
            serverSide: true,
            searching: true,
            ajax: "{{ url('content/getDtRowData') }}",
			order: [
				[0, "desc"]
			],
            columns: [{
                    data: 'created_at'
                },
                {
                    data: 'fullname',
                    name: 'minimi_user_data.fullname'
                },
                // {
                //     data: 'content_title'
                // },
                {
                    data: 'content_rating'
                },
                {
                    data: 'content_type'
                },
                {
                    data: 'content_curated'
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