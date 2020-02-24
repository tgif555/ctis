<?php
	include("../_incs/showsaving.php");
	include("../_incs/chksession.php");
	include("../_incs/config.php"); 	
	include("../_incs/funcServer.php");	
	date_default_timezone_set('Asia/Bangkok');
	
	$today = date("Y-m-d H:i:s");  	
	$dlvm_nbr = decrypt(mssql_escape($_REQUEST['dlvmnumber']), $key);
	
	$total_record = sumdlvddet($dlvm_nbr,$conn);
	$max_line = 20;
	
	$sql_dlvm = "SELECT * from dlvm_mstr where dlvm_nbr = '$dlvm_nbr'";
	$result_dlvm = sqlsrv_query($conn, $sql_dlvm);	
	$r_dlvm = sqlsrv_fetch_array($result_dlvm, SQLSRV_FETCH_ASSOC);		
	if ($r_dlvm) {		
		$dlvm_sptm_nbr = $r_dlvm['dlvm_sptm_nbr'];
		$dlvm_postdlv_date = $r_dlvm['dlvm_postdlv_date'];
		$dlvm_postdlv_by = $r_dlvm['dlvm_postdlv_by'];
		$dlvm_postdlv_cmmt = html_quot($r_dlvm['dlvm_postdlv_cmmt']);
		$dlvm_packing_count = html_quot($r_dlvm['dlvm_packing_count']);
		$dlvm_printed = $r_dlvm['dlvm_printed'];
		$dlvm_print_cnt = $r_dlvm['dlvm_print_cnt'];
		$dlvm_transport_date = $r_dlvm['dlvm_transport_date'];
		$dlvm_transport_tspm_code = $r_dlvm['dlvm_transport_tspm_code'];
		$dlvm_transport_ref_no = html_quot($r_dlvm['dlvm_transport_ref_no']);
		$dlvm_transport_driver_name = html_quot($r_dlvm['dlvm_transport_driver_name']);
		$dlvm_transport_driver_tel = html_quot($r_dlvm['dlvm_transport_driver_tel']);
		$dlvm_transport_car_nbr = html_quot($r_dlvm['dlvm_transport_car_nbr']);
		$dlvm_transport_cmmt = html_quot($r_dlvm['dlvm_transport_cmmt']);
		$dlvm_dlvs_step_code = $r_dlvm['dlvm_dlvs_step_code'];
		$dlvm_create_by = $r_dlvm['dlvm_create_by'];
		$dlvm_create_date = $r_dlvm['dlvm_create_date'];
		$dlvm_packing_weight = $r_dlvm['dlvm_packing_weight'];
		$dlvm_packing_by = $r_dlvm['dlvm_packing_by'];
		$dlvm_packing_by_name = findsqlval("worker_mstr","worker_name","worker_code",$dlvm_packing_by,$conn);
		$dlvm_packing_date = $r_dlvm['dlvm_packing_date'];
		
		
		$sql_sptm = "SELECT * from sptm_mstr where sptm_nbr = '$dlvm_sptm_nbr'";
		$result_sptm = sqlsrv_query($conn, $sql_sptm);	
		$r_sptm = sqlsrv_fetch_array($result_sptm, SQLSRV_FETCH_ASSOC);		
		if ($r_sptm) {		
			$sptm_customer_number = $r_sptm['sptm_customer_number'];
			$sptm_customer_dummy = html_quot($r_sptm['sptm_customer_dummy']);
			$sptm_customer_type = $r_sptm['sptm_customer_type'];
			
			$sptm_expect_receiver_name = html_quot($r_sptm['sptm_expect_receiver_name']);
			$sptm_expect_receiver_tel = html_quot($r_sptm['sptm_expect_receiver_tel']);
			$sptm_customer_amphur = html_quot($r_sptm['sptm_customer_amphur']);
			$sptm_customer_province = html_quot($r_sptm['sptm_customer_province']);
			$sptm_expect_receiver = $sptm_expect_receiver_name."/".$sptm_expect_receiver_tel;
			$sptm_delivery_mth =html_quot( $r_sptm['sptm_delivery_mth']);
			$sptm_delivery_mth_name = html_quot(findsqlval("delivery_mth","delivery_name", "delivery_code", $sptm_delivery_mth,$conn));
			$sptm_delivery_mth_desc = html_quot($r_sptm['sptm_delivery_mth_desc']);
			$sptm_print_cnt = $r_sptm['sptm_print_cnt'];
			
			$sptm_customer_location = $r_sptm['sptm_customer_amphur']."/".$r_sptm['sptm_customer_province'];
			
			$sptm_reason_code = $r_sptm['sptm_reason_code'];
			$sptm_reason_name = findsqlval("reason_mstr","reason_name", "reason_code", $sptm_reason_code,$conn);
			$sptm_req_by = $r_sptm['sptm_req_by'];
			$sptm_req_by_name = findsqlval("emp_mstr","emp_th_firstname+' '+emp_th_lastname", "emp_user_id", $sptm_req_by,$conn);
			$sptm_req_by_sec = html_quot(findsqlval("emp_mstr","emp_en_sec", "emp_user_id", $sptm_req_by,$conn));
			$sptm_req_date = $r_sptm['sptm_req_date'];
			if ($r_sptm['sptm_step_code'] == '0') {
				$sptm_submit_date = date('d/m/Y H:i:s');
			} else {
				$sptm_submit_date = dmyhmsdb($r_sptm['sptm_submit_date'],'Y');
			}
			$sptm_remark = html_quot($r_sptm['sptm_remark']);
			
			$sptm_expect_receipt_date = $r_sptm['sptm_expect_receipt_date'];
			$sptm_tel_contact = findsqlval("emp_mstr","emp_tel_contact","emp_user_id",$sptm_req_by,$conn);
			if ($sptm_tel_contact != "") {
				$sptm_tel_contact = " <span style='color:red'>(".$sptm_tel_contact.")</span>";
			}
			
			// $sptm_customer_name = findsqlval("customer","customer_name1", "customer_number", $sptm_customer_number,$conn);
			// if ($sptm_customer_name != "") { $sptm_customer_name = '['.$sptm_customer_number.'] ' . $sptm_customer_name; }
			// else { $sptm_customer_name = $sptm_customer_dummy; }
			
			if($sptm_customer_number != "DUMMY") {
				$sptm_customer_name = html_quot(findsqlval("customer","customer_name1", "customer_number", $sptm_customer_number,$conn));
				if ($sptm_customer_name != "") {
					$sptm_customer_name = "[".$sptm_customer_number."] " . $sptm_customer_name;
				}
			}
			else {
				$sptm_customer_name = '<font color=red>*</font>' .$sptm_customer_dummy;
			}
			
			$sptm_npd = $r_sptm['sptm_npd'];
			$sptm_npd_text = "";
			if ($sptm_npd!="") {$sptm_npd_text = " [NPD]";}

		}
	}
	
	/////////////////
	//CREATE PDF FILE
	$header =
		"<table width=100% cellpadding=5 cellspacing=5 style='border: 1px dotted black; border-collapse: separate;font-size:12pt'>" .
			"<tr >".
				"<td style='width:250px'><b>SCG Ceramics PCL.</b><br><span style='font-size:9pt'>Package Detail<br></span><span style='font-size:8pt'>อ้างอิงใบเบิกเลขที่:</span> <span style='font-size:12pt'>$dlvm_sptm_nbr $sptm_npd_text</span><br><span style='font-size:8pt'>ผู้พิมพ์: $user_fullname</span></td>".
				"<td style='width:250px;text-align:right'>$dlvm_nbr<br><span style='font-size:9pt'>**ใช้สำหรับติดหน้า Package **<br>หน้าที่: {PAGENO}/{nbpg}</span><br><span style='font-size:8pt'>วันที่พิมพ์: ".date('d/m/Y h:m:s')."  ($sptm_print_cnt)</span></td>".
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
				"<td><span style='font-size:11pt'>$sptm_customer_name</span></td>".
				"<td align=right><b>อำเภอ:</b></td>".
				"<td>$sptm_customer_amphur</td>".
			"</tr>".
			"<tr>".
				"<td align=right><b>วิธีการจัดส่ง:</b></td>".
				"<td><span style='font-size:11pt'>$sptm_delivery_mth_name</span></td>".
				"<td align=right><b>จังหวัด:</b></td>".
				"<td>$sptm_customer_province</td>".
			"</tr>".
			"<tr><td colspan=4 style='height:5px'></td></tr>" .
			"<tr>".
				"<td align=right><b>ชื่อผู้รับและเบอร์ติดต่อ:</b></td>".
				"<td colspan=3><span style='font-size:11pt'>$sptm_expect_receiver</span></td>".
			"</tr>".
			"<tr>
				<td valign=top align=right><b>หมายเหตุการจัดส่ง:</b></td>
				<td colspan=3><span style='font-size:11pt'>$sptm_delivery_mth_desc</span></td>
			</tr>" .
			"<tr><td colspan=4 style='height:5px'></td></tr>" .
						
		"</table>" .
		"<table width=100% cellpadding=5 cellspacing=5 style='border-collapse: collapse;border: 1px dotted black; font-size:8pt'>" .
			"<tr>" .
				"<td style='border: 1px dotted black;width:50px;text-align:center'><b>ลำดับ</b></td>".
				"<td style='border: 1px dotted black;width:100px;text-align:center'><b>ประเภท</b></td>".
				"<td style='border: 1px dotted black;width:400px;text-align:center'><b>รายการ</b></td>".
				"<td style='border: 1px dotted black;width:80px;text-align:center'><b>จำนวน</b></td>".
				"<td style='border: 1px dotted black;text-align:center'><b>หน่วย</b></td>" .
			"</tr>".
		"</table>";
		
	$footer = "<div>
		<p>** (น้ำหนักรวม: $dlvm_packing_weight KG.) **</p>
		<table width=100% cellpadding=5 cellspacing=5 style='border-collapse: collapse;border: 1px dotted black; font-size:8pt'>
		<tr>
			<td>
				<br><br>
				ผู้เตรียม: $dlvm_packing_by_name , วันที่: " . dmytx($dlvm_packing_date) .
				"<br><br>
			</td>
			<td  align=right><barcode code='$dlvm_nbr' type='C39' size='1' height='1'/><br>$dlvm_nbr&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
		</tr>
		
		</table>
		</div>";
	
	require_once('../_libs/mpdf/mpdf.php');
	//ob_start();
	$pdf = new mPDF('th', 'A4', '0', 'THSaraban'); //การตั้งค่ากระดาษถ้าต้องการแนวตั้ง ก็ A4 เฉยๆครับ ถ้าต้องการแนวนอนเท่ากับ A4-L
	$pdf->SetTitle('Package No '.$dlvm_nbr);
	//PreDefine
	$pdf->SetHTMLHeader($header);
	//$pdf->SetWatermarkImage("sample.jpg");
	//$pdf->showWatermarkImage = true;
	$pdf->SetHTMLFooter("<table width=100% style='font-size:8pt'><tr><td align=center><barcode code='$dlvm_nbr' type='C39' size='1.2' height='1'/><br>$dlvm_nbr</td></tr></table>");
	$pdf->AddPage('', // L - landscape, P - portrait 
			'', '', '1', '0',
			10, // margin_left
			10, // margin right
			74, // margin top
			20, // margin bottom
			10, // margin header
			5); // margin footer
	//End PreDefine
	$data = "";
		
	$pdf->WriteHTML("<table width=100% cellpadding=5 cellspacing=5 style='border-collapse: collapse;border: 1px dotted black; font-size:8pt'>");
	$n = 0;
	$sql = "SELECT * FROM dlvd_det" .
		" INNER JOIN material ON mat_code = dlvd_mat_code" .
		" INNER JOIN unit_mstr ON unit_code = dlvd_unit_code" .
		" where dlvd_dlvm_nbr = '$dlvm_nbr' and dlvd_mat_group = 'MT'";
														
	$result = sqlsrv_query( $conn, $sql );											
	while($r_dlvd = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
		$n++;
		$dlvd_mat_code = $r_dlvd['dlvd_mat_code'];												
		$dlvd_mat_name = $r_dlvd['mat_th_name'];
		$dlvd_mat_name_en = $r_dlvd['mat_en_name'];
		$dlvd_qty = $r_dlvd['dlvd_qty'];												
		$dlvd_unit_name = $r_dlvd['unit_name'];
		$dlvd_mat_group = "กระเบื้อง";
		
		$dlvd_mat_name = html_quot($r_dlvd['mat_th_name']);
		if ($r_dlvd['mat_th_name'] != $r_dlvd['mat_en_name']) {
			$dlvd_mat_name .= "<br>" . html_quot($r_dlvd['mat_en_name']);
		}
			
		$data = "<tr>".
				"<td style='border: 1px dotted black;width:50px;text-align: center'>$n</td>".
				"<td style='border: 1px dotted black;width:100px'>$dlvd_mat_group</td>".
				"<td style='border: 1px dotted black;width:400px;font-size:11pt;'><b>$dlvd_mat_code</b><br>$dlvd_mat_name</td>".
				"<td style='border: 1px dotted black;width:80px;text-align: center'>$dlvd_qty</td>".
				"<td style='border: 1px dotted black;text-align: center'>$dlvd_unit_name</td>".
				"</tr>";
		$pdf->WriteHTML($data);
		if ($n % $max_line == 0) {
			if ($n < $total_record) {
				$pdf->WriteHTML("</table>");
				$pdf->AddPage();
				$pdf->WriteHTML("<table width=100% cellpadding=5 cellspacing=5 style='border-collapse: collapse;border: 1px dotted black; font-size:8pt'>");
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
	$sql = "SELECT * FROM dlvd_det" .
		" INNER JOIN material ON mat_code = dlvd_mat_code" .
		" INNER JOIN unit_mstr ON unit_code = dlvd_unit_code" .
		" where dlvd_dlvm_nbr = '$dlvm_nbr' and dlvd_mat_group = 'BS'";
														
	$result = sqlsrv_query( $conn, $sql );											
	while($r_dlvd = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
		$n++;
		$dlvd_mat_code = $r_dlvd['dlvd_mat_code'];												
		$dlvd_mat_name = html_quot($r_dlvd['mat_th_name']);
		$dlvd_qty = $r_dlvd['dlvd_qty'];												
		$dlvd_unit_name = html_quot($r_dlvd['unit_name']);
		$dlvd_mat_group = "บอร์ดมาตรฐาน";
		$data = "<tr>".
				"<td style='border: 1px dotted black;width:50px;text-align: center'>$n</td>".
				"<td style='border: 1px dotted black;width:100px'>$dlvd_mat_group</td>".
				"<td style='border: 1px dotted black;width:400px'>$dlvd_mat_code<br>$dlvd_mat_name</td>".
				"<td style='border: 1px dotted black;width:80px;text-align: center'>$dlvd_qty</td>".
				"<td style='border: 1px dotted black;text-align: center'>$dlvd_unit_name</td>".
				"</tr>";
		$pdf->WriteHTML($data);
		if ($n % $max_line == 0) {
			if ($n < $total_record) {
				$pdf->WriteHTML("</table>");
				$pdf->AddPage();
				$pdf->WriteHTML("<table width=100% cellpadding=5 cellspacing=5 style='border-collapse: collapse;border: 1px dotted black; font-size:8pt'>");
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
	$sql = "SELECT * FROM dlvd_det" .
		" INNER JOIN material ON mat_code = dlvd_mat_code" .
		" INNER JOIN unit_mstr ON unit_code = dlvd_unit_code" .
		" where dlvd_dlvm_nbr = '$dlvm_nbr' and dlvd_mat_group = 'BC'";
														
	$result = sqlsrv_query( $conn, $sql );											
	while($r_dlvd = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
		$n++;
		$dlvd_id = $r_dlvd['dlvd_id'];
		$dlvd_sptd_id = $r_dlvd['dlvd_sptd_id'];
		$dlvd_mat_code = "บอร์ดปรับแต่ง";												
		$dlvd_mat_name = "";
		$dlvd_qty = $r_dlvd['dlvd_qty'];												
		$dlvd_unit_name = html_quot($r_dlvd['unit_name']);
		$dlvd_mat_group = "บอร์ดปรับแต่ง";
		
		$data = "<tr>".
				"<td style='border: 1px dotted black;width:50px;text-align: center'>$n</td>".
				"<td style='border: 1px dotted black;width:100px'>$dlvd_mat_group</td>".
				"<td style='border: 1px dotted black;width:400px'>[$dlvd_mat_group]</td>".
				"<td style='border: 1px dotted black;width:80px;text-align: center'>$dlvd_qty</td>".
				"<td style='border: 1px dotted black;text-align: center'>$dlvd_unit_name</td>".
				"</tr>";
		$pdf->WriteHTML($data);
		if ($n % $max_line == 0) {
			if ($n < $total_record) {
				$pdf->WriteHTML("</table>");
				$pdf->AddPage();
				$pdf->WriteHTML("<table width=100% cellpadding=5 cellspacing=5 style='border-collapse: collapse;border: 1px dotted black; font-size:8pt'>");
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
		
		$bc_product_cnt = 0;
		$sql_bcdet = "SELECT * FROM sptbc_det" .
			" INNER JOIN material ON mat_code = sptbc_mat_code" .
			" where sptbc_sptm_nbr = '$dlvm_sptm_nbr' and sptbc_sptd_id = '$dlvd_sptd_id'";
															
		$result_bcdet = sqlsrv_query( $conn, $sql_bcdet );											
		while($r_bcdet = sqlsrv_fetch_array($result_bcdet, SQLSRV_FETCH_ASSOC)) {	
			$sptbc_id = $r_bcdet['sptbc_id'];
			$sptbc_mat_code = $r_bcdet['sptbc_mat_code'];
			$sptbc_mat_name = html_quot($r_bcdet['mat_th_name']);
			$sptbc_remark = html_quot($r_bcdet['sptbc_remark']);
			$bc_product_cnt++;
			$n++;				
			$data = "<tr>".
				"<td colspan=2 style='border: 1px dotted black;'></td>".
				"<td style='border: 1px dotted black;'>$sptbc_mat_code<br>$sptbc_mat_name</td>".
				"<td style='border: 1px dotted black;'></td>".
				"<td style='border: 1px dotted black;'></td>".
				"</tr>";
			$pdf->WriteHTML($data);
			if ($n % $max_line == 0) {
				if ($n < $total_record) {
					$pdf->WriteHTML("</table>");
					$pdf->AddPage();
					$pdf->WriteHTML("<table width=100% cellpadding=5 cellspacing=5 style='border-collapse: collapse;border: 1px dotted black; font-size:8pt'>");
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
	}
	$pdf->WriteHTML("</table>");
	$pdf->Output();
	
	//Update Print Flag
	$sql = "UPDATE dlvm_mstr SET ".		
			"dlvm_printed='1'," .			
			"dlvm_print_cnt = dlvm_print_cnt + 1," .
			"dlvm_last_print_by = '$user_login'," .
			"dlvm_last_print_date = '$today'" .
			" WHERE dlvm_nbr = '$dlvm_nbr'";			
	$result = sqlsrv_query($conn,$sql);

?> 