<div class="tab-pane fade" id="pills-order" role="tabpanel" aria-labelledby="pills-order-tab">
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Name</th>
                    <th>Updated At</th>
                </tr>
            </thead>
            <tbody>
                @foreach($info->order as $row)
                <tr>
                    <td class="align-baseline">{{ $row->order_id }}</td>
                    <td class="align-baseline">{{ $row->fullname }}</td>
                    <td class="align-baseline">{{ date('d F Y H:i', strtotime($row->updated_at)) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@push('script')
<script>
    
</script>
@endpush