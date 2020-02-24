<?php
	include("../_incs/acunx_metaheader.php");
	include("../_incs/chksession.php");  
	include("../_incs/config.php");	
	include("../_incs/funcServer.php");	
	include("../_incs/acunx_cookie_var.php");
	include "../_incs/acunx_csrf_var.php";

	if (($_SERVER['REQUEST_METHOD'] == 'POST')) {
		if (!matchToken($csrf_key,$user_login)) {
			echo "System detect CSRF attack8!!";
			exit;
		}
	}
	$params = array();
	
	set_time_limit(0);
	date_default_timezone_set('Asia/Bangkok');
	$today = date("Y-m-d H:i:s");
	$errortxt = "";
	$allow_post = false;

	$pg = html_escape($_REQUEST['pg']);
	$action = html_escape($_POST['action']);
	
	//--1. Parameter From pjmadd.php
	//-- Section I : Project Information
	$pjm_nbr = mssql_escape($_POST['pjm_nbr']);	
	$pjm_nbr = mssql_escape($_POST['pjm_nbr']);	
	$pjm_name = mssql_escape($_POST['pjm_name']);
	$pjm_pjt_code = mssql_escape($_POST['pjm_pjt_code']);
	
	$pjm_addr = mssql_escape($_POST['pjm_addr']);
	$pjm_district = mssql_escape($_POST['pjm_district']);
	$pjm_amphur = mssql_escape($_POST['pjm_amphur']);
	$pjm_province = mssql_escape($_POST['pjm_province']);
	$pjm_zipcode = mssql_escape($_POST['pjm_zipcode']);	
	$pjm_latitude = mssql_escape($_POST['pjm_latitude']);
	$pjm_longtitude = mssql_escape($_POST['pjm_longtitude']);
	
	//-- Section II : Customer Information & Contact Information
	$pjm_custpj_code = mssql_escape($_POST['pjm_custpj_code']);
	// if ($pjm_custpj_code == "" || $pjm_custpj_code == "DUMMY") {
		// $pjm_custpj_code = "DUMMY";
		// $pjm_custpj_code = mssql_escape($_POST['pjm_custpj_code']);
	// }
	// else {
		// $pjm_custpj_code = findsqlval("custpj_mstr","custpj_name","custpj_code",$pjm_custpj_code,$conn);
	// }
	
	$pjm_contact_name = mssql_escape($_POST['pjm_contact_name']);
	$pjm_contact_addr = mssql_escape($_POST['pjm_contact_addr']);	
	$pjm_contact_email = mssql_escape($_POST['pjm_contact_email']);
	$pjm_contact_tel = mssql_escape($_POST['pjm_contact_tel']);
	$pjm_contact_lineid = mssql_escape($_POST['pjm_contact_lineid']);
	
	//-- Section III : Project Detail
	
	$pjm_start_date = mssql_escape($_POST['pjm_start_date']);
	$pjm_budget = mssql_escape($_POST['pjm_budget']);
	$pjm_paymth_code = mssql_escape($_POST['pjm_paymth_code']);
	$pjm_deposit_amt = mssql_escape($_POST['pjm_deposit_amt']);
	$pjm_end_date = mssql_escape($_POST['pjm_end_date']);
	$pjm_per_disc = mssql_escape($_POST['pjm_per_disc']);
	$pjm_buy_scg = mssql_escape($_POST['pjm_buy_scg']);
	$pjm_area_size = mssql_escape($_POST['pjm_area_size']);
	$pjm_from_channel = mssql_escape($_POST['pjm_from_channel']);
	$pjm_amt_disc = mssql_escape($_POST['pjm_amt_disc']);
	$pjm_buy_scg_custcode = mssql_escape($_POST['pjm_buy_scg_custcode']);
	$pjm_sc_code = mssql_escape($_POST['pjm_sc_code']);
	
	//-- Section IV : System Setting & System Requirement
	$pjm_pjst_code ="OPEN";
	$pjm_create_by = $user_login;
	$pjm_create_date = $today;
	//-- End Parameter From pjmadd.php
	
	//--2. INPUT VALIDATION
	$errorflag = false;
	$errortxt = "";
	if (inlist("pjmadd,pjmedit",$action)) {	
		// Section I VALIDATION
		if ($pjm_name=="") {
			if ($errortxt!="") {$errortxt .= "<br>";}
			$errorflag = true;					
			$errortxt .= "กรุณาระบุ - [ Project Name ]";
		}
		if ($pjm_pjt_code == "") {
			if ($errortxt!="") {$errortxt .= "<br>";}
			$errorflag = true;					
			$errortxt .= "กรุณาระบุ - [ Project Type ]";
		}
		
		if ($pjm_addr=="") {
			if ($errortxt!="") {$errortxt .= "<br>";}
			$errorflag = true;					
			$errortxt .= "กรุณาระบุ - [ Address]";
		}
		if ($pjm_district=="") {
			if ($errortxt!="") {$errortxt .= "<br>";}
			$errorflag = true;					
			$errortxt .= "กรุณาระบุ - [ District]";
		}
		if ($pjm_amphur=="") {
			if ($errortxt!="") {$errortxt .= "<br>";}
			$errorflag = true;					
			$errortxt .= "กรุณาระบุ - [ Amphur]";
		}
		if ($pjm_province=="") {
			if ($errortxt!="") {$errortxt .= "<br>";}
			$errorflag = true;					
			$errortxt .= "กรุณาระบุ - [ Province]";
		}
		if ($pjm_zipcode=="") {
			if ($errortxt!="") {$errortxt .= "<br>";}
			$errorflag = true;					
			$errortxt .= "กรุณาระบุ - [ Zip Code]";
		}
		if ($pjm_latitude=="") {
			if ($errortxt!="") {$errortxt .= "<br>";}
			$errorflag = true;					
			$errortxt .= "กรุณาระบุ - [ Project latitude]";
		}
		if ($pjm_longtitude=="") {
			if ($errortxt!="") {$errortxt .= "<br>";}
			$errorflag = true;					
			$errortxt .= "กรุณาระบุ - [ Project longtitude]";
		}		
		// Section II VALIDATION
		if ($pjm_custpj_code !="") {
			$params = array($pjm_custpj_code);
			$sql = "select custpj_code from custpj_mstr where custpj_code = ?";
			$result = sqlsrv_query($conn, $sql,$params);
			$row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
			if (!$row) {	
				if ($errortxt!="") {$errortxt .= "<br>";}
				$errorflag = true;					
				$errortxt .= "$pjm_custpj_code รหัสลูกค้าที่ระบุไม่มีในระบบ";
			}
		}
		else  {
			if ($errortxt!="") {$errortxt .= "<br>";}
			$errorflag = true;					
			$errortxt .= "กรุณาระบุ - [ Project Customer Code]";
		}
		
		if ($pjm_contact_name=="") {
			if ($errortxt!="") {$errortxt .= "<br>";}
			$errorflag = true;					
			$errortxt .= "กรุณาระบุ - [Contact Name]";
		}
		if ($pjm_contact_addr=="") {
			if ($errortxt!="") {$errortxt .= "<br>";}
			$errorflag = true;					
			$errortxt .= "กรุณาระบุ - [ Contact Address]";
		}
		if ($pjm_contact_email=="") {
			if ($errortxt!="") {$errortxt .= "<br>";}
			$errorflag = true;					
			$errortxt .= "กรุณาระบุ - [ Contact Email]";
		}
		if ($pjm_contact_tel=="") {
			if ($errortxt!="") {$errortxt .= "<br>";}
			$errorflag = true;					
			$errortxt .= "กรุณาระบุ - [ Contact Telephone]";
		}
		if ($pjm_contact_lineid=="") {
			if ($errortxt!="") {$errortxt .= "<br>";}
			$errorflag = true;					
			$errortxt .= "กรุณาระบุ - [ Contact Line ID ]";
		}
		// Section III VALIDATION

		if($pjm_start_date==""){
			if ($errortxt!="") {$errortxt .= "<br>";}
			$errorflag = true;					
			$errortxt .= "กรุณาระบุ - [ Start Date]";	
		} 
		else if (!isdate($pjm_start_date)) {
			if ($errortxt!="") {$errortxt .= "<br>";}
			$errorflag = true;					
			$errortxt .= "กรุณาระบุ - [ Start Date] ให้ถูกต้องตามรูปแบบ วว/ดด/ปปปป เป็น ค.ศ.";	
		} 
		else {
			$pjm_start_date = ymd($pjm_start_date);
		}
		
		if ($pjm_budget != "") {
			if (!is_numeric($pjm_budget)) {
				if ($errortxt!="") {$errortxt .= "<br>";}
				$errorflag = true;					
				$errortxt .= "กรุณาระบุ - [ Budget Amount must be Numeric ]";
			}			
		}
		else {
			if ($errortxt!="") {$errortxt .= "<br>";}
				$errorflag = true;					
				$errortxt .= "กรุณาระบุ - [  Budget Amount]";
		}
		if ($pjm_paymth_code=="") {
			if ($errortxt!="") {$errortxt .= "<br>";}
			$errorflag = true;					
			$errortxt .= "กรุณาระบุ - [ Payment Term]";
		}
		if ($pjm_deposit_amt != "") {
			if (!is_numeric($pjm_deposit_amt)) {
				if ($errortxt!="") {$errortxt .= "<br>";}
				$errorflag = true;					
				$errortxt .= "กรุณาระบุ - [ Deposit Amount must be Numeric ]";
			}
		}
		else {
			if ($errortxt!="") {$errortxt .= "<br>";}
				$errorflag = true;					
				$errortxt .= "กรุณาระบุ - [  Deposit Amount]";
		}
		if($pjm_end_date==""){
			if ($errortxt!="") {$errortxt .= "<br>";}
			$errorflag = true;					
			$errortxt .= "กรุณาระบุ - [ End Date]";	
		} 
		else if (!isdate($pjm_end_date)) {
			if ($errortxt!="") {$errortxt .= "<br>";}
			$errorflag = true;					
			$errortxt .= "กรุณาระบุ - [ End Date] ให้ถูกต้องตามรูปแบบ วว/ดด/ปปปป เป็น ค.ศ.";	
		} 
		else {
			if($pjm_start_date != "")
			{
				$start_date = $pjm_start_date;
				$end_date = ymd($pjm_end_date);
				$day_diff = day_diff_sign($start_date,$end_date);
				
				if($day_diff < 0)
				{
					if ($errortxt!="") {$errortxt .= "<br>";}
						$errorflag = true;					
						$errortxt .= "กรุณาระบุ - [ End Date Must More Than Start Date]";
				}
			}	
			$pjm_end_date = ymd($pjm_end_date);
		}
		
		if ($pjm_per_disc != "") {
			if (!is_numeric($pjm_per_disc)) {
				if ($errortxt!="") {$errortxt .= "<br>";}
				$errorflag = true;					
				$errortxt .= "กรุณาระบุ - [ Percent of Discount (%) must be Numeric]";
			}			
		}
		else {
			if ($errortxt!="") {$errortxt .= "<br>";}
				$errorflag = true;					
				$errortxt .= "กรุณาระบุ - [  Percent of Discount (%)]";
		}
		if ($pjm_buy_scg=="") {
			if ($errortxt!="") {$errortxt .= "<br>";}
			$errorflag = true;					
			$errortxt .= "กรุณาระบุ - [ ลูกค้าซื้อสินค้าของ SCG หรือไม่ ?]";
		}
		if ($pjm_area_size=="") {
			if ($errortxt!="") {$errortxt .= "<br>";}
			$errorflag = true;					
			$errortxt .= "กรุณาระบุ - [ Area Size]";
		}
		if ($pjm_from_channel=="") {
			if ($errortxt!="") {$errortxt .= "<br>";}
			$errorflag = true;					
			$errortxt .= "กรุณาระบุ - [ Channel]";
		}
		if ($pjm_amt_disc != "") {
			if (!is_numeric($pjm_amt_disc)) {
				if ($errortxt!="") {$errortxt .= "<br>";}
				$errorflag = true;					
				$errortxt .= "กรุณาระบุ - [  Amount of Discount (Baht) must be Numeric]";
			}			
		}
		else {
			if ($errortxt!="") {$errortxt .= "<br>";}
				$errorflag = true;					
				$errortxt .= "กรุณาระบุ - [  Amount of Discount (Baht)]";
		}
		if ($pjm_buy_scg_custcode=="") {
			if ($errortxt!="") {$errortxt .= "<br>";}
			$errorflag = true;					
			$errortxt .= "กรุณาระบุ - [ SCG Custcode]";
		}
		if ($pjm_sc_code=="") {
			if ($errortxt!="") {$errortxt .= "<br>";}
			$errorflag = true;					
			$errortxt .= "กรุณาระบุ - [ Site Consultant]";
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
		$pjm_nbr = getpjmnbr("PJ-",$conn);
		if ($pjm_nbr == "0") {
			if ($errortxt!="") {$errortxt .= "<br>";}
			$errorflag = true;					
			$errortxt .= "ไม่สามารถสร้าง Project รหัส $pjm_nbr นี้ได้";	
		}
		if (!$errorflag) {
			$sql_add = " INSERT INTO pjm_mstr (
			pjm_nbr
      ,pjm_name
      ,pjm_addr
	  ,pjm_district
      ,pjm_amphur
      ,pjm_province
      ,pjm_zipcode
      ,pjm_latitude
      ,pjm_longtitude
      ,pjm_pjt_code
      ,pjm_buy_scg
      ,pjm_buy_scg_custcode
      ,pjm_from_channel
      ,pjm_start_date
      ,pjm_end_date
      ,pjm_budget
      ,pjm_per_disc
      ,pjm_amt_disc
      ,pjm_deposit_amt
      ,pjm_custpj_code
      ,pjm_contact_name
      ,pjm_contact_addr
      ,pjm_contact_tel
      ,pjm_contact_lineid
      ,pjm_contact_email
      ,pjm_area_size
      ,pjm_work_detail
      ,pjm_paymth_code
      ,pjm_sc_code
      ,pjm_pjst_code
      ,pjm_create_by
      ,pjm_create_date)".
			" VALUES (
			'$pjm_nbr'
,'$pjm_name'
,'$pjm_addr'
,'$pjm_district'
,'$pjm_amphur'
,'$pjm_province'
,'$pjm_zipcode'
,'$pjm_latitude'
,'$pjm_longtitude'
,'$pjm_pjt_code'
,'$pjm_buy_scg'
,'$pjm_buy_scg_custcode'
,'$pjm_from_channel'
,'$pjm_start_date'
,'$pjm_end_date'
,'$pjm_budget'
,'$pjm_per_disc'
,'$pjm_amt_disc'
,'$pjm_deposit_amt'
,'$pjm_custpj_code'
,'$pjm_contact_name'
,'$pjm_contact_addr'
,'$pjm_contact_tel'
,'$pjm_contact_lineid'
,'$pjm_contact_email'
,'$pjm_area_size'
,'$pjm_work_detail'
,'$pjm_paymth_code'
,'$pjm_sc_code'
,'$pjm_pjst_code'
,'$pjm_create_by'
,'$pjm_create_date')";
			$result_add = sqlsrv_query($conn, $sql_add);
			if ($result_add) {
				$r="1";
				$errortxt="Insert success.";
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
		//$allow_post = false;
		//$pjm_nbr = html_escape(decrypt($_REQUEST['pjm_nbr'], $key));
		//$params = array($qtm_nbr);
		//$params = array($pjm_nbr);
		// $sql_pjm_edit = "SELECT pjm_curprocessor from pjm_mstr where pjm_nbr = ?";
		// $result_pjm_edit = sqlsrv_query($conn, $sql_pjm_edit, $params);	
		// $r_pjm_edit = sqlsrv_fetch_array($result_pjm_edit, SQLSRV_FETCH_ASSOC);		
		// if ($r_pjm_edit) {
			// $pjm_curprocessor_check = $r_pjm_edit['pjm_curprocessor'];
			
			// if (inlist($pjm_curprocessor_check,$user_login)) {
				// $allow_post = true;
			// }
			// else {
				// $pjm_curprocessor_role_access = "";
				// $pjm_curprocessor_role_array = explode(",",$user_role);
				// for ($c=0;$c<count($pjm_curprocessor_role_array);$c++) {
					// if (inlist($pjm_curprocessor_check,$pjm_curprocessor_role_array[$c])) {
						// $allow_post = true;
						// break;
					// }
				// }
			// }
		// }
		// if (!$allow_post) {
			// if ($errortxt!="") {$errortxt .= "<br>";}
			// $errorflag = true;					
			// $errortxt .= "คุณไม่มีสิทธิ์ใช้งานหน้านี้".$pjm_nbr."tt";
		// }
		//$pjm_nbr = html_escape(decrypt($_REQUEST['pjm_nbr'], $key));
		if (!$errorflag) {
			$params = array($pjm_nbr);
			$sql_edit = "UPDATE pjm_mstr".
				" SET pjm_name = '$pjm_name',
				pjm_addr = '$pjm_addr',
				pjm_district = '$pjm_district',
				pjm_amphur = '$pjm_amphur',
				pjm_province = '$pjm_province',
				pjm_zipcode = '$pjm_zipcode',
				pjm_latitude = '$pjm_latitude',
				pjm_longtitude = '$pjm_longtitude',
				pjm_pjt_code = '$pjm_pjt_code',
				pjm_buy_scg = '$pjm_buy_scg',
				pjm_buy_scg_custcode = '$pjm_buy_scg_custcode',
				pjm_from_channel = '$pjm_from_channel',
				pjm_start_date = '$pjm_start_date',
				pjm_end_date = '$pjm_end_date',
				pjm_budget = '$pjm_budget',
				pjm_per_disc = '$pjm_per_disc',
				pjm_amt_disc = '$pjm_amt_disc',
				pjm_deposit_amt = '$pjm_deposit_amt',
				pjm_custpj_code = '$pjm_custpj_code',
				pjm_contact_name = '$pjm_contact_name',
				pjm_contact_addr = '$pjm_contact_addr',
				pjm_contact_tel = '$pjm_contact_tel',
				pjm_contact_lineid = '$pjm_contact_lineid',
				pjm_contact_email = '$pjm_contact_email',
				pjm_area_size = '$pjm_area_size',
				pjm_work_detail = '$pjm_work_detail',
				pjm_paymth_code = '$pjm_paymth_code',
				pjm_sc_code = '$pjm_sc_code',
				pjm_pjst_code = '$pjm_pjst_code',
				pjm_update_by = '$user_login',
				pjm_update_date  = '$today'			
				WHERE pjm_nbr = '$pjm_nbr'";
			$result_edit = sqlsrv_query($conn,$sql_edit);
			if ($result_edit) {
				$r="1";
				
				$nb=encrypt($pjm_nbr, $key);
				$errortxt="Edit success.";
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
		//$pjm_nbr = html_escape(decrypt($_REQUEST['pjm_nbr'], $key));
		//Case 1. When User Delete Project : setting CLOSED Status for Project 
		// $params = array($user_login,$today,$pjm_nbr);
		// $sql_del = "UPDATE pjm_mstr SET ".		
		// "pjm_pjst_code ='CLOSED'," .			
		// "pjm_update_by = ? ," .
		// "pjm_update_date = ? " .
		// " WHERE pjm_nbr = ?";				
		// $result_del = sqlsrv_query($conn,$sql_del,$params);
		
		// if ($result_del) {
				// $r="1";
				// $errortxt="delete success.";
				// $nb=encrypt($pjm_nbr, $key);
		// }
		// else {
			// $r="0";
			// $nb="";
			// if( ($errors = sqlsrv_errors() ) != null) {
				// foreach( $errors as $error ) {
					// $errortxt .= "SQLSTATE: ".$error[ 'SQLSTATE']."<br />".
						// "code: ".$error[ 'code']."<br />".
						// "message: ".$error[ 'message']."<br />";
				// }
			// }
		// }
		
	
		//Case 2. Check Reference Project Before Delete
		$rowCounts = 0;	
		$sql_check_del = "select count(qtm_nbr) as rowCounts from qtm_mstr  WHERE qtm_pjm_nbr = ? ";
		$params_check_del = array($pjm_nbr);
		$result_check_del = sqlsrv_query($conn,$sql_check_del,$params_check_del, array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
		while($array_check_del = sqlsrv_fetch_array($result_check_del)){	
			$rowCounts = $array_check_del['rowCounts'];
		}
		if($rowCounts > 0)
		{  
			$r="0";
			$nb="";
			$errortxt="Cannot Delete, You have $rowCounts Quotation reference this project";
		}
		else 
		{
			$sql_del = "delete from pjm_mstr where pjm_nbr = ?";		
			$result_del = sqlsrv_query($conn,$sql_del,$params_check_del, array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
		
			if ($result_del) {
				$r="1";
				$errortxt="Delete success.";
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
		}
		
		echo '{"r":"'.$r.'","e":"'.$errortxt.'","nb":"'.$nb.'","pg":"'.$pg.'"}';
	}
?>