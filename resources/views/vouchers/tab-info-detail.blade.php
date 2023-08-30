<div class="tab-pane fade show active" id="pills-info" role="tabpanel" aria-labelledby="pills-info-tab">
    <form class="forms-sample" enctype="multipart/form-data">
        <div class="form-group">
            <label for="voucher_name">Voucher Name</label>
            <input type="text" class="form-control" id="voucher_name" name="voucher_name" placeholder="Voucher Name" value="{{ $info->voucher_name }}" readonly>
        </div>
        <div class="form-group">
            <label for="voucher_code">Voucher Code</label>
            <input type="text" class="form-control" id="voucher_code" name="voucher_code" placeholder="Voucher Code" value="{{ $info->voucher_code }}" readonly>
        </div>
        <div class="form-group">
            <label for="voucher_desc">Description</label>
            <textarea class="form-control" name="voucher_desc" id="voucher_desc" rows="4" placeholder="Description" readonly>{{ $info->voucher_desc }}</textarea>
        </div>
        <div class="form-group">
            <label for="voucher_tnc">Terms and Conditions</label>
            <textarea class="form-control editablediv" name="voucher_tnc" id="voucher_tnc" rows="4" placeholder="" readonly>{{ $info->voucher_tnc }}</textarea>
        </div>
        <div class="form-group">
            <label for="shown_in_list">Shown in list</label>
            <select name="shown_in_list" class="form-control" id="shown_in_list" disabled>
                <option value="0">No</option>
                <option value="1">Yes</option>
            </select>
        </div>
        <div class="form-group">
            <label for="publish">Publish</label>
            <select name="publish" class="form-control" id="publish" disabled>
                <option value="0">No</option>
                <option value="1">Yes</option>
            </select>
        </div>
        <div class="form-group">
            <label for="limit_usage">Limit Usage</label>
            <input type="number" class="form-control" id="limit_usage" name="limit_usage" placeholder="Limit Usage" value="{{ $info->limit_usage }}" readonly>
        </div>
        <div class="form-group" id="usage_period_once">
            <label for="usage_period">Usage Period</label>
            <select name="usage_period" class="form-control" id="usage_period" disabled>
                <option value="ONCE">ONCE</option>
                <option value="CUSTOM">CUSTOM</option>
            </select>
        </div>
        <div class="form-row @if($info->usage_period == 'ONCE') d-none @endif" id="usage_period_custom" readonly>
            <div class="form-group col-md-6">
                <label for="duration_count">Voucher Duration</label>
                <input type="number" class="form-control" id="duration_count" name="duration_count" placeholder="Duration Count" readonly>
            </div>
            <div class="form-group col-md-6">
                <label for="duration_range" class="text-white">Voucher Duration</label>
                <select id="duration_range" class="form-control" name="duration_range" placeholder="Duration Range" disabled>
                    <option value="Days">Days</option>
                    <option value="Weeks">Weeks</option>
                    <option value="Months">Months</option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label for="voucher_minimum">Voucher Minimum Price</label>
            <input type="number" class="form-control" id="voucher_minimum" name="voucher_minimum" placeholder="Voucher Minimum Price" value="{{ $info->voucher_minimum }}" readonly>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="colour_palette">Color Palete</label>
                <input type="text" class="form-control" id="colour_palette" name="colour_palette" placeholder="Color Palete" value="{{ $info->colour_palette }}" disabled>
            </div>
            <div class="form-group col-md-6">
                <label for="duration_range" class="text-white">Color Canvas</label>
                <input class="form-control" type="text" id="colour_canvas">
            </div>
        </div>
        <div class="form-group">
            <label for="transaction_type">Transaction Type</label>
            <select name="transaction_type" class="form-control" id="transaction_type" disabled>
                <option value="0">All Transaction</option>
                <option value="1">Physical Transaction</option>
                <option value="2">Digital Transaction</option>
                <option value="3">Beli Bareng Transaction</option>
            </select>
        </div>
        <div class="form-group">
            <label for="promo_type">Promo Type</label>
            <select name="promo_type" class="form-control" id="promo_type" disabled>
                <option value="2">Free For All</option>
                <option value="1">Personal Voucher</option>
            </select>
        </div>
        <input type="hidden" name="user_id" id="user_id">
        <div class="form-group @if($info->promo_type == 2) d-none @endif" id="input_user">
            <label for="fullname">Name</label>
            <input type="text" class="form-control" id="fullname" value="{{ $info->fullname }}" placeholder="Name" onkeydown="if (event.keyCode == 13) return false;" readonly>
            <div class="invalid-feedback">
                User Not Found.
            </div>
        </div>
        <div class="form-group">
            <label for="voucher_type">Voucher Type</label>
            <select name="voucher_type" class="form-control" id="voucher_type" disabled>
                <option value="1">Transaction</option>
                <option value="2">Item</option>
                <option value="3">Delivery Discount</option>
            </select>
        </div>
        <input type="hidden" name="product_id" id="product_id">
        <div class="form-group @if($info->voucher_type == 1) d-none @endif" id="input_product">
            <label for="product_name">Product</label>
            <input type="text" class="form-control mb-2 mr-sm-2" id="product_name" value="{{ $info->product_name }}" placeholder="Product" onkeydown="if (event.keyCode == 13) return false;" readonly>
            <div class="invalid-feedback">
                Product Not Found.
            </div>
        </div>
        <div class="form-group">
            <label for="discount_type">Discount Type</label>
            <select name="discount_type" class="form-control" id="discount_type" disabled>
                <option value="1">Fixed Price</option>
                <option value="2">Percentage</option>
                <option value="3">Hybrid</option>
            </select>
        </div>
        <div class="form-group @if($info->discount_type == 2) d-none @endif" id="input_price">
            <label for="voucher_value_price">Discount Price</label>
            <input type="number" class="form-control" id="voucher_value_price" name="voucher_value_price" value="{{ $info->voucher_value_price }}" placeholder="Price" min="0" readonly>
        </div>
        <div class="form-group @if($info->discount_type == 1) d-none @endif" id="input_percentage">
            <label for="voucher_value_percentage">Discount Percentage</label>
            <div class="input-group">
                <input type="number" class="form-control" id="voucher_value_percentage" name="voucher_value_percentage" value="{{ $info->voucher_value_percentage }}" placeholder="Percentage" min="0" readonly>
                <div class="input-group-append">
                    <span class="input-group-text">%</span>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label for="combined_voucher">Combined Voucher</label>
            <select name="combined_voucher" class="form-control" id="combined_voucher" disabled>
                <option value="0">No</option>
                <option value="1">Yes</option>
            </select>
        </div>
        <div class="@if($info->combined_voucher == 0) d-none @endif" id="combined_voucher_field">
            <div class="form-group">
                <label for="voucher_type_2">Voucher Type 2</label>
                <select name="voucher_type_2" class="form-control" id="voucher_type_2" disabled>
                    <option value="1">Transaction</option>
                    <option value="2">Item</option>
                    <option value="3">Delivery Discount</option>
                </select>
            </div>
            <div class="form-group @if($info->voucher_type_2 == 1) d-none @endif" id="input_product_2">
                <label for="product_name_2">Product</label>
                <input type="text" class="form-control product-name mb-2 mr-sm-2" id="product_name_2" value="{{ $info->product_name }}" placeholder="Product" onkeydown="if (event.keyCode == 13) return false;" readonly>
                <div class="invalid-feedback">
                    Product Not Found.
                </div>
            </div>
            <div class="form-group">
                <label for="discount_type_2">Discount Type 2</label>
                <select name="discount_type_2" class="form-control" id="discount_type_2" disabled>
                    <option value="1">Fixed Price</option>
                    <option value="2">Percentage</option>
                    <option value="3">Hybrid</option>
                </select>
            </div>
            <div class="form-group @if($info->discount_type_2 == 2) d-none @endif" id="input_price_2">
                <label for="voucher_value_price_2">Discount Price</label>
                <input type="number" class="form-control" id="voucher_value_price_2" name="voucher_value_price_2" value="{{ $info->voucher_value_price_2 }}" placeholder="Price" min="0" readonly>
            </div>
            <div class="form-group @if($info->discount_type_2 == 1) d-none @endif" id="input_percentage_2">
                <label for="voucher_value_percentage_2">Discount Percentage</label>
                <div class="input-group">
                    <input type="number" class="form-control" id="voucher_value_percentage_2" name="voucher_value_percentage_2" value="{{ $info->voucher_value_percentage_2 }}" placeholder="Percentage" min="0" readonly>
                    <div class="input-group-append">
                        <span class="input-group-text">%</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label for="voucher_validity_end">Voucher Validity End</label>
            <input type="text" class="form-control" id="voucher_validity_end" name="voucher_validity_end" value="{{ $info->voucher_validity_end }}" placeholder="Voucher Validity End" disabled>
        </div>
        <div class="form-group row">
            <label for="voucher_image" class="col-sm-2 col-form-label">Current Image:</label>
            <div class="col-sm-4">
                <img src="{{ $info->voucher_image }}" class="img-thumbnail">
            </div>
        </div>
        <a href="{{ route('voucher.index') }}" class="btn btn-light">Back</a>
    </form>
