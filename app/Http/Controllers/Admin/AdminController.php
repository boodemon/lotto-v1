<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests\adminRequest;

use App\Http\Requests;
use Request as Req;
use App\Http\Controllers\Controller;
use App\User;
use App\Models\Logs;
use App\Models\OrderHead;
use App\Models\OrderList;
use App\Models\Category;
use Auth;
use DB;

use App\Lib;
class AdminController extends Controller
{
	public function __construct(){
		$this->user = Auth::guard('admin')->user();
	}
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
		if( $this->user->level !== 'admin') return abort(400);
		$users = User::orderBy('name');
		if( Req::has('keywords') ){
			$keywords = Req::input('keywords');
			$users = $users->where(function($query) use ( $keywords ){
				$keys = explode(' ', $keywords );
				foreach( $keys as $k => $key ){
					$query = $query->where('name', 'like','%'. $key .'%')
									->orWhere('username', 'like','%'. $key .'%')
									->orWhere('email', 'like','%'. $key .'%');
				}
			});
		}
		$users = $users->paginate(24);
		$data = [
			'users' => $users, 
			'i' 	=> 0,
			'id'	=> 0,
			'subject'=> 'Manage user account',
			'title'	=>	'User List',
			'actionUrl' => 'user/0',
				];
				
		Logs::activity(Auth::guard('admin')->user()->name . ' open user page');
		return view('lostrip.users.admin',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
		if( $this->user->level !== 'admin') return abort(400);
		$data = [
				'user' 		=> false,
				'id' 		=> 0,
				'actionUrl' => 'user',
				'subject'	=> 'Add new user',
				'title'		=> 'Form user'
			];
		Logs::activity(Auth::guard('admin')->user()->name . ' open create user page');
		return view('lostrip.users.form',$data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(adminRequest $request)
    {
			$id 			= $request->input('id');
			$user 			= new User;
			$user->name 	= $request->input('name');	
			$user->position = Lib::level($request->input('position'));	
			$user->tel 		= $request->input('tel');	
			$user->level 	= $request->input('position');
			
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
			Logs::activity(Auth::guard('admin')->user()->name . ' insert new user is ' . $user );

			return redirect('user');
    }
	
	public function postDelete($request){
		if($request->input('id')){
			foreach($request->input('id') as $k => $id){
				echo 'delete is '. $id;
				$this->del($id);
			}
		}
	}

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
		$this->del($id);
		return redirect()->back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
		if( $this->user->level !== 'admin') return abort(400);
		$user = User::where('id',$id)->first();
		$data = [
			'user' => $user,
			'id' => $id,
			'actionUrl' => 'user/'.$id,
			'subject'	=> 'Profile ' . $user->name,
			'title'		=> 'Form user'
			];
		Logs::activity(Auth::guard('admin')->user()->name . ' open page edit user  ' . $user->name );
		return view('lostrip.users.form',$data);

	}    
	
	public function profile()
    {
		$id 	= $this->user->id;
		$user 	= User::where('id',$id)->first();
		if(!$user) return abort(404);
		$level = $user->level;
		
		$rows = DB::table('order_lists as list')
					->join('order_heads as head','head.id','=','list.order_id');
					//->whereIn('status',$status);
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
			$rows = $rows->where('head.sale_id',$user->id)
						 ->orderByRaw(DB::raw("FIELD(sale_status,'cancel','rf','done','','wl','new','help') DESC"));
		}
		
		if($level == 'op'){
			$rows = $rows->where('head.op_id',$user->id);
		}
		
		if($level == 'account'){
			$rows = $rows->where('head.account_id',$user->id)
						->orWhere('head.complete_id',$user->id );
		}
		
		
		
		
		$rows = $rows->orderBy('head.updated_at','desc')->orderBy('head.invoice')->orderBy('list.id')->paginate(50);

		$data = [
			'subject' 	=> 	'Order management',
			'title'		=> 	'Order list' . $ct ,
			'rows'		=> 	$rows,
			'level'		=> 	$user->level,
			'status'	=>	$status,
			'code'		=> 	Category::keycode(),
			'color' 	=> ['color-green','color-orange','color-blue','color-purple','color-success'],
			'icon'		=> ['<i class="color-green 	fa fa-user icon-status" title="Sale"></i>',
							'<i class="color-orange fa fa-money icon-status" title="Account correct"></i>',
							'<i class="color-blue 	fa fa-ticket icon-status" title="Operations"></i>',
							'<i class="color-purple fa fa-money icon-status" title="Account Confirm"></i>',
							'<i class="color-success fa fa-check icon-status" title="Completed"></i>'],
			'user' 		=> $user ,
			'id' 		=> $id,
			'subject'	=> 'Profile ' . Auth::guard('admin')->user()->name,
			'title'		=> 'Form profile',
			'actionUrl' => 'user/'.$id,
		];
		Logs::activity(Auth::guard('admin')->user()->name . ' open page profile  ' );
		return view('lostrip.users.profile',$data);
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
		$activity = [];
		if( $request->exists('btn-delete') ){
			$this->postDelete($request);
			return redirect()->back();
		}else{ 
			$user 		= User::where('id',$id)->first();
			$user->name 	= $request->input('name');	
			$user->position = $request->input('position');	
			$user->tel 		= $request->input('tel');	
			$user->level 	= $request->input('position');
			
			if($request->input('password') != ''){
				$user->password = bcrypt($request->input('password'));
				$activity[] = 'change password';
			}
				$username 	= $user->username;
				$email	 	= $user->email;
				if($request->input('email') != $email){
					$c = User::where('email',$request->input('email'))->first();
					if(!$c){
						$activity[] = 'change email from '. $user->email .' to '. $request->input('email');
						$user->email = $request->input('email');
					}else{
						return redirect()->back()->withErrors(['email' => 'Error! E-mail is already in use Please try again']);
					}
				}
				if($request->input('username') != $username){
					$c = User::where('username',$request->input('username'))->first();
					if(!$c){
						$activity[] = 'change username from ' . $username . ' to ' . $request->input('username');
						$user->username = $request->input('username');
					}else{
						return redirect()->back()->withErrors(['username' => 'Error! Username is already in use Please try again']);
					}
				}
			
			$user->save();
			//echo '<pre>',print_r($request->all()), print_r($user), '</pre>';
			Logs::activity( Auth::guard('admin')->user()->name . ( $activity ? implode(', ', $activity) : '' ) );
			return redirect('user');
		}
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->del($id);
		return redirect()->back();
    }
	public function del($id = 0){
		$user = User::where('id',$id)->first();
		Logs::activity(Auth::guard('admin')->user()->name . ' Delete user ' . $user->name );
		$user->delete();
	}
	
	public function logout(){
		Logs::activity( Auth::guard('admin')->user()->name . ' Logout system' );
		Auth::guard('admin')->logout();
		return redirect('login');
	}
}
