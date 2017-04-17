<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
use App\Models\Api;

class ApiController extends Controller
{
	
	public function __construct(){
		if(Auth::guard('admin')->user()->level != 'admin') return abort(400);
		$this->user = Auth::guard('admin')->user();
	}
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
		$data = [
				'actionUrl' => 	'api/0',
				'subject'	=>	'Api key generator',
				'title'		=> 	'Api list',
				'rows'		=> 	Api::orderBy('id','desc')->paginate(24)
				
			];
		return view('lostrip.api.index',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
		$data = [
				'actionUrl' => 	'api',
				'subject'	=>	'Api key generator',
				'title'		=> 	'New api key',
				'row'		=> 	false,
				'id'		=> 	0,
				
			];
		return view('lostrip.api.form',$data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
		$row = new Api;
		$row->user_id 		= $this->user->id;
		$row->api_key 		= $request->input('api_key');
		$row->secret_key 	= $request->input('secret_key');
		$row->active 		= $request->has('active') ? 'Y' : 'N';
		$row->save();
		return redirect('api');
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
		return redirect('api');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
		$data = [
				'actionUrl' => 	'api/' . $id ,
				'subject'	=>	'Api key generator',
				'title'		=> 	'New api key',
				'row'		=> 	Api::where('id',$id)->first(),
				'id'		=> 	$id,
				
			];
		return view('lostrip.api.form',$data);
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
		if($request->exists('btn-delete')){
			$this->postDel($request);
		}else{
		$row = Api::where('id',$id)->first();
			if( $row ){
				$row->user_id 		= $this->user->id;
				$row->api_key 		= $request->input('api_key');
				$row->secret_key 	= $request->input('secret_key');
				$row->active 		= $request->has('active') ? 'Y' : 'N';
				$row->save();
			}
		}
		return redirect('api');
    }
	
	public function postDel($request){
		if($request->input('id')){
			foreach($request->input('id') as $no => $id){
				$this->del($id);
			}
		}
	}
	
	public function del($id = 0){
		Api::where('id',$id)->delete();
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
}
