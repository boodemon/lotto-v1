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
					->orderby('type')
					->orderBy('name')
					->paginate(50);
		$data = [
			'rows' => $rows,
			'actionUrl' => 'dealer/0',
			'subject'	=> 'ผู้ใช้งานระบบตัวแทนขาย',
			'title'		=> 'รายชื่อผู้ใช้งานระบบตัวแทนขาย'
		];
		return view('lotto.dealer.index',$data);
	}
	
	public function create(){
		$data = [
			'id' => 0,
			'row'=> false,
			'actionUrl' => 'dealer',
			'subject'	=> 'เพิ่มตัวแทนขาย',
			'title'		=> 'ฟอร์มเพิ่มตัวแทนขาย'
		];
		return view('lotto.dealer.form',$data);
	}
	
    public function store(Request $request)
    {
			$id 			= $request->input('id');
			$user 			= new User;
			$user->name 	= $request->input('name');	
			$user->tel 		= $request->input('tel');	
			$user->type 	= $request->input('position');
			
			if($request->input('password') != ''){
				$user->password = bcrypt($request->input('password'));
			}

			$ec = User::where('email',$request->input('email'))->first();
			if(!$ec){
				$user->email = $request->input('email');			
			}else{
				return redirect()->back()->withErrors(['email' => 'Error! E-mail is already in use Please try again']);
			}

			$uc = User::where('username',$request->input('username'))->first();
			if(!$uc){
				$user->username = $request->input('username');
			}else{
				return redirect()->back()->withErrors(['username' => 'Error! Username is already in use Please try again']);
			}
			
			$user->save();
			return redirect('dealer');
    }
	
	public function edit($id){
		$row = User::where('id',$id)->where('type','dealer')->first();
		$data = [
			'id' => $id,
			'row'=> $row,
			'actionUrl' => 'dealer/' . $id ,
			'subject'	=> 'แก้ไขข้อมูลผู้ใช้งานระบบ',
			'title'		=> 'ข้อมูลผู้ใช้งาน ' . $row->name,
		];
		return view('lotto.dealer.form',$data);
	}
	
    public function update(Request $request, $id)
    {
		if( $request->exists('btn-delete') ){
			$this->postDelete($request);
			return redirect()->back();
		}else{ 
			$user 		= User::where('id',$id)->first();
			$user->name 	= $request->input('name');	
			$user->tel 		= $request->input('tel');	
			$user->type 	= $request->input('position');
			
			if($request->input('password') != ''){
				$user->password = bcrypt($request->input('password'));
			}
				$username 	= $user->username;
				$email	 	= $user->email;
				if($request->input('email') != $email){
					$c = User::where('email',$request->input('email'))->first();
					if(!$c){
						$user->email = $request->input('email');
					}else{
						return redirect()->back()->withErrors(['email' => 'Error! E-mail is already in use Please try again']);
					}
				}
				if($request->input('username') != $username){
					$c = User::where('username',$request->input('username'))->first();
					if(!$c){
						$user->username = $request->input('username');
					}else{
						return redirect()->back()->withErrors(['username' => 'Error! Username is already in use Please try again']);
					}
				}
			
			$user->save();
			return redirect('dealer');
		}
    }
	
	public function del($id = 0){
		$user = User::where('id',$id)->first();
		$user->delete();
	}
	
	public function postDelete($request){
		if($request->input('id')){
			foreach($request->input('id') as $k => $id){
				echo 'delete is '. $id;
				$this->del($id);
			}
		}
	}
    public function show($id)
    {
		$this->del($id);
		return redirect()->back();
    }

}
