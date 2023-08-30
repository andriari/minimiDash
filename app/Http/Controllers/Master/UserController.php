<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Traits\CurlTrait;
use App\Models\User;

use DB, DataTables, Session;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('users.list');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $res = CurlTrait::sendReq("GET", config('env.API_URL') . "/dash/user/load/" . $id);

        // Get Total Approved and Rejected Reviews by User
        $reviews = $this->getReviews($id);

        if ($res->code != 200) {
            Session::flash('error', $res->message);
            return redirect()->route('user.index');
        }
        $data = [
            'info' => $res->data->user,
            'review' => $res->data->review,
            'offset' => $res->data->offset,
            'point' => $res->data->point_history,
            'info2' => $reviews,
        ];

        return view('users.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
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
        $data = DB::table('minimi_user_data')
            ->select('user_id', 'fullname', 'email', 'point_count', 'created_at')
            ->where('active', 1)
            ->where('test_flag', 0);
        //dd($data);
        return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('created_at', '{{ date("d M Y", strtotime($created_at)) }}')
            ->addColumn('action', function ($row) {
                return "<a href=\"" . route('user.show', ['user' => $row->user_id]) . "\" class=\"btn btn-sm btn-block btn-gradient-primary btn-icon-text\"><i class=\"mdi mdi-account-card-details btn-icon-prepend\"></i> Details </a>";
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function loadReview(Request $request, $id)
    {
        $params = $request->all();
        $res = CurlTrait::sendReq("GET", config('env.API_URL') . "/dash/user/content/load/" . $id . "?limit=" . $params['limit'] . "&offset=" . $params['offset']);

        if ($res->code != 200) {
            return response()->json(['code' => 404, 'message' => 'error']);
        }

        $data = [
            'review' => $res->data->review,
            'offset' => $res->data->offset,
        ];

        return response()->json(['code' => 200, 'message' => 'success', 'data' => $data]);
    }

    public function search(Request $request)
    {
        $search_query = $request->input('search_query');
        $result = DB::table('minimi_user_data')
            ->select('user_id', 'fullname', 'email', 'point_count')
            ->where('minimi_user_data.active', 1)
            ->where(function ($query) use ($search_query) {
                $query->where('fullname', 'like', '%' . $search_query . '%')
                    ->orWhere('email', 'like', '%' . $search_query . '%');
            })
            ->get();

        if (count($result) <= 0)
            return response()->json(['code' => 404, 'message' => 'Not Found']);

        return response()->json(['code' => 200, 'message' => 'success', 'data' => $result]);
    }

    public function getReviews($user_id)
    {
        $data = User::leftJoin('minimi_content_post', 'minimi_user_data.user_id', '=', 'minimi_content_post.user_id')
            ->where('minimi_user_data.user_id', $user_id)
            ->pluck('content_curated');

        // Approved Reviews
        $approved = $data->filter(function ($value, $key) {
            return $value == 1;
        })->count();
        
        // Rejected Reviews
        $rejected = $data->filter(function ($value, $key) {
            return $value == 2;
        })->count();

        return ['approved' => $approved, 'rejected' => $rejected];
    }
}
