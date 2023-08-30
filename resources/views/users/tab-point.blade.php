<div class="tab-pane fade" id="pills-point" role="tabpanel" aria-labelledby="pills-point-tab">
    <div class="row">
        <div class="col">
            <div class="table-responsive">
                <table class="table table-hover" id="pointTable">
                    <thead>
                        <tr>
                            <th>Point</th>
                            <th>Remarks</th>
                            <th>Created</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($point as $row)
                        <tr>
                            <td>{{ $row->pt_amount }}</td>
                            <td>{{ $row->remarks }}</td>
                            <td>{{ date('d M Y', strtotime($row->created_at)) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('script')
<script>
    $(document).ready(function() {
		$('#pointTable').DataTable();
    });
</script>
@endpush