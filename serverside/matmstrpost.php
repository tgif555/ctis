<?php
	//Temp
	$user_login = "PISACHAM";
	
	include("../_incs/acunx_metaheader.php");
	//include("../_incs/chksession.php");	
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
	
	$mat_code = mssql_escape($_POST['mat_code']);	
	$mat_th_name = mssql_escape($_POST['mat_th_name']);	
	$mat_en_name = mssql_escape($_POST['mat_en_name']);	
	$mat_mag_code = mssql_escape($_POST['mat_mag_code']);	
	$mat_cat_code = mssql_escape($_POST['mat_cat_code']);
	$mat_unit_code = mssql_escape($_POST['mat_unit_code']);	
	$mat_detail = mssql_escape($_POST['mat_detail']);	
	/*$mat_customer_unit_cost = mssql_escape($_POST['mat_customer_unit_cost']);	
	$mat_customer_unit_price = mssql_escape($_POST['mat_customer_unit_price']);	
	$mat_contractor_unit_cost = mssql_escape($_POST['mat_contractor_unit_cost']);	
	$mat_contractor_unit_price = mssql_escape($_POST['mat_contractor_unit_price']);	
	*/
	
	$mat_customer_unit_price = mssql_escape($_POST['mat_customer_unit_price']);
	$mat_contractor_unit_price = mssql_escape($_POST['mat_contractor_unit_price']);	
	//$mat_standard_unit_cost = mssql_escape($_POST['mat_standard_unit_cost']);	
	//$mat_standard_unit_price = mssql_escape($_POST['mat_standard_unit_price']);	
	
	$mat_active = mssql_escape($_POST['mat_active']);
	
	//INPUT VALIDATION
	$errorflag = false;
	$errortxt = "";
	
	if (inlist("matadd,matedit",$action)) {
		if ($mat_code=="") {
			if ($errortxt!="") {$errortxt .= "<br>";}
			$errorflag = true;					
		$errortxt .= "กรุณาระบุ - [ Material Code ]";
		}
		else {
			if ($action == "matadd") {
				$params = array($mat_code);
				$sql = "select mat_code from mat_mstr where mat_code = ?";
				$result = sqlsrv_query($conn, $sql,$params);
				$row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
				if ($row) {	
					if ($errortxt!="") {$errortxt .= "<br>";}
					$errorflag = true;					
					$errortxt .= "รหัสสินค้ามีในระบบแล้ว";
				}	
			}
		}
		if ($mat_th_name=="") {
			if ($errortxt!="") {$errortxt .= "<br>";}
			$errorflag = true;					
			$errortxt .= "กรุณาระบุ - [ Material TH Name]";
		}
		if ($mat_en_name=="") {
			if ($errortxt!="") {$errortxt .= "<br>";}
			$errorflag = true;					
			$errortxt .= "กรุณาระบุ - [ Material EN Name]";
		}
		if ($mat_mag_code=="") {
			if ($errortxt!="") {$errortxt .= "<br>";}
			$errorflag = true;					
			$errortxt .= "กรุณาระบุ - [ Material Group]";
		}
		if ($mat_unit_code=="") {
			if ($errortxt!="") {$errortxt .= "<br>";}
			$errorflag = true;					
			$errortxt .= "กรุณาระบุ - [ Material Unit]";
		}
		//Customer
		
		
		if ($mat_customer_unit_price=="") {
			if ($errortxt!="") {$errortxt .= "<br>";}
			$errorflag = true;					
			$errortxt .= "กรุณาระบุ - [ Customer Price]";
		}
		else {
			if (!is_numeric($mat_customer_unit_price)) {
				if ($errortxt!="") {$errortxt .= "<br>";}
				$errorflag = true;					
				$errortxt .= "กรุณาระบุ - [ ราคาสำหรับลูกค้าเป็นตัวเลข ]";
			}
		}
		//Contractor
	
		if ($mat_contractor_unit_price=="") {
			if ($errortxt!="") {$errortxt .= "<br>";}
			$errorflag = true;					
			$errortxt .= "กรุณาระบุ - [ Contractor Price]";
		}
		else {
			if (!is_numeric($mat_contractor_unit_price)) {
				if ($errortxt!="") {$errortxt .= "<br>";}
				$errorflag = true;					
				$errortxt .= "กรุณาระบุ - [ ราคาสำหรับผู้รับเหมาเป็นตัวเลข ]";
			}
		}
		
		//Status
		if ($mat_active=="") {
			if ($errortxt!="") {$errortxt .= "<br>";}
			$errorflag = true;					
			$errortxt .= "กรุณาระบุ - [ สถานะ]";
		}
	}
	if ($action == "matdel") {
		if ($mat_code == "DUMMY") {
			if ($errortxt!="") {$errortxt .= "<br>";}
			$errorflag = true;					
			$errortxt .= "[ไม่อนุญาติให้ลบ รหัสสินค้า] DUMMY";
		}
		$params = array($mat_code);
		$sql = "select qtd_mat_code from qtd_det where qtd_mat_code = ?";
		$result = sqlsrv_query($conn, $sql,$params);
		$row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
		if ($row) {	
			if ($errortxt!="") {$errortxt .= "<br>";}
			$errorflag = true;					
			$errortxt .= "[ไม่อนุญาติให้ลบ รหัสสินค้า] การนำไปใช้ใน Quotation แล้ว";
		}
		
		
	}
	/*
	$allow_admin = false;
	if (!inlist($user_role,"ADMIN")) {
		$path = "../expense/expenseauthorize.php"; 
		//echo "<meta http-equiv=\"refresh\" content=\"0;URL=".$path."\" />";
	}
	else {
		$allow_admin = true;
	}
	$allow_admin = true;
	*/
	if (!$errorflag) {
		if ($action=="matadd") {
			
			
			$sql_add = " INSERT INTO mat_mstr (" . 
			" mat_code,mat_th_name,mat_en_name,".
			" mat_mag_code,mat_unit_code,mat_detail,".
			" mat_customer_unit_price,".
			" mat_contractor_unit_price,".
			" mat_active,".
			" mat_create_by,mat_create_date,mat_cat_code)" .					
			" VALUES('$mat_code','$mat_th_name','$mat_en_name',".
			" '$mat_mag_code','$mat_unit_code','$mat_detail',".
			" '$mat_customer_unit_price',".
			" '$mat_contractor_unit_price',".
			" '$mat_active',".
			" '$user_login','$today','$mat_cat_code')";
			$result_add = sqlsrv_query($conn, $sql_add);
			if ($result_add) {
				$r="1";
				$errortxt="add success";
				$nb=encrypt($mat_code, $key);
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
		if ($action=="matedit") {
			array_push($params, $mat_code);
			$sql_edit = "UPDATE mat_mstr SET ".
				" mat_th_name = '$mat_th_name',".
				" mat_en_name = '$mat_en_name',".
				" mat_mag_code = '$mat_mag_code',".
				" mat_unit_code = '$mat_unit_code',".
				" mat_detail = '$mat_detail',".
				" mat_customer_unit_price = '$mat_customer_unit_price',".
				" mat_contractor_unit_price = '$mat_contractor_unit_price',".
				" mat_active = '$mat_active'".
				" WHERE mat_code = ?";
			$result_edit = sqlsrv_query($conn,$sql_edit,$params);
			if ($result_edit) {
				$r="1";
				$errortxt="update success.";
				$nb=encrypt($mat_code, $key);
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
		if ($action=="matdel") {
			array_push($params, $mat_code);
			$sql_del = "delete from mat_mstr WHERE mat_code = ?";
			$result_del = sqlsrv_query($conn,$sql_del,$params);
			if ($result_del) {
				$r="1";
				$errortxt="delete success.";
				$nb="";
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
	}
	else {
		$r="0";
		$nb=encrypt($mat_code, $key);
		echo '{"r":"'.$r.'","e":"'.$errortxt.'","nb":"'.$nb.'","pg":"'.$pg.'"}';
	}
?>