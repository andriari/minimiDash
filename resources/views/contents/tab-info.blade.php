<div class="tab-pane fade show active" id="pills-info" role="tabpanel" aria-labelledby="pills-info-tab">
    <form class="forms-sample">
        <div class="form-group row">
            <label for="content_title" class="col-sm-2 col-form-label">Title</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="content_title" value="{{ $info->content_title }}" readonly>
            </div>
        </div>
        <div class="form-group row">
            <label for="content_text" class="col-sm-2 col-form-label">Text</label>
            <div class="col-sm-10">
                <textarea class="form-control" name="content_text" id="content_text" rows="4" placeholder="Meta Description" readonly>{{ $info->content_text }}</textarea>
            </div>
        </div>
        <div class="form-group row">
            <label for="content_rating" class="col-sm-2 col-form-label">Rating</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="content_rating" value="{{ $info->content_rating }}" readonly>
            </div>
        </div>
        <div class="form-group row">
            <label for="content_embed_link" class="col-sm-2 col-form-label">Link</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="content_embed_link" value="{{ $info->content_embed_link }}" readonly>
            </div>
        </div>
        <div class="form-group row">
            <label for="content_video_link" class="col-sm-2 col-form-label">Video Link</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="content_video_link" value="{{ $info->content_video_link }}" readonly>
            </div>
        </div>
        <div class="form-group row">
            <label for="thumb" class="col-sm-2 col-form-label">Thumbnail</label>
            <div class="col-sm-4">
                <img src="{{ $info->content_thumbnail }}" class="img-thumbnail">
            </div>
        </div>
    </form>
</div>

@push('script')
<script>
</script>
@endpush