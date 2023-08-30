<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Traits\CurlTrait;

use DB, DataTables, Session, PDF;

class OrderController extends Controller
{
    public function verificationIndex()
    {
        $data['grand_total'] = $this->getGrandTotal();
        return view('orders.verification.list', $data);
    }

    public function getDtRowVerificationData()
    {
        $data = DB::table('commerce_booking')
            ->select('commerce_booking.*', 'fullname')
            ->leftJoin('minimi_user_data', 'commerce_booking.user_id', '=', 'minimi_user_data.user_id')
            ->where('paid_status', 1)
            ->whereIn('transaction_type', [1, 2])
            ->where('cancel_status', 0);
        //dd($data);
        return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('created_at', '{{ date("d M Y", strtotime($created_at)) }}')
            ->editColumn('transaction_type', function ($row) {
                switch ($row->transaction_type) {
                    case 1:
                        $cell = "<label class=\"badge badge-primary\">Physical</label>";
                        break;
                    case 2:
                        $cell = "<label class=\"badge badge-info\">Digital</label>";
                        break;
                    default:
                        $cell = "<label class=\"badge badge-success\">Group Buy</label>";
                        break;
                }
                return $cell;
            })
            ->addColumn('verify', function ($row) {
                $action = "<label class=\"badge badge-secondary btn-block\">Waiting</label>";
                if ($row->admin_verified == 1)
                    $action = "<label class=\"badge badge-success btn-block\">Verified</label>";
                return $action;
            })
            ->filterColumn('verify', function ($query, $keyword) {
                $admin_verified = null;
                if(stristr('verified', $keyword)) {
                    $admin_verified = 1;
                } else if (stristr('waiting', $keyword)) {
                    $admin_verified = 0;
                }
                $query->where('admin_verified', $admin_verified);
            })
            ->addColumn('action', function ($row) {
                $action = "<a href=\"" . route('order.verification.details', ['order' => $row->booking_id]) . "\" class=\"btn btn-sm btn-block btn-gradient-info btn-icon-text\"><i class=\"mdi mdi-details btn-icon-prepend\"></i> Details </a>";
                return $action;
            })
            ->rawColumns(['transaction_type', 'verify', 'action'])
            ->make(true);
    }

    public function verifyPayment(Request $request)
    {
        $params = $request->except(['_token']);
        $params['admin_id'] = Session::get('user.id');
        $options = array('form_params' => $params);
        $res = CurlTrait::sendReq("POST", config('env.API_URL') . "/dash/order/verified", $options);

        if ($res->code != 200) {
            Session::flash('error', $res->message);
        } else {
            Session::flash('success', $res->message);
        }

        return back();
    }

    public function orderVerificationDetails($id)
    {
        $res = CurlTrait::sendReq("GET", config('env.API_URL') . "/dash/order/detail/" . $id);

        if ($res->code != 200) {
            Session::flash('error', $res->message);
            return back();
        }

        $data = [
            'info' => $res->data,
        ];

        return view('orders.verification.details', $data);
    }

    /**
     * Delivery
     **/

    public function deliveryIndex()
    {
        return view('orders.delivery.list');
    }

