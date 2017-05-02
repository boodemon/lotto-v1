<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\User;
use Auth;
use DB;
use Woocommerce;

class DashboardController extends Controller
{

    public function index()
    {	
		$data = [
			'subject' => 'Home dashboard',
			'title'		=> 'Dashboard',
		];
        return view('lotto.dashboard.index',$data);
    }
}
