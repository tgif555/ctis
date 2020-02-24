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
	$pg = html_escape($_REQUEST['pg']);
	$action = html_escape($_POST['action']);
	$qtm_nbr = html_escape($_POST['qtm_nbr']);
	$qtd_mat_code = mssql_escape($_POST['qtd_mat_code']);
	$qtd_mat_name = mssql_escape($_POST['qtd_mat_name']);
	$qtd_customer_price = mssql_escape($_POST['qtd_customer_price']);
	$qtd_customer_disc = mssql_escape($_POST['qtd_customer_disc']);
	$qtd_customer_disc_unit = mssql_escape($_POST['qtd_customer_disc_unit']);
	$qtd_contractor_price = mssql_escape($_POST['qtd_contractor_price']);
	$qtd_contractor_disc = mssql_escape($_POST['qtd_contractor_disc']);
	$qtd_contractor_disc_unit = mssql_escape($_POST['qtd_contractor_disc_unit']);
	$qtd_qty = mssql_escape($_POST['qtd_qty']);
	$qtd_unit_code = mssql_escape($_POST['qtd_unit_code']);
	$qtd_remark = mssql_escape($_POST['qtd_remark']);
	//INPUT VALIDATION
	$errorflag = false;
	$errortxt = "";
	if (inlist("add_qtd_product,edit_qtd_product",$action)) {
		if ($qtd_mat_code !="") {
			if ($qtd_mat_code != "DUMMY") {
				$params = array($qtd_mat_code);
				$sql = "select * from mat_mstr where mat_code = ?";
				$result = sqlsrv_query($conn, $sql,$params);
				$row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
				if (!$row) {	
					if ($errortxt!="") {$errortxt .= "<br>";}
					$errorflag = true;					
					$errortxt .= "รหัสสินค้าที่ระบุไม่มีในระบบ";
				}
				else {
					$mat_unit_code = html_escape($row['mat_unit_code']);
					$mat_unit_name = findsqlval("unit_mstr","unit_name","unit_code",$mat_unit_code,$conn);
				}
			}
			else {
				//IS DUMMY
				if ($qtd_mat_name == "") {
					if ($errortxt!="") {$errortxt .= "<br>";}
					$errorflag = true;					
					$errortxt .= "กรุณาระบุ - [ ชื่อสินค้า ]";
				}
			}
		}
		else {
			if ($qtd_mat_name == "") {
				if ($errortxt!="") {$errortxt .= "<br>";}
				$errorflag = true;					
				$errortxt .= "กรุณาระบุ - [ ชื่อสินค้า ]";
			}
		}
		if ($qtd_customer_price=="") {
			if ($errortxt!="") {$errortxt .= "<br>";}
			$errorflag = true;					
			$errortxt .= "กรุณาระบุ - [ ราคาสินค้าต่อหน่วยสำหรับลูกค้า ]";
		}
		else {
			if (!is_numeric($qtd_customer_price)) {
				if ($errortxt!="") {$errortxt .= "<br>";}
				$errorflag = true;					
				$errortxt .= "กรุณาระบุ - [ ราคาสินค้าต่อหน่วยสำหรับลูกค้าเป็นตัวเลข ]";
			}
		}
		if ($qtd_customer_disc!="") {	
			if (!is_numeric($qtd_customer_disc)) {
				if ($errortxt!="") {$errortxt .= "<br>";}
				$errorflag = true;					
				$errortxt .= "กรุณาระบุ - [ ส่วนลดสินค้าต่อหน่วยสำหรับลูกค้าเป็นตัวเลข ]";
			}
			else {
				if ($qtd_customer_disc == 0) {
					$qtd_customer_disc_unit = "B";
				}
			}
			if ($qtd_customer_disc_unit == "") {
				if ($errortxt!="") {$errortxt .= "<br>";}
				$errorflag = true;					
				$errortxt .= "กรุณาระบุ - [ หน่วยส่วนลดสินค้าต่อหน่วยสำหรับลูกค้า ]";
			}
		}
		else {
			$qtd_customer_disc = 0;
			$qtd_customer_disc_unit = "B";
		}
		if ($qtd_contractor_price=="") {
			if ($errortxt!="") {$errortxt .= "<br>";}
			$errorflag = true;					
			$errortxt .= "กรุณาระบุ - [ ราคาสินค้าต่อหน่วยสำหรับผู้รับเหมา ]";
		}
		else {
			if (!is_numeric($qtd_contractor_price)) {
				if ($errortxt!="") {$errortxt .= "<br>";}
				$errorflag = true;					
				$errortxt .= "กรุณาระบุ - [ ราคาสินค้าต่อหน่วยสำหรับผู้รับเหมาเป็นตัวเลข ]";
			}
		}
		if ($qtd_contractor_disc!="") {
			if (!is_numeric($qtd_contractor_disc)) {
				if ($errortxt!="") {$errortxt .= "<br>";}
				$errorflag = true;					
				$errortxt .= "กรุณาระบุ - [ ส่วนลดสินค้าต่อหน่วยสำหรับผู้รับเหมาเป็นตัวเลข ]";
			}
			else {
				if ($qtd_contractor_disc == 0) {
					$qtd_contractor_disc_unit = "B";
				}
			}
			if ($qtd_contractor_disc_unit == "") {
				if ($errortxt!="") {$errortxt .= "<br>";}
				$errorflag = true;					
				$errortxt .= "กรุณาระบุ - [ หน่วยส่วนลดสินค้าต่อหน่วยสำหรับผู้รับเหมา ]";
			}
		}
		else {
			$qtd_contractor_disc = 0;
			$qtd_contractor_disc_unit = "B";
		}
		if ($qtd_qty=="") {
			if ($errortxt!="") {$errortxt .= "<br>";}
			$errorflag = true;					
			$errortxt .= "กรุณาระบุ - [ จำนวนสินค้า ]";
		}
		else {
			if (!is_numeric($qtd_qty)) {
				if ($errortxt!="") {$errortxt .= "<br>";}
				$errorflag = true;					
				$errortxt .= "กรุณาระบุ - [ จำนวนสินค้าเป็นตัวเลข ]";
			}
		}
		if ($qtd_unit_code=="") {
			if ($errortxt!="") {$errortxt .= "<br>";}
			$errorflag = true;					
			$errortxt .= "กรุณาระบุ - [ หน่วยสินค้า ]";
		}
		else {
			if ($qtd_mat_code != "DUMMY") {
				if ($qtd_unit_code != $mat_unit_code) {
					if ($errortxt!="") {$errortxt .= "<br>";}
					$errorflag = true;					
					$errortxt .= "กรุณาระบุ - [ หน่วยสินค้าเป็น $mat_unit_name]";
				}
			}
		}
	}
	
	if ($action == "add_qtd_product") {
		$qtd_id = getnewqtddetid($qtm_nbr,$conn);
		if (!$errorflag) {
			//Contractor Cal
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
			
			$sql_add = " INSERT INTO qtd_det (
				qtd_id,qtd_qtm_nbr,
				qtd_mat_code,qtd_mat_name,qtd_qty,qtd_unit_code,
				qtd_customer_price,qtd_customer_disc,qtd_customer_disc_unit,
				qtd_contractor_price,qtd_contractor_disc,qtd_contractor_disc_unit,
				qtd_contractor_auction_unit_amt,
				qtd_remark,
				qtd_create_by,qtd_create_date)".
				" VALUES (
				'$qtd_id','$qtm_nbr',
				'$qtd_mat_code','$qtd_mat_name','$qtd_qty','$qtd_unit_code',
				'$qtd_customer_price','$qtd_customer_disc','$qtd_customer_disc_unit',
				'$qtd_contractor_price','$qtd_contractor_disc','$qtd_contractor_disc_unit',
				'$qtd_contractor_auction_unit_amt',
				'$qtd_remark',
				'$user_login','$today')";
			
			$result_add = sqlsrv_query($conn, $sql_add);
			//UPDATE TOTAL PRICE for qtm_mstr
			update_qtm_total_amt($qtm_nbr,$conn);

			$errortxt="";
			$nb=encrypt($qtm_nbr, $key);
			$ta=encrypt("tab_qtprod", $key);
			echo '{"r":"'.$r.'","e":"'.$errortxt.'","ta":"'.$ta.'","nb":"'.$nb.'","pg":"'.$pg.'"}';
		}
		else {
			$r="0";
			$nb="";
			$ta=encrypt("tab_qtprod", $key);
			echo '{"r":"'.$r.'","e":"'.$errortxt.'","ta":"'.$ta.'","nb":"'.$nb.'","pg":"'.$pg.'"}';
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
			//UPDATE TOTAL PRICE for qtm_mstr
			update_qtm_total_amt($qtm_nbr,$conn);
			
			$r="1";
			$errortxt="";
			$nb=encrypt($qtm_nbr, $key);
			$ta=encrypt("tab_qtprod", $key);
			echo '{"r":"'.$r.'","e":"'.$errortxt.'","ta":"'.$ta.'","nb":"'.$nb.'","pg":"'.$pg.'"}';
		}
		else {
			$r="0";
			$nb="";
			$ta=encrypt("tab_qtprod", $key);
			echo '{"r":"'.$r.'","e":"'.$errortxt.'","ta":"'.$ta.'","nb":"'.$nb.'","pg":"'.$pg.'"}';
		}
	}
	if ($action == "del_qtd_product") {
		$qtd_id = mssql_escape($_POST['qtd_id']);
		$params = array($qtd_id);
		$sql_del = "DELETE FROM qtd_det WHERE qtd_id = ?";	
		$result_del = sqlsrv_query($conn,$sql_del,$params);
		//UPDATE TOTAL PRICE for qtm_mstr
		update_qtm_total_amt($qtm_nbr,$conn);
		$r="1";
		$errortxt="";
		$nb=encrypt($qtm_nbr, $key);	
		$ta=encrypt("tab_qtprod", $key);
		echo '{"r":"'.$r.'","e":"'.$errortxt.'","ta":"'.$ta.'","nb":"'.$nb.'","pg":"'.$pg.'"}';
	}
	
	function update_qtm_total_amt($qtm_nbr,$conn) {
		//UPDATE TOTAL PRICE for qtm_mstr
		$qtm_customer_amt_total = 0;
		$qtm_contractor_amt_total = 0;
		$qtm_auction_amt_total = 0;
		
		$sql_sumqtdprice = "SELECT * from qtd_det WHERE qtd_qtm_nbr = '$qtm_nbr'";
		$result_sumqtdprice = sqlsrv_query( $conn, $sql_sumqtdprice);											
		while($rec_sumqtdprice = sqlsrv_fetch_array($result_sumqtdprice, SQLSRV_FETCH_ASSOC)) {
			$qtd_qty = $rec_sumqtdprice['qtd_qty'];
			$qtd_customer_price = $rec_sumqtdprice['qtd_customer_price'];
			$qtd_customer_disc = $rec_sumqtdprice['qtd_customer_disc'];
			$qtd_customer_disc_unit = $rec_sumqtdprice['qtd_customer_disc_unit'];
			$qtd_contractor_price = $rec_sumqtdprice['qtd_contractor_price'];
			$qtd_contractor_disc = $rec_sumqtdprice['qtd_contractor_disc'];
			$qtd_contractor_disc_unit = $rec_sumqtdprice['qtd_contractor_disc_unit'];
			$qtd_contractor_auction_unit_amt = $rec_sumqtdprice['qtd_contractor_auction_unit_amt'];
			
			//Customer Cal
			$qtd_customer_amt = 0;
			if ((double)$qtd_customer_disc > 0) {
				if ($qtd_customer_disc_unit == "P") {
					$qtd_customer_amt = $qtd_customer_price - ($qtd_customer_price * $qtd_customer_disc /100);
				}
				if ($qtd_customer_disc_unit == "B") {
					$qtd_customer_amt = $qtd_customer_price - $qtd_customer_disc;
				}
			}
			else {
				$qtd_customer_amt = $qtd_customer_price;	
			}
			//Customer
			$qtm_customer_amt_total = $qtm_customer_amt_total + ($qtd_qty * $qtd_customer_amt);
			//
			//Contractor Cal
			$qtd_contractor_amt = 0;
			if ((double)$qtd_contractor_disc > 0) {
				if ($qtd_contractor_disc_unit == "P") {
					$qtd_contractor_amt = $qtd_contractor_price - ($qtd_contractor_price * $qtd_contractor_disc /100);
				}
				if ($qtd_contractor_disc_unit == "B") {
					$qtd_contractor_amt = $qtd_contractor_price - $qtd_contractor_disc;
				}
			}
			else {
				$qtd_contractor_amt = $qtd_contractor_price;	
			}
			//Contractor
			$qtm_contractor_amt_total = $qtm_contractor_amt_total + ($qtd_qty * $qtd_contractor_amt);
			//Auction
			$qtm_auction_amt_total = $qtm_auction_amt_total + $qtd_contractor_auction_unit_amt;
		}
		//
		$sql_updateqtmprice = " UPDATE qtm_mstr SET ".
			"qtm_customer_amt = '$qtm_customer_amt_total'," .
			"qtm_contractor_amt = '$qtm_contractor_amt_total'," .
			"qtm_auction_amt = '$qtm_auction_amt_total'" .
			" WHERE qtm_nbr = '$qtm_nbr'";
		$result_updateqtmprice = sqlsrv_query($conn, $sql_updateqtmprice);
		//END UPDATE TOTAL PRICE
	}
?> 