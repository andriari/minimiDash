<?php

namespace App\Http\Controllers\Utility;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\User;

use Illuminate\Database\QueryException as QueryException;
use Illuminate\Http\Request;

use Carbon\Carbon;

use View, Excel;

class TestController extends Controller
{
	public function test(Request $request)
	{
		return "test";
	}

	public function tepos(Request $request)
	{
		$params = $request->except(['_token']);
		$params['subcat_id'] = array_shift($params['subcategory_alt']);
		$params['subcategory_alt'] = implode(",", $params['subcategory_alt']);
		return response()->json($params);
	}
}
