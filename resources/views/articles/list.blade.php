@extends('layouts.app')

@section('title', 'Article')

@section('content')
@include('layouts.alert')
<div class="page-header">
    <h3 class="page-title"> Article </h3>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Article</a></li>
            <li class="breadcrumb-item active" aria-current="page">Article</li>
        </ol>
    </nav>
</div>
<div class="row mb-3">
    <div class="col-lg">
        <a href="{{ route('article.create') }}" class="btn btn-sm btn-gradient-primary btn-icon-text">
            <i class="mdi mdi-plus btn-icon-prepend"></i> Add </a>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover" id="articleTable">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Sub Title</th>
                                <th>Thumbnail</th>
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
        $('#articleTable').DataTable({
            processing: true,
            serverSide: true,
            searching: true,
            ajax: "{{ url('article/getDtRowData') }}",
            columns: [{
                    data: 'content_title'
                },
                {
                    data: 'content_subtitle',
                },
                {
                    data: 'content_thumbnail'
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