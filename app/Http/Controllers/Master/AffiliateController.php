<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Traits\CurlTrait;

use DB, Session, DataTables;

class AffiliateController extends Controller
{
    public function article()
    {
        return view('affiliate.article.list');
    }

    public function articleDetails($id)
    {
        // app('App\Http\Controllers\Master\NotificationController')->markRead("content", $id);

        $res = CurlTrait::sendReq("GET", config('env.API_URL') . "/dash/curate/post/detail/" . $id);

        if ($res->code != 200) {
            Session::flash('error', $res->message);
            return redirect()->route('affiliate.article.list');
        }
        $data = [
            'content_id' => $id,
            'info' => $res->data->info,
            'image' => $res->data->image,
            'rating' => $res->data->rating,
            'user' => $res->data->user,
            'product' => $res->data->product,
        ];

        return view('affiliate.article.details', $data);
    }

    public function articleCurate(Request $request)
    {
        $params = $request->except(['_token']);
        $options = array('form_params' => $params);
        $res = CurlTrait::sendReq("POST", config('env.API_URL') . "/dash/curate/post/change/status", $options);

        if ($res->code != 200) {
            Session::flash('error', $res->message);
        } else {
            Session::flash('success', $res->message);
        }

        return redirect()->route('affiliate.article.list');
    }

    public function getDtRowArticleData()
    {
        $data = DB::table('minimi_content_post')
            ->select('minimi_content_post.*', 'fullname')
            ->leftJoin('minimi_user_data', 'minimi_content_post.user_id', '=', 'minimi_user_data.user_id')
            ->where('content_type', [5])
            ->where('minimi_content_post.status', 1);
        //dd($data);
        return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('created_at', '{{ date("d M Y", strtotime($created_at)) }}')
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
                $action = "<a href=\"" . route('affiliate.article.details', ['id' => $row->content_id]) . "\" class=\"btn btn-sm btn-block btn-gradient-primary btn-icon-text\"><i class=\"mdi mdi-pencil-box-outline btn-icon-prepend\"></i> Preview </a>";
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
            ->rawColumns(['content_curated', 'action'])
            ->make(true);
    }

    public function bank()
    {
        return view('affiliate.bank.list');
    }

    public function getDtRowBankData()
    {
        $data = DB::table('affiliate_data')
            ->select('affiliate_data.*', 'fullname', 'bank_name')
            ->leftJoin('minimi_user_data', 'affiliate_data.user_id', '=', 'minimi_user_data.user_id')
            ->leftJoin('data_bank', 'affiliate_data.bank_abbr_code', '=', 'data_bank.bank_abbr_code')
            ->where('affiliate_data.status', 1);
        //dd($data);
        return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('created_at', '{{ date("d M Y", strtotime($created_at)) }}')
            ->editColumn('verified', function ($row) {
                switch ($row->verified) {
                    case 1:
                        $cell = "<label class=\"badge badge-success\">Verified</label>";
                        break;
                    case 2:
                        $cell = "<label class=\"badge badge-danger\">Declined</label>";
                        break;
                    default:
                        $cell = "<label class=\"badge badge-secondary\">Waiting for verify</label>";
                        break;
                }
                return $cell;
            })
            ->filterColumn('verified', function ($query, $keyword) {
                $verified = null;
                if (stristr('waiting', $keyword)) {
                    $verified = 0;
                } else if (stristr('verified', $keyword)) {
                    $verified = 1;
                } else if (stristr('declined', $keyword)) {
                    $verified = 2;
                }
                $query->where('verified', $verified);
            })
            ->rawColumns(['verified'])
            ->make(true);
    }

    public function withdraw()
    {
        return view('affiliate.withdraw.list');
    }

