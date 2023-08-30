@extends('layouts.app')

@section('title', 'Affiliate | Credit Status')

@section('content')
@include('layouts.alert')
<div class="page-header">
    <h3 class="page-title"> Credit Status </h3>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Affiliate</a></li>
            <li class="breadcrumb-item active" aria-current="page">Credit Status</li>
        </ol>
    </nav>
</div>
<div class="row justify-content-end mb-3">
    <div class="col-auto">
        <button type="button" class="btn btn-sm btn-gradient-success btn-icon-text" data-toggle="modal" data-target="#adjustCreditModal" data-adjustment="add"><i class="mdi mdi-plus btn-icon-prepend"></i>Add Point</button>
    </div>
    <div class="col-auto">
        <button type="button" class="btn btn-sm btn-gradient-danger btn-icon-text" data-toggle="modal" data-target="#adjustCreditModal" data-adjustment="remove"><i class="mdi mdi-minus btn-icon-prepend"></i>Remove Point</button>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover" id="creditTable">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Name</th>
                                <th>Credit</th>
                                <th>Status</th>
                                <th>Transaction</th>
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
<div class="modal fade" id="adjustCreditModal" tabindex="-1" role="dialog" aria-labelledby="adjustCreditModal" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="adjustCreditModal">Adjust Credit</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="card">
                    <div class="card-body">
                        <form id="adjustCreditForm" method="POST" action="{{ url('affiliate/credit/adjust') }}" class="forms-sample" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="affiliate_id" id="affiliate_id">
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
                                <label for="amount">Credit</label>
                                <input type="number" class="form-control" name="amount" id="amount" placeholder="Credit" required>
                            </div>
                            <div class="form-group">
                                <label for="remarks">Transaction</label>
                                <textarea class="form-control" id="remarks" name="remarks" rows="4" placeholder="Transaction" required></textarea>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" form="adjustCreditForm" class="btn btn-primary">Save</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
<script>
    $(document).ready(function() {
        $('#creditTable').DataTable({
            processing: true,
            serverSide: true,
            searching: true,
            order: [
                [0, 'desc']
            ],
            ajax: "{{ url('affiliate/credit/getDtRowData') }}",
            columns: [{
                    data: 'created_at',
                },
                {
                    data: 'fullname',
                    name: 'minimi_user_data.fullname'
                },
                {
                    data: 'amount',
                },
                {
                    data: 'trans_type',
                },
                {
                    data: 'remarks',
                },
            ]
        });

        $('#adjustCreditModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget)
            var adjustment = button.data('adjustment')
            var modal = $(this)
            modal.find('.modal-title').text(jsUcfirst(adjustment) + ' Credit')
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
                $.post("{{ url(config('env.APP_URL')) }}/affiliate/search", param, function(data) {
                    if (data.code == 200) {
                        response($.map(data.data, function(item) {
                            return {
                                user: item.user_id,
                                affiliate: item.affiliate_id,
                                value: item.fullname + " (" + item.email + ")"
                            };
                        }));
                    } else {
                        $(".invalid-feedback").show();
                    }
                }, "json");
            },
            select: function(event, ui) {
                $("#user_id").val(ui.item.user);
                $("#affiliate_id").val(ui.item.affiliate);
            }
        });
        $("#fullname").autocomplete("option", "appendTo", "#adjustCreditForm");
    });
</script>
@endpush