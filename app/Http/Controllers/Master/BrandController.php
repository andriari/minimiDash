<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Traits\ImageTrait;

use DB, Session, Redirect, URL, DataTables;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $brands = DB::table('data_brand')->where('status', 1)->orderBy('brand_name', 'asc')->get();
        return view('brands.list')->with(['data' => $brands]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('brands.create');
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

        $date = date('y-m-d H:i:s');
        $params['created_at'] = $date;
        $params['updated_at'] = $date;

        if ($request->hasFile('brand_picture')) {
            $destinationPath = 'public/brand';
            $image_path = ImageTrait::upload_image($request->file('brand_picture'), $destinationPath);
            switch ($image_path) {
                case 'too_big':
                    Session::flash('error', 'image too big');
                    return back();
                    break;
                case 'not_an_image':
                    Session::flash('error', 'image invalid');
                    return back();
                    break;
                default:
                    $params['brand_picture'] = $image_path;
                    break;
            }
        }

        $brand_id = DB::table('data_brand')->insertGetId($params);

        Session::flash('success', 'Success');
        return redirect()->route('brand.index');
        // return redirect()->route('brand.edit', ['brand' => $brand_id]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $brand = DB::table('data_brand')->where('brand_id', $id)->first();

        $data = [
            'info' => $brand
        ];
        return view('brands.edit', $data);
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

        $date = date('y-m-d H:i:s');
        $params['created_at'] = $date;
        $params['updated_at'] = $date;

        if ($request->hasFile('brand_picture')) {
            $destinationPath = 'public/brand';
            $image_path = ImageTrait::upload_image($request->file('brand_picture'), $destinationPath);
            switch ($image_path) {
                case 'too_big':
                    Session::flash('error', 'image too big');
                    return back();
                    break;
                case 'not_an_image':
                    Session::flash('error', 'image invalid');
                    return back();
                    break;
                default:
                    $params['brand_picture'] = $image_path;
                    break;
            }
        }

        DB::table('data_brand')->where('brand_id', $id)->update($params);

        Session::flash('success', 'Success');
        return redirect()->route('brand.edit', ['brand' => $id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::table('data_brand')->where('brand_id', $id)->update([
            'status' => 0,
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        Session::flash('success', 'Success');
        return back();
    }

    public function getDtRowData()
    {
        $data = DB::table('data_brand')->where('status', 1);
        //dd($data);
        return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('brand_picture', function ($row) {
                $cell = "";
                if ($row->brand_picture != "")
                    $cell = '<img src="' . $row->brand_picture . '" alt="image" style="width: auto; height: 50px; border-radius: 0;" />';
                return $cell;
            })
            ->addColumn('action', function ($row) {
                $action = "<a href=\"" . route('brand.edit', ['brand' => $row->brand_id]) . "\" class=\"btn btn-sm btn-block btn-gradient-primary btn-icon-text\"><i class=\"mdi mdi-pencil-box-outline btn-icon-prepend\"></i> Edit </a>";
                $action .= "<button type=\"button\" class=\"btn btn-sm btn-block btn-gradient-danger btn-icon-text\" data-toggle=\"modal\" data-target=\"#deleteBrandModal\" data-id=\"" . $row->brand_id . "\" data-title=\"" . $row->brand_name . "\"><i class=\"mdi mdi-delete btn-icon-prepend\"></i>Delete</button>";
                return $action;
            })
            ->rawColumns(['brand_picture', 'action'])
            ->make(true);
    }

    public function checkName(Request $request)
    {
        $params = $request->all();
        try {
            // if (isset($params['brand_id'])) {
            // 	$edited_brand = DB::table('data_brand')->where('brand_id', $params['brand_id'])->first();
            // 	if ($params['brand_name'] == $edited_brand->brand_name) {
            // 		return response()->json(['message' => "true"]);
            // 	}
            // }

            $brand = DB::table('data_brand')
                ->where('status', 1)
                ->where('brand_name', $params['brand_name'])
                ->first();
            if (!empty($brand)) {
                return response()->json(['message' => "false"]);
            } else {
                return response()->json(['message' => "true"]);
            }
        } catch (QueryException $ex) {
            return response()->json(['code' => $ex->getCode(), 'message' => $ex->getMessage()], 500);
        }
    }
}
