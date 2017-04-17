<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
use App\Models\OrderHead;
use App\Models\OrderList;
use App\Models\Logs;
use App\Models\Category;
use App\Models\Remark;
use App\Models\Payment;
use Request as Req;
use Woocommerce;
use App\User;
use App\Lib;
use DB;

use App\Http\Controllers\Omise\lib\omise\OmiseTransfer;
use App\Http\Controllers\Omise\lib\omise\OmiseRecipient;
use App\Http\Controllers\Omise\lib\omise\OmiseCharge;
define('OMISE_API_VERSION', '2015-11-17');
define('OMISE_PUBLIC_KEY', 'pkey_55mrefhroilquctsjyz');
define('OMISE_SECRET_KEY', 'skey_55mregb19ew4pctlymr');

class CustomerController extends Controller
{
	
	public function __construct(){
		$this->user  = Auth::guard('admin')->user();
		$level = $this->user->level;

		if($level == 'sale'){
			$this->status = [0,1,9];
		}elseif( $level == 'account'){
			$this->status = [1,3,9];
		}elseif( $level == 'op'){
			$this->status = [2,3];
		}elseif( $level == 'admin'){
			$this->status = [0,1,2,3,4,9,99];
		}
	}

    public function index()
    {
		$user 	= $this->user;
		$level 	= $user->level;
		$status = $this->status;
		$rows = DB::table('order_lists as list')
					->join('order_heads as head','head.id','=','list.order_id')
					->whereIn('status',$status);
		if( Req::exists('keywords') ){
			$by 		= Req::input('by');
			$keywords 	= explode(' ', Req::input('keywords'));
			$rows 		= $rows->where(function($query) use ($keywords,$by){
				foreach( $keywords as $no => $key ){
					$query = $query->where('head.' . $by,'like','%'. $key .'%');
				}
			});
		}
		$ct = '';
		if( Req::exists('sale_status') ){
			$saleStatus = Req::input('sale_status');
			if( $saleStatus == 'floating'){
				$rows 	= 	$rows->where('head.status',9);	
				$ct = ' filter by Money no matching order';
			}			
			
			if( $saleStatus == 'help'){
				$rows 	= 	$rows->where('head.status',0)->where('head.sale_status','help');	
				$ct = ' filter by Help order';
			}
			
			if( $saleStatus == 'new'){
				$rows 	= 	$rows->where('head.status',0)->where('head.sale_status','new');	
				$ct = ' filter by New order';
			}
			
			if( $saleStatus == 'wl'){
				$rows 	= 	$rows->where('head.status',0)->where('head.sale_status','wl');	
				$ct = ' filter by Waiting list order';
			}
		}
		
		if($level == 'sale'){
			$rows = $rows->orderByRaw(DB::raw("FIELD(sale_status,'cancel','rf','done','','wl','new','help') DESC"));
		}
		
		if($level == 'op'){
			$rows = $rows->orderByRaw(DB::raw("FIELD(op_status,'done','','wifi','delay','normal','quick') DESC"));
		}
		
		
		$rows = $rows->orderBy('head.updated_at','desc')->orderBy('head.invoice')->orderBy('list.id')->paginate(50);

		$data = [
			'subject' 	=> 	'Order management',
			'title'		=> 	'Order list' . $ct ,
			'actionUrl'	=> 	'order',
			'rows'		=> 	$rows,
			'level'		=> 	$user->level,
			'status'	=>	$status,
			'code'		=> 	Category::keycode(),
			'color' 	=> ['color-green','color-orange','color-blue','color-purple','color-success'],
			'icon'		=> ['<i class="color-green 	fa fa-user icon-status" title="Sale"></i>',
							'<i class="color-orange fa fa-money icon-status" title="Account correct"></i>',
							'<i class="color-blue 	fa fa-ticket icon-status" title="Operations"></i>',
							'<i class="color-purple fa fa-money icon-status" title="Account Confirm"></i>',
							'<i class="color-success fa fa-check icon-status" title="Completed"></i>']
		];
		Logs::activity(Auth::guard('admin')->user()->name . ' open order page');
        return view('lostrip.customer.index',$data);
    }
	
	public function create(){
		$id 	= 0;
		$level 	= $this->user->level;
		$ukey 	= ['no','sale_id','account_id','op_id','account_id'];
		$bkey 	= ['','Sale','Account','OP','Account'];
		$branch	=  '' ;
		$use  	=  '';
		$pays 	= 	Payment::orderQuery($id);
		$data = [
			'subject' 	=> 'Order management ' . $branch .' #' . $use ,
			'title'		=> 'Create order ',
			'id'		=> $id,
			'actionUrl'	=> 'customers' ,
			'row'		=> false,
			'rows'		=> false,
			'level'		=> $level,
			'code'		=> 	Category::keycode(),
			'pays'		=> $pays,
			'status'	=> $this->status,
			'name'		=> '' ,
		];
		Logs::activity(Auth::guard('admin')->user()->name . ' open create order page',$id);
        return view('lostrip.customer.form',$data);
	}
	
