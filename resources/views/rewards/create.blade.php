@extends('layouts.app')

@section('title', 'Reward | Add')

@section('content')
@include('layouts.alert')
<div class="page-header">
    <h3 class="page-title"> Reward </h3>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Reward</a></li>
            <li class="breadcrumb-item active" aria-current="page">Add</li>
        </ol>
    </nav>
</div>
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Add Reward</h4>
                <form method="POST" action="{{ url('reward') }}" class="forms-sample" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="reward_name">Name</label>
                        <input type="text" class="form-control" name="reward_name" id="reward_name" placeholder="Reward Name" value="{{ old('reward_name') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="reward_desc">Description</label>
                        <textarea class="form-control editablediv" name="reward_desc" id="reward_desc" rows="4" placeholder="Description">{{ old('reward_desc') }}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="reward_point_price">Point Price</label>
                        <input type="number" class="form-control" name="reward_point_price" id="reward_point_price" placeholder="Point Price" value="{{ old('reward_point_price') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="reward_stock">Stock</label>
                        <input type="number" class="form-control" name="reward_stock" id="reward_stock" placeholder="Stock" value="{{ old('reward_stock') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="reward_embed_link">Embedded Link</label>
                        <input type="text" class="form-control" name="reward_embed_link" id="reward_embed_link" placeholder="Embedded Link" value="{{ old('reward_embed_link') }}">
                    </div>
                    <div class="form-group">
                        <label>Image</label>
                        <input type="file" name="image" class="file-upload-default">
                        <div class="input-group col-xs-12">
                            <input type="text" class="form-control file-upload-info" disabled placeholder="Upload Image" required>
                            <span class="input-group-append">
                                <button class="file-upload-browse btn btn-gradient-primary" type="button">Browse</button>
                            </span>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-gradient-primary mr-2">Submit</button>
                    <a href="{{ route('reward.index') }}" class="btn btn-light">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
<script>
</script>
@endpush