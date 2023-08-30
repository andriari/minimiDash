@extends('layouts.app')

@section('title', 'Product | ' . $info->product_name)

@section('content')
@include('layouts.alert')
<div class="page-header">
    <h3 class="page-title"> {{ $info->product_name }} </h3>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('product.index') }}">Product</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $info->product_name }}</li>
        </ol>
    </nav>
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
                        <a class="nav-link" id="pills-variant-tab" data-toggle="pill" href="#pills-variant" role="tab" aria-controls="pills-variant" aria-selected="false">Variant</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="pills-gallery-tab" data-toggle="pill" href="#pills-gallery" role="tab" aria-controls="pills-gallery" aria-selected="false">Gallery</a>
                    </li>
                </ul>
                <div class="tab-content" id="pills-tabContent">
                    @include('products.tab-info')
                    @include('products.tab-variant')
                    @include('products.tab-gallery')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
@endpush