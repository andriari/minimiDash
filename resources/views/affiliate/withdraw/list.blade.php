@extends('layouts.app')

@section('title', 'Affiliate | Withdraw')

@section('content')
<div class="page-header">
    <h3 class="page-title"> Withdraw </h3>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Affiliate</a></li>
            <li class="breadcrumb-item active" aria-current="page">Withdraw</li>
        </ol>
    </nav>
</div>
<!-- <div class="row mb-3">
    <div class="col-lg">
        <form class="form-inline" action="{{ url('export/excel') }}" method="POST" id="exportForm">
            @csrf
            <input type="hidden" id="menu" name="menu" value="content_post">
            <button type="submit" class="btn btn-sm btn-gradient-primary btn-icon-text"><i class="mdi mdi-file-export btn-icon-prepend"></i> Export Excel</button>
        </form>
    </div>
</div> -->
@include('layouts.alert')
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover" id="withdrawTable">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Username</th>
                                <th>Jumlah Penarikan</th>
                                <th>Nama Bank</th>
                                <th>Nomor Rekening</th>
                                <th>Nama Pemilik</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
<script>
    $(document).ready(function() {
        $('#withdrawTable').DataTable({
            processing: true,
            serverSide: true,
            searching: true,
            ajax: "{{ url('affiliate/withdraw/getDtRowData') }}",
			order: [
				[0, "desc"]
			],
            columns: [{
                    data: 'created_at'
                },
                {
                    data: 'fullname',
                    name: 'minimi_user_data.fullname'
                },
                {
                    data: 'amount'
                },
                {
                    data: 'bank_name'
                },
                {
                    data: 'account_number'
                },
                {
                    data: 'fullname',
                    name: 'minimi_user_data.fullname'
                },
                {
                    data: 'status'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },
            ]
        });
    });
</script>
@endpush