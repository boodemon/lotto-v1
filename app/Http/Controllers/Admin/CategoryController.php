<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Category;
use Request as Req;

class CategoryController extends Controller
{
	/*
		================================================================================
		Start main category 
		================================================================================
	*/
	public function category(){
		$rows = Category::ref(0)->type('category');
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
			'subject' 	=> 'Main Category',
			'title' 	=> 'Category list',
			'actionUrl'	=>	'category-action',
			'rows'		=> $rows
		];
		
		return view('lostrip.category.main',$data);
	}
	
	public function categoryForm($id = 0){
		$row = Category::where('id',$id)->first();
		$data = [
			'subject' 	=> 'Main Category manage',
			'title' 	=> 'Category form',
			'actionUrl'	=> 'category-form',
			'id'		=> $id,
			'type'		=> 'category',
			'row'		=> $row
		];
		
		return view('lostrip.category.main-form',$data);
	}
	
	public function categoryPost(Request $request){
		//echo '<pre>', print_r($request->all()),'</pre>';
		if($request->input('name')){
			foreach($request->input('name') as $key => $name){
				if( !empty( $name ) ){
					$ck = Category::where('id',$request->input('id'))->first();
					$row = $ck ? $ck : new Category;
					$row->name 	= $name;
					$row->type 	= $request->input('type');
					$row->active = $request->has('active.'. $key) ? 'Y' : 'N';
					$row->save();
				}
			}
		}
		return redirect('category');
	}
	
	public function categoryAction(Request $request){
		if($request->input('id') ){
			foreach($request->input('id') as $k => $id){
				$this->del($id);
			}
		}
		return redirect()->back();
	}
	
	public function CategoryDelete($id = 0){
		$this->del($id);
		return redirect()->back();
	}
	
	public function del($id = 0){
		if($id != 0)
			Category::where('ref_id',$id)->delete();
		
		Category::where('id',$id)->delete();

	}
	
	/* 
		=============================================================================
		Start main category 
		=============================================================================
	*/
	public function option(){
		$rows = Category::type('option');
		if(Req::exists('keywords') ){
			$keywords = explode(' ', Req::input('keywords') );
			$rows = $rows->where( function($query) use ($keywords){
				foreach($keywords as $k => $key){
					$query = $query->where('name','like','%'. $key .'%');
				}
			});
		}
		
		$rows = $rows->paginate(24);		$data = [
			'subject' 	=> 'Option management',
			'title' 	=> 'Option list',
			'actionUrl'	=>	'category-action',
			'rows'		=> $rows
		];
		
		return view('lostrip.category.option',$data);
	}
	
	public function optionForm($id = 0){
		$row = Category::where('id',$id)->type('option')->first();
		$data = [
			'subject' 	=> 	'Option Data',
			'title' 	=> 	'Option form',
			'actionUrl'	=>	'option-form',
			'id'		=> 	$id,
			'type'		=> 	'option',
			'row'		=> 	$row,
			'category' 	=>  Category::mainopt('id','name',$id),
		];
		
		return view('lostrip.category.option-form',$data);
	}
	
	public function optionPost(Request $request){
		echo '<pre>',print_r( $request->all()),'</pre>';
		if($request->input('name')){
			foreach($request->input('name') as $key => $name){
				if( !empty( $name ) ){
					$ck = Category::where('id',$request->input('id'))->first();
					$row = $ck ? $ck : new Category;
					$row->name 	= $name;
					$row->input	= $request->input('input-type');
					$row->type 	= $request->input('type');
					$row->active = $request->has('active.'. $key) ? 'Y' : 'N';
					$row->save();
				}
			}
		}
		return redirect('option');	
	}
}
