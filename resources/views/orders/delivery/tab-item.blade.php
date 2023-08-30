<div class="tab-pane fade" id="pills-item" role="tabpanel" aria-labelledby="pills-item-tab">
    <ul class="list-group list-group-flush">
        @foreach ($info->shopping_cart_item as $row)
        <li class="list-group-item">
            <div class="d-flex flex-row">
                <div class="d-flex align-items-center">{{ $loop->iteration }}.</div>
                <div>
                    <dl class="row m-0 p-0">
                        <dt class="col-2">Product</dt>
                        <dd class="col-10">{{ $row->product_name }}</dd>
                        <dt class="col-2">Variant</dt>
                        <dd class="col-10">{{ $row->variant_name }}</dd>
                        <dt class="col-2">Quantity</dt>
                        <dd class="col-10">{{ $row->count }}</dd>
                        <dt class="col-2">Price</dt>
                        <dd class="col-10">{{ number_format($row->price) }}</dd>
                    </dl>
                </div>
            </div>
        </li>
        @endforeach
    </ul>
</div>