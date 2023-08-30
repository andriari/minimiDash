<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Traits\CurlTrait;

use DB, DataTables, Session;

class PointController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $res = CurlTrait::sendReq("GET", config('env.API_URL') . "/dash/point/status/multiplier");
        $status = ($res->code == 200) ? $res->data->status : 0;
        return view('points.list')->with(['status' => $status]);
    }

    public function getDtRowData()
    {
        $data = DB::table('point_transaction')
            ->select('point_transaction.*', 'fullname')
            ->leftJoin('minimi_user_data', 'point_transaction.user_id', '=', 'minimi_user_data.user_id');
        return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('created_at', '{{ date("d M Y", strtotime($created_at)) }}')
            ->editColumn('pt_trans_type', function ($row) {
                switch ($row->pt_trans_type) {
                    case 2:
                        $cell = "<label class=\"badge badge-danger\">Outgoing</label>";
                        break;
                    default:
                        $cell = "<label class=\"badge badge-success\">Incoming</label>";
                        break;
                }
                return $cell;
            })
            // ->addColumn('action', function ($row) {
            //     $action = "<a href=\"" . route('product.edit', ['product' => $row->product_id]) . "\" class=\"btn btn-sm btn-block btn-gradient-primary btn-icon-text\"><i class=\"mdi mdi-pencil-box-outline btn-icon-prepend\"></i> Edit </a>";
            //     $action .= "<button type=\"button\" class=\"btn btn-sm btn-block btn-gradient-danger btn-icon-text\" data-toggle=\"modal\" data-target=\"#deleteProductModal\" data-id=\"" . $row->product_id . "\" data-title=\"" . $row->product_name . "\"><i class=\"mdi mdi-delete btn-icon-prepend\"></i>Delete</button>";
            //     return $action;
            // })
            ->rawColumns(['pt_trans_type'])
            ->make(true);
    }

    public function pointMultiply($status)
    {
        $status = ($status == 0) ? 1 : 0;
        $res = CurlTrait::sendReq("GET", config('env.API_URL') . "/dash/point/multiplier/" . $status);

        if ($res->code != 200) {
            Session::flash('error', $res->message);
        } else {
            Session::flash('success', $res->message);
        }

        return back();
    }

    public function pointAdjust(Request $request)
    {
        $params = $request->except(['_token', 'adjustment', 'fullname']);
        $adjustment = $request->input('adjustment');
        $params['admin_id'] = Session::get('user.id');
        $options = array('form_params' => $params);
        $res = CurlTrait::sendReq("POST", config('env.API_URL') . "/dash/point/" . $adjustment, $options);

        if ($res->code != 200) {
            Session::flash('error', $res->message);
        } else {
            Session::flash('success', $res->message);
        }

        return back();
    }
}
