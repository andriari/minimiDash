@extends('layouts.app')

@section('title', 'Voucher')

@section('content')
@include('layouts.alert')
<div class="page-header">
    <h3 class="page-title">Detail {{ $info->voucher_name }}</h3>
    <a href="{{ route('voucher.edit', ['voucher' => $info->voucher_id]) }}" class="btn btn-sm btn-gradient-success btn-icon-text">
            <i class="mdi mdi-pencil-box-outline btn-icon-prepend"></i> Edit </a>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('voucher.index') }}">Voucher</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $info->voucher_name }}</li>
        </ol>
    </nav>
</div>
<div class="row mb-3">
    <div class="col-lg-12">
        <div class="row justify-content-between">
            <div class="col-md-auto">
                <h3 class="page-title"> Jumlah voucher digunakan : {{ number_format($info->usage) }} </h3>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" id="pills-info-tab" data-toggle="pill" href="#pills-info" role="tab" aria-controls="pills-info" aria-selected="true">Info</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="pills-order-tab" data-toggle="pill" href="#pills-order" role="tab" aria-controls="pills-order" aria-selected="true">Order</a>
                    </li>
                </ul>
                <div class="tab-content" id="pills-tabContent">
                    @include('vouchers.tab-info-detail')
                    @include('vouchers.tab-order')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection