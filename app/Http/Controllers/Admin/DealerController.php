<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\User;

class DealerController extends Controller
{
	public function index(){
		$rows = User::where('type','dealer')
					->orderBy('name')
					->paginate(50);
		$data = [
			'rows' => $rows,
		];
		return view('lotto.dealer.index',$data);
	}
	
	public function create(){
		$data = [
			'id' => 0,
			'row'=> false,
		];
		return view('lotto.dealer.form',$data);
	}
}
