@extends('layouts.app')

@section('title', 'Product | Add')

@section('content')
@include('layouts.alert')
<div class="page-header">
    <h3 class="page-title"> Product </h3>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('product.index') }}">Product</a></li>
            <li class="breadcrumb-item active" aria-current="page">Add</li>
        </ol>
    </nav>
</div>
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Add Product</h4>
                <form method="POST" action="{{ url('product') }}" class="forms-sample" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="product_type" value="1">
                    <div class="form-group">
                        <label for="category_id-0">Category</label>
                        {{ Form::select('category_id', $categories, old('category_id'), ['class' => 'form-control', 'id' => 'category_id-0']) }}
                    </div>
                    <!-- <div class="form-group">
                        <label for="subcat_id">Sub Category</label>
                        <select class="form-control" id="subcat_id" name="subcat_id" required></select>
                    </div> -->
                    <div id="subcat-0" class="mb-3">
                        <label>Sub Category</label>
                        <span id="subcat_alt-0">
                        </span>
                    </div>
                    <button id="add_category" type="button" class="btn btn-rounded btn-gradient-light mb-3 mt-1" onclick="addCategory()">+ Add Category</button>
                    <div class="form-group">
                        <label for="brand_id">Brand</label>
                        {{ Form::select('brand_id', $brands, old('brand_id'), ['class' => 'form-control', 'id' => 'brand_id']) }}
                    </div>
                    <div class="form-group">
                        <label for="product_name">Product Name</label>
                        <input type="text" class="form-control" name="product_name" id="product_name" placeholder="Product Name" value="{{ old('product_name') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="product_weight">Product Weight</label>
                        <div class="input-group">
                            <input type="number" class="form-control" name="product_weight" id="product_weight" placeholder="Product Weight (gram)" value="0" step=".01">
                            <div class="input-group-append">
                                <span class="input-group-text">gram</span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="product_desc">Description</label>
                        <textarea class="form-control editablediv" name="product_desc" id="product_desc" rows="4" placeholder="Description" required>{{ old('product_desc') }}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="product_feed_desc">Description for google feed</label>
                        <textarea class="form-control" name="product_feed_desc" id="product_feed_desc" rows="4" placeholder="Description for google feed">{{ old('product_feed_desc') }}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="product_tags">Tag Signature</label>
                        <textarea class="form-control" name="product_tags" id="product_tags" rows="4" placeholder="Example: #toys" required>{{ old('product_tags') }}</textarea>
                    </div>
                    <button type="submit" class="btn btn-gradient-primary mr-2">Submit</button>
                    <a href="{{ route('product.index') }}" class="btn btn-light">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
<script>
    var category_index = 1;
    $(document).ready(function() {
        getSubCat2($('#category_id-0').val(), 0);
        $('#category_id').on('change', function() {
            getSubCat(this.value)
        });
        $(document).on('change', '[id^=category_id-]', function() {
            id = this.id;
            var lastChar = id.substr(id.length - 1)
            console.log(lastChar)
            getSubCat2(this.value, lastChar)
        });
    });

    String.prototype.splice = function(idx, rem, str) {
        return this.slice(0, idx) + str + this.slice(idx + Math.abs(rem));
    };

    function addCategory() {
        var option = '{!! Form::select("category_alt[]", $categories, "", ["class" => "form-control", "id" => "category_id-"]) !!}';
        option = option.splice(46, 0, category_index);
        var cat = '<div class="form-group" id="category-' + category_index + '"><label for="category_id-' + category_index + '" class="align-middle">Category Alt</label><button type="button" class="btn btn-xs btn-rounded btn-gradient-dark ml-3 mb-2" onclick="removeCategory(' + category_index + ')">Remove Category</button>' + option + '</div>';
        var subcat = '<div id="subcat-' + category_index + '" class="mb-3"><label>Sub Category Alt</label><span id="subcat_alt-' + category_index + '"></span></div>';
        $('#add_category').before(cat + subcat);
        category_index++;
    }

    function removeCategory(category_index) {
        $('#category-' + category_index).remove();
        $('#subcat-' + category_index).remove();
    }

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

    function getSubCat2(category_id, idx) {
        $('#subcat_alt-' + idx).find('.custom-checkbox').remove().end();
        $.get("{{ url(config('env.APP_URL')) }}/category/sub/" + category_id, function(data) {
            if (data.code == 200) {
                $.each(data.data, function(i, item) {
                    $('#subcat_alt-' + idx).append('<div class="custom-control custom-checkbox"><input class="form-check-input" type="checkbox" name="subcategory_alt[]" value="' + item.subcat_id + '" id="check-' + item.subcat_id + '" /><label class="form-check-label" for="check-' + item.subcat_id + '">' + item.subcat_name + '</label></div>');
                });
            } else {
                $('#subcat_alt-' + idx).append('<div>Sub Category Empty</div>');
            }
        }, "json");
    }
</script>
@endpush