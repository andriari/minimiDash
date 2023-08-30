@extends('layouts.app')

@section('title', 'Home')

@section('content')
@include('layouts.alert')
<div class="page-header">
    <h3 class="page-title"> Category </h3>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Category</a></li>
            <li class="breadcrumb-item active" aria-current="page">Category</li>
        </ol>
    </nav>
</div>
<div class="row mb-3">
    <div class="col-lg">
        <a href="{{ route('category.create') }}" class="btn btn-sm btn-gradient-primary btn-icon-text">
            <i class="mdi mdi-plus btn-icon-prepend"></i> Add </a>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <table class="table table-hover" id="categoryTable">
                    <thead>
                        <tr>
                            <th>Category Name</th>
                            <th>Picture</th>
                            <th>Category Code</th>
                            <th>Tag Signature</th>
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
<!-- Modal -->
<div class="modal fade" id="deleteCategoryModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete Category</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <form class="form-horizontal" method="POST" id="deleteCategoryForm">
                    @csrf
                    @method('DELETE')
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary" form="deleteCategoryForm">Yes</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
<script>
    $(document).ready(function() {
        $('#categoryTable').DataTable({
            processing: true,
            serverSide: true,
            searching: true,
            ajax: "{{ url('category/getDtRowData') }}",
            columns: [{
                    data: 'category_name'
                },
                {
                    data: 'category_picture'
                },
                {
                    data: 'category_code'
                },
                {
                    data: 'category_tag_signature'
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

    $('#deleteCategoryModal').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget)
        var id = button.data('id')
        var title = button.data('title')
        var modal = $(this)
        modal.find('.modal-title').text('Delete ' + title + '?')
        $('#deleteCategoryForm').attr('action', '{{ url("category") }}/' + id);
    });
</script>
@endpush