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
		$data = [
			'subject' 	=> 'ผลการออกรางวัลสลาก ',
			'title'		=> 'ฟอร์มบันทึกรายการผลการออกสลาก' ,
			'row' 		=> false,
			'id'		=> 0,
		];

		return view('lotto.result.form',$data);
	}
	
	public function due(){
		$cdate 	= strtotime( date('Y-m-d' ) );
		$d1 	= strtotime( date('Y-m-01') );
		$d2 	= strtotime( date('Y-m-16') );
		
		$row 	= Peroid::orderBy('ondate','desc')->first();
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
}
