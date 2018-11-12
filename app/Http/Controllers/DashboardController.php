<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DataTables;
use App\MstAtk;
class DashboardController extends Controller
{

	/**
	 * Halaman utama ketika pengguna selesai login
	 * url('/')
	 * 
	 */
	public function index()
	{
		return view('dashboard');
	}
}
