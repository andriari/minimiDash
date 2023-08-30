<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>PDF</title>

    <!-- Fonts -->
    <!-- <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet"> -->

    <!-- Styles -->
    <link rel="stylesheet" href="{{ URL::asset('public/css/receipt.css') }}">
</head>

<body>
    <table>
        @php $tr = 0; @endphp
        @foreach($orders as $info)
        @if($loop->index == $tr)
        @php $tr += 3 @endphp
        <tr>
            @endif
            <td class="no-border">
                <table class="receipt">
                    <tr>
                        <td colspan="3" class="border-right-none">
                            <div class="logo">
                                <img src="https://minimi-bucket.s3-ap-southeast-1.amazonaws.com/public/logo/minimi-logo-green-small.png" alt="logo" style="height: 35px;" />
                            </div>
                            @if(strtolower($info->delivery_vendor) == 'sicepat')
                            <div class="logo">
                                <img src="https://minimi-bucket.s3-ap-southeast-1.amazonaws.com/public/vendor/sicepat-logo.png" alt="logo" style="height: 35px;" />
                            </div>
                            @endif
                        </td>
                        <td class="border-left-none">
                            <div class="text-right">SHIPPING LABEL</div>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-center" colspan="4">
                            @if(strtolower($info->delivery_vendor) == 'sicepat')
                            <img src="data:image/png;base64,{!! DNS1D::getBarcodePNG($info->delivery_receipt_number, 'C39', 1, 55) !!}" alt="barcode" />
                            @else
                            <img src="data:image/png;base64,{!! DNS1D::getBarcodePNG($info->order_id, 'C39', 1, 55) !!}" alt="barcode" />
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="text-center" colspan="4">
                            INVOICE : {{ $info->order_id }}
                        </td>
                    </tr>
                    <tr>
                        <td class="text-center" colspan="4">
                            NO RESI : {{ $info->delivery_receipt_number }}
                        </td>
                    </tr>
                    <tr>
                        <td class="border-bottom-none w-25 delivery-details">
                            KURIR :
                        </td>
                        <td class="border-bottom-none w-25 delivery-details">
                            ADMINISTRASI :
                        </td>
                        <td class="border-bottom-none w-25 delivery-details">
                            ONGKIR :
                        </td>
                        <td class="border-bottom-none w-25 delivery-details">
                            TANGGAL PEMBELIAN :
                        </td>
                    </tr>
                    <tr>
                        <td class="border-top-none">
                            {{ $info->delivery_vendor }}
                        </td>
                        <td class="border-top-none">
                            Rp 0
                        </td>
                        <td class="border-top-none">
                            Rp {{ $info->delivery_amount }}
                        </td>
                        <td class="border-top-none">
                            {{ date('d/m/y', strtotime($info->created_at)) }}
                        </td>
                    </tr>
                    <tr>
                        <td class="border-bottom-none delivery-details">
                            JENIS LAYANAN :
                        </td>
                        <td class="border-bottom-none delivery-details">
                            ASURANSI :
                        </td>
                        <td class="border-bottom-none delivery-details">
                            BERAT (kg) :
                        </td>
                        <td class="border-bottom-none delivery-details">
                            KETERANGAN
                        </td>
                    </tr>
                    <tr>
                        <td class="border-top-none">
                            {{ $info->delivery_service }}
                        </td>
                        <td class="border-top-none">
                            Rp {{ $info->insurance_amount }}
                        </td>
                        <td class="border-top-none">
                            {{ $info->total_weight }} KG
                        </td>
                        <td class="border-top-none">

                        </td>
                    </tr>
                    <tr>
                        <td rowspan="2">
                            PENERIMA :
                        </td>
                        <td class="border-right-none border-bottom-none" colspan="2">
                            {{ $info->address_pic }}
                        </td>
                        <td class="border-left-none border-bottom-none">
                            {{ $info->address_phone }}
                        </td>
                    </tr>
                    <tr>
                        <td class="border-top-none" colspan="3" style="padding-top: 0;">
                            {{ $info->address_detail }}
                        </td>
                    </tr>
                    <tr>
                        <td class="border-bottom-none" rowspan="2">
                            PENGIRIM :
                        </td>
                        <td class="border-right-none border-bottom-none" colspan="2">
                            minimi.co.id
                        </td>
                        <td class="border-left-none border-bottom-none">
                            081211960455
                        </td>
                    </tr>
                    <tr>
                        <td class="border-top-none border-bottom-none" colspan="3" style="padding-top: 0;">
                            Jl. Suryo No.2, RT.10/RW.3, Rw. Bar., Kec. Kby. Baru, Kota Jakarta Selatan, Daerah Khusus Ibukota Jakarta 12180
                        </td>
                    </tr>
                    <tr>
                        <td colspan="4" class="text-center cut-line">
                            INVOICE : {{ $info->order_id }}
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="width: 50%;">
                            LIST PRODUK : <br>
                            @foreach($info->shopping_cart_item as $item)
                            {{ $item->product_name }} {{ $item->variant_name }}<br>
                            @endforeach
                        </td>
                        <td colspan="2" class="align-baseline">
                            PCS<br>
                            @foreach($info->shopping_cart_item as $item)
                            {{ $item->count }}<br>
                            @endforeach
                        </td>
                    </tr>
                    <tr>
                        <td rowspan="2">
                            PENERIMA :
                        </td>
                        <td class="border-right-none border-bottom-none" colspan="2">
                            {{ $info->address_pic }}
                        </td>
                        <td class="border-left-none border-bottom-none">
                            {{ $info->address_phone }}
                        </td>
                    </tr>
                    <tr>
                        <td class="border-top-none" colspan="3" style="padding-top: 0;">
                            {{ $info->address_detail }}
                        </td>
                    </tr>
                    <tr>
                        <td rowspan="2">
                            PENGIRIM :
                        </td>
                        <td class="border-right-none border-bottom-none" colspan="2">
                            minimi.co.id
                        </td>
                        <td class="border-left-none border-bottom-none">
                            081211960455
                        </td>
                    </tr>
                    <tr>
                        <td class="border-top-none" colspan="3" style="padding-top: 0;">
                            Jl. Suryo No.2, RT.10/RW.3, Rw. Bar., Kec. Kby. Baru, Kota Jakarta Selatan, Daerah Khusus Ibukota Jakarta 12180
                        </td>
                    </tr>
                </table>
            </td>
            @if($loop->index == $tr)
        </tr>
        @endif
        @endforeach
    </table>
</body>

</html>