	public function store(Request $request){
		$user = $this->user;
		$level = $user->level;
		if( $level == 'account'){
			$row = new OrderHead;
			$row->user_id 		= $user->id;
			$row->wc_status 	= 'floating';
			$row->status 		= 9;
			$row->bank 		= !empty( $request->input('bank') ) 	? $request->input('bank') 		: '';
			$row->time 		= !empty( $request->input('time') ) 	? $request->input('time') 		: '';
			$row->save();
			
			$detail = new OrderList;
			$detail->order_id = $row->id;
			$detail->save();
			$pays = new Payment;
			$pays->order_id 	= $row->id;
			$pays->received 	= !empty( $request->input('received') ) ? $request->input('received') 	: '';
			$pays->bank 		= !empty( $request->input('bank') ) 	? $request->input('bank') 		: '';
			$pays->time 		= !empty( $request->input('time') ) 	? $request->input('time') 		: '';
			$pays->save();
		}
		/* Start Remark record 
			::====================================================================*/
		if( !empty( $request->input('remark') ) && $row ){
			$rmk = new Remark;
			$rmk->remark 		=  	$request->input('remark');
			$rmk->user_id 		= 	$user->id;
			$rmk->order_id		= 	$row->id;
			$rmk->save();
		}	
		return redirect('customers');
	}
	
	public function edit($id){
		$row = OrderHead::where('id',$id)->first();
		$rows = OrderList::orderid($id)->orderby('id')->get();
		if(!$row) return abort(404);
		$level 	= $this->user->level;
		$ukey 	= ['no','sale_id','account_id','op_id','account_id'];
		$bkey 	= ['','Sale','Account','OP','Account'];
		$field 	=  isset( $ukey[ $row->status ] ) ? $ukey[ $row->status ] : false;
		$branch	=  isset($bkey[ $row->status ]) ? $bkey[ $row->status ] : '' ;
		$use  	=  $field ? User::field( $row->$field ) : '';
		$pays 	= 	Payment::orderQuery($id);
		$cust 	= 	json_decode($row->customer);

		$oQuery = '?limit=100&order=reverse_chronological';//from=' . date('Y-m-01') .'T00:00:00&to='. date('Y-m-d').'T'.date('H:i:s');
		$omise  = OmiseCharge::retrieve($oQuery);
		
		$data = [
			'subject' 	=> 'Order management ' . $branch .' #' . $use ,
			'title'		=> 'Update order id ' . $row->client_id  ,
			'id'		=> $id,
			'actionUrl'	=> 'customers/'. $id ,
			'row'		=> $row,
			'rows'		=> $rows,
			'level'		=> $level,
			'code'		=> 	Category::keycode(),
			'pays'		=> $pays,
			'status'	=> $this->status,
			'omise'		=> $omise['data'],
			'name'		=> $cust ? $cust->first_name .' '. $cust->last_name : '',
		];
		Logs::activity(Auth::guard('admin')->user()->name . ' open update order id #'. $data['row']->client_id .' page',$id);
        return view('lostrip.customer.form',$data);
	}
	
