@extends('layouts.app')

@section('title', 'Order Delivery')

@section('content')
@include('layouts.alert')
<div class="fixed-top">
    <div class="toast fade bg-danger float-right mt-3 mr-3 hide" id="errorToast" role="alert" aria-live="assertive" aria-atomic="true" data-autohide="false">
        <div class="toast-header">
            <strong class="mr-auto">Error</strong>
            <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="toast-body">
            No Data Selected.
        </div>
    </div>
</div>
<div class="page-header">
    <h3 class="page-title"> Order Delivery </h3>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Transaction</a></li>
            <li class="breadcrumb-item active" aria-current="page">Delivery</li>
        </ol>
    </nav>
</div>
<div class="row mb-3 justify-content-between">
    <div class="col-md-auto">
        <form class="form-inline" action="{{ url('export/excel') }}" method="POST" id="exportForm">
            @csrf
            <input type="hidden" id="menu" name="menu" value="order_delivery">
            <button type="submit" class="btn btn-sm btn-gradient-primary btn-icon-text"><i class="mdi mdi-file-export btn-icon-prepend"></i> Export Excel</button>
        </form>
    </div>
    <div class="col-md-auto">
        <button type="button" class="btn btn-sm btn-gradient-success btn-icon-text" id="requestPickupBulkBtn"><i class="mdi mdi-truck btn-icon-prepend"></i> Request Pickup</button>
        <button type="button" class="btn btn-sm btn-gradient-primary btn-icon-text" id="verifyBulkBtn"><i class="mdi mdi-truck-delivery btn-icon-prepend"></i> Verify Delivery</button>
        <button type="button" class="btn btn-sm btn-gradient-warning btn-icon-text" id="downloadReceiptBulkBtn"><i class="mdi mdi-download btn-icon-prepend"></i> Download Receipt</button>
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
                                <th></th>
                                <th>Transaction Date</th>
                                <th>Verify</th>
                                <th>Order ID</th>
                                <th>User</th>
                                <th>Postal Code</th>
                                <th>Insurance</th>
                                <th>Total Weight</th>
                                <th>Date Verified</th>
                                <th>Delivery Vendor</th>
                                <th>Delivery Service</th>
                                <th>Delivery Receipt Number</th>
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
<!-- Modal -->
<div class="modal fade" id="requestPickupModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Request Pickup</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="card">
                    <div class="card-body">
                        <form id="requestPickupForm" action="{{ url('order/pickup') }}" method="POST" class="forms-sample" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="order_id" id="order_id">
                            <input type="hidden" name="user_id" id="user_id">
                            <div class="form-group">
                                <label for="parcel_category">Parcel Category</label>
                                <select name="parcel_category" class="form-control" id="parcel_category">
                                    <option>Normal</option>
                                    <option>Organic</option>
                                    <option>FragileElectronic</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="receipt_number">Receipt Number</label>
                                <input type="text" class="form-control" id="receipt_number" name="receipt_number" placeholder="Receipt Number">
                            </div>
                            <div class="form-group">
                                <label for="notes">Notes</label>
                                <textarea class="form-control" name="notes" id="notes" rows="4" placeholder="Notes"></textarea>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary" form="requestPickupForm">Yes</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="verifyDeliveryModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Verify</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <form class="form-horizontal" action="{{ url('order/verify/delivery') }}" method="POST" id="verifyDeliveryForm">
                    @csrf
                    <input type="hidden" name="order_id" id="order_id">
                    <input type="hidden" name="user_id" id="user_id">
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary" form="verifyDeliveryForm">Yes</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="verifyDeliveryBulkModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Verify Bulk</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <form class="form-horizontal" action="{{ url('order/verify/delivery/bulk') }}" method="POST" id="verifyDeliveryBulkForm">
                    @csrf
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary" form="verifyDeliveryBulkForm">Yes</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="downloadReceiptBulkModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Download Receipt</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <form class="form-horizontal" action="{{ url('order/download/receipt') }}" id="downloadReceiptBulkForm" target="_blank">
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary" form="downloadReceiptBulkForm">Yes</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
            </div>
        </div>
    </div>
