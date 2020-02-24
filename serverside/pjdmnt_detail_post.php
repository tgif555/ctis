<?php
	include("../_incs/acunx_metaheader.php");
	include("../_incs/chksession.php");  
	include("../_incs/config.php");	
	include("../_incs/funcServer.php");	
	include("../_incs/acunx_cookie_var.php");
	include "../_incs/acunx_csrf_var.php";

	if (($_SERVER['REQUEST_METHOD'] == 'POST')) {
		if (!matchToken($csrf_key,$user_login)) {
			echo "System detect CSRF attack666!!";
			exit;
		}
	}
	$params = array();
	
	date_default_timezone_set('Asia/Bangkok');
	
	$today = date("Y-m-d H:i:s");  	
	$pg = html_escape($_REQUEST['pg']);
	$action = html_escape($_POST['action']);
	$qtm_nbr = html_escape($_POST['qtm_nbr']);
	$qtm_name = mssql_escape($_POST['qtm_name']);
	$pjm_nbr = html_escape($_POST['pjm_nbr']);
	
	//INPUT VALIDATION
	$errorflag = false;
	$errortxt = "";
	if (inlist("add_qtm_project,edit_qtd_product",$action)) {
		if ($qtm_nbr != "") {
			$params = array($qtm_nbr);
			$sql = "select * from qtm_mstr where qtm_nbr = ?";
			$result = sqlsrv_query($conn, $sql,$params);
			$row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
			if (!$row) {	
				if ($errortxt!="") {$errortxt .= "<br>";}
				$errorflag = true;					
				$errortxt .= "$qtm_nbr Quotation Number นี้ ที่ระบุไม่มีในระบบ";
			}
			else 
			{
				$qtm_name = findsqlval("qtm_mstr","qtm_name","qtm_nbr",$qtm_nbr,$conn);
			}
		}			
	}
	
	if ($action == "add_qtm_project") {
		//$qtd_id = getnewqtddetid($qtm_nbr,$conn);
		if (!$errorflag) {			
			$sql_add = " update qtm_mstr set qtm_pjm_nbr ='".$pjm_nbr."', qtm_update_by ='".$user_login."', qtm_update_date ='".$today."' , qtm_whocanread = (qtm_whocanread + ',$user_login'), qtm_curprocessor ='".$user_login."' where qtm_nbr ='".$qtm_nbr."'";
			
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
				$errortxt .=$sql_add;
			}
			echo '{"r":"'.$r.'","e":"'.$errortxt.'","nb":"'.$nb.'","pg":"'.$pg.'"}';
		}
		else {
			$r="0";
			$nb="";
			echo '{"r":"'.$r.'","e":"'.$errortxt.'","nb":"'.$nb.'","pg":"'.$pg.'"}';
		}
	}
	
	if ($action=="edit_qtd_product") {
		$qtd_id = mssql_escape($_POST['qtd_id']);
		if (!$errorflag) {
			$qtd_contractor_amt = $qtd_contractor_price;
			$qtd_contractor_unit_amt = 0;
			$qtd_contractor_disc_amt = 0;
			if ((double)$qtd_contractor_disc > 0) {
				if ($qtd_contractor_disc_unit == "P") {
					$qtd_contractor_disc_amt = $qtd_contractor_price * $qtd_contractor_disc /100;
					$qtd_contractor_unit_amt = $qtd_contractor_price - $qtd_contractor_disc_amt;
				}
				if ($qtd_contractor_disc_unit == "B") {
					$qtd_contractor_disc_amt = $qtd_contractor_disc;
					$qtd_contractor_unit_amt = $qtd_contractor_amt - $qtd_contractor_disc;
				}
			}
			else {
				$qtd_contractor_unit_amt = $qtd_contractor_amt;	
			}
			$qtd_contractor_auction_unit_amt = $qtd_contractor_unit_amt;
			
			$params = array($qtd_id);
			$sql_edit = "UPDATE qtd_det" .
				" SET qtd_mat_code = '$qtd_mat_code',
				qtd_mat_name = '$qtd_mat_name',
				qtd_qty = '$qtd_qty',
				qtd_unit_code = '$qtd_unit_code',
				qtd_customer_price = '$qtd_customer_price',
				qtd_customer_disc = '$qtd_customer_disc',
				qtd_customer_disc_unit = '$qtd_customer_disc_unit',
				qtd_contractor_price = '$qtd_contractor_price',
				qtd_contractor_disc = '$qtd_contractor_disc',
				qtd_contractor_disc_unit = '$qtd_contractor_disc_unit',
				qtd_contractor_auction_unit_amt = '$qtd_contractor_auction_unit_amt',
				qtd_remark = '$qtd_remark',
				qtd_create_by = '$user_login',
				qtd_create_date = '$today' " .
				" WHERE qtd_id = ?";
			
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
			echo '{"r":"'.$r.'","e":"'.$errortxt.'","nb":"'.$nb.'","pg":"'.$pg.'"}';
		}
		else {
			$r="0";
			$nb="";
			echo '{"r":"'.$r.'","e":"'.$errortxt.'","nb":"'.$nb.'","pg":"'.$pg.'"}';
		}
	}
	if ($action == "del_qtm_project") {
		
		$pjm_nbr = mssql_escape($_POST['pjm_nbr']);
		$qtm_nbr = mssql_escape($_POST['qtm_nbr']);
		$params = array($qtd_id);
		$sql_del = "update qtm_mstr set qtm_pjm_nbr ='' where qtm_nbr ='".$qtm_nbr."' and qtm_pjm_nbr ='".$pjm_nbr."'";	
		$result_del = sqlsrv_query($conn,$sql_del,$params);
		
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
		echo '{"r":"'.$r.'","e":"'.$errortxt.'","nb":"'.$nb.'","pg":"'.$pg.'"}';
	}
?> 