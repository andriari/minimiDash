<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Traits\ImageTrait;

use DB, Session, Redirect, URL, DataTables;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = DB::table('data_category')->where('status', 1)->orderBy('category_name', 'asc')->get();
        return view('categories.list')->with(['data' => $categories]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('categories.create');
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

        if ($request->hasFile('category_picture')) {
            $destinationPath = 'public/category-icon';
            $image_path = ImageTrait::upload_image($request->file('category_picture'), $destinationPath);
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
                    $params['category_picture'] = $image_path;
                    break;
            }
        }

        $category_id = DB::table('data_category')->insertGetId($params);

        Session::flash('success', 'Success');
        return redirect(route('category.index'));
        // return route('category.edit', ['category' => $category_id]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $category = DB::table('data_category')->where('category_id', $id)->first();
        $sub_category = DB::table('data_category_sub')
            ->where('category_id', $id)
            ->where('status', 1)
            ->orderBy('subcat_name', 'asc')
            ->get();

        $data = [
            'info' => $category,
            'sc' => $sub_category
        ];
        return view('categories.edit', $data);
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
        $params['updated_at'] = $date;

        if ($request->hasFile('category_picture')) {
            $destinationPath = 'public/category-icon';
            $image_path = ImageTrait::upload_image($request->file('category_picture'), $destinationPath);
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
                    $params['category_picture'] = $image_path;
                    break;
            }
        }

        DB::table('data_category')->where('category_id', $id)->update($params);

        Session::flash('success', 'Success');
        return Redirect::to(URL::previous() . "#pills-info");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::table('data_category')->where('category_id', $id)->update([
            'status' => 0,
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        Session::flash('success', 'Success');
        return back();
    }

    public function subShow($id)
    {
        $sub_categories = DB::table('data_category_sub')->where('category_id', $id)->where('status', 1)->get();

        return response()->json(['code' => 200, 'message' => 'success', 'data' => $sub_categories]);
    }

    public function subStore(Request $request)
    {
        $params = $request->except(['_token']);

        $date = date('y-m-d H:i:s');
        $params['created_at'] = $date;
        $params['updated_at'] = $date;

        if ($request->hasFile('subcat_picture')) {
            $destinationPath = 'public/sub-category';
            $image_path = ImageTrait::upload_image($request->file('subcat_picture'), $destinationPath);
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
                    $params['subcat_picture'] = $image_path;
                    break;
            }
        }

        DB::table('data_category_sub')->insertGetId($params);

        Session::flash('success', 'Success');
        return Redirect::to(URL::previous() . "#pills-sub-category");
    }

    public function subUpdate(Request $request, $id)
    {
        $params = $request->except(['_token', '_method']);

        $date = date('y-m-d H:i:s');
        $params['updated_at'] = $date;

        if ($request->hasFile('subcat_picture')) {
            $destinationPath = 'public/sub-category';
            $image_path = ImageTrait::upload_image($request->file('subcat_picture'), $destinationPath);
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
                    $params['subcat_picture'] = $image_path;
                    break;
            }
        }

        DB::table('data_category_sub')->where('subcat_id', $id)->update($params);

        Session::flash('success', 'Success');
        return Redirect::to(URL::previous() . "#pills-sub-category");
    }
    public function subDestroy($id)
    {
        DB::table('data_category_sub')->where('subcat_id', $id)->update([
            'status' => 0,
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        Session::flash('success', 'Success');
        return Redirect::to(URL::previous() . "#pills-sub-category");
    }

    public function getDtRowData()
    {
        $data = DB::table('data_category')->where('status', 1);
        //dd($data);
        return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('category_picture', function ($row) {
                $cell = "";
                if ($row->category_picture != "")
                    $cell = '<img src="' . $row->category_picture . '" alt="image" style="width: auto; height: 50px; border-radius: 0;" />';
                return $cell;
            })
            ->addColumn('action', function ($row) {
                $action = "<a href=\"" . route('category.edit', ['category' => $row->category_id]) . "\" class=\"btn btn-sm btn-block btn-gradient-primary btn-icon-text\"><i class=\"mdi mdi-pencil-box-outline btn-icon-prepend\"></i> Edit </a>";
                $action .= "<button type=\"button\" class=\"btn btn-sm btn-block btn-gradient-danger btn-icon-text\" data-toggle=\"modal\" data-target=\"#deleteCategoryModal\" data-id=\"" . $row->category_id . "\" data-title=\"" . $row->category_name . "\"><i class=\"mdi mdi-delete btn-icon-prepend\"></i>Delete</button>";
                return $action;
            })
            ->rawColumns(['category_picture', 'action'])
            ->make(true);
    }
}
