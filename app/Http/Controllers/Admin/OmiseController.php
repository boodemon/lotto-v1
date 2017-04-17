<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Http\Controllers\Omise\lib\omise\OmiseTransfer;
use App\Http\Controllers\Omise\lib\omise\OmiseRecipient;
use App\Http\Controllers\Omise\lib\omise\OmiseCharge;
define('OMISE_API_VERSION', '2015-11-17');
define('OMISE_PUBLIC_KEY', 'pkey_55mrefhroilquctsjyz');
define('OMISE_SECRET_KEY', 'skey_55mregb19ew4pctlymr');
use App\Models\OrderHead;
use App\Models\Payment;
class OmiseController extends Controller
{
    public function index($id = 0){
			$order = Payment::where('order_id',$id)->first();
			if(!$order) return abort(404);
			
			$query = '?limit=100&order=reverse_chronological';//from=' . date('Y-m-01') .'T00:00:00&to='. date('Y-m-d').'T'.date('H:i:s');
			$transfers = OmiseCharge::retrieve($query);
			$data = [
				'rows' 		=> $transfers['data'],
				'received' 	=> $order->received,
				'id'		=> $id,
			];
		return view('lostrip.ajax.omise',$data);
	}
	
	public function charge($order = 0){
		$pays = Payment::where('order_id',$order)->first();
		if(!$pays) return false;
		$chargeID = $pays->charge_id;
		$charge = OmiseCharge::retrieve($chargeID);
		
		echo $chargeID . '<br/><pre>',print_r($charge),'</pre>';
	}	
	
	public function chargeAll(){
			$query = '?limit=100&order=reverse_chronological';//from=' . date('Y-m-01') .'T00:00:00&to='. date('Y-m-d').'T'.date('H:i:s');
			$charge = OmiseCharge::retrieve($query);
		
		echo $chargeID . '<br/><pre>',print_r($charge),'</pre>';
	}
}
