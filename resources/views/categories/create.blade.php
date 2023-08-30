@extends('layouts.app')

@section('title', 'Home')

@section('content')
@include('layouts.alert')
<div class="page-header">
    <h3 class="page-title"> Category </h3>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Category</a></li>
            <li class="breadcrumb-item active" aria-current="page">Add</li>
        </ol>
    </nav>
</div>
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Add Category</h4>
                <form method="POST" action="{{ url('category') }}" class="forms-sample" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="category_name">Category Name</label>
                        <input type="text" class="form-control" name="category_name" id="category_name" placeholder="Category Name" required>
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
                        <input type="text" class="form-control" name="category_code" id="category_code" placeholder="Category Code">
                    </div>
                    <div class="form-group">
                        <label for="category_tag_signature">Tag Signature</label>
                        <textarea class="form-control" name="category_tag_signature" id="category_tag_signature" rows="4" placeholder="Example: #toys"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="category_meta_title">Meta Title</label>
                        <input type="text" class="form-control" name="category_meta_title" id="category_meta_title" placeholder="Meta Title">
                    </div>
                    <div class="form-group">
                        <label for="category_meta_desc">Meta Description</label>
                        <textarea class="form-control" name="category_meta_desc" id="category_meta_desc" rows="4" placeholder="Meta Description"></textarea>
                    </div>
                    <button type="submit" class="btn btn-gradient-primary mr-2">Submit</button>
                    <a href="{{ route('category.index') }}" class="btn btn-light">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
@endpush