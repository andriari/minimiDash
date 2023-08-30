<div class="tab-pane fade show active" id="pills-info" role="tabpanel" aria-labelledby="pills-info-tab">
    <form method="POST" action="{{ route('article.update', ['article' => $content_id]) }}" class="forms-sample" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <input type="hidden" name="content_id" value="{{ $content_id }}">
        <!-- <div class="form-group">
            <label for="category_id">Category</label>
            Form::select('category_id', $categories, $info->category_id, ['class' => 'form-control', 'id' => 'category_id']) }}
        </div> -->
        <div class="form-group">
            <label for="title">Title</label>
            <input type="text" class="form-control" name="title" id="title" placeholder="Title" value="{{ $info->content_title }}" required>
        </div>
        <div class="form-group">
            <label for="subtitle">Sub Title</label>
            <input type="text" class="form-control" name="subtitle" id="subtitle" placeholder="Sub Title" value="{{ $info->content_subtitle }}">
        </div>
        <div class="form-group">
            <label>Thumbnail</label>
            <input type="file" name="thumbnail" class="file-upload-default">
            <div class="input-group col-xs-12">
                <input type="text" class="form-control file-upload-info" disabled placeholder="Upload Picture">
                <span class="input-group-append">
                    <button class="file-upload-browse btn btn-gradient-primary" type="button">Browse</button>
                </span>
            </div>
        </div>
        <div class="form-group">
            <label for="uri">URI</label>
            <input type="text" class="form-control" name="uri" id="uri" placeholder="URI" value="{{ $info->content_uri }}">
        </div>
        <div class="form-group">
            <label for="meta_tag">Meta Title</label>
            <input type="text" class="form-control" name="meta_tag" id="meta_tag" placeholder="Meta Title" value="{{ $info->meta_title }}">
        </div>
        <div class="form-group">
            <label for="meta_desc">Meta Description</label>
            <textarea class="form-control" name="meta_desc" id="meta_desc" rows="4" maxlength="255" placeholder="Meta Description (Max 255 Character)">{{ $info->meta_desc }}</textarea>
        </div>
        <div class="form-group">
            <label for="text">Text</label>
            <textarea class="form-control editablediv" name="text" id="text" rows="4" placeholder="Description">{{ $info->content_text }}</textarea>
        </div>
        <button type="submit" class="btn btn-gradient-primary mr-2">Submit</button>
        <a href="{{ route('article.index') }}" class="btn btn-light">Cancel</a>
    </form>
</div>

@push('script')
@endpush