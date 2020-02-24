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
	$errortxt = "";
	$allow_post = false;

	$pg = html_escape($_REQUEST['pg']);
	$action = html_escape($_POST['action']);
	
	$qtm_nbr = mssql_escape($_POST['qtm_nbr']);
	$qtm_to = mssql_escape($_POST['qtm_to']);
	$qtm_name = mssql_escape($_POST['qtm_name']);
	$qtm_customer_number = mssql_escape($_POST['qtm_customer_number']);
	if ($qtm_customer_number == "" || $qtm_customer_number == "DUMMY") {
		$qtm_customer_number = "DUMMY";
		$qtm_customer_name = mssql_escape($_POST['qtm_customer_name']);
	}
	else {
		$qtm_customer_name = findsqlval("custpj_mstr","custpj_name","custpj_code",$qtm_customer_number,$conn);
	}
 	$qtm_date = mssql_escape($_POST['qtm_date']);
	$qtm_expire_date = mssql_escape($_POST['qtm_expire_date']);
	$qtm_address = mssql_escape($_POST['qtm_address']);
	$qtm_amphur = mssql_escape($_POST['qtm_amphur']);
	$qtm_province = mssql_escape($_POST['qtm_province']);
	$qtm_zip_code = mssql_escape($_POST['qtm_zip_code']);
	$qtm_lineid = mssql_escape($_POST['qtm_lineid']);
	$qtm_email = mssql_escape($_POST['qtm_email']);
	$qtm_tel_contact = mssql_escape($_POST['qtm_tel_contact']);
	$qtm_detail = mssql_escape($_POST['qtm_detail']);
	$qtm_remark = mssql_escape($_POST['qtm_remark']);
	$qtm_prepaid_amt = mssql_escape($_POST['qtm_prepaid_amt']);
	$qtm_prepaid_date = mssql_escape($_POST['qtm_prepaid_date']);
	$qtm_disc = mssql_escape($_POST['qtm_disc']);
	$qtm_disc_unit = mssql_escape($_POST['qtm_disc_unit']);
	
	//INPUT VALIDATION
	$errorflag = false;
	$errortxt = "";
	if (inlist("qtmadd,qtmedit",$action)) {
		if ($qtm_to=="") {
			if ($errortxt!="") {$errortxt .= "<br>";}
			$errorflag = true;					
			$errortxt .= "กรุณาระบุ - [ Quotation To]";
		}	
		
		if ($qtm_name=="") {
			if ($errortxt!="") {$errortxt .= "<br>";}
			$errorflag = true;					
			$errortxt .= "กรุณาระบุ - [ Quotation Name ]";
		}
		if (!isdate($qtm_date) or $qtm_date=="") {
			if ($errortxt!="") {$errortxt .= "<br>";}
			$errorflag = true;					
			$errortxt .= "กรุณาระบุ - [ Quotation Date] ให้ถูกต้องตามรูปแบบ วว/ดด/ปปปป เป็น ค.ศ.";	
		} 
		else {
			$qtm_date = ymd($qtm_date);
		}
		if (!isdate($qtm_expire_date) or $qtm_expire_date=="") {
			if ($errortxt!="") {$errortxt .= "<br>";}
			$errorflag = true;					
			$errortxt .= "กรุณาระบุ - [ Expire Date] ให้ถูกต้องตามรูปแบบ วว/ดด/ปปปป เป็น ค.ศ.";	
		} else {
			$qtm_expire_date = ymd($qtm_expire_date);
		}
		if ($qtm_prepaid_amt != "") {
			if (!is_numeric($qtm_prepaid_amt)) {
				if ($errortxt!="") {$errortxt .= "<br>";}
				$errorflag = true;					
				$errortxt .= "กรุณาระบุ - [ Pre-Paid Amount เป็นตัวเลข ]";
			}
			if ($qtm_prepaid_amt == "") {
				if ($errortxt!="") {$errortxt .= "<br>";}
				$errorflag = true;					
				$errortxt .= "กรุณาระบุ - [ Pre-Paid Amount ]";
			}
			if ($qtm_prepaid_date == "") {
				if ($errortxt!="") {$errortxt .= "<br>";}
				$errorflag = true;					
				$errortxt .= "กรุณาระบุ - [ Pre-Paid Date]";
			}
		}
		else {
			$qtm_prepaid_amt = 0;
		}
		if ($qtm_prepaid_date != "") {
			if (!isdate($qtm_prepaid_date)) {
				if ($errortxt!="") {$errortxt .= "<br>";}
				$errorflag = true;					
				$errortxt .= "กรุณาระบุ - [ Pre-Paid Date] ให้ถูกต้องตามรูปแบบ วว/ดด/ปปปป เป็น ค.ศ.";	
			} else {
				$qtm_prepaid_date = ymd($qtm_prepaid_date);
			}
		}
		if ($qtm_disc != "") {
			if (!is_numeric($qtm_disc)) {
				if ($errortxt!="") {$errortxt .= "<br>";}
				$errorflag = true;					
				$errortxt .= "กรุณาระบุ - [ Discount เป็นตัวเลข ]";
			}
			if ($qtm_disc == "") {
				if ($errortxt!="") {$errortxt .= "<br>";}
				$errorflag = true;					
				$errortxt .= "กรุณาระบุ - [ Discount Unit ]";
			}
		}
		else {
			$qtm_disc = 0;
			$qtm_disc_unit = "B";
		}
		if ($qtm_customer_number !="") {
			$params = array($qtm_customer_number);
			$sql = "select custpj_code from custpj_mstr where custpj_code = ?";
			$result = sqlsrv_query($conn, $sql,$params);
			$row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
			if (!$row) {	
				if ($errortxt!="") {$errortxt .= "<br>";}
				$errorflag = true;					
				$errortxt .= "รหัสลูกค้าที่ระบุไม่มีในระบบ";
			}
		}
		else {
			$qtm_customer_number = "DUMMY";
			if ($qtm_customer_name !="") {
				if ($errortxt!="") {$errortxt .= "<br>";}
				$errorflag = true;					
				$errortxt .= "กรุณาระบุ - [ Customer Name ]";
			}
		}
		if ($qtm_address=="") {
			if ($errortxt!="") {$errortxt .= "<br>";}
			$errorflag = true;					
			$errortxt .= "กรุณาระบุ - [ Address]";
		}
		if ($qtm_amphur=="") {
			if ($errortxt!="") {$errortxt .= "<br>";}
			$errorflag = true;					
			$errortxt .= "กรุณาระบุ - [ Amphur]";
		}
		if ($qtm_province=="") {
			if ($errortxt!="") {$errortxt .= "<br>";}
			$errorflag = true;					
			$errortxt .= "กรุณาระบุ - [ Province]";
		}
		if ($qtm_zip_code=="") {
			if ($errortxt!="") {$errortxt .= "<br>";}
			$errorflag = true;					
			$errortxt .= "กรุณาระบุ - [ Zip Code]";
		}
		if ($qtm_tel_contact=="") {
			if ($errortxt!="") {$errortxt .= "<br>";}
			$errorflag = true;					
			$errortxt .= "กรุณาระบุ - [ Tel Contract]";
		}
	}
	if ($action == "qtmadd") {
		$qtm_whocanread = "ADMIN";
		//ADD ผู้ขอเบิกให้สามารถเห็นเอกสาร
		if ($user_login!="") {
			if(!inlist($qtm_whocanread,$user_login)) {
				if ($qtm_whocanread != "") { $qtm_whocanread = $qtm_whocanread .","; }
				$qtm_whocanread = $qtm_whocanread . $user_login;
			}
		}
		
		$qtm_step_code = 0;	//Save Draft - Await for Submit	
		$qtm_step_by = $user_login;
		$qtm_curprocessor = $user_login;
		$qtm_create_by = $user_login;
		$qtm_nbr = getqtmnbr("QT-",$conn);
		if ($qtm_nbr == "0") {
			if ($errortxt!="") {$errortxt .= "<br>";}
			$errorflag = true;					
			$errortxt .= "ไม่สามารถสร้างหมายเลขใบเบิกได้";	
		}
		if (!$errorflag) {
			$sql_add = " INSERT INTO qtm_mstr (
			qtm_nbr,qtm_to,qtm_name,qtm_customer_number,qtm_customer_name,
			qtm_date,qtm_expire_date,
			qtm_address,qtm_amphur,qtm_province,qtm_zip_code,
			qtm_lineid,qtm_email,qtm_tel_contact,
			qtm_detail,qtm_remark,
			qtm_prepaid_amt,qtm_prepaid_date,
			qtm_disc,qtm_disc_unit,
			qtm_step_code,qtm_step_by,qtm_step_date,qtm_step_cmmt,
			qtm_is_delete,
			qtm_whocanread,qtm_curprocessor,
			qtm_create_by,qtm_create_date)".
			" VALUES (
			'$qtm_nbr','$qtm_to','$qtm_name','$qtm_customer_number','$qtm_customer_name',
			'$qtm_date','$qtm_expire_date',
			'$qtm_address','$qtm_amphur','$qtm_province','$qtm_zip_code',
			'$qtm_lineid','$qtm_email','$qtm_tel_contact',
			'$qtm_detail','$qtm_remark',
			'$qtm_prepaid_amt','$qtm_prepaid_date',
			'$qtm_disc','$qtm_disc_unit',
			'$qtm_step_code','$qtm_step_by','$qtm_step_date','$qtm_step_cmmt',
			'0',
			'$qtm_whocanread','$qtm_curprocessor',
			'$qtm_create_by','$qtm_create_date'
			)";
			$result_add = sqlsrv_query($conn, $sql_add);
			if ($result_add) {
				$r="1";
				$errortxt="update success.";
				$nb=encrypt($qtm_nbr, $key);
			}
			else {
				$r="0";
				$nb="";
				if( ($errors = sqlsrv_errors() ) != null) {
					foreach( $errors as $error ) {
						$errortxt .= "SQLSTATE: ".$error[ 'SQLSTATE']."<br />".
							"code: ".$error[ 'code']."<br />".
							"message: ".$error[ 'message']."<br />";
					}
				}
			}
			echo '{"r":"'.$r.'","e":"'.$errortxt.'","nb":"'.$nb.'","pg":"'.$pg.'"}';
		}
		else {
			$r="0";
			$nb="";
			echo '{"r":"'.$r.'","e":"'.$errortxt.'","nb":"'.$nb.'","pg":"'.$pg.'"}';
		}
	}
	if ($action == "qtmedit") {	
		//For Edit
		//ยืนยัน current processor อีกครั้ง กรณีที่มีคนที่ไม่ใช่ current processor login เข้ามาอีก page
		$allow_post = false;

		$params = array($qtm_nbr);
		$sql_qtm = "SELECT qtm_curprocessor from qtm_mstr where qtm_nbr = ?";
		$result_qtm = sqlsrv_query($conn, $sql_qtm, $params);	
		$r_qtm = sqlsrv_fetch_array($result_qtm, SQLSRV_FETCH_ASSOC);		
		if ($r_qtm) {
			$qtm_curprocessor_check = $r_qtm['qtm_curprocessor'];
			
			if (inlist($qtm_curprocessor_check,$user_login)) {
				$allow_post = true;
			}
			else {
				$qtm_curprocessor_role_access = "";
				$qtm_curprocessor_role_array = explode(",",$user_role);
				for ($c=0;$c<count($qtm_curprocessor_role_array);$c++) {
					if (inlist($qtm_curprocessor_check,$qtm_curprocessor_role_array[$c])) {
						$allow_post = true;
						break;
					}
				}
			}
		}
		if (!$allow_post) {
			if ($errortxt!="") {$errortxt .= "<br>";}
			$errorflag = true;					
			$errortxt .= "คุณไม่มีสิทธิ์ใช้งานหน้านี้".$qtm_nbr."tt";
		}
		if (!$errorflag) {
			$params = array($qtm_nbr);
			$sql_edit = "UPDATE qtm_mstr".
				" SET qtm_to = '$qtm_to',
				qtm_name = '$qtm_name',
				qtm_customer_number = '$qtm_customer_number',
				qtm_customer_name = '$qtm_customer_name',
				qtm_date = '$qtm_date',
				qtm_expire_date = '$qtm_expire_date',
				qtm_address = '$qtm_address',
				qtm_amphur = '$qtm_amphur',
				qtm_province = '$qtm_province',
				qtm_zip_code = '$qtm_zip_code',
				qtm_lineid = '$qtm_lineid',
				qtm_email = '$qtm_email',
				qtm_tel_contact = '$qtm_tel_contact',
				qtm_detail = '$qtm_detail',
				qtm_remark = '$qtm_remark',
				qtm_prepaid_amt = '$qtm_prepaid_amt',
				qtm_prepaid_date = '$qtm_prepaid_date',
				qtm_disc = '$qtm_disc',
				qtm_disc_unit = '$qtm_disc_unit',
				qtm_update_by = '$user_login',
				qtm_update_date  = '$today'
				WHERE qtm_nbr = ?";
			$result_edit = sqlsrv_query($conn,$sql_edit,$params);
			if ($result_edit) {
				$r="1";
				$errortxt="update success.";
				$nb=encrypt($qtm_nbr, $key);
			}
			else {
				$r="0";
				$nb="";
				if( ($errors = sqlsrv_errors() ) != null) {
					foreach( $errors as $error ) {
						$errortxt .= "SQLSTATE: ".$error[ 'SQLSTATE']."<br />".
							"code: ".$error[ 'code']."<br />".
							"message: ".$error[ 'message']."<br />";
					}
				}
			}
			$ta=encrypt("tab_qtinfo", $key);
			echo '{"r":"'.$r.'","e":"'.$errortxt.'","ta":"'.$ta.'","nb":"'.$nb.'","pg":"'.$pg.'"}';
		}
		else {
			$r="0";
			$nb="";
			echo '{"r":"'.$r.'","e":"'.$errortxt.'","nb":"'.$nb.'","pg":"'.$pg.'"}';
		}
	}
	if ($action == "qtmdel") {
		//Check เงื่อนไขการ Delete
			//มีการอ้างอิงจาก Project หรือไม่ถ้ามีระบบจะไม่ยอมให้ Delete
			//Quotation อยู่ในสถานะทำดำเนินการไปแล้ว
		//
		$params = array($qtm_nbr);
		$sql_del = "UPDATE qtm_mstr SET ".		
		"qtm_is_delete='1'," .			
		"qtm_update_by = '$user_login'," .
		"qtm_update_date = '$today'" .
		" WHERE qtm_nbr = ?";			
		$result_del = sqlsrv_query($conn,$sql_del,$params);
		
		if ($result_del) {
			$r="1";
			$errortxt="delete success.";
			$nb=encrypt($qtm_nbr, $key);
		}
		else {
			$r="0";
			$nb="";
			if( ($errors = sqlsrv_errors() ) != null) {
				foreach( $errors as $error ) {
					$errortxt .= "SQLSTATE: ".$error[ 'SQLSTATE']."<br />".
						"code: ".$error[ 'code']."<br />".
						"message: ".$error[ 'message']."<br />";
				}
			}
		}
		echo '{"r":"'.$r.'","e":"'.$errortxt.'","nb":"'.$nb.'","pg":"'.$pg.'"}';
	}
?>