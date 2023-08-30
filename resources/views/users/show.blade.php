@extends('layouts.app')

@section('title', 'User')

@section('content')
@include('layouts.alert')
<div class="page-header">
    <h3 class="page-title"> {{ $info->fullname }} </h3>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('user.index') }}">User</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $info->fullname }}</li>
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
                        <a class="nav-link" id="pills-review-tab" data-toggle="pill" href="#pills-review" role="tab" aria-controls="pills-review" aria-selected="true">Review</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="pills-point-tab" data-toggle="pill" href="#pills-point" role="tab" aria-controls="pills-point" aria-selected="true">Point</a>
                    </li>
                </ul>
                <div class="tab-content" id="pills-tabContent">
                    @include('users.tab-info')
                    @include('users.tab-review')
                    @include('users.tab-point')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
@endpush