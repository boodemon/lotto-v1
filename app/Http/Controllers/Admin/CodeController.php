<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Category;
use Request as Req;
use File;
use Excel;
class CodeController extends Controller
{
    public function index(){
		$rows = Category::type('code');
		if(Req::exists('keywords') ){
			$keywords = explode(' ', Req::input('keywords') );
			$rows = $rows->where( function($query) use ($keywords){
				foreach($keywords as $k => $key){
					$query = $query->where('name','like','%'. $key .'%');
				}
			});
		}
		
		$rows = $rows->paginate(24);		$data = [
			'subject' 	=> 'Product code management',
			'title' 	=> 'Code list',
			'actionUrl'	=>	'code-action',
			'rows'		=> $rows
		];
		
		return view('lostrip.category.code',$data);
	}
	
	public function form($id = 0){
		$row = Category::where('id',$id)->type('code')->first();
		$data = [
			'subject' 	=> 	'Code Data',
			'title' 	=> 	'Code form',
			'actionUrl'	=>	'code-form',
			'id'		=> 	$id,
			'type'		=> 	'code',
			'row'		=> 	$row,
		];
		
		return view('lostrip.category.code-form',$data);
	}
	
	public function formPost(Request $request){
		if($request->input('name')){
			foreach($request->input('name') as $key => $name){
				if( !empty( $name ) ){
					$ck = Category::where('id',$request->input('id'))->first();
					$row = $ck ? $ck : new Category;
					$row->name 	= $name;
					$row->code 	= $request->input('code.'. $key );
					$row->type 	= $request->input('type');
					$row->active = $request->has('active.'. $key) ? 'Y' : 'N';
					$row->save();
				}
			}
		}
		return redirect('code');
	}
	
	public function action(Request $request){
		if($request->input('id') ){
			foreach($request->input('id') as $k => $id){
				$this->del($id);
			}
		}
		return redirect()->back();
	}
	
	public function codeDelete($id = 0){
		$this->del($id);
		return redirect()->back();
	}
	
	public function del($id = 0){
		if($id != 0)
			Category::where('ref_id',$id)->delete();
		
		Category::where('id',$id)->delete();
	}
	
	public function import(){
		$file = $this->readxls();
		$data = [
			'subject' 	=> 	'Import code data',
			'title' 	=> 	'Import excel file',
			'actionUrl'	=>	'code-import',
			'type'		=> 	'code',
			'excels' 	=> $file,
			'rows'		=> $file ? $file->setActiveSheetIndex(0)->getHighestRow() : 0,
		];
		
		return view('lostrip.category.code-import',$data);
	}	
	
	public function generate(Request $request){
		$request->file('xls-file')->move(storage_path().'/_tmp/','import-code.xlsx');
		//echo '<pre>',print_r($request->all()),'</pre>';
		return redirect('code-import');
	}
	
	public function readxls(){
		$file =  storage_path() . '/_tmp/import-code.xlsx';
		return file_exists($file) ? Excel::selectSheetsByIndex(0)->load($file) : false;
	}
	
	public function codeImported(){
		$excels = $this->readxls();
		$rows 	= $excels ? $excels->setActiveSheetIndex(0)->getHighestRow() : 0;
		if($excels){
			$excel = $excels->getActiveSheet();
			for($x = 2; $x <= $rows; $x++){
				$code = $excel->getCell( 'A' . $x )->getValue();
				$name = $excel->getCell( 'B' . $x )->getValue();
				$ck = Category::type('code')->code($code)->first();
				$row = $ck ? $ck : new Category;
				$row->code = $code;
				$row->name = $name;
				$row->type = 'code';
				$row->save();
				
				//echo $code .' => ' . $name .'<br/>';
			}
			$this->delfile($excels);
		}
		return redirect('code');
	}
	public function codeCancel(){
		$this->delfile();
		return redirect()->back();
	}
	
	public function delfile(){
		$file =  storage_path() . '/_tmp/import-code.xlsx';
		@File::delete($file);
	}
}
