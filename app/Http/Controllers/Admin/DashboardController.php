<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\User;
use App\Models\Logs;
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
		Logs::activity(Auth::guard('admin')->user()->name . ' open dashboard page');
        return view('lostrip.dashboard.index',$data);
    }
	
	public function create(){
		$data = [
			'per_page' 	=> 50,
			//'status'	=> 'remittance',
			'page'		=> 1,
			'after' => date('Y-m-d',strtotime('-2 days')).'T00:00:00',
				];

		$result = Woocommerce::get('orders', $data);
		$no = 0;
		$status = [];
		if($result){
			foreach($result as $k ){
				//echo '<p>'. ( ++$no ) .') '. $k['id'] .' | ' . $k['status'] .' | '. ( date('d-M-Y H:i',strtotime( $k['date_modified'] )) ) .'</p>';
				$status[] = $k['status'];
			}
		}
		//array_unique( $status );
		echo '<pre>',print_r( array_unique( $status ) ),'</pre>';
		echo '<pre>', print_r( $result[0] ),'</pre>';
		
	}

}
