<div class="tab-pane fade show active" id="pills-info" role="tabpanel" aria-labelledby="pills-info-tab">
    <form class="forms-sample" enctype="multipart/form-data">
        <div class="form-group row">
            <label for="order_id" class="col-sm-2 col-form-label">Order ID</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="order_id" value="{{ $info->order_id }}" readonly>
            </div>
        </div>
        @if($info->cg_id != NULL)
        <div class="form-group row">
            <label for="order_id" class="col-sm-2 col-form-label">Group ID</label>
            <div class="col-sm-10">
                <a class="btn btn-primary" href="{{ route('order.groupbuy.details', ['cg_id' => $info->cg_id]) }}" role="button">{{ $info->cg_id }}</a>
            </div>
        </div>
        @endif
        <div class="form-group row">
            <label for="fullname" class="col-sm-2 col-form-label">Name</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="fullname" value="{{ $info->fullname }}" readonly>
            </div>
        </div>
        <div class="form-group row">
            <label for="total_amount" class="col-sm-2 col-form-label">Total</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="total_amount" value="{{ number_format($info->total_amount) }}" readonly>
            </div>
        </div>
        <div class="form-group row">
            <label for="payment_type" class="col-sm-2 col-form-label">Payment Type</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="payment_type" value="{{ $info->payment_data->payment_type }}" readonly>
            </div>
        </div>
        <div class="form-group row">
            <label for="payment_method" class="col-sm-2 col-form-label">Payment Method</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="payment_method" value="{{ $info->payment_data->payment_method }}" readonly>
            </div>
        </div>
    </form>
</div>