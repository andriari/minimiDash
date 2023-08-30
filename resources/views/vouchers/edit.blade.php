@extends('layouts.app')

@section('title', 'Voucher')

@section('content')
@include('layouts.alert')
<div class="page-header">
    <h3 class="page-title">Edit {{ $info->voucher_name }}</h3>
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
                </ul>
                <div class="tab-content" id="pills-tabContent">
                    @include('vouchers.tab-info')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
