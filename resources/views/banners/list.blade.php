@extends('layouts.app')

@section('title', 'Banner')

@section('content')
@include('layouts.alert')
<div class="page-header">
    <h3 class="page-title"> Banner </h3>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Banner</a></li>
            <li class="breadcrumb-item active" aria-current="page">Banner</li>
        </ol>
    </nav>
</div>
<div class="row mb-3">
    <div class="col-lg">
        <button type="button" class="btn btn-sm btn-gradient-primary btn-icon-text" data-toggle="modal" data-target="#createBannerModal">
            <i class="mdi mdi-plus btn-icon-prepend"></i> Add
        </button>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover" id="bannerTable">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Alt</th>
                                <th>Image</th>
                                <th>Embedded Link</th>
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
<div class="modal fade" id="createBannerModal" tabindex="-1" role="dialog" aria-labelledby="createBannerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createBannerModalLabel">Add Banner</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="card">
                    <div class="card-body">
                        <form id="createBannerForm" method="POST" action="{{ url('banner') }}" class="forms-sample" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label>Image</label>
                                <input type="file" name="image" class="file-upload-default" required>
                                <div class="input-group col-xs-12">
                                    <input type="text" class="form-control file-upload-info" disabled placeholder="Upload Image">
                                    <span class="input-group-append">
                                        <button class="file-upload-browse btn btn-gradient-primary" type="button">Browse</button>
                                    </span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="title">Title</label>
                                <input type="text" class="form-control" name="title" id="title" placeholder="Title">
                            </div>
                            <div class="form-group">
                                <label for="alt">Alt</label>
                                <input type="text" class="form-control" name="alt" id="alt" placeholder="ALT">
                            </div>
                            <div class="form-group">
                                <label for="link">Embedded Link</label>
                                <input type="text" class="form-control" name="link" id="link" placeholder="Embedded Link">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" form="createBannerForm" class="btn btn-primary">Save</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="editBannerModal" tabindex="-1" role="dialog" aria-labelledby="editBannerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editBannerModalLabel">Edit Banner</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="card">
                    <div class="card-body">
                        <form id="editBannerForm" method="POST" class="forms-sample" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="banner_id" id="banner_id">
                            <div class="form-group">
                                <label>Image</label>
                                <input type="file" name="image" class="file-upload-default">
                                <div class="input-group col-xs-12">
                                    <input type="text" class="form-control file-upload-info" disabled placeholder="Upload Image">
                                    <span class="input-group-append">
                                        <button class="file-upload-browse btn btn-gradient-primary" type="button">Browse</button>
                                    </span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="edit_title">Title</label>
                                <input type="text" class="form-control" name="title" id="edit_title" placeholder="Title">
                            </div>
                            <div class="form-group">
                                <label for="edit_alt">Alt</label>
                                <input type="text" class="form-control" name="alt" id="edit_alt" placeholder="ALT">
                            </div>
                            <div class="form-group">
                                <label for="edit_link">Embedded Link</label>
                                <input type="text" class="form-control" name="link" id="edit_link" placeholder="Embedded Link">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" form="editBannerForm" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="deleteBannerModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete Image</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <form class="form-horizontal" method="POST" id="deleteBannerForm">
                    @csrf
                    @method('DELETE')
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary" form="deleteBannerForm">Yes</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
<script>
    $(document).ready(function() {
        $('#bannerTable').DataTable({
            processing: true,
            serverSide: true,
            searching: true,
            ajax: "{{ url('banner/getDtRowData') }}",
            columns: [{
                    data: 'banner_title'
                },
                {
                    data: 'banner_alt'
                },
                {
                    data: 'banner_image'
                },
                {
                    data: 'banner_embedded_link'
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

    $('#editBannerModal').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget)
        var id = button.data('id')
        var title = button.data('title')
        var alt = button.data('alt')
        var link = button.data('link')
        var modal = $(this)
        modal.find('.modal-title').text('Edit ' + title)
        modal.find('.modal-body #banner_id').val(id)
        modal.find('.modal-body #edit_title').val(title)
        modal.find('.modal-body #edit_alt').val(alt)
        modal.find('.modal-body #edit_link').val(link)
        $('#editBannerForm').attr('action', '{{ url("banner") }}/' + id);
    });

    $('#deleteBannerModal').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget)
        var id = button.data('id')
        var title = button.data('title')
        var modal = $(this)
        modal.find('.modal-title').text('Delete ' + title + '?')
        $('#deleteBannerForm').attr('action', '{{ url("banner") }}/' + id);
    });
</script>
@endpush