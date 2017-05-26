<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
use DB;
use App\User;
use App\Lib;
use App\Models\Customer;
use App\Models\Number;
use App\Models\Peroid;
use App\Models\Result;
class ResultController extends Controller
{
    public function index(){
		$rows = Result::orderBy('id','desc')->paginate(24);
		$data = [
			'subject' 	=> 'ผลการออกรางวัลสลาก ',
			'title'		=> 'รายการผลออกสลาก' ,
			'rows' 		=> $rows,
		];

		return view('lotto.result.index',$data);
	}
	
	public function create(){
		$dues = Peroid::orderBy('ondate','desc')->skip(0)->take(10)->get();
		$data = [
			'subject' 	=> 'ผลการออกรางวัลสลาก ',
			'title'		=> 'ฟอร์มบันทึกรายการผลการออกสลาก' ,
			'row' 		=> false,
			'dues'		=> $dues,
			'id'		=> 0,
			'actionUrl' => 'result',
		];

		return view('lotto.result.form',$data);
	}
	
	public function store(Request $request){
		//echo '<pre>',print_r( $request->all() ),'</pre>';
		$row = new Result;
		$row->period_id = $request->input('peroid');
		$row->tang 		= $request->input('tang');
		$row->uptwo 	= $request->input('uptwo');
		$row->downtree 	= implode(',',$request->input('downtree'));
		$row->downtwo 	= $request->input('downtwo');
		$row->save();
		return redirect('result');
	}
	
	public function duedate(){
		$cdate 	= strtotime( date('Y-m-d' ) );
		$d1 	= strtotime( date('Y-m-01') );
		$d2 	= strtotime( date('Y-m-16') );
		
		$row 	= Peroid::orderBy('ondate','desc')->first();
		if($row){
			$rdate = strtotime( $row->ondate );
			if( $rdate > $d2){
				$peroid = date('Y-m-01' , strtotime('+1 month'));
			}else{
				$peroid = $rdate;
			}
		}else{
			if( $cdate > $d2){
				$peroid = date('Y-m-01' , strtotime('+1 month'));
			}else{
				$peroid = $due;
			}
		}
		return $peroid;
	}
}
