@extends('layouts.app')

@section('title', 'Order Request Pickup Bulk')

@section('content')
@include('layouts.alert')
<div class="page-header">
    <h3 class="page-title"> Request Pickup Bulk </h3>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('order.delivery') }}">Transaction</a></li>
            <li class="breadcrumb-item"><a href="{{ route('order.delivery') }}">Delivery</a></li>
            <li class="breadcrumb-item active" aria-current="page">Request Pickup Bulk</li>
        </ol>
    </nav>
</div>
<form method="POST" action="{{ url('order/pickup/bulk') }}" class="forms-sample">
    @foreach($order_id as $value)
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">{{ $value }}</h4>
                    @csrf
                    <input type="hidden" name="request[{{ $loop->index }}][order_id]" value="{{ $value }}">
                    <div class="form-group">
                        <label for="parcel_category[]">Parcel Category</label>
                        <select name="request[{{ $loop->index }}][parcel_category]" class="form-control" id="parcel_category[]">
                            <option>Normal</option>
                            <option>Organic</option>
                            <option>FragileElectronic</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="receipt_number[]">Receipt Number</label>
                        <input type="text" class="form-control" name="request[{{ $loop->index }}][receipt_number]" id="receipt_number[]" placeholder="Receipt Number">
                    </div>
                    <div class="form-group">
                        <label for="notes[]">Notes</label>
                        <textarea class="form-control" name="request[{{ $loop->index }}][notes]" id="notes" rows="4" placeholder="Notes"></textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
    <button type="submit" class="btn btn-gradient-primary mr-2">Submit</button>
    <a href="{{ route('order.delivery') }}" class="btn btn-light">Cancel</a>
</form>
@endsection

@push('script')
<script>
</script>
@endpush