<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Models\Logs;
class LogsController extends Controller
{
	public function __construct(){
		//$this->user = 
	}
	public function index(){
		$rows = Logs::where('created_at','like',date('Y-m-d') .'%')->orderBy('created_at')->get();
		$data = [
			'rows' => $rows,
			];
		return view('lostrip.logs.index',$data);
	}
	
	public function order($id = 0){
		$rows = Logs::where('ref_id',$id)->orderBy('id')->get();
		$data = [
			'rows' => $rows,
		];
		
		return view('lostrip.logs.index',$data);
	}
}
