<table>
    <tbody>
        <tr>
            <th>No Invoice</th>
            <th>Tanggal Pembelian</th>
            <th>Tanggal Pengiriman</th>
            <th>No Resi</th>
            <th>Kurir</th>
            <th>Jenis Layanan</th>
            <th>Ongkir</th>
            <th>Discount Ongkir</th>
            <th>Asuransi</th>
            <th>Berat</th>
            <th>Nama Penerima</th>
            <th>Kontak Penerima</th>
            <th>Alamat Penerima</th>
            <th>Kota</th>
            <th>Kecamatan</th>
            <th>Product</th>
            <th>Variant</th>
            <th>Qty</th>
        </tr>
        @foreach($orders as $row)
        <tr>
            <td>{{ $row->order_id }}</td>
            <td>{{ date('d M Y', strtotime($row->created_at)) }}</td>
            <td>{{ date('d M Y', strtotime($row->delivery_verified_at)) }}</td>
            <td>{{ $row->delivery_receipt_number }}</td>
            <td>{{ $row->delivery_vendor }}</td>
            <td>{{ $row->delivery_service }}</td>
            <td>{{ number_format($row->delivery_amount) }}</td>
            <td>{{ number_format($row->delivery_discount_amount) }}</td>
            <td>{{ number_format($row->insurance_amount) }}</td>
            <td>{{ $row->total_weight }} KG</td>
            <td>{{ $row->address_pic }}</td>
            <td>{{ $row->address_phone }}</td>
            <td>{{ $row->address_detail }} {{ $row->address_postal_code }}</td>
            <td>{{ $row->address_city_name }}</td>
            <td>{{ $row->address_subdistrict_name }} </td>
            <td>
                <ul>
                    @foreach($row->items as $item)
                    <li>{{ $item->product_name }}</li>
                    @endforeach
                </ul>
            </td>
            <td>
                <ul>
                    @foreach($row->items as $item)
                    <li>{{ $item->variant_name }}</li>
                    @endforeach
                </ul>
            </td>
            <td>
                <ul>
                    @foreach($row->items as $item)
                    <li>{{ $item->count }}</li>
                    @endforeach
                </ul>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>