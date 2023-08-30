@extends('layouts.app')

@section('title', 'Product | Add')

@section('content')
@include('layouts.alert')
<div class="page-header">
    <h3 class="page-title"> Product </h3>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Product</a></li>
            <li class="breadcrumb-item"><a href="{{ route('product.digital.index') }}">Digital</a></li>
            <li class="breadcrumb-item active" aria-current="page">Add</li>
        </ol>
    </nav>
</div>
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Add Product</h4>
                <form method="POST" action="{{ url('product/digital') }}" class="forms-sample" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="product_type" value="2">
                    <div class="form-group">
                        <label for="category_id">Category</label>
                        {{ Form::select('category_id', $categories, old('category_id'), ['class' => 'form-control', 'id' => 'category_id']) }}
                    </div>
                    <div class="form-group">
                        <label for="subcat_id">Sub Category</label>
                        <select class="form-control" id="subcat_id" name="subcat_id" required></select>
                    </div>
                    <div class="form-group">
                        <label for="product_name">Product Name</label>
                        <input type="text" class="form-control" name="product_name" id="product_name" placeholder="Product Name" value="{{ old('product_name') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="product_price">Product Price</label>
                        <input type="number" class="form-control" name="product_price" id="product_price" placeholder="Product Price" value="{{ old('product_price') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="product_name">Description</label>
                        <input type="text" class="form-control" name="product_sub_name" id="product_sub_name" placeholder="Description" value="{{ old('product_sub_name') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="product_name">Short Description</label>
                        <input type="text" class="form-control" name="product_short_desc" id="product_short_desc" placeholder="Short Description" value="{{ old('product_short_desc') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="product_desc">Terms and Condition</label>
                        <textarea class="form-control editablediv" name="product_desc" id="product_desc" rows="4" placeholder="Terms and Condition">{{ old('product_desc') }}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="product_tags">Tag Signature</label>
                        <textarea class="form-control" name="product_tags" id="product_tags" rows="4" placeholder="Example: #toys">{{ old('product_tags') }}</textarea>
                    </div>
                    <button type="submit" class="btn btn-gradient-primary mr-2">Submit</button>
                    <a href="{{ route('product.digital.index') }}" class="btn btn-light">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
<script>
    $(document).ready(function() {
        getSubCat($('#category_id').val());
        $('#category_id').on('change', function() {
            getSubCat(this.value)
        });
    });

    function getSubCat(category_id) {
        $('#subcat_id').find('option').remove().end();
        $.get("{{ url(config('env.APP_URL')) }}/category/sub/" + category_id, function(data) {
            if (data.code == 200) {
                $.each(data.data, function(i, item) {
                    $('#subcat_id').append($('<option>', {
                        value: item.subcat_id,
                        text: item.subcat_name
                    }));
                });
            } else {
                $('#subcat_id').append($('<option>', {
                    value: "",
                    text: "Empty"
                }));
            }
        }, "json");
    }
</script>
@endpush