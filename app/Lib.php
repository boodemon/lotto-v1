<?php namespace App;

use Illuminate\Http\Request;
use Request as Req;
use Closure;
use File;
use App;
class Lib {

	public static function datetime($strDate = '0000-00-00 00:00:00'){
		if($strDate == '0000-00-00 00:00:00') return false;
		
		$strYear = date("Y",strtotime($strDate))+543;
		$strMonth= date("n",strtotime($strDate));
		$strDay= date("j",strtotime($strDate));
		$strHor= date("H",strtotime($strDate));
		$strMinute= date("i",strtotime($strDate));
		$strSeconds= date("s",strtotime($strDate));
		$strMonthCut = Array("","มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม","มิถุนายน","กรกฎาคม","สิงหาคม","กันยายน","ตุลาคม","พศจิกายน","ธันวาคม");
		$strMonthThai=$strMonthCut[$strMonth];

		return "$strDay $strMonthThai $strYear   $strHor:$strMinute";
	}
	
	public static function xTimeAgo ($oldTime) {
        $timeCalc = time() - strtotime($oldTime);  
		
        if ($timeCalc >= (60*60*24*2)) {
            $timeType = "full";
        }else if ($timeCalc >= (60*60*24)) {
            $timeType = "d";
        }else if ($timeCalc >= (60*60)) {
            $timeType = "h";
        }else{//$timeCalc <= 60) {
            $timeType = "m";
        }
        
		
        if ($timeType == "s") {
            $timeCalc .= " seconds ago";
        }
        if ($timeType == "m") {
            $timeCalc = round($timeCalc/60) . " นาที ที่แล้ว";
        }        
        if ($timeType == "h") {
            $timeCalc = round($timeCalc/60/60) . " ชั่วโมง ที่แล้ว";
        }
        if ($timeType == "d") {
            $timeCalc = round($timeCalc/60/60/24) . " วัน ที่แล้ว";
        }
		if($timeType == 'full'){
			$timeCalc = date('d/m/Y H:i',strtotime($oldTime));
		}
        return $timeCalc;
    }
	
	public static function datemonth($date = ''){
		if($date == '') return '-';
		$dx = explode('-',$date);
		$dm = $dx[1];
		$dy = $dx[0];
		$month = ['','01' => 'ม.ค.','02' => 'ก.พ.','03' => 'มี.ค.','04' => 'เม.ย.','05' => 'พ.ค.','06' => 'มิ.ย.','07' => 'ก.ค.','08' => 'ส.ค.','09' => 'ก.ย.','10' => 'ต.ค','11' => 'พ.ย.','12' => 'ธ.ค.'];
		return $month[$dm].' '. substr(($dy + 543),2,2);
	}
	
	public static function dateDep($date = ''){
		$dx = strtotime($date);
		//echo $dx;
		if( $dx <= 0 ) return false;
		return date('d-M',strtotime($date));
	}
	
	public static function pmtDate($date = ''){
		$dx = strtotime($date);
		//echo $dx;
		if( $dx <= 0 ) return false;
		$d = ['Su','M','T','W','Th','F','Sa'];
		return $d[date('w',strtotime($date) )] . date('n/j',strtotime($date));
	}
	
	public static function datetimeThai($strDate = ''){
		if($strDate == '0000-00-00 00:00:00') return false;
		
		$strYear = date("Y",strtotime($strDate))+543;
		$strMonth= date("n",strtotime($strDate));
		$strDay= date("j",strtotime($strDate));
		$strHor= date("H",strtotime($strDate));
		$strMinute= date("i",strtotime($strDate));
		$strSeconds= date("s",strtotime($strDate));
		$strMonthCut = Array("","มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม","มิถุนายน","กรกฎาคม","สิงหาคม","กันยายน","ตุลาคม","พศจิกายน","ธันวาคม");
		$strMonthThai=$strMonthCut[$strMonth];

		return "$strDay $strMonthThai $strYear เวลา $strHor:$strMinute:$strSeconds";
	}		
	
	public static function shortdatetimeThai($strDate = ''){
		if($strDate == '0000-00-00 00:00:00') return false;
		
		$strYear = date("Y",strtotime($strDate));
		$strMonth= date("n",strtotime($strDate));
		$strDay= date("j",strtotime($strDate));
		$strHor= date("H",strtotime($strDate));
		$strMinute= date("i",strtotime($strDate));
		$strSeconds= date("s",strtotime($strDate));
		$strMonthCut = Array("","ม.ค.","ก.พ.","มี.ค.","เม.ย.","พ.ค.","มิ.ย.","ก.ค.","ส.ค.","ก.ย.","ต.ค.","พ.ย.","ธ.ค.");
		$strMonthThai=$strMonthCut[$strMonth];

		return "$strDay $strMonthThai $strYear เวลา $strHor:$strMinute:$strSeconds";
	}		
	
	public static function dateThai($strDate = ''){
		if($strDate == '0000-00-00 00:00:00') return false;
		
		$strYear = date("Y",strtotime($strDate));
		$strMonth= date("n",strtotime($strDate));
		$strDay= date("j",strtotime($strDate));
		$strHor= date("H",strtotime($strDate));
		$strMinute= date("i",strtotime($strDate));
		$strSeconds= date("s",strtotime($strDate));
		$strMonthCut = Array("","ม.ค.","ก.พ.","มี.ค.","เม.ย.","พ.ค.","มิ.ย.","ก.ค.","ส.ค.","ก.ย.","ต.ค.","พ.ย.","ธ.ค.");
		$strMonthThai=$strMonthCut[$strMonth];

		return "$strDay $strMonthThai $strYear ";
	}
	
	public static function monthYear($strDate = ''){
		if($strDate == '0000-00-00 00:00:00') return false;
		
		$strYear = date("Y",strtotime($strDate));
		$strMonth= date("n",strtotime($strDate));
		$strDay= date("j",strtotime($strDate));
		$strHor= date("H",strtotime($strDate));
		$strMinute= date("i",strtotime($strDate));
		$strSeconds= date("s",strtotime($strDate));
		$strMonthCut = Array("","มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม","มิถุนายน","กรกฎาคม","สิงหาคม","กันยายน","ตุลาคม","พศจิกายน","ธันวาคม");
		$strMonthThai=$strMonthCut[$strMonth];

		return "$strMonthThai $strYear";
	}
	
	public static function fulldate($strDate = ''){
		if($strDate == '0000-00-00 00:00:00') return false;
		return date('l d F Y', strtotime($strDate));
	}
	
	public static function nbshow($a,$dec = 0,$cm = ','){
		$a = floatval($a);
		if(empty($a)){
			return '-';
		}else{
			return $dec != 0 ? number_format($a,$dec,'.',$cm): number_format($a,$dec,'',$cm);
		}
	}
	
	public static function nb($a,$dec = 0,$cm = ','){
		$a = floatval($a);
		if(empty($a)){
			return 0;
		}else{
			return $dec != 0 ? number_format($a,$dec,'.',$cm): number_format($a,$dec,'',$cm);
		}
	}
	
	public static function number($a,$dec = 0,$cm = ','){
		$a = floatval($a/100);
		if(empty($a)){
			return 0;
		}else{
			return $dec != 0 ? number_format($a,$dec,'.',$cm): number_format($a,$dec,'',$cm);
		}
	}
	
	public static function toNumber($number = ''){
		return str_replace(',','',$number);
	}
	
	public static function encodelink($value=''){
		$link = strtolower($value);
		$link = str_replace(' ', '-', $link);
		$link = str_replace('/', '-', $link);
		$link = str_replace('%', '-', $link);
		$link = str_replace('*', '-', $link);
		$link = str_replace('&', '-', $link);
		$link = str_replace('+', '-', $link);
		$link = str_replace('?', '-', $link);
		$link = str_replace('=', '-', $link);
		$link = str_replace('+', '-', $link);
		$link = str_replace('#', '-', $link);
		$link = str_replace(',', '-', $link);
		$link = str_replace(';', '-', $link);
		$link = str_replace('@', '', $link);
		$link = str_replace('!', '', $link);
		$link = str_replace('?', '', $link);
		$link = str_replace('<', '', $link);
		$link = str_replace('>', '', $link);
		$link = str_replace('\"', '', $link);
		$link = str_replace('(', '', $link);
		$link = str_replace(')', '', $link);
		return $link;
	}

	public static function decodelink($value=''){
		$link = str_replace('-', ' ', $value);
		$link = str_replace('+', '/', $link);
		return $link;
	}
	
	public static function monththai($m = 1){
		$strMonthCut = Array("","มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม","มิถุนายน","กรกฎาคม","สิงหาคม","กันยายน","ตุลาคม","พศจิกายน","ธันวาคม");
		return $strMonthCut[ (int)$m ];
	}
	
	public static function percent($number = 0, $full = 0){
		return ($number /  $full ) * 100 ;
	}
	
	public static function createFolder($image){
		$ex = explode('/',$image);
		$f 	= count($ex)-2;
		$path = [];
		for($i=0; $i <= $f; $i++){
			$path[] = $ex[$i];
		}
		$folder = implode('/',$path);
		//echo $folder;
		if(!file_exists($folder)){
			File::makeDirectory($folder,0777,true);
		}
	}
	
	public static function makeFolder($folder){
		//echo $folder;
		if(!file_exists($folder)){
			File::makeDirectory($folder,0777,true);
		}
	}
	
	public static function active($status = 'N'){
		return ( $status == 'Y' || $status == 1 ) ? '<i class="fa fa-check-circle color-green"></i>' : '<i class="fa fa-exclamation-circle color-red"></i>';
	}
	
	public static function status($status = 'N'){
		return ( $status == 'Y' || $status == 1 ) ? '<i class="fa fa-check-circle color-green"></i>' : '';
	}
	
	
	public static function tag($text = ''){
		$keys = explode(',' , $text);
		$txt = '<ul class="tags list-inline">';
		if($keys){
			foreach($keys as $k => $key){
				$txt .= '<li class="tag"><strong><a href="'. Req::fullUrl() .'" title="'. $key .'">'. $key .'</strong></a></li>'. "\n\t";
			}
		}
		$txt .= '</ul>';
		return $txt;
	}
	
	public static function numberTag($json = []){
		//echo '<pre>',print_r($json),'</pre>';
		$txt = '';
		
		if($json){
			foreach($json as   $data){
				$txt .= '<span class="number-tag"><span class="number"><strong>'. $data['number'] .'</strong> = </span>'
						.'<span class="tang">'. ( ( $data['wingup'] == 'Y' || $data['wingdown'] == 'Y' ) ? ( ( $data['wingup'] == 'Y' ? ' วิงบน ' . $data['tang'] : '' ) . ( $data['wingdown'] == 'Y' ? ' วิงล่าง '. $data['tang'] : '' ) ) : $data['tang']  ) .'</span>';
				if( !empty( $data['tod'] ) )
					$txt .= ' x <span class="tod">' . $data['tod'] .'</span>';
				$txt .= '</span>';
			}
		}
			
		return $txt;
		
	}
	

	public static function filter($arr,$key){
		return ( $arr && !in_array($key,$arr) ) ? 'hide' : '';
	}

	
	public static function webname($web = null){
		return str_replace('http://','', url('/') );
	}
}