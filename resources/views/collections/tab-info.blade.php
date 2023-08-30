<div class="tab-pane fade show active" id="pills-info" role="tabpanel" aria-labelledby="pills-info-tab">
    <form method="POST" action="{{ route('collection.update', ['collection' => $info->collection_id]) }}" class="forms-sample" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <input type="hidden" name="collection_id" value="{{ $info->collection_id }}">
        <div class="form-group">
            <label for="collection_name">Name</label>
            <input type="text" class="form-control" name="collection_name" id="collection_name" placeholder="collection Name" value="{{ $info->collection_name }}" required>
        </div>
        <div class="form-group">
            <label for="collection_desc">Description</label>
            <textarea class="form-control" name="collection_desc" id="collection_desc" rows="4" placeholder="Description">{{ $info->collection_desc }}</textarea>
        </div>
        <button type="submit" class="btn btn-gradient-primary mr-2">Submit</button>
        <a href="{{ route('collection.index') }}" class="btn btn-light">Cancel</a>
    </form>
</div>

@push('script')
<script>
    $(document).ready(function() {});
</script>
@endpush