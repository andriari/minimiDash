@extends('layouts.app')

@section('title', 'Transaction')

@section('content')
@include('layouts.alert')
<div class="page-header">
    <h3 class="page-title"> Order Details {{ $info->order_id }} </h3>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('order.verification') }}">Transaction</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $info->order_id }}</li>
        </ol>
    </nav>
</div>
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="row justify-content-between">
                    <div class="col-md-auto">
                        <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <a class="nav-link active" id="pills-info-tab" data-toggle="pill" href="#pills-info" role="tab" aria-controls="pills-info" aria-selected="true">Info</a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="pills-item-tab" data-toggle="pill" href="#pills-item" role="tab" aria-controls="pills-item" aria-selected="true">Item</a>
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-auto">
                        @if($info->admin_verified == 0)
                        <button type="button" class="btn btn-sm btn-block btn-gradient-success btn-icon-text" data-toggle="modal" data-target="#verifyPaymentModal" data-id="{{ $info->order_id }}" data-user_id="{{ $info->user_id }}"><i class="mdi mdi-file-check btn-icon-prepend"></i> Verify</button>
                        @else
                        <h2><span class="badge badge-success">Verified</span></h2>
                        @endif
                    </div>
                </div>
                <div class="tab-content" id="pills-tabContent">
                    @include('orders.verification.tab-info')
                    @include('orders.verification.tab-item')
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="verifyPaymentModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Verify</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <form class="form-horizontal" method="POST" id="verifyPaymentForm">
                    @csrf
                    <input type="hidden" name="order_id" id="order_id">
                    <input type="hidden" name="user_id" id="user_id">
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary" form="verifyPaymentForm">Yes</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
<script>
    $('#verifyPaymentModal').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget)
        var id = button.data('id')
        var user_id = button.data('user_id')
        var modal = $(this)
        modal.find('.modal-title').text('Verify ' + id + '?')
        modal.find('.modal-header #order_id').val(id)
        modal.find('.modal-header #user_id').val(user_id)
        $('#verifyPaymentForm').attr('action', '{{ url("order/verify/payment") }}');
    });
</script>
@endpush