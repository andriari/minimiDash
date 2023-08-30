<div class="tab-pane fade show active" id="pills-info" role="tabpanel" aria-labelledby="pills-info-tab">
    <form method="POST" action="{{ route('brand.update', ['brand' => $info->brand_id]) }}" class="forms-sample" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="brand_name">Brand Name</label>
            <input type="text" class="form-control" name="brand_name" id="brand_name" placeholder="Brand Name" value="{{ $info->brand_name }}">
        </div>
        <div class="form-group">
            <label>Picture</label>
            <input type="file" name="brand_picture" class="file-upload-default">
            <div class="input-group col-xs-12">
                <input type="text" class="form-control file-upload-info" disabled placeholder="Upload Picture">
                <span class="input-group-append">
                    <button class="file-upload-browse btn btn-gradient-primary" type="button">Browse</button>
                </span>
            </div>
        </div>
        <div class="form-group">
            <label for="brand_code">Brand Code</label>
            <input type="text" class="form-control" name="brand_code" id="brand_code" placeholder="Brand Code" value="{{ $info->brand_code }}">
        </div>
        <div class="form-group">
            <label for="brand_tag_signature">Tag Signature</label>
            <textarea class="form-control" name="brand_tag_signature" id="brand_tag_signature" rows="4" placeholder="Example: #toys">{{ $info->brand_tag_signature }}</textarea>
        </div>
        <button type="submit" class="btn btn-gradient-primary mr-2">Submit</button>
        <a href="{{ route('brand.index') }}" class="btn btn-light">Cancel</a>
    </form>
</div>