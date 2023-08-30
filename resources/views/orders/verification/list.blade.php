@extends('layouts.app')

@section('title', 'Beli Sendiri')

@section('content')
@include('layouts.alert')
<div class="page-header">
    <h3 class="page-title"> Beli Sendiri </h3>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Transaction</a></li>
            <li class="breadcrumb-item active" aria-current="page">Beli Sendiri</li>
        </ol>
    </nav>
</div>
<div class="row mb-3 justify-content-between">
    <div class="col-md-auto">
        <h3 class="page-title"> Grand Total : {{ number_format($grand_total) }} </h3>
    </div>
    <div class="col-md-auto">
        <form class="form-inline" action="{{ url('export/excel') }}" method="POST" id="exportForm">
            @csrf
            <input type="hidden" id="menu" name="menu" value="order_verification">
            <label class="sr-only" for="from_date">From Date</label>
            <input type="text" class="form-control mb-2 mr-sm-2" id="from_date" name="from_date" placeholder="From Date" autocomplete="off">

            <label class="sr-only" for="to_date">To Date</label>
            <input type="text" class="form-control mb-2 mr-sm-2" id="to_date" name="to_date" placeholder="To Date" autocomplete="off">

            <button type="submit" class="btn btn-sm btn-gradient-primary btn-icon-text"><i class="mdi mdi-file-export btn-icon-prepend"></i> Export Excel</button>
        </form>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover" id="orderTable">
                        <thead>
                            <tr>
                                <th>Transaction Date</th>
                                <th>Verify</th>
                                <th>Order ID</th>
                                <th>User</th>
                                <th>Price</th>
                                <th>Discount</th>
                                <th>Delivery</th>
                                <th>Delivery Discount</th>
                                <th>Insurance</th>
                                <th class="table-success">Total</th>
                                <th>Type</th>
                                <th>Payment Method</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
<script>
    $(document).ready(function() {
        $('#orderTable').DataTable({
            processing: true,
            serverSide: true,
            searching: true,
            order: [
                [0, "desc"]
            ],
            ajax: "{{ url('order/getDtRowVerificationData') }}",
            columns: [{
                    data: 'created_at',
                },
                {
                    data: 'verify',
                    name: 'verify'
                },
                {
                    data: 'order_id',
                },
                {
                    data: 'fullname',
                    name: 'minimi_user_data.fullname',
                },
                {
                    data: 'price_amount',
                },
                {
                    data: 'discount_amount',
                },
                {
                    data: 'delivery_amount',
                },
                {
                    data: 'delivery_discount_amount',
                },
                {
                    data: 'insurance_amount',
                },
                {
                    data: 'total_amount',
                    class: 'table-success'
                },
                {
                    data: 'transaction_type',
                },
                {
                    data: 'payment_method',
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },
            ]
        });
    });
</script>
@endpush