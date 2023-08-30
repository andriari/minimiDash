@extends('layouts.app')

@section('title', 'Beli Bareng Verification')

@section('content')
@include('layouts.alert')
<div class="page-header">
    <h3 class="page-title">Beli Bareng Verification</h3>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Transaction</a></li>
            <li class="breadcrumb-item active" aria-current="page">Beli Bareng</li>
        </ol>
    </nav>
</div>
<div class="row mb-3">
    <div class="col-lg-12">
        <div class="row">
            <div class="col">
                <h3 class="page-title"> Grand Total Beli Bareng : {{ number_format($grand_total) }} </h3>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-4 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <form class="form-inline">
                    <div class="form-group">
                        <input type="text" class="form-control mb-2 mr-sm-2" id="order_id" placeholder="Search Order ID" onkeydown="if (event.keyCode == 13) return false;">
                        <div class="invalid-feedback">
                            Order ID Not Found.
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-8 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <form class="form-inline" action="{{ url('export/excel') }}" method="POST" id="exportForm">
                    @csrf
                    <input type="hidden" id="menu" name="menu" value="order_groupbuy">
                    <label class="sr-only" for="from_date">From Date</label>
                    <input type="text" class="form-control mb-2 mr-sm-2" id="from_date" name="from_date" placeholder="From Date" autocomplete="off">

                    <label class="sr-only" for="to_date">To Date</label>
                    <input type="text" class="form-control mb-2 mr-sm-2" id="to_date" name="to_date" placeholder="To Date" autocomplete="off">

                    <button type="submit" class="btn btn-sm btn-gradient-primary btn-icon-text">Export Excel</button>
                </form>
            </div>
        </div>
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
                                <th>Last Updated</th>
                                <th>Group ID</th>
                                <th>User</th>
                                <th>Product</th>
                                <th>Total Participant</th>
                                <th>Beli Bareng Status </th>
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
            ajax: "{{ url('order/getDtRowGroupBuyData') }}",
            columns: [{
                    data: 'created_at',
                },
                {
                    data: 'updated_at',
                },
                {
                    data: 'cg_id',
                    name: 'commerce_group_buy.cg_id',
                },
                {
                    data: 'fullname',
                    name: 'minimi_user_data.fullname',
                },
                {
                    data: 'product_name',
                    name: 'minimi_product.product_name',
                },
                {
                    data: 'total_participant',
                },
                {
                    data: 'status',
                    orderable: false,
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },
            ],
            initComplete: function() {
                this.api().columns(6).every(function() {
                    var column = this;
                    var input = document.createElement("input");
                    $(input).appendTo($(column.header()))
                        .on('change', function() {
                            var val = $.fn.dataTable.util.escapeRegex($(this).val());

                            column.search(val ? val : '', true, false).draw();
                        });
                });
            }
        });

        $("#order_id").autocomplete({
            source: function(request, response) {
                $(".invalid-feedback").hide();
                var param = {
                    '_token': "{{ csrf_token() }}",
                    'search_query': request.term
                }
                $.post("{{ url(config('env.APP_URL')) }}/order/search", param, function(data) {
                    if (data.code == 200) {
                        response($.map(data.data, function(item) {
                            return {
                                code: item.booking_id,
                                value: item.order_id
                            };
                        }));
                    } else {
                        $(".invalid-feedback").show();
                    }
                }, "json");
            },
            select: function(event, ui) {
                window.location.href = "{!! url('order/verification') !!}/" + ui.item.code;
            }
        });
    });
</script>
@endpush