<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
use App\User;
use App\Models\Setting;

class SettingController extends Controller
{
	public function __construct(){
		$this->user = Auth::guard('admin')->user();
		if($this->user->type != 'admin'){
			return abort(400);
		}
	}
	public function index(){
		$row = Setting::orderBy('id')->first();
		$data = [
			'row' => $row,
			'actionUrl' => 'setting',
			'subject'	=> 'ตั้งค่ารางวัลและผลตอบแทน',
			'title'		=> 'ฟอร์มบันทึกรางวัลถูกหวย/ค่าตอบแทนขาย'
		];
		return view('lotto.setting.index',$data);
	}
	
	public function store(Request $request){
		echo '<pre>',print_r($request->all()),'</pre>';
		
		$crow = Setting::orderBy('id')->first();
		$row = $crow ? $crow : new Setting;
		$row->tang 		= $request->input('tang');
		$row->tod 		= $request->input('tod');
		$row->uptwo 	= $request->input('uptwo');
		$row->todtwo 	= $request->input('todtwo');
		$row->upwing 	= $request->input('upwing');
		$row->downtwo 	= $request->input('downtwo');
		$row->downtree 	= $request->input('downtree');
		$row->downwing 	= $request->input('downwing');
		$row->comission = $request->input('comission');
		$row->save();
		return redirect()->back();
	}
}
