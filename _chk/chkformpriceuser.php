<?php
	include("../_incs/chksession.php");	
	include("../_incs/config.php");
	include("../_incs/funcServer.php");
	
	$errorflag = false;					
	$result_text = "";
	$qtm_nbr = mssql_escape($_POST["qtmnumber"]);
	///
	if ($gbv_qt_price_approver1 != "") {
		$sql_emp = "select emp_user_id,emp_email_bus,emp_th_firstname+' '+emp_th_lastname as emp_fullname from emp_mstr where emp_user_id = '$gbv_qt_price_approver1'";
		$result_emp = sqlsrv_query($conn, $sql_emp);
		$row_emp = sqlsrv_fetch_array($result_emp, SQLSRV_FETCH_ASSOC);
		if ($row_emp) {
			$emp_email_bus = explode("@",trim($row_emp['emp_email_bus']));
			$emp_fullname = $row_emp['emp_fullname'];
			if (strtoupper($emp_email_bus[1]) != "SCG.COM") {
				$errorflag = true;
				if ($result_text!="") {$result_text .= "<br>";}	
				$result_text .= "($emp_email_bus[0].$emp_email_bus[1])" . " " . "Email ของผู้อนุมัติคนที่ 1 (คุณ$emp_fullname) ที่เลือกไม่ใช้ @SCG.COM";
			}
		}
		else {
			$errorflag = true;
			if ($result_text!="") {$result_text .= "<br>";}
			$result_text .= "ไม่พบผู้อนุมัติคนที่ 1 ในข้อมูลพนักงาน";
		}
	}
	else {
		$errorflag = true;
		if ($result_text!="") {$result_text .= "<br>";}
		$result_text .= "ไม่พบผู้อนุมัติคนที่ 1 ในข้อมูลพนักงาน";
	}
	$params = array($qtm_nbr);
	$sql_qtm = "SELECT * from qtm_mstr where qtm_nbr = ?";		
	$result_qtm = sqlsrv_query($conn, $sql_qtm,$params);		
	$rec_qtm = sqlsrv_fetch_array($result_qtm, SQLSRV_FETCH_ASSOC);
	if ($rec_qtm) {
		$qtm_customer_number = html_escape($rec_qtm['qtm_customer_number']);
		if ($qtm_customer_number == "DUMMY") {
			$errorflag = true;
			if ($result_text!="") {$result_text .= "<br>";}
			$result_text = "ระบบไม่อนุญาติให้ส่งเอกสารขออนุมัติเนื่องจากรหัสลูกค้ายังอยู่ในสถานะ DUMMY ค่ะ";
		}
	}
	
	if (!$errorflag) {
		$params = array($qtm_nbr);
		$sql = "SELECT count(*) as cnt FROM qtd_det where qtd_qtm_nbr = ?";		
		$result = sqlsrv_query($conn, $sql,$params);		
		$row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
		if ($row) {		
			if ($row["cnt"] > 0) {
				$result_text = "OK";
			}
			else {
				$result_text = "ไม่พบรายการกระเบื้องที่ขอเบิกตามหมายเลขใบเบิกนี้";	
			}
		}
		else {
			$result_text = "ไม่พบรายการกระเบื้องที่ขอเบิกตามหมายเลขใบเบิกนี้";
		}
	}
	echo $result_text;
?>