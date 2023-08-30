@extends('layouts.app')

@section('title', 'Collection')

@section('content')
@include('layouts.alert')
<div class="page-header">
    <h3 class="page-title"> Collection </h3>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Collection</a></li>
            <li class="breadcrumb-item active" aria-current="page">Collection</li>
        </ol>
    </nav>
</div>
<div class="row mb-3">
    <div class="col-lg">
        <a href="{{ route('collection.create') }}" class="btn btn-sm btn-gradient-primary btn-icon-text">
            <i class="mdi mdi-plus btn-icon-prepend"></i> Add </a>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover" id="collectionTable">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Description</th>
                                <th>Show</th>
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
<!-- Modal -->
<div class="modal fade" id="deleteCollectionModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete Collection</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <form class="form-horizontal" method="POST" id="deleteCollectionForm">
                    @csrf
                    @method('DELETE')
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary" form="deleteCollectionForm">Yes</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="publishCollectionModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Show/Hide Collection</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <form class="form-horizontal" method="GET" id="publishCollectionForm">
                    @csrf
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary" form="publishCollectionForm">Yes</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
<script>
    $(document).ready(function() {
        $('#collectionTable').DataTable({
            processing: true,
            serverSide: true,
            searching: true,
            ajax: "{{ url('collection/getDtRowData') }}",
            columns: [{
                    data: 'collection_name'
                },
                {
                    data: 'collection_desc'
                },
                {
                    data: 'show',
                    name: 'show',
                    orderable: true,
                    searchable: false
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

    $('#deleteCollectionModal').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget)
        var id = button.data('id')
        var title = button.data('title')
        var modal = $(this)
        modal.find('.modal-title').text('Delete ' + title + '?')
        $('#deleteCollectionForm').attr('action', '{{ url("collection") }}/' + id);
    });

    $('#publishCollectionModal').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget)
        var id = button.data('id')
        var title = button.data('title')
        var act = button.data('act')
        var modal = $(this)
        modal.find('.modal-title').text(act+' ' + title + '?')
        $('#publishCollectionForm').attr('action', '{{ url("collection/publish") }}/' + id);
    });
</script>
@endpush