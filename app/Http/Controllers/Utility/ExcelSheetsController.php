<?php

namespace App\Http\Controllers\Utility;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Database\QueryException as QueryException;
use Illuminate\Http\Request;

use Carbon\Carbon;

use DB, Excel, Input;

class ExcelSheetsController extends Controller
{
	private $from_date;
	private $to_date;

	public function export()
	{
		$params = Input::all();
		if (!Input::has('from_date')) {
			return back();
		}
		$this->from_date = $params['from_date'];
		$this->to_date = $params['to_date'];
		switch ($params['menu']) {
			case "report_location":
				$endpoint = $this->report_location();
				break;
			case "report_tamu":
				$endpoint = $this->report_tamu();
				break;
			case "report_host":
				$endpoint = $this->report_host();
				break;
			case "report_payment":
				$endpoint = $this->report_payment();
				break;
		}
		
		Excel::create($endpoint['file_name'], function ($excel) use ($endpoint) {
			foreach($endpoint['sheets'] as $value){
				$excel->sheet($value['name'], function ($sheet) use ($value) {
					$sheet->fromArray($value['data'], null, 'A1', false, false);
	
					$sheet->prependRow(1, $value['headings']);
					$sheet->row(1, function ($row) {
						// call cell manipulation methods
						$row->setFontWeight('bold');
					});
				});
			}
		})->export('xls');
	}

	private function report_location()
	{
		$start = Carbon::parse($this->from_date);
		$end = Carbon::parse($this->to_date);

		$data = $this->getLocationData($start, $end);
		$headings = array('Tempat');
		foreach ($data['months'] as $m) {
			array_push($headings, $m[2]);
		}
		array_push($headings, 'Total');
		$return['file_name'] = "Report Location " . date('d F Y');
		$sheets = [
			[
				'name' => 'Transaksi',
				'headings' => $headings,
				'data' => json_decode(json_encode($data['transaction']), true)
			],
			[
				'name' => 'Guest',
				'headings' => $headings,
				'data' => json_decode(json_encode($data['guest']), true)
			],
			[
				'name' => 'Price',
				'headings' => $headings,
				'data' => json_decode(json_encode($data['price']), true)
			]
		];
		$return['sheets'] = $sheets;
		return $return;
	}

	public function getLocationData($start, $end)
	{
		$months = app('App\Http\Controllers\Reports\ReportsController')->getMonthListFromDate($start, $end);

		$transaction = [];
		$guest = [];
		$price = [];
		$trip = app('App\Http\Controllers\Reports\ReportsController')->getAllTrip();
		$trip_c = collect($trip);

		$cities = $trip_c->groupBy('city_code')->map(function ($item) {
			return [
				'city_name'     => $item[0]->city_name,
				'city_code'     => $item[0]->city_code,
			];
		})->values();

		$total = 0;
		$total2 = 0;
		$total3 = 0;

		foreach ($cities as $city) {
			$code = $city['city_code'];
			$tripPerCity = $trip_c->filter(function ($item, $key) use ($code) {
				if ($item->city_code == $code) {
					return $item->id_trip;
				}
			})->values();
			$tripPerCity = $tripPerCity->transform(function ($item) {
				return $item->id_trip;
			})->toArray();
			$t = [$city['city_name']];
			$g = [$city['city_name']];
			$p = [$city['city_name']];
			$subtotal = 0;
			$subtotal2 = 0;
			$subtotal3 = 0;
			foreach ($months as $m) {
				$res = app('App\Http\Controllers\Reports\ReportsController')->getMonthlyTransactionTripData($m[0], $m[1], $tripPerCity);
				$res2 = app('App\Http\Controllers\Reports\ReportsController')->getMonthlyGuestTripData($m[0], $m[1], $tripPerCity);
				$res3 = app('App\Http\Controllers\Reports\ReportsController')->getMonthlyPriceTripData($m[0], $m[1], $tripPerCity);
				$subtotal += $res;
				$subtotal2 += $res2;
				$subtotal3 += $res3;
				array_push($t, $res);
				array_push($g, $res2);
				array_push($p, number_format($res3));
			}
			$total += $subtotal;
			$total2 += $subtotal2;
			$total3 += $subtotal3;
			array_push($t, $subtotal);
			array_push($g, $subtotal2);
			array_push($p, number_format($subtotal3));

			array_push($transaction, $t);
			array_push($guest, $g);
			array_push($price, $p);
		}
		$total_t = ['Total'];
		$total_g = ['Total'];
		$total_p = ['Total'];
		foreach ($months as $m) {
			array_push($total_t, '-');
			array_push($total_g, '-');
			array_push($total_p, '-');
		}
		array_push($total_t, $total);
		array_push($total_g, $total2);
		array_push($total_p, number_format($total3));
		array_push($transaction, $total_t);
		array_push($guest, $total_g);
		array_push($price, $total_p);
		$data = [
			'months' => $months,
			'transaction' => $transaction,
			'guest' => $guest,
			'price' => $price
		];

		return $data;
	}

