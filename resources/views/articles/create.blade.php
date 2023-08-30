@extends('layouts.app')

@section('title', 'Article | Add')

@section('content')
@include('layouts.alert')
<div class="page-header">
    <h3 class="page-title"> Article </h3>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Article</a></li>
            <li class="breadcrumb-item active" aria-current="page">Add</li>
        </ol>
    </nav>
</div>
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Add Article</h4>
                <form method="POST" action="{{ url('article') }}" class="forms-sample" enctype="multipart/form-data">
                    @csrf
                    <!-- <div class="form-group">
                        <label for="category_id">Category</label>
                        Form::select('category_id', $categories, old('category_id'), ['class' => 'form-control', 'id' => 'category_id']) }}
                    </div> -->
                    <div class="form-group">
                        <label for="title">Title</label>
                        <input type="text" class="form-control" name="title" id="title" placeholder="Title" value="{{ old('title') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="subtitle">Sub Title</label>
                        <input type="text" class="form-control" name="subtitle" id="subtitle" placeholder="Sub Title" value="{{ old('subtitle') }}">
                    </div>
                    <div class="form-group">
                        <label>Thumbnail</label>
                        <input type="file" name="thumbnail" class="file-upload-default">
                        <div class="input-group col-xs-12">
                            <input type="text" class="form-control file-upload-info" disabled placeholder="Upload Picture" required>
                            <span class="input-group-append">
                                <button class="file-upload-browse btn btn-gradient-primary" type="button">Browse</button>
                            </span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="uri">URI</label>
                        <input type="text" class="form-control" name="uri" id="uri" placeholder="URI" value="{{ old('uri') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="meta_tag">Meta Title</label>
                        <input type="text" class="form-control" name="meta_tag" id="meta_tag" placeholder="Meta Title" value="{{ old('meta_tag') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="meta_desc">Meta Description</label>
                        <textarea class="form-control" name="meta_desc" id="meta_desc" rows="4" maxlength="255" placeholder="Meta Description (Max 255 Character)">{{ old('meta_desc') }}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="text">Text</label>
                        <textarea class="form-control editablediv" name="text" id="text" rows="4" placeholder="Text">{{ old('text') }}</textarea>
                    </div>
                    <button type="submit" class="btn btn-gradient-primary mr-2">Submit</button>
                    <a href="{{ route('article.index') }}" class="btn btn-light">Cancel</a>
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