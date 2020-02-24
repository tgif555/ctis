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
 	
	//$pg = html_escape($_REQUEST['pg']);
	$action = html_escape($_POST['action']);
	
	$tem_code = mssql_escape($_POST['tem_code']);		
	$tem_name = mssql_escape($_POST['team_name']);	
	//$tem_engm_code = mssql_escape($_POST['team_leader']);
	$tem_leader_name = mssql_escape($_POST['team_leader']);	
	$tem_detail = mssql_escape($_POST['tem_detail']);	
	$tem_addr = mssql_escape($_POST['team_address']);
	$tem_lineid = mssql_escape($_POST['tem_lineid']);
	$tem_tel = mssql_escape($_POST['team_tel']);
	
	$tem_district = mssql_escape($_POST['team_district']);
	$tem_amphur = mssql_escape($_POST['team_amphur']);
	$tem_province = mssql_escape($_POST['team_province']);
	$tem_zip = mssql_escape($_POST['team_zip']);	
	$tem_email = mssql_escape($_POST['team_email']);	
	$tem_active = mssql_escape($_POST['team_active']);	
	$tem_detail = mssql_escape($_POST['switch1']);
	$tem_create_by = $user_login;	
	$tem_create_date = $today ;	
	
	
	
	//INPUT VALIDATION
	$errorflag = false;
	$errortxt = "";
	
	if ($action == "teamadd") {
		if ($tem_name=="") {
			if ($errortxt!="") {$errortxt .= "<br>";}
			$errorflag = true;					
			$errortxt .= "กรุณาระบุ - [ Team TH Name]";
		}
		if ($tem_leader_name=="") {
			if ($errortxt!="") {$errortxt .= "<br>";}
			$errorflag = true;					
			$errortxt .= "กรุณาระบุ - [  หัวน้าทีม ]";
		}
		if ($tem_tel=="") {
			if ($errortxt!="") {$errortxt .= "<br>";}
			$errorflag = true;					
			$errortxt .= "กรุณาระบุ - [  เบอร์โทร ]";
		}
		if ($tem_addr=="") {
			if ($errortxt!="") {$errortxt .= "<br>";}
			$errorflag = true;					
			$errortxt .= "กรุณาระบุ - [  ที่อยู่ ]";
		}
		if ($tem_district=="") {
			if ($errortxt!="") {$errortxt .= "<br>";}
			$errorflag = true;					
			$errortxt .= "กรุณาระบุ - [  ตำบล/แขวง ]";
		}
		if ($tem_amphur=="") {
			if ($errortxt!="") {$errortxt .= "<br>";}
			$errorflag = true;					
			$errortxt .= "กรุณาระบุ - [  อำเภอ/เขต ]";
		}
		if ($tem_province=="") {
			if ($errortxt!="") {$errortxt .= "<br>";}
			$errorflag = true;					
			$errortxt .= "กรุณาระบุ - [  จังหวัด ]";
		}
		
		//Status
		
	}
	
	if ($action == "teamdel") {
		
		$params = array($tem_code);
		$sql = "select  qtm_tem_code from qtm_mstr where qtm_tem_code = ?";
		$result = sqlsrv_query($conn, $sql,$params);
		$row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
		if ($row) {	
			if ($errortxt!="") {$errortxt .= "<br>";}
			$errorflag = true;					
			$errortxt .= "[ไม่อนุญาติให้ลบ ทีมนี้] ถูกนำไปใช้ใน Quotation แล้ว";
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
		if ($action=="teamadd") {
			
			$tem_code = getnewteamid("T",$conn);
			//tem_district, tem_amphur, tem_province, tem_zipcode
			$sql_add = " INSERT INTO tem_mstr (" . 
			" tem_code,tem_name,tem_leader_name,".
			" tem_detail,tem_addr,tem_district, tem_amphur, tem_province, tem_zipcode,".
			" tem_tel,tem_lineid,tem_email,".
			" tem_active,".
			" tem_create_by,tem_create_date)" .					
			" VALUES('$tem_code','$tem_name','$tem_leader_name',".
			" '$tem_detailtem_detail','$tem_addr','$tem_district','$tem_amphur','$tem_province','$tem_zip',".
			" '$tem_tel', '$tem_lineid','$tem_email',".
			" '$tem_active',".
			" '$user_login','$today')";
			$result_add = sqlsrv_query($conn, $sql_add);
			if ($result_add) {
				$r="1";
				$errortxt="add success";
				$nb=encrypt($tem_code, $key);
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
		if ($action=="teamedit") {
			array_push($params, $tem_code);
			$sql_edit = "UPDATE tem_mstr SET ".
				" tem_name = '$tem_name',".
				" tem_leader_name = '$tem_leader_name',".
				" tem_detail = '$tem_detail',".
				" tem_addr = '$tem_addr',".
				" tem_district = '$tem_district',".
				" tem_amphur = '$tem_amphur',".
				" tem_province = '$tem_province',".
				" tem_zipcode = '$tem_zip',".
				" tem_tel = '$tem_tel',".
				" tem_lineid = '$tem_lineid',".
				" tem_email = '$tem_email',".
				" tem_active = '$tem_active',".
				" tem_update_by = '$user_login',".
				" tem_update_date = '$today'".
				" WHERE tem_code = ?";
			$result_edit = sqlsrv_query($conn,$sql_edit,$params);
			if ($result_edit) {
				$r="1";
				$errortxt="update success.";
				$nb=encrypt($tem_code, $key);
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
		if ($action=="teamdel") {
			array_push($params, $tem_code);
			$sql_del = "delete from tem_mstr WHERE tem_code = ?";
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
		$nb=encrypt($tem_code, $key);
		echo '{"r":"'.$r.'","e":"'.$errortxt.'","nb":"'.$nb.'","pg":"'.$pg.'"}';
	}
?>