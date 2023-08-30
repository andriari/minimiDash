@extends('layouts.app')

@section('title', 'Collection')

@section('content')
@include('layouts.alert')
<div class="page-header">
    <h3 class="page-title"> {{ $info->collection_name }} </h3>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('collection.index') }}">Collection</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $info->collection_name }}</li>
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
                        <a class="nav-link" id="pills-item-tab" data-toggle="pill" href="#pills-item" role="tab" aria-controls="pills-item" aria-selected="false">Item</a>
                    </li>
                </ul>
                <div class="tab-content" id="pills-tabContent">
                    @include('collections.tab-info')
                    @include('collections.tab-item')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
@endpush