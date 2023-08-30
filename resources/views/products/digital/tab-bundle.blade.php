<div class="tab-pane fade" id="pills-bundle" role="tabpanel" aria-labelledby="pills-bundle-tab">
    <div class="row">
        <div class="col-lg-12">
            @empty($bundle)
            <form method="POST" action="{{ route('product.bundle.store') }}" class="forms-sample" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="product_id" value="{{ $info->product_id }}">
                <div class="form-group">
                    <label for="voucher_name">Voucher Name</label>
                    <input type="text" class="form-control" name="voucher_name" id="voucher_name" placeholder="Voucher Name" required>
                </div>
                <div class="form-group">
                    <label for="voucher_desc">Description</label>
                    <textarea class="form-control" name="voucher_desc" id="voucher_desc" rows="4" placeholder="Description"></textarea>
                </div>
                <div class="form-group">
                    <label for="voucher_tnc">Terms and Conditions</label>
                    <textarea class="form-control editablediv" name="voucher_tnc" id="voucher_tnc" rows="4" placeholder=""></textarea>
                </div>
                <div class="form-group">
                    <label for="voucher_count">Voucher Count</label>
                    <input type="number" class="form-control" name="voucher_count" id="voucher_count" placeholder="Voucher Count" required>
                </div>
                <div class="form-group">
                    <label for="voucher_minimum">Voucher Minimum</label>
                    <input type="number" class="form-control" name="voucher_minimum" id="voucher_minimum" placeholder="Voucher Minimum" required>
                </div>
                <div class="form-group">
                    <label for="voucher_type">Voucher Type</label>
                    <select name="voucher_type" class="form-control" id="voucher_type">
                        <option value="1">Transaction</option>
                        <option value="2">Item</option>
                        <option value="3">Delivery</option>
                    </select>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="duration_count">Voucher Duration</label>
                        <input type="number" class="form-control" id="duration_count" name="duration_count" placeholder="Duration Count">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="duration_range" class="text-white">Voucher Duration</label>
                        <select id="duration_range" class="form-control" name="duration_range" placeholder="Duration Range">
                            <option value="days">days</option>
                            <option value="weeks">weeks</option>
                            <option value="months">months</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="discount_type">Discount Type</label>
                    <select name="discount_type" class="form-control" id="discount_type">
                        <option value="1">Fixed Price</option>
                        <option value="2">Percentage</option>
                        <option value="3">Hybrid</option>
                    </select>
                </div>
                <div class="form-group" id="input_price">
                    <label for="voucher_value_price">Discount Price</label>
                    <input type="number" class="form-control" id="voucher_value_price" name="voucher_value_price" placeholder="Price" min="0">
                </div>
                <div class="form-group" id="input_percentage">
                    <label for="voucher_value_percentage">Discount Percentage</label>
                    <input type="number" class="form-control" id="voucher_value_percentage" name="voucher_value_percentage" placeholder="Percentage" min="0">
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="colour_palette">Color Palette</label>
                        <input type="text" class="form-control" id="colour_palette" name="colour_palette" placeholder="Color Palette">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="duration_range" class="text-white">Color Canvas</label>
                        <input class="form-control bg-white" type="text" id="colour_canvas" readonly>
                    </div>
                </div>
                <button type="submit" class="btn btn-gradient-primary mr-2">Submit</button>
                <a href="{{ route('product.digital.index') }}" class="btn btn-light">Cancel</a>
            </form>
            @endempty
            @isset($bundle)
            <form method="POST" action="{{ route('product.bundle.update', ['id' => $bundle->digital_id]) }}" class="forms-sample" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="hidden" name="digital_id" value="{{ $bundle->digital_id }}">
                <div class="form-group">
                    <label for="voucher_name">Voucher Name</label>
                    <input type="text" class="form-control" name="voucher_name" id="voucher_name" placeholder="Voucher Name" value="{{ $bundle->voucher_name }}" required>
                </div>
                <div class="form-group">
                    <label for="voucher_desc">Description</label>
                    <textarea class="form-control" name="voucher_desc" id="voucher_desc" rows="4" placeholder="Description">{{ $bundle->voucher_desc }}</textarea>
                </div>
                <div class="form-group">
                    <label for="voucher_tnc">Terms and Conditions</label>
                    <textarea class="form-control editablediv" name="voucher_tnc" id="voucher_tnc" rows="4" placeholder="">{{ $bundle->voucher_tnc }}</textarea>
                </div>
                <div class="form-group">
                    <label for="voucher_count">Voucher Count</label>
                    <input type="number" class="form-control" name="voucher_count" id="voucher_count" placeholder="Voucher Count" value="{{ $bundle->voucher_count }}" required>
                </div>
                <div class="form-group">
                    <label for="voucher_minimum">Voucher Minimum</label>
                    <input type="number" class="form-control" name="voucher_minimum" id="voucher_minimum" placeholder="Voucher Minimum" value="{{ $bundle->voucher_minimum }}" required>
                </div>
                <div class="form-group">
                    <label for="voucher_type">Voucher Type</label>
                    <select name="voucher_type" class="form-control" id="voucher_type">
                        <option value="1">Transaction</option>
                        <option value="2">Item</option>
                        <option value="3">Delivery</option>
                    </select>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="duration_count">Voucher Duration</label>
                        <input type="number" class="form-control" id="duration_count" name="duration_count" placeholder="Duration Count">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="duration_range" class="text-white">Voucher Duration</label>
                        <select id="duration_range" class="form-control" name="duration_range" placeholder="Duration Range">
                            <option value="days">days</option>
                            <option value="weeks">weeks</option>
                            <option value="months">months</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="discount_type">Discount Type</label>
                    <select name="discount_type" class="form-control" id="discount_type">
                        <option value="1">Fixed Price</option>
                        <option value="2">Percentage</option>
                        <option value="3">Hybrid</option>
                    </select>
                </div>
                <div class="form-group @if($bundle->discount_type == 2) d-none @endif" id="input_price">
                    <label for="voucher_value_price">Discount Price</label>
                    <input type="number" class="form-control" id="voucher_value_price" name="voucher_value_price" placeholder="Price" value="{{ $bundle->voucher_value_price }}" min="0">
                </div>
                <div class="form-group @if($bundle->discount_type == 1) d-none @endif" id="input_percentage">
                    <label for="voucher_value_percentage">Discount Percentage</label>
                    <input type="number" class="form-control" id="voucher_value_percentage" name="voucher_value_percentage" placeholder="Percentage" value="{{ $bundle->voucher_value_percentage }}" min="0">
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="colour_palette">Color Palete</label>
                        <input type="text" class="form-control" id="colour_palette" name="colour_palette" placeholder="Color Palete" value="{{ $bundle->colour_palette }}">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="duration_range" class="text-white">Color Canvas</label>
                        <input class="form-control" type="text" id="colour_canvas" readonly>
                    </div>
                </div>
                <button type="submit" class="btn btn-gradient-primary mr-2">Submit</button>
                <a href="{{ route('product.digital.index') }}" class="btn btn-light">Cancel</a>
            </form>
            @endisset
        </div>
    </div>
</div>

@push('script')
<script>
    $('#colour_canvas').css('background-color', "{{ $bundle->colour_palette ?? 'white' }}");
    $("#duration_count").val("{{ $bundle->duration_count ?? '' }}");
    $("#duration_range").val("{{ $bundle->duration_range ?? 'days' }}");
    $("#voucher_type").val("{{ $bundle->voucher_type ?? 1 }}");
    $("#discount_type").val("{{ $bundle->discount_type ?? 3 }}");
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

    $('#colour_palette').colorpicker();
    $('#colour_palette').on('changeColor', function(event) {
        $('#colour_canvas').css('background-color', event.color.toString());
    });
</script>
@endpush