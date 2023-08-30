<div class="tab-pane fade show active" id="pills-info" role="tabpanel" aria-labelledby="pills-info-tab">
    <form method="POST" action="{{ route('category.update', ['category' => $info->category_id]) }}" class="forms-sample" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="category_name">Category Name</label>
            <input type="text" class="form-control" name="category_name" id="category_name" placeholder="Name" value="{{ $info->category_name }}">
        </div>
        <div class="form-group">
            <label>Picture</label>
            <input type="file" name="category_picture" class="file-upload-default">
            <div class="input-group col-xs-12">
                <input type="text" class="form-control file-upload-info" disabled placeholder="Upload Picture">
                <span class="input-group-append">
                    <button class="file-upload-browse btn btn-gradient-primary" type="button">Browse</button>
                </span>
            </div>
        </div>
        <div class="form-group">
            <label for="category_code">Category Code</label>
            <input type="text" class="form-control" name="category_code" id="category_code" placeholder="Category Code" value="{{ $info->category_code }}">
        </div>
        <div class="form-group">
            <label for="category_tag_signature">Tag Signature</label>
            <textarea class="form-control" name="category_tag_signature" id="category_tag_signature" rows="4" placeholder="Example: #toys">{{ $info->category_tag_signature }}</textarea>
        </div>
        <div class="form-group">
            <label for="category_meta_title">Meta Title</label>
            <input type="text" class="form-control" name="category_meta_title" id="category_meta_title" placeholder="Meta Title" value="{{ $info->category_meta_title }}">
        </div>
        <div class="form-group">
            <label for="category_meta_desc">Meta Description</label>
            <textarea class="form-control" name="category_meta_desc" id="category_meta_desc" rows="4" placeholder="Meta Description">{{ $info->category_meta_desc }}</textarea>
        </div>
        <button type="submit" class="btn btn-gradient-primary mr-2">Submit</button>
        <a href="{{ route('category.index') }}" class="btn btn-light">Cancel</a>
    </form>
</div>