<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Traits\CurlTrait;

use DB, Session, Redirect, URL, DataTables;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('products.list');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = $this->getSources();
        return view('products.create', $data);
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

        $params['subcat_id'] = array_shift($params['subcategory_alt']);
        $params['subcategory_alt'] = implode(",", $params['subcategory_alt']);

        if ($request->has('category_alt')) {
            $params['category_alt'] = implode(",", $params['category_alt']);
        }

        $options = array('form_params' => $params);
        $res = CurlTrait::sendReq("POST", config('env.API_URL') . "/dash/product/save", $options);
        if ($res->code != 200) {
            Session::flash('error', $res->message);
            return back();
        }

        return redirect()->route('product.edit', ['product' => $res->data->product_id]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = $this->getSources();

        $res = CurlTrait::sendReq("GET", config('env.API_URL') . "/dash/product/detail/" . $id);

        if ($res->code != 200) {
            Session::flash('error', $res->message);
            return redirect()->route('product.index');
        }

        $variant = DB::table('minimi_product_variant')
            ->where('product_id', $id)
            ->where('status', 1)
            ->get();
        $gallery = DB::table('minimi_product_gallery')
            ->where('product_id', $id)
            ->where('status', 1)
            ->get();

        $data['info'] = $res->data;
        $data['variant'] = $variant;
        $data['gallery'] = $gallery;

        if (isset($data['info']->alt)) {
            foreach ($data['info']->alt as $row) {
                $row->subcat = app('App\Http\Controllers\Master\CategoryController')->subShow($row->category_id)->getData()->data;
            }
        } else {
			$obj = new \stdClass;
			$obj->category_id = $data['info']->category_id;
			$obj->subcat_id = [$data['info']->subcat_id];
			$obj->subcat = app('App\Http\Controllers\Master\CategoryController')->subShow($data['info']->category_id)->getData()->data;
            $data['info']->alt = array($obj);
        }

        return view('products.edit', $data);
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

        $params['subcat_id'] = array_shift($params['subcategory_alt']);
        $params['subcategory_alt'] = implode(",", $params['subcategory_alt']);

        if ($request->has('category_alt')) {
            $params['category_alt'] = implode(",", $params['category_alt']);
        }
        
        $options = array('form_params' => $params);
        $res = CurlTrait::sendReq("POST", config('env.API_URL') . "/dash/product/edit", $options);

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
        $res = CurlTrait::sendReq("GET", config('env.API_URL') . "/dash/product/delete/" . $id);

        if ($res->code != 200) {
            Session::flash('error', $res->message);
        } else {
            Session::flash('success', $res->message);
        }

        return back();
    }

    public function getDtRowData()
    {
        $data = DB::table('minimi_product')
            ->select('minimi_product.*', DB::raw('SUM(minimi_product_variant.stock_count) as total_stock'), 'category_name', 'value')
            ->leftJoin('data_category', 'minimi_product.category_id', '=', 'data_category.category_id')
            ->leftJoin('minimi_content_rating_tab', 'minimi_product.product_id', '=', 'minimi_content_rating_tab.product_id')
            ->leftJoin('minimi_product_variant', 'minimi_product.product_id', '=', 'minimi_product_variant.product_id')
            ->where('minimi_product.product_type', 1)
            ->where('tag', 'review_count')
            ->where('minimi_product.status', 1)
            ->where('minimi_product_variant.status', 1)
            ->groupBy('minimi_product.product_id');
        return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('last_date', '{{ ($last_date != NULL) ? date("d M Y", strtotime($last_date)) : "" }}')
            ->addColumn('action', function ($row) {
                $action = "<a href=\"" . route('product.edit', ['product' => $row->product_id]) . "\" class=\"btn btn-sm btn-block btn-gradient-primary btn-icon-text\"><i class=\"mdi mdi-pencil-box-outline btn-icon-prepend\"></i> Edit </a>";
                $action .= "<button type=\"button\" class=\"btn btn-sm btn-block btn-gradient-danger btn-icon-text\" data-toggle=\"modal\" data-target=\"#deleteProductModal\" data-id=\"" . $row->product_id . "\" data-title=\"" . $row->product_name . "\"><i class=\"mdi mdi-delete btn-icon-prepend\"></i>Delete</button>";
                return $action;
            })
            // ->filterColumn('total_stock', function ($query, $keyword) {
            //     $query->raw('SUM(minimi_product_variant.stock_count)', $keyword);
            // })
            // ->rawColumns(['total_stock', 'action'])
            ->rawColumns(['action'])
            ->make(true);
    }

    public function getSources()
    {
        $categories = DB::table('data_category')->where('status', 1)->orderBy('category_name', 'asc')->pluck('category_name', 'category_id');
        $brands = DB::table('data_brand')->where('status', 1)->orderBy('brand_name', 'asc')->pluck('brand_name', 'brand_id');

        return [
            'categories' => $categories,
            'brands' => $brands,
        ];
    }

    public function variantStore(Request $request)
    {
        $params = $request->except(['_token']);
        $options = array('form_params' => $params);
        $res = CurlTrait::sendReq("POST", config('env.API_URL') . "/dash/product/variant/save", $options);

        if ($res->code != 200) {
            Session::flash('error', $res->message);
        } else {
            Session::flash('success', $res->message);
        }

        return Redirect::to(URL::previous() . "#pills-variant");
    }

    public function variantUpdate(Request $request, $id)
    {
        $params = $request->except(['_token', '_method']);
        $options = array('form_params' => $params);
        $res = CurlTrait::sendReq("POST", config('env.API_URL') . "/dash/product/variant/edit", $options);

        if ($res->code != 200) {
            Session::flash('error', $res->message);
        } else {
            Session::flash('success', $res->message);
        }

        return Redirect::to(URL::previous() . "#pills-variant");
    }

    public function variantDestroy(Request $request, $id)
    {
        $res = CurlTrait::sendReq("GET", config('env.API_URL') . "/dash/product/variant/delete/" . $id);

        if ($res->code != 200) {
            Session::flash('error', $res->message);
        } else {
            Session::flash('success', $res->message);
        }

        return Redirect::to(URL::previous() . "#pills-variant");
    }

    public function variantPublish(Request $request)
    {
        $params = $request->except(['_token']);
        $res = CurlTrait::sendReq("GET", config('env.API_URL') . "/dash/product/variant/publish/" . $params['variant_id'] . "/" . $params['mode']);

        if ($res->code != 200) {
            Session::flash('error', $res->message);
        } else {
            Session::flash('success', $res->message);
        }

        return Redirect::to(URL::previous() . "#pills-variant");
    }

    public function galleryStore(Request $request)
    {
        $params = $request->except(['_token', 'image']);
        $multipart = [
            [
                'name'     => 'product_id',
                'contents' => $params['product_id'],
            ],
            [
                'name' => 'photo[0][image]',
                'filename' => $request->file('image')->getClientOriginalName(),
                'contents' => fopen($request->file('image'), "r")
            ],
            [
                'name'     => 'photo[0][main]',
                'contents' => $request->has('main') ? $params['main'] : 0,
            ],
            [
                'name'     => 'photo[0][alt]',
                'contents' => $params['alt'],
            ],
            [
                'name'     => 'photo[0][title]',
                'contents' => $params['title'],
            ],
        ];

        $options['multipart'] = $multipart;
        $res = CurlTrait::sendReq("POST", config('env.API_URL') . "/dash/product/image/save", $options);

        if ($res->code != 200) {
            Session::flash('error', $res->message);
        } else {
            Session::flash('success', $res->message);
        }

        return Redirect::to(URL::previous() . "#pills-gallery");
    }

    public function galleryUpdate(Request $request, $id)
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

        if (!$request->has('main')) {
            $data['name'] = 'main';
            $data['contents'] = 0;
            array_push($multipart, $data);
        }

        $options['multipart'] = $multipart;

        $res = CurlTrait::sendReq("POST", config('env.API_URL') . "/dash/product/image/edit", $options);

        if ($res->code != 200) {
            Session::flash('error', $res->message);
        } else {
            Session::flash('success', $res->message);
        }

        return Redirect::to(URL::previous() . "#pills-gallery");
    }

    public function gallerydestroy($id)
    {
        $res = CurlTrait::sendReq("GET", config('env.API_URL') . "/dash/product/image/delete/" . $id);

        if ($res->code != 200) {
            Session::flash('error', $res->message);
        } else {
            Session::flash('success', $res->message);
        }

        return Redirect::to(URL::previous() . "#pills-gallery");
    }

    public function galleryMain($id)
    {
        $res = CurlTrait::sendReq("GET", config('env.API_URL') . "/dash/product/image/main/" . $id);

        if ($res->code != 200) {
            Session::flash('error', $res->message);
        } else {
            Session::flash('success', $res->message);
        }

        return Redirect::to(URL::previous() . "#pills-gallery");
    }

    public function search(Request $request)
    {
        $search_query = $request->input('search_query');
        $params = [
            'limit' => 6,
            'offset' => 0,
            'search_query' => $search_query,
        ];
        $options = array('form_params' => $params);
        $res = CurlTrait::sendReq("POST", config('env.API_URL') . "/dash/search/product", $options);
        return response()->json($res);
    }

    public function digitalIndex()
    {
        return view('products.digital.list');
    }

    public function digitalCreate()
    {
        $data = $this->getSources();
        return view('products.digital.create', $data);
    }

    public function digitalStore(Request $request)
    {
        $params = $request->except(['_token']);
        $options = array('form_params' => $params);
        $res = CurlTrait::sendReq("POST", config('env.API_URL') . "/dash/product/save", $options);
        if ($res->code != 200) {
            Session::flash('error', $res->message);
            return back();
        }

        return redirect()->route('product.digital.edit', ['product' => $res->data->product_id]);
    }

    public function digitalEdit($id)
    {
        $data = $this->getSources();
        $res = CurlTrait::sendReq("GET", config('env.API_URL') . "/dash/product/digital/detail/" . $id);

        if ($res->code != 200) {
            Session::flash('error', $res->message);
            return redirect()->route('product.digital.index');
        }

        $data['info'] = $res->data;
        $data['images'] = $res->data->images;
        $data['bundle'] = $res->data->bundle;
        if ($res->data->bundle != NULL) {
            $duration = explode(" ", $data['bundle']->voucher_duration);
            $data['bundle']->duration_count = $duration[0];
            $data['bundle']->duration_range = $duration[1];
            switch ($data['bundle']->discount_type) {
                case 1:
                    $data['bundle']->voucher_value_price = $data['bundle']->voucher_value;
                    $data['bundle']->voucher_value_percentage = "";
                    break;
                case 2:
                    $data['bundle']->voucher_value_price = "";
                    $data['bundle']->voucher_value_percentage = $data['bundle']->voucher_value;
                    break;
                default:
                    $pieces = explode(";", $data['bundle']->voucher_value);
                    $data['bundle']->voucher_value_price = $pieces[0];
                    $data['bundle']->voucher_value_percentage = $pieces[1];
            }
        }

        return view('products.digital.edit', $data);
    }

    public function digitalUpdate(Request $request, $id)
    {
        $params = $request->except(['_token', '_method']);
        $options = array('form_params' => $params);
        $res = CurlTrait::sendReq("POST", config('env.API_URL') . "/dash/product/edit", $options);

        if ($res->code != 200) {
            Session::flash('error', $res->message);
        } else {
            Session::flash('success', $res->message);
        }
        return back();
    }

    public function digitalBundleStore(Request $request)
    {
        $params = $request->except(['_token', 'voucher_value_price', 'voucher_value_percentage', 'duration_count', 'duration_range']);
        $voucher_value_price = $request->input('voucher_value_price');
        $voucher_value_percentage = $request->input('voucher_value_percentage');
        $duration_count = $request->input('duration_count');
        $duration_range = $request->input('duration_range');

        $params['voucher_duration'] = $duration_count . " " . $duration_range;
        if ($params['discount_type'] == "1") {
            $params['voucher_value'] = $voucher_value_price;
        } elseif ($params['discount_type'] == "2") {
            $params['voucher_value'] = $voucher_value_percentage;
        } else {
            $params['voucher_value'] = $voucher_value_price . ";" . $voucher_value_percentage;
        }
        $options = array('form_params' => $params);
        $res = CurlTrait::sendReq("POST", config('env.API_URL') . "/dash/product/voucher/save", $options);

        if ($res->code != 200) {
            Session::flash('error', $res->message);
        } else {
            Session::flash('success', $res->message);
        }

        return Redirect::to(URL::previous() . "#pills-bundle");
    }

    public function digitalBundleUpdate(Request $request, $id)
    {
        $params = $request->except(['_token', '_method', 'voucher_value_price', 'voucher_value_percentage', 'duration_count', 'duration_range']);
        $voucher_value_price = $request->input('voucher_value_price');
        $voucher_value_percentage = $request->input('voucher_value_percentage');
        $duration_count = $request->input('duration_count');
        $duration_range = $request->input('duration_range');

        $params['voucher_duration'] = $duration_count . " " . $duration_range;
        if ($params['discount_type'] == "1") {
            $params['voucher_value'] = $voucher_value_price;
        } elseif ($params['discount_type'] == "2") {
            $params['voucher_value'] = $voucher_value_percentage;
        } else {
            $params['voucher_value'] = $voucher_value_price . ";" . $voucher_value_percentage;
        }
        $options = array('form_params' => $params);
        $res = CurlTrait::sendReq("POST", config('env.API_URL') . "/dash/product/voucher/edit", $options);

        if ($res->code != 200) {
            Session::flash('error', $res->message);
        } else {
            Session::flash('success', $res->message);
        }

        return Redirect::to(URL::previous() . "#pills-bundle");
    }

    public function getDtRowDataDigital()
    {
        $data = DB::table('minimi_product')
            ->select('minimi_product.*', 'category_name')
            ->leftJoin('data_category', 'minimi_product.category_id', '=', 'data_category.category_id')
            ->where('minimi_product.product_type', 2)
            ->where('minimi_product.status', 1);
        return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('last_date', '{{ ($last_date != NULL) ? date("d M Y", strtotime($last_date)) : "" }}')
            ->addColumn('action', function ($row) {
                $action = "<a href=\"" . route('product.digital.edit', ['product' => $row->product_id]) . "\" class=\"btn btn-sm btn-block btn-gradient-primary btn-icon-text\"><i class=\"mdi mdi-pencil-box-outline btn-icon-prepend\"></i> Edit </a>";
                $action .= "<button type=\"button\" class=\"btn btn-sm btn-block btn-gradient-danger btn-icon-text\" data-toggle=\"modal\" data-target=\"#deleteProductModal\" data-id=\"" . $row->product_id . "\" data-title=\"" . $row->product_name . "\"><i class=\"mdi mdi-delete btn-icon-prepend\"></i>Delete</button>";
                return $action;
            })
            ->rawColumns(['action'])
            ->make(true);
    }
}
