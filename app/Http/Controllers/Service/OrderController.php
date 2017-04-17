<?php

namespace App\Http\Controllers\Service;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Request as Req;
use App\Models\Api;
use Woocommerce;
use App\Models\OrderHead;
use App\Models\OrderList;
use App\Models\Category;
use App\Models\Payment;
use File;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
		$param = [
			//'status'	=> 'confirmation',
			'per_page' 	=> 100,
			'page'		=> 1,
			'id'		=> '12296',
			//'order'		=> 'asc',
			'after' 	=> date('Y-m-d').'T00:00:00',
			
		];
		
		$woos = Woocommerce::get('orders',$param);
		if($woos){
			foreach($woos as $woo){
				// Record Head order
				$chkHead = OrderHead::wcid($woo['id'])->first();
				$wc_modify = date('Y-m-d H:i:s', strtotime($woo['date_modified']));
				if( !$chkHead ){
					$wcStatus = $woo['status'];
					
					$status = [
						'on-hold' 		=> 0,
						'pending' 		=> 0,
						'remittance' 	=> 0,
						'confirmation' 	=> 0,
						'processing' 	=> 2,
						'delay' 		=> 2,
						'standard' 		=> 2,
						'urgent' 		=> 2,
						'waiting' 		=> 2,
						'shipment' 		=> 3,
						'collecting' 	=> 3,
						'amendment' 	=> 1,
						'completed' 	=> 4,
						'cancelled' 	=> 99,
						'refunded' 		=> 3,
						'failed' 		=> 1
					];
					$head =  new OrderHead;
					$head->wc_id 	= 	$woo['id'];
					$head->invoice 	= 	OrderHead::newCode();
					$head->client_id=	$woo['number'];
					$head->pmt		= 	date('Y-m-d');
					$head->customer	= 	json_encode($woo['billing']);
					$head->omise_note = $this->omiseNotes($woo['id']);
					$head->status 		=   0;
					$head->wc_status	=	$wcStatus;
					$head->wc_modify	=	$wc_modify;
					if($woo['shipping_lines']){
						$ship = $woo['shipping_lines'][0];
						if( $ship['method_title'] == 'รับสินค้าเอง' ){
							$head->shipping == 'pickup';
						}
					}else{
						$head->shipping = 'email';
					}
					
					$head->save();
					$this->tracking($head->id);
					//Payment form credit card//
					$chp = Payment::order($head->id)->first();
					if($woo['payment_method'] == 'omise'){
						$pm = $chp ? $chp : new Payment;
						$pm->order_id 	= $head->id;
						$pm->bank 		= $woo['payment_method'];
						$pm->received 	= $woo['total'];
						$pm->time 		= date('H:i',strtotime($woo['date_completed']));
						$pm->save();
						
						$head->bank 		= $woo['payment_method'];
						$head->time 		= date('H:i',strtotime($woo['date_completed']));
						$head->save();
						
						
					}
					// Record Detail order //
					$total = 0;
					if($woo['line_items']){
						foreach( $woo['line_items'] as $item ){
							$sku 			= $item['sku'];
							
							$chkOrder 		= OrderList::orderid($head->id)
														->wcid($item['id'])
														->first();
														
							$order 			= $chkOrder ? $chkOrder : new OrderList;
							$order->order_id= $head->id;
							$order->wc_id 	= $item['id'];
							$order->name 	= $item['name'];
							
							$code = $this->extractCode($sku);
							$tcode = $code['type'];
							$scode = $code['spect'];
							$order->ticket 	= $code['ticket'];
							$order->sku 	= $sku;
							$order->type 	= $tcode;
							$order->spect 	= $scode;
							$meta = $this->checkMeta( $item['meta'] );
							
							if( $meta ){
								if( isset($meta['dep'])   )		$order->dep 	= $meta['dep'];
							}
							
							$order->qty 		= $item['quantity'];
							$order->unit_price 	= $item['price'];
							$order->amount 		= $item['subtotal'];
							$order->save();
							
						}
					}
					$total_unit 	= OrderList::orderid($head->id)->sum('qty');
					$total_price 	= OrderList::orderid($head->id)->sum('amount');
					OrderHead::where('id',$head->id)->update(['total_unit' => $total_unit,'total_price' => $total_price]);
				}
			}
		}
		echo 'success';
		//echo '<pre>',print_r($woos),'</pre>';
		
    }
	
	public function sortBy( $array, $key, $order = "ASC" ){ 
			$tmp = []; 
			foreach($array as $akey => $array2) 
			{ 
				$tmp[$akey] = strtotime($array2[$key]); 
			} 
			
			if($order == "DESC") 
			{arsort($tmp , SORT_NUMERIC );} 
			else 
			{asort($tmp , SORT_NUMERIC );} 

			$tmp2 = [];        
			foreach($tmp as $key => $value) 
			{ 
				$tmp2[$key] = $array[$key]; 
			}        
		return $tmp2; 
	}
	
	public function getCode(){
		$rows = Category::type('code')->get();
		$arr = [];
		if($rows){
			foreach($rows as $row){
				$arr[$row->code] = $row->name;
			}
		}
		return $arr;
	}
	
	public function extractCode($sku = ''){
		$excode = explode('-',$sku);
		$mcode = $excode[0];
		$tcode = isset($excode[1]) ? $excode[1] : '';
		$scode = isset($excode[2]) ? $excode[2] : '';
		return [
			'ticket' => $mcode,
			'type'	 => $tcode,
			'spect'	 => $scode,
		];
	}
	
	public function dep($date = ''){
		if( empty($date) ) return false;
		$d = str_replace('/','-',$date);
		return date('Y-m-d',strtotime($d) );
	}
	
	public function checkMeta($meta = []){
		$res = [];
		if($meta){
			foreach($meta as $d){
				$e = explode('/',$d['value']);
				if($d['key'] == 'pa_age'){
					$res['spect'] = $this->metaKey($d['value']);
				}
				
				if($d['key'] == 'วันเริ่มใช้งาน' ){
					$dep = str_replace('/','-',$d['value']);
					$res['dep'] = date('Y-m-d',strtotime($dep));
					
				}
				
				if($d['key'] == 'pa_seat'){
					$res['type'] = $this->metaKey($d['value']);
				}

			}
			
		}
		return $res;
	}
	
	public function metaKey($key = ''){
		
		$arr = [
			'ผู้ใหญ่' 		=> 'Adult',
			'เด็ก' 			=> 'Child',
			'เยาวชน' 		=> 'Youth',
			'เด็กน้อย' 		=> 'Kid',
			'ผู้สูงอายุ' 		=> 'Senior',
			'เดินทางคู่กัน' 		=> 'Saver-double',
			'2 ท่านขึ้นไป' 		=> 'Saver-2',
			'3 ท่านขึ้นไป' 		=> 'Saver-3',
			'ที่นั่งชั้นสอง' 		=> '2nd',
			'ที่นั่งชั้นหนึ่ง' 		=> '1st',
			'ที่นั่งธรรมดา' 		=> 'Normal',
			'ที่นั่ง Green' 	=> 'Green',
			];
		
		return isset($arr[$key]) ? $arr[$key] : '';
	}
	
	public function checkKey($api_key,$secret_key){
		//$api_key 	= Req::input('key');
		//$secret_key = Req::input('secret');
		$row = Api::where('secret_key',$secret_key)->where('api_key',$api_key)->first();
		return $row ? $row : false;
	}

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
		$chkApi 	= $this->checkKey();
		if(!$chkApi) return response()->json(['message'=>'Error!! API Key false']);
		
		$param = [
			'per_page' 	=> 25,
			'page'		=> 1,
			'before' => date('Y-m-d',strtotime('-10 days')) . 'T00:00:00'
			];
		
		$woos = Woocommerce::get('orders',$param);
		//echo '<pre>',print_r($woos),'</pre>';
		if($woos){
			foreach($woos as $woo){
				if($woo['line_items']){
					foreach($woo['line_items'] as $item){
						echo 'SKU = ' . $item['sku'] .'<br/>';
					}
				}
			}
		}
		
	}

	public function products(){
		$qr = [
			'per_page' => 50,
			'page'		=> 1,
		];
		$wcms = Woocommerce::get('products',$qr);
		echo '<pre>',print_r($wcms),'</pre>';
	}
	
	// Tracking id update //
	public function tracking($id = 0){
		$row = OrderHead::where('id',$id)->first();
		$wcm = Woocommerce::get('orders/' . $row->wc_id .'/shipment-trackings');
		if($wcm){
			$tracking = [
				'tracking_id' 		=> $wcm[0]['tracking_id'],
				'tracking_provider' => $wcm[0]['tracking_provider'],
				'tracking_link' 	=> $wcm[0]['tracking_link'],
				'tracking_number' 	=> $wcm[0]['tracking_number'],
				'date_shipped' 		=> $wcm[0]['date_shipped'],
			];
			
			$row->tracking_json = json_encode($wcm[0]);
			$row->tracking = $wcm[0]['tracking_number'];
			$row->shipping = 'transport';
			$row->delivery = $wcm[0]['tracking_provider'];
			
			$row->save();
		}
	}
	
	public function additional($addit = ''){
		$adx = explode(' ', $addit);
		if( count($adx) < 3 ) return false;
		$month = [
			'มกราคม' 	=> '01',
			'กุมภาพันธ์' 	=> '02',
			'มีนาคม' 	=> '03',
			'เมษายน' 	=> '04',
			'พฤษภาคม' 	=> '05',
			'มิถุนายน' 	=> '06',
			'กรกฎาคม' 	=> '07',
			'สิงหาคม' 	=> '08',
			'กันยายน' 	=> '09',
			'ตุลาคม' 	=> '10',
			'พฤศจิกายน' 	=> '11',
			'ธันวาคม' 	=> '12',
		];
		$m = isset( $month[ $adx[0] ] ) ? $month[ $adx[0] ] : $adx[0];
		$d = str_replace(',','',$adx[1]);
		$y = $adx[2];
		return date('Y-m-d',strtotime($y .'-'. $m .'-'. $d) );
	}
	
	public function hooks($request,$name=''){
		$json = json_encode( $request->all() );
		$orders = $request->input('order');//json_decode($json);
		$file = storage_path() . '/_tmp/'. $name . '-' . $orders['order_number'] . '.txt';
		File::put( $file, json_encode($orders) );
	}

	public function setTime($datetime = '0000-00-00T00:00:00'){
		return $datetime != '0000-00-00T00:00:00' ? date('H:i',strtotime($datetime .' + 7 hours')) : '';
		
	}
	
	public function omiseNotes($id = 0){
		$notes = Woocommerce::get('orders/'.$id .'/notes',['note'=>'Omise']);
		$txt = [];
		$err = [];
		if( $notes ){
			foreach( $notes as $note){
				if( strpos($note['note'] ,'Omise') !== false ){
					
					if( $note['note'] == 'Authorize with Omise successful'){
					$txt = [
						'created' => $note['date_created'],
						'note' => $note['note'],
							 ];
						break;
					}else{ 
					$txt = [ 
						'created' => $note['date_created'],
						'note' => $note['note'],
							 ];
					}
				}
			}
		}
		return $txt ? $txt : false;
	}
	
	public function hooksCreate(Request $request){
		$this->hooks($request,'create');
		$woo = $request->input('order');
		$status = [
				'on-hold' 		=> 0,
				'pending' 		=> 0,
				'remittance' 	=> 0,
				'confirmation' 	=> 0,
				'processing' 	=> 2,
				'delay' 		=> 2,
				'standard' 		=> 2,
				'urgent' 		=> 2,
				'waiting' 		=> 2,
				'shipment' 		=> 3,
				'collecting' 	=> 3,
				'amendment' 	=> 1,
				'completed' 	=> 4,
				'cancelled' 	=> 99,
				'refunded' 		=> 3,
				'failed' 		=> 1
					];
				// Record Head order
				$cid = $woo['order_number'];
				$chkHead = OrderHead::wcid($woo['id'])->first();
				$wc_modify = date('Y-m-d H:i:s', strtotime($woo['updated_at']));
				if( !$chkHead ){
					$wcStatus = $woo['status'];
					
					$head = $chkHead ? $chkHead : new OrderHead;
					$head->wc_id 		= 	$woo['id'];
					$head->invoice 		= 	OrderHead::newCode();
					$head->client_id	=	$cid;
					$head->pmt			= 	date('Y-m-d');
					$head->customer		= 	json_encode($woo['billing_address']);
					$head->status 		=   isset($status[$wcStatus]) ? $status[$wcStatus] : 0;
					$head->wc_status	=	$wcStatus;
					$head->wc_modify	=	$wc_modify;
					$head->note			=	( !empty( $woo['note'] ) ?  $woo['note'] .'<br/>'  : '' ) 
										  . ( isset($woo['additional']['confirm_condition']) ? $woo['additional']['confirm_condition'] .'<br/>' : '' )
										  . ( isset($woo['additional']['departure_date']) ? '<strong>วันออกเดินทาง: </strong>'. $woo['additional']['departure_date'] .'<br/>' : '' )
										  . ( isset($woo['additional']['arrival_date']) ? '<strong>วันกลับไทย: </strong>' . $woo['additional']['arrival_date']  .'<br/>' : '' );
					
					if($woo['shipping_lines']){
						$ship = $woo['shipping_lines'][0];
						if( $ship['method_title'] == 'รับสินค้าเอง' ){
							$head->shipping == 'pickup';
						}
					}else{
						$head->shipping = 'email';
					}
					$omNote = $this->omiseNotes($woo['id']);
					$head->omise_note = $omNote ? $omNote['note'] : '';
					
					$head->save();

					//Payment form credit card//
					$chp = Payment::order($head->id)->first();
					$pms = $woo['payment_details']['method_id'];
					if($pms == 'omise'){
						$time = $omNote ? $this->setTime( $omNote['created'] )  : '00:00';
						$pm = $chp ? $chp : new Payment;
						$pm->order_id 	= $head->id;
						$pm->bank 		= $pms;
						$pm->received 	= $woo['total'];
						$pm->time 		= $time;
						$pm->save();
						
						$head->bank 	= $pms;
						$head->time 	= $time;
						$head->save();
					}
					// Record Detail order //
					$total = 0;
					if($woo['line_items']){
						foreach( $woo['line_items'] as $item ){
							$sku 			= $item['sku'];
							$code 	= $this->extractCode($sku);
							$tcode 	= $code['type'];
							$scode 	= $code['spect'];
							
							$chkOrder 		= OrderList::orderid($head->id)
														->wcid($item['id'])
														->first();
														
							$order 			= $chkOrder ? $chkOrder : new OrderList;
							$order->order_id= $head->id;
							$order->wc_id 	= $item['id'];
							$order->name 	= $item['name'];
							$order->sku 	= $sku;
							$order->ticket 	= $code['ticket'];
							$order->type 	= $tcode;
							$order->spect 	= $scode;
							$order->dep 	= $this->additional($woo['additional']['departure_date']);
							
							$order->qty 		= $item['quantity'];
							$order->meta 		= json_encode($item['meta']);
							$order->unit_price 	= $item['price'];
							$order->amount 		= $item['subtotal'];
							$order->save();
						}
					}
					$total_unit 	= OrderList::orderid($head->id)->sum('qty');
					$total_price 	= OrderList::orderid($head->id)->sum('amount');
					OrderHead::where('id',$head->id)->update(['total_unit' => $total_unit,'total_price' => $total_price]);
				}
	}
	
	
	public function hooksUpdate(Request $request){
		$this->hooks($request,'update');
		$woo 	= $request->input('order');
		$cid 	= $woo['order_number'];
		
		$head 	= OrderHead::wcid($woo['id'])->first();
		$wc_modify = date('Y-m-d H:i:s', strtotime($woo['updated_at']));
		
		if( $head ){
			$wcStatus 			= 	$woo['status'];
			$head->wc_id 		= 	$woo['id'];
			$head->customer		= 	json_encode($woo['billing_address']);
			$head->wc_status	=	$wcStatus;
			if( $wcStatus == 'cancelled')
				$head->sale_status = 'cancel';
			
			if( $wcStatus == 'on-hold')
				$head->sale_status = 'new';
			
			$head->wc_modify	=	$wc_modify;
			
			$head->note			=	( !empty( $woo['note'] ) ?  $woo['note'] .'<br/>'  : '' ) 
								  . ( isset($woo['additional']['confirm_condition']) ? $woo['additional']['confirm_condition'] .'<br/>' : '' )
								  . ( isset($woo['additional']['departure_date']) ? '<strong>วันออกเดินทาง: </strong>'. $woo['additional']['departure_date'] .'<br/>' : '' )
								  . ( isset($woo['additional']['arrival_date']) ? '<strong>วันกลับไทย: </strong>' . $woo['additional']['arrival_date']  .'<br/>' : '' );
			
					$omNote = $this->omiseNotes($woo['id']);
					$head->omise_note = $omNote ? $omNote['note'] : '';
			if($woo['shipping_lines']){
				$ship = $woo['shipping_lines'][0];
				if( $ship['method_title'] == 'รับสินค้าเอง' ){
					$head->shipping == 'pickup';
				}
			}else{
				$head->shipping = 'email';
			}
					
			//Payment form credit card//
			$chp = Payment::order($head->id)->first();
			$pms = $woo['payment_details']['method_id'];
			if($pms == 'omise'){
				$time = $omNote ? $this->setTime( $omNote['created'] )  : '00:00';
				
				$pm = $chp ? $chp : new Payment;
				$pm->order_id 	= $head->id;
				$pm->bank 		= $pms;
				$pm->received 	= $woo['total'];
				
				//$pm->time 		= $time;
				$pm->save();
				
				$head->bank 		= $pms;
				//$head->time 		= $time;
			}
			$head->save();

			// Record Detail order //
			$total = 0;
			$itemID = [];
			if($woo['line_items']){
				foreach( $woo['line_items'] as $item ){
					$sku 			= $item['sku'];
					$code 	= $this->extractCode($sku);
					$tcode 	= $code['type'];
					$scode 	= $code['spect'];
					$itemID[] = $item['id'];
							
					$chkOrder 		= OrderList::orderid($head->id)
														->wcid($item['id'])
														->first();
														
					$order 			= $chkOrder ? $chkOrder : new OrderList;
					$order->order_id= $head->id;
					$order->wc_id 	= $item['id'];
					$order->name 	= $item['name'];
					$order->sku 	= $sku;
					$order->ticket 	= $code['ticket'];
					$order->type 	= $tcode;
					$order->spect 	= $scode;
					$order->dep 	= $this->additional($woo['additional']['departure_date']);
					$order->meta 	= json_encode($item['meta']);
					$order->qty 	= $item['quantity'];
					$order->unit_price 	= $item['price'];
					$order->amount 	= $chkOrder ? ( $item['price'] + $order->fee ) * $item['quantity'] : $item['subtotal'];
					$order->save();
				}
			}
			if( count($itemID) != OrderList::orderid($head->id)->count() ){
				OrderList::whereNotIn('wc_id',$itemID)->where('order_id',$head->id)->delete();
			}
			$this->tracking($head->id);
			$this->updateTotal($head->id);
		}
	}
	
	public function updateTotal($order_id = 0){
		$total_unit 	= OrderList::orderid($order_id)->sum('qty');
		$total_price 	= OrderList::orderid($order_id)->sum('amount');
		if($total_unit && $total_price)
			OrderHead::where('id',$order_id)->update(['total_unit' => $total_unit,'total_price' => $total_price]);
		
	}
	
	public function addOrder($id = 0){
		if( $id == 0) return false;
		$woo = Woocommerce::get('orders/'. $id );
		if($woo){
				// Record Head order
				$chkHead = OrderHead::wcid($woo['id'])->first();
				$wc_modify = date('Y-m-d H:i:s', strtotime($woo['date_modified']));
				if(! $chkHead ){
					$wcStatus = $woo['status'];
					
					$status = [
						'on-hold' 		=> 0,
						'pending' 		=> 0,
						'remittance' 	=> 0,
						'confirmation' 	=> 0,
						'processing' 	=> 2,
						'delay' 		=> 2,
						'standard' 		=> 2,
						'urgent' 		=> 2,
						'waiting' 		=> 2,
						'shipment' 		=> 3,
						'collecting' 	=> 3,
						'amendment' 	=> 1,
						'completed' 	=> 4,
						'cancelled' 	=> 0,
						'refunded' 		=> 0,
						'failed' 		=> 1
					];
					
					$head = $chkHead ? $chkHead : new OrderHead;
					$head->wc_id 	= 	$woo['id'];
					$head->invoice 	= 	OrderHead::newCode();
					$head->client_id=	$woo['number'];
					$head->pmt		= 	date('Y-m-d');
					$head->customer	= 	json_encode($woo['billing']);
					//$head->total_price	=	$woo['total'];
					$head->status 		=   isset($status[$wcStatus]) ? $status[$wcStatus] : 0;
					$head->wc_status	=	$wcStatus;
					$head->wc_modify	=	$wc_modify;
					if($woo['shipping_lines']){
						$ship = $woo['shipping_lines'][0];
						if( $ship['method_title'] == 'รับสินค้าเอง' ){
							$head->shipping == 'pickup';
						}
					}else{
						$head->shipping = 'email';
					}
					
					$head->save();
					$this->tracking($head->id);
					//Payment form credit card//
					$chp = Payment::order($head->id)->first();
					if($woo['payment_method'] == 'omise'){
						$pm = $chp ? $chp : new Payment;
						$pm->order_id 	= $head->id;
						$pm->bank 		= $woo['payment_method'];
						$pm->received 	= $woo['total'];
						$pm->time 		= date('H:i',strtotime($woo['date_completed']));
						$pm->save();
						
						$head->bank 		= $woo['payment_method'];
						$head->time 		= date('H:i',strtotime($woo['date_completed']));
						$head->save();
						
						
					}
					// Record Detail order //
					$total = 0;
					if($woo['line_items']){
						foreach( $woo['line_items'] as $item ){
							$sku 			= $item['sku'];
							
							$chkOrder 		= OrderList::orderid($head->id)
														->wcid($item['id'])
														->first();
														
							$order 			= $chkOrder ? $chkOrder : new OrderList;
							$order->order_id= $head->id;
							$order->wc_id 	= $item['id'];
							$order->name 	= $item['name'];
							
							$code = $this->extractCode($sku);
							$tcode = $code['type'];
							$scode = $code['spect'];
							$order->ticket 	= $code['ticket'];
							$order->sku 	= $sku;
							$order->type 	= $tcode;
							$order->spect 	= $scode;
							$meta = $this->checkMeta( $item['meta'] );
							
							if( $meta ){
								if( isset($meta['dep'])   )		$order->dep 	= $meta['dep'];
							}
							
							$order->qty 		= $item['quantity'];
							$order->unit_price 	= $item['price'];
							$order->amount 		= $item['subtotal'];
							$order->save();
						}
					}
					$total_unit 	= OrderList::orderid($head->id)->sum('qty');
					$total_price 	= OrderList::orderid($head->id)->sum('amount');
					OrderHead::where('id',$head->id)->update(['total_unit' => $total_unit,'total_price' => $total_price]);
				}
		}
	}
	
}
