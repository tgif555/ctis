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
	$temb_id = mssql_escape($_POST['temb_id']);
	
	$temb_tem_code = mssql_escape($_POST['temb_tem_code']);	
	$memb_name = mssql_escape($_POST['memb_name']);	
	$memb_age = mssql_escape($_POST['memb_age']);	
	$memb_gender = mssql_escape($_POST['memb_gender']);	
	
	//INPUT VALIDATION
	$errorflag = false;
	$errortxt = "";
	
	if (inlist("memteamadd,memteamedit",$action)) {
		if ($temb_tem_code=="") {
			if ($errortxt!="") {$errortxt .= "<br>";}
			$errorflag = true;					
			$errortxt .= "กรุณาระบุ - ทีม";
		}
		
		if ($memb_name=="") {
			if ($errortxt!="") {$errortxt .= "<br>";}
			$errorflag = true;					
			$errortxt .= "กรุณาระบุ - ชื่อสมาชิกในทีม";
		}
		if ($memb_age=="") {
			if ($errortxt!="") {$errortxt .= "<br>";}
			$errorflag = true;					
			$errortxt .= "กรุณาระบุ - อายุของสมาชิกในทีม";
		}
		if ($memb_gender=="") {
			if ($errortxt!="") {$errortxt .= "<br>";}
			$errorflag = true;					
			$errortxt .= "กรุณาระบุ - เพศของสมาชิกในทีม";
		}
		
	}
	if ($action == "memtembdel") {
				
		
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
	
	SELECT     temb_id, temb_tem_code, temb_name, temb_age, temb_gender, temb_detail, 
	temb_create_by, temb_create_date, temb_update_by, temb_update_date
	FROM         temb_det
	*/
	if (!$errorflag) {
		if ($action=="memteamadd") {
			
			$temb_id = getnewid("temb_id","temb_det",$conn);
			
			$sql_add = " INSERT INTO temb_det (" . 
			" temb_id,temb_tem_code,temb_name,".
			" temb_age,temb_gender,".
			" temb_create_by,temb_create_date)" .					
			" VALUES('$temb_id','$temb_tem_code','$memb_name','$memb_age',".
			" '$memb_gender',".
			" '$user_login','$today')";
			$result_add = sqlsrv_query($conn, $sql_add);
			if ($result_add) {
				$r="1";
				$errortxt="add success";
				$nb=encrypt($temb_tem_code, $key);
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
		if ($action=="memtembdel") {
			array_push($params, $temb_id);
			$sql_del = "delete from temb_det WHERE temb_id = ?";
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