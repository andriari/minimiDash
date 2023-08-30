<table>
    <tbody>
        <tr>
            <td>Grand Total</td>
            <td>{{ number_format($grand_total) }}</td>
        </tr>
        <tr>
            <td></td>
        </tr>
        <tr>
            <th>Date</th>
            @if($mode==3)
            <th>Group ID</th>
            <th>Beli Bareng Status</th>
            <th>Total Participant</th>
            @endif
            <th>Order No.</th>
            <th>Name</th>
            <th>Email</th>
            <th>Product</th>
            <th>Variant</th>
            <th>Price</th>
            <th>Qty</th>
            <th>Sub Total</th>
            <th>Ongkir</th>
            <th>Diskon</th>
            <th>Asuransi</th>
            <th>Total</th>
        </tr>
        @foreach($orders as $row)
        <tr>
            <td>{{ date('d M Y', strtotime($row->created_at)) }}</td>
            @if($mode==3)
            <td>{{ $row->cg_id }}</td>
            <td>{{ $row->groupbuy_status }}</td>
            <td>{{ $row->total_participant }}</td>
            @endif
            <td>{{ $row->order_id }}</td>
            <td>{{ $row->fullname }}</td>
            <td>{{ $row->email }}</td>
            <td>{{ $row->product_name }}</td>
            <td>{{ $row->variant_name }}</td>
            <td>{{ number_format($row->price_amount) }}</td>
            <td>{{ $row->count }}</td>
            <td>{{ number_format($row->total_amount) }}</td>
            <td>{{ number_format($row->delivery_amount) }}</td>
            <td>{{ number_format($row->discount_amount) }}</td>
            <td>{{ number_format($row->insurance_amount) }}</td>
            <td>{{ number_format($row->total) }}</td>
        </tr>
        @endforeach
    </tbody>
</table>