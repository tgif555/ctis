<?php 
function mssql_escape($s) {
	if (is_null($s) || $s == "") {
		$o="";
	}
	else { 
		$o = str_replace("'","''",trim($s));
		$o = str_replace("--","",$o);
	}
	return $o;
}
function html_escape($value) {
	$v = (string)$value;
	if (is_null($v) || $v == "") {
		return "";
	}
	else {
		return htmlspecialchars($v,ENT_QUOTES);
	}
}
function html_quot($q) {
	
	if (is_null($q)) {$o="";}
	else {
		if (is_numeric($q)) {$o=$q;}
		else {$o = str_replace('"',"&quot;",trim($q));}
	}
	return $o;
}
function number_fmt($num,$decimals = 2,$thousands_sep = ",",$fixdecimal = false) {
	//decimals จำนวนเลขหลังจุดทศนิยม
	//thousands_sep ตัวแบ่งหลักพัน
	//fixdecimal ถ้าต้องการ fix จำนวนจุดทศนิยมในกรณีที่เป็น integer
	if (!empty($num)) {
		if (((double)$num - (int)$num) > 0.00) {
			return number_format($num,$decimals,".",$thousands_sep);
		} else {
			if ($fixdecimal) {
				return number_format($num,$decimals,".",$thousands_sep);
			}else {
				return number_format($num,0,".",$thousands_sep);
			}
		}
	}
	else {
		return "0";
	}
}
function ymdsql($strdate) {
	//get from format dd/mm/yyyy
	$d = substr($strdate,0,2);
	$m = substr($strdate,3,2);
	$y = substr($strdate,6,4);
	return $y."-".$m."-".$d;
}

function isservonline($ip) {
	$ch = curl_init($ip);
	curl_setopt($ch, CURLOPT_TIMEOUT, 5);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$data = curl_exec($ch);
	$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	curl_close($ch);
	if($httpcode>=200 && $httpcode<300){
		return true;
	} else {
		return false;
	}
}
function getnewid($id, $table, $conn) {
	$sql = "select max($id) as id from " . $table;		
	$result = sqlsrv_query($conn, $sql);		
	$row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
	if (!$row) {
		return 1;
	}
	else {
		return $row["id"] + 1;
	}
}

function getnewseq($seq, $table, $conn) {
	$sql = "select max($seq) as seq from " . $table;		
	$result = sqlsrv_query($conn, $sql);		
	$row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
	if (!$row) {
		return 1;
	}
	else {
		return $row["seq"] + 1;
	}
}
function getnewseqbycon($seq,$table,$condition,$conn) {
	$sql = "select max($seq) as seq from " . $table . " where " . $condition;
	$result = sqlsrv_query($conn, $sql);		
	$row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
	if (!$row) {
		return 1;
	}
	else {
		return $row["seq"] + 1;
	}
}
function getqtmnbr($type,$conn) {
	//QT-YYMM-0001
	$tym = strtoupper($type).date('ym');
	$sql = "select max(substring(qtm_nbr,9,4)) as nbr from qtm_mstr where substring(qtm_nbr,1,7) = '$tym'";	
	$result = sqlsrv_query($conn, $sql);	
	$row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);		
	if ($row) {
		if (is_null($row['nbr'])) {
			$next_numner = 1;
		}
		else {
			$next_numner = $row['nbr'] + 1;
		}
	}
	else {
		$next_numner = 1;
	}
	$qtm_nbr = $tym."-".substr("0000{$next_numner}", -4);
	return $qtm_nbr;
}
//ID for sptd_det
function getnewqtddetid($qtm_nbr,$conn) {
	//QT-2001-0001-001
	$sql = "select max(substring(qtd_id,14,3)) as seq from qtd_det where qtd_qtm_nbr = '$qtm_nbr'";		
	$result = sqlsrv_query($conn, $sql);		
	$row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
	if (!$row) {		
		$id = 1;
	}
	else {		
		$id = $row["seq"] + 1;
	}
	return $qtm_nbr."-".substr("000{$id}", -3);	
}
function getaucmnumber($qtm_nbr,$conn) {
	//QA-YYMM-0000-001
	$prefix_aucm_nbr = str_replace("QT","QA",$qtm_nbr)."-";
	$sql = "select max(substring(aucm_nbr,14,3)) as nbr from aucm_mstr where substring(aucm_nbr,1,13) = '$prefix_aucm_nbr'";
	$result = sqlsrv_query($conn, $sql);	
	$row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);		
	if ($row) {
		if (is_null($row['nbr'])) {
			$next_numner = 1;
		}
		else {
			$next_numner = $row['nbr'] + 1;
		}
	}
	else {
		$next_numner = 1;
	}
	$aucm_nbr = $prefix_aucm_nbr.substr("000{$next_numner}", -3);
	return $aucm_nbr;
}
function getaucdid($aucm_nbr,$conn) {
	//QT-2001-0001-001
	
	$sql = "select max(substring(aucd_id,18,3)) as seq from aucd_det where aucd_aucm_nbr = '$aucm_nbr'";
	$result = sqlsrv_query($conn, $sql);		
	$row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
	if (!$row) {		
		$id = 1;
	}
	else {		
		$id = $row["seq"] + 1;
	}
	return $aucm_nbr."-".substr("000{$id}", -3);
}
function cntqtddet($qtm_nbr,$conn) {
	$sql = "SELECT count(*) 'cnt_record' FROM sptd_det WHERE qtd_qtm_nbr = '$qtm_nbr'" .
		
	$result = sqlsrv_query($conn, $sql); 
	$row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);		
	if ($row) {
		$total_record = (int)$row['total_record'];
	}
	else {
		$total_record = 0;
	}	
	return $total_record;
}
function get_new_qtap_approval_id($qtm_nbr,$conn) {			
	$sql = "select max(substring(qtap_id,13,3)) as seq from qtap_approval where qtap_qtm_nbr = '$qtm_nbr'";		
	$result = sqlsrv_query($conn, $sql);		
	$row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
	if (!$row) {		
		$id = 1;
	}
	else {		
		$id = $row["seq"] + 1;
	}
	return substr("000{$id}", -3);	
}