	public function update(Request $request , $id){

		$user  = $this->user;
		$level = $user->level;
		
		/* Start Sale 
			::====================================================================*/
		if($level == 'sale' ){
			if( $request->input('status') == 9 ){
				$row = OrderHead::where('client_id', $request->input('client_id'))->first();
				if( $row ){
					$pays = Payment::where('id',$request->input('pay_id'))->first();
					if($pays){
						$pays->order_id = $row->id;
						$pays->sale_id 	= $user->id;
						
						if( !$request->exists('btn-remark') )
						$pays->save();
						
						OrderHead::where('id',$id)->delete();
						OrderList::where('order_id',$id)->delete();
					}
					Remark::where('order_id',$id)->update(['order_id'=>$row->id]);
				}
			}else{
				$row 	= OrderHead::where('id',$id)->first();
			}
			$status = $row->status;
			
			// Head order //
			if($row){
				$row->user_id 	= $user->id;
				$row->sale_id 	= $user->id;
				if(!empty( $request->input('invoice') ) && $request->exists('invoice'))
				$row->invoice 	= $request->input('invoice');
			
				if(!empty( $request->input('client_id') )&& $request->exists('client_id') )
				$row->client_id = $request->input('client_id');
			
				$contact = json_decode($row->customer);
				$name = (!empty( $request->input('name') ) 	&& $request->exists('name') )		? explode(' ',$request->input('name')) 		: '';
				if($contact){
					$cs = [
						'first_name' 	=> $name[0],
						'last_name' 	=> $name[1],
						'company' 		=> $contact->company,
						'address_1' 	=> $contact->address_1,
						'address_2' 	=> $contact->address_2,
						'city' 			=> $contact->city,
						'state' 		=> $contact->state,
						'postcode' 		=> $contact->postcode,
						'country' 		=> $contact->country,
						'email' 		=> $contact->email,
						'phone' 		=> $contact->phone,
						
					];
				$row->customer 	= json_encode($cs);
				}
				if( $request->exists('pmt') )
				$row->pmt 	= $request->input('pmt');
				if( $request->exists('sale_status') )
				$row->sale_status 	=  $request->input('sale_status');
			
				$row->remark 		= $request->input('sale_status') == 'cancel'	? $user->name . ' : '. $request->input('head-remark') 		: '';
				$sst = $request->input('sale_status');
				
				if($sst == 'cancel'){
					$row->status 		=  99;
					Woocommerce::put('orders/'. $row->wc_id ,['status' => 'cancelled']);
				}elseif($sst == 'rf'){
					$row->status 		=  88;
				}elseif($sst == 'done'){
					$row->status 		=  1;	
					$row->wc_status 	=  'confirmation';
					Woocommerce::put('orders/'. $row->wc_id ,['status' => 'confirmation']);
				}else{
					$row->status 		=  0;
				}
				if( !$request->exists('btn-remark') )
				$row->save();
					
				// Detail order //
				$total = 0;
				$unit  = 0;
				
					if($request->input('oid') ){
						foreach($request->input('oid') as $no => $oid ){
							$qty 	= $request->input('qty.'. $no );
							$price 	= $request->input('unit_price.' .$no );
							$fee 	= $request->input('fee.' .$no );
							$amount = ($price + $fee) * $qty;
							$total 	+= $amount;
							$unit 	+= $qty;
							
							$detail = OrderList::where('id',$oid)->where('order_id',$row->id)->first();
							$detail->order_id 	= $row->id;
							$detail->ticket 	= $request->input('ticket.' . $no );
							$detail->type 		= $request->input('type.'. $no );
							$detail->spect 		= $request->input('spect.'. $no );
							$detail->fee 		= $fee;
							$detail->qty 		= $qty;
							
							if( $request->exists('ticket_type.' . $no ) )
								$detail->ticket_type = $request->input('ticket_type.' . $no );
							
							$detail->unit_price = $price;
							$detail->amount 	= $amount;
							
							if( !$request->exists('btn-remark') )
							$detail->save();
						}
					}
				OrderHead::where('id',$row->id)->update(['total_price' => $total,'total_unit' => $unit]);
				$cpays = Payment::where('id',$request->input('pay_id'))->first();
				$pays = $cpays ? $cpays : new Payment;
				if(!$cpays)
				$pays->order_id 	= $row->id;
				$pays->sale_id		= $user->id;
				$pays->received 	= $request->input('received');
				$pays->bank 		= $request->input('bank');
				$pays->time 		= $request->input('time');
				
				if( !$request->exists('btn-remark') )
				$pays->save();
			
				if( $request->exists('bank') )
				$row->bank 		=  $request->input('bank');
			
				if( $request->exists('time') )
				$row->time 		= $request->input('time');
			
				if( !$request->exists('btn-remark') )
				$row->save();
			}
		}
		/* Start Account 
			::====================================================================*/
		
		if( $level == 'account' ){
			$row = OrderHead::where('id',$id)->first();
			$cpays = Payment::where('id',$request->input('pay_id'))->first();
			$pays = $cpays ? $cpays : new Payment;
			if($request->input('status') == 9){
				$pays->received 	= $request->input('received');
				$pays->bank 		= $request->input('bank');
				$pays->time 		= $request->input('time');
				$pays->save();

				$row->bank 		=  $request->input('bank');
				$row->time 		= $request->input('time');
				$row->save();
			}else{
				if(!$cpays)
				$pays->order_id 	= $row->id;
				$row->account_id	= $user->id;
				$pays->account_id	= $user->id;
		
				if( $request->exists('pmt') )
				$row->pmt 	= $request->input('pmt');

			
				if( $request->exists('less') )
					$pays->less 		= !empty( $request->input('less') ) ? $request->input('less') : '';
			
				if( $request->exists('paid') )
					$pays->paid 		= !empty( $request->input('paid') ) ? $request->input('paid') : '';
			
				if( $request->exists('received') )
					$pays->received 	= !empty( $request->input('received') ) ? $request->input('received') : '';
			
				if( $request->exists('bank') )
					$pays->bank 		= !empty( $request->input('bank') ) ? $request->input('bank') : '';
			
				if( $request->exists('time') )
					$pays->time 		= !empty( $request->input('time') ) ? $request->input('time') : '';
				$pays->save();
				$nStatus = $row->status + 1;
				if($nStatus == 2){
					$row->wc_status 	= 'processing';
					Woocommerce::put('orders/'. $row->wc_id ,['status' => 'processing']);
					if($pays->bank == 'omise' && $request->has('charge') ){
						$chargID = $request->input('charge');
						$this->capture($chargID);
						Payment::where('order_id',$id)->update(['charge_id'=>$chargID]);
					}
				}
				if($nStatus == 4){
					$pays->complete_id	= $user->id;
					$row->wc_status = 'completed';
					Woocommerce::put('orders/'. $row->wc_id ,['status' => 'completed']);
				}
				
				if( $request->exists('btn-correct') || $request->exists('btn-complete') )
					$row->status 	= $nStatus;//$row->status + 1;
				
				
				$row->bank 		=  $request->input('bank');
				$row->time 		=  $request->input('time');
				$row->save();

			}
		}
		
		/* Start OP 
			::====================================================================*/
		if( $level == 'op' ){
			$row = OrderHead::where('id',$id)->first();
			
			$row->delivery 	= !empty( $request->input('delivery') ) ? $request->input('delivery') : '';
			if( $request->input('op_status') == 'done' )
				$row->status 	= 3;
			
			$row->shipping 	= $request->input('shipping');
			$row->tracking 	= $request->input('tracking');
			$row->op_status = $request->input('op_status');
			$row->op_id		= $user->id;
			$row->save();
			if($request->input('oid') ){
				foreach($request->input('oid') as $no => $oid ){
					$detail = OrderList::where('id',$oid)->where('order_id',$id)->first();
					$detail->dep 	= $request->input('dep.' . $no );
					$detail->order_no 		= $request->input('order.'. $no );
					$detail->save();
				}
			}
			
		}
		
		/* Start Admin 
			::====================================================================*/
		
		if($level == 'admin'){
			$row->status = $request->input('status');
			$row->save();
			$stp = ['Sale check order','Account correct step','Operations','Account complete step','Completed'];
			$act = ' Rollback step to ' . $stp[$request->input('status')] .' process ';
		}

		/* Start Remark record 
			::====================================================================*/
		if( !empty( $request->input('remark') )){
			$rmk = new Remark;
			$rmk->remark 		=  	$request->input('remark');
			$rmk->user_id 		= 	$user->id;
			$rmk->order_id		= 	( $request->input('status') == 9 && $level == 'sale' && $row ) ? $row->id : $id;
			$rmk->save();
		}
		
		
		Logs::activity(Auth::guard('admin')->user()->name . '('. $level .') '. ( isset($act) ? $act : '' ) .' save update order client id #'. ( $row ? $row->client_id : '' ), ( $row ? $row->id : 0 ) );
		return redirect('customers');
	}
	
