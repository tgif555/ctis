<?php
	include("../_incs/acunx_metaheader.php");
	include("../_incs/chksession.php");  
	include("../_incs/config.php");	
	include("../_incs/funcServer.php");	
	include("../_incs/acunx_cookie_var.php");
	include "../_incs/acunx_csrf_var.php";

	if (($_SERVER['REQUEST_METHOD'] == 'POST')) {
		if (!matchToken($csrf_key,$user_login)) {
			echo "System detect CSRF attack!!";
			exit;
		}
	}
	$params = array();
	
	date_default_timezone_set('Asia/Bangkok');
	$today = date("Y-m-d H:i:s");  	
	$pg = html_escape($_REQUEST['pg']);
	$action = html_escape($_POST['action']);
	$qtm_nbr = html_escape($_POST['qtm_nbr']);
	$conspay_pay_seq = html_escape($_POST['conspay_pay_seq']);
	$conspay_pay_date = html_escape($_POST['conspay_pay_date']);
	$conspay_pay_desc = html_escape($_POST['conspay_pay_desc']);
	$conspay_pay_amt = html_escape($_POST['conspay_pay_amt']);
	$conspay_pay_cmmt = html_escape($_POST['conspay_pay_cmmt']);
	//INPUT VALIDATION
	$errorflag = false;
	$errortxt = "";
	if (inlist("add_conspay,edit_conspay",$action)) {
		if ($action == "edit_conspay") {
			if ($conspay_pay_seq=="") {
				if ($errortxt!="") {$errortxt .= "<br>";}
				$errorflag = true;					
				$errortxt .= "กรุณาระบุ - [ งวดชำระ ]";
			}
			else {
				if (!is_numeric($conspay_pay_seq)) {
					if ($errortxt!="") {$errortxt .= "<br>";}
					$errorflag = true;					
					$errortxt .= "กรุณาระบุ - [ งวดชำระเป็นตัวเลข ]";
				}
			}
		}
		if (!isdate($conspay_pay_date) or $conspay_pay_date=="") {
			if ($errortxt!="") {$errortxt .= "<br>";}
			$errorflag = true;					
			$errortxt .= "กรุณาระบุ - [ วันที่ต้องชำระ]  ให้ถูกต้องตามรูปแบบ วว/ดด/ปปปป เป็น ค.ศ.";	
		} 
		else {
			$conspay_pay_date = ymd($conspay_pay_date);
		}
		if ($conspay_pay_amt=="") {
			if ($errortxt!="") {$errortxt .= "<br>";}
			$errorflag = true;					
			$errortxt .= "กรุณาระบุ - [ จำนวนเงินที่ต้องชำระ ]";
		}
		else {
			if (!is_numeric($conspay_pay_amt)) {
				if ($errortxt!="") {$errortxt .= "<br>";}
				$errorflag = true;					
				$errortxt .= "กรุณาระบุ - [ จำนวนเงินที่ต้องชำระเป็นตัวเลข ]";
			}
		}
	}
	
	if ($action == "add_conspay") {
		$conspay_id = getnewconspayid($qtm_nbr,$conn);
		$conspay_pay_seq = getnewseqbycon("conspay_pay_seq","conspay_det", "conspay_qtm_nbr = '".$qtm_nbr."'",  $conn);
		if (!$errorflag) {
			$sql_add = " INSERT INTO conspay_det (
				conspay_id,conspay_qtm_nbr,conspay_pay_seq,
				conspay_pay_date,conspay_pay_desc,conspay_pay_amt,
				conspay_pay_cmmt,
				conspay_create_by,conspay_create_date)".
				" VALUES (
				'$conspay_id','$qtm_nbr','$conspay_pay_seq',
				'$conspay_pay_date','$conspay_pay_desc','$conspay_pay_amt',
				'$conspay_pay_cmmt',
				'$user_login','$today')";
			
			$result_add = sqlsrv_query($conn, $sql_add);
			$errortxt="";
			$r="1";
			$nb=encrypt($qtm_nbr, $key);
			$tb=encrypt("tab_conspay", $key);
			echo '{"r":"'.$r.'","e":"'.$errortxt.'","nb":"'.$nb.'","tb":"'.$tb.'","pg":"'.$pg.'"}';
		}
		else {
			$r="0";
			$nb="";
			$tb=encrypt("tab_conspay", $key);
			echo '{"r":"'.$r.'","e":"'.$errortxt.'","nb":"'.$nb.'","tb":"'.$tb.'","pg":"'.$pg.'"}';
		}
	}
	
	if ($action=="edit_conspay") {
		$conspay_id = mssql_escape($_POST['conspay_id']);
		if (!$errorflag) {
			$params = array($conspay_id);
			$sql_edit = "UPDATE conspay_det" .
				" SET conspay_pay_seq = '$conspay_pay_seq',
				conspay_pay_date = '$conspay_pay_date',
				conspay_pay_desc = '$conspay_pay_desc',
				conspay_pay_amt = '$conspay_pay_amt',
				conspay_pay_cmmt = '$conspay_pay_cmmt',
				conspay_create_by = '$user_login',
				conspay_create_date = '$today' " .
				" WHERE conspay_id = ?";
			
			$result_edit = sqlsrv_query($conn,$sql_edit,$params);
			$r="1";
			$errortxt="";
			$nb=encrypt($qtm_nbr, $key);
			$tb=encrypt("tab_conspay", $key);
			echo '{"r":"'.$r.'","e":"'.$errortxt.'","nb":"'.$nb.'","tb":"'.$tb.'","pg":"'.$pg.'"}';
		}
		else {
			$r="0";
			$nb="";
			$tb=encrypt("tab_conspay", $key);
			echo '{"r":"'.$r.'","e":"'.$errortxt.'","nb":"'.$nb.'","tb":"'.$tb.'","pg":"'.$pg.'"}';
		}
	}
	if ($action == "del_conspay") {
		$conspay_id = mssql_escape($_POST['conspay_id']);
		$params = array($conspay_id);
		$sql_del = "DELETE FROM conspay_det WHERE conspay_id = ?";	
		$result_del = sqlsrv_query($conn,$sql_del,$params);
		$r="1";
		$errortxt="";
		$nb=encrypt($qtm_nbr, $key);
		$tb=encrypt("tab_conspay", $key);
		echo '{"r":"'.$r.'","e":"'.$errortxt.'","nb":"'.$nb.'","tb":"'.$tb.'","pg":"'.$pg.'"}';		
		
	}
?> 