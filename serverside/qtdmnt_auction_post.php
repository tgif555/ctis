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
	$aucd_tem_code = html_escape($_POST['aucd_tem_code']);
	$aucd_qtd_id_list = mssql_escape($_POST['aucd_qtd_id_list']);
	$aucd_auction_unit_amt_list = mssql_escape($_POST['aucd_auction_unit_amt_list']);
	
	//INPUT VALIDATION
	$errorflag = false;
	$errortxt = "";
	
	if (inlist("add_auction,edit_auction",$action)) {
		if ($aucd_tem_code=="") {
			if ($errortxt!="") {$errortxt .= "<br>";}
			$errorflag = true;					
			$errortxt .= "กรุณาระบุ - [ Contractor]";
		}
		if ($action == "add_auction") {
			//Check Contractor Available (เช็คว่า Contractor เคยสร้างมาแล้วหรือยัง)
			array_push($params, $qtm_nbr);
			array_push($params, $aucd_tem_code);
			$sql_contractor_exist = "SELECT * from aucm_mstr WHERE aucm_qtm_nbr = ? and aucm_tem_code = ?";
			$result_contractor_exist = sqlsrv_query($conn, $sql_contractor_exist,$params);	
			$rec_contractor_exist = sqlsrv_fetch_array($result_contractor_exist, SQLSRV_FETCH_ASSOC);	
			if ($rec_contractor_exist) {
				if ($errortxt!="") {$errortxt .= "<br>";}
				$errorflag = true;					
				$errortxt .= "Contractor นี้ได้สร้าง Auction แล้ว";
			}
		}
		$aucd_auction_unit_amt_array = explode("*^*",$aucd_auction_unit_amt_list);
		$aucd_qtd_id_array = explode(",",$aucd_qtd_id_list);
		$aucd_qtd_id_count = sizeof($aucd_qtd_id_array);
		for($i = 0; $i < $aucd_qtd_id_count;$i++) {
			$aucd_auction_unit_amt = $aucd_auction_unit_amt_array[$i];
			if ($aucd_auction_unit_amt=="") {
				if ($errortxt!="") {$errortxt .= "<br>";}
				$errorflag = true;					
				$errortxt .= "(บรรทัดที่ ". ($i+1).") กรุณาระบุ - [ ราคาเสนอ/หน่วย]";
			}
			else {
				if (!is_numeric($aucd_auction_unit_amt)) {
					if ($errortxt!="") {$errortxt .= "<br>";}
					$errorflag = true;					
					$errortxt .= "(บรรทัดที่ ". ($i+1) .") กรุณาระบุ - [ ราคาเสนอ/หน่วย] เป็นตัวเลข";
				}
			}
		}
	}
	if (!$errorflag) {
		if ($action == "add_auction") {
			$aucm_nbr = getaucmnumber($qtm_nbr,$conn);
			$sql_add_aucm = " INSERT INTO aucm_mstr (" . 
				"aucm_nbr,aucm_tem_code,aucm_qtm_nbr,aucm_auction_date,aucm_auction_price,".
				"aucm_step_code,aucm_is_delete,aucm_create_by,aucm_create_date) " .
				" VALUES (" .
				"'$aucm_nbr','$aucd_tem_code','$qtm_nbr','$today','0',".
				"'0','0','$user_login','$today')";
			$result_add_aucm = sqlsrv_query($conn, $sql_add_aucm);
			
			$aucm_auction_price = 0;
			$aucd_auction_unit_amt_array = explode("*^*",$aucd_auction_unit_amt_list);
			$aucd_qtd_id_array = explode(",",$aucd_qtd_id_list);
			$aucd_qtd_id_count = sizeof($aucd_qtd_id_array);
			for($i = 0; $i < $aucd_qtd_id_count;$i++) {
				$aucd_qtd_id = $aucd_qtd_id_array[$i];
				$aucd_auction_unit_amt = $aucd_auction_unit_amt_array[$i];
				//
				$sql_qtd = "SELECT * from qtd_det WHERE qtd_id = '$aucd_qtd_id'";
				$result_qtd = sqlsrv_query($conn, $sql_qtd);	
				$rec_qtd = sqlsrv_fetch_array($result_qtd, SQLSRV_FETCH_ASSOC);	
				
				if ($rec_qtd) {
					$aucd_id = getaucdid($aucm_nbr,$conn);
					$qtd_mat_code = mssql_escape($rec_qtd['qtd_mat_code']);
					$qtd_mat_name = mssql_escape($rec_qtd['qtd_mat_name']);
					$qtd_qty = mssql_escape($rec_qtd['qtd_qty']);
					$qtd_unit_code = mssql_escape($rec_qtd['qtd_unit_code']);
					$qtd_contractor_price = mssql_escape($rec_qtd['qtd_contractor_price']);
					$qtd_contractor_disc = mssql_escape($rec_qtd['qtd_contractor_disc']);
					$qtd_contractor_disc_unit = mssql_escape($rec_qtd['qtd_contractor_disc_unit']);
					$sql_add_aucd = " INSERT INTO aucd_det (" .
						"aucd_id,aucd_aucm_nbr,aucd_tem_code,aucd_qtm_nbr,".
						"aucd_qtd_id,aucd_mat_code,aucd_mat_name,".
						"aucd_contractor_qty,aucd_contractor_unit_code,".
						"aucd_contractor_price,aucd_contractor_disc,aucd_contractor_disc_unit,".
						"aucd_auction_unit_amt,aucd_create_by,aucd_create_date)".
						" VALUES (".
						"'$aucd_id','$aucm_nbr','$aucd_tem_code','$qtm_nbr',".
						"'$aucd_qtd_id','$qtd_mat_code','$qtd_mat_name',".
						"'$qtd_qty','$qtd_unit_code',".
						"'$qtd_contractor_price','$qtd_contractor_disc','$qtd_contractor_disc_unit',".
						"'$aucd_auction_unit_amt','$user_login','$today')";
					$result_add_aucd = sqlsrv_query($conn, $sql_add_aucd);
					$aucm_auction_price = $aucm_auction_price +  ($aucd_auction_unit_amt * $qtd_qty);
				}	
			}
			$sql_update_aucm = "UPDATE aucm_mstr SET aucm_auction_price = '$aucm_auction_price' WHERE aucm_nbr = '$aucm_nbr'";						
			$result_update_aucm = sqlsrv_query($conn, $sql_update_aucm);
			//Assign Auction Win
			//ดูว่า auction ตัวไหนที่ได้ WIN
			$win_aucm_nbr = getauc_win($qtm_nbr,$gbv_auction_type,$conn);
			if ($win_aucm_nbr != "") {
				//กำหนด WIN Flag ให้กับ Auction นั้นและเอาทีมที่อยู่ใน Auction นั้นๆไป update ให้กับ Quotation ด้วย
				assign_win($win_aucm_nbr,$conn);
			}
			//update ยอดรวมของ auction ที่ win กลับไปที่ qtm_mstr
			//ดึงข้อมลจาก qtd_det เนื่องจาก qtd_det โดย update ยอดไปแล้ว
			//วิธีที่ง่ายที่สุดคือดึงจาก qtd_det เลย
			update_qtm_auction_total_amt($qtm_nbr,$conn);
			//
			$r="1";
			$nb=encrypt($qtm_nbr, $key);
			$tb=encrypt("tab_auction", $key);
			echo '{"r":"'.$r.'","e":"'.$errortxt.'","nb":"'.$nb.'","tb":"'.$tb.'","pg":"'.$pg.'"}';
		}
		if ($action == "edit_auction") {
			$aucm_nbr = html_escape($_POST['aucm_nbr']);
			$aucm_auction_price = 0;
			$aucd_auction_unit_amt_array = explode("*^*",$aucd_auction_unit_amt_list);
			$aucd_id_array = explode(",",$aucd_qtd_id_list);
			$aucd_id_count = sizeof($aucd_id_array);
			for($i = 0; $i < $aucd_id_count;$i++) {
				$aucd_id = $aucd_id_array[$i];
				$aucd_auction_unit_amt = $aucd_auction_unit_amt_array[$i];
				//get qty from db
				$aucd_contractor_qty = 0;
				$sql_aucdqty = "SELECT aucd_contractor_qty from aucd_det WHERE aucd_id = '$aucd_id'";
				$result_aucdqty = sqlsrv_query($conn, $sql_aucdqty);	
				$rec_aucdqty = sqlsrv_fetch_array($result_aucdqty, SQLSRV_FETCH_ASSOC);	
				if ($rec_aucdqty) {
					$aucd_contractor_qty = $rec_aucdqty['aucd_contractor_qty'];
				}
				//
				$sql_edit_aucd = " UPDATE aucd_det SET ".
					"aucd_auction_unit_amt = '$aucd_auction_unit_amt'," .
					"aucd_update_by = '$user_login',".
					"aucd_update_date = '$today'".
					" WHERE aucd_id = '$aucd_id'";
				$result_edit_aucd = sqlsrv_query($conn, $sql_edit_aucd);
				$aucm_auction_price = $aucm_auction_price +  ($aucd_auction_unit_amt * $aucd_contractor_qty);
			}
			$sql_update_aucm = "UPDATE aucm_mstr SET aucm_auction_price = '$aucm_auction_price' WHERE aucm_nbr = '$aucm_nbr'";						
			$result_update_aucm = sqlsrv_query($conn, $sql_update_aucm);
			//Assign Auction Win
			//ดูว่า auction ตัวไหนที่ได้ WIN
			$win_aucm_nbr = getauc_win($qtm_nbr,$gbv_auction_type,$conn);
			if ($win_aucm_nbr != "") {
				//กำหนด WIN Flag ให้กับ Auction นั้นและเอาทีมที่อยู่ใน Auction นั้นๆไป update ให้กับ Quotation ด้วย
				assign_win($win_aucm_nbr,$conn);
			}
			//update ยอดรวมของ auction ที่ win กลับไปที่ qtm_mstr
			//ดึงข้อมลจาก qtd_det เนื่องจาก qtd_det โดย update ยอดไปแล้ว
			//วิธีที่ง่ายที่สุดคือดึงจาก qtd_det เลย
			update_qtm_auction_total_amt($qtm_nbr,$conn);
			//
			$r="1";
			$nb=encrypt($qtm_nbr, $key);
			$tb=encrypt("tab_auction", $key);
			echo '{"r":"'.$r.'","e":"'.$errortxt.'","nb":"'.$nb.'","tb":"'.$tb.'","pg":"'.$pg.'"}';
		}
		if ($action == "del_auction") {
			$qtm_nbr = mssql_escape($_POST['qtm_nbr']);
			$aucm_nbr = mssql_escape($_POST['aucm_nbr']);
			$params = array($aucm_nbr);
			$sql_del = "DELETE FROM aucd_det WHERE aucd_aucm_nbr = ?";	
			$result_del = sqlsrv_query($conn,$sql_del,$params);
			
			$sql_del = "DELETE FROM aucm_mstr WHERE aucm_nbr = ?";	
			$result_del = sqlsrv_query($conn,$sql_del,$params);
			
			//Assign Auction Win
			//ดูว่า auction ตัวไหนที่ได้ WIN
			$win_aucm_nbr = getauc_win($qtm_nbr,$gbv_auction_type,$conn);
			if ($win_aucm_nbr != "") {
				//กำหนด WIN Flag ให้กับ Auction นั้นและเอาทีมที่อยู่ใน Auction นั้นๆไป update ให้กับ Quotation ด้วย
				assign_win($win_aucm_nbr,$conn);
			}
			else {
				//เช็คเพื่อ confirm ว่ามี auction เหลืออยู่มั๊ยถ้าไม่มีก็ต้อง clear ค่า team ใน qtm_mtr
				//และก็ต้อง set ค่า default ให้กับ qtd_contractor_auction_unit_amt
				$sql_chk_aucm = "SELECT TOP 1 * from aucm_mstr where aucm_qtm_nbr = '$qtm_nbr'";
				$result_chk_aucm = sqlsrv_query( $conn, $sql_chk_aucm);											
				$rec_chk_aucm = sqlsrv_fetch_array($result_chk_aucm, SQLSRV_FETCH_ASSOC);
				if (!$rec_chk_aucm) {
					//Clear Team on header
					$sql_update_qtm = "UPDATE qtm_mstr SET qtm_tem_code = '' WHERE qtm_nbr = '$qtm_nbr'";						
					$result_sql_update_qtm = sqlsrv_query($conn, $sql_update_qtm);
					//Update Default Value from qtd_contractor_* to qtd_contractor_acution_*
					//ดึงค่ากลางของผู้รับเหมามาใส่ใน field qtd_contractor_auction_unit_amt
					update_qtd_contractor_auction($qtm_nbr,$conn);
				}
			}
			$r="1";
			$errortxt="delete success.";
			$nb=encrypt($qtm_nbr, $key);
			$tb=encrypt("tab_auction", $key);
			echo '{"r":"'.$r.'","e":"'.$errortxt.'","nb":"'.$nb.'","tb":"'.$tb.'","pg":"'.$pg.'"}';
		}
		if ($action == "assign_win_auction") {
			$aucm_nbr = html_escape($_POST['aucm_nbr']);
			assign_win($aucm_nbr,$conn);
			$r="1";
			$errortxt="";
			$nb=encrypt($qtm_nbr, $key);
			$tb=encrypt("tab_auction", $key);
			echo '{"r":"'.$r.'","e":"'.$errortxt.'","nb":"'.$nb.'","tb":"'.$tb.'","pg":"'.$pg.'"}';
		}
	}
	else {
		$r="0";
		$nb=encrypt($aucm_nbr, $key);
		$tb=encrypt("tab_auction", $key);
		echo '{"r":"'.$r.'","e":"'.$errortxt.'","nb":"'.$nb.'","tb":"'.$tb.'","pg":"'.$pg.'"}';
	}
	
	function getauc_win($qtm_nbr,$auction_type,$conn) {
		$win_aucm_nbr = "";
		if ($auction_type == "PRICE") {
			$params = array($qtm_nbr);
			$sql = "SELECT TOP 1 aucm_nbr from aucm_mstr WHERE aucm_qtm_nbr = '$qtm_nbr' order by aucm_auction_price,aucm_create_date";
			$result = sqlsrv_query($conn, $sql,$params);	
			$rec = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);	
			if ($rec) {
				$win_aucm_nbr = $rec['aucm_nbr'];
			}
		}
		if ($auction_type == "SEQ") {
			$params = array($qtm_nbr);
			$sql = "SELECT TOP 1 aucm_nbr from aucm_mstr WHERE aucm_qtm_nbr = '$qtm_nbr' order by aucm_create_date";
			$result = sqlsrv_query($conn, $sql,$params);	
			$rec = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);	
			if ($rec) {
				$win_aucm_nbr = $rec['aucm_nbr'];
			}
		}
		return $win_aucm_nbr;
	}
	function assign_win($aucm_nbr,$conn) {
		$qtm_nbr = findsqlval("aucm_mstr","aucm_qtm_nbr","aucm_nbr",$aucm_nbr,$conn);
		$aucm_tem_code = findsqlval("aucm_mstr","aucm_tem_code","aucm_nbr",$aucm_nbr,$conn);
		$params = array($aucm_nbr);
		$sql = "SELECT * FROM aucd_det WHERE aucd_aucm_nbr = ?";									
		$result = sqlsrv_query( $conn, $sql,$params);											
		while($rec = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {	
			$aucd_qtd_id = html_escape($rec['aucd_qtd_id']);
			$aucd_qtm_nbr = html_escape($rec['aucd_qtm_nbr']);
			$aucd_auction_unit_amt = $rec['aucd_auction_unit_amt'];
			$sql_edit_qtd = " UPDATE qtd_det SET ".
				"qtd_contractor_auction_unit_amt = '$aucd_auction_unit_amt'," .
				"qtd_update_by = '$user_login',".
				"qtd_update_date = '$today'".
				" WHERE qtd_id = '$aucd_qtd_id'";
			
			$result_edit_qtd = sqlsrv_query($conn, $sql_edit_qtd);
		}
		//Reset Win
		$sql_update_win_aucm = "UPDATE aucm_mstr SET aucm_result = '' WHERE aucm_nbr <> '$aucm_nbr'";						
		$result_update_win_aucm = sqlsrv_query($conn, $sql_update_win_aucm);
		//Update Win
		$sql_update_win_aucm = "UPDATE aucm_mstr SET aucm_result = 'WIN' WHERE aucm_nbr = '$aucm_nbr'";						
		$result_update_win_aucm = sqlsrv_query($conn, $sql_update_win_aucm);
		//Assign Team to Quotation Header
		$sql_update_qtm = "UPDATE qtm_mstr SET qtm_tem_code = '$aucm_tem_code' WHERE qtm_nbr = '$qtm_nbr'";						
		$result_sql_update_qtm = sqlsrv_query($conn, $sql_update_qtm);
		
	}
	function update_qtm_auction_total_amt($qtm_nbr,$conn) {
		//UPDATE TOTAL AUCTION PRICE for qtm_mstr
		$total_auction_amt = 0;
		$sql_sumaucprice = "SELECT sum(qtd_contractor_auction_unit_amt * qtd_qty) 'total_auction_amt' from qtd_det WHERE qtd_qtm_nbr = '$qtm_nbr'";
		$result_sumaucprice = sqlsrv_query( $conn, $sql_sumaucprice);											
		$rec_sumaucprice = sqlsrv_fetch_array($result_sumaucprice, SQLSRV_FETCH_ASSOC);
		if ($rec_sumaucprice) {
			$total_auction_amt = $rec_sumaucprice['total_auction_amt'];
		}
		//
		$sql_updateaucprice = " UPDATE qtm_mstr SET ".
			"qtm_auction_amt = '$total_auction_amt'" .
			" WHERE qtm_nbr = '$qtm_nbr'";
			
		$result_updateaucprice = sqlsrv_query($conn, $sql_updateaucprice);
		//END UPDATE TOTAL PRICE
	}
	function update_qtd_contractor_auction($qtm_nbr,$conn) {
		$sql_qtd = "SELECT * from qtd_det WITH (NOLOCK) WHERE qtd_qtm_nbr = '$qtm_nbr'";
		$result_qtd = sqlsrv_query( $conn, $sql_qtd);											
		while($rec_qtd = sqlsrv_fetch_array($result_qtd, SQLSRV_FETCH_ASSOC)) {
			$qtd_contractor_price = $rec_qtd['qtd_contractor_price'];
			$qtd_contractor_disc = $rec_qtd['qtd_contractor_disc'];
			$qtd_contractor_disc_unit = $rec_qtd['qtd_contractor_disc_unit'];
			
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
			$sql_update_qtd = " UPDATE qtd_det SET ".
				"qtd_contractor_auction_unit_amt = '$qtd_contractor_amt'" .
				" WHERE qtd_qtm_nbr = '$qtm_nbr'";
			$result_update_qtd = sqlsrv_query($conn, $sql_update_qtd);
		}
	}
?> 