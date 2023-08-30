<?php

namespace App\Http\Controllers\Utility;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\User;

use Illuminate\Database\QueryException as QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

use Tymon\JWTAuth\Exceptions as Exceptions;
use Tymon\JWTAuth\Facades\JWTAuth;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7;
use GuzzleHttp\Exception\RequestException;

use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;

use Pusher\Pusher as Pusher;

use DB;
use View;
use Mail;
use FCM;

class UtilityController extends Controller
{
	public function sendReq($method, $endpoint, $params = array())
	{
		$client = new Client();
		try {
			$res = $client->request($method, $endpoint, $params);

			return json_decode($res->getBody());
		} catch (RequestException $e) {
			//echo Psr7\str($e->getRequest());
			// if ($e->hasResponse()) {
			// 	echo Psr7\str($e->getResponse());
			// }
			$obj = new \stdClass;
			$obj->code = 500;
			$obj->message = "Internal Server Error";
			return $obj;
		}
	}

	public function sendReqWithSec($method, $endpoint, $params = array())
	{
		$client = new Client();
		$params['headers'] = array('Authorization' => 'Bearer ' . config('app.TREYA_KEY'));
		try {
			$res = $client->request($method, $endpoint, $params);
			return json_decode($res->getBody());
		} catch (RequestException $e) {
			//echo Psr7\str($e->getRequest());
			// if ($e->hasResponse()) {
			// 	echo Psr7\str($e->getResponse());
			// }
			$obj = new \stdClass;
			$obj->code = 500;
			$obj->message = "Internal Server Error";
			return $obj;
		}
	}

	public function sendMail($array, $name = "No Reply Sana")
	{
		$email = $array['receiver_email'];
		$subject = $array['subject'];
		$template = $array['template'];
		Mail::send($template, $array['data'], function ($message) use ($email, $subject, $name) {
			$message->from('hello@treya.io', $name);
			$message->to($email);
			$message->subject($subject);
		});

		return response()->json(['message' => 'Request completed']);
	}

	public function sendMailWithAttachments($array, $name = "No Reply Sana")
	{
		$email = $array['receiver_email'];
		$subject = $array['subject'];
		$template = $array['template'];
		$attachments = $array['attachments'];
		Mail::send($template, $array['data'], function ($message) use ($email, $subject, $name, $attachments) {
			$message->from('hello@treya.io', $name);
			$message->to($email);
			$message->subject($subject);
			$message->attach($attachments);
		});

		return response()->json(['message' => 'Request completed']);
	}

	public function image_url_correction($url_old)
	{
		$url = str_replace("devopentrip.yuu.ai", "devapi.treya.io", $url_old);
		return $url;
	}

	public function getCity($country_code = 'ID')
	{
		try {
			$query = DB::table('data_city')
				->select('city_code', 'city_name')
				->where('country_code', $country_code)
				->orderBy('city_name', 'asc')
				->get();
			return $query;
		} catch (QueryException $ex) {
			return response()->json(['code' => $ex->getCode(), 'message' => $ex->getMessage()], 500);
		}
	}

	public function compose_title($driver, $coverage, $transmission, $car_name, $duration)
	{
		$driver_ano = ($driver == 1) ? "Dengan supir" : "Tanpa supir";
		$duration_ano = (($duration == 0) ? "6 Jam" : ($duration == 1)) ? "12 Jam" : "24 Jam";
		if ($driver == 1) {
			$coverage_ano = ($coverage == 1) ? "Dalam kota" : "Luar kota";
			$title = $car_name . " " . $driver_ano . " " . $coverage_ano . " " . $duration_ano;
		} else {
			$transmission_ano = ($transmission == 1) ? "MT" : "AT";
			$title = $car_name . " " . $transmission_ano . " " . $driver_ano . " " . $duration_ano;
		}
		return $title;
	}

	public function compose_short_title($car_name, $duration)
	{
		$duration_ano = (($duration == 0) ? "6 Jam" : ($duration == 1)) ? "12 Jam" : "24 Jam";
		$title = $car_name . " " . $duration_ano;
		return $title;
	}

	public function sendNotificationToDevice($deviceToken, $title, $message, $array)
	{
		$optionBuilder = new OptionsBuilder();
		$optionBuilder->setTimeToLive(60 * 20);

		$notificationBuilder = new PayloadNotificationBuilder($title);
		$notificationBuilder->setBody($message)
			->setChannelId('treya')
			->setSound('default');

		$dataBuilder = new PayloadDataBuilder();
		$dataBuilder->addData($array);

		$option = $optionBuilder->build();
		$notification = $notificationBuilder->build();
		$data = $dataBuilder->build();

		$downstreamResponse = FCM::sendTo($deviceToken, $option, $notification, $data);

		$result['success'] = $downstreamResponse->numberSuccess();
		$result['fail'] = $downstreamResponse->numberFailure();

		return $result;
	}

	public function UserNotification($mode, $mode_id, $identifier_name, $users, $room_id = "")
	{
		foreach ($users as $user) {
			$array['mode'] = $mode;
			$array['mode_id'] = $mode_id;
			$array['identifier_name'] = $identifier_name;
			if ($room_id != "") {
				$array['room_id'] = $room_id;
			}
			$array['user_id'] = $user['user_id'];
			$array['status'] = 1;
			$array['created_at'] = date('Y-m-d H:i:s');
			DB::table('message_notification')->insert($array);
			if ($user['channel'] != "") {
				$messageGrid['id_user'] = $user['user_id'];
				$messageGrid['channel'] = $user['channel'];
				$messageGrid['content'] = $identifier_name;
				if ($room_id != "") {
					$messageGrid['room_id'] = $room_id;
				}
				$this->pusherTrigger($user['channel'], $messageGrid);
			}

			$ch = curl_init();
			$search_url = config('app.SANA_API_URL') . 'api/push_it';
			$post = [
				'id_user' => $user['user_id'],
				'mode' => $mode
			];
			curl_setopt($ch, CURLOPT_URL, $search_url);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$server_output = curl_exec($ch);
			curl_close($ch);
		}
	}

	public function UserNotificationSana($mode, $mode_id, $identifier_name, $users, $room_id = "")
	{
		foreach ($users as $user) {
			$array['mode'] = $mode;
			$array['mode_id'] = $mode_id;
			$array['identifier_name'] = $identifier_name;
			if ($room_id != "") {
				$array['room_id'] = $room_id;
			}
			$array['user_id'] = $user['id_user'];
			$array['status'] = 1;
			$array['message_type'] = $user['message_type'];
			$array['created_at'] = date('Y-m-d H:i:s');
			DB::table('message_notification')->insert($array);
			if ($user['channel'] != "") {
				$messageGrid['id_user'] = $user['id_user'];
				$messageGrid['channel'] = $user['channel'];
				$messageGrid['content'] = $identifier_name;
				if ($room_id != "") {
					$messageGrid['room_id'] = $room_id;
				}
				$this->pusherTrigger($user['channel'], $messageGrid);
			}

			$ch = curl_init();
			$search_url = config('app.SANA_API_URL') . 'api/push_it';
			$post = [
				'id_user' => $user['id_user'],
				'mode' => $mode
			];
			curl_setopt($ch, CURLOPT_URL, $search_url);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$server_output = curl_exec($ch);
			curl_close($ch);
		}
	}

	public function pusherTrigger($channel, $array)
	{
		$pusher = new Pusher(config('app.PUSHER_KEY'), config('app.PUSHER_SECRET'), config('app.PUSHER_APP_ID'), array('cluster' => config('app.PUSHER_CLUSTER'), 'encrypted' => true));
		$pusher->trigger($channel, 'event-' . $channel, $array);
		return $array;
	}

	public function notifyUser($id, $type, $title)
	{
		$query = DB::table('opentrip_user_data')
			->select('treya_user_fcm.fcm_token')
			->leftJoin('treya_user_fcm', 'opentrip_user_data.id_user', '=', 'treya_user_fcm.id_user')
			->where('active', 1)
			->where('vendor', 1)
			->get();
		$message = $type . " baru telah ditambahkan";
		foreach ($query as $row) {
			$array['id'] = $id;
			$array['type'] = $type;
			$array['title'] = $title;

			if ($row->fcm_token != NULL || $row->fcm_token != "") {
				$report = $this->sendNotificationToDevice($row->fcm_token, 'Sana Notification', $message, $array);
			}
		}
	}

	function random_color_part()
	{
		return str_pad(dechex(mt_rand(0, 255)), 2, '0', STR_PAD_LEFT);
	}

	function random_color()
	{
		return "#" . $this->random_color_part() . $this->random_color_part() . $this->random_color_part();
	}
}
