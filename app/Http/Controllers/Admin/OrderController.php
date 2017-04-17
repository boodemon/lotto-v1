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
use App\User;
use App\Lib;
use DB;
use PHPExcel;
use Excel;
class OrderController extends Controller
{
	
	public function __construct(){
		$this->level = Auth::guard('admin')->user()->level;
		$this->user  = Auth::guard('admin')->user();
	}
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
		$rows = DB::table('order_lists as list')
					->join('order_heads as head','head.id','=','list.order_id')
					->whereIn('head.status',[2,3,4,88,99]);
		if( Req::exists('keywords') ){
			if( !empty( Req::input('keywords')) ){
				$by 	= Req::input('by');
				$keywords = explode(' ', Req::input('keywords'));
				$rows = $rows->where(function($query) use ($keywords,$by){
					foreach($keywords as $no => $key){
						$query = $query->where('head.' . $by,'like','%'. $key .'%');
					}
				});
			}
			if( !empty( Req::input('date') ) ){
				$rows = $rows->where('head.pmt','like',Req::input('date') .'%');
			}
		}
		
		$rows = $rows->orderBy('head.pmt','asc')
					->orderByRaw(DB::raw("FIELD(bank,'k','scb','bbl','ktb','bay','cr','omise','cash','') ASC"))
					->orderBy('head.time','asc')
					// ->orderBy('head.updated_at','desc')
					 ->orderBy('head.invoice')
					 ->orderBy('list.id')->paginate(50);
					 
		$data = [
			'subject' 	=> 	'Summary orders',
			'title'		=> 	'Order list',
			'actionUrl'	=> 	'order',
			'rows'		=> 	$rows,
			'level'		=> 	$this->level,
			'code'		=> 	Category::keycode(),
			'color' 	=> ['color-green','color-orange','color-blue','color-purple','color-success'],
			'icon'		=> ['<i class="color-green 	fa fa-user icon-status" title="Sale"></i>',
							'<i class="color-orange fa fa-money icon-status" title="Account correct"></i>',
							'<i class="color-blue 	fa fa-ticket icon-status" title="Operations"></i>',
							'<i class="color-purple fa fa-money icon-status" title="Account Confirm"></i>',
							'<i class="color-success fa fa-check icon-status" title="Completed"></i>']
		];
		Logs::activity(Auth::guard('admin')->user()->name . ' open order page');
        return view('lostrip.order.index',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {	

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
		$row  = OrderHead::where('id',$id)->first();
		$rows = OrderList::where('order_id',$id)->get();
		if(!$row) return abort(404);
		$level 	= $this->user->level;
		$ukey 	= ['no','sale_id','account_id','op_id','account_id'];
		$bkey 	= ['','Sale','Account','OP','Account'];
		$field 	=  isset( $ukey[ $row->status ] ) ? $ukey[ $row->status ] : false;
		$branch	=  isset($bkey[ $row->status ]) ? $bkey[ $row->status ] : '' ;
		$use  	=  $field ? User::field( $row->$field ) : '';
		$pays 	= 	Payment::orderQuery($id);
		$cust 	= 	json_decode($row->customer);
		
		$data = [
			'subject' 	=> 'Order management ' . $branch .' #' . $use ,
			'title'		=> 'Update order id ' . $row->client_id  ,
			'id'		=> $id,
			'actionUrl'	=> 'order/'. $id ,
			'row'		=> $row,
			'rows'		=> $rows,
			'level'		=> $this->level,
			'code'		=> 	Category::keycode(),
			'pays'		=> $pays,
			'name'		=> $cust ? $cust->first_name .' '. $cust->last_name : '',
		];
		Logs::activity(Auth::guard('admin')->user()->name . ' open update order id #'. $data['row']->client_id .' page',$id);
        return view('lostrip.order.form',$data);
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
		$level = $this->level;
		$user  = $this->user;
		
		//echo '<pre>',print_r($request->all()),'</pre>';
		
		
		/* Start Admin 
			::====================================================================*/
		
		if($level == 'admin'){
			$row 	= OrderHead::where('id',$id)->first();
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
			$rmk->order_id		= 	$id;
			$rmk->save();
		}
		
		
		Logs::activity(Auth::guard('admin')->user()->name . '('. $level .') '. ( isset($act) ? $act : '' ) .' save update order client id #'. $row->client_id, $row->id );
		return redirect('customers');
	}

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
	
	public function client(Request $request){
		$rows = OrderHead::where('client_id','like','%' . $request->input('term') .'%')
						->account(0)
					//	->sale(0)
						->orderBy('client_id')->take(25)->skip(0)->get();
		$arr = [];
		if($rows){
			foreach($rows as $row){
				$cust = json_decode($row->customer);
				$arr[] = [
					'value' 	=> $row->client_id,
					'label' 	=> $row->client_id,
					'id'		=> $row->id,
					'name'		=> $cust ? $cust->first_name .' '. $cust->last_name : '' ,
					'pmt'		=> $row->pmt,
					'invoice' 	=> $row->invoice,
				];
			}
		}
		return json_encode($arr);
	}
	
	public function table($id = 0){
		$rows = OrderList::orderid($id)->get();
		$data = [
			'rows' 	=> $rows,
			'code'	=> 	Category::keycode(),
			'total'	=>	0,
			'id'	=> $id,
		];
		return view('lostrip.customer.inc-ajax-match',$data);
	}
	
	public function export(){
		$orders = DB::table('order_lists as list')
					->join('order_heads as head','head.id','=','list.order_id');
		if( Req::exists('keywords') ){
			if( !empty( Req::input('keywords')) ){
				$by 	= Req::input('by');
				$keywords = explode(' ', Req::input('keywords'));
				$orders = $orders->where(function($query) use ($keywords,$by){
					foreach($keywords as $no => $key){
						$query = $query->where('head.' . $by,'like','%'. $key .'%');
					}
				});
			}
			if( !empty( Req::input('date') ) ){
				$orders = $orders->where('head.pmt','like',Req::input('date') .'%');
			}
		}
		
		$orders = $orders->orderBy('head.pmt','asc')
					// ->orderBy('head.updated_at','desc')
					 ->orderBy('head.invoice')
					 ->orderBy('list.id')->get();
		$level		= 	$this->level;
		$code		= 	Category::keycode();
		$pc 	= 0;
		$accm 	= 0;
		$i 		= 0 ;
		$rows 	= 1;
		$xls 	= new PHPExcel();
		$excel 	= 'public/documents/export-order.xlsx';
		include public_path() . '/class/excel-order.php';
		return redirect($excel);	
	}
}
