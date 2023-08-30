<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Traits\CurlTrait;

use Session;

class NotificationController extends Controller
{
    public function getNotification($offset = 0, $limit = 5)
    {
        $params = [
            'offset' => $offset,
            'limit' => $limit
        ];

        $options = array('query' => $params);
        $res = CurlTrait::sendReq("GET", config('env.API_URL') . "/dash/notification/show", $options);

        Session::put('notification', []);
        if ($res->code == 200)
            Session::put('notification', $res->data);

        return response()->json(['code' => 200, 'message' => 'success']);
    }

    public function markAllRead()
    {
        $res = CurlTrait::sendReq("GET", config('env.API_URL') . "/dash/notification/read/all");

        if ($res->code != 200)
            Session::put('error', $res->message);

        return back();
    }

    public function markRead($mode, $id)
    {
        $res = CurlTrait::sendReq("GET", config('env.API_URL') . "/dash/notification/read/" . $mode . "/" . $id);

        if ($res->code != 200)
            Session::put('error', $res->message);

        return response()->json(['code' => 200, 'message' => 'success']);
    }
}
