@extends('layouts.app')

@section('title', 'Affiliate | Bank verification')

@section('content')
<div class="page-header">
    <h3 class="page-title"> Bank verification </h3>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Affiliate</a></li>
            <li class="breadcrumb-item active" aria-current="page">Bank verification</li>
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
                    <table class="table table-hover" id="bankTable">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Username</th>
                                <th>Nama Bank</th>
                                <th>Nomor Rekening</th>
                                <th>Nama Pemilik</th>
                                <th>Status</th>
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
        $('#bankTable').DataTable({
            processing: true,
            serverSide: true,
            searching: true,
            ajax: "{{ url('affiliate/bank/getDtRowData') }}",
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
                    data: 'bank_name',
                    name: 'data_bank.bank_name'
                },
                {
                    data: 'account_number'
                },
                {
                    data: 'account_name'
                },
                {
                    data: 'verified',
                    orderable: false,
                    searchable: false
                },
            ]
        });
    });
</script>
@endpush