    public function getDtRowDeliveryData()
    {
        $data = DB::table('commerce_booking')
            ->select('commerce_booking.*', 'fullname', 'address_postal_code', 'commerce_shopping_cart.total_weight')
            ->leftJoin('minimi_user_data', 'commerce_booking.user_id', '=', 'minimi_user_data.user_id')
            ->leftJoin('minimi_user_address', 'commerce_booking.address_id', '=', 'minimi_user_address.address_id')
            ->leftJoin('commerce_shopping_cart', 'commerce_booking.cart_id', '=', 'commerce_shopping_cart.cart_id')
            ->where('admin_verified', 1)
            ->whereIn('transaction_type', [1, 3]);
        //dd($data);
        return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('verified_at', '{{ date("d M Y H:i", strtotime($verified_at)) }}')
            ->editColumn('created_at', '{{ date("d M Y H:i", strtotime($created_at)) }}')
            ->editColumn('total_weight', '{{ $total_weight }}' . ' KG')
            ->addColumn('check', '')
            ->addColumn('verify', function ($row) {
                $action = "<label class=\"badge badge-secondary\">Waiting</label>";
                if ($row->delivery_verified == 1)
                    $action = "<label class=\"badge badge-success\">Verified</label>";
                return $action;
            })
            /*->filterColumn('verify', function ($query, $keyword) {
                $delivery_verified = null;
                if(stristr('verified', $keyword)) {
                    $delivery_verified = 1;
                } else if (stristr('waiting', $keyword)) {
                    $delivery_verified = 0;
                }
                $query->where('delivery_verified', $delivery_verified);
            })*/
            ->addColumn('action', function ($row) {
                $action = "<a href=\"" . route('order.delivery.details', ['order' => $row->booking_id]) . "\" class=\"btn btn-sm btn-block btn-gradient-info btn-icon-text\"><i class=\"mdi mdi-details btn-icon-prepend\"></i> Details </a>";
                if ($row->delivery_verified == 0) {
                    if (strtolower($row->delivery_vendor) == 'sicepat' && $row->delivery_receipt_number == NULL) {
                        $action .= "<button type=\"button\" class=\"btn btn-sm btn-block btn-gradient-success btn-icon-text\" data-toggle=\"modal\" data-target=\"#requestPickupModal\" data-id=\"" . $row->order_id . "\" data-user_id=\"" . $row->user_id . "\"><i class=\"mdi mdi-truck btn-icon-prepend\"></i> Request Pickup</button>";
                    } else {
                        $action .= "<a href=\"" . route('order.download.receipt', ['booking_id[]' => $row->booking_id]) . "\" class=\"btn btn-sm btn-block btn-gradient-warning btn-icon-text\" target=\"_blank\"><i class=\"mdi mdi-download btn-icon-prepend\"></i> Download Receipt</a>";
                    }

                    if (strtolower($row->delivery_vendor) == 'sicepat' && $row->delivery_receipt_number != NULL) {
                        $action .= "<button type=\"button\" class=\"btn btn-sm btn-block btn-gradient-primary btn-icon-text\" data-toggle=\"modal\" data-target=\"#verifyDeliveryModal\" data-id=\"" . $row->order_id . "\" data-user_id=\"" . $row->user_id . "\"><i class=\"mdi mdi-truck-delivery btn-icon-prepend\"></i> Verify Delivery</button>";
                    } else if (strtolower($row->delivery_vendor) == 'minimi') {
                        $action .= "<button type=\"button\" class=\"btn btn-sm btn-block btn-gradient-primary btn-icon-text\" data-toggle=\"modal\" data-target=\"#verifyDeliveryModal\" data-id=\"" . $row->order_id . "\" data-user_id=\"" . $row->user_id . "\"><i class=\"mdi mdi-truck-delivery btn-icon-prepend\"></i> Verify Delivery</button>";
                    }
                } else {
                    $action .= "<a href=\"" . route('order.download.receipt', ['booking_id[]' => $row->booking_id]) . "\" class=\"btn btn-sm btn-block btn-gradient-warning btn-icon-text\" target=\"_blank\"><i class=\"mdi mdi-download btn-icon-prepend\"></i> Download Receipt</a>";
                }
                return $action;
            })
            ->rawColumns(['action', 'verify'])
            ->make(true);
    }

    public function requestPickup(Request $request)
    {
        $params = $request->except(['_token']);
        $params['admin_id'] = Session::get('user.id');
        $options = array('form_params' => $params);
        $res = CurlTrait::sendReq("POST", config('env.API_URL') . "/dash/sicepat/pickup/request", $options);

        if ($res->code != 200) {
            Session::flash('error', $res->message);
        } else {
            Session::flash('success', $res->message);
        }

        return back();
    }

    public function requestPickupCreate(Request $request)
    {
        $params = $request->except(['_token']);

        return view('orders.delivery.pickup-bulk', $params);
    }

