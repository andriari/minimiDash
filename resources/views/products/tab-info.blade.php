<div class="tab-pane fade show active" id="pills-info" role="tabpanel" aria-labelledby="pills-info-tab">
    <form method="POST" action="{{ route('product.update', ['product' => $info->product_id]) }}" class="forms-sample" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <input type="hidden" name="product_id" value="{{ $info->product_id }}">
        <input type="hidden" name="product_type" value="1">
        <div class="form-group row">
            <label for="review_count" class="col-sm-2 col-form-label font-weight-bold">Reviews</label>
            <div class="col-sm-10">
                <input type="text" readonly class="form-control-plaintext" id="review_count" value="{{ $info->review_count ?? 0 }}">
            </div>
        </div>
        <!-- <div class="form-group">
            <label for="category_id">Category</label>
            {{ Form::select('category_id', $categories, $info->category_id, ['class' => 'form-control', 'id' => 'category_id']) }}
        </div>
        <div class="form-group">
            <label for="subcat_id">Sub Category</label>
            <select class="form-control" id="subcat_id" name="subcat_id" required></select>
        </div> -->
        @foreach ($info->alt as $row)
        @if ($loop->first)
        <div class="form-group" id="category-{{ $loop->index }}">
            <label for="category_id-{{ $loop->index }}" class="align-middle">Category</label>
            {!! Form::select("category_id", $categories, $row->category_id, ["class" => "form-control", "id" => "category_id-".$loop->index]) !!}
        </div>
        <div id="subcat-{{ $loop->index }}" class="mb-3">
            <label>Sub Category</label>
            <span id="subcat_alt-{{ $loop->index }}">
                @foreach($row->subcat as $subcat)
                <div class="custom-control custom-checkbox">
                    <input class="form-check-input" type="checkbox" name="subcategory_alt[]" value="{{ $subcat->subcat_id }}" id="check-{{ $subcat->subcat_id }}" @if(in_array($subcat->subcat_id, $row->subcat_id)) checked @endif />
                    <label class="form-check-label" for="check-{{ $subcat->subcat_id }}">{{ $subcat->subcat_name }}</label>
                </div>
                @endforeach
            </span>
        </div>
        @else
        <div class="form-group" id="category-{{ $loop->index }}">
            <label for="category_id-{{ $loop->index }}" class="align-middle">Category Alt</label>
            <button type="button" class="btn btn-xs btn-rounded btn-gradient-dark ml-3 mb-2" onclick="removeCategory('{{ $loop->index }}')">Remove Category</button>
            {!! Form::select("category_alt[]", $categories, $row->category_id, ["class" => "form-control", "id" => "category_id-".$loop->index]) !!}
        </div>
        <div id="subcat-{{ $loop->index }}" class="mb-3">
            <label>Sub Category Alt</label>
            <span id="subcat_alt-{{ $loop->index }}">
                @foreach($row->subcat as $subcat)
                <div class="custom-control custom-checkbox">
                    <input class="form-check-input" type="checkbox" name="subcategory_alt[]" value="{{ $subcat->subcat_id }}" id="check-{{ $subcat->subcat_id }}" @if(in_array($subcat->subcat_id, $row->subcat_id)) checked @endif />
                    <label class="form-check-label" for="check-{{ $subcat->subcat_id }}">{{ $subcat->subcat_name }}</label>
                </div>
                @endforeach
            </span>
        </div>
        @endif
        @endforeach
        <button id="add_category" type="button" class="btn btn-rounded btn-gradient-light mb-3 mt-1" onclick="addCategory()">+ Add Category</button>
        <div class="form-group">
            <label for="brand_id">Brand</label>
            {{ Form::select('brand_id', $brands, $info->brand_id, ['class' => 'form-control', 'id' => 'brand_id']) }}
        </div>
        <div class="form-group">
            <label for="product_name">Product Name</label>
            <input type="text" class="form-control" name="product_name" id="product_name" placeholder="Product Name" value="{{ $info->product_name }}" required>
        </div>
        <div class="form-group">
            <label for="product_weight">Product Weight</label>
            <div class="input-group">
                <input type="number" class="form-control" name="product_weight" id="product_weight" placeholder="Product Weight (gram)" value="{{ $info->product_weight }}" step=".01">
                <div class="input-group-append">
                    <span class="input-group-text">gram</span>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label for="product_desc">Description</label>
            <textarea class="form-control editablediv" name="product_desc" id="product_desc" rows="4" placeholder="Description">{{ $info->product_desc }}</textarea>
        </div>
        <div class="form-group">
            <label for="product_feed_desc">Description for google feed</label>
            <textarea class="form-control" name="product_feed_desc" id="product_feed_desc" rows="4" placeholder="Description for google feed">{{ $info->product_feed_desc }}</textarea>
        </div>
        <div class="form-group">
            <label for="product_tags">Tag Signature</label>
            <textarea class="form-control" name="product_tags" id="product_tags" rows="4" placeholder="Example: #toys">{{ $info->product_tags }}</textarea>
        </div>
        <button type="submit" class="btn btn-gradient-primary mr-2">Submit</button>
        <a href="{{ route('product.index') }}" class="btn btn-light">Cancel</a>
    </form>
</div>

@push('script')
<script>
    var category_index = "{{ count($info->alt)+1 }}";
    $(document).ready(function() {
        // getSubCat2($('#category_id-0').val(), 0);
        // getSubCat($('#category_id').val(), "{{ $info->subcat_id }}");
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
        var option = '{!! Form::select("category_alt[]", $categories, "", ["class" => "form-control", "id" => "category_alt-"]) !!}';
        option = option.splice(46, 0, category_index);
        var cat = '<div class="form-group" id="category-' + category_index + '"><label for="category_alt-' + category_index + '" class="align-middle">Category Alt</label><button type="button" class="btn btn-xs btn-rounded btn-gradient-dark ml-3 mb-2" onclick="removeCategory(' + category_index + ')">Remove Category</button>' + option + '</div>';
        var subcat = '<div id="subcat-' + category_index + '" class="mb-3"><label>Sub Category Alt</label><span id="subcat_alt-' + category_index + '"></span></div>';
        $('#add_category').before(cat + subcat);
        category_index++;
    }

    function removeCategory(category_index) {
        $('#category-' + category_index).remove();
        $('#subcat-' + category_index).remove();
    }

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

    function getSubCat2(category_id, idx) {
        $('#subcat_alt-' + idx).find('.custom-checkbox').remove().end();
        $.get("{{ url(config('env.APP_URL')) }}/category/sub/" + category_id, function(data) {
            if (data.code == 200) {
                $.each(data.data, function(i, item) {
                    $('#subcat_alt-' + idx).append('<div class="custom-control custom-checkbox"><input class="form-check-input" type="checkbox" name="subcategory_alt[]" value="' + item.subcat_id + '" id="check-' + item.subcat_id + '" /><label class="form-check-label" for="check-' + item.subcat_id + '">' + item.subcat_name + '</label></div>');
                });
            } else {}
        }, "json");
    }
</script>
@endpush