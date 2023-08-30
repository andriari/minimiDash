<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Traits\CurlTrait;

use DB, Session, Redirect, URL, DataTables;
use Illuminate\Contracts\Session\Session as SessionSession;

class AgentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('agents.list');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('agents.create');
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
        $multipart = array();

        if ($request->hasFile('agent_logo')) {
            $multipart = [
                [
                    'name' => 'agent_logo',
                    'filename' => $request->file('agent_logo')->getClientOriginalName(),
                    'contents' => fopen($request->file('agent_logo'), "r")
                ]
            ];
        }

        foreach ($params as $key => $value) {
            $data['name'] = $key;
            $data['contents'] = $value;
            array_push($multipart, $data);
        }

        $options['multipart'] = $multipart;

        $res = CurlTrait::sendReq("POST", config('env.API_URL') . "/dash/agent/save", $options);

        if ($res->code != 200) {
            Session::flash('error', $res->message);
        } else {
            Session::flash('success', $res->message);
        }

        return redirect()->route('agent.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $res = CurlTrait::sendReq("GET", config('env.API_URL') . "/dash/agent/detail/" . $id);

        if ($res->code != 200) {
            Session::flash('error', $res->message);
            return redirect()->route('agent.index');
        }
        $data['info'] = $res->data;

        return view('agents.edit', $data);
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

        if ($request->hasFile('agent_logo')) {
            $multipart = [
                [
                    'name' => 'agent_logo',
                    'filename' => $request->file('agent_logo')->getClientOriginalName(),
                    'contents' => fopen($request->file('agent_logo'), "r")
                ]
            ];
        }

        foreach ($params as $key => $value) {
            $data['name'] = $key;
            $data['contents'] = $value;
            array_push($multipart, $data);
        }

        if (!$request->has('main')) {
            $data['name'] = 'main';
            $data['contents'] = 0;
            array_push($multipart, $data);
        }

        $options['multipart'] = $multipart;

        $res = CurlTrait::sendReq("POST", config('env.API_URL') . "/dash/agent/edit", $options);

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
        $res = CurlTrait::sendReq("GET", config('env.API_URL') . "/dash/agent/delete/" . $id);

        if ($res->code != 200) {
            Session::flash('error', $res->message);
        } else {
            Session::flash('success', $res->message);
        }

        return back();
    }

    public function getDtRowData()
    {
        $data = DB::table('warehouse_agent')
            ->where('warehouse_agent.status', 1);
        return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('agent_type', function ($row) {
                switch ($row->agent_type) {
                    case 2:
                        $cell = "<label class=\"badge badge-primary\">Producer</label>";
                        break;
                    default:
                        $cell = "<label class=\"badge badge-success\">Distributor</label>";
                        break;
                }
                return $cell;
            })
            ->editColumn('agent_logo', function ($row) {
                $cell = "";
                if ($row->agent_logo != "")
                    $cell = '<img src="' . $row->agent_logo . '" alt="image" style="width: auto; height: 50px; border-radius: 0;" />';
                return $cell;
            })
            ->addColumn('action', function ($row) {
                $action = "<a href=\"" . route('agent.edit', ['agent' => $row->agent_id]) . "\" class=\"btn btn-sm btn-block btn-gradient-primary btn-icon-text\"><i class=\"mdi mdi-pencil-box-outline btn-icon-prepend\"></i> Edit </a>";
                $action .= "<button type=\"button\" class=\"btn btn-sm btn-block btn-gradient-danger btn-icon-text\" data-toggle=\"modal\" data-target=\"#deleteAgentModal\" data-id=\"" . $row->agent_id . "\" data-title=\"" . $row->agent_name . "\"><i class=\"mdi mdi-delete btn-icon-prepend\"></i> Delete</button>";
                return $action;
            })
            ->rawColumns(['agent_type', 'agent_logo', 'action'])
            ->make(true);
    }
}