</div>
<form class="form-horizontal" action="{{ url('order/pickup/bulk/create') }}" method="POST" id="requestPickupBulkForm">
    @csrf
</form>
@endsection

@push('script')
<script>
    $(document).ready(function() {
        var table = $('#orderTable').DataTable({
            processing: true,
            serverSide: true,
            searching: true,
            order: [
                [1, "desc"]
            ],
            ajax: "{{ url('order/getDtRowDeliveryData') }}",
            columnDefs: [{
                className: 'select-checkbox',
                targets: 0,
                checkboxes: true
            }],
            select: {
                style: 'multi',
                selector: 'td:first-child'
            },
            columns: [{
                    data: 'check',
                    orderable: false,
                    searchable: false
                },
                {
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
                    data: 'address_postal_code',
                    name: 'minimi_user_address.address_postal_code',
                },
                {
                    data: 'insurance_amount',
                },
                {
                    data: 'total_weight',
                    name: 'commerce_shopping_cart.total_weight',
                },
                {
                    data: 'verified_at',
                    name: 'commerce_booking.verified_at',
                },
                {
                    data: 'delivery_vendor',
                },
                {
                    data: 'delivery_service',
                },
                {
                    data: 'delivery_receipt_number',
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },
            ]
        });

        $('#requestPickupModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget)
            var id = button.data('id')
            var user_id = button.data('user_id')
            var modal = $(this)
            modal.find('.modal-title').text('Request Pickup ' + id + '?')
            modal.find('.modal-body #order_id').val(id)
            modal.find('.modal-body #user_id').val(user_id)
        });

        $('#verifyDeliveryModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget)
            var id = button.data('id')
            var user_id = button.data('user_id')
            var modal = $(this)
            modal.find('.modal-title').text('Verify Delivery ' + id + '?')
            modal.find('.modal-header #order_id').val(id)
            modal.find('.modal-header #user_id').val(user_id)
        });

        $('#verifyBulkBtn').on('click', function(event) {

            var rows_count = table.rows({
                selected: true
            }).count();
            if (rows_count <= 0) {
                $('#errorToast').toast('show');
                return;
            }

            $('#verifyDeliveryBulkModal').modal('show');
        });

        $('#verifyDeliveryBulkModal').on('show.bs.modal', function(event) {
            var form = $("#verifyDeliveryBulkForm");
            var modal = $(this)

            var rows_selected = table.rows({
                selected: true
            }).data();

            $.each(rows_selected, function(key, value) {
                // Create a hidden element 
                $(form).append(
                    $('<input>')
                    .attr('type', 'hidden')
                    .attr('name', 'order_id[]')
                    .val(value.order_id)
                );
            });

        });

        $('#verifyDeliveryBulkModal').on('hide.bs.modal', function(event) {
            var form = $("#verifyDeliveryBulkForm");
            form.find('input[name ="order_id[]"]').remove();
        });

        $('#requestPickupBulkBtn').on('click', function(event) {

            var rows_count = table.rows({
                selected: true
            }).count();
            if (rows_count <= 0) {
                $('#errorToast').toast('show');
                return;
            }

            var form = $("#requestPickupBulkForm");
            var modal = $(this)

            var rows_selected = table.rows({
                selected: true
            }).data();

            $.each(rows_selected, function(key, value) {
                // Create a hidden element 
                $(form).append(
                    $('<input>')
                    .attr('type', 'hidden')
                    .attr('name', 'order_id[]')
                    .val(value.order_id)
                );
            });

            form.submit();
        });

        $('#downloadReceiptBulkBtn').on('click', function(event) {

            var rows_count = table.rows({
                selected: true
            }).count();
            if (rows_count <= 0) {
                $('#errorToast').toast('show');
                return;
            }

            var form = $("#downloadReceiptBulkForm");
            var modal = $(this)

            var rows_selected = table.rows({
                selected: true
            }).data();

            $.each(rows_selected, function(key, value) {
                // Create a hidden element 
                $(form).append(
                    $('<input>')
                    .attr('type', 'hidden')
                    .attr('name', 'booking_id[]')
                    .val(value.booking_id)
                );
            });

            form.submit();
            
            form.find('input[name ="booking_id[]"]').remove();
        });
    });
</script>
@endpush