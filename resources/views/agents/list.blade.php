@extends('layouts.app')

@section('title', 'Agent')

@section('content')
@include('layouts.alert')
<div class="page-header">
    <h3 class="page-title"> Agent </h3>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Agent</a></li>
            <li class="breadcrumb-item active" aria-current="page">Agent</li>
        </ol>
    </nav>
</div>
<div class="row mb-3">
    <div class="col-lg">
        <a href="{{ route('agent.create') }}" class="btn btn-sm btn-gradient-primary btn-icon-text">
            <i class="mdi mdi-plus btn-icon-prepend"></i> Add </a>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover" id="agentTable">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>PIC</th>
                                <th>Phone</th>
                                <th>Type</th>
                                <th>Logo</th>
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
<!-- Modal -->
<div class="modal fade" id="deleteAgentModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete Agent</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <form class="form-horizontal" method="POST" id="deleteAgentForm">
                    @csrf
                    @method('DELETE')
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary" form="deleteAgentForm">Yes</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
<script>
    $(document).ready(function() {
        $('#agentTable').DataTable({
            processing: true,
            serverSide: true,
            searching: true,
            ajax: "{{ url('agent/getDtRowData') }}",
            columns: [{
                    data: 'agent_name'
                },
                {
                    data: 'agent_pic_name'
                },
                {
                    data: 'agent_pic_phone'
                },
                {
                    data: 'agent_type'
                },
                {
                    data: 'agent_logo'
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

    $('#deleteAgentModal').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget)
        var id = button.data('id')
        var title = button.data('title')
        var modal = $(this)
        modal.find('.modal-title').text('Delete ' + title + '?')
        $('#deleteAgentForm').attr('action', '{{ url("agent") }}/' + id);
    });
</script>
@endpush