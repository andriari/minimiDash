@extends('layouts.app')

@section('title', 'Point')

@section('content')
@include('layouts.alert')
<div class="page-header">
    <h3 class="page-title"> Point </h3>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Point</a></li>
            <li class="breadcrumb-item active" aria-current="page">Point</li>
        </ol>
    </nav>
</div>
<div class="row mb-3">
    <div class="col">
        <a href="{{ route('point.multiply', ['status'=> $status]) }}" class="btn btn-sm btn-gradient-{{ ($status==0)?'danger':'success' }} btn-icon-text">
            Multiply Point {{ ($status==0)?'Off':'On' }} </a>
    </div>
    <div class="col-auto">
        <button type="button" class="btn btn-sm btn-gradient-success btn-icon-text" data-toggle="modal" data-target="#adjustPointModal" data-adjustment="add"><i class="mdi mdi-plus btn-icon-prepend"></i>Add Point</button>
    </div>
    <div class="col-auto">
        <button type="button" class="btn btn-sm btn-gradient-danger btn-icon-text" data-toggle="modal" data-target="#adjustPointModal" data-adjustment="remove"><i class="mdi mdi-minus btn-icon-prepend"></i>Remove Point</button>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover" id="pointTable">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Point</th>
                                <th>Type</th>
                                <th>Remarks</th>
                                <th>Created At</th>
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
<div class="modal fade" id="adjustPointModal" tabindex="-1" role="dialog" aria-labelledby="adjustPointModal" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="adjustPointModal">Adjust Point</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="card">
                    <div class="card-body">
                        <form id="adjustPointForm" method="POST" action="{{ url('point/adjust') }}" class="forms-sample" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="user_id" id="user_id">
                            <input type="hidden" name="adjustment" id="adjustment">
                            <div class="form-group">
                                <label for="fullname">Name</label>
                                <input type="text" class="form-control" id="fullname" placeholder="Name" onkeydown="if (event.keyCode == 13) return false;">
                                <div class="invalid-feedback">
                                    User Not Found.
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="point_amount">Point</label>
                                <input type="number" class="form-control" name="point_amount" id="point_amount" placeholder="Point" required>
                            </div>
                            <div class="form-group">
                                <label for="remarks">Remarks</label>
                                <textarea class="form-control" id="remarks" name="remarks" rows="4" placeholder="Remarks" required></textarea>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" form="adjustPointForm" class="btn btn-primary">Save</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
<script>
    $(document).ready(function() {
        $('#pointTable').DataTable({
            processing: true,
            serverSide: true,
            searching: true,
            order: [[ 4, 'desc' ]],
            ajax: "{{ url('point/getDtRowData') }}",
            columns: [{
                    data: 'fullname',
                    name: 'minimi_user_data.fullname'
                },
                {
                    data: 'pt_amount',
                },
                {
                    data: 'pt_trans_type',
                },
                {
                    data: 'remarks',
                },
                {
                    data: 'created_at',
                },
            ]
        });

        $('#adjustPointModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget)
            var adjustment = button.data('adjustment')
            var modal = $(this)
            modal.find('.modal-title').text(jsUcfirst(adjustment) + ' Point')
            modal.find('.modal-body #adjustment').val(adjustment)
        });

        function jsUcfirst(string) {
            return string.charAt(0).toUpperCase() + string.slice(1);
        }

        $("#fullname").autocomplete({
            source: function(request, response) {
                $(".invalid-feedback").hide();
                var param = {
                    '_token': "{{ csrf_token() }}",
                    'search_query': request.term
                }
                $.post("{{ url(config('env.APP_URL')) }}/user/search", param, function(data) {
                    if (data.code == 200) {
                        response($.map(data.data, function(item) {
                            return {
                                code: item.user_id,
                                value: item.fullname + " (" + item.email + ") " + item.point_count
                            };
                        }));
                    } else {
                        $(".invalid-feedback").show();
                    }
                }, "json");
            },
            select: function(event, ui) {
                $("#user_id").val(ui.item.code);
            }
        });
        $( "#fullname" ).autocomplete( "option", "appendTo", "#adjustPointForm" );
    });
</script>
@endpush