<div class="tab-pane fade" id="pills-gallery" role="tabpanel" aria-labelledby="pills-gallery-tab">
    <div class="row mb-3">
        <div class="col-lg">
            <button type="button" class="btn btn-sm btn-gradient-primary btn-icon-text" data-toggle="modal" data-target="#createGalleryModal">
                <i class="mdi mdi-plus btn-icon-prepend"></i> Add
            </button>
        </div>
    </div>
    <div class="row">
        @foreach ($images as $row)
        <div class="col-sm-4">
            <div class="card">
                <img class="card-img-top" src="{{ $row->prod_gallery_picture }}">
                <div class="card-body p-1">
                    <h5 class="card-title">{{ $row->prod_gallery_title }}</h5>
                    <h6 class="card-subtitle mb-2 text-muted">{{ $row->prod_gallery_alt }}</h6>
                </div>
                <div class="card-footer">
                    <div class="row">
                        <div class="col">
                            <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#editGalleryModal" data-id="{{ $row->prod_gallery_id }}" data-title="{{ $row->prod_gallery_title }}" data-alt="{{ $row->prod_gallery_alt }}" data-main="{{ $row->main_poster }}">Edit</button>
                        </div>
                        <div class="col">
                            <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteGalleryModal" data-id="{{ $row->prod_gallery_id }}" data-title="{{ $row->prod_gallery_title }}">Delete</button>
                        </div>
                        @if($row->main_poster==1)
                        <div class="col">
                            <h3 class="mx-auto"><span class="badge badge-info">Main Poster</span></h3>
                        </div>
                        @else
                        <div class="col mt-1">
                            <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#mainGalleryModal" data-id="{{ $row->prod_gallery_id }}" data-title="{{ $row->prod_gallery_title }}">Set Main</button>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    <!-- Modal -->
    <div class="modal fade" id="createGalleryModal" tabindex="-1" role="dialog" aria-labelledby="createGalleryModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createGalleryModalLabel">Create Variant</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="card">
                        <div class="card-body">
                            <form id="createGalleryForm" method="POST" action="{{ url('product/gallery') }}" class="forms-sample" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $info->product_id }}">
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
                                <div class="form-check form-check-flat form-check-primary">
                                    <label class="form-check-label">
                                        <input type="checkbox" class="form-check-input" name="main"> Main Poster </label>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" form="createGalleryForm" class="btn btn-primary">Save</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="editGalleryModal" tabindex="-1" role="dialog" aria-labelledby="editGalleryModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editGalleryModalLabel">Edit Variant</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="card">
                        <div class="card-body">
                            <form id="editGalleryForm" method="POST" class="forms-sample" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="prod_gallery_id" id="prod_gallery_id">
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
                                <div class="form-check form-check-flat form-check-primary">
                                    <label class="form-check-label">
                                        <input type="checkbox" class="form-check-input" name="main" id="edit_main"> Main Poster </label>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" form="editGalleryForm" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="deleteGalleryModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Delete Image</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <form class="form-horizontal" method="POST" id="deleteGalleryForm">
                        @csrf
                        @method('DELETE')
                        <input type="hidden" name="prod_gallery_id" id="delete_prod_gallery_id">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" form="deleteGalleryForm">Yes</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="mainGalleryModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Set Main Poster</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <form class="form-horizontal" method="POST" id="mainGalleryForm">
                        @csrf
                        <input type="hidden" name="poster_id" id="main_id_poster">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" form="mainGalleryForm">Yes</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('script')
<script>
    $('#editGalleryModal').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget)
        var id = button.data('id')
        var title = button.data('title')
        var alt = button.data('alt')
        var main = button.data('main')
        var modal = $(this)
        modal.find('.modal-title').text('Edit ' + title)
        modal.find('.modal-body #prod_gallery_id').val(id)
        modal.find('.modal-body #edit_title').val(title)
        modal.find('.modal-body #edit_alt').val(alt)
        modal.find('.modal-body #edit_main').prop("checked", main)
        $('#editGalleryForm').attr('action', '{{ url("product/gallery") }}/' + id);
    });

    $('#deleteGalleryModal').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget)
        var id = button.data('id')
        var title = button.data('title')
        var modal = $(this)
        modal.find('.modal-title').text('Delete ' + title + '?')
        $('#deleteGalleryForm').attr('action', '{{ url("product/gallery") }}/' + id);
    });

    $('#mainGalleryModal').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget)
        var id = button.data('id')
        var title = button.data('title')
        var modal = $(this)
        modal.find('.modal-title').text('Set ' + title + ' as Main Poster?')
        $('#mainGalleryForm').attr('action', '{{ url("product/gallery") }}/' + id);
    });
</script>
@endpush