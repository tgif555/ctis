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
	//$server_address = "192.168.1.101";
	//$server_address = "10.28.111.94";
	
	
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
		
		$sql_sptm = "SELECT * from sptm_mstr where sptm_nbr = '$dlvm_sptm_nbr'";
		$result_sptm = sqlsrv_query($conn, $sql_sptm);	
		$r_sptm = sqlsrv_fetch_array($result_sptm, SQLSRV_FETCH_ASSOC);		
		if ($r_sptm) {		
			$sptm_customer_number = $r_sptm['sptm_customer_number'];
			$sptm_customer_dummy = html_quot($r_sptm['sptm_customer_dummy']);
			$sptm_customer_type = $r_sptm['sptm_customer_type'];
			
			$sptm_expect_receiver_name = html_quot($r_sptm['sptm_expect_receiver_name']);
			$sptm_expect_receiver_tel = html_quot($r_sptm['sptm_expect_receiver_tel']);
			
			$sptm_delivery_mth =html_quot( $r_sptm['sptm_delivery_mth']);
			$sptm_delivery_mth_name = html_quot(findsqlval("delivery_mth","delivery_name", "delivery_code", $sptm_delivery_mth,$conn));
			$sptm_delivery_mth_desc = html_quot($r_sptm['sptm_delivery_mth_desc']);
			
			if($sptm_delivery_mth=="MCUS") {
				$sptm_customer_localtion = $r_sptm['sptm_customer_amphur']."/".$r_sptm['sptm_customer_province'];
			}
			else {
				$sptm_customer_localtion = "";
			}
			$sptm_reason_code = $r_sptm['sptm_reason_code'];
			$sptm_reason_name = findsqlval("reason_mstr","'['+reason_code+']'+ ' '+reason_name", "reason_code", $sptm_reason_code,$conn);
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
			$sptm_customer_name = findsqlval("customer","customer_name1", "customer_number", $sptm_customer_number,$conn);
			if ($sptm_customer_name != "") { $sptm_customer_name = '['.$sptm_customer_number.'] ' . $sptm_customer_name; }
			else { $sptm_customer_name = $sptm_customer_dummy; }
			
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
				"<td style='width:250px'><b>SCG Ceramics PCL.</b><br><span style='font-size:9pt'>เอกสารส่งกระเบื้องตัวอย่าง<br>ใบเบิกเลขที่: $dlvm_sptm_nbr $sptm_npd_text</span></td>".
				"<td style='width:250px;text-align:right'>$dlvm_nbr<br><span style='font-size:9pt'>**ใช้สำหรับส่งกระเบื้องตัวอย่าง **<br>หน้าที่: {PAGENO}/{nbpg}</span></td>".
			"</tr>".
		"</table>" .
		"<table width=100% style='border: 1px dotted black; border-collapse: collapse;font-size:8pt'>" .
			"<tr><td colspan=4 style='height:5px'></td></tr>" .
						"<tr>".
							"<td align=right style='width: 110px'><b>ชื่อผู้ขอเบิก:</b></td>".
							"<td>$sptm_req_by_name</td>".
							"<td align=right style='width: 90px;'><b>ผู้พิมพ์:</b></td>".
							"<td style='width: 200px;'>$user_fullname</td>".
						"</tr>".
						"<tr>".
							"<td align=right><b>สังกัด:</b></td>".
							"<td>$sptm_req_by_sec</td>".
							"<td style='width:160px' align=right><b>วันที่พิมพ์:</b></td>".
							"<td style='width:110px'>" . date('d/m/Y H:i:s') . "</td>".
						"</tr>".
						"<tr>".
							"<td align=right><b>รหัสลูกค้า:</b></td>".
							"<td>$sptm_customer_name</td>".
							"<td align=right><b>ครั้งที่พิมพ์:</b></td>".
							"<td>$dlvm_print_cnt</td>".
						"</tr>" .
						"<tr>".
							"<td align=right><b>วัตถุประสงค์:</b></td>".
							"<td>$sptm_reason_name</td>".
							"<td align=right><b>ชื่อผู้รับสินค้า:</b></td>".
							"<td>$sptm_expect_receiver_name/$sptm_expect_receiver_tel</td>".
						"</tr>" .
						"<tr>" .
							"<td align=right><b>วิธีการจัดส่ง:</b></td>".
							"<td>[$sptm_delivery_mth] $sptm_delivery_mth_name</td>".
							"<td align=right><b>อำเภอ/จังหวัด:</b></td>".
							"<td>$sptm_customer_localtion</td>".
						"</tr>".
						"<tr>" .
							"<td align=right><b>หมายเหตุการจัดส่ง:</b></td>".
							"<td colspan=3>$sptm_delivery_mth_desc</td>".
						"</tr>".
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
		<p>** จำนวน " . $dlvm_packing_count . " แพ็ค (น้ำหนักรวม: ".sumdlvdweight($dlvm_nbr,$conn)." KG.) **</p>
		<table width=100% cellpadding=5 cellspacing=5 style='border-collapse: collapse;border: 1px dotted black; font-size:8pt'>
		<tr>
			<td width=35% valign=top style='border: 1px dotted black;'>
				<table style='border-collapse: separate; font-size:9pt'>
					<tr><td colspan=2><b><u>ข้อมูลการจัดส่ง:</u></b></td></tr>
					<tr><td colspan=2 style='height:10px'></td></tr>
					<tr>
						<td style='width: 70px'>บริษัทขนส่ง:</td>
						<td>$dlvm_transport_tspm_code</td>
					</tr>
					<tr><td colspan=2 style='height:3px'></td></tr>
					<tr>
						<td>ทะเบียนรถ:</td>
						<td>$dlvm_transport_car_nbr</td>
					</tr>
					<tr><td colspan=2 style='height:3px'></td></tr>
					<tr>
						<td>ชื่อผู้ขับขี่:</td>
						<td>$dlvm_transport_driver_name</td>
					</tr>
					<tr><td colspan=2 style='height:3px'></td></tr>
					<tr>
						<td>วันที่:</td>
						<td>".dmyhmsdb($dlvm_transport_date,'y')."</td>
					</tr>
					<tr>
						<td>หมายเหตุ:</td>
						<td>$dlvm_transport_cmmt</td>
					</tr>
				</table>
			</td>
			<td width=25% valign=top style='border: 1px dotted black;'>
				<table style='border-collapse: separate; font-size:9pt'>
					<tr><td colspan=2><b><u>แผนกจัดส่ง:</u></b></td></tr>
					<tr><td colspan=2 style='height:10px'></td></tr>
					<tr>
						<td style='width: 60px'>ผู้อนุมัติ:</td>
						<td>______________</td>
					</tr>
					<tr><td colspan=2 style='height:3px'></td></tr>
					<tr>
						<td>วันที่:</td>
						<td>____/____/_____</td>
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
			<td width=30% valign=top style='border: 1px dotted black;'>
				<table style='border-collapse: separate; font-size:9pt'>
					<tr><td colspan=2><b><u>ข้อมูลการรับสินค้า:</u></b></td></tr>
					<tr><td colspan=2 style='height:10px'></td></tr>
					<tr>
						<td stype='width: 100px'>ผู้รับสินค้า:</td>
						<td>______________</td>
					</tr>
					<tr><td colspan=2 style='height:3px'></td></tr>
					<tr>
						<td>วันที่:</td>
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
			<td width=15% align=center valign=top style='border: 1px dotted white;'>
				<barcode code='".$app_url."sampletile/dlvmrct.php?d=".encrypt($dlvm_nbr, $key)."' type='QR' size='1.4' error='L' disableborder = '1'/>
			</td>
		</tr>
		<tr><td colspan=4 align=center><barcode code='$dlvm_nbr' type='C39' size='1' height='1'/><br>$dlvm_nbr</td></tr>
		</table>
		</div>";
	
		// "<table width=100% border=1 cellpadding=5 cellspacing=5 style='border-collapse: collapse; font-size:8pt'>" .
			// "<tr><td align=right'><b>ผู้ปฏิบัติงาน: ......................</b></td></tr>" .
			// "<tr><td>วันที่ปฏิบัติงาน: ..../...../......</td></tr>" .
			// "<tr><td align=center>หน้าที่: {PAGENO}/{nbpg}</td></tr>" .
		// "</table>";	
			  
	require_once('../_libs/mpdf/mpdf.php');
	//ob_start();
	$pdf = new mPDF('th', 'A4', '0', 'THSaraban'); //การตั้งค่ากระดาษถ้าต้องการแนวตั้ง ก็ A4 เฉยๆครับ ถ้าต้องการแนวนอนเท่ากับ A4-L
	$pdf->SetTitle('Delivery Form');
	//PreDefine
	$pdf->SetHTMLHeader($header);
	//$pdf->SetWatermarkImage("sample.jpg");
	//$pdf->showWatermarkImage = true;
	$pdf->SetHTMLFooter("<table width=100% style='font-size:8pt'><tr><td align=center><barcode code='$dlvm_nbr' type='C39' size='1.2' height='1'/><br>$dlvm_nbr</td></tr></table>");
	$pdf->AddPage('', // L - landscape, P - portrait 
			'', '', '1', '0',
			10, // margin_left
			10, // margin right
			61, // margin top
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
		$dlvd_qty = $r_dlvd['dlvd_qty'];												
		$dlvd_unit_name = $r_dlvd['unit_name'];
		$dlvd_mat_group = "กระเบื้อง";
		$data = "<tr>".
				"<td style='border: 1px dotted black;width:50px;text-align: center'>$n</td>".
				"<td style='border: 1px dotted black;width:100px'>$dlvd_mat_group</td>".
				"<td style='border: 1px dotted black;width:400px'>[$dlvd_mat_code] $dlvd_mat_name</td>".
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
				"<td style='border: 1px dotted black;width:400px'>[$dlvd_mat_code] $dlvd_mat_name</td>".
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
				"<td style='border: 1px dotted black;'>[$sptbc_mat_code] $sptbc_mat_name</td>".
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

	// $output_folder = "../_filedownloads/";
	// $output_filename = "DE_".$dlvm_nbr.".pdf";
	// $pdf->Output($output_folder.$output_filename,'F');
	// END PDF CREATE //
	////////////////////	
?> 