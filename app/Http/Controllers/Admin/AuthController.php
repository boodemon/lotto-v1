<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
use App\Models\Logs;

class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
		if(!Auth::guard('admin')->guest()){return redirect('dashboard');
		}else{
			//echo 'GUEST!!';
		}
        return view('lotto.login');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
		//echo '<pre>',print_r($request->all()),'</pre>';
		
		$user = $request->input('username');
		$password = $request->input('password');
		$email 	= ['email' => $user, 'password' => $password];
		$username 	= ['username' => $user, 'password' => $password];
		if( Auth::guard('admin')->attempt($email) || Auth::guard('admin')->attempt($username)){
			Logs::activity(Auth::guard('admin')->user()->name . ' Login system');
			$link = Auth::guard('admin')->user()->level == 'admin' ? 'dashboard' : 'customers';
			return redirect($link);
		}else{
			Logs::activity( $user . ' Login username or password false');
			return redirect()->back()->withErrors(['error' => 'Username or password false']);
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
