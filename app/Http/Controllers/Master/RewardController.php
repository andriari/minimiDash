<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Traits\CurlTrait;

use DB, Session, Redirect, URL, DataTables;

class RewardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('rewards.list');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('rewards.create');
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

        $res = CurlTrait::sendReq("POST", config('env.API_URL') . "/dash/reward/save", $options);
        if ($res->code != 200) {
            Session::flash('error', $res->message);
            return back();
        }
        
        Session::flash('success', $res->message);

        return redirect()->route('reward.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $res = CurlTrait::sendReq("GET", config('env.API_URL') . "/dash/reward/detail/" . $id);

        if ($res->code != 200) {
            Session::flash('error', $res->message);
            return redirect()->route('reward.index');
        }

        $data['info'] = $res->data;

        return view('rewards.edit', $data);
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

        $res = CurlTrait::sendReq("POST", config('env.API_URL') . "/dash/reward/edit", $options);

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
        $res = CurlTrait::sendReq("GET", config('env.API_URL') . "/dash/reward/delete/" . $id);

        if ($res->code != 200) {
            Session::flash('error', $res->message);
        } else {
            Session::flash('success', $res->message);
        }

        return back();
    }

    public function getDtRowData()
    {
        $data = DB::table('affiliate_reward')->where('affiliate_reward.status', 1);
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $action = "<a href=\"" . route('reward.edit', ['reward' => $row->reward_id]) . "\" class=\"btn btn-sm btn-block btn-gradient-primary btn-icon-text\"><i class=\"mdi mdi-pencil-box-outline btn-icon-prepend\"></i> Edit </a>";
                $action .= "<button type=\"button\" class=\"btn btn-sm btn-block btn-gradient-danger btn-icon-text\" data-toggle=\"modal\" data-target=\"#deleteRewardModal\" data-id=\"" . $row->reward_id . "\" data-title=\"" . $row->reward_name . "\"><i class=\"mdi mdi-delete btn-icon-prepend\"></i>Delete</button>";
                return $action;
            })
            ->rawColumns(['action'])
            ->make(true);
    }
}
