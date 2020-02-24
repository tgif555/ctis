<?php
	//Temp
	$user_login = "KOMSUNYU";
	
	include("../_incs/acunx_metaheader.php");
	//include("../_incs/chksession.php");  
	include("../_incs/config.php");	
	include("../_incs/funcServer.php");	
	include("../_incs/funcServer_project.php");	
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
	
	$pjm_nbr = mssql_escape($_POST['pjm_nbr']);	
	$pjm_name = mssql_escape($_POST['pjm_name']);
	$pjm_pjt_code = mssql_escape($_POST['pjm_pjt_code']);
	
	$pjm_custpj_code = mssql_escape($_POST['qtm_customer_number']);
	
	if ($pjm_custpj_code == "" || $pjm_custpj_code == "DUMMY") {
		$pjm_custpj_code = "DUMMY";
		$pjm_contact_name = mssql_escape($_POST['qtm_customer_name']);
	}
	else {
		//$pjm_contact_name = mssql_escape($_POST['qtm_customer_name']);
		$pjm_contact_name = findsqlval("custpj_mstr","custpj_name","custpj_code",$pjm_custpj_code,$conn);
	}
 	
	//INPUT VALIDATION
	$errorflag = false;
	$errortxt = "";
	if (inlist("pjmadd,pjmedit",$action)) {	
		// if ($pjm_nbr=="") {
			// if ($errortxt!="") {$errortxt .= "<br>";}
			// $errorflag = true;					
			// $errortxt .= "กรุณาระบุ - [ Project Number ]";
		// }
		if ($pjm_name=="") {
			if ($errortxt!="") {$errortxt .= "<br>";}
			$errorflag = true;					
			$errortxt .= "กรุณาระบุ - [ Project Name ]";
		}
		if ($pjm_pjt_code=="") {
			if ($errortxt!="") {$errortxt .= "<br>";}
			$errorflag = true;					
			$errortxt .= "กรุณาระบุ - [ Project Type ]";
		}
		if ($pjm_custpj_code =="") {
			if ($errortxt!="") {$errortxt .= "<br>";}
			$errorflag = true;					
			$errortxt .= "กรุณาระบุ - [ Customer Code ]";
		}
		else if ($pjm_custpj_code !="") {
			$params = array($pjm_custpj_code);
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
			$pjm_custpj_code = "DUMMY";
			if ($pjm_contact_name !="") {
				if ($errortxt!="") {$errortxt .= "<br>";}
				$errorflag = true;					
				$errortxt .= "กรุณาระบุ - [ Customer Name ]";
			}
		}
		
	}
	if ($action == "pjmadd") {
		$pjm_whocanread = "ADMIN";
		//ADD ผู้ขอเบิกให้สามารถเห็นเอกสาร
		if ($user_login!="") {
			if(!inlist($pjm_whocanread,$user_login)) {
				if ($pjm_whocanread != "") { $pjm_whocanread = $pjm_whocanread .","; }
				$pjm_whocanread = $pjm_whocanread . $user_login;
			}
		}
		
		//$pjm_step_code = 0;	//Save Draft - Await for Submit	
		//$pjm_step_by = $user_login;
		//$pjm_curprocessor = $user_login;
		$pjm_create_by = $user_login;
		$pjm_create_date = date("Y-m-d H:i:s");
		$pjm_nbr = getpjmnbr("PJ-",$conn);
		if ($pjm_nbr == "0") {
			if ($errortxt!="") {$errortxt .= "<br>";}
			$errorflag = true;					
			$errortxt .= "ไม่สามารถสร้างหมายเลขใบเบิกได้";	
		}
		if (!$errorflag) {
			$sql_add = " insert into pjm_mstr ([pjm_nbr],[pjm_name],[pjm_pjt_code]     
      ,[pjm_custpj_code],[pjm_contact_name],[pjm_pjst_code],[pjm_create_by],[pjm_create_date]) VALUES (
			'$pjm_nbr','$pjm_name','$pjm_pjt_code','$pjm_custpj_code','$pjm_contact_name','$pjm_pjst_code','$pjm_create_by','$pjm_create_date')";
			$result_add = sqlsrv_query($conn, $sql_add);
			if ($result_add) {
				$r="1";
				$errortxt="update success.";
				$nb=encrypt($pjm_nbr, $key);
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
	if ($action == "pjmedit") {	
		//For Edit
		//ยืนยัน current processor อีกครั้ง กรณีที่มีคนที่ไม่ใช่ current processor login เข้ามาอีก page
		$allow_post = false;

		$params = array($pjm_nbr);
		$sql_pjm = "SELECT pjm_curprocessor from pjm_mstr where pjm_nbr = ?";
		$result_pjm = sqlsrv_query($conn, $sql_pjm, $params);	
		$r_pjm = sqlsrv_fetch_array($result_pjm, SQLSRV_FETCH_ASSOC);		
		if ($r_pjm) {
			$pjm_curprocessor_check = $r_pjm['pjm_curprocessor'];
			
			if (inlist($pjm_curprocessor_check,$user_login)) {
				$allow_post = true;
			}
			else {
				$pjm_curprocessor_role_access = "";
				$pjm_curprocessor_role_array = explode(",",$user_role);
				for ($c=0;$c<count($pjm_curprocessor_role_array);$c++) {
					if (inlist($pjm_curprocessor_check,$pjm_curprocessor_role_array[$c])) {
						$allow_post = true;
						break;
					}
				}
			}
		}
		if (!$allow_post) {
			if ($errortxt!="") {$errortxt .= "<br>";}
			$errorflag = true;					
			$errortxt .= "คุณไม่มีสิทธิ์ใช้งานหน้านี้".$pjm_nbr."tt";
		}
		if (!$errorflag) {
			$params = array($pjm_nbr);
			$sql_edit = "UPDATE pjm_mstr".
				" SET pjm_to = '$pjm_to',
				pjm_name = '$pjm_name',
				pjm_custpj_code = '$pjm_custpj_code',
				pjm_contact_name = '$pjm_contact_name',
				pjm_date = '$pjm_date',
				pjm_expire_date = '$pjm_expire_date',
				pjm_address = '$pjm_address',
				pjm_amphur = '$pjm_amphur',
				pjm_province = '$pjm_province',
				pjm_zip_code = '$pjm_zip_code',
				pjm_lineid = '$pjm_lineid',
				pjm_email = '$pjm_email',
				pjm_tel_contact = '$pjm_tel_contact',
				pjm_payterm_code = '$pjm_payterm_code',
				pjm_detail = '$pjm_detail',
				pjm_remark = '$pjm_remark',
				pjm_disc = '$pjm_disc',
				pjm_disc_unit = '$pjm_disc_unit',
				pjm_update_by = '$user_login',
				pjm_update_date  = '$today'
				WHERE pjm_nbr = ?";
			$result_edit = sqlsrv_query($conn,$sql_edit,$params);
			if ($result_edit) {
				$r="1";
				$errortxt="update success.";
				$nb=encrypt($pjm_nbr, $key);
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
	if ($action == "pjmdel") {
		//Check เงื่อนไขการ Delete
			//มีการอ้างอิงจาก Project หรือไม่ถ้ามีระบบจะไม่ยอมให้ Delete
			//Quotation อยู่ในสถานะทำดำเนินการไปแล้ว
		//
		$params = array($pjm_nbr);
		$sql = "UPDATE pjm_mstr SET ".		
		"pjm_is_delete='1'," .			
		"pjm_update_by = '$user_login'," .
		"pjm_update_date = '$today'" .
		" WHERE pjm_nbr = ?";			
		$result_del = sqlsrv_query($conn,$sql_del,$params);
		
		if ($result_del) {
			$r="1";
			$errortxt="delete success.";
			$nb=encrypt($pjm_nbr, $key);
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