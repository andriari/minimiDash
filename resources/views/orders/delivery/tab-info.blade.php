<div class="tab-pane fade show active" id="pills-info" role="tabpanel" aria-labelledby="pills-info-tab">
    <form class="forms-sample" enctype="multipart/form-data">
        <div class="form-group row">
            <label for="order_id" class="col-sm-2 col-form-label">Order ID</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="order_id" value="{{ $info->order_id }}" readonly>
            </div>
        </div>
        <div class="form-group row">
            <label for="fullname" class="col-sm-2 col-form-label">Name</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="fullname" value="{{ $info->fullname }}" readonly>
            </div>
        </div>
        <div class="form-group row">
            <label for="email" class="col-sm-2 col-form-label">Email</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="email" value="{{ $info->email }}" readonly>
            </div>
        </div>
        <div class="form-group row">
            <label for="phone" class="col-sm-2 col-form-label">Phone</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="phone" value="{{ $info->phone }}" readonly>
            </div>
        </div>
        <div class="form-group row">
            <label for="address_detail" class="col-sm-2 col-form-label">Address</label>
            <div class="col-sm-10">
                <textarea class="form-control" id="address_detail" readonly>{{ $info->address_detail }}</textarea>
            </div>
        </div>
        <div class="form-group row">
            <label for="address_subdistrict_name" class="col-sm-2 col-form-label">Kecamatan</label>
            <div class="col-sm-10">
                <textarea class="form-control" id="address_subdistrict_name" readonly>{{ $info->address_subdistrict_name }}</textarea>
            </div>
        </div>
        <div class="form-group row">
            <label for="address_city_name" class="col-sm-2 col-form-label">Kabupaten / Kota</label>
            <div class="col-sm-10">
                <textarea class="form-control" id="address_city_name" readonly>{{ $info->address_city_name }}</textarea>
            </div>
        </div>
        <div class="form-group row">
            <label for="address_postal_code" class="col-sm-2 col-form-label">Postal Code</label>
            <div class="col-sm-10">
                <textarea class="form-control" id="address_postal_code" readonly>{{ $info->address_postal_code }}</textarea>
            </div>
        </div>
        <div class="form-group row">
            <label for="address_phone" class="col-sm-2 col-form-label">Address Phone</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="address_phone" value="{{ $info->address_phone }}" readonly>
            </div>
        </div>
        <div class="form-group row">
            <label for="total_weight" class="col-sm-2 col-form-label">Total Weight</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="total_weight" value="{{ $info->total_weight }} KG" readonly>
            </div>
        </div>
        <div class="form-group row">
            <label for="verified_at" class="col-sm-2 col-form-label">Date Verified</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="verified_at" value="{{ date('d M Y H:i',strtotime($info->verified_at)) }}" readonly>
            </div>
        </div>
        <div class="form-group row">
            <label for="delivery_vendor" class="col-sm-2 col-form-label">Delivery Vendor</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="delivery_vendor" value="{{ $info->delivery_vendor }}" readonly>
            </div>
        </div>
        <div class="form-group row">
            <label for="delivery_service" class="col-sm-2 col-form-label">Delivery Service</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="delivery_service" value="{{ $info->delivery_service }}" readonly>
            </div>
        </div>
        <div class="form-group row">
            <label for="delivery_receipt_number" class="col-sm-2 col-form-label">Delivery Receipt Number</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="delivery_receipt_number" value="{{ $info->delivery_receipt_number }}" readonly>
            </div>
        </div>
        <div class="form-group row">
            <label for="delivery_amount" class="col-sm-2 col-form-label">Delivery Fee</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="delivery_amount" value="{{ number_format($info->delivery_amount) }}" readonly>
            </div>
        </div>
        <div class="form-group row">
            <label for="delivery_amount" class="col-sm-2 col-form-label">Delivery Fee Discount</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="delivery_amount" value="{{ number_format($info->delivery_discount_amount) }}" readonly>
            </div>
        </div>
        <div class="form-group row">
            <label for="insurance_amount" class="col-sm-2 col-form-label">Insurance</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="insurance_amount" value="{{ number_format($info->insurance_amount) }}" readonly>
            </div>
        </div>
    </form>
</div>