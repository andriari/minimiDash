@extends('layouts.app')

@section('title', 'Home')

@section('content')
@include('layouts.alert')
<div class="page-header">
    <h3 class="page-title"> Brand </h3>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Brand</a></li>
            <li class="breadcrumb-item active" aria-current="page">Add</li>
        </ol>
    </nav>
</div>
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Add Brand</h4>
                <form method="POST" action="{{ url('brand') }}" class="forms-sample" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="brand_name">Brand Name</label>
                        <input type="text" class="form-control" name="brand_name" id="brand_name" placeholder="Brand Name" required>
                        <div class="invalid-feedback">
                            Brand Name Already Used
                        </div>
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
                        <input type="text" class="form-control" name="brand_code" id="brand_code" placeholder="Brand Code">
                    </div>
                    <div class="form-group">
                        <label for="brand_tag_signature">Tag Signature</label>
                        <textarea class="form-control" name="brand_tag_signature" id="brand_tag_signature" rows="4" placeholder="Example: #toys"></textarea>
                    </div>
                    <button type="submit" class="btn btn-gradient-primary mr-2">Submit</button>
                    <a href="{{ route('brand.index') }}" class="btn btn-light">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
<script>
    $('#brand_name').keyup(function(e) {
        if (this.value != "") {
            $.get("{{ url(config('env.APP_URL')) }}/brand/check", {
                brand_name: this.value
            }, function(data) {
                if (data.message == "true") {
                    $('#brand_name').removeClass('is-invalid');
                    $('#brand_name').addClass('is-valid');
                } else {
                    $('.invalid-feedback').text('Brand Name Already Used');
                    $('#brand_name').removeClass('is-valid');
                    $('#brand_name').addClass('is-invalid');
                }
            }, "json");
        }
    });
</script>
@endpush