    public function requestPickupBulk(Request $request)
    {
        $params = $request->except(['_token']);
        $params['admin_id'] = Session::get('user.id');
        $options = array('form_params' => $params);
        $res = CurlTrait::sendReq("POST", config('env.API_URL') . "/dash/sicepat/pickup/request/bulk", $options);

        if ($res->code != 200) {
            Session::flash('error', $res->message);
        } else {
            Session::flash('success', $res->message);
        }

        return redirect()->route('order.delivery');
    }

    public function verifyDelivery(Request $request)
    {
        $params = $request->except(['_token']);
        $params['admin_id'] = Session::get('user.id');
        $options = array('form_params' => $params);
        $res = CurlTrait::sendReq("POST", config('env.API_URL') . "/dash/order/pickup/verified", $options);

        if ($res->code != 200) {
            Session::flash('error', $res->message);
        } else {
            Session::flash('success', $res->message);
        }

        return back();
    }

    public function verifyDeliveryBulk(Request $request)
    {
        $params = $request->except(['_token']);
        $params['admin_id'] = Session::get('user.id');
        $options = array('form_params' => $params);
        $res = CurlTrait::sendReq("POST", config('env.API_URL') . "/dash/order/pickup/verified/bulk", $options);

        if ($res->code != 200) {
            Session::flash('error', $res->message);
        } else {
            Session::flash('success', $res->message);
        }

        return back();
    }

    public function orderDeliveryDetails($id)
    {
        $res = CurlTrait::sendReq("GET", config('env.API_URL') . "/dash/order/detail/" . $id);

        if ($res->code != 200) {
            Session::flash('error', $res->message);
            return back();
        }

        $data = [
            'info' => $res->data,
        ];

        return view('orders.delivery.details', $data);
    }

    public function getGrandTotal()
    {
        $grand_total = DB::table('commerce_booking')
            ->leftJoin('minimi_user_data', 'commerce_booking.user_id', '=', 'minimi_user_data.user_id')
            ->where('paid_status', 1)
            ->whereIn('transaction_type', [1, 2])
            ->where('cancel_status', 0)
            ->sum('total_amount');

        return $grand_total;
    }

    /**
     * Group Buy
     **/

    public function groupBuyIndex()
    {
        CurlTrait::sendReq("GET", config('env.API_URL') . "/dash/groupbuy/status/checker");
        $data['grand_total'] = $this->getGrandTotalGroupBuy();
        return view('orders.groupbuy.list', $data);
    }

