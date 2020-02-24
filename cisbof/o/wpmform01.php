<?php
	include("../_incs/showsaving.php");
	include("../_incs/chksession.php");
	include("../_incs/config.php"); 	
	include("../_incs/funcServer.php");	
	date_default_timezone_set('Asia/Bangkok');
	
	$today = date("Y-m-d H:i:s"); 
	$print_date = date("d/m/Y H:i:s");
	$wpm_nbr = decrypt(mssql_escape($_REQUEST['wpmnumber']), $key);
	$total_record = sumwpddet($wpm_nbr,$conn);
	$max_line = 20;
	
	$sql_wpm = "SELECT * from wpm_mstr where wpm_nbr = '$wpm_nbr'";
	$result_wpm = sqlsrv_query($conn, $sql_wpm);	
	$r_wpm = sqlsrv_fetch_array($result_wpm, SQLSRV_FETCH_ASSOC);		
	if ($r_wpm) {		
		$wpm_date = $r_wpm['wpm_date'];
		$wpm_remark = html_quot($r_wpm['wpm_remark']);
		$wpm_status_code = $r_wpm['wpm_status_code'];
		$wpm_group_type = $r_wpm['wpm_group_type'];
		$wpm_printed = $r_wpm['wpm_printed'];
		$wpm_print_by = $r_wpm['wpm_print_by'];
		$wpm_print_date = $r_wpm['wpm_print_date'];
		$wpm_print_cnt = $r_wpm['wpm_print_cnt'];
		$wpm_create_by = $r_wpm['wpm_create_by'];
		$wpm_create_date = $r_wpm['wpm_create_date'];
		if ($wpm_group_type == "A") {
			$group_by = "sptm_customer_number,sptm_customer_dummy,dlvm_transport_car_nbr,sptm_customer_amphur,sptm_customer_province";
		}
		if ($wpm_group_type == "B") {
			$group_by = "sptm_delivery_mth,sptm_req_by";
		}
		if ($wpm_group_type == "C") {
			$group_by = "sptm_delivery_mth,sptm_req_by";
		}
		if ($wpm_group_type == "D") {
			$group_by = "sptm_customer_number,sptm_customer_dummy,sptm_customer_province";
		}
		if ($wpm_group_type == "E") {
			$group_by = "sptm_customer_number,sptm_customer_dummy,sptm_delivery_mth";
		}
		if ($wpm_group_type == "F") {
			$group_by = "sptm_customer_number,sptm_customer_dummy,cast(sptm_delivery_mth_desc as nvarchar(255))";
		}
	}
	
	/////////////////
	//CREATE PDF FILE
	$header =
		"<table width=100% cellpadding=5 cellspacing=5 style='border: 1px dotted black; border-collapse: separate;font-size:12pt'>" .
			"<tr>".
				"<td style='width:250px'><b>SCG Ceramics PCL.</b><br><span style='font-size:9pt'>ใบเตรียมขึ้นเลขที่: $wpm_nbr</span></td>".
				"<td style='width:250px;text-align:right'>$dlvm_nbr<br><span style='font-size:9pt'>**ใช้สำหรับคลังเตรียมขึ้นสินค้า **<br>หน้าที่: {PAGENO}/{nbpg}</span></td>".
			"</tr>".
			"<tr>".
				"<td style='width:250px;font-size:9pt'><b>$wpm_remark</b></td>".
				"<td style='width:250px;text-align:right;font-size:9pt'>พิมพ์ครั้งที่: $wpm_print_cnt</td>".
			"</tr>".
		"</table>" .
		
		"<table width=100% cellpadding=5 cellspacing=5 style='border-collapse: collapse;border: 1px dotted black; font-size:8pt'>" .
			"<tr>" .
				"<th style='border: 1px dotted black;width:50px;text-align:center'>ลำดับ</th>".
				"<th style='border: 1px dotted black;width:110px;text-align:center'>Package No</th>".
				"<th style='border: 1px dotted black;width:60px;text-align:center'>ประเภท</th>" .
				"<th style='border: 1px dotted black;width:200px;text-align:center'>ชุดที่/ที่วาง</th>" .
				"<th style='border: 1px dotted black;width:60px;text-align:center'>น้ำหนัก(KG)</th>".
				"<th style='border: 1px dotted black;width:100px;text-align:center'>ทะเบียน</th>".
				"<th style='border: 1px dotted black;width:200px;text-align:center'>ลูกค้า</th>".
				"<th style='border: 1px dotted black;width:120px;text-align:center'>อำเภอ/จังหวัด</th>" .
				"<th style='border: 1px dotted black;width:120px;text-align:center'>วิธีการจัดสส่ง</th>" .
				"<th style='border: 1px dotted black;width:50px;text-align:center'>(OK)<br>ขึ้นได้</th>" .
				"<th style='border: 1px dotted black;width:50px;text-align:center'>(X)<br>ไม่ได้</th>" .
			"</tr>".
		"</table>";
		
	$footer = "<div>
		<table width=100% cellpadding=5 cellspacing=5 style='border-collapse: collapse;border: 1px dotted black; font-size:10pt'>
		<tr>
			<td width=50% valign=top style='border: 1px dotted black;'>
				<table style='border-collapse: separate; font-size:8pt'>
					<tr><td colspan=2><b><u>(CS) ข้อมูลการสร้างใบเตรียม:</u></b></td></tr>
					<tr><td colspan=2 style='height:10px'></td></tr>
					<tr>
						<td style='width: 100px'>ผู้พิมพ์เอกสาร:</td>
						<td>$user_fullname</td>
					</tr>
					<tr><td colspan=2 style='height:3px'></td></tr>
					<tr>
						<td>วันที่พิมพ์:</td>
						<td>$print_date</td>
					</tr>
					<tr><td colspan=2 style='height:3px'></td></tr>
					<tr>
						<td><u>หมายเหตุ:</u></td>
					</tr>
					<tr>
						<td colspan=2 style='font-size:8pt'><br>__________________________</td>
					</tr>
				</table>
			</td>
			<td width=50% align=right valign=top style='border: 1px dotted black;'>
				<table style='border-collapse: separate; font-size:8pt'>
					<tr><td colspan=2><b><u>(คลังสินค้า) ข้อมูลการขึ้นสินค้า:</u></b></td></tr>
					<tr><td colspan=2 style='height:10px'></td></tr>
					<tr>
						<td stype='width: 100px'>ผู้ขึ้นสินค้า:</td>
						<td>______________</td>
					</tr>
					<tr><td colspan=2 style='height:3px'></td></tr>
					<tr>
						<td>วันที่ขึ้นสินค้า:</td>
						<td>____/____/_____</td>
					</tr>
					<tr>
						<td><u>หมายเหตุ:</u></td>
					</tr>
					<tr>
						<td colspan=2 style='font-size:8pt'><br>__________________________</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr><td colspan=4 align=center><barcode code='$wpm_nbr' type='C39' size='0.8' height='0.8'/><br><span style='font-size:7pt'>$wpm_nbr</span></td></tr>
		</table>
		</div>";
	
			  
	require_once('../_libs/mpdf/mpdf.php');
	//ob_start();
	$pdf = new mPDF('th', 'A4', '0', 'THSaraban'); //การตั้งค่ากระดาษถ้าต้องการแนวตั้ง ก็ A4 เฉยๆครับ ถ้าต้องการแนวนอนเท่ากับ A4-L
	$pdf->SetTitle('ใบเตรียมขึ้นสินค้า');
	//PreDefine
	$pdf->SetHTMLHeader($header);
	//$pdf->SetWatermarkImage("sample.jpg");
	//$pdf->showWatermarkImage = true;
	$pdf->SetHTMLFooter("<table width=100% style='font-size:8pt'><tr><td align=center><barcode code='$dlvm_nbr' type='C39' size='1.2' height='1'/><br>$dlvm_nbr</td></tr></table>");
	$pdf->AddPage('', // L - landscape, P - portrait 
			'', '', '1', '0',
			10, // margin_left
			10, // margin right
			43, // margin top
			20, // margin bottom
			10, // margin header
			5); // margin footer
	//End PreDefine
	$data = "";
		
	$pdf->WriteHTML("<table width=100% cellpadding=5 cellspacing=5 style='border-collapse: collapse;border: 1px dotted black; font-size:8pt'>");
	
	$dlvm_nbr_move_step_list = "";
	$n = 0;													
	$sql_wpd = "SELECT wpd.* FROM" .
		" (SELECT ROW_NUMBER() OVER(ORDER BY $group_by) AS rownumber,* FROM wpd_det " .
		" INNER JOIN wpm_mstr ON wpm_nbr = wpd_wpm_nbr" .
		" INNER JOIN dlvm_mstr ON dlvm_nbr = wpd_dlvm_nbr " .
		" INNER JOIN sptm_mstr ON sptm_nbr = dlvm_sptm_nbr " .
		" WHERE wpd_wpm_nbr = '$wpm_nbr') as wpd";
	
	$result_wpd = sqlsrv_query( $conn, $sql_wpd);
	while($r_wpd = sqlsrv_fetch_array($result_wpd, SQLSRV_FETCH_ASSOC)) {	
		$wpd_id = $r_wpd['wpd_id'];
		$wpd_dlvm_nbr = $r_wpd['wpd_dlvm_nbr'];
		$wpd_sptm_nbr = $r_wpd['wpd_sptm_nbr'];
		$wpd_status = $r_wpd['wpd_status'];
		$wpd_transport_car_nbr = $r_wpd['dlvm_transport_car_nbr'];
		$wpd_dlvm_packing_weight = $r_wpd['dlvm_packing_weight'];
		$wpd_packing_location = html_quot($r_wpd['dlvm_packing_location']);
		if ($wpd_packing_location != "") {
			$wpd_packing_location = "( วางไว้ที่: " . $wpd_packing_location." )";
		}
		/**
		//ดึงข้อมูลของใบเบิก
		$wpd_npd = false;
		$wpd_cust_amphur =  "";
		$wpd_cust_province = "";
		$wpd_cust_name = "";
		$wpd_npd_brand = "";
		$wpd_npd_setno = "";
		
		$sql_sptm = "SELECT * from sptm_mstr where sptm_nbr = '$wpd_sptm_nbr'";
		$result_sptm = sqlsrv_query($conn, $sql_sptm);	
		$r_sptm = sqlsrv_fetch_array($result_sptm, SQLSRV_FETCH_ASSOC);		
		if ($r_sptm) {
			$wpd_npd = $r_sptm['sptm_npd'];
			$wpd_npd_type = $r_sptm['sptm_npd_type'];
			$wpd_cust_code = $r_sptm['sptm_customer_number'];
			$wpd_cust_dummy = html_quot($r_sptm['sptm_customer_dummy']);
			$wpd_cust_type = $r_sptm['sptm_cust_type'];
			$wpd_cust_amphur =  html_quot($r_sptm['sptm_customer_amphur']);
			$wpd_cust_province = html_quot($r_sptm['sptm_customer_province']);
			$wpd_npd_com = html_quot($r_sptm['sptm_npd_com']);
			$wpd_npd_brand = html_quot($r_sptm['sptm_npd_brand']);
			$wpd_npd_setno = html_quot($r_sptm['sptm_npd_setno']);
			if($wpd_cust_code != "DUMMY") {
				$wpd_cust_name = findsqlval("customer","customer_name1", "customer_number", $wpd_cust_code,$conn);
				if ($wpd_cust_name != "") {
					$wpd_cust_name = $wpd_cust_name;
				}
			}
			else {
				$wpd_cust_name = $wpd_cust_dummy;
			}
		}
		**/
		$wpd_npd = $r_wpd['sptm_npd'];
		$wpd_npd_type = $r_wpd['sptm_npd_type'];
		$wpd_cust_code = $r_wpd['sptm_customer_number'];
		$wpd_cust_dummy = html_quot($r_wpd['sptm_customer_dummy']);
		$wpd_cust_type = $r_wpd['sptm_cust_type'];
		$wpd_cust_amphur =  html_quot($r_wpd['sptm_customer_amphur']);
		$wpd_cust_province = html_quot($r_wpd['sptm_customer_province']);
		$wpd_npd_com = html_quot($r_wpd['sptm_npd_com']);
		$wpd_npd_brand = html_quot($r_wpd['sptm_npd_brand']);
		$wpd_npd_setno = html_quot($r_wpd['sptm_npd_setno']);
		
		if($wpd_npd_setno!="") {
			$wpd_packing_location = "<br>".$wpd_packing_location;
		}
		
		
		if($wpd_cust_code != "DUMMY") {
			$wpd_cust_name = findsqlval("customer","customer_name1", "customer_number", $wpd_cust_code,$conn);
			if ($wpd_cust_name != "") {
				$wpd_cust_name = $wpd_cust_name;
			}
		}
		else {
			$wpd_cust_name = '<font color=red>*</font>' .$wpd_cust_dummy;
		}
		
		//
		$wpd_delivery_mth = $r_wpd['sptm_delivery_mth'];
		$wpd_delivery_mth_name = findsqlval("delivery_mth","delivery_name", "delivery_code", $wpd_delivery_mth,$conn);
		
		///
		if ($wpd_npd) {$wpd_npd_text = "NPD";}
		else {$wpd_npd_text = "SPT";}
		
		$wpd_status_y = "";
		$wpd_status_n = "";
		if ($wpd_status == "Y") {
			$wpd_status_y = "OK";
			$wpd_status_n = "";
		}
		if ($wpd_status == "N") {
			$wpd_status_y = "";
			$wpd_status_n = "X";
		}
		
		// if (!$wpm_printed) {
			// /*UPDATE dlvm_mstr เปลี่ยน status ของ delivery ให้เป็นกำลังขึ้นสินค้า*/
			// $sql_update_dlvm = "UPDATE dlvm_mstr SET ".		
					// "dlvm_dlvs_step_code = '40'," .	
					// "dlvm_step_by = '$user_login'," .	
					// "dlvm_step_date = '$today'" .	
					// " WHERE dlvm_nbr = '$wpd_dlvm_nbr'";		
			// $resultupdate_dlvm = sqlsrv_query($conn,$sql_update_dlvm);
		// }
		if (!$wpm_printed) {
			if ($dlvm_nbr_move_step_list != "") {$dlvm_nbr_move_step_list .= ",";}
			$dlvm_nbr_move_step_list .= "'".$wpd_dlvm_nbr."'";
		}
		
		$wpd_dlvm_packing_weight_diff = $wpd_dlvm_packing_weight - (int) $wpd_dlvm_packing_weight;
		if ($wpd_dlvm_packing_weight_diff>0) {$wpd_dlvm_packing_weight = number_format($wpd_dlvm_packing_weight,2);}
		else {$wpd_dlvm_packing_weight = number_format($wpd_dlvm_packing_weight,0);}
		
		//
		$n++;
		$data = "<tr>".
			"<td style='border: 1px dotted black;width:50px;text-align: center'>$n</td>".
			"<td style='border: 1px dotted black;width:110px'>$wpd_dlvm_nbr</td>".
			"<td style='border: 1px dotted black;width:60px;text-align:center'>$wpd_npd_text</td>" .
			"<td style='border: 1px dotted black;width:200px;'>$wpd_npd_setno$wpd_packing_location</td>" .
			"<td style='border: 1px dotted black;width:60px;text-align:center'>$wpd_dlvm_packing_weight</td>" .
			"<td style='border: 1px dotted black;width:100px'>$wpd_transport_car_nbr</td>".
			"<td style='border: 1px dotted black;width:200px;'>$wpd_cust_name</td>".
			"<td style='border: 1px dotted black;width:120px;'>$wpd_cust_amphur/$wpd_cust_province</td>".
			"<td style='border: 1px dotted black;width:120px;'>$wpd_delivery_mth_name</td>".
			"<td style='border: 1px dotted black;width:50px;text-align:center;'>$wpd_status_y</td>" .
			"<td style='border: 1px dotted black;width:50px;text-align:center;'>$wpd_status_n</td>" .
			"</tr>";
		$pdf->WriteHTML($data);
		if ($n % $max_line == 0) {
			if ($n < $total_record) {
				$pdf->WriteHTML("</table>");
				$pdf->AddPage();
				$pdf->WriteHTML("<table width=100% cellpadding=5 cellspacing=5 style='border-collapse: collapse;border: 1px dotted black; font-size:10pt'>");
			}
			else {
				$pdf->SetHTMLFooter($footer);	
			}
		}
		else {
			if ($n >= $total_record) {
				$pdf->SetHTMLFooter($footer);
				
			}	
		}
	}
				
	$pdf->WriteHTML("</table>");
	$pdf->Output();
	if ($total_record > 0) {
		if (!$wpm_printed) {
			//Update Print Flag
			//Update Status = 30 (กำลังขึ้นสินค้า)
			$sql = "UPDATE wpm_mstr SET ".		
				"wpm_printed='1'," .			
				"wpm_print_cnt = wpm_print_cnt + 1," .
				"wpm_status_code = '30'," .
				"wpm_print_by = '$user_login'," .
				"wpm_print_date = '$today'" .
				" WHERE wpm_nbr = '$wpm_nbr'";
		} else {
			//Update Print Count
			$sql = "UPDATE wpm_mstr SET ".					
				"wpm_print_cnt = wpm_print_cnt + 1," .
				"wpm_print_by = '$user_login'," .
				"wpm_print_date = '$today'" .
				" WHERE wpm_nbr = '$wpm_nbr'";
		}
		$result = sqlsrv_query($conn,$sql);
		
		if (!$wpm_printed) {
			/*UPDATE dlvm_mstr เปลี่ยน status ของ delivery ให้เป็นกำลังขึ้นสินค้า*/
			if ($dlvm_nbr_move_step_list!="") {
				$sql_update_dlvm = "UPDATE dlvm_mstr SET ".		
						"dlvm_dlvs_step_code = '40'," .	
						"dlvm_step_by = '$user_login'," .	
						"dlvm_step_date = '$today'" .	
						" WHERE dlvm_nbr in ($dlvm_nbr_move_step_list)";
				$resultupdate_dlvm = sqlsrv_query($conn,$sql_update_dlvm);
			}
		}
	}
?> 