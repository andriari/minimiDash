<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Traits\CurlTrait;

use DB, Session, DataTables;

class BannerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('banners.list');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $params = $request->except(['_token']);
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

        $res = CurlTrait::sendReq("POST", config('env.API_URL') . "/dash/banner/save", $options);

        if ($res->code != 200) {
            Session::flash('error', $res->message);
        } else {
            Session::flash('success', $res->message);
        }

        return redirect()->route('banner.index');
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
        $params = $request->except(['_token', '_method']);
        $multipart = array(
            [
                'name' => 'image',
                'contents' => ""
            ]
        );

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

        $res = CurlTrait::sendReq("POST", config('env.API_URL') . "/dash/banner/edit", $options);

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
        $res = CurlTrait::sendReq("GET", config('env.API_URL') . "/dash/banner/delete/" . $id);

        if ($res->code != 200) {
            Session::flash('error', $res->message);
        } else {
            Session::flash('success', $res->message);
        }

        return back();
    }

    public function getDtRowData()
    {
        $data = DB::table('minimi_banner')->where('status', 1);
        //dd($data);
        return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('banner_image', function ($row) {
                $cell = "";
                if ($row->banner_image != "")
                    $cell = '<img src="' . $row->banner_image . '" alt="image" style="width: auto; height: 50px; border-radius: 0;" />';
                return $cell;
            })
            ->addColumn('action', function ($row) {
                $action = "<button type=\"button\" class=\"btn btn-sm btn-block btn-gradient-primary btn-icon-text\" data-toggle=\"modal\" data-target=\"#editBannerModal\" data-id=\"" . $row->banner_id . "\" data-title=\"" . $row->banner_title . "\" data-alt=\"" . $row->banner_alt . "\" data-link=\"" . $row->banner_embedded_link . "\"><i class=\"mdi mdi-pencil-box-outline btn-icon-prepend\"></i>Edit</button>";
                $action .= "<button type=\"button\" class=\"btn btn-sm btn-block btn-gradient-danger btn-icon-text\" data-toggle=\"modal\" data-target=\"#deleteBannerModal\" data-id=\"" . $row->banner_id . "\" data-title=\"" . $row->banner_title . "\"><i class=\"mdi mdi-delete btn-icon-prepend\"></i>Delete</button>";
                return $action;
            })
            ->rawColumns(['banner_image', 'action'])
            ->make(true);
    }
}