    public function getDtRowGroupBuyData()
    {
        $data = DB::table('commerce_group_buy')
            ->select('commerce_group_buy.*', 'fullname', 'product_name')
            ->leftJoin('minimi_user_data', 'commerce_group_buy.user_id', '=', 'minimi_user_data.user_id')
            ->leftJoin('minimi_product', 'commerce_group_buy.product_id', '=', 'minimi_product.product_id')
            ->whereIn('commerce_group_buy.status', [0, 2, 3, 4])
            // ->whereNotIn('commerce_group_buy.status', [5])
            ->whereNotNull('commerce_group_buy.user_id');
        //dd($data);
        return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('created_at', '{{ date("d M Y H:i", strtotime($created_at)) }}')
            ->editColumn('updated_at', '{{ date("d M Y H:i", strtotime($updated_at)) }}')
            ->editColumn('status', function ($row) {
                switch ($row->status) {
                    case 0:
                        $action = "<label class=\"badge badge-primary btn-block\">0 : Expired (Waiting for verification)</label>";
                        break;
                    case 1:
                        $action = "<label class=\"badge badge-secondary btn-block\">1 : Waiting Payment</label>";
                        break;
                    case 2:
                        $action = "<label class=\"badge badge-secondary btn-block\">2 : Participant < 3</label>";
                        break;
                    case 3:
                        $action = "<label class=\"badge badge-primary btn-block\">3 : Participant >= 3 (Waiting for verification)</label>";
                        break;
                    case 4:
                        $action = "<label class=\"badge badge-success btn-block\">4 : Verified</label>";
                        break;
                    default:
                        $action = "<label class=\"badge badge-danger btn-block\">5 : Closed</label>";
                        break;
                }
                return $action;
            })
            ->addColumn('action', function ($row) {
                $action = "<a href=\"" . route('order.groupbuy.details', ['cg_id' => $row->cg_id]) . "\" class=\"btn btn-sm btn-block btn-gradient-info btn-icon-text\"><i class=\"mdi mdi-details btn-icon-prepend\"></i> Details </a>";
                return $action;
            })
            ->filterColumn('status', function ($query, $keyword) {
                $status = null;
                if(stristr('0 : Expired', $keyword)) {
                    $status = 0;
                } else if (stristr('1 : Waiting Payment', $keyword)) {
                    $status = 1;
                } else if (stristr('2 : Participant < 3', $keyword)) {
                    $status = 2;
                } else if (stristr('3 : Participant >= 3', $keyword)) {
                    $status = 3;
                } else if (stristr('4 : Verified', $keyword)) {
                    $status = 4;
                }
                $query->where('commerce_group_buy.status', $status);
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
    }

    public function orderGroupBuyDetails($id)
    {
        $res = CurlTrait::sendReq("GET", config('env.API_URL') . "/dash/groupbuy/detail/" . $id);

        if ($res->code != 200) {
            Session::flash('error', $res->message);
            return back();
        }

        $data = [
            'info' => $res->data->group,
            'order' => $res->data->order,
        ];

        return view('orders.groupbuy.details', $data);
    }

    public function verifyGroupBuy(Request $request)
    {
        $params = $request->except(['_token']);
        $params['admin_id'] = Session::get('user.id');
        $options = array('form_params' => $params);
        $res = CurlTrait::sendReq("POST", config('env.API_URL') . "/dash/groupbuy/verify", $options);

        if ($res->code != 200) {
            Session::flash('error', $res->message);
        } else {
            Session::flash('success', $res->message);
        }

        return back();
    }

    public function getGrandTotalGroupBuy()
    {
        $grand_total = DB::table('commerce_booking')
            ->leftJoin('minimi_user_data', 'commerce_booking.user_id', '=', 'minimi_user_data.user_id')
            ->where([
                'paid_status' => 1,
                'transaction_type' => 3,
                'cancel_status' => 0
            ])
            ->sum('total_amount');

        return $grand_total;
    }

    public function searchGb(Request $request)
    {
        $search_query = $request->input('search_query');
        $params = [
            'limit' => 6,
            'offset' => 0,
            'search_query' => $search_query,
        ];
        $options = array('form_params' => $params);
        $res = CurlTrait::sendReq("POST", config('env.API_URL') . "/dash/groupbuy/search", $options);
        return response()->json($res);
    }

    public function mergeGb(Request $request)
    {
        $params = $request->only(['cg_id_to', 'cg_id_from']);
        $params['admin_id'] = Session::get('user.id');
        $options = array('form_params' => $params);
        $res = CurlTrait::sendReq("POST", config('env.API_URL') . "/dash/groupbuy/merge", $options);

        if ($res->code != 200) {
            Session::flash('error', $res->message);
        } else {
            Session::flash('success', $res->message);
        }

        return redirect()->route('order.groupbuy.details', ['cg_id' => $params['cg_id_to']]);
    }

    public function searchOrder(Request $request)
    {
        $search_query = $request->input('search_query');
        $params = [
            'transaction_type' => 3,
            'search_query' => $search_query,
        ];
        $options = array('form_params' => $params);
        $res = CurlTrait::sendReq("POST", config('env.API_URL') . "/dash/order/search", $options);
        return response()->json($res);
    }

    public function downloadReceipt(Request $request)
    {
        if(!$request->has('booking_id')){
            Session::flash('error', 'Order Not Found');
            return redirect()->route('order.delivery');
        }
        $params = $request->all();
        $options = array('form_params' => $params);
        $res = CurlTrait::sendReq("POST", config('env.API_URL') . "/dash/order/bulk/detail", $options);

        if ($res->code != 200) {
            Session::flash('error', $res->message);
            return redirect()->route('order.delivery');
        }

        $data = [
            'orders' => $res->data,
        ];
        $pdf = PDF::loadView('pdf.receipt', $data)->setPaper('a4', 'landscape')->setWarnings(false);
        return $pdf->stream();
    }
}
