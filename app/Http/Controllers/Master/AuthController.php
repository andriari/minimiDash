<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Tymon\JWTAuth\Exceptions as Exceptions;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\User;

use ReCaptcha\ReCaptcha;

use DB, Hash, Cookie, Session;

class AuthController extends Controller
{
	public function login(Request $request)
	{
		$recaptchaResp = $request->input('g-recaptcha-response');
		$remoteip = $_SERVER['REMOTE_ADDR'];
		$secret = config('env.RE_CAP_SECRET');
		$recaptcha = new ReCaptcha($secret);
		$resp = $recaptcha->verify($recaptchaResp,$remoteip);
		if (!$resp->isSuccess()) {
			return back()->with('error', "Complete the captcha!");
		}
		// grab credentials from the request
		$credentials = $request->only('username', 'password');

		try {
			$user = User::where('username', '=', $credentials['username'])->first();
			if (!isset($user)) {
				return back()->with('error', "User Not Found");
			}
			// attempt to verify the credentials and create a token for the user
			if (!$token = JWTAuth::attempt($credentials)) {
				return back()->with('error', "Invalid Credentials");
			} else if ($user->status == 0) {
				return back()->with('error', "Inactive User");
				//return response()->json(['error'=>'user_inactive']);
			}
			$minutes = config('env.COOKIE_MINUTES');
			$this->set_profile($token);
			if (Session::has('url.intended')) {
				return redirect(Session::get('url.intended'))->withCookie(config('env.COOKIE_TOKEN'), $token, $minutes);
			}
			return redirect()->route('home')->withCookie(config('env.COOKIE_TOKEN'), $token, $minutes);
		} catch (JWTException $e) {
			// something went wrong whilst attempting to encode the token
			return Response()->json(['error' => 'could_not_create_token'], 500);
		}
	}

	public function logout(Request $request)
	{
		$token = Cookie::get(config('env.COOKIE_TOKEN'));
		JWTAuth::invalidate($token);
		$cookie = Cookie::forget(config('env.COOKIE_TOKEN'));
		$request->session()->flush();
		return redirect('/')->withCookie($cookie);
	}

	public function getAuthenticatedUser($token = "")
	{
		if (Cookie::has(config('env.COOKIE_TOKEN')))
			$token = Cookie::get(config('env.COOKIE_TOKEN'));
		try {
			JWTAuth::setToken($token);
			if (!$user = JWTAuth::authenticate()) {
				return response()->json(['code' => 404, 'message' => 'User Not Found'], 404);
			} else if ($user->status == 0) {
				return response()->json(['code' => 402, 'message' => 'User Inactive']);
			} else {
				// the token is valid and we have found the user via the sub claim
				return response()->json(['code' => 200, 'message' => 'valid', 'data' => $user]);
			}
		} catch (Exceptions\TokenExpiredException $e) {
			return response()->json(['code' => 400, 'message' => 'Token Expired']);
		} catch (Exceptions\TokenInvalidException $e) {
			return response()->json(['code' => 401, 'message' => 'Token Invalid']);
		} catch (Exceptions\JWTException $e) {
			return response()->json(['code' => 404, 'message' => 'Token Absent']);
		}
	}

	public function show_change_password()
	{
		return view('auth.change-password');
	}

	public function change_password(Request $request)
	{
		$params = $request->except(['_token']);
		$currentUser = $this->getAuthenticatedUser()->getData();
		$credentials['username'] = $currentUser->data->username;
		$credentials['password'] = $params['old_password'];
		if (!JWTAuth::attempt($credentials)) {
			Session::flash('error', 'Old Password Wrong');
			return redirect('/change_password');
		}
		DB::table('minimi_admin')
			->where('admin_id', $currentUser->data->admin_id)
			->update(['password' => Hash::make($params['new_password'])]);
		Session::flash('success', 'Successfully Changed Password');
		return redirect('/change_password');
	}

	public function set_profile($token)
	{
		$user = $this->getAuthenticatedUser($token)->getData();
		$this->set_permission($user->data->role_id);
		Session::put('user.id', $user->data->admin_id);
		Session::put('user.name', $user->data->username);
		// Session::put('user.profile_picture', $user->data->profile_picture);
	}
	
	public function set_permission($role_id)
	{
		$menu_id = DB::table('minimi_admin_role')
			->where('role_id', $role_id)
			->where('status', 1)
			->value('menu_id');
		$mar = array_map('intval', explode(";", $menu_id));
		$menu_tags = DB::table('minimi_admin_menu')
			->whereIn('menu_id', $mar)
			->pluck('menu_tag');
		foreach($menu_tags as $menu_tag){
			$permissions[] = $menu_tag;
		}
		Session::put('user.permission', $permissions);
	}
}
