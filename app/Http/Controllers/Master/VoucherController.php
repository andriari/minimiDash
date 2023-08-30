<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Traits\CurlTrait;

use DB, Session, Redirect, URL, DataTables;

class VoucherController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('vouchers.list');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('vouchers.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $params = $request->except(['_token', 'user_id', 'product_id', 'voucher_value_price', 'voucher_value_percentage', 'duration_count', 'duration_range']);
        $voucher_value_price = $request->input('voucher_value_price');
        $voucher_value_percentage = $request->input('voucher_value_percentage');
        $voucher_value_price_2 = $request->input('voucher_value_price_2');
        $voucher_value_percentage_2 = $request->input('voucher_value_percentage_2');
        $duration_count = $request->input('duration_count');
        $duration_range = $request->input('duration_range');

        if ($params['usage_period'] == "CUSTOM") {
            $params['usage_period'] = $duration_count . " " . $duration_range;
        }

        if ($params['discount_type'] == "1") {
            $params['voucher_value'] = $voucher_value_price;
        } elseif ($params['discount_type'] == "2") {
            $params['voucher_value'] = $voucher_value_percentage;
        } else {
            $params['voucher_value'] = $voucher_value_price . ";" . $voucher_value_percentage;
        }

        if ($params['promo_type'] == 1) {
            $params['user_id'] = $request->input('user_id');
        }

        if ($params['voucher_type'] == 2 || $params['voucher_type_2'] == 2) {
            $params['product_id'] = $request->input('product_id');
        }

        if ($params['combined_voucher'] == 1) {
            if ($params['discount_type_2'] == "1") {
                $params['voucher_value_2'] = $voucher_value_price_2;
            } elseif ($params['discount_type_2'] == "2") {
                $params['voucher_value_2'] = $voucher_value_percentage_2;
            } else {
                $params['voucher_value_2'] = $voucher_value_price_2 . ";" . $voucher_value_percentage_2;
            }
        }

        $multipart = [
            [
                'name' => 'image',
                'filename' => $request->file('image')->getClientOriginalName(),
                'contents' => fopen($request->file('image'), "r")
            ]
        ];

        foreach ($params as $key => $value) {
            $data['name'] = $key;
            $data['contents'] = $value;
            array_push($multipart, $data);
        }

        $options['multipart'] = $multipart;

        $res = CurlTrait::sendReq("POST", config('env.API_URL') . "/dash/voucher/save", $options);
        if ($res->code != 200) {
            Session::flash('error', $res->message);
            return back();
        }

        Session::flash('success', $res->message);

        return redirect()->route('voucher.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $res = CurlTrait::sendReq("GET", config('env.API_URL') . "/dash/voucher/detail/" . $id);

        if ($res->code != 200) {
            Session::flash('error', $res->message);
            return redirect()->route('voucher.index');
        }

        $data['info'] = $res->data;
        $data['info']->fullname = "";
        $data['info']->product_name = "";
        $data['info']->voucher_value_price_2 = "";
        $data['info']->voucher_value_percentage_2 = "";

        if ($data['info']->usage_period != "ONCE") {
            $duration = explode(" ", $data['info']->usage_period);
            $data['info']->usage_period = "CUSTOM";
            $data['info']->duration_count = $duration[0];
            $data['info']->duration_range = $duration[1];
        }

        $voucher_value = $data['info']->voucher_value;
        switch ($data['info']->discount_type) {
            case 1:
                $data['info']->voucher_value_price = $voucher_value;
                $data['info']->voucher_value_percentage = "";
                break;
            case 2:
                $data['info']->voucher_value_price = "";
                $data['info']->voucher_value_percentage = $voucher_value;
                break;
            default:
                $pieces = explode(";", $voucher_value);
                $data['info']->voucher_value_price = $pieces[0];
                $data['info']->voucher_value_percentage = $pieces[1];
        }

        if ($data['info']->promo_type == 1) {
            $data['info']->fullname = DB::table('minimi_user_data')->where('user_id', $data['info']->user_id)->value('fullname');
        }

        if ($data['info']->voucher_type == 2 || $data['info']->voucher_type_2 == 2) {
            $data['info']->product_name = DB::table('minimi_product')->where('product_id', $data['info']->product_id)->value('product_name');
        }

        if ($data['info']->combined_voucher == 1) {
            $voucher_value_2 = $data['info']->voucher_value_2;
            switch ($data['info']->discount_type_2) {
                case 1:
                    $data['info']->voucher_value_price_2 = $voucher_value_2;
                    break;
                case 2:
                    $data['info']->voucher_value_percentage_2 = $voucher_value_2;
                    break;
                default:
                    $pieces = explode(";", $voucher_value_2);
                    $data['info']->voucher_value_price_2 = $pieces[0];
                    $data['info']->voucher_value_percentage_2 = $pieces[1];
            }
        }

        return view('vouchers.details', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $res = CurlTrait::sendReq("GET", config('env.API_URL') . "/dash/voucher/detail/" . $id);

        if ($res->code != 200) {
            Session::flash('error', $res->message);
            return redirect()->route('voucher.index');
        }

        $data['info'] = $res->data;
        $data['info']->fullname = "";
        $data['info']->product_name = "";
        $data['info']->voucher_value_price_2 = "";
        $data['info']->voucher_value_percentage_2 = "";

        if ($data['info']->usage_period != "ONCE") {
            $duration = explode(" ", $data['info']->usage_period);
            $data['info']->usage_period = "CUSTOM";
            $data['info']->duration_count = $duration[0];
            $data['info']->duration_range = $duration[1];
        }

        switch ($data['info']->discount_type) {
            case 1:
                $data['info']->voucher_value_price = $data['info']->voucher_value;
                $data['info']->voucher_value_percentage = "";
                break;
            case 2:
                $data['info']->voucher_value_price = "";
                $data['info']->voucher_value_percentage = $data['info']->voucher_value;
                break;
            default:
                $pieces = explode(";", $data['info']->voucher_value);
                $data['info']->voucher_value_price = $pieces[0];
                $data['info']->voucher_value_percentage = $pieces[1];
        }

        if ($data['info']->promo_type == 1) {
            $data['info']->fullname = DB::table('minimi_user_data')->where('user_id', $data['info']->user_id)->value('fullname');
        }

        if ($data['info']->voucher_type == 2 || $data['info']->voucher_type_2 == 2) {
            $data['info']->product_name = DB::table('minimi_product')->where('product_id', $data['info']->product_id)->value('product_name');
        }

        if ($data['info']->combined_voucher == 1) {
            $voucher_value_2 = $data['info']->voucher_value_2;
            switch ($data['info']->discount_type_2) {
                case 1:
                    $data['info']->voucher_value_price_2 = $voucher_value_2;
                    break;
                case 2:
                    $data['info']->voucher_value_percentage_2 = $voucher_value_2;
                    break;
                default:
                    $pieces = explode(";", $voucher_value_2);
                    $data['info']->voucher_value_price_2 = $pieces[0];
                    $data['info']->voucher_value_percentage_2 = $pieces[1];
            }
        }

        return view('vouchers.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $params = $request->except(['_token', '_method', 'user_id', 'product_id', 'voucher_value_price', 'voucher_value_percentage', 'duration_count', 'duration_range']);
        $voucher_value_price = $request->input('voucher_value_price');
        $voucher_value_percentage = $request->input('voucher_value_percentage');
        $voucher_value_price_2 = $request->input('voucher_value_price_2');
        $voucher_value_percentage_2 = $request->input('voucher_value_percentage_2');
        $duration_count = $request->input('duration_count');
        $duration_range = $request->input('duration_range');

        if ($params['usage_period'] == "CUSTOM") {
            $params['usage_period'] = $duration_count . " " . $duration_range;
        }
        if ($params['discount_type'] == "1") {
            $params['voucher_value'] = $voucher_value_price;
        } elseif ($params['discount_type'] == "2") {
            $params['voucher_value'] = $voucher_value_percentage;
        } else {
            $params['voucher_value'] = $voucher_value_price . ";" . $voucher_value_percentage;
        }

        if ($params['promo_type'] == 1) {
            $params['user_id'] = $request->input('user_id');
        }

        if ($params['voucher_type'] == 2 || $params['voucher_type_2'] == 2) {
            $params['product_id'] = $request->input('product_id');
        }

        if ($params['combined_voucher'] == 1) {
            if ($params['discount_type_2'] == "1") {
                $params['voucher_value_2'] = $voucher_value_price_2;
            } elseif ($params['discount_type_2'] == "2") {
                $params['voucher_value_2'] = $voucher_value_percentage_2;
            } else {
                $params['voucher_value_2'] = $voucher_value_price_2 . ";" . $voucher_value_percentage_2;
            }
        }

        $multipart = array();
        if ($request->hasFile('image')) {
            $multipart = [
                [
                    'name' => 'image',
                    'filename' => $request->file('image')->getClientOriginalName(),
                    'contents' => fopen($request->file('image'), "r")
                ]
            ];
        }

        foreach ($params as $key => $value) {
            $data['name'] = $key;
            $data['contents'] = $value;
            array_push($multipart, $data);
        }

        $options['multipart'] = $multipart;

        $res = CurlTrait::sendReq("POST", config('env.API_URL') . "/dash/voucher/edit", $options);

        if ($res->code != 200) {
            Session::flash('error', $res->message);
        } else {
            Session::flash('success', $res->message);
        }
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $res = CurlTrait::sendReq("GET", config('env.API_URL') . "/dash/voucher/delete/" . $id);

        if ($res->code != 200) {
            Session::flash('error', $res->message);
        } else {
            Session::flash('success', $res->message);
        }

        return back();
    }

    public function getDtRowData()
    {
        $data = DB::table('commerce_voucher')
            ->where('commerce_voucher.official_voucher', 1)
            ->where('commerce_voucher.status', 1);
        return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('voucher_validity_end', '{{ date("d F Y", strtotime($voucher_validity_end)) }}')
            ->editColumn('publish', function ($row) {
                $action = "<label class=\"badge badge-success btn-block\">Published</label>";
                if ($row->publish == 0)
                    $action = "<label class=\"badge badge-danger btn-block\">Hidden</label>";
                return $action;
            })
            ->addColumn('action', function ($row) {
                $action = "<a href=\"" . route('voucher.show', ['voucher' => $row->voucher_id]) . "\" class=\"btn btn-sm btn-block btn-gradient-info btn-icon-text\"><i class=\"mdi mdi-information-outline btn-icon-prepend\"></i> Details </a>";
                $action .= "<a href=\"" . route('voucher.edit', ['voucher' => $row->voucher_id]) . "\" class=\"btn btn-sm btn-block btn-gradient-primary btn-icon-text\"><i class=\"mdi mdi-pencil-box-outline btn-icon-prepend\"></i> Edit </a>";
                $action .= "<button type=\"button\" class=\"btn btn-sm btn-block btn-gradient-danger btn-icon-text\" data-toggle=\"modal\" data-target=\"#deleteVoucherModal\" data-id=\"" . $row->voucher_id . "\" data-title=\"" . $row->voucher_name . "\"><i class=\"mdi mdi-delete btn-icon-prepend\"></i>Delete</button>";

                if ($row->publish == 0) {
                    $action .= "<button type=\"button\" class=\"btn btn-sm btn-block btn-gradient-success btn-icon-text\" data-toggle=\"modal\" data-target=\"#publishVoucherModal\" data-id=\"" . $row->voucher_id . "\" data-title=\"" . $row->voucher_name . "\" data-mode=\"1\">Publish</button>";
                } else {
                    $action .= "<button type=\"button\" class=\"btn btn-sm btn-block btn-gradient-warning btn-icon-text\" data-toggle=\"modal\" data-target=\"#publishVoucherModal\" data-id=\"" . $row->voucher_id . "\" data-title=\"" . $row->voucher_name . "\" data-mode=\"0\">Hide</button>";
                }
                return $action;
            })
            ->rawColumns(['publish', 'action'])
            ->make(true);
    }

    public function publish(Request $request)
    {
        $params = $request->except(['_token']);
        $res = CurlTrait::sendReq("GET", config('env.API_URL') . "/dash/voucher/publish/" . $params['voucher_id'] . "/" . $params['mode']);

        if ($res->code != 200) {
            Session::flash('error', $res->message);
        } else {
            Session::flash('success', $res->message);
        }

        return back();
    }

    public function voucherDetails($id)
    {
        $res = CurlTrait::sendReq("GET", config('env.API_URL') . "/dash/voucher/detail/" . $id);

        if ($res->code != 200) {
            Session::flash('error', $res->message);
            return back();
        }

        $data = [
            'info' => $res->data,
        ];

        return view('vouchers.details', $data);
    }
}
