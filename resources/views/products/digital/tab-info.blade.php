<div class="tab-pane fade show active" id="pills-info" role="tabpanel" aria-labelledby="pills-info-tab">
    <form method="POST" action="{{ route('product.digital.update', ['product' => $info->product_id]) }}" class="forms-sample" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <input type="hidden" name="product_id" value="{{ $info->product_id }}">
        <div class="form-group">
            <label for="category_id">Category</label>
            {{ Form::select('category_id', $categories, $info->category_id, ['class' => 'form-control', 'id' => 'category_id']) }}
        </div>
        <div class="form-group">
            <label for="subcat_id">Sub Category</label>
            <select class="form-control" id="subcat_id" name="subcat_id" required></select>
        </div>
        <div class="form-group">
            <label for="product_name">Product Name</label>
            <input type="text" class="form-control" name="product_name" id="product_name" placeholder="Product Name" value="{{ $info->product_name }}" required>
        </div>
        <div class="form-group">
            <label for="product_price">Product Price</label>
            <input type="number" class="form-control" name="product_price" id="product_price" placeholder="Product Price" value="{{ $info->product_price }}" required>
        </div>
        <div class="form-group">
            <label for="product_name">Description</label>
            <input type="text" class="form-control" name="product_sub_name" id="product_sub_name" placeholder="Description" value="{{ $info->product_sub_name }}" required>
        </div>
        <div class="form-group">
            <label for="product_name">Short Description</label>
            <input type="text" class="form-control" name="product_short_desc" id="product_short_desc" placeholder="Short Description" value="{{ $info->product_short_desc }}" required>
        </div>
        <div class="form-group">
            <label for="product_desc">Terms and Condition</label>
            <textarea class="form-control editablediv" name="product_desc" id="product_desc" rows="4" placeholder="Term and Condition">{{ $info->product_desc }}</textarea>
        </div>
        <div class="form-group">
            <label for="product_tags">Tag Signature</label>
            <textarea class="form-control" name="product_tags" id="product_tags" rows="4" placeholder="Example: #toys">{{ $info->product_tags }}</textarea>
        </div>
        <button type="submit" class="btn btn-gradient-primary mr-2">Submit</button>
        <a href="{{ route('product.digital.index') }}" class="btn btn-light">Cancel</a>
    </form>
</div>

@push('script')
<script>
    $(document).ready(function() {
        getSubCat($('#category_id').val(), "{{ $info->subcat_id }}");
        $('#category_id').on('change', function() {
            getSubCat(this.value)
        });
    });

    function getSubCat(category_id, subcat_id = null) {
        $('#subcat_id').find('option').remove().end();
        $.get("{{ url(config('env.APP_URL')) }}/category/sub/" + category_id, function(data) {
            if (data.code == 200) {
                $.each(data.data, function(i, item) {
                    $('#subcat_id').append($('<option>', {
                        value: item.subcat_id,
                        text: item.subcat_name
                    }));
                });
                if (subcat_id != null) {
                    $('#subcat_id').val("{{ $info->subcat_id }}");
                }
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