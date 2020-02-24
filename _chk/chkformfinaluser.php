<?php
	include("../_incs/chksession.php");	
	include("../_incs/config.php");
	include("../_incs/funcServer.php");
	
	$errorflag = false;					
	$result_text = "";
	$qtm_nbr = mssql_escape($_POST["qtmnumber"]);
	///
	$params = array($qtm_nbr);
	$sql_aucm = "select TOP 1 aucm_nbr from aucm_mstr where aucm_qtm_nbr = ?";
	$result_aucm = sqlsrv_query($conn, $sql_aucm,$params);
	$row_aucm = sqlsrv_fetch_array($result_aucm, SQLSRV_FETCH_ASSOC);
	if (!$row_aucm) {
		$errorflag = true;
		if ($result_text!="") {$result_text .= "<br>";}
		$result_text .= "ไม่พบข้อมูล Auction : Contractor WIN";
	}
	$sql_custpay = "select TOP 1 custpay_id from custpay_det where custpay_qtm_nbr = ?";
	$result_custpay = sqlsrv_query($conn, $sql_custpay,$params);
	$row_custpay = sqlsrv_fetch_array($result_custpay, SQLSRV_FETCH_ASSOC);
	if (!$row_custpay) {
		$errorflag = true;
		if ($result_text!="") {$result_text .= "<br>";}
		$result_text .= "ไม่พบข้อมูล Customer Payment";
	}
	$sql_conspay = "select TOP 1 conspay_id from conspay_det where conspay_qtm_nbr = ?";
	$result_conspay = sqlsrv_query($conn, $sql_conspay,$params);
	$row_conspay = sqlsrv_fetch_array($result_conspay, SQLSRV_FETCH_ASSOC);
	if (!$row_conspay) {
		$errorflag = true;
		if ($result_text!="") {$result_text .= "<br>";}
		$result_text .= "ไม่พบข้อมูล Contractor Payment";
	}
	if (!$errorflag) {
		$result_text = "OK";
	}
	echo $result_text;
?>