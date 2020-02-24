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
	$custpay_pay_seq = html_escape($_POST['custpay_pay_seq']);
	$custpay_pay_date = html_escape($_POST['custpay_pay_date']);
	$custpay_pay_desc = html_escape($_POST['custpay_pay_desc']);
	$custpay_pay_amt = html_escape($_POST['custpay_pay_amt']);
	$custpay_pay_cmmt = html_escape($_POST['custpay_pay_cmmt']);
	//INPUT VALIDATION
	$errorflag = false;
	$errortxt = "";
	if (inlist("add_custpay,edit_custpay",$action)) {
		if ($action == "edit_custpay") {
			if ($custpay_pay_seq=="") {
				if ($errortxt!="") {$errortxt .= "<br>";}
				$errorflag = true;					
				$errortxt .= "กรุณาระบุ - [ งวดชำระ ]";
			}
			else {
				if (!is_numeric($custpay_pay_seq)) {
					if ($errortxt!="") {$errortxt .= "<br>";}
					$errorflag = true;					
					$errortxt .= "กรุณาระบุ - [ งวดชำระเป็นตัวเลข ]";
				}
			}
		}
		if (!isdate($custpay_pay_date) or $custpay_pay_date=="") {
			if ($errortxt!="") {$errortxt .= "<br>";}
			$errorflag = true;					
			$errortxt .= "กรุณาระบุ - [ วันที่ต้องชำระ]  ให้ถูกต้องตามรูปแบบ วว/ดด/ปปปป เป็น ค.ศ.";	
		} 
		else {
			$custpay_pay_date = ymd($custpay_pay_date);
		}
		if ($custpay_pay_amt=="") {
			if ($errortxt!="") {$errortxt .= "<br>";}
			$errorflag = true;					
			$errortxt .= "กรุณาระบุ - [ จำนวนเงินที่ต้องชำระ ]";
		}
		else {
			if (!is_numeric($custpay_pay_amt)) {
				if ($errortxt!="") {$errortxt .= "<br>";}
				$errorflag = true;					
				$errortxt .= "กรุณาระบุ - [ จำนวนเงินที่ต้องชำระเป็นตัวเลข ]";
			}
		}
	}
	
	if ($action == "add_custpay") {
		$custpay_id = getnewcustpayid($qtm_nbr,$conn);
		$custpay_pay_seq = getnewseqbycon("custpay_pay_seq", "custpay_det","custpay_qtm_nbr = '".$qtm_nbr."'", $conn);
		if (!$errorflag) {
			$sql_add = " INSERT INTO custpay_det (
				custpay_id,custpay_qtm_nbr,custpay_pay_seq,
				custpay_pay_date,custpay_pay_desc,custpay_pay_amt,
				custpay_pay_cmmt,
				custpay_create_by,custpay_create_date)".
				" VALUES (
				'$custpay_id','$qtm_nbr','$custpay_pay_seq',
				'$custpay_pay_date','$custpay_pay_desc','$custpay_pay_amt',
				'$custpay_pay_cmmt',
				'$user_login','$today')";
			
			$result_add = sqlsrv_query($conn, $sql_add);
			$errortxt="";
			$r="1";
			$nb=encrypt($qtm_nbr, $key);
			$tb=encrypt("tab_custpay", $key);
			echo '{"r":"'.$r.'","e":"'.$errortxt.'","nb":"'.$nb.'","tb":"'.$tb.'","pg":"'.$pg.'"}';
		}
		else {
			$r="0";
			$nb="";
			$tb=encrypt("tab_custpay", $key);
			echo '{"r":"'.$r.'","e":"'.$errortxt.'","nb":"'.$nb.'","tb":"'.$tb.'","pg":"'.$pg.'"}';
		}
	}
	
	if ($action=="edit_custpay") {
		$custpay_id = mssql_escape($_POST['custpay_id']);
		if (!$errorflag) {
			$params = array($custpay_id);
			$sql_edit = "UPDATE custpay_det" .
				" SET custpay_pay_seq = '$custpay_pay_seq',
				custpay_pay_date = '$custpay_pay_date',
				custpay_pay_desc = '$custpay_pay_desc',
				custpay_pay_amt = '$custpay_pay_amt',
				custpay_pay_cmmt = '$custpay_pay_cmmt',
				custpay_create_by = '$user_login',
				custpay_create_date = '$today' " .
				" WHERE custpay_id = ?";
			
			$result_edit = sqlsrv_query($conn,$sql_edit,$params);
			$r="1";
			$errortxt="";
			$nb=encrypt($qtm_nbr, $key);
			$tb=encrypt("tab_custpay", $key);
			echo '{"r":"'.$r.'","e":"'.$errortxt.'","nb":"'.$nb.'","tb":"'.$tb.'","pg":"'.$pg.'"}';
		}
		else {
			$r="0";
			$nb="";
			$tb=encrypt("tab_custpay", $key);
			echo '{"r":"'.$r.'","e":"'.$errortxt.'","nb":"'.$nb.'","tb":"'.$tb.'","pg":"'.$pg.'"}';
		}
	}
	if ($action == "del_custpay") {
		$custpay_id = mssql_escape($_POST['custpay_id']);
		$params = array($custpay_id);
		$sql_del = "DELETE FROM custpay_det WHERE custpay_id = ?";	
		$result_del = sqlsrv_query($conn,$sql_del,$params);
		$r="1";
		$errortxt="";
		$nb=encrypt($qtm_nbr, $key);	
		$tb=encrypt("tab_custpay", $key);
		echo '{"r":"'.$r.'","e":"'.$errortxt.'","nb":"'.$nb.'","tb":"'.$tb.'","pg":"'.$pg.'"}';
	}
?> 