function getnewcustpayid($qtm_nbr,$conn) {
	//CT-2001-0001-001
	$sql = "select max(substring(custpay_id,14,3)) as seq from custpay_det where custpay_qtm_nbr = '$qtm_nbr'";		
	$result = sqlsrv_query($conn, $sql);		
	$row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
	if (!$row) {		
		$id = 1;
	}
	else {		
		$id = $row["seq"] + 1;
	}
	return str_replace("QT","CT",$qtm_nbr)."-".substr("000{$id}", -3);
}
function getnewconspayid($qtm_nbr,$conn) {
	//CS-2001-0001-001
	$sql = "select max(substring(conspay_id,14,3)) as seq from conspay_det where conspay_qtm_nbr = '$qtm_nbr'";		
	$result = sqlsrv_query($conn, $sql);		
	$row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
	if (!$row) {		
		$id = 1;
	}
	else {		
		$id = $row["seq"] + 1;
	}
	return str_replace("QT","CS",$qtm_nbr)."-".substr("000{$id}", -3);
}

function inlist($pattern,$astr) {
    $xpattern = $pattern;
	if (strpos($xpattern,",")) {	
	   while (strpos($xpattern,",")) {		   
	      $pos = strpos($xpattern,",",0);		  
		  $stmt = substr($xpattern,0,$pos);		  
		  if ($stmt == $astr) {			 
			 return true;			 
		  }
		  $xpattern = substr($xpattern,$pos + 1,strlen($xpattern));		  
	   }
	}	
	if ($xpattern == $astr) {		
		return true;
	}	
	else {		
		return false;
	}	
}
function genzero($id,$digit,$lr) {
	if ($lr == "l" or $lr == "L") {
		return str_pad($id,$digit,"0",STR_PAD_LEFT);
	}
	else {
		return str_pad($id,$digit,"0",STR_PAD_RIGHT);
	}	
}
function findsqlval($table, $selectfield, $wfield, $vfield,$conn) {
	if (isset($vfield)) {
		$sql = "SELECT " . $selectfield . " AS fvalue FROM " . $table . " Where " . $wfield . "=" . "'" . $vfield . "'";		
		$result = sqlsrv_query($conn, $sql);	
		$row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);		
		if (!$row) {
			return "";
		}
		else {
			return $row["fvalue"];
		}
	}
	else {return "";}	
}
Function findsqlvalbycon($table, $selectfield, $wcondition,$conn) {
	if (isset($wcondition)) {		
		$sql = "SELECT " . $selectfield . " AS fvalue FROM " . $table . " Where " . $wcondition;		
		$result = sqlsrv_query($conn, $sql);
		$row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);		
		if (!$row) {
			return "";
		}
		else {
			return $row["fvalue"];
		}		
	}
}
function day($date) {return date("d",strtotime($date));}
function month($date) {return date("m",strtotime($date));}
function year($date) {return date("Y",strtotime($date));}
function today() {return date("d/m/Y");}
function dmydb($dbdate,$y) {
	if (!is_null($dbdate)) {
		//get from format in db
		if ($y=='Y') {
			return date_format($dbdate,'d/m/Y');
		} else {
			return date_format($dbdate,'d/m/y');
		}
	} else {return "";}
}
function dmyhmsdb($dbdate,$y) {
	if (!is_null($dbdate)) {
		//get from format in db
		if ($y=='Y') {
			return date_format($dbdate,'d/m/Y H:i:s');
		} else {
			return date_format($dbdate,'d/m/y H:i:s');
		}
	} else {return "";}
}
function cnvtodmyhmsdb($dbdate,$y) {
	if (!is_null($dbdate)) {
		//get from format in db
		if ($y=='Y') {
			return date_format($dbdate,'Y-m-d H:i:s');
		} else {
			return date_format($dbdate,'y-m-d H:i:s');
		}
	} else {return null;}
}