    public function getDtRowWithdrawData()
    {
        $data = DB::table('affiliate_withdraw_request')
            ->select('affiliate_withdraw_request.*', 'fullname')
            ->leftJoin('minimi_user_data', 'affiliate_withdraw_request.user_id', '=', 'minimi_user_data.user_id');
        //dd($data);
        return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('created_at', '{{ date("d M Y", strtotime($created_at)) }}')
            ->editColumn('amount', '{{ number_format($amount) }}')
            ->editColumn('status', function ($row) {
                switch ($row->status) {
                    case 2:
                        $cell = "<label class=\"badge badge-success\">Approved</label>";
                        break;
                    case 0:
                        $cell = "<label class=\"badge badge-danger\">Declined</label>";
                        break;
                    default:
                        $cell = "<label class=\"badge badge-secondary\">Waiting</label>";
                        break;
                }
                return $cell;
            })
            ->addColumn('action', function ($row) {
                $action = "<a href=\"" . route('affiliate.withdraw.details', ['id' => $row->awr_id]) . "\" class=\"btn btn-sm btn-block btn-gradient-primary btn-icon-text\"><i class=\"mdi mdi-pencil-box-outline btn-icon-prepend\"></i> Preview </a>";
                return $action;
            })
            ->filterColumn('status', function ($query, $keyword) {
                $status = null;
                if (stristr('waiting', $keyword)) {
                    $status = 1;
                } else if (stristr('approved', $keyword)) {
                    $status = 2;
                } else if (stristr('declined', $keyword)) {
                    $status = 0;
                }
                $query->where('affiliate_withdraw_request.status', $status);
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
    }

    public function withdrawDetails($id)
    {
        $res = CurlTrait::sendReq("GET", config('env.API_URL') . "/dash/affiliate/withdraw/detail/" . $id);

        if ($res->code != 200) {
            Session::flash('error', $res->message);
            return redirect()->route('affiliate.withdraw.list');
        }
        $data = [
            'awr_id' => $id,
            'info' => $res->data
        ];

        return view('affiliate.withdraw.details', $data);
    }

    public function withdrawApproval(Request $request)
    {
        $params = $request->except(['_token']);
        $params['admin_id'] = Session::get('user.id');
        $options = array('form_params' => $params);
        $res = CurlTrait::sendReq("POST", config('env.API_URL') . "/dash/affiliate/withdraw/change/status", $options);

        if ($res->code != 200) {
            Session::flash('error', $res->message);
        } else {
            Session::flash('success', $res->message);
        }

        return redirect()->route('affiliate.withdraw.list');
    }

    public function credit()
    {
        return view('affiliate.credit.list');
    }

    public function getDtRowCreditData()
    {
        $data = DB::table('affiliate_transaction')
            ->select('affiliate_transaction.*', 'fullname')
            ->leftJoin('minimi_user_data', 'affiliate_transaction.user_id', '=', 'minimi_user_data.user_id');
        return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('created_at', '{{ date("d M Y", strtotime($created_at)) }}')
            ->editColumn('trans_type', function ($row) {
                switch ($row->trans_type) {
                    case 2:
                        $cell = "<label class=\"badge badge-danger\">Outgoing</label>";
                        break;
                    default:
                        $cell = "<label class=\"badge badge-success\">Incoming</label>";
                        break;
                }
                return $cell;
            })
            ->rawColumns(['trans_type'])
            ->make(true);
    }

    public function creditAdjust(Request $request)
    {
        $params = $request->except(['_token', 'adjustment', 'fullname']);
        $adjustment = $request->input('adjustment');
        // $params['admin_id'] = Session::get('user.id');
        $options = array('form_params' => $params);
        $res = CurlTrait::sendReq("POST", config('env.API_URL') . "/dash/affiliate/credit/" . $adjustment, $options);

        if ($res->code != 200) {
            Session::flash('error', $res->message);
        } else {
            Session::flash('success', $res->message);
        }

        return back();
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
        $res = CurlTrait::sendReq("POST", config('env.API_URL') . "/dash/affiliate/search/user", $options);
        return response()->json($res);
    }
}
