<div class="tab-pane fade" id="pills-sub-category" role="tabpanel" aria-labelledby="pills-sub-category-tab">
    <div class="row mb-3">
        <div class="col-lg">
            <button type="button" class="btn btn-sm btn-gradient-primary btn-icon-text" data-toggle="modal" data-target="#createSubModal">
                <i class="mdi mdi-plus btn-icon-prepend"></i> Add
            </button>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Sub Category Name</th>
                            <th>Picture</th>
                            <th>Sub Category Code</th>
                            <th>Tag Signature</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($sc as $row)
                        <tr>
                            <td>{{ $row->subcat_name }}</td>
                            <td>@if($row->subcat_picture) <img src="{{ $row->subcat_picture }}" alt="image" /> @endif</td>
                            <td>{{ $row->subcat_code }}</td>
                            <td style="word-wrap: break-word;min-width: 200px;max-width: 200px;white-space: normal;">{{ $row->subcat_tag_signature }}</td>
                            <td>
                                <!-- Button trigger modal -->
                                <button type="button" class="btn btn-sm btn-block btn-gradient-primary btn-icon-text" data-toggle="modal" data-target="#editSubModal" data-id="{{ $row->subcat_id }}" data-name="{{ $row->subcat_name }}" data-subcat_code="{{ $row->subcat_code }}" data-subcat_tag_signature="{{ $row->subcat_tag_signature }}">
                                    <i class="mdi mdi-pencil-box-outline btn-icon-prepend"></i> Edit
                                </button>
                                <button type="button" class="btn btn-sm btn-block btn-gradient-danger btn-icon-text" data-toggle="modal" data-target="#deleteSubModal" data-id="{{ $row->subcat_id }}" data-title="{{ $row->subcat_name }}"><i class="mdi mdi-delete btn-icon-prepend"></i>Delete</button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="createSubModal" tabindex="-1" role="dialog" aria-labelledby="createSubModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createSubModalLabel">Create Sub Category</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="card">
                        <div class="card-body">
                            <form id="createSubForm" method="POST" action="{{ url('category/sub') }}" class="forms-sample" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="category_id" value="{{ $info->category_id }}">
                                <div class="form-group">
                                    <label for="subcat_name">Sub Category Name</label>
                                    <input type="text" class="form-control" name="subcat_name" id="subcat_name" placeholder="Name" required>
                                </div>
                                <div class="form-group">
                                    <label>Picture</label>
                                    <input type="file" name="subcat_picture" class="file-upload-default">
                                    <div class="input-group col-xs-12">
                                        <input type="text" class="form-control file-upload-info" disabled placeholder="Upload Picture">
                                        <span class="input-group-append">
                                            <button class="file-upload-browse btn btn-gradient-primary" type="button">Upload</button>
                                        </span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="subcat_code">Sub Category Code</label>
                                    <input type="text" class="form-control" name="subcat_code" id="subcat_code" placeholder="Category Code" required>
                                </div>
                                <div class="form-group">
                                    <label for="subcat_tag_signature">Tag Signature</label>
                                    <textarea class="form-control" name="subcat_tag_signature" id="subcat_tag_signature" rows="3" placeholder="Example: #toys"></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="subcat_meta_title">Meta Title</label>
                                    <input type="text" class="form-control" name="subcat_meta_title" id="subcat_meta_title" placeholder="Meta Title">
                                </div>
                                <div class="form-group">
                                    <label for="subcat_meta_desc">Meta Description</label>
                                    <textarea class="form-control" name="subcat_meta_desc" id="subcat_meta_desc" rows="4" placeholder="Meta Description"></textarea>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" form="createSubForm" class="btn btn-primary">Save</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="editSubModal" tabindex="-1" role="dialog" aria-labelledby="editSubModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editSubModalLabel">Edit Sub Category</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="card">
                        <div class="card-body">
                            <form id="editSubForm" method="POST" class="forms-sample" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="category_id" value="{{ $info->category_id }}">
                                <div class="form-group">
                                    <label for="edit_subcat_name">Sub Category Name</label>
                                    <input type="text" class="form-control" name="subcat_name" id="edit_subcat_name" placeholder="Name" required>
                                </div>
                                <div class="form-group">
                                    <label>Picture</label>
                                    <input type="file" name="subcat_picture" class="file-upload-default">
                                    <div class="input-group col-xs-12">
                                        <input type="text" class="form-control file-upload-info" disabled placeholder="Upload Picture">
                                        <span class="input-group-append">
                                            <button class="file-upload-browse btn btn-gradient-primary" type="button">Browse</button>
                                        </span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="edit_subcat_code">Sub Category Code</label>
                                    <input type="text" class="form-control" name="subcat_code" id="edit_subcat_code" placeholder="Category Code" required>
                                </div>
                                <div class="form-group">
                                    <label for="edit_subcat_tag_signature">Tag Signature</label>
                                    <textarea class="form-control" name="subcat_tag_signature" id="edit_subcat_tag_signature" rows="3" placeholder="Example: #toys"></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="edit_subcat_meta_title">Meta Title</label>
                                    <input type="text" class="form-control" name="subcat_meta_title" id="edit_subcat_meta_title" placeholder="Meta Title">
                                </div>
                                <div class="form-group">
                                    <label for="edit_subcat_meta_desc">Meta Description</label>
                                    <textarea class="form-control" name="subcat_meta_desc" id="edit_subcat_meta_desc" rows="4" placeholder="Meta Description"></textarea>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" form="editSubForm" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="deleteSubModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Delete Sub Category</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <form class="form-horizontal" method="POST" id="deleteSubForm">
                        @csrf
                        @method('DELETE')
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" form="deleteSubForm">Yes</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('script')
<script>
    $('#editSubModal').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget)
        var id = button.data('id')
        var name = button.data('name')
        var subcat_code = button.data('subcat_code')
        var subcat_tag_signature = button.data('subcat_tag_signature')
        var subcat_meta_title = button.data('subcat_meta_title')
        var subcat_meta_desc = button.data('subcat_meta_desc')
        var modal = $(this)
        modal.find('.modal-title').text('Edit ' + name)
        modal.find('.modal-body #edit_subcat_name').val(name)
        modal.find('.modal-body #edit_subcat_code').val(subcat_code)
        modal.find('.modal-body #edit_subcat_tag_signature').val(subcat_tag_signature)
        modal.find('.modal-body #edit_subcat_meta_title').val(subcat_meta_title)
        modal.find('.modal-body #edit_subcat_meta_desc').val(subcat_meta_desc)
        $('#editSubForm').attr('action', '{{ url("category/sub") }}/' + id);
    });

    $('#deleteSubModal').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget)
        var id = button.data('id')
        var title = button.data('title')
        var modal = $(this)
        modal.find('.modal-title').text('Delete ' + title + '?')
        $('#deleteSubForm').attr('action', '{{ url("category/sub") }}/' + id);
    });
</script>
@endpush