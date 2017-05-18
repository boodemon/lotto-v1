<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
use DB;
use App\Lib;
use App\Models\Customer;
use App\Models\Number;
use App\Models\Peroid;

class CustomerController extends Controller
{
    public function index(){
		
		$data = [
			//'row' => $row,
			'actionUrl' => 'customer',
			'subject'	=> 'รายชื่อผู้ซื้อหวยประจำงวด ' . Lib::dateThai( $this->peroid() ),
			'title'		=> 'รายละเอียดผู้ซื้อ'
		];		
		return view('lotto.customer.index',$data);
	}
	
	public function create(){
		
		$data = [
			'row' 		=> false,
			'actionUrl' => 'customer',
			'subject'	=> 'บันทึกรายการขายประจำงวด ' . Lib::dateThai( $this->peroid() ),
			'title'		=> 'ฟอร์มบันทึกรายการขาย',
			'peroid'	=> $this->peroid(),
			'id'		=> 0,
		];		
		echo 'date : ' . $this->peroid();
		return view('lotto.customer.form',$data);
	}
	
	public function peroid(){
		$row 	= Peroid::orderBy('id','desc')->first();
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
}
