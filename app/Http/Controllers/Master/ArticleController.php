<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Traits\CurlTrait;

use DB, Session, DataTables;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('articles.list');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('articles.create');
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
                'name' => 'thumbnail',
                'filename' => $request->file('thumbnail')->getClientOriginalName(),
                'contents' => fopen($request->file('thumbnail'), "r")
            ],
            [
                'name' => 'product_id',
                'contents' => ""
            ]
        ];

        foreach ($params as $key => $value) {
            $data['name'] = $key;
            $data['contents'] = $value;
            array_push($multipart, $data);
        }

        $options['multipart'] = $multipart;

        $res = CurlTrait::sendReq("POST", config('env.API_URL') . "/dash/article/save", $options);

        if ($res->code != 200) {
            Session::flash('error', $res->message);
        } else {
            Session::flash('success', $res->message);
        }

        return redirect()->route('article.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $res = CurlTrait::sendReq("GET", config('env.API_URL') . "/dash/article/detail/" . $id);

        if ($res->code != 200) {
            Session::flash('error', $res->message);
            return redirect()->route('article.index');
        }
        $data = [
            'content_id' => $id,
            'info' => $res->data->info
        ];

        return view('articles.edit', $data);
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
                'name' => 'product_id',
                'contents' => ""
            ]
        );

        if ($request->hasFile('thumbnail')) {
            $multipart = [
                [
                    'name' => 'thumbnail',
                    'filename' => $request->file('thumbnail')->getClientOriginalName(),
                    'contents' => fopen($request->file('thumbnail'), "r")
                ]
            ];
        }

        foreach ($params as $key => $value) {
            $data['name'] = $key;
            $data['contents'] = $value;
            array_push($multipart, $data);
        }

        $options['multipart'] = $multipart;

        $res = CurlTrait::sendReq("POST", config('env.API_URL') . "/dash/article/edit", $options);

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
        //
    }

    public function getDtRowData()
    {
        $data = DB::table('minimi_content_post')
            ->select('minimi_content_post.*')
            ->where('content_type', 3)
            ->where('status', 1);
        //dd($data);
        return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('content_thumbnail', function ($row) {
                $cell = "";
                if ($row->content_thumbnail != "")
                    $cell = '<img src="' . $row->content_thumbnail . '" alt="thumbnail" style="width: auto; height: 50px; border-radius: 0;" />';
                return $cell;
            })
            ->addColumn('action', function ($row) {
                $action = "<a href=\"" . route('article.edit', ['article' => $row->content_id]) . "\" class=\"btn btn-sm btn-block btn-gradient-primary btn-icon-text\"><i class=\"mdi mdi-pencil-box-outline btn-icon-prepend\"></i> Edit </a>";
                return $action;
            })
            ->rawColumns(['content_thumbnail', 'action'])
            ->make(true);
    }
}
