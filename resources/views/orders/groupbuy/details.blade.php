@extends('layouts.app')

@section('title', 'Beli Bareng Details')

@section('content')
@include('layouts.alert')
<div class="page-header">
    <h3 class="page-title"> Beli Bareng Details {{ $info->fullname }} </h3>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Transaction</a></li>
            <li class="breadcrumb-item"><a href="{{ route('order.groupbuy') }}">Beli Bareng</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $info->fullname }}</li>
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
                                <a class="nav-link" id="pills-order-tab" data-toggle="pill" href="#pills-order" role="tab" aria-controls="pills-order" aria-selected="true">Order</a>
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-auto">
                        @if($info->verify == 2)
                        <h2><span class="badge badge-success">Verified</span></h2>
                        @elseif($info->verify == 1)
                        <button type="button" class="btn btn-sm btn-block btn-gradient-success btn-icon-text" data-toggle="modal" data-target="#verifyGroupBuyModal" data-id="{{ $info->cg_id }}"><i class="mdi mdi-file-check btn-icon-prepend"></i> Verify</button>
                        @endif
                    </div>
                </div>
                <div class="tab-content" id="pills-tabContent">
                    @include('orders.groupbuy.tab-info')
                    @include('orders.groupbuy.tab-order')
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="verifyGroupBuyModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Verify</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <form class="form-horizontal" method="POST" id="verifyGroupBuyForm">
                    @csrf
                    <input type="hidden" name="cg_id" id="cg_id">
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary" form="verifyGroupBuyForm">Yes</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
<script>
    $('#verifyGroupBuyModal').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget)
        var id = button.data('id')
        var modal = $(this)
        modal.find('.modal-title').text('Verify This Beli Bareng?')
        modal.find('.modal-header #cg_id').val(id)
        $('#verifyGroupBuyForm').attr('action', '{{ url("order/verify/groupbuy") }}');
    });
</script>
@endpush