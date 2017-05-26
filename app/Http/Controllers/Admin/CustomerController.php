<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use Request as Req;
use App\Http\Controllers\Controller;
use Auth;
use DB;
use App\User;
use App\Lib;
use App\Models\Customer;
use App\Models\Number;
use App\Models\Peroid;

class CustomerController extends Controller
{
    public function index(){
		$due  = Peroid::orderBy('id','desc')->first();
		if($due){
			$rows = Customer::where('period_id',$due->id);
			if(Req::exists('keywords')){
				$keywords = Req::input('keywords');
				$rows = $rows->where(function($query) use ($keywords){
						$keys = explode(' ',$keywords);
						foreach($keys as $kno => $key){
							$query = $query->where('name','like','%'. $key .'%');
						}
				});
			}
			$rows = $rows->orderBy('name')->paginate(52);
			$numbers = $this->number($due->id);
		}else{
			$rows = false;
			$numbers = false;
		}
		$data = [
			'rows' => $rows,
			'actionUrl' => 'customer/0',
			'subject'	=> 'รายชื่อผู้ซื้อหวยประจำงวด ' . Lib::dateThai( $this->peroid() ),
			'title'		=> 'รายละเอียดผู้ซื้อ',
			'number'	=> $numbers,
		];		
		return view('lotto.customer.index',$data);
	}
	
	public function create(){
		$dealer = User::orderBy('name')->get();
		$user 	= Auth::guard('admin')->user();
		$data = [
			'row' 		=> false,
			'actionUrl' => 'customer',
			'subject'	=> 'บันทึกรายการขายประจำงวด ' . Lib::dateThai( $this->peroid() ),
			'title'		=> 'ฟอร์มบันทึกรายการขาย',
			'peroid'	=> $this->peroid(),
			'id'		=> 0,
			'user'		=> $user,
			'dealer'		=> $dealer ,
		];		
		return view('lotto.customer.form',$data);
	}
	
	public function store(Request $request){
		//echo '<pre>',print_r($request->all()),'</pre>';
		
		$cp = Peroid::where('ondate',$request->input('peroid'))->first();
		$pe = $cp ? $cp : new Peroid;
		$pe->ondate = $request->input('peroid');
		$pe->save();
		
		$customer = new Customer;
		$customer->user_id 		= $request->input('dealer_id');
		$customer->name 		= $request->input('name');
		$customer->paid 		= $request->input('paid');
		$customer->discount 	= 0;//$request->input('discount');
		$customer->remain 		= $request->input('remain');
		$customer->period_id 	= $pe->id;
		$customer->save();
		
		$total = 0;
		if( $request->input('number') ){
			foreach( $request->input('number') as $no => $number ){
				$tang 	= $request->input('tang.' . $no);
				$tod 	= $request->input('tod.' .  $no); 
				$number 	= $request->input('number.' .  $no); 
				$d = 0;
				if( $request->exists('wingup.' .  $no) )
					++$d;
				
				if( $request->exists('wingdown.' .  $no) )
					++$d;
			
				//echo 'no '. $no . ' | d : '. $d .'<br/>';
				$amount = ($d > 0 ? $tang * $d  : $tang ) + $tod;
				
				$num 	= new Number;
				$num->number 	= $number;
				$num->tang 		= $tang;
				$num->tod 		= $tod == '' ? 0 : $tod;
				$num->amount	= $amount;
				$num->wingup 	= $request->exists('wingup.' .  $no) ? 'Y' : 'N';
				$num->wingdown= $request->exists('wingdown.' .  $no) ? 'Y' : 'N';
				$num->user_id 		= $request->input('dealer_id');
				$num->period_id 	= $pe->id;
				$num->customer_id 	= $customer->id;
				if( !empty($number) )
					$num->save();
				
				$total += ($d > 0 ? $amount * $d : $amount);
				
			}
		}
	//echo $d .' | '. $total .'<br/>';
		Customer::where('id',$customer->id)->update(['total' => $total]);
		return redirect('customer');
	
		
	}
	