</div>

@push('script')
<script>
    $("#voucher_validity_end").datepicker({
        dateFormat: 'yy-mm-dd'
    });
    $('#colour_canvas').css('background-color', "{{ $info->colour_palette ?? 'white' }}");
    $("#shown_in_list").val("{{ $info->shown_in_list }}");
    $("#publish").val("{{ $info->publish }}");
    $("#usage_period").val("{{ $info->usage_period }}");
    $("#user_id").val("{{ $info->user_id ?? '' }}");
    $("#product_id").val("{{ $info->product_id ?? '' }}");
    $("#duration_count").val("{{ $info->duration_count ?? '' }}");
    $("#duration_range").val("{{ $info->duration_range ?? 'Days' }}");
    $("#transaction_type").val("{{ $info->transaction_type }}");
    $("#promo_type").val("{{ $info->promo_type }}");
    $("#voucher_type").val("{{ $info->voucher_type }}");
    $("#discount_type").val("{{ $info->discount_type }}");
    $("#combined_voucher").val("{{ $info->combined_voucher }}");
    $("#voucher_type_2").val("{{ $info->voucher_type_2 ?? '1' }}");
    $("#discount_type_2").val("{{ $info->discount_type_2 ?? '1' }}");

    $('#discount_type').on('change', function() {
        var selection = $(this).val();
        switch (selection) {
            case "1":
                $("#input_price").removeClass('d-none');
                $("#input_percentage").addClass('d-none');
                break;
            case "2":
                $("#input_price").addClass('d-none');
                $("#input_percentage").removeClass('d-none');
                break;
            default:
                $("#input_price").removeClass('d-none');
                $("#input_percentage").removeClass('d-none');
        }
    });

    $('#usage_period').on('change', function() {
        var selection = $(this).val();
        switch (selection) {
            case "CUSTOM":
                $("#usage_period_custom").removeClass('d-none');
                break;
            default:
                $("#usage_period_custom").addClass('d-none');
        }
    });

    $('#colour_palette').colorpicker();
    $('#colour_palette').on('changeColor', function(event) {
        $('#colour_canvas').css('background-color', event.color.toString());
    });

    $('#promo_type').on('change', function() {
        var selection = $(this).val();
        switch (selection) {
            case "1":
                $("#input_user").removeClass('d-none');
                break;
            default:
                $("#input_user").addClass('d-none');
        }
    });

    $("#fullname").autocomplete({
        source: function(request, response) {
            $(".invalid-feedback").hide();
            var param = {
                '_token': "{{ csrf_token() }}",
                'search_query': request.term
            }
            $.post("{{ url(config('env.APP_URL')) }}/user/search", param, function(data) {
                if (data.code == 200) {
                    response($.map(data.data, function(item) {
                        return {
                            code: item.user_id,
                            value: item.fullname + " (" + item.email + ")"
                        };
                    }));
                } else {
                    $(".invalid-feedback").show();
                }
            }, "json");
        },
        select: function(event, ui) {
            $("#user_id").val(ui.item.code);
        }
    });

    $('#voucher_type').on('change', function() {
        var selection = $(this).val();
        switch (selection) {
            case "2":
                $("#input_product").removeClass('d-none');
                break;
            default:
                $("#input_product").addClass('d-none');
        }
    });
</script>
@endpush