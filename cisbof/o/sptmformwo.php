<?php
	include("../_incs/showsaving.php");
	include("../_incs/chksession.php");
	include("../_incs/config.php"); 	
	include("../_incs/funcServer.php");	
	date_default_timezone_set('Asia/Bangkok');
	$today = date("Y-m-d H:i:s");  	
	$sptm_nbr = decrypt($_REQUEST['sptmnumber'], $key);
	$mg = strtoupper($_REQUEST['mg']);
	
	switch ($mg) {
		case "ALL":
			$mg_text = "ประเภทงาน : รวม";
			$total_record = sumsptddet($sptm_nbr,'ALL',$conn);
			break;
		case "MT":
			$mg_text = "ประเภทงาน : กระเบื้อง";
			$total_record = sumsptddet($sptm_nbr,'MT',$conn);
			break;
		case "BS":
			$mg_text = "ประเภทงาน : บอร์ดมาตรฐาน";
			$total_record = sumsptddet($sptm_nbr,'BS',$conn);
			break;
		case "BC":
			$mg_text = "ประเภทงาน : บอร์ดปรับแต่ง";
			$total_record = sumsptddet($sptm_nbr,'BC',$conn);
			break;
	}
	$max_line = 20;
	
	//ยืนยัน current processor อีกครั้ง กรณีที่มีคนที่ไม่ใช่ current processor login เข้ามาอีก page
	$sql_sptm = "SELECT * from sptm_mstr where sptm_nbr = '$sptm_nbr'";
	$result_sptm = sqlsrv_query($conn, $sql_sptm);	
	$r_sptm = sqlsrv_fetch_array($result_sptm, SQLSRV_FETCH_ASSOC);		
	if ($r_sptm) {		
		$sptm_customer_number = $r_sptm['sptm_customer_number'];
		$sptm_customer_dummy = html_quot($r_sptm['sptm_customer_dummy']);
		$sptm_customer_type = $r_sptm['sptm_customer_type'];
		$sptm_reason_code = $r_sptm['sptm_reason_code'];
		$sptm_reason_name = findsqlval("reason_mstr","'['+reason_code+']'+ ' '+reason_name", "reason_code", $sptm_reason_code,$conn);
		$sptm_first_print_date = $r_sptm['sptm_first_print_date'];
		if (is_null($sptm_first_print_date)) { 
			$sptm_first_print_date = $today;
		}
		else {
			$sptm_first_print_date = date_format($sptm_first_print_date,'Y-m-d H:i:s');
		}
		$sptm_print_cnt = $r_sptm['sptm_print_cnt'];
		$sptm_req_by = $r_sptm['sptm_req_by'];
		$sptm_req_by_name = findsqlval("emp_mstr","emp_th_firstname+' '+emp_th_lastname", "emp_user_id", $sptm_req_by,$conn);
		$sptm_req_by_sec = html_quot(findsqlval("emp_mstr","emp_en_sec", "emp_user_id", $sptm_req_by,$conn));
		$sptm_req_date = $r_sptm['sptm_req_date'];
		$sptm_expect_receipt_date = $r_sptm['sptm_expect_receipt_date'];
		
		$sptm_tel_contact = findsqlval("emp_mstr","emp_tel_contact","emp_user_id",$sptm_req_by,$conn);
		if ($sptm_tel_contact != "") {
			$sptm_tel_contact = " <span style='color:red'>(".$sptm_tel_contact.")</span>";
		}
		
		if ($r_sptm['sptm_step_code'] == '0') {
			$sptm_submit_date = date('d/m/Y H:i:s');
		} else {
			$sptm_submit_date = dmyhmsdb($r_sptm['sptm_submit_date'],'Y');
		}
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
		$sptm_delivery_mth = html_quot($r_sptm['sptm_delivery_mth']);
		$sptm_delivery_mth_name = findsqlval("delivery_mth","'['+delivery_code+']'+ ' '+delivery_name", "delivery_code", $sptm_delivery_mth,$conn);
		
		$sptm_customer_amphur = html_quot($r_sptm['sptm_customer_amphur']);
		$sptm_customer_province = html_quot($r_sptm['sptm_customer_province']);
		$sptm_delivery_mth_desc = html_quot($r_sptm['sptm_delivery_mth_desc']);
		$sptm_expect_receiver_name = html_quot($r_sptm['sptm_expect_receiver_name']);
		$sptm_expect_receiver_tel = html_quot($r_sptm['sptm_expect_receiver_tel']);
		$sptm_expect_receiver = $sptm_expect_receiver_name."/".$sptm_expect_receiver_tel;
	
		$sptm_curprocessor_check = $r_sptm['sptm_curprocessor'];
		if (inlist($sptm_curprocessor_check,$user_login)) {
			$allow_post = true;
		}
		else {
			$sptm_curprocessor_role_access = "";
			$sptm_curprocessor_role_array = explode(",",$user_role);
			for ($c=0;$c<count($sptm_curprocessor_role_array);$c++) {
				if (inlist($sptm_curprocessor_check,$sptm_curprocessor_role_array[$c])) {
					$allow_post = true;
					break;
				}
			}
		}
		$sptm_npd = $r_sptm['sptm_npd'];
		$sptm_copy_refer = $r_sptm['sptm_copy_refer'];
		$sptm_npd_text = "";
		if ($sptm_npd) {
			$sptm_npd_text = " [NPD]";
		}
	}
	
	// $sptm_curr_step = findsqlval("sptm_mstr", "sptm_step_code", "sptm_nbr", $sptm_nbr,$conn);
	// $sptm_next_curprocessor = $approver_user_id;
			
	/////////////////
	//CREATE PDF FILE
	$header =
		"<table width=100% cellpadding=5 cellspacing=5 style='border: 1px dotted black; border-collapse: separate;font-size:12pt'>" .
			"<tr >".
				"<td valign=top style='width:250px'><b>SCG Ceramics PCL.</b><br><span style='font-size:12pt'>ใบสั่งงานจัดกระเบื้องตัวอย่าง</span><br><span style='font-size:8pt'><b><u>$mg_text</u></b></span><br><span style='font-size:8pt'>ผู้พิมพ์: $user_fullname</span></td>".
				"<td style='width:250px;text-align:right'>$sptm_nbr $sptm_npd_text<br><span style='font-size:9pt'>**ใช้สำหรับจัดกระเบื้องตัวอย่าง **<br>หน้าที่: {PAGENO}/{nbpg}</span><br><span style='font-size:8pt'>วันที่พิมพ์: ".date('d/m/Y h:m:s')."  ($sptm_print_cnt)</span></td>".
			"</tr>".
		"</table>" .
		"<table width=100% style='border: 1px dotted black; border-collapse: collapse;font-size:8pt'>" .
			"<tr><td colspan=4 style='height:5px'></td></tr>" .
			"<tr style='height:30px'>".
				"<td align=right style='width:150px'><b>ชื่อผู้ขอเบิก:</b></td>".
				"<td style='width:300px'>$sptm_req_by_name $sptm_tel_contact</td>".
				"<td style='width:150px' align=right><b>วันที่ขอเบิก:</b></td>".
				"<td style='width:150px'>".dmytx($sptm_req_date)."</td>".
			"</tr>".
			"<tr><td colspan=4 style='height:5px'></td></tr>" .
			"<tr style='height:30px'>".
				"<td align=right><b>วัตถุประสงค์เพื่อ:</b></td>".
				"<td>$sptm_reason_name</td>".
				"<td align=right><b>วันที่ขอรับสินค้า:</b></td>".
				"<td>".dmytx($sptm_expect_receipt_date)."</td>".
			"</tr>".
			"<tr><td colspan=4 style='height:5px'></td></tr>" .
			"<tr>".
				"<td align=right><b>รหัสลูกค้า:</b></td>".
				"<td><span style='font-size:10pt'>$sptm_customer_name</span></td>".
				"<td align=right><b>อำเภอ:</b></td>".
				"<td>$sptm_customer_amphur</td>".
			"</tr>".
			"<tr>".
				"<td align=right><b>วิธีการจัดส่ง:</b></td>".
				"<td><span style='font-size:10pt'>$sptm_delivery_mth_name</span></td>".
				"<td align=right><b>จังหวัด:</b></td>".
				"<td>$sptm_customer_province</td>".
			"</tr>".
			"<tr><td colspan=4 style='height:5px'></td></tr>" .
			"<tr>".
				"<td align=right><b>ชื่อผู้รับและเบอร์ติดต่อ:</b></td>".
				"<td colspan=3>$sptm_expect_receiver</td>".
			"</tr>".
			"<tr><td valign=top align=right><b>หมายเหตุการจัดส่ง:</b></td><td colspan=3><span style='font-size:10pt'>$sptm_delivery_mth_desc</span></td></tr>" .
			"<tr><td colspan=4 style='height:5px'></td></tr>" .
		"</table>" .
		"<table width=100% cellpadding=5 cellspacing=5 style='border: 1px dotted black; border-collapse: collapse;font-size:8pt'>" .
			"<tr>" .
				"<td style='border: 1px dotted black;width:50px;text-align:center'><b>ลำดับ</b></td>".
				"<td style='border: 1px dotted black;width:80px;text-align:center'><b>ประเภท</b></td>".
				"<td style='border: 1px dotted black;width:300px;text-align:center'><b>รายการ</b></td>".
				"<td style='border: 1px dotted black;width:50px;text-align:center'><b>จำนวน</b></td>".
				"<td style='border: 1px dotted black;width:90px;text-align:center'><b>หน่วย</b></td>" .
				"<td style='border: 1px dotted black;width:150px;text-align:center'><b>สถานที่จัดเก็บ</b></td>" .
				"<td style='border: 1px dotted black;width:30px;text-align:center'><b>FOC</b></td>" .
				"<td style='border: 1px dotted black;width:50px;text-align:center'><b>(1)</b></td>" .
				"<td style='border: 1px dotted black;width:50px;text-align:center'><b>(2)</b></td>" .
			"</tr>".
		"</table>";
		
	$footer = "<div>
		<table border=0 width=100% style='font-size: 8pt;'>
		<tr><td colspan=2></td><tr>
		<tr><td align=left>ผู้ปฏิบัติงาน: ____________________</td><td align=right>วันที่ปฏิบัติ: _____/_____/________</td></tr>
		<tr><td colspan=2 align=center></td></tr>
		<tr><td colspan=2 align=left><barcode code='$sptm_nbr' type='C39' size='1.2' height='1'/><br>&nbsp;&nbsp;&nbsp;&nbsp;$sptm_nbr</td></tr>
		</table>
		</div>";
	
			  
	require_once('../_libs/mpdf/mpdf.php');
	//ob_start();
	$pdf = new mPDF('th', 'A4', '0', 'THSaraban'); //การตั้งค่ากระดาษถ้าต้องการแนวตั้ง ก็ A4 เฉยๆครับ ถ้าต้องการแนวนอนเท่ากับ A4-L
	$pdf->SetTitle('ใบสั่งงาน');
	//PreDefine
	$pdf->SetHTMLHeader($header);
	//$pdf->SetWatermarkImage("sample.jpg");
	//$pdf->showWatermarkImage = true;
	
	$pdf->SetHTMLFooter("<table width=100% style='font-size:8pt'><tr><td align=center><barcode code='$sptm_nbr' type='C39' size='1.2' height='1'/><br>$sptm_nbr</td></tr></table>");
	$pdf->AddPage('', // L - landscape, P - portrait 
			'', '', '1', '0',
			10, // margin_left
			10, // margin right
			70, // margin top
			20, // margin bottom
			10, // margin header
			5); // margin footer
	//End PreDefine
	$data = "";
		
	$pdf->WriteHTML("<table width=100% cellpadding=5 cellspacing=5 style='border-collapse: collapse;border: 1px dotted black; font-size:8pt'>");
	$line_cnt = 0;
	$item_cnt = 0;
	if ($mg=='ALL' || $mg=='MT') {
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
			
			$sptd_mat_name = html_quot($r_sptd['mat_th_name']);
			if ($r_sptd['mat_th_name'] != $r_sptd['mat_en_name']) {
				$sptd_mat_name .= "<br>" . html_quot($r_sptd['mat_en_name']);
			}
			$sptd_qty_order = $r_sptd['sptd_qty_order'];												
			$sptd_unit_name = html_quot($r_sptd['unit_name']);
			$sptd_remark = html_quot($r_sptd['sptd_remark']);
			$stkm_location = findsqlval("stkm_mstr","stkm_location","stkm_mat_code",$sptd_mat_code,$conn);
			if ($sptd_remark!="") {
				$sptd_remark = "<br><span style='color:red'>**".$sptd_remark."**</span>";
			}
			$sptd_mat_group = "กระเบื้อง";
			$data = "<tr>".
					"<td style='border: 1px dotted black;width:50px;text-align: center'>$item_cnt</td>".
					"<td style='border: 1px dotted black;width:80px'>$sptd_mat_group</td>".
					"<td style='border: 1px dotted black;width:300px'><span style='font-size:10pt'>$sptd_mat_code<br>$sptd_mat_name $sptd_remark</span></td>".
					"<td style='border: 1px dotted black;width:50px;text-align: center'>$sptd_qty_order</td>".
					"<td style='border: 1px dotted black;width:90px;text-align: center'>$sptd_unit_name</td>".
					"<td style='border: 1px dotted black;width:150px;'>$stkm_location</td>".
					"<td style='border: 1px dotted black;width:30px;text-align: center'></td>".
					"<td style='border: 1px dotted black;width:50px;text-align: center'></td>".
					"<td style='border: 1px dotted black;width:50px;text-align: center'></td>".
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
	if ($mg=='ALL' || $mg=='BS') {
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
			$stkm_location = findsqlval("stkm_mstr","stkm_location","stkm_mat_code",$sptd_mat_code,$conn);
			$sptd_mat_group = "บอร์ดมาตรฐาน";
			$data = "<tr>".
					"<td style='border: 1px dotted black;width:50px;text-align: center'>$item_cnt</td>".
					"<td style='border: 1px dotted black;width:80px'>$sptd_mat_group</td>".
					"<td style='border: 1px dotted black;width:300px'><u>$sptd_mat_code</u><br><i>$sptd_mat_name</i></td>".
					"<td style='border: 1px dotted black;width:50px;text-align: center'>$sptd_qty_order</td>".
					"<td style='border: 1px dotted black;width:90px;text-align: center'>$sptd_unit_name</td>".
					"<td style='border: 1px dotted black;width:150px;'>$stkm_location</td>".
					"<td style='border: 1px dotted black;width:30px;text-align: center'></td>".
					"<td style='border: 1px dotted black;width:50px;text-align: center'></td>".
					"<td style='border: 1px dotted black;width:50px;text-align: center'></td>".
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
	if ($mg=='ALL' || $mg=='BC') {
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
			$sptd_remark = "ชื่อบอร์ด: ".$r_sptd['sptd_remark'];
			
			$data = "<tr>".
					"<td style='border: 1px dotted black;width:50px;text-align: center'>$item_cnt</td>".
					"<td style='border: 1px dotted black;' colspan=2>$sptd_remark</td>".
					"<td style='border: 1px dotted black;width:50px;text-align: center'>$sptd_qty_order</td>".
					"<td style='border: 1px dotted black;width:90px;text-align: center'>$sptd_unit_name</td>".
					"<td style='border: 1px dotted black;width:150px;'></td>".
					"<td style='border: 1px dotted black;width:30px;text-align: center'></td>".
					"<td style='border: 1px dotted black;width:50px;text-align: center'></td>".
					"<td style='border: 1px dotted black;width:50px;text-align: center'></td>".
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
				$sptbc_id = $r_bcdet['sptbc_id'];
				$sptbc_mat_code = $r_bcdet['sptbc_mat_code'];
				$sptbc_mat_name = html_quot($r_bcdet['mat_th_name']);
				$sptbc_mat_name_en = html_quot($r_bcdet['mat_en_name']);
				$sptbc_remark = html_quot($r_bcdet['sptbc_remark']);
				$stkm_location = findsqlval("stkm_mstr","stkm_location","stkm_mat_code",$sptbc_mat_code,$conn);
				$line_cnt++;
				$product_cnt++;
				$data = "<tr>".
					"<td style='border: 1px dotted black;width:50px'></td>".
					"<td align=right style='width:80px;border: 1px dotted black;'>$product_cnt) </td>".
					"<td style='border: 1px dotted black;width:300px;'><u>$sptbc_mat_code</u><br>$sptbc_mat_name</td>".
					"<td style='border: 1px dotted black;width:50px;'></td>".
					"<td style='border: 1px dotted black;width:90px;'></td>".
					"<td style='border: 1px dotted black;width:150px;'>$stkm_location</td>".
					"<td style='border: 1px dotted black;width:30px;'></td>".
					"<td style='border: 1px dotted black;width:50px;'></td>".
					"<td style='border: 1px dotted black;width:50px;'></td>".
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
	}
	// $pdf->WriteHTML("UPDATE sptm_mstr SET ".		
			// "sptm_printed='0'," .			
			// "sptm_print_cnt = sptm_print_cnt + 1," .
			// "sptm_first_print_date = '$sptm_first_print_date'," .
			// "sptm_last_print_by = '$user_login'," .
			// "sptm_last_print_date = '$today'" .
			// " WHERE sptm_nbr = '$sptm_nbr'");
	
	$pdf->WriteHTML("</table>");
	$pdf->Output();	
	//Update Print Flag
	$sql = "UPDATE sptm_mstr SET ".		
			"sptm_printed='1'," .			
			"sptm_print_cnt = sptm_print_cnt + 1," .
			"sptm_first_print_date = '$sptm_first_print_date'," .
			"sptm_last_print_by = '$user_login'," .
			"sptm_last_print_date = '$today'" .
			" WHERE sptm_nbr = '$sptm_nbr'";			
	$result = sqlsrv_query($conn,$sql);
	
	// $output_folder = "../_filedownloads/";
	// $output_filename = $sptm_nbr.$mg.".pdf";
	// $pdf->Output($output_folder.$output_filename,'F');
	// END PDF CREATE //
	////////////////////	
?> 