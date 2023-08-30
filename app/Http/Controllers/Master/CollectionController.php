<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Traits\CurlTrait;

use DB, Session, Redirect, URL, DataTables;

class CollectionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('collections.list');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('collections.create');
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
        $options = array('form_params' => $params);
        $res = CurlTrait::sendReq("POST", config('env.API_URL') . "/dash/collection/save", $options);
        if ($res->code != 200) {
            Session::flash('error', $res->message);
            return back();
        }

        return redirect()->route('collection.edit', ['collection' => $res->data->collection_id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $res = CurlTrait::sendReq("GET", config('env.API_URL') . "/dash/collection/detail/" . $id);

        if ($res->code != 200) {
            Session::flash('error', $res->message);
            return redirect()->route('collection.index');
        }
        $data = [
            'info' => $res->data->collection,
            'item' => $res->data->item,
        ];

        return view('collections.edit', $data);
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
        $options = array('form_params' => $params);
        $res = CurlTrait::sendReq("POST", config('env.API_URL') . "/dash/collection/edit", $options);

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
        $res = CurlTrait::sendReq("GET", config('env.API_URL') . "/dash/collection/delete/" . $id);

        if ($res->code != 200) {
            Session::flash('error', $res->message);
        } else {
            Session::flash('success', $res->message);
        }

        return back();
    }

    public function publish($id)
    {
        $res = CurlTrait::sendReq("GET", config('env.API_URL') . "/dash/collection/publish/" . $id);

        if ($res->code != 200) {
            Session::flash('error', $res->message);
        } else {
            Session::flash('success', $res->message);
        }

        return back();
    }

    public function getDtRowData()
    {
        $data = DB::table('minimi_product_collection')
            ->where('minimi_product_collection.status', 1);
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('show', function ($row) {
                $show = "<label class=\"badge btn-gradient-dark btn-block\">Hidden</label>";
                if ($row->show == 1)
                    $show = "<label class=\"badge btn-gradient-light btn-block\">Shown</label>";
                return $show;
            })
            ->addColumn('action', function ($row) {
                $action = "<a href=\"" . route('collection.edit', ['collection' => $row->collection_id]) . "\" class=\"btn btn-sm btn-block btn-gradient-primary btn-icon-text\"><i class=\"mdi mdi-pencil-box-outline btn-icon-prepend\"></i> Edit </a>";
                $action .= "<button type=\"button\" class=\"btn btn-sm btn-block btn-gradient-danger btn-icon-text\" data-toggle=\"modal\" data-target=\"#deleteCollectionModal\" data-id=\"" . $row->collection_id . "\" data-title=\"" . $row->collection_name . "\"><i class=\"mdi mdi-delete btn-icon-prepend\"></i>Delete</button>";
                if($row->show==1){
                    $action .= "<button type=\"button\" class=\"btn btn-sm btn-block btn-gradient-dark btn-icon-text\" data-toggle=\"modal\" data-target=\"#publishCollectionModal\" data-id=\"" . $row->collection_id . "\" data-title=\"" . $row->collection_name . "\" data-act=\"Hide\"><i class=\"mdi btn-icon-prepend\"></i>Hide</button>";
                }else{
                    $action .= "<button type=\"button\" class=\"btn btn-sm btn-block btn-gradient-light btn-icon-text\" data-toggle=\"modal\" data-target=\"#publishCollectionModal\" data-id=\"" . $row->collection_id . "\" data-title=\"" . $row->collection_name . "\" data-act=\"Show\"><i class=\"mdi btn-icon-prepend\"></i>Show</button>";
                }
                return $action;
            })
            ->rawColumns(['show','action'])
            ->make(true);
    }

    public function itemAssignment(Request $request, $mode)
    {
        $params = $request->except(['_token']);
        $options = array('form_params' => $params);
        $res = CurlTrait::sendReq("POST", config('env.API_URL') . "/dash/collection/item/" . $mode, $options);

        if ($res->code != 200) {
            Session::flash('error', $res->message);
        } else {
            Session::flash('success', $res->message);
        }

        return Redirect::to(URL::previous() . "#pills-item");
    }

    public function getDtItemRowData($collection_id)
    {
        $data = DB::table('minimi_product_collection_item')
            ->select('minimi_product.product_id', 'minimi_product.product_name', 'data_category.category_name', 'data_category_sub.subcat_name', 'data_brand.brand_name', 'prod_gallery_picture as pict')
            ->join('minimi_product', 'minimi_product.product_id', '=', 'minimi_product_collection_item.product_id')
            ->join('minimi_product_gallery', 'minimi_product_gallery.product_id', '=', 'minimi_product_collection_item.product_id')
            ->join('data_category', 'data_category.category_id', '=', 'minimi_product.category_id')
            ->join('data_category_sub', 'data_category_sub.subcat_id', '=', 'minimi_product.subcat_id')
            ->join('data_brand', 'data_brand.brand_id', '=', 'minimi_product.brand_id')
            ->where([
                'collection_id' => $collection_id,
                'minimi_product_collection_item.status' => 1,
                'minimi_product.status' => 1,
                'minimi_product_gallery.main_poster' => 1
            ]);
        return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('pict', function ($row) {
                $cell = "";
                if ($row->pict != "")
                    $cell = '<img src="' . $row->pict . '" alt="thumbnail" style="width: auto; height: 50px; border-radius: 0;" />';
                return $cell;
            })
            ->addColumn('action', function ($row) {
                $action = "<a href=\"" . route('product.edit', ['product' => $row->product_id]) . "\" class=\"btn btn-sm btn-block btn-gradient-info btn-icon-text\"> Details </a>";
                $action .= "<button type=\"button\" class=\"btn btn-gradient-danger btn-sm btn-block btn-icon-text\" data-toggle=\"modal\" data-target=\"#deleteProductModal\" data-id=\"" . $row->product_id . "\" data-title=\"" . $row->product_name . "\">Remove</button>";
                return $action;
            })
            ->rawColumns(['pict', 'action'])
            ->make(true);
    }
}
