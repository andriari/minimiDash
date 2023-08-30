@extends('layouts.app')

@section('title', 'Review')

@section('content')
<div class="page-header">
    <h3 class="page-title"> {{ $user->fullname }} </h3>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('content.post') }}">Content</a></li>
            <li class="breadcrumb-item active" aria-current="page">Review</li>
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
                                <a class="nav-link" id="pills-user-tab" data-toggle="pill" href="#pills-user" role="tab" aria-controls="pills-user" aria-selected="false">User</a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="pills-product-tab" data-toggle="pill" href="#pills-product" role="tab" aria-controls="pills-product" aria-selected="false">Product</a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="pills-image-tab" data-toggle="pill" href="#pills-image" role="tab" aria-controls="pills-image" aria-selected="false">Image</a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="pills-rating-tab" data-toggle="pill" href="#pills-rating" role="tab" aria-controls="pills-rating" aria-selected="false">Rating</a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="pills-trivia-tab" data-toggle="pill" href="#pills-trivia" role="tab" aria-controls="pills-trivia" aria-selected="false">Trivia</a>
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-auto">
                        @if($info->content_curated == 0)
                        <button class="btn btn-sm btn-danger mr-2" data-toggle="modal" data-target="#declineModal">Decline</button>
                        @if($info->content_type != 4)
                        <button class="btn btn-sm btn-success" data-toggle="modal" data-target="#approveModal">Approve</button>
                        @endif
                        @elseif($info->content_curated == 1)
                        <h2><span class="badge badge-success">Approved</span></h2>
                        @else
                        <h2><span class="badge badge-danger">Declined</span></h2>
                        @endif
                    </div>
                </div>
                <div class="tab-content" id="pills-tabContent">
                    @include('contents.tab-info')
                    @include('contents.tab-user')
                    @include('contents.tab-product')
                    @include('contents.tab-image')
                    @include('contents.tab-rating')
                    @include('contents.tab-trivia')
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="approveModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Approve</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-footer">
                <form action="{{ route('content.curate') }}" method="POST" id="approveForm">
                    @csrf
                    <input type="hidden" name="content_id" value="{{ $content_id }}">
                    <input type="hidden" name="status" value="1">
                </form>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                <button type="submit" class="btn btn-primary" form="approveForm">Yes</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="declineModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Decline</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-footer">
                <form action="{{ route('content.curate') }}" method="POST" id="declineForm">
                    @csrf
                    <input type="hidden" name="content_id" value="{{ $content_id }}">
                    <input type="hidden" name="status" value="2">
                </form>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                <button type="submit" class="btn btn-primary" form="declineForm">Yes</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
@endpush