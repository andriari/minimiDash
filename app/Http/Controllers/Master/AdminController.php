<?php

namespace App\Http\Controllers\Master;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\User;

use Illuminate\Database\QueryException as QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

use Carbon\CarbonPeriod;

use DB, Hash, Redirect, Session, URL, View;

class AdminController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$admins = DB::table('minimi_admin')
			->select('minimi_admin.*', 'role_name')
			->leftJoin('minimi_admin_role', 'minimi_admin.role_id', '=', 'minimi_admin_role.role_id')
			->get();
		return View::make('admins.list')->with(['data' => $admins]);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		$roles = DB::table('minimi_admin_role')->pluck('role_name', 'role_id');
		$data = array(
			'roles' => $roles,
		);

		return View::make('admins.create')->with($data);
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
		$check = DB::table('minimi_admin')->where('username', $params['username'])->count();
		if ($check > 0) {
			Session::flash('error', 'Username already registered!');
			return Redirect::back()->withInput($request->all());
		}
		$params['password'] = Hash::make('123456');
		DB::table('minimi_admin')->insert($params);

		Session::flash('success', 'Success');
		return redirect()->route('admin.index');
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
		$info = DB::table('minimi_admin')->where('admin_id', $id)->first();
		$roles = DB::table('minimi_admin_role')->pluck('role_name', 'role_id');
		$data = array(
			'info' => $info,
			'roles' => $roles,
		);

		return View::make('admins.edit')->with($data);
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
		$check = DB::table('minimi_admin')
			->whereNotIn('admin_id', [$id])
			->where('username', $params['username'])
			->count();
		if ($check > 0) {
			Session::flash('error', 'Username already registered!');
			return Redirect::back()->withInput($request->all());
		}

		DB::table('minimi_admin')->where('admin_id', $id)->update($params);
		Session::flash('success', 'Success');
		return redirect()->route('admin.index');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id)
	{
		DB::table('minimi_admin')->where('admin_id', $id)->delete();

		Session::flash('success', 'Success');
		return back();
	}

	public function reset_password(Request $request, $id)
	{
		DB::table('minimi_admin')->where('admin_id', $id)->update(['password' => Hash::make('123456')]);

		Session::flash('success', 'Success');
		return back();
	}

	public function roleList()
	{
		$roles = DB::table('minimi_admin_role')
			->select('minimi_admin_role.*')
			->where('status', 1)
			->get();
		$menus = DB::table('minimi_admin_menu')->get();
		$collection = collect($menus);
		foreach ($roles as $row) {
			$menu_id = array_map('intval', explode(";", $row->menu_id));
			$filtered = $collection->whereIn('menu_id', $menu_id)->sortBy('menu_tag');
			$row->menus = $filtered->pluck('menu_name')->all();
		}
		$data = [
			'data' => $roles
		];
		return View::make('admins.role-list')->with($data);
	}

	public function roleStore(Request $request)
	{
		$params = $request->except('_token');
		DB::table('minimi_admin_role')->insert($params);

		Session::flash('success', 'Success');
		return back();
	}

	public function roleEdit($id)
	{
		$role = DB::table('minimi_admin_role')
			->where('role_id', $id)
			->where('status', 1)
			->first();
		$menu_id = array_map('intval', explode(";", $role->menu_id));
		$menus = DB::table('minimi_admin_menu')->orderBy('menu_tag', 'asc')->get();

		$data = [
			'info' => $role,
			'menu_id' => $menu_id,
			'menus' => $menus,
		];
		return View::make('admins.role-edit')->with($data);
	}

	public function roleUpdate(Request $request, $id)
	{
		$params = $request->except('_token');
		if ($request->has('menu_id')) {
			$params['menu_id'] = implode(";", $params['menu_id']);
		}
		DB::table('minimi_admin_role')->where('role_id', $id)->update($params);

		Session::flash('success', 'Success');
		return redirect('admin/role');
	}
}
