<div class="tab-pane fade" id="pills-product" role="tabpanel" aria-labelledby="pills-product-tab">
    @isset($product)
    @if(!is_array($product))
    <form class="forms-sample">
        <div class="form-group row">
            <label for="product_name" class="col-sm-2 col-form-label">Product</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="product_name" value="{{ $product->product_name }}" readonly>
            </div>
        </div>
        <div class="form-group row">
            <label for="brand_name" class="col-sm-2 col-form-label">Brand</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="brand_name" value="{{ $product->brand_name }}" readonly>
            </div>
        </div>
        <div class="form-group row">
            <label for="category_name" class="col-sm-2 col-form-label">Category</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="category_name" value="{{ $product->category_name }}" readonly>
            </div>
        </div>
        <div class="form-group row">
            <label for="subcat_name" class="col-sm-2 col-form-label">Sub Category</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="subcat_name" value="{{ $product->subcat_name }}" readonly>
            </div>
        </div>
        <div class="form-group row">
            <label for="product_rating" class="col-sm-2 col-form-label">Rating</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="product_rating" value="{{ $product->product_rating }}" readonly>
            </div>
        </div>
        <div class="form-group row">
            <label for="pict" class="col-sm-2 col-form-label">Picture</label>
            <div class="col-sm-4">
                <img src="{{ $product->pict }}" class="img-thumbnail">
            </div>
        </div>
    </form>
    @endif
    @endisset

    @isset($proposed_item)
    <h4 class="card-title">Assign Product</h4>
    <form class="form-inline mb-4" method="POST" action="{{ url('content/product/assign') }}">
        @csrf
        <input type="hidden" name="content_id" value="{{ $content_id }}">
        <input type="hidden" name="product_id" id="product_id">
        <label class="sr-only" for="product_name_assign">Product</label>
        <div class="input-group mb-2 mr-sm-2">
            <input type="text" class="form-control mb-2 mr-sm-2" id="product_name_assign" placeholder="Product" onkeydown="if (event.keyCode == 13) return false;">
            <div class="invalid-feedback">
                Product Not Found.
            </div>
        </div>
        <button type="submit" class="btn btn-gradient-primary mb-2">Submit</button>
    </form>
    <form class="forms-sample">
        <div class="form-group row">
            <label for="product_name" class="col-sm-2 col-form-label">Product</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="product_name" value="{{ $proposed_item->product_name }}" readonly>
            </div>
        </div>
        <div class="form-group row">
            <label for="product_brand" class="col-sm-2 col-form-label">Brand</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="product_brand" value="{{ $proposed_item->product_brand }}" readonly>
            </div>
        </div>
        <div class="form-group row">
            <label for="category_name" class="col-sm-2 col-form-label">Category</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="category_name" value="{{ $proposed_item->category_name }}" readonly>
            </div>
        </div>
        <div class="form-group row">
            <label for="subcat_name" class="col-sm-2 col-form-label">Sub Category</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="subcat_name" value="{{ $proposed_item->subcat_name }}" readonly>
            </div>
        </div>
    </form>
    @endisset
</div>

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

    $("#product_name_assign").autocomplete({
        source: function(request, response) {
            $(".invalid-feedback").hide();
            var param = {
                '_token': "{{ csrf_token() }}",
                'search_query': request.term
            }
            $.post("{{ url(config('env.APP_URL')) }}/product/search", param, function(data) {
                // console.log(data)
                if (data.code == 200) {
                    response($.map(data.data, function(item) {
                        return {
                            code: item.product_id,
                            value: item.product_name
                        };
                    }));
                } else {
                    $(".invalid-feedback").show();
                }
            }, "json");
        },
        select: function(event, ui) {
            $("#product_id").val(ui.item.code);
        }
    });
</script>
@endpush