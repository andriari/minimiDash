<div class="tab-pane fade" id="pills-order" role="tabpanel" aria-labelledby="pills-order-tab">
    <div class="row mb-3" style="border-bottom: 1px solid #ebedf2;">
        <div class="col-lg">
            <h5>Assign Product</h5>
            <form class="form-inline" method="POST" action="{{ url('groupbuy/merge') }}">
                @csrf
                <input type="hidden" name="cg_id_to" value="{{ $info->cg_id }}">
                <input type="hidden" name="cg_id_from" id="cg_id_from">
                <label class="sr-only" for="merge_group_buy">Beli Bareng</label>
                <div class="input-group mb-2 mr-sm-2">
                    <input type="text" class="form-control mb-2 mr-sm-2" id="merge_group_buy" placeholder="Beli Bareng" onkeydown="if (event.keyCode == 13) return false;">
                    <div class="invalid-feedback">
                        Beli Bareng Not Found.
                    </div>
                </div>
                <button type="submit" class="btn btn-gradient-primary mb-2">Submit</button>
            </form>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <div class="table-responsive">
                <table class="table table-hover" id="orderTable">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Name</th>
                            <th>Total</th>
                            <!-- <th>Payment Type</th> -->
                            <th>Payment Method</th>
                            <th>Payment Status</th>
                            <th>Item</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                    @foreach ($order as $row)
                    <tr>
                        <td>{{ $row->order_id }}</td>
                        <td>{{ $row->first_name }} {{ $row->last_name }}</td>
                        <td>{{ $row->total_amount }}</td>
                        <td>{{ $row->payment_method }}</td>
                        @if($row->paid_status==0||$row->paid_status==3||$row->paid_status==4)
                        <td><b>Unpaid</b></td>
                        @elseif($row->paid_status==1)
                        <td><b>Paid</b></td>
                        @else
                        <td><b>Cancelled</b></td>
                        @endif
                        <td>
                            <ul class="list-group list-group-flush">
                                @foreach ($row->shopping_cart_item as $col)
                                <li class="list-group-item">
                                    <div class="d-flex flex-row">
                                        <div class="d-flex align-items-center">{{ $loop->iteration }}.</div>
                                        <div>
                                            <dl class="row m-0 p-0">
                                                <dt class="col-2">Product</dt>
                                                <dd class="col-10">{{ $col->product_name }}</dd>
                                                <dt class="col-2">Variant</dt>
                                                <dd class="col-10">{{ $col->variant_name }}</dd>
                                                <dt class="col-2">Quantity</dt>
                                                <dd class="col-10">{{ $col->count }}</dd>
                                                <dt class="col-2">Price</dt>
                                                <dd class="col-10">{{ number_format($col->price) }}</dd>
                                            </dl>
                                        </div>
                                    </div>
                                </li>
                                @endforeach
                            </ul>
                        </td>
                    </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>
</div>

@push('script')
<script>
    $("#merge_group_buy").autocomplete({
        source: function(request, response) {
            $(".invalid-feedback").hide();
            var param = {
                '_token': "{{ csrf_token() }}",
                'search_query': request.term
            }
            $.post("{{ url(config('env.APP_URL')) }}/groupbuy/search", param, function(data) {
                // console.log(data)
                if (data.code == 200) {
                    response($.map(data.data, function(item) {
                        return {
                            code: item.cg_id,
                            value: "cg_id: "+item.cg_id+" "+item.fullname+" - "+item.product_name+" - "+item.variant_name+" ("+item.status+")"
                        };
                    }));
                } else {
                    $(".invalid-feedback").show();
                }
            }, "json");
        },
        select: function(event, ui) {
            $("#cg_id_from").val(ui.item.code);
        }
    });
</script>
@endpush