	private function report_tamu()
	{
		$start = Carbon::parse($this->from_date);
		$end = Carbon::parse($this->to_date);

		$data = $this->getTamuData($start, $end);
		$headings = array('Nama');
		foreach ($data['months'] as $m) {
			array_push($headings, $m[2]);
		}
		array_push($headings, 'Total');
		$return['file_name'] = "Report Tamu " . date('d F Y');
		$sheets = [
			[
				'name' => 'Transaksi',
				'headings' => $headings,
				'data' => json_decode(json_encode($data['transaction']), true)
			],
			[
				'name' => 'Guest',
				'headings' => $headings,
				'data' => json_decode(json_encode($data['guest']), true)
			],
			[
				'name' => 'Price',
				'headings' => $headings,
				'data' => json_decode(json_encode($data['price']), true)
			]
		];
		$return['sheets'] = $sheets;
		return $return;
	}

	public function getTamuData($start, $end)
	{
		$months = app('App\Http\Controllers\Reports\ReportsController')->getMonthListFromDate($start, $end);

		$transaction = [];
		$guest = [];
		$price = [];

		$users = app('App\Http\Controllers\Reports\ReportsController')->getAllTamu();

		$total = 0;
		$total2 = 0;
		$total3 = 0;

		foreach ($users as $user) {
			$t = [$user->name];
			$g = [$user->name];
			$p = [$user->name];
			$subtotal = 0;
			$subtotal2 = 0;
			$subtotal3 = 0;
			foreach ($months as $m) {
				$res = app('App\Http\Controllers\Reports\ReportsController')->getMonthlyTransactionTamuData($m[0], $m[1], $user->id_user);
				$res2 = app('App\Http\Controllers\Reports\ReportsController')->getMonthlyGuestTamuData($m[0], $m[1], $user->id_user);
				$res3 = app('App\Http\Controllers\Reports\ReportsController')->getMonthlyPriceTamuData($m[0], $m[1], $user->id_user);
				$subtotal += $res;
				$subtotal2 += $res2;
				$subtotal3 += $res3;
				array_push($t, $res);
				array_push($g, $res2);
				array_push($p, number_format($res3));
			}
			$total += $subtotal;
			$total2 += $subtotal2;
			$total3 += $subtotal3;
			array_push($t, $subtotal);
			array_push($g, $subtotal2);
			array_push($p, number_format($subtotal3));

			array_push($transaction, $t);
			array_push($guest, $g);
			array_push($price, $p);
		}
		$total_t = ['Total'];
		$total_g = ['Total'];
		$total_p = ['Total'];
		foreach ($months as $m) {
			array_push($total_t, '-');
			array_push($total_g, '-');
			array_push($total_p, '-');
		}
		array_push($total_t, $total);
		array_push($total_g, $total2);
		array_push($total_p, number_format($total3));
		array_push($transaction, $total_t);
		array_push($guest, $total_g);
		array_push($price, $total_p);
		$data = [
			'months' => $months,
			'transaction' => $transaction,
			'guest' => $guest,
			'price' => $price
		];

		return $data;
	}

	private function report_host()
	{
		$start = Carbon::parse($this->from_date);
		$end = Carbon::parse($this->to_date);

		$data = $this->getHostData($start, $end);
		$headings = array('Nama');
		foreach ($data['months'] as $m) {
			array_push($headings, $m[2]);
		}
		array_push($headings, 'Total');
		$return['file_name'] = "Report Host " . date('d F Y');
		$sheets = [
			[
				'name' => 'Transaksi',
				'headings' => $headings,
				'data' => json_decode(json_encode($data['transaction']), true)
			],
			[
				'name' => 'Guest',
				'headings' => $headings,
				'data' => json_decode(json_encode($data['guest']), true)
			],
			[
				'name' => 'Price',
				'headings' => $headings,
				'data' => json_decode(json_encode($data['price']), true)
			]
		];
		$return['sheets'] = $sheets;
		return $return;
	}