	public function edit($id){
		$dealer = User::orderBy('name')->get();
		$row = Customer::where('id',$id)->first();
		if(!$row) return false;
		$user 	= Auth::guard('admin')->user();
		$nums = Number::where('customer_id',$id)->get();
		$data = [
			'row' 		=> $row,
			'nums'		=> $nums,
			'actionUrl' => 'customer/' . $id,
			'subject'	=> 'บันทึกรายการขายประจำงวด ' . Lib::dateThai( $this->peroid() ),
			'title'		=> 'ฟอร์มบันทึกรายการขาย',
			'peroid'	=> $this->peroid(),
			'id'		=> $id,
			'user'		=> $user,
			'dealer'		=> $dealer ,
			'i'			=> 0,
			//'total'		=> 0,
		];		
		return view('lotto.customer.form',$data);
		
	}
	
	public function update(Request $request, $id){
		
		//echo '<pre>',print_r($request->all()),'</pre>';
		if( $request->exists('btn-delete') ){
			if(	$request->input('id') ){
				foreach($request->input('id') as $no => $cid){
					$this->del($cid);
				}
			}
			return redirect()->back();
		}else{
			$customer = Customer::where('id',$id)->first();
			
			$pe = Peroid::where('id',$customer->period_id)->first();
			$pe->ondate = $request->input('peroid');
			$pe->save();
			
			$customer->user_id 		= $request->input('dealer_id');
			$customer->name 		= $request->input('name');
			$customer->paid 		= $request->input('paid');
			$customer->discount 	= $request->input('discount');
			$customer->remain 		= $request->input('remain');
			$customer->period_id 	= $pe->id;
			$customer->save();
			
			$total = 0;
			if( $request->input('number') ){
				foreach( $request->input('number') as $no => $number ){
					$tang 	= $request->input('tang.' . $no);
					$tod 	= $request->input('tod.' .  $no); 
					$number 	= $request->input('number.' .  $no); 
					$d = 0;
					if( $request->exists('wingup.' .  $no) )
						++$d;
					
					if( $request->exists('wingdown.' .  $no) )
						++$d;
				
					//echo 'no '. $no . ' | d : '. $d .'<br/>';
					$amount = ($d > 0 ? $tang * $d  : $tang ) + $tod;
					$num_id = $request->input('number_id.'. $no);
					$cnum = Number::where('id',$num_id)->first();
					$num 	= $cnum ? $cnum : new Number;
					$num->number 	= $number;
					$num->tang 		= $tang;
					$num->tod 		= $tod == '' ? 0 : $tod;
					$num->amount	= $amount;
					$num->wingup 	= $request->exists('wingup.' .  $no) ? 'Y' : 'N';
					$num->wingdown= $request->exists('wingdown.' .  $no) ? 'Y' : 'N';
					$num->user_id 		= $request->input('dealer_id');
					$num->period_id 	= $pe->id;
					$num->customer_id 	= $customer->id;
					if( !empty($number) )
						$num->save();

					$total += $amount;
					
				}
			}
		
		//echo $d .' | '. $total .'<br/>';
			Customer::where('id',$id)->update(['total' => $total]);
			return redirect('customer');
		}
	}
	
	public function peroid(){
		$row 	= Peroid::orderBy('ondate','desc')->first();
		$cdate 	= strtotime( date('Y-m-d'));
		$due 	= strtotime( date('Y-m-16') );
		if($row){
			$rdate = strtotime( $row->ondate );
			if( $rdate > $due){
				$peroid = date('Y-m-01' , strtotime('+1 month'));
			}else{
				$peroid = $rdate;
			}
		}else{
			if( $cdate > $due){
				$peroid = date('Y-m-01' , strtotime('+1 month'));
			}else{
				$peroid = $due;
			}
		}
		return $peroid;
	}
	
	public function number($due_id = 0){
		$rows = Number::where('period_id',$due_id)->get();
		$num = [];
		if( $rows->count() ){
			foreach($rows as $row){
				$num[$row->customer_id][] = [
						'number' => $row->number,
						'tang'	=> $row->tang,
						'tod'	=> $row->tod,
						'wingup'	=> $row->wingup,
						'wingdown'	=> $row->wingdown,
						'amount'	=> $row->amount,
							];
			}
		}
		return $num;
	}
	
	public function del($id){
		Number::where('customer_id',$id)->delete();
		Customer::where('id',$id)->delete();
	}
	
	public function show($id){
		$this->del($id);
		return redirect()->back();
	}
	
}