function dmytx($txdate) {
	if ($txdate!="") {
		//get from format yyyymmdd
		$d = substr($txdate,6,2);
		$m = substr($txdate,4,2);
		$y = substr($txdate,0,4);	
		return $d . "/" . $m . "/" . $y;
	} else {
		return "";
	}
}
function dmyty($txdate) {
	if ($txdate!="") {
		//get from format yyyymmdd
		$d = substr($txdate,6,2);
		$m = substr($txdate,4,2);
		$y = substr($txdate,2,2);	
		return $d . "/" . $m . "/" . $y;
	} else {
		return "";
	}
}
function ymd($strdate) {
	//get from format dd/mm/yyyy
	$d = substr($strdate,0,2);
	$m = substr($strdate,3,2);
	$y = substr($strdate,6,4);
	return $y . $m . $d;
}

function rand_str($length = 4, $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890') {
	$chars_length = (strlen($chars) - 1);
	$string = $chars{rand(0, $chars_length)};
	for ($i = 1; $i < $length; $i = strlen($string))
	{
		$r = $chars{rand(0, $chars_length)};
		if ($r != $string{$i - 1}) $string .=  $r;
	}
	return $string;
}

function getvalue($v) {
	if ($v == "" || $v == null) {
		return 0;
	}
	else {
		return $v;
	}
}

function base64_url_encode($input) {
	//return strtr(base64_encode($input), '+/=', '-_,');
	return base64_encode($input);
}
function base64_url_decode($input) {
	//return base64_decode(strtr($input, '-_,', '+/='));
	return base64_decode($input);
}

function encrypt($string,$txtkey) {
	$encrypt_method = "AES-256-CBC";
    $secret_key = $txtkey;
    $secret_iv = $txtkey;
    // hash
    $key = hash('sha256', $secret_key);
    // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
    $iv = substr(hash('sha256', $secret_iv), 0, 16);
	$output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
	//$output = strtr(base64_encode($output), '+/=', '-_,');
	$output = base64_encode($output);
	return $output;
}
function decrypt($encrypted,$txtkey) {
    $encrypt_method = "AES-256-CBC";
    $secret_key = $txtkey;
    $secret_iv = $txtkey;
    // hash
    $key = hash('sha256', $secret_key);
    // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
    $iv = substr(hash('sha256', $secret_iv), 0, 16);
	//$output = strtr(openssl_decrypt(base64_decode($encrypted), $encrypt_method, $key, 0, $iv),'-_,', '+/=');
	$output = openssl_decrypt(base64_decode($encrypted), $encrypt_method, $key, 0, $iv);
	return $output;
}

