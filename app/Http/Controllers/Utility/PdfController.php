<?php

namespace App\Http\Controllers\Utility;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Traits\CurlTrait;

use PDF, DB;

class PdfController extends Controller
{
	public function __construct()
	{
	  date_default_timezone_set("Asia/Jakarta");
	}

	public function index()
	{
		$params = [
			'booking_id' => [161, 126, 164, 172, 202, 240, 262]
		];
		$options = array('form_params' => $params);
        $res = CurlTrait::sendReq("POST", config('env.API_URL') . "/dash/order/bulk/detail", $options);

        $data = [
            'orders' => $res->data,
        ];
		// dd($data);
		// return view('pdf.receipt', $data);
		$pdf = PDF::loadView('pdf.receipt', $data)->setPaper('a4', 'landscape')->setWarnings(false);
		return $pdf->stream();
	}

	public function ticket_pdf($data, $to_tamu)
	{
		$data['total_price'] = number_format($data['total_price'], 0);
		$data['unpaid_fare'] = number_format($data['unpaid_fare'], 0);
		$data['afiliate'] = false;
		$res = $this->getTicketData($data['id_user'], $data['id_schedule']);
		if ($to_tamu != NULL) {
			$data['afiliate'] = true;
			$res2 = $this->getToTamuData($to_tamu);
			$data = array_merge($data, $res2);
			$pdf = PDF::loadView('pdf.ticket-new', $data)->setPaper('a5')->setWarnings(false);
		} else {
			$data = array_merge($data, $res);
			$pdf = PDF::loadView('pdf.ticket', $data)->setPaper('a5', 'landscape')->setWarnings(false);	
		}

		$content = $pdf->output();
		$guest_name = app('App\Http\Controllers\Utility\UtilityController')->slug($data['guest_name']);
		$fileName = 'e-ticket-' . $guest_name . '-' . $data['qr_code_id'] . '.pdf';
		$destinationPath = 'sana/ticket/pdf';
		$filePath = $destinationPath . '/' . $fileName;

		Storage::disk('s3')->put($filePath, $content, 'public');

		$url = 'https://s3.' . config('app.AWS_REGION') . '.amazonaws.com/' . config('app.AWS_BUCKET');
		$return = $url . '/' . $filePath;

		return $return;
	}
}
