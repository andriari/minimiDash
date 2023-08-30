<div class="tab-pane fade show active" id="pills-info" role="tabpanel" aria-labelledby="pills-info-tab">
    <form method="POST" action="{{ route('reward.update', ['reward' => $info->reward_id]) }}" class="forms-sample" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <input type="hidden" name="reward_id" value="{{ $info->reward_id }}">
        <div class="form-group">
            <label for="reward_name">Name</label>
            <input type="text" class="form-control" name="reward_name" id="reward_name" placeholder="Reward Name" value="{{ $info->reward_name }}" required>
        </div>
        <div class="form-group">
            <label for="reward_desc">Description</label>
            <textarea class="form-control editablediv" name="reward_desc" id="reward_desc" rows="4" placeholder="Description">{{ $info->reward_desc }}</textarea>
        </div>
        <div class="form-group">
            <label for="reward_point_price">Point Price</label>
            <input type="number" class="form-control" name="reward_point_price" id="reward_point_price" placeholder="Point Price" value="{{ $info->reward_point_price }}" required>
        </div>
        <div class="form-group">
            <label for="reward_stock">Stock</label>
            <input type="number" class="form-control" name="reward_stock" id="reward_stock" placeholder="Stock" value="{{ $info->reward_stock }}" required>
        </div>
        <div class="form-group">
            <label for="reward_embed_link">Embedded Link</label>
            <input type="text" class="form-control" name="reward_embed_link" id="reward_embed_link" placeholder="Embedded Link" value="{{ $info->reward_embed_link }}">
        </div>
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
        <div class="form-group row">
            <label for="reward_thumbnail" class="col-sm-2 col-form-label">Current Image:</label>
            <div class="col-sm-4">
                <img src="{{ $info->reward_thumbnail }}" class="img-thumbnail">
            </div>
        </div>
        <button type="submit" class="btn btn-gradient-primary mr-2">Submit</button>
        <a href="{{ route('reward.index') }}" class="btn btn-light">Cancel</a>
    </form>
</div>