function mail_attachment($filename_attach, $filename_in_mail,$path, $mail_to, $from_mail, $from_name, $subject, $message) {
	$file = $path.$filename_attach;
	$file_size = filesize($file);
	$handle = fopen($file, "r");
	$content = fread($handle, $file_size);
	fclose($handle);

	$content = chunk_split(base64_encode($content));
	$uid = md5(uniqid(time()));
	$name = basename($file);

	$eol = PHP_EOL;

	$header = "From: ".$from_name." <".$from_mail.">\n";
    // $header .= "Reply-To: ".$replyto."\n";
    $header .= "MIME-Version: 1.0\n";
    $header .= "Content-Type: multipart/mixed; boundary=\"".$uid."\"\n\n";
    $emessage= "--".$uid."\n";
    //$emessage.= "Content-type:text/plain; charset=iso-8859-1\n";
	$emessage .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $emessage.= "Content-Transfer-Encoding: 7bit\n\n";
    $emessage .= $message."\n\n";
    $emessage.= "--".$uid."\n";
    $emessage .= "Content-Type: application/octet-stream; name=\"".$filename_in_mail."\"\n"; // use different content types here
    $emessage .= "Content-Transfer-Encoding: base64\n";
    $emessage .= "Content-Disposition: attachment; filename=\"".$filename_in_mail."\"\n\n";
    $emessage .= $content."\n\n";
    $emessage .= "--".$uid."--";
	$subject1 = "=?UTF-8?B?".base64_encode($subject)."?=";
	
    $result = mail($mail_to,$subject1,$emessage,$header);
	if($result) {
		return true;
	} else {
		return false;
	}
	
	//return true;
	/*
	ตัวอย่างการใช้งาน
	$my_file = "2562_07_09-10.pdf";
	$my_path = "d:/appserv/www/testmail/f/";
	$my_name = "Komsun";
	$my_mail = "komsunyu@scg.com";
	$my_replyto = "komsunyu@scg.com";
	$my_subject = "This is a mail with attachment.";
	$my_message = "Hallo,rndo you like this script? I hope it will help.rnrngr. Olaf";
	mail_attachment($my_file, "S19000000011.pdf",$my_path, "komsunyu@scg.com", $my_mail, $my_name, $my_replyto, $my_subject, $my_message);
	*/
}

function mail_normal($from_name,$from_mail,$mail_to,$email_subject,$message) {
	$headers = "MIME-Version: 1.0" . "\r\n";
	$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
	$headers .= 'From: '.$from_name.'<'.$from_mail.'>'.'\r\n';
	$subject1 = "=?UTF-8?B?".base64_encode($email_subject)."?=";
	
	$result = mail($mail_to,$subject1,$message,$headers);
	if($result) {
		return true;
	} else {
		return false;
	}
	
	//return true;
}

function gen_uuid() {
	$uuid = array(
	 'time_low'  => 0,
	 'time_mid'  => 0,
	 'time_hi'  => 0,
	 'clock_seq_hi' => 0,
	 'clock_seq_low' => 0,
	 'node'   => array()
	);

	$uuid['time_low'] = mt_rand(0, 0xffff) + (mt_rand(0, 0xffff) << 16);
	$uuid['time_mid'] = mt_rand(0, 0xffff);
	$uuid['time_hi'] = (4 << 12) | (mt_rand(0, 0x1000));
	$uuid['clock_seq_hi'] = (1 << 7) | (mt_rand(0, 128));
	$uuid['clock_seq_low'] = mt_rand(0, 255);

	for ($i = 0; $i < 6; $i++) {
	  $uuid['node'][$i] = mt_rand(0, 255);
	}

	$uuid = sprintf('%08x-%04x-%04x-%02x%02x-%02x%02x%02x%02x%02x%02x',
	 $uuid['time_low'],
	 $uuid['time_mid'],
	 $uuid['time_hi'],
	 $uuid['clock_seq_hi'],
	 $uuid['clock_seq_low'],
	 $uuid['node'][0],
	 $uuid['node'][1],
	 $uuid['node'][2],
	 $uuid['node'][3],
	 $uuid['node'][4],
	 $uuid['node'][5]
	);
	return $uuid;
}
function day_diff($fromdate,$todate) {
	//para1 yyyymmdd, para2 = yyyymmdd
	$date1 = date_create(substr($fromdate,0,4).'-'.substr($fromdate,4,2).'-'.substr($fromdate,6,2));
	$date2 = date_create(substr($todate,0,4).'-'.substr($todate,4,2).'-'.substr($todate,6,2));
	$interval = $date2->diff($date1);
	return $interval->format('%a');
}
function isdate($date) {
	if (preg_match("/^(((0[1-9]|[12]\d|3[01])\/(0[13578]|1[02])\/((19|[2-9]\d)\d{2}))|((0[1-9]|[12]\d|30)\/(0[13456789]|1[012])\/((19|[2-9]\d)\d{2}))|((0[1-9]|1\d|2[0-8])\/02\/((19|[2-9]\d)\d{2}))|(29\/02\/((1[6-9]|[2-9]\d)(0[48]|[2468][048]|[13579][26])|((16|[2468][048]|[3579][26])00))))$/",$date)) {
		return true;
	} else {
		return false;
	}
}
function getFileType($file) {
    $ex = explode(".",$file);
    return $ex[1];
}
// function resizess($images,$new_images) {
	// $resizeObj = new resize($images);
	// $resizeObj -> resizeImage(200, 200, 'auto');
	// $resizeObj -> saveImage($new_images, 80); 
