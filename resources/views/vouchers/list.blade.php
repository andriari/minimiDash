@extends('layouts.app')

@section('title', 'Voucher')

@section('content')
@include('layouts.alert')
<div class="page-header">
    <h3 class="page-title"> Voucher </h3>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Voucher</a></li>
            <li class="breadcrumb-item active" aria-current="page">Voucher</li>
        </ol>
    </nav>
</div>
<div class="row mb-3 justify-content-between">
    <div class="col-md-auto">
        <a href="{{ route('voucher.create') }}" class="btn btn-sm btn-gradient-primary btn-icon-text">
            <i class="mdi mdi-plus btn-icon-prepend"></i> Add </a>
    </div>
    <div class="col-md-auto">
        <form class="form-inline" action="{{ url('export/excel') }}" method="POST" id="exportForm">
            @csrf
            <input type="hidden" id="menu" name="menu" value="voucher_used">
            <button type="submit" class="btn btn-sm btn-gradient-primary btn-icon-text"><i class="mdi mdi-file-export btn-icon-prepend"></i> Export Excel</button>
        </form>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <table class="table table-hover" id="voucherTable">
                    <thead>
                        <tr>
                            <th>Voucher Name</th>
                            <th>Voucher Code</th>
                            <th>Validity End</th>
                            <th>Publish</th>
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
<!-- Modal -->
<div class="modal fade" id="deleteVoucherModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete Voucher</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <form class="form-horizontal" method="POST" id="deleteVoucherForm">
                    @csrf
                    @method('DELETE')
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary" form="deleteVoucherForm">Yes</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="publishVoucherModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Publish Voucher</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <form class="form-horizontal" action="{{ route('voucher.publish') }}" method="POST" id="publishVoucherForm">
                    @csrf
                    <input type="hidden" name="voucher_id" id="voucher_id">
                    <input type="hidden" name="mode" id="mode">
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary" form="publishVoucherForm">Yes</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
<script>
    $(document).ready(function() {
        $('#voucherTable').DataTable({
            processing: true,
            serverSide: true,
            searching: true,
            ajax: "{{ url('voucher/getDtRowData') }}",
            columns: [{
                    data: 'voucher_name'
                },
                {
                    data: 'voucher_code'
                },
                {
                    data: 'voucher_validity_end'
                },
                {
                    data: 'publish'
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

    $('#deleteVoucherModal').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget)
        var id = button.data('id')
        var title = button.data('title')
        var modal = $(this)
        modal.find('.modal-title').text('Delete ' + title + '?')
        $('#deleteVoucherForm').attr('action', '{{ url("voucher") }}/' + id);
    });

    $('#publishVoucherModal').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget)
        var id = button.data('id')
        var title = button.data('title')
        var mode = button.data('mode')
        var modal = $(this)
        var text_title = 'Publish ' + title + '?'
        if (mode == 0)
            text_title = 'Hide ' + title + '?'
        modal.find('.modal-title').text(text_title)
        modal.find('.modal-header #voucher_id').val(id)
        modal.find('.modal-header #mode').val(mode)
    });
</script>
@endpush