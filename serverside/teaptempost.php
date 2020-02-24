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
	$teap_id = html_escape($_POST['teap_id']);
	$teap_tem_code = mssql_escape($_POST['teap_tem_code']);	
	$teap_matcat_code = mssql_escape($_POST['teap_matcat_code']);	
	$teap_price = mssql_escape($_POST['teap_price']);	
	$teap_unit = mssql_escape($_POST['teap_unit']);	
	
	//INPUT VALIDATION
	$errorflag = false;
	$errortxt = "";
	
	if (inlist("teapadd,teapedit",$action)) {
		if ($teap_tem_code=="") {
			if ($errortxt!="") {$errortxt .= "<br>";}
			$errorflag = true;					
			$errortxt .= "กรุณาระบุ - ทีม";
		}
		
		if ($teap_matcat_code=="") {
			if ($errortxt!="") {$errortxt .= "<br>";}
			$errorflag = true;					
			$errortxt .= "กรุณาระบุ - ความถนัด";
		}
		if ($teap_price=="") {
			if ($errortxt!="") {$errortxt .= "<br>";}
			$errorflag = true;					
			$errortxt .= "กรุณาระบุ - ราคา";
		}
		if ($teap_unit=="") {
			if ($errortxt!="") {$errortxt .= "<br>";}
			$errorflag = true;					
			$errortxt .= "กรุณาระบุ - หน่วยของราคา";
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
		if ($action=="teapadd") {
			//teap_id, teap_tem_code, teap_aptt_code, teap_matcat_code, teap_price, teap_unit,
			//teap_create_by, teap_create_date, teap_update_by, teap_update_date
			//FROM         teap_det
			$teap_id = getnewid("teap_id","teap_det",$conn);
			
			$sql_add = " INSERT INTO teap_det(" . 
			" teap_id,teap_tem_code,teap_aptt_code,teap_matcat_code,".
			" teap_price,teap_unit,".
			" teap_create_by,teap_create_date)" .					
			" VALUES('$teap_id','$teap_tem_code','$teap_aptt_code','$teap_matcat_code',".
			" '$teap_price','$teap_unit',".
			" '$user_login','$today')";
			$result_add = sqlsrv_query($conn, $sql_add);
			if ($result_add) {
				$r="1";
				$errortxt="add success";
				$nb=encrypt($teap_tem_code, $key);
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
				" mat_customer_unit_cost = '$mat_customer_unit_cost',".
				" mat_customer_unit_price = '$mat_customer_unit_price',".
				" mat_contractor_unit_cost = '$mat_contractor_unit_cost',".
				" mat_contractor_unit_price = '$mat_contractor_unit_price',".
				" mat_standard_unit_cost = '$mat_standard_unit_cost',".
				" mat_standard_unit_price = '$mat_standard_unit_price',".
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
		if ($action=="teapdel") {
			array_push($params, $teap_id);
			$sql_del = "delete from teap_det WHERE teap_id = ?";
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
		$nb=encrypt($temb_tem_code, $key);
		echo '{"r":"'.$r.'","e":"'.$errortxt.'","nb":"'.$nb.'","pg":"'.$pg.'"}';
	}
?>