	public function getHostData($start, $end)
	{
		$months = app('App\Http\Controllers\Reports\ReportsController')->getMonthListFromDate($start, $end);

		$transaction = [];
		$guest = [];
		$price = [];

		$users = app('App\Http\Controllers\Reports\ReportsController')->getAllHost();

		$total = 0;
		$total2 = 0;
		$total3 = 0;

		foreach ($users as $user) {
			$t = [$user->name];
			$g = [$user->name];
			$p = [$user->name];
			$subtotal = 0;
			$subtotal2 = 0;
			$subtotal3 = 0;
			foreach ($months as $m) {
				$res = app('App\Http\Controllers\Reports\ReportsController')->getMonthlyTransactionHostData($m[0], $m[1], $user->host);
				$res2 = app('App\Http\Controllers\Reports\ReportsController')->getMonthlyGuestHostData($m[0], $m[1], $user->host);
				$res3 = app('App\Http\Controllers\Reports\ReportsController')->getMonthlyPriceHostData($m[0], $m[1], $user->host);
				$subtotal += $res;
				$subtotal2 += $res2;
				$subtotal3 += $res3;
				array_push($t, $res);
				array_push($g, $res2);
				array_push($p, number_format($res3));
			}
			$total += $subtotal;
			$total2 += $subtotal2;
			$total3 += $subtotal3;
			array_push($t, $subtotal);
			array_push($g, $subtotal2);
			array_push($p, number_format($subtotal3));

			array_push($transaction, $t);
			array_push($guest, $g);
			array_push($price, $p);
		}
		$total_t = ['Total'];
		$total_g = ['Total'];
		$total_p = ['Total'];
		foreach ($months as $m) {
			array_push($total_t, '-');
			array_push($total_g, '-');
			array_push($total_p, '-');
		}
		array_push($total_t, $total);
		array_push($total_g, $total2);
		array_push($total_p, number_format($total3));
		array_push($transaction, $total_t);
		array_push($guest, $total_g);
		array_push($price, $total_p);
		$data = [
			'months' => $months,
			'transaction' => $transaction,
			'guest' => $guest,
			'price' => $price
		];

		return $data;
	}

	private function report_payment()
	{
		$start = Carbon::parse($this->from_date);
		$end = Carbon::parse($this->to_date);

		$data = app('App\Http\Controllers\Reports\ReportsController')->getPaymentData($start, $end);
		$month_h = array('');
		$month_h2 = array('','');
		$user_h = array('Nama');
		$lokasi_h = array('Tempat', 'Nama Paket Wisata');

		$letters = $this->getLetters();
		$merge1 = [];
		$merge2 = [];
		foreach ($data['months'] as $i=>$m) {
			$add = $i+1;
			$l1 = $i+$add;
			$l2 = $i+$add+1;
			array_push($merge1, $letters[$l1]."1:".$letters[$l2]."1");
			array_push($merge2, $letters[$l1+1]."1:".$letters[$l2+1]."1");
			array_push($month_h, $m[2]);
			array_push($month_h2, $m[2]);
			array_push($user_h, 'Half Payment');
			array_push($user_h, 'Full Payment');
			array_push($lokasi_h, 'Half Payment');
			array_push($lokasi_h, 'Full Payment');
		}
		$endpoint['file_name'] = "Report Payments " . date('d F Y');
		$sheets = [
			[
				'name' => 'Payments',
				'headings' => $month_h,
				'data' => json_decode(json_encode($data['reports']), true)
			],
			[
				'name' => 'Tamu',
				'headings' => $user_h,
				'headings2' => $month_h,
				'merge' => $merge1,
				'data' => json_decode(json_encode($data['tamu']), true)
			],
			[
				'name' => 'Host',
				'headings' => $user_h,
				'headings2' => $month_h,
				'merge' => $merge1,
				'data' => json_decode(json_encode($data['host']), true)
			],
			[
				'name' => 'Lokasi',
				'headings' => $lokasi_h,
				'headings2' => $month_h2,
				'merge' => $merge2,
				'data' => json_decode(json_encode($data['lokasi']), true)
			]
		];
		$endpoint['sheets'] = $sheets;

		Excel::create($endpoint['file_name'], function ($excel) use ($endpoint) {
			
			foreach($endpoint['sheets'] as $value){
				$excel->sheet($value['name'], function ($sheet) use ($value) {
					$sheet->fromArray($value['data'], null, 'A1', false, false);

					$sheet->prependRow(1, $value['headings']);

					if($value['name'] != 'Payments'){
						$sheet->prependRow(1, $value['headings2']);
						foreach($value['merge'] as $col){
							$sheet->mergeCells($col);
						}
					}

					$sheet->row(1, function ($row) {
						// call cell manipulation methods
						$row->setFontWeight('bold');
					});
				});
			}
		})->export('xls');
	}

	public function getLetters(){
		$a = range('A', 'Z');
		$letters = range('A', 'Z');
		foreach ($letters as $one) {
			foreach ($letters as $two) {
				array_push($a, "$one$two");
			}
		}

		return $a;
	}
}