	public function correct($id = 0){
		$user 	= $this->user;
		$level 	= $user->level;
		if($level != 'account') return abort(400);
		$row = OrderHead::where('id',$id)->first();
		if(!$row) return abort(404);
		$row->account_id = $user->id;
		$row->status 		= 2;
		$row->wc_status 	= 'processing';
		
		$row->save();
		Woocommerce::put('orders/'. $row->wc_id ,['status' => 'processing']);
		Payment::where('order_id',$id)->update(['account_id'=>$user->id]);
		return redirect()->back();
	}
	
	public function correctPost( Request $request ){
		$user 	= $this->user;
		$level 	= $user->level;
		if($level != 'account') return abort(400);
		$id = $request->input('id');
		$row = OrderHead::where('id',$id)->first();
		if(!$row) return abort(404);
		$row->account_id = $user->id;
		$row->status 		= 2;
		$row->wc_status 	= 'processing';
		
		if($request->has('charge') && !empty( $request->input('charge') ) ){
			$chargID = $request->input('charge');
			$this->capture($chargID);
			Payment::where('order_id',$id)->update(['charge_id'=>$chargID]);
		}
		
		$row->save();
		Woocommerce::put('orders/'. $row->wc_id ,['status' => 'processing']);
		Payment::where('order_id',$id)->update(['account_id'=>$user->id]);
		return redirect()->back();
	}
	
	public function capture($chargeID = ''){
		$charge = OmiseCharge::retrieve($chargeID);
		$charge->capture();
		
	}
}
