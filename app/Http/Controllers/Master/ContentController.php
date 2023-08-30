<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Traits\CurlTrait;

use DB, Session, DataTables;

class ContentController extends Controller
{
    public function contentPost()
    {
        return view('contents.post');
    }

    public function contentReview($id)
    {
        app('App\Http\Controllers\Master\NotificationController')->markRead("content", $id);

        $res = CurlTrait::sendReq("GET", config('env.API_URL') . "/dash/curate/post/detail/" . $id);

        $products = DB::table('minimi_product')
            ->where('minimi_product.status', 1)
            ->orderBy('product_name', 'asc')
            ->pluck('product_name', 'product_id');
        if ($res->code != 200) {
            Session::flash('error', $res->message);
            return redirect()->route('content.post');
        }
        $data = [
            'content_id' => $id,
            'info' => $res->data->info,
            // 'product' => $res->data->product,
            'image' => $res->data->image,
            'rating' => $res->data->rating,
            'trivia' => $res->data->trivia,
            'user' => $res->data->user,
            'products' => $products,
        ];

        if (isset($res->data->product)) {
            $data['product'] = $res->data->product;
        } else {
            $data['proposed_item'] = $res->data->proposed_item;
        }

        return view('contents.review', $data);
    }

    public function contentCurate(Request $request)
    {
        $params = $request->except(['_token']);
        $options = array('form_params' => $params);
        $res = CurlTrait::sendReq("POST", config('env.API_URL') . "/dash/curate/post/change/status", $options);

        if ($res->code != 200) {
            Session::flash('error', $res->message);
        } else {
            Session::flash('success', $res->message);
        }

        return redirect()->route('content.post');
    }

    public function getDtRowData()
    {
        $data = DB::table('minimi_content_post')
            ->select('minimi_content_post.*', 'fullname')
            ->leftJoin('minimi_user_data', 'minimi_content_post.user_id', '=', 'minimi_user_data.user_id')
            ->whereNotIn('content_type', [3])
            ->where('status', 1);
        //dd($data);
        return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('created_at', '{{ date("d F Y", strtotime($created_at)) }}')
            ->editColumn('content_type', function ($row) {
                switch ($row->content_type) {
                    case 1:
                        $cell = "<label class=\"badge badge-success\">Video</label>";
                        break;
                    case 2:
                        $cell = "<label class=\"badge badge-info\">Review</label>";
                        break;
                    case 3:
                        $cell = "<label class=\"badge badge-warning\">Artikel</label>";
                        break;
                    default:
                        $cell = "<label class=\"badge badge-danger\">Propose Product</label>";
                        break;
                }
                return $cell;
            })
            ->editColumn('content_curated', function ($row) {
                switch ($row->content_curated) {
                    case 1:
                        $cell = "<label class=\"badge badge-success\">Approved</label>";
                        break;
                    case 2:
                        $cell = "<label class=\"badge badge-danger\">Declined</label>";
                        break;
                    default:
                        $cell = "<label class=\"badge badge-secondary\">Waiting</label>";
                        break;
                }
                return $cell;
            })
            ->addColumn('action', function ($row) {
                $action = "<a href=\"" . route('content.review', ['id' => $row->content_id]) . "\" class=\"btn btn-sm btn-block btn-gradient-primary btn-icon-text\"><i class=\"mdi mdi-pencil-box-outline btn-icon-prepend\"></i> Review </a>";
                return $action;
            })
            ->filterColumn('content_curated', function ($query, $keyword) {
                $content_curated = null;
                if (stristr('waiting', $keyword)) {
                    $content_curated = 0;
                } else if (stristr('approved', $keyword)) {
                    $content_curated = 1;
                } else if (stristr('declined', $keyword)) {
                    $content_curated = 2;
                }
                $query->where('content_curated', $content_curated);
            })
            ->rawColumns(['content_type', 'content_curated', 'action'])
            ->make(true);
    }

    public function assignProduct(Request $request)
    {
        $params = $request->only(['content_id', 'product_id']);
        $options = array('form_params' => $params);
        $res = CurlTrait::sendReq("POST", config('env.API_URL') . "/dash/assign/product/review", $options);

        if ($res->code != 200) {
            Session::flash('error', $res->message);
        } else {
            Session::flash('success', $res->message);
        }

        return redirect()->route('content.review', ['id' => $params['content_id']]);
    }
}
