<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
use App\Models\Category;
use App\Models\Product;
use App\Models\Option;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
		$data = [
			'subject' 	=> 'Management products',
			'title' 	=> 'Products list',
			'actionUrl'	=> 'product-action',
		];
		
		return view('lostrip.products.index',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
		$data = [
			'subject' 	=> 'Add new product',
			'title' 	=> 'Product form',
			'actionUrl'	=> 'products',
			'id'		=> 0,
			'type'		=> 'option-data',
			'row'		=> false,
			'refID'		=> 0,
			'category_id' 	=>  Category::mainopt('id','name',0),
			'options'	=>	$this->optionList(0),
			'input'		=> ['textbox','textdate'],
		];
		
		return view('lostrip.products.form',$data);
    }
	
	public function optionList($selected = ''){
		return Category::type('option')->active()->orderby('id')->get();
	}

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
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
