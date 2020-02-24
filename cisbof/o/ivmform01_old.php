<?php
	include("../_incs/showsaving.php");
	include("../_incs/chksession.php");
	include("../_incs/config.php"); 	
	include("../_incs/funcServer.php");	
	date_default_timezone_set('Asia/Bangkok');
	
	$today = date("Y-m-d H:i:s"); 
	$print_date = date("d/m/Y H:i:s");
	$ivm_nbr = decrypt(mssql_escape($_REQUEST['ivmnumber']), $key);
	$total_record = cntivddet($ivm_nbr,$conn);
	$max_line = 20;
	
	$sql_ivm = "SELECT * from ivm_mstr where ivm_nbr = '$ivm_nbr'";
	$result_ivm = sqlsrv_query($conn, $sql_ivm);	
	$r_ivm = sqlsrv_fetch_array($result_ivm, SQLSRV_FETCH_ASSOC);		
	if ($r_ivm) {		
		$ivm_date = $r_ivm['ivm_date'];
		$ivm_wpm_nbr = html_quot($r_ivm['ivm_wpm_nbr']);
		$ivm_customer_number = $r_ivm['ivm_customer_number'];
		$ivm_customer_dummy = html_quot($r_ivm['ivm_customer_dummy']);
		$ivm_customer_type = $r_ivm['ivm_customer_type'];
		$ivm_customer_amphur = html_quot($r_ivm['ivm_customer_amphur']);
		$ivm_customer_province = html_quot($r_ivm['ivm_customer_province']);
		$ivm_transport_car_nbr = html_quot($r_ivm['ivm_transport_car_nbr']);
		$ivm_transport_tspm_code = html_quot($r_ivm['ivm_transport_tspm_code']);
		$ivm_transport_tspm_other = html_quot($r_ivm['ivm_transport_tspm_other']);
		$ivm_transport_ref_no = html_quot($r_ivm['ivm_transport_ref_no']);
		$ivm_transport_driver_name = html_quot($r_ivm['ivm_transport_driver_name']);
		$ivm_transport_driver_tel = html_quot($r_ivm['ivm_transport_driver_tel']);
		$ivm_transport_cmmt = html_quot($r_ivm['ivm_transport_cmmt']);
		$ivm_printed = $r_ivm['ivm_printed'];
		$ivm_print_by = $r_ivm['ivm_print_by'];
		$ivm_print_date = $r_ivm['ivm_print_date'];
		$ivm_print_cnt = $r_ivm['ivm_print_cnt'];
		$ivm_status_code = $r_ivm['ivm_status_code'];
		$ivm_create_by = $r_ivm['ivm_create_by'];
		$ivm_create_date = $r_ivm['ivm_create_date'];
		
		$ivm_print_by_name = findsqlval("emp_mstr","emp_th_firstname+ ' '+ emp_th_lastname","emp_user_id",$ivm_print_by,$conn);
														
		if($ivm_customer_number != "DUMMY") {
			$ivm_customer_name = findsqlval("customer","customer_name1", "customer_number", $ivm_customer_number,$conn);
			if ($ivm_customer_name != "") {
				$ivm_customer_name = '['.$ivm_customer_number.'] ' . $ivm_customer_name;
			}
		}
		else {
			$ivm_customer_name = '<font color=red>[DUMMY]</font> ' .$ivm_customer_dummy;
		}
		$ivm_weight = sumdlvmweight($ivm_nbr,$conn);										
		$ivm_weight_diff = $ivm_weight - (int) $ivm_weight;
		if ($ivm_weight_diff>0) {$ivm_weight = number_format($ivm_weight,2);}
		else {$ivm_weight = number_format($ivm_weight,0);}
	}
	
	/////////////////
	//CREATE PDF FILE
	$header =
		"<table width=100% cellpadding=5 cellspacing=5 style='border: 1px dotted black; border-collapse: separate;font-size:12pt'>" .
			"<tr >".
				"<td style='width:250px'><b>SCG Ceramics PCL.</b><br><span style='font-size:9pt'>ใบส่งกระเบื้องตัวอย่าง<br>ใบขึ้นสินค้า: $ivm_wpm_nbr</span></td>".
				"<td style='width:250px;text-align:right'>$ivm_nbr<br><span style='font-size:9pt'>**ใช้สำหรับส่งกระเบื้องตัวอย่าง **<br>หน้าที่: {PAGENO}/{nbpg}</span></td>".
			"</tr>".
		"</table>" .
		"<table width=100% style='border: 1px dotted black; border-collapse: collapse;font-size:8pt'>" .
			"<tr><td colspan=4 style='height:5px'></td></tr>" .
			"<tr>".
				"<td style='width:150px' align=right><b>รหัสลูกค้า:</b></td>".
				"<td>$ivm_customer_name</td>".
				"<td align=right><b>ครั้งที่พิมพ์:</b></td>".
				"<td>$ivm_print_cnt</td>".
			"</tr>" .
			"<tr>".
				"<td align=right><b>อำเภอ:</b></td>".
				"<td>$ivm_customer_amphur</td>".
				"<td align=right><b>จังหวัด:</b></td>".
				"<td>$ivm_customer_province</td>".
			"</tr>" .
			"<tr>" .
				"<td align=right><b>ทะเบียนรถ:</b></td>".
				"<td>$ivm_transport_car_nbr</td>".
				"<td align=right><b>ชื่อผู้ขนส่ง:</b></td>".
				"<td>$ivm_transport_driver_name</td>".
			"</tr>".
			"<tr><td colspan=4 style='height:5px'></td></tr>" .
		"</table>" .
		
		"<table width=100% cellpadding=5 cellspacing=5 style='border-collapse: collapse;border: 1px dotted black; font-size:8pt'>" .
			"<tr>" .
				"<td style='border: 1px dotted black;width:50px;text-align:center'><b>ลำดับ</b></td>".
				"<td style='border: 1px dotted black;width:100px;text-align:center'><b>Package No</b></td>".
				"<td style='border: 1px dotted black;width:100px;text-align:center'><b>หมายเลขใบเบิก</b></td>".
				"<td style='border: 1px dotted black;width:170px;text-align:center'><b>ชื่อผู้ขอเบิก</b></td>".
				"<td style='border: 1px dotted black;width:200px;text-align:center'><b>ชื่อผู้ติดต่อรับสินค้า</b></td>".
				"<td style='border: 1px dotted black;text-align:center'><b>จำนวนรายการ</b></td>".
			"</tr>".
		"</table>";
		
	$footer = "<div>
		<p>** (น้ำหนักรวม: ".$ivm_weight." KG.) **</p>
		<table width=100% cellpadding=5 cellspacing=5 style='border-collapse: collapse;border: 1px dotted black; font-size:10pt'>
		<tr>
			<td width=35% valign=top style='border: 1px dotted black;'>
				<table style='border-collapse: separate; font-size:9pt'>
					<tr><td colspan=2><b><u>ข้อมูลการจัดส่ง:</u></b></td></tr>
					<tr><td colspan=2 style='height:10px'></td></tr>
					<tr>
						<td style='width: 70px'>บริษัทขนส่ง:</td>
						<td>$ivm_transport_tspm_code</td>
					</tr>
					<tr><td colspan=2 style='height:3px'></td></tr>
					<tr>
						<td>ทะเบียนรถ:</td>
						<td>$ivm_transport_car_nbr</td>
					</tr>
					<tr><td colspan=2 style='height:3px'></td></tr>
					<tr>
						<td>ชื่อผู้ขับขี่:</td>
						<td>$ivm_transport_driver_name</td>
					</tr>
					<tr><td colspan=2 style='height:3px'></td></tr>
					<tr>
						<td>วันที่:</td>
						<td>".dmytx($ivm_date)."</td>
					</tr>
					<tr>
						<td>หมายเหตุ:</td>
						<td>$ivm_transport_cmmt</td>
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
				<barcode code='".$app_url."sampletile/ivmrct.php?d=".encrypt($ivm_nbr, $key)."' type='QR' size='1.4' error='L' disableborder = '1'/>
			</td>
		</tr>
		<tr><td colspan=4 align=center><barcode code='$ivm_nbr' type='C39' size='1' height='1'/><br>$ivm_nbr</td></tr>
		</table>
		</div>";
	
			  
	require_once('../_libs/mpdf/mpdf.php');
	//ob_start();
	$pdf = new mPDF('th', 'A4', '0', 'THSaraban'); //การตั้งค่ากระดาษถ้าต้องการแนวตั้ง ก็ A4 เฉยๆครับ ถ้าต้องการแนวนอนเท่ากับ A4-L
	$pdf->SetTitle('ใบส่งของ');
	//PreDefine
	$pdf->SetHTMLHeader($header);
	//$pdf->SetWatermarkImage("sample.jpg");
	//$pdf->showWatermarkImage = true;
	$pdf->SetHTMLFooter("<table width=100% style='font-size:8pt'><tr><td align=center><barcode code='$dlvm_nbr' type='C39' size='1.2' height='1'/><br>$dlvm_nbr</td></tr></table>");
	$pdf->AddPage('', // L - landscape, P - portrait 
			'', '', '1', '0',
			10, // margin_left
			10, // margin right
			50, // margin top
			20, // margin bottom
			10, // margin header
			5); // margin footer
	//End PreDefine
	$data = "";
		
	$pdf->WriteHTML("<table width=100% cellpadding=5 cellspacing=5 style='border-collapse: collapse;border: 1px dotted black; font-size:8pt'>");
										
	$n = 0;													
	$sql_ivd = "SELECT * FROM ivd_det" .
		" INNER JOIN ivm_mstr ON ivm_nbr = ivd_ivm_nbr" .
		" INNER JOIN dlvm_mstr ON dlvm_nbr = ivd_dlvm_nbr " .
		" INNER JOIN sptm_mstr ON sptm_nbr = dlvm_sptm_nbr " .
		" WHERE ivd_ivm_nbr = '$ivm_nbr'";
			
	$result_ivd = sqlsrv_query( $conn, $sql_ivd);
	while($r_ivd = sqlsrv_fetch_array($result_ivd, SQLSRV_FETCH_ASSOC)) {	
		$ivd_id = $r_ivd['ivd_id'];
		$ivd_ivm_nbr = $r_ivd['ivd_ivm_nbr'];
		$ivd_wpm_nbr = $r_ivd['ivd_wpm_nbr'];
		$ivd_dlvm_nbr = $r_ivd['ivd_dlvm_nbr'];
		$ivd_sptm_nbr = $r_ivd['ivd_sptm_nbr'];
		$ivd_receive_status = $r_ivd['ivd_receive_status'];
		$ivd_receive_status_cmmt = $r_ivd['ivd_receive_status_cmmt'];
		$ivd_create_by = $r_ivd['ivd_create_by'];
		$ivd_create_date = $r_ivd['ivd_create_date'];
		$ivd_sptm_req_by = $r_ivd['sptm_req_by'];
		$ivd_sptm_expect_receiver_name = $r_ivd['sptm_expect_receiver_name'];
		$ivd_sptm_expect_receiver_tel = $r_ivd['sptm_expect_receiver_tel'];
		$ivd_sptm_req_by_name = findsqlval("emp_mstr","emp_th_firstname+' '+emp_th_lastname","emp_user_id",$r_ivd['sptm_req_by'],$conn);
		
		$ivd_dlvd_cnt = sumdlvddet($ivd_dlvm_nbr,$conn);
		
		if ($ivm_status_code == "10") { //เมื่อพิมพ์ Invoice ครั้งแรกระบบจะทำการเปลี่ยนสถานะของ Deliver จาก สร้างใบส่งของแล้วเป็นกำลังส่งสินค้า
			//UPDATE dlvm_mstr เปลี่ยน status ของ delivery ให้เป็นกำลังส่งสินค้า
			$sql_update_dlvm = "UPDATE dlvm_mstr SET ".		
				"dlvm_dlvs_step_code = '60'," .	
				"dlvm_step_by = '$user_login'," .	
				"dlvm_step_date = '$today'" .	
				" WHERE dlvm_nbr = '$ivd_dlvm_nbr'";		
			$resultupdate_dlvm = sqlsrv_query($conn,$sql_update_dlvm);
			
			//Move Qty From Delivery To Shipment
			$sql_dlvd = "SELECT * FROM dlvd_det where dlvd_dlvm_nbr = '$ivd_dlvm_nbr'";
			$result_dlvd = sqlsrv_query( $conn, $sql_dlvd );											
			while($r_dlvd = sqlsrv_fetch_array($result_dlvd, SQLSRV_FETCH_ASSOC)) {	
				$dlvd_id = $r_dlvd['dlvd_id'];
				$dlvd_sptd_id = $r_dlvd['dlvd_sptd_id'];
				$dlvd_qty  = $r_dlvd['dlvd_qty'];
				
				$sql_update_sptd_det = " UPDATE sptd_det SET " .
					" sptd_qty_delivery = sptd_qty_delivery - $dlvd_qty," .
					" sptd_qty_shipment = sptd_qty_shipment + $dlvd_qty" .
					" where sptd_id = '$dlvd_sptd_id'";
					
				$result_update_sptd_det = sqlsrv_query($conn, $sql_update_sptd_det);
			}
		}
		//
		$n++;
		$data = "<tr>" .
				"<td style='border: 1px dotted black;width:50px;text-align:center'>$n</td>".
				"<td style='border: 1px dotted black;width:100px;text-align:center'>$ivd_dlvm_nbr</td>".
				"<td style='border: 1px dotted black;width:100px;text-align:center'>$ivd_sptm_nbr</td>".
				"<td style='border: 1px dotted black;width:170px;text-align:center'>$ivd_sptm_req_by_name</td>".
				"<td style='border: 1px dotted black;width:200px;text-align:center'>$ivd_sptm_expect_receiver_name</td>".
				"<td style='border: 1px dotted black;text-align:center'>$ivd_dlvd_cnt</td>".
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
				
	$pdf->WriteHTML("</table>");
	$pdf->Output();
	if ($total_record > 0) {
		if ($ivm_status_code == "10") {
			$sql_update_ivm = "UPDATE ivm_mstr SET ".		
				"ivm_status_code = '20'," .
				"ivm_printed = '1'," .
				"ivm_print_cnt = '1'," .
				"ivm_print_by = '$user_login'," .
				"ivm_print_date = '$today'" .
				" WHERE ivm_nbr = '$ivm_nbr'";
			$resultupdate_ivm = sqlsrv_query($conn,$sql_update_ivm);
		}
		//Update Print Flag
		$sql = "UPDATE ivm_mstr SET ".				
			"ivm_print_cnt = ivm_print_cnt + 1," .
			"ivm_print_by = '$user_login'," .
			"ivm_print_date = '$today'" .
			" WHERE ivm_nbr = '$ivm_nbr'";
		$result = sqlsrv_query($conn,$sql);
	}
?> 