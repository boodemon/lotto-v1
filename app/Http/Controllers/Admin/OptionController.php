<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Option;
use Request as Req;
use Auth;
class OptionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id = 0)
    {
		$opt = Category::where('id',$id)->first();
		if(!$opt) return abort(404);
		$rows = Option::ref($id);
		if(Req::exists('keywords') ){
			$keywords = explode(' ', Req::input('keywords') );
			$rows = $rows->where( function($query) use ($keywords){
				foreach($keywords as $k => $key){
					$query = $query->where('name','like','%'. $key .'%');
				}
			});
		}
		
		$rows = $rows->paginate(24);		
		$data = [
			'subject' 	=> $opt->name,
			'title' 	=> $opt->name . ' list',
			'actionUrl'	=> 'option-data-action',
			'opt'		=> $opt,
			'rows'		=> $rows,
			'refID'		=> $id,
		];
		
		return view('lostrip.option.index',$data);
        //
    }

	public function form($refID = 0,$id = 0){
	  $ref = Category::where('id',$refID)->first();
	  if( !$ref ) return abort(404);
	  
		$row = Option::where('id',$id)->first();
		$data = [
			'subject' 	=> 'data of '. $ref->name,
			'title' 	=> 'opton form',
			'actionUrl'	=> 'option-data-form/' . $refID,
			'id'		=> $id,
			'type'		=> 'option-data',
			'row'		=> $row,
			'refID'		=> $ref->id,
			'category' 	=>  Category::maincheckbox('id','name',$row ? $row->category : 0),
		];
		
		return view('lostrip.option.form',$data);
	}
	
	public function formPost(Request $request,$ref_id = 0){
		if($ref_id == 0) return abort(404);
		if(!$request->has('category')){
			return redirect()->back()->withErrors(['category' => 'Please checked category'])->withInput();
		}
		echo '<pre>', print_r( $request->all() ),'</pre>';
		$category 	= implode( ',',$request->input('category') );
		$id 		= $request->input('id');
		$ck 		= Option::where('id',$id)->first();
		if($request->input('name')){
			foreach($request->input('name') as $k => $name){
				if( !empty( $name ) ){
					$row 			= $ck ? $ck : new Option;
					$row->ref_id	= $ref_id;
					$row->code 		=  $request->input('code.' . $k );
					$row->name 		= $name;
					$row->category 	= $category;
					$row->save();
				}
			}
		}
		return redirect('option-data/'. $ref_id );
	}
	
	public function deleteRow($id = 0){
		$this->del($id);
		return redirect()->back();
	}
	
	public function action(Request $request){
		if($request->input('id') ){
			foreach($request->input('id') as $k => $id){
				$this->del($id);
			}
		}
		return redirect()->back();
	}
	
	public function del($id = 0){
	
		Option::where('id',$id)->delete();
	}
	
}
