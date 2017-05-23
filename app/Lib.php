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
						.'<span class="tang">'. $data['tang'] .'</span>';
				if( !empty( $data['tod'] ) )
					$txt .= ' x <span class="tod">' . $data['tod'] .'</span>';
				$txt .= '</span>';
			}
		}
			
		return $txt;
		
	}
	
	public static function shippingStatus($status = null){
		$res = [
			'cancle' => '<span class="color-red">ยกเลิกรายการ</span>',
			'pending' => '<span class="color-red">ส่งคำสั่งซื้อ</span>',
			'packing' => '<span class="color-brows">บรรจุเตรียมส่ง</span>',
			'sending' => '<span class="color-blue">ระหว่างจัดส่ง</span>',
			'success' => '<span class="color-green">ส่งสินค้าเรียบร้อยแล้ว</span>',
		];
		return isset($res[$status]) ? $res[$status] : false;
	}
	
	public static function price($discount = 0, $price = 0){
		if($discount > 0){
			
		}
		return $discount > 0 ? '<span class="discount">'. Lib::nb($price,2) .'</span><br/><span class="price">'. Lib::nb($discount,2) .'</span>' : '<span class="price">'. Lib::nb( $price ,2) .'</span>';
	}
	
	public static function stock($type = null){
		$arr = [
			'N' => '<span class="color-red">สินค้าหมด</span>',
			'Y' => '<span class="color-green">มีสินค้า</span>',
		];
		return isset($arr[$type]) ? $arr[$type] : false;
	}
	
	public static function level($key = ''){
		$arr = [
			'admin' 	=> 'Administrator',
			'sale' 		=> 'Sale',
			'account' 	=> 'Account',
			'op' 		=> 'Operations',
		];
		return isset($arr[$key]) ?  $arr[$key] : ucfirst($key); 
	}
	
	public static function icon($key = '',$title = ''){
		$icon = [
			'textbox' 	=> '<div class="form-control"></div>',
			'textdate' 	=> '<div class="input-group" style="width:140px; margin:auto;">'
                                .'<span class="add-on input-group-addon"><i class="glyphicon glyphicon-calendar fa fa-calendar"></i></span>'
                                .'<div class="form-control"></div>'
                              .'</div>',
			'radiobox' 	=> '<i class="fa fa-dot-circle-o"></i>',
			'checkbox' 	=> '<i class="fa fa-check-square-o"></i>',
			'on-hold'			=>	'<i class="fa fa-minus-circle on-hold wcIcon" title="รายการใหม่ '. $title .'" data-html="true"></i>',
			'pending'			=>	'<i class="fa fa-credit-card pending wcIcon" title="ชำระผ่านเน็ต '. $title .'" data-html="true"></i>',
			'remittance'		=>	'<i class="fa fa-clock-o remittance wcIcon" title="รอการโอนเงิน '. $title .'" data-html="true"></i>',
			'confirmation'		=>	'<i class="fa fa-thumbs-o-up confirmation wcIcon" title="รอตรวจสอบ '. $title .'" data-html="true"></i>',
			'processing'		=>	'<i class="fa fa-ellipsis-h processing wcIcon" title="กำลังดำเนินการ '. $title .'" data-html="true"></i>',
			'delay'				=>	'<i class="fa fa-calendar delay wcIcon" title="OP ช้า '. $title .'" data-html="true"></i>',
			'standard'			=>	'<i class="fa fa-circle-o standard wcIcon" title="OP ปรกติ '. $title .'" data-html="true"></i>',
			'urgent'			=>	'<i class="fa fa-warning urgent wcIcon" title="OP ด่วน '. $title .'" data-html="true"></i>',
			'waiting'			=>	'<i class="fa fa-plug waiting wcIcon" title="รอสินค้า '. $title .'" data-html="true"></i>',
			'shipment'			=>	'<i class="fa fa-truck shipment wcIcon" title="จัดส่งสินค้า '. $title .'" data-html="true"></i>',
			'collecting'		=>	'<i class="fa fa-home collecting wcIcon" title="รับสินค้า '. $title .'" data-html="true"></i>',
			'amendment'			=>	'<i class="fa fa-wrench amendment wcIcon" title="กำลังแก้ไข '. $title .'" data-html="true"></i>',
			'completed'			=>	'<i class="fa fa-check-circle completed wcIcon" title="เรียบร้อยแล้ว '. $title .'" data-html="true"></i>',
			'cancelled'			=>	'<i class="fa fa-times-circle cancelled wcIcon" title="ยกเลิก '. $title .'" data-html="true"></i>',
			'refunded'			=>	'<i class="fa fa-retweet refunded wcIcon" title="คืนเงิน '. $title .'" data-html="true"></i>',
			'failed'			=>	'<i class="fa fa-exclamation-circle failed wcIcon" title="ไม่สำเร็จ '. $title .'" data-html="true"></i>',
			'floating'			=>	'<i class="fa fa-money floating wcIcon" title="ยอดโอนที่ลูกค้ายังไม่แจ้ง  '. $title .'" data-html="true"></i>',
			
			// sale status icon //
			'new' 		=> '<i class="fa sale-status-icon wcIcon  new" title="New '. $title .'" data-html="true">N</i>',
			'wl' 		=> '<i class="fa sale-status-icon wcIcon  wl" title="W/L '. $title .'" data-html="true">W</i>',
			'hold' 		=> '<i class="fa sale-status-icon wcIcon  hold" title="Hold '. $title .'" data-html="true">HO</i>',
			'help' 		=> '<i class="fa sale-status-icon wcIcon  help" title="Help '. $title .'" data-html="true">HE</i>',
			'cancel' 		=> '<i class="fa sale-status-icon wcIcon  cancel" title="Cancel '. $title .'" data-html="true">C</i>',
			'done' 		=> '<i class="fa sale-status-icon wcIcon  done" title="Done '. $title .'" data-html="true">D</i>',
			'rf' 		=> '<i class="fa sale-status-icon wcIcon  rf" title="Refund '. $title .'" data-html="true">RF</i>',
			// op status icon //
			'quick' 		=> '<i class="fa fa-warning color-red wcIcon  quick" title="OP ด่วน '. $title .'" data-html="true"></i>',
			'normal' 		=> '<i class="fa fa-circle-o  color-green wcIcon normal" title="OP ปรกติ '. $title .'" data-html="true"></i>',
			'wifi' 		=> '<i class="fa fa-wifi color-orange wcIcon  wifi" title="WIFI '. $title .'" data-html="true"></i>',
			];
		return isset($icon[$key]) ? $icon[$key] : $key ;
	}
	
	public static function paidType($type = ''){
		$arr = [
			"deposit"	=> 'มัดจำ',
			"change"	=> 'ทอน',
			"return"	=> 'คืน',
			"refund"	=> 'รีฟัน',
		];
		return isset($arr[$type]) ? $arr[$type] : $arr['deposit'] ;
	}
	
	public static function filter($arr,$key){
		return ( $arr && !in_array($key,$arr) ) ? 'hide' : '';
	}

	
	public static function webname($web = null){
		return str_replace('http://','', url('/') );
	}
}