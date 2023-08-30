<?php

namespace App\Http\Traits;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7;
use GuzzleHttp\Exception\RequestException;

trait CurlTrait
{
    public static function sendReq($method, $endpoint, $params = array())
	{
		$client = new Client();
		$params['headers'] = array('Authorization' => 'Bearer ' . config('env.DASHBOARD_KEY'));
		try {
			$res = $client->request($method, $endpoint, $params);
			return json_decode($res->getBody());
		} catch (RequestException $e) {
			echo Psr7\str($e->getRequest());
			if ($e->hasResponse()) {
				echo Psr7\str($e->getResponse());
			}
			// $obj = new \stdClass;
			// $obj->code = 500;
			// $obj->message = "Internal Server Error";
			// return $obj;
		}
	}

	public static function sendMail($array, $name = "No Reply Sana")
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

	public static function sendMailWithAttachments($array, $name = "No Reply Sana")
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
}
