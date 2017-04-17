<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use DateTime;
use DateTimeZone;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Woocommerce;
use File;
class WcmController extends Controller
{
    public function index(){
		$wc = Woocommerce::get('orders');
		echo '<pre>',print_r($wc) ,'</pre>';
	}
    public function getid($id = 0){
		$wc = Woocommerce::get('orders/'.$id);
		echo '<pre>',print_r($wc) ,'</pre>';
	}    
	
	public function notes($id = 0){
		$notes = Woocommerce::get('orders/'.$id .'/notes',['note'=>'Omise']);
		$txt = [];
		if( $notes ){
			foreach( $notes as $note){
				if( strpos($note['note'] ,'Omise') !== false ){
					$txt[] = [ 
						'created_at' => $note['date_created'],
						'note' => $note['note'],
							 ];
				}
					//echo $note['note'] .'<br/>';
			}
		}
		echo '<pre>',print_r($notes) ,'</pre>';
		return $txt[0]['note'];
	}
	
	public function sub($id = 0){
		$wc = Woocommerce::get('subscriptions/'. $id .'/orders');
		echo '<pre>',print_r($wc) ,'</pre>';
	}
	
	public function setTime($datetime = '0000-00-00T00:00:00'){
		$ntime = new DateTime($datetime);
		$zone  = new DateTimeZone('Asia/Bangkok');
		$ntime->setTimezone($zone);
		return date('H:i',strtotime($datetime .' + 7 hours'));
		
	}
	
	public function filelist(){
		$files = File::allFiles( storage_path() .'/_tmp' );
		if($files){
			foreach($files as $file){
				$filename = str_replace(storage_path() .'/_tmp/','',$file);
				echo '<div style="padding:4px; float:left; width:10%;"><a href="'. url( 'file-read/'. $filename ) .'" target="_blank">'. $filename .'</a></div>';
			}
		}
	}
	public function readtext($name = ''){
		$file = storage_path() . '/_tmp/' . $name;
		if(!file_exists($file) ) return 'File not found!!';
		
		$txts = File::get( $file );
		$js = get_object_vars( json_decode( $txts ) );
		echo '<pre>',print_r($js) ,'</pre>';
		
	}
}
