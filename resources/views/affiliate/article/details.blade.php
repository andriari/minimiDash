@extends('layouts.app')

@section('title', 'Article Details')

@section('content')
<div class="page-header">
    <h3 class="page-title"> Article Details {{ $user->fullname }} </h3>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Affiliate</a></li>
            <li class="breadcrumb-item"><a href="{{ route('affiliate.article.list') }}">Article</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $user->fullname }}</li>
        </ol>
    </nav>
</div>
<div class="row">
    <div class="col-lg-8 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                {!! $info->content_text !!}
            </div>
        </div>
    </div>
    <div class="col-lg-4 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <dl class="p-0">
                    @if($info->content_curated != 0)
                    <dt class="mb-2">Status</dt>
                    <dd class="mb-3">
                        @if($info->content_curated == 1)
                        <span class="badge badge-success">Approved</span>
                        @else
                        <span class="badge badge-danger">Declined</span>
                        @endif
                    </dd>
                    @endif
                    <dt class="mb-2">Tanggal Pembuatan</dt>
                    <dd class="mb-3">{{ date('d F Y', strtotime($info->content_published)) }}</dd>
                    <dt class="mb-2">Nama User</dt>
                    <dd class="mb-3">{{ $user->fullname }}</dd>
                    <dt class="mb-2">Nama Produk</dt>
                    <dd class="mb-3">{{ $product->product_name }}</dd>
                    <dt class="mb-2">Judul Artikel</dt>
                    <dd class="mb-3">{{ $info->content_title }}</dd>
                    @foreach($rating as $row)
                    <dt class="mb-2">{{ $row->rating_name }}</dt>
                    <dd class="mb-3">{{ $row->rating_value }}</dd>
                    @endforeach
                    <dt class="mb-2">Link Video</dt>
                    <dd class="mb-3">{{ $info->content_video_link }}</dd>
                    @if($info->content_curated == 1)
                    <dt class="mb-2">Jumlah Transaksi</dt>
                    <dd class="mb-3">{{ $info->content_transaction_count }}</dd>
                    <dt class="mb-2">Jumlah Transaksi</dt>
                    <dd class="mb-3">{{ $info->content_transaction_value }}</dd>
                    @endif
                    @if($info->content_curated == 0)
                    <dt class="mb-2">Action</dt>
                    <dd class="mb-3">
                        <div class="row">
                            <div class="col pr-0">
                                <button class="btn btn-sm btn-success btn-block" data-toggle="modal" data-target="#approveModal">Terima</button>
                            </div>
                            <div class="col">
                                <button class="btn btn-sm btn-danger btn-block" data-toggle="modal" data-target="#declineModal">Tolak</button>
                            </div>
                        </div>
                    </dd>
                    @endif
                </dl>
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
                <form action="{{ route('affiliate.article.curate') }}" method="POST" id="approveForm">
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
                <form action="{{ route('affiliate.article.curate') }}" method="POST" id="declineForm">
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