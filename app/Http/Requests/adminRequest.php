<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Contracts\Validation\Validator;
class adminRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
			'email' 	=> 	'required|email|unique:users',
			'username' 	=> 	'required|alpha|unique:users',
			'name'		=> 	'required',
			'password'	=> 	'required'
        ];
    }
	
	public function messages(){
		return [
			'email.required' => 	'กรุณาทำการป้อน E-mail',
			'email.email' 	=> 	'รูปแบบ Email ไม่ถูกต้อง',
			'email.unique' 	=> 	'E-mail นี้ถูกใช้ไปก่อนหน้านี้แล้ว',
			'username.required' 	=> 	'กรุณาทำการป้อน Username',
			'username.alpha' 	=> 	'Username ต้องประกอบไปด้วย A-Z, a-z , 1-9 เที่านั้น ',
			'username.unique' 	=> 	'ชื่อผู้ใช้นี้ถูกใช้งานไปก่อนหน้านี้แล้ว',
			'name'		=> 	'กรุณาป้อนชื่อ',
			'password'	=> 	'กรุณาป้อนรหัสผ่าน'
		];
	}
}
