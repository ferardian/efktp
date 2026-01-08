<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Admin;
use App\Models\Pegawai;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AuthController extends Controller
{
	function index()
	{
		$settings = Setting::select()->get();

		foreach ($settings as $setting) {
			$setting->logo = 'data:image/jpeg;base64,' . base64_encode($setting->logo);
			$setting->wallpaper = 'data:image/jpeg;base64,' . base64_encode($setting->wallpaper);
		}
		return view('auth.login', ['data' => $setting]);
	}

	function auth(Request $request)
	{

		$auth = User::select('*', DB::raw("AES_DECRYPT(id_user, 'nur') as username, AES_DECRYPT(password, 'windi') as passwd"))
			->where('id_user', DB::raw("AES_ENCRYPT('" . $request->get('username') . "', 'nur')"))
			->where('password', DB::raw("AES_ENCRYPT('" . $request->get('password') . "', 'windi')"))
			->first();

		if ($auth) {
			$isAuth = Auth::login($auth);
			$pegawai = Pegawai::where('nik', $auth->username)
				->with('dokter', function ($q) {
					return $q->select('kd_dokter', 'nm_dokter');
				})->select(['nik', 'departemen', 'nama', 'jk', 'jbtn'])
				->first();

			$unsetAuth = [
				$auth['password'],
				$auth['id_user'],
				$auth['passwd']
			];

			unset($unsetAuth);
			$userSesion = $auth;

			$request->session()->put(
				['pegawai' => $pegawai, 'user' => $userSesion, 'role' => $this->setRole($pegawai)]
			);

			if ($request->has('href')) {
				$routes = Route::getRoutes();
				$req = Request::create('/' . $request->href);
				try {
					$routes->match($req);
					return redirect('/' . $request->href);
				} catch (NotFoundHttpException $e) {
					return redirect('/');
				}
			}
		} else {
			return back()->with(['error' => 'Gagal Login, Periksa username & password'])->withInput();
		}

		return redirect('/');
	}

	public function logout(Request $request)
	{
		Auth::logout();
		Session::flush();
		$request->session()->regenerateToken();
		if ($request->href) {
			return redirect('/login?href=' . basename(URL::previous()));
		}
		return redirect('/login');
	}

	function setRole($pegawai): string
	{
		return $role = $pegawai->dokter ? 'dokter' : 'petugas';
	}
}
