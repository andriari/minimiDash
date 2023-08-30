@extends('layouts.app')

@section('title', 'Withdrawal Order')

@section('content')
<div class="page-header">
    <h3 class="page-title"> Withdrawal Order {{ $info->fullname }} </h3>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Affiliate</a></li>
            <li class="breadcrumb-item"><a href="{{ route('affiliate.withdraw.list') }}">Withdrawal Process</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $info->fullname }}</li>
        </ol>
    </nav>
</div>
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="d-flex flex-row pb-4 mb-4 border-bottom">
                    <div class="mr-5">
                        <div class="mb-2 font-weight-bold">Username</div>
                        <div style="font-size: 20px;">{{ $info->fullname }}</div>
                    </div>
                    <div>
                        <div class="mb-2 font-weight-bold">Total Saldo</div>
                        <div style="font-size: 20px;">{{ number_format($info->point_count) }}</div>
                    </div>
                </div>
                <dl class="row p-0">
                    <dt class="col-sm-3">Jumlah Penarikan</dt>
                    <dd class="col-sm-9 mb-4">{{ number_format($info->amount) }}</dd>
                    <dt class="col-sm-3">Nama Bank</dt>
                    <dd class="col-sm-9 mb-4">{{ $info->bank_name }}</dd>
                    <dt class="col-sm-3">Nomor Rekening</dt>
                    <dd class="col-sm-9 mb-4">{{ $info->account_number }}</dd>
                    <dt class="col-sm-3">Nama Pemilik</dt>
                    <dd class="col-sm-9 mb-4">{{ $info->fullname }}</dd>
                    <dt class="col-sm-3">Status Rekening</dt>
                    <dd class="col-sm-9 mb-4">
                        @switch($info->verified)
                        @case(1)
                        Verified
                        @break

                        @case(2)
                        Declined
                        @break

                        @default
                        Waiting
                        @endswitch
                    </dd>
                    <dt class="col-sm-3 d-flex align-items-center">Status Transaksi</dt>
                    <dd class="col-sm-9">
                        @if($info->status == 1)
                        <div class="row">
                            <div class="col-md-auto">
                                <button class="btn btn-sm btn-success" data-toggle="modal" data-target="#approveModal">Terima</button>
                            </div>
                            <div class="col-md-auto">
                                <button class="btn btn-sm btn-danger" data-toggle="modal" data-target="#declineModal">Tolak</button>
                            </div>
                        </div>
                        @else
                        @if($info->status == 2)
                        <span class="badge badge-success">Approved</span>
                        @else
                        <span class="badge badge-danger">Declined</span>
                        @endif
                        @endif
                    </dd>
                </dl>
            </div>
        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="approveModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Approve</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="card">
                    <div class="card-body">
                        <form id="approveForm" action="{{ route('affiliate.withdraw.approval') }}" method="POST" class="forms-sample" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="awr_id" value="{{ $awr_id }}">
                            <input type="hidden" name="status" value="2">
                            <div class="form-group">
                                <label for="remarks">Remarks</label>
                                <textarea class="form-control" name="remarks" id="remarks" rows="4" placeholder="Remarks"></textarea>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary" form="approveForm">Yes</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="declineModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Decline</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="card">
                    <div class="card-body">
                        <form id="declineForm" action="{{ route('affiliate.withdraw.approval') }}" method="POST" class="forms-sample" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="awr_id" value="{{ $awr_id }}">
                            <input type="hidden" name="status" value="0">
                            <div class="form-group">
                                <label for="remarks">Remarks</label>
                                <textarea class="form-control" name="remarks" id="remarks" rows="4" placeholder="Remarks"></textarea>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary" form="declineForm">Yes</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
@endpush