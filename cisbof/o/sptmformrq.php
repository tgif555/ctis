<?php
	include("../_incs/showsaving.php");
	include("../_incs/chksession.php");
	include("../_incs/config.php"); 	
	include("../_incs/funcServer.php");	
	date_default_timezone_set('Asia/Bangkok');
	$today = date("Y-m-d H:i:s");  	
	$sptm_nbr = decrypt($_REQUEST['sptmnumber'], $key);
	$total_record = sumsptddet($sptm_nbr,'ALL',$conn);
	$max_line = 20;
	$line_cnt = 0;
	$item_cnt = 0;
	
	$sql_sptm = "SELECT * from sptm_mstr where sptm_nbr = '$sptm_nbr'";
	$result_sptm = sqlsrv_query($conn, $sql_sptm);	
	$r_sptm = sqlsrv_fetch_array($result_sptm, SQLSRV_FETCH_ASSOC);		
	if ($r_sptm) {		
		$sptm_customer_number = $r_sptm['sptm_customer_number'];
		$sptm_customer_dummy = html_quot($r_sptm['sptm_customer_dummy']);
		$sptm_customer_type = $r_sptm['sptm_customer_type'];
		$sptm_reason_code = $r_sptm['sptm_reason_code'];
		$sptm_reason_name = findsqlval("reason_mstr","'['+reason_code+']'+ ' '+reason_name", "reason_code", $sptm_reason_code,$conn);

		$sptm_req_by = $r_sptm['sptm_req_by'];
		$sptm_req_by_name = findsqlval("emp_mstr","emp_th_firstname+' '+emp_th_lastname", "emp_user_id", $sptm_req_by,$conn);
		$sptm_req_by_sec = html_quot(findsqlval("emp_mstr","emp_en_sec", "emp_user_id", $sptm_req_by,$conn));
		$sptm_req_date = $r_sptm['sptm_req_date'];
		$sptm_step_code = $r_sptm['sptm_step_code'];
		if ($sptm_step_code == '0') {
			$sptm_submit_date = date('d/m/Y H:i:s');
		} else {
			$sptm_submit_date = dmyhmsdb($r_sptm['sptm_submit_date'],'Y');
		}
		$sptm_step_name = findsqlval("step_mstr","step_name","step_code",$sptm_step_code,$conn);
		$sptm_remark = html_quot($r_sptm['sptm_remark']);
		
		// $sptm_customer_name = html_quot(findsqlval("customer","customer_name1", "customer_number", $sptm_customer_number,$conn));
		// if ($sptm_customer_name != "") { $sptm_customer_name = '['.$sptm_customer_number.'] ' . $sptm_customer_name; }
		// else { $sptm_customer_name = $sptm_customer_dummy; }

		if($sptm_customer_number != "DUMMY") {
			$sptm_customer_name = html_quot(findsqlval("customer","customer_name1", "customer_number", $sptm_customer_number,$conn));
			if ($sptm_customer_name != "") {
				$sptm_customer_name = "[".$sptm_customer_number."] " . $sptm_customer_name;
			}
		}
		else {
			$sptm_customer_name = $sptm_customer_dummy;
		}
		$sptm_customer_amphur = html_quot($r_sptm['sptm_customer_amphur']);
		$sptm_customer_province = html_quot($r_sptm['sptm_customer_province']);
		$sptm_delivery_mth_desc = html_quot($r_sptm['sptm_delivery_mth_desc']);
		
		$sptm_approve_by = $r_sptm['sptm_approve_by'];
		$sptm_approve_name = findsqlval("emp_mstr","emp_th_firstname+' '+emp_th_lastname", "emp_user_id", $sptm_approve_by,$conn);
		$sptm_approve_date = $r_sptm['sptm_approve_date'];
		if ($sptm_approve_date!="") {
			$sptm_approve_date = dmyhmsdb($r_sptm['sptm_approve_date'],'Y');
		}
	}
		
	/////////////////
	//CREATE PDF FILE
	$header =
		"<table width=100% cellpadding=5 cellspacing=5 style='border: 1px dotted black; border-collapse: separate;font-size:12pt'>" .
			"<tr><td style='text-align:center;height: 40px'><h4>เอกสารขอเบิกกระเบื้องตัวอย่างหมายเลข: $sptm_nbr</h4>วันที่ขอเบิก " . $sptm_submit_date ."<br><br><span style='font-size:10pt;'>*** สถานะเอกสาร: ".$sptm_step_name ." ***</span></td></tr>" .
		"</table>" .	
		"<table width=100% style='border: 1px dotted black; border-collapse: collapse;font-size:8pt'>" .
			"<tr><td colspan=4 style='height:5px'></td></tr>" .
			"<tr><td align=right style='width: 110px'><b>ชื่อผู้ขอเบิก:</b></td><td style='width: 300px'>$sptm_req_by_name</td><td style='width:100px' align=right><b>สังกัดผู้ขอเบิก:</b></td><td>$sptm_req_by_sec</td></tr>".
			"<tr><td colspan=4 style='height:5px'></td></tr>" .
			"<tr><td align=right><b>รหัสลูกค้า:</b></td><td>$sptm_customer_name</td><td align=right><b>วัตถุประสงค์เพื่อ:</b></td><td>$sptm_reason_name</td></tr>".
			"<tr><td align=right><b>อำเภอ:</b></td><td>$sptm_customer_amphur</td><td align=right><b>จังหวัด:</b></td><td>$sptm_customer_province</td></tr>" .
			"<tr><td valign=top align=right><b>หมายเหตุการจัดส่ง:</b></td><td colspan=3>$sptm_delivery_mth_desc</td></tr>" .
			"<tr><td colspan=4 style='height:5px'></td></tr>" .
		"</table>" .
		"<table width=100% style='border: 1px dotted black; border-collapse: collapse;font-size:8pt'>" .
			"<tr>" .
				"<td style='border: 1px dotted black; width:50px; height:30px;text-align:center'><b>ลำดับ</b></td>".
					"<td style='border: 1px dotted black; width:100px; height:30px;text-align:center'><b>ประเภท</b></td>".
				"<td style='border: 1px dotted black; width:400px; height:30px;text-align:center'><b>รายการ</b></td>".
				"<td style='border: 1px dotted black; width:100px; height:30px;text-align:center'><b>จำนวน</b></td>".
				"<td style='border: 1px dotted black; height:30px; text-align:center'><b>หน่วย</b></td>" .
			"</tr>".
		"</table>";
	$footer = 
		"<table width=100% border=0 style='font-size:8pt'>" .
			"<tr><td width=85% style='text-align:right'><b>ผู้อนุมัติ:</b></td><td style='text-align:right;width=150px;'>$sptm_approve_name</td></tr>" .
			"<tr><td style='text-align:right'>ว<b>ันที่:</b></td><td style='text-align:right;width=150px;'>$sptm_approve_date</td></tr>" .
			"<tr><td colspan=2 style='text-align:center'>หน้าที่ {PAGENO}/{nbpg}</td></tr>" .
		"</table>";
				
		  
	require_once('../_libs/mpdf/mpdf.php');
	//ob_start();
	$pdf = new mPDF('th', 'A4', '0', 'THSaraban'); //การตั้งค่ากระดาษถ้าต้องการแนวตั้ง ก็ A4 เฉยๆครับ ถ้าต้องการแนวนอนเท่ากับ A4-L
	$pdf->SetTitle('Sampletile Request Form');
	//PreDefine
	$pdf->SetHTMLHeader($header);
	//$pdf->SetWatermarkImage("sample.jpg");
	//$pdf->showWatermarkImage = true;
	$pdf->SetHTMLFooter($footer);
	$pdf->AddPage('', // L - landscape, P - portrait 
			'', '', '1', '0',
			10, // margin_left
			10, // margin right
			63, // margin top
			20, // margin bottom
			10, // margin header
			0); // margin footer
	//End PreDefine
	$data = "";
		
	$pdf->WriteHTML("<table width=100% border=1 cellpadding=5 cellspacing=5 style='border-collapse: collapse; font-size:8pt'>");
	$sql = "SELECT * FROM sptd_det" .
		" INNER JOIN material ON mat_code = sptd_mat_code" .
		" INNER JOIN unit_mstr ON unit_code = sptd_unit_code" .
		" where sptd_sptm_nbr = '$sptm_nbr' and sptd_mat_group = 'MT'";
														
	$result = sqlsrv_query( $conn, $sql );											
	while($r_sptd = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
		$line_cnt++;
		$item_cnt++;
		$sptd_mat_code = $r_sptd['sptd_mat_code'];												
		$sptd_mat_name = html_quot($r_sptd['mat_th_name']);
		$sptd_qty_order = $r_sptd['sptd_qty_order'];												
		$sptd_unit_name = html_quot($r_sptd['unit_name']);
		$sptd_mat_group = "กระเบื้อง";
		$data = "<tr>".
				"<td style='border: 1px dotted black; width: 50px;text-align: center'>$item_cnt</td>".
				"<td style='border: 1px dotted black; width: 100px'>$sptd_mat_group</td>".
				"<td style='border: 1px dotted black; width: 400px'>[$sptd_mat_code] $sptd_mat_name</td>".
				"<td style='border: 1px dotted black; width: 100px;text-align: center'>$sptd_qty_order</td>".
				"<td style='border: 1px dotted black; text-align: center'>$sptd_unit_name</td>".
				"</tr>";
		$pdf->WriteHTML($data);
		if ($line_cnt % $max_line == 0) {
			if ($line_cnt < $total_record) {
				$pdf->WriteHTML("</table>");
				$pdf->AddPage();
				$pdf->WriteHTML("<table width=100% cellpadding=5 cellspacing=5 style='border-collapse: collapse;border: 1px dotted black; font-size:8pt'>");
			}
			else {
				$pdf->SetHTMLFooter($footer);	
			}
		}
		else {
			if ($line_cnt >= $total_record) {
				$pdf->SetHTMLFooter($footer);
				
			}	
		}
	}
	$sql = "SELECT * FROM sptd_det" .
		" INNER JOIN material ON mat_code = sptd_mat_code" .
		" INNER JOIN unit_mstr ON unit_code = sptd_unit_code" .
		" where sptd_sptm_nbr = '$sptm_nbr' and sptd_mat_group = 'BS'";
														
	$result = sqlsrv_query( $conn, $sql );											
	while($r_sptd = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
		$line_cnt++;
		$item_cnt++;
		$sptd_mat_code = $r_sptd['sptd_mat_code'];												
		$sptd_mat_name = html_quot($r_sptd['mat_th_name']);
		$sptd_qty_order = $r_sptd['sptd_qty_order'];												
		$sptd_unit_name = html_quot($r_sptd['unit_name']);
		$sptd_mat_group = "บอร์ดมาตรฐาน";
		$data = "<tr>".
				"<td style='border: 1px dotted black; width: 50px;text-align: center'>$item_cnt</td>".
				"<td style='border: 1px dotted black; width: 100px'>$sptd_mat_group</td>".
				"<td style='border: 1px dotted black; width: 400px'>[$sptd_mat_code] $sptd_mat_name</td>".
				"<td style='border: 1px dotted black; width:100px;text-align: center'>$sptd_qty_order</td>".
				"<td style='border: 1px dotted black; text-align: center'>$sptd_unit_name</td>".
				"</tr>";
		$pdf->WriteHTML($data);
		if ($line_cnt % $max_line == 0) {
			if ($line_cnt < $total_record) {
				$pdf->WriteHTML("</table>");
				$pdf->AddPage();
				$pdf->WriteHTML("<table width=100% cellpadding=5 cellspacing=5 style='border-collapse: collapse;border: 1px dotted black; font-size:8pt'>");
			}
			else {
				$pdf->SetHTMLFooter($footer);	
			}
		}
		else {
			if ($line_cnt >= $total_record) {
				$pdf->SetHTMLFooter($footer);
				
			}	
		}
	}
	$sql = "SELECT * FROM sptd_det" .
		" INNER JOIN material ON mat_code = sptd_mat_code" .
		" INNER JOIN unit_mstr ON unit_code = sptd_unit_code" .
		" where sptd_sptm_nbr = '$sptm_nbr' and sptd_mat_group = 'BC'";
															
	$result = sqlsrv_query( $conn, $sql );											
	while($r_sptd = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
		$line_cnt++;
		$item_cnt++;
		$sptd_id = $r_sptd['sptd_id'];
		$sptd_mat_code = "บอร์ดปรับแต่ง";												
		$sptd_mat_name = "";
		$sptd_qty_order = $r_sptd['sptd_qty_order'];												
		$sptd_unit_name = html_quot($r_sptd['unit_name']);
		$sptd_mat_group = "บอร์ดปรับแต่ง";
		$sptd_remark = html_quot($r_sptd['sptd_remark']);
		
		$data = "<tr>".
				"<td style='border: 1px dotted black; width: 50px;text-align: center'>$item_cnt</td>".
				"<td style='border: 1px dotted black; width: 100px'>$sptd_mat_group</td>".
				"<td style='border: 1px dotted black; width: 400px'>$sptd_remark</td>".
				"<td style='border: 1px dotted black; width: 100px;text-align: center'>$sptd_qty_order</td>".
				"<td style='border: 1px dotted black; text-align: center'>$sptd_unit_name</td>".
				"</tr>";
		$pdf->WriteHTML($data);
		if ($line_cnt % $max_line == 0) {
			if ($line_cnt < $total_record) {
				$pdf->WriteHTML("</table>");
				$pdf->AddPage();
				$pdf->WriteHTML("<table width=100% cellpadding=5 cellspacing=5 style='border-collapse: collapse;border: 1px dotted black; font-size:8pt'>");
			}
			else {
				$pdf->SetHTMLFooter($footer);	
			}
		}
		else {
			if ($line_cnt >= $total_record) {
				$pdf->SetHTMLFooter($footer);
				
			}	
		}
		
		$product_cnt = 0;
		$sql_bcdet = "SELECT * FROM sptbc_det" .
			" INNER JOIN material ON mat_code = sptbc_mat_code" .
			" where sptbc_sptm_nbr = '$sptm_nbr' and sptbc_sptd_id = '$sptd_id'";
															
		$result_bcdet = sqlsrv_query( $conn, $sql_bcdet );											
		while($r_bcdet = sqlsrv_fetch_array($result_bcdet, SQLSRV_FETCH_ASSOC)) {	
			$line_cnt++;
			$product_cnt++;
			$sptbc_id = $r_bcdet['sptbc_id'];
			$sptbc_mat_code = $r_bcdet['sptbc_mat_code'];
			$sptbc_mat_name = html_quot($r_bcdet['mat_th_name']);
			$sptbc_remark = html_quot($r_bcdet['sptbc_remark']);	
			$data = "<tr>".
				"<td style='border: 1px dotted black;width:50px'></td>".
				"<td style='border: 1px dotted black; width:100px;' align=right>$product_cnt) </td>".
				"<td style='border: 1px dotted black; width:400px;'>[$sptbc_mat_code] $sptbc_mat_name</td>".
				"<td style='border: 1px dotted black; width:100px;'></td>".
				"<td style='border: 1px dotted black;'></td>".
				"</tr>";
			$pdf->WriteHTML($data);
			if ($line_cnt % $max_line == 0) {
				if ($line_cnt < $total_record) {
					$pdf->WriteHTML("</table>");
					$pdf->AddPage();
					$pdf->WriteHTML("<table width=100% cellpadding=5 cellspacing=5 style='border-collapse: collapse;border: 1px dotted black; font-size:8pt'>");
				}
				else {
					$pdf->SetHTMLFooter($footer);	
				}
			}
			else {
				if ($line_cnt >= $total_record) {
					$pdf->SetHTMLFooter($footer);
					
				}	
			}
		}
	}
	$pdf->WriteHTML("</table>");
	$pdf->Output();
	// END PDF CREATE //
	////////////////////
?> 