// }
function resizess($images,$new_images,$w,$h) 
{
	// *** 1) Initialise / load image
	$resizeObj = new resize($images);
	// *** 2) Resize image (options: exact, portrait, landscape, auto, crop)
	$resizeObj -> resizeImage($w, $h, 'auto');
	// *** 3) Save image
	$resizeObj -> saveImage($new_images, 100); 
}
function uploadfileimage ($srcfile,$folder,$prefix,$resizeflag) {
	$ext = explode(".",$_FILES[ $srcfile]['name']);		
	if(count($ext)>1) {
		switch (strtolower($ext[1])) {
			case "png":
				$type = "png";
				break;			
			case "jpg";
				$type = "jpg";
				break;	
			case "jpeg";
				$type = "jpg";
				break;
			default:
				$type = strtolower($ext[1]);				
				//parseError("Mime Type must be image/jpeg or image/png");
				break;
		}
	} else {
		$type = "jpg";	
	}	
	$firstpicname =  $prefix."_".date("ymd_his");
	$middlename = rand_str();	
	$name = $firstpicname.'_'.$middlename;
	$name = trim($name);
	
	$fullname = $name.".".$type;
	$thumname = $name."_thum".".".$type;
		
	//$destupload = $uploadPath.$folder."/".$fullname; 
	//$destthum = $uploadPath.$folder."/".$thumname;
	$destupload = $folder."/".$fullname; 
	$destthum = $folder."/".$thumname;

	if (move_uploaded_file($_FILES[$srcfile][ 'tmp_name' ], $destupload)) { 
		if ($type=="png" || $type=="jpg") {
			if (strtolower($resizeflag)=="resize") {
				resizess($destupload,$destthum,200,200);     
			}
		}
		return $fullname;
	} else {
		parseError("File upload failed");
	}
}
function uploadfiledata ($srcfile,$folder,$prefix) {
	//global $uploadPath;
	//$uploadPath = "../_fileupload/"; 	
	$ext = explode(".",$_FILES[$srcfile]['name']);
	//$type = strtolower($ext[1]);
	$sizeof_ext = count($ext);
	$type = "";
	if ($sizeof_ext >= 2) {
		$type = strtolower($ext[$sizeof_ext-1]);
	}
	$firstpicname =  $prefix."_".date("ymd_his");
	$middlename = rand_str();	
	$name = $firstpicname.'_'.$middlename;
	$name = trim($name);
	if ($type != "") {
		$fullname = $name.".".$type;
	} else {
		$fullname = $name;
	}

	$destupload = $folder."/".$fullname; 

	if (move_uploaded_file($_FILES[$srcfile]['tmp_name'], $destupload)) { 		
		return $fullname;
	} else {
		parseError("File upload failed");
	}
}

function uploadfileimage_fixwidth($srcfile,$folder,$prefix,$targetwidth,$thumnail_flag,$filename_flag) {
	$ext = explode(".",$_FILES[ $srcfile]['name']);		
	if(count($ext)>1) {
		switch (strtolower($ext[1])) {
			case "png":
				$type = "png";
				break;			
			case "jpg";
				$type = "jpg";
				break;	
			case "jpeg";
				$type = "jpg";
				break;
			default:
				$type = strtolower($ext[1]);				
				//parseError("Mime Type must be image/jpeg or image/png");
				break;
		}
	} else {
		$type = "jpg";	
	}	
	
	
	if ($filename_flag == "USE_PREFIX_FILENAME") {
		$name = $prefix;
	}
	elseif ($filename_flag == "USE_IMAGE_FILENAME") {
		$name = $prefix."_".trim($ext[0]);
	}
	else { //RANDOM FILE NAME
		$firstpicname =  $prefix."_".date("ymd_his");
		$middlename = rand_str();	
		$name = $firstpicname.'_'.$middlename;
	}
	$name = trim($name);
	$fullname = $name.".".$type;
	$thumname = $name."_thum".".".$type;

	$destupload = $folder."/".$fullname; 
	$destthum = $folder."/".$thumname;
	
	if (move_uploaded_file($_FILES[$srcfile]['tmp_name'], $destupload)) { 
		$getImageInfo = getimagesize($destupload);
		$actual_image_width = $getImageInfo[0];
		$actual_image_height = $getImageInfo[1];
		
		if ($type=="png" || $type=="jpg") {
			if (strtolower($thumnail_flag)=="thumnail") {
				resizess($destupload,$destthum,200,200); 
			}
			if ($actual_image_width > $targetwidth) {
				$percent = (100 * $targetwidth / $actual_image_width);
				$h = $actual_image_height * $percent / 100;
				resizess($destupload,$destupload,$targetwidth,$h);
			}
		}
		return $fullname;
	} else {
		parseError("File upload failed");
	}
}

/**เก้บไว้ดู**/
// function isDate($i_sDate) {
	// /*
	// function isDate
	// boolean isDate(string)
	// Summary: checks if a date is formatted correctly: mm/dd/yyyy (US English)
	// Author: Laurence Veale (modified by Sameh Labib)
	// Date: 07/30/2001
	// */
 
	// $blnValid = TRUE;
   
	// if ( $i_sDate == "00/00/0000" ) { return $blnValid; }
	   
	////check the format first (may not be necessary as we use checkdate() below)
	// if(!ereg ("^[0-9]{2}/[0-9]{2}/[0-9]{4}$", $i_sDate)) {
		// $blnValid = FALSE;
	// } else {
		////format is okay, check that days, months, years are okay
		// $arrDate = explode("/", $i_sDate); // break up date by slash
		// $intMonth = $arrDate[0];
		// $intDay = $arrDate[1];
		// $intYear = $arrDate[2];
		 
		// $intIsDate = checkdate($intMonth, $intDay, $intYear);
		 
		// if(!$intIsDate) {
		  // $blnValid = FALSE;
		// }
	// }
	// return ($blnValid);
// } 
//----------------------------------------------------------------------------

function matchToken($key,$user) {
	$securecode_post = decrypt($_POST['csrf_securecode'], $key);
	$token_post = md5(encrypt($securecode_post,$key).$user);
	if(!isset($_POST['csrf_token']))
		return false;
	if($_POST['csrf_token'] === $token_post) {	
		return true;
	}
	return false;
}
function getnewteamid($tem_code,$conn) {
	//QT-2001-0001-001
	$sql = "select max(substring(tem_code,2,4)) as seq from tem_mstr";		
	$result = sqlsrv_query($conn, $sql);		
	$row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
	if (!$row) {		
		$id = 1;
	}
	else {		
		$id = $row["seq"] + 1;
	}
	return $tem_code.substr("0000{$id}", -4);	
}
function getpjmnbr($type,$conn) {
	//QT-YYMM-0001
	$tym = strtoupper($type).date('ym');
	$sql = "select max(substring(pjm_nbr,9,4)) as nbr from pjm_mstr where substring(pjm_nbr,1,7) = '$tym'";	
	$result = sqlsrv_query($conn, $sql);	
	$row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);		
	if ($row) {
		if (is_null($row['nbr'])) {
			$next_numner = 1;
		}
		else {
			$next_numner = $row['nbr'] + 1;
		}
	}
	else {
		$next_numner = 1;
	}
	$qtm_nbr = $tym."-".substr("0000{$next_numner}", -4);
	return $qtm_nbr;
}
function Ymd_fr_Txt_Date($strdate) {
	//get from format dd/mm/yyyy to yyyy - mm -dd
	$Y = substr($strdate,0,4);
	$m = substr($strdate,4,2);
	$d = substr($strdate,6,2);
	return $Y."-".$m."-".$d;
}
function day_diff_sign($fromdate,$todate) {
	//para1 yyyymmdd, para2 = yyyymmdd
	$date1 = date_create(substr($fromdate,0,4).'-'.substr($fromdate,4,2).'-'.substr($fromdate,6,2));
	$date2 = date_create(substr($todate,0,4).'-'.substr($todate,4,2).'-'.substr($todate,6,2));
	$interval = date_diff($date1,$date2);
	return $interval->format('%R%a');
}
?>
