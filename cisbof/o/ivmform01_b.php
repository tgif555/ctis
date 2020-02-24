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
	
	//Detect Mail Server is availbale
	if (isservonline($smtp)) {$can_sendmail=true;}
	else { $can_sendmail=false; }

	$sql_ivm = "SELECT * from ivm_mstr where ivm_nbr = '$ivm_nbr'";
	$result_ivm = sqlsrv_query($conn, $sql_ivm);	
	$r_ivm = sqlsrv_fetch_array($result_ivm, SQLSRV_FETCH_ASSOC);		
	if ($r_ivm) {		
		$ivm_date = $r_ivm['ivm_date'];
		$ivm_wpm_nbr = html_quot($r_ivm['ivm_wpm_nbr']);
		$ivm_group_type  = $r_ivm['ivm_group_type'];
		$ivm_customer_number = $r_ivm['ivm_customer_number'];
		$ivm_customer_dummy = html_quot($r_ivm['ivm_customer_dummy']);
		$ivm_customer_type = $r_ivm['ivm_customer_type'];
		$ivm_customer_amphur = html_quot($r_ivm['ivm_customer_amphur']);
		$ivm_customer_province = html_quot($r_ivm['ivm_customer_province']);
		$ivm_transport_car_nbr = html_quot($r_ivm['ivm_transport_car_nbr']);
		$ivm_transport_tspm_code = html_quot($r_ivm['ivm_transport_tspm_code']);
		$ivm_transport_tspm_other = html_quot($r_ivm['ivm_transport_tspm_other']);
		if ($ivm_transport_tspm_code=="OTHER") {
			$ivm_transport_tspm_name = $ivm_transport_tspm_other;
		}
		else {
			$ivm_transport_tspm_name = findsqlval("tspm_mstr","tspm_name","tspm_code",$ivm_transport_tspm_code,$conn);
		}
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
				$ivm_customer_name = "[".$ivm_customer_number."] ". $ivm_customer_name;
			}
		}
		else {
			$ivm_customer_name = $ivm_customer_dummy;
		}
		
		$ivm_weight = sumdlvmweight($ivm_nbr,$conn);										
		$ivm_weight_diff = $ivm_weight - (int) $ivm_weight;
		if ($ivm_weight_diff>0) {$ivm_weight = number_format($ivm_weight,2);}
		else {$ivm_weight = number_format($ivm_weight,0);}
	}
	//ดึงข้อมูลวิธีการจัดส่ง

	$sql = "SELECT TOP 1 * from ivd_det WHERE ivd_ivm_nbr = '$ivm_nbr'";
	$result = sqlsrv_query($conn, $sql);	
	$r_ivd = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);		
	if ($r_ivd) {
		$ivd_sptm_nbr = $r_ivd['ivd_sptm_nbr'];
		
		$sptm_delivery_mth = findsqlval("sptm_mstr","sptm_delivery_mth","sptm_nbr",$ivd_sptm_nbr,$conn);
		$sptm_delivery_mth_name = findsqlval("delivery_mth","delivery_name","delivery_code",$sptm_delivery_mth,$conn);
		if ($ivm_group_type == "C") {
			$sptm_req_by = findsqlval("sptm_mstr","sptm_req_by","sptm_nbr",$ivd_sptm_nbr,$conn);
			$sptm_req_by_name = findsqlval("emp_mstr","emp_th_firstname+' '+emp_th_lastname","emp_user_id",$sptm_req_by,$conn);
		}
	}
	
	//
	/////////////////
	//CREATE PDF FILE
	$header =
		"<table width=100% cellpadding=5 cellspacing=5 style='border: 1px dotted black; border-collapse: separate;font-size:12pt'>" .
			"<tr>".
				"<td style='width:33%;font-size:12pt'><span><b>SCG Ceramics PCL.</b></span><br>
				<span style='font-size:8pt'>"."(วันที่ใบส่งของ " . dmytx($ivm_date).")</span></td>".
				"<td style='width:33%;text-align:center;font-size:12pt'>ใบส่งกระเบื้องตัวอย่าง<br><span style='font-size:8pt'>(B:วิธีการจัดส่ง)</span></td>".
				"<td style='text-align:right;'><span style='font-size:12pt'>$ivm_nbr</span><br>
				<span style=''><barcode code='$ivm_nbr' type='C39' size='0.8' height='0.8' style='margin-right: -10;'/></span>
				</td>".
			"</tr>".
		"</table>" .
		"<table width=100% style='border: 1px dotted black; border-collapse: collapse;font-size:8pt'>" .
			"<tr><td colspan=4 style='height:5px'></td></tr>" .
			"<tr>".
				"<td style='width:150px' align=right><b>วิธีการจัดส่ง:</b></td>".
				"<td>$sptm_delivery_mth_name</td>".
				"<td align=right></td>".
				"<td></td>".
			"</tr>" .
			"<tr>" .
				"<td align=right><b>บริษัทขนส่ง:</b></td>".
				"<td colspan=3>$ivm_transport_tspm_name</td>".
			"</tr>".
			"<tr>" .
				"<td align=right><b>ทะเบียนรถ:</b></td>".
				"<td>$ivm_transport_car_nbr</td>".
				"<td align=right><b>เบอร์ติดต่อ:</b></td>".
				"<td>$ivm_transport_driver_tel</td>".
			"</tr>".
			"<tr><td colspan=4 style='height:5px'></td></tr>" .
		"</table>" .
		"<table width=100% cellpadding=5 cellspacing=5 style='border-collapse: collapse;border: 1px dotted black; font-size:6pt'>" .
			"<tr>" .
				"<td style='border: 1px dotted black;width:35px;text-align:center'><b>ลำดับ</b></td>".
				"<td style='border: 1px dotted black;width:70px;text-align:center'><b>Package No</b></td>".
				"<td style='border: 1px dotted black;width:100px;text-align:center'><b>ชื่อลูกค้า</b></td>".
				"<td style='border: 1px dotted black;width:320px;text-align:center'><b>หมายเหตุการจัดส่ง</b></td>".
				"<td style='border: 1px dotted black;width:100px;text-align:center'><b>ชื่อผู้ขอเบิก</b></td>".
				"<td style='border: 1px dotted black;width:50px;text-align:center'><b>รายการ</b></td>".
				"<td style='border: 1px dotted black;text-align:center'><b>(KG)</b></td>".
			"</tr>".
		"</table>";
		
	$footer = "<div>
		<table width=100% cellpadding=5 cellspacing=5 style='border-collapse: collapse;border:1px dotted black; font-size:9pt'>
		<tr><td style='font-size:7pt'>ผู้พิมพ์: " . $user_fullname . ", ".date('d/m/Y h:s:i'). " [" . $ivm_print_cnt ."]</td><td width=30% align=right><span style='font-size:8pt'>** (น้ำหนักรวม: ".$ivm_weight." KG.) **</span></td></tr>".
		"<tr>
			
			<td width=70% valign=top style='border: 0px dotted black;'>
				<table style='border-collapse: separate; font-size:8pt'>
					<tr><td colspan=2><b><u>ข้อมูลการรับสินค้า:</u></b></td></tr>
					<tr><td colspan=2 style='height:10px'></td></tr>
					<tr>
						<td stype='width: 100px'>ผู้รับสินค้า:</td>
						<td>______________</td>
					
						<td colspan=2 style='height:3px'></td></tr>
					
						<td>วันที่:</td>
						<td>____/____/_____</td>
					</tr>
					<tr>
						<td><u>หมายเหตุ:</u></td>
						<td colspan=2 style='font-size:8pt'><br>__________________________</td>
					</tr>
				</table>
			</td>
			<td width=30% align=right valign=top style='border: 1px dotted white;'>
				<barcode code='".$app_url."sampletile/ivmrct.php?d=".encrypt($ivm_nbr, $key)."' type='QR' size='0.8' error='L' disableborder = '1'/>
			</td>
		</tr>
		
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
			40, // margin top
			30, // margin bottom
			2, // margin header
			2); // margin footer
	//End PreDefine
	
	
 
	$data = "";
		
	$pdf->WriteHTML("<table width=100% cellpadding=5 cellspacing=5 style='border-collapse: collapse;border: 1px dotted black; font-size:6pt'>");
										
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
		$ivd_packing_weight = $r_ivd['dlvm_packing_weight'];
		$ivd_sptm_expect_receiver_name = $r_ivd['sptm_expect_receiver_name'];
		$ivd_sptm_expect_receiver_tel = $r_ivd['sptm_expect_receiver_tel'];
		$ivd_sptm_req_by_name = findsqlval("emp_mstr","emp_th_firstname+' '+emp_th_lastname","emp_user_id",$r_ivd['sptm_req_by'],$conn);
		
		$ivd_customer_number = $r_ivd['sptm_customer_number'];
		$ivd_customer_dummy = $r_ivd['sptm_customer_dummy'];
		if($ivd_customer_number != "DUMMY") {
			$ivd_customer_name = findsqlval("customer","customer_name1", "customer_number", $ivd_customer_number,$conn);
			if ($ivd_customer_name != "") {
				$ivd_customer_name = $ivd_customer_name;
			}
		}
		else {
			$ivd_customer_name = '<font color=red>*</font>' .$ivd_customer_dummy;
		}
		$ivd_sptm_delivery_mth_desc = $r_ivd['sptm_delivery_mth_desc'];
		
		$ivd_dlvd_cnt = sumdlvddet($ivd_dlvm_nbr,$conn);
		
		if ($ivm_status_code == "10") { //เมื่อพิมพ์ Invoice ครั้งแรกระบบจะทำการเปลี่ยนสถานะของ Package จาก สร้างใบส่งของแล้วเป็นกำลังส่งสินค้า
			//UPDATE dlvm_mstr เปลี่ยน status ของ delivery ให้เป็นกำลังส่งสินค้า
			$sql_update_dlvm = "UPDATE dlvm_mstr SET ".		
				"dlvm_dlvs_step_code = '60'," .
				"dlvm_ivm_print_date = '$today'," .
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
			
			//เก็บประวัติการดำเนินการ
			$spta_f_step = "30";
			$spta_t_step = "30";
				
			$spta_text = "ดำเนินการส่งของ";
			$spta_remark = $ivd_dlvm_nbr."/".$ivd_ivm_nbr;
			$sptm_nbr = $ivd_sptm_nbr;
			$spta_id = $sptm_nbr.getnewsptaapprovalid($sptm_nbr,$conn);
				
			$sql = " INSERT INTO spta_approval (" . 
				" spta_id,spta_sptm_nbr,spta_f_step_code,spta_t_step_code,spta_text,spta_remark,spta_active,spta_create_by,spta_create_date)" .		
				" VALUES('$spta_id','$sptm_nbr','$spta_f_step','$spta_t_step','$spta_text','$spta_remark','1','$user_login','$today')";				
			$result = sqlsrv_query($conn, $sql);
		}
		//
		$n++;
		$data = "<tr>" .
				"<td style='border: 1px dotted black;width:35px;text-align:center'>$n</td>".
				"<td style='border: 1px dotted black;width:70px;text-align:center'>$ivd_dlvm_nbr<br>($ivd_sptm_nbr)</td>".
				"<td style='border: 1px dotted black;width:100px;'>$ivd_customer_name</td>".
				"<td style='border: 1px dotted black;width:320px;'>$ivd_sptm_delivery_mth_desc</td>".
				"<td style='border: 1px dotted black;width:100px;'>$ivd_sptm_req_by_name</td>".
				"<td style='border: 1px dotted black;width:50px;text-align:center'>$ivd_dlvd_cnt</td>".
				"<td style='border: 1px dotted black;text-align:center'>$ivd_packing_weight</td>".
				"</tr>";
		
		$pdf->WriteHTML($data);
	}
	for ($j=$n+1; $j <= 8; $j++) {
		$data = "<tr>" .
					"<td style='border: 1px dotted black;width:35px;text-align:center'>&nbsp;</td>".
					"<td style='border: 1px dotted black;width:70px;text-align:center'>&nbsp;<br>&nbsp;</td>".
					"<td style='border: 1px dotted black;width:100px;text-align:center'></td>".
					"<td style='border: 1px dotted black;width:320px;text-align:center'></td>".
					"<td style='border: 1px dotted black;width:100px;text-align:center'></td>".
					"<td style='border: 1px dotted black;width:50px;text-align:center'></td>".
					"<td style='border: 1px dotted black;text-align:center'></td>".
					"</tr>";
		$pdf->WriteHTML($data);
	}
	$pdf->WriteHTML("</table>");
	$pdf->WriteHTML($footer);
	//
	//
	//พิมพ์ส่วนที่ 2
	$pdf->WriteHTML("<table width=100%><tr><td align=right><span style='text-align:right;font-size:7pt'>**ส่วนของบริษัท**</td></tr></table><hr><span style='font-size:7pt'>**ส่วนของลูกค้า**</span>");
	$n=0;
	$pdf->WriteHTML($header);
	$pdf->WriteHTML("<table width=100% cellpadding=5 cellspacing=5 style='border-collapse: collapse;border: 1px dotted black; font-size:6pt'>");
	
	$sql_ivd = "SELECT * FROM ivd_det" .
		" INNER JOIN ivm_mstr ON ivm_nbr = ivd_ivm_nbr" .
		" INNER JOIN dlvm_mstr ON dlvm_nbr = ivd_dlvm_nbr " .
		" INNER JOIN sptm_mstr ON sptm_nbr = dlvm_sptm_nbr " .
		" WHERE ivd_ivm_nbr = '$ivm_nbr'";
			
	$result_ivd = sqlsrv_query( $conn, $sql_ivd);
	while($r_ivd = sqlsrv_fetch_array($result_ivd, SQLSRV_FETCH_ASSOC)) {	
		$ivd_dlvm_nbr = $r_ivd['ivd_dlvm_nbr'];
		$ivd_sptm_nbr = $r_ivd['ivd_sptm_nbr'];
		$ivd_packing_weight = $r_ivd['dlvm_packing_weight'];
		$ivd_sptm_expect_receiver_name = $r_ivd['sptm_expect_receiver_name'];
		$ivd_sptm_expect_receiver_tel = $r_ivd['sptm_expect_receiver_tel'];
		$ivd_sptm_req_by_name = findsqlval("emp_mstr","emp_th_firstname+' '+emp_th_lastname","emp_user_id",$r_ivd['sptm_req_by'],$conn);
		$ivd_sptm_create_by = $r_ivd['sptm_create_by'];
		
		$ivd_customer_number = $r_ivd['sptm_customer_number'];
		$ivd_customer_dummy = $r_ivd['sptm_customer_dummy'];
		if($ivd_customer_number != "DUMMY") {
			$ivd_customer_name = findsqlval("customer","customer_name1", "customer_number", $ivd_customer_number,$conn);
			if ($ivd_customer_name != "") {
				$ivd_customer_name = $ivd_customer_name;
			}
		}
		else {
			$ivd_customer_name = '<font color=red>*</font>' .$ivd_customer_dummy;
		}
		$ivd_sptm_delivery_mth_desc = $r_ivd['sptm_delivery_mth_desc'];
		
		$ivd_dlvd_cnt = sumdlvddet($ivd_dlvm_nbr,$conn);
		$n++;
		$data = "<tr>" .
				"<td style='border: 1px dotted black;width:35px;text-align:center'>$n</td>".
				"<td style='border: 1px dotted black;width:70px;text-align:center'>$ivd_dlvm_nbr<br>($ivd_sptm_nbr)</td>".
				"<td style='border: 1px dotted black;width:100px;'>$ivd_customer_name</td>".
				"<td style='border: 1px dotted black;width:320px;'>$ivd_sptm_delivery_mth_desc</td>".
				"<td style='border: 1px dotted black;width:100px;'>$ivd_sptm_req_by_name</td>".
				"<td style='border: 1px dotted black;width:50px;text-align:center'>$ivd_dlvd_cnt</td>".
				"<td style='border: 1px dotted black;text-align:center'>$ivd_packing_weight</td>".
				"</tr>";
		$pdf->WriteHTML($data);
		
		if ($can_sendmail) {
			//	ส่ง mail แจ้ง requester ว่าสินค้าเริ่มส่งแล้ว
			if ($ivm_status_code == "10") {
				$emp_inform_print_invoice = false;
				$sql_emp = "SELECT * from emp_mstr where emp_user_id = '$ivd_sptm_create_by'";
				$result_emp = sqlsrv_query($conn, $sql_emp);	
				$r_emp = sqlsrv_fetch_array($result_emp, SQLSRV_FETCH_ASSOC);		
				if ($r_emp) {		
					$emp_inform_print_invoice = $r_emp['emp_inform_print_invoice'];
					$ivd_sptm_create_by_email = $r_emp['emp_email_bus'];
					$ivd_sptm_create_by_name = $r_emp['emp_th_firstname'] . " " . $r_emp['emp_th_lastname'];
				}
				if ($emp_inform_print_invoice == 1) {
					$mail_from = "Sampletile Admin";
					$mail_from_email = "sampletile_admin@scg.com";
					$mail_to = $ivd_sptm_create_by_email;
					$mail_subject = "[เบิกตัวอย่าง] - จนท.จัดส่งได้เริ่มส่งสินค้าตาม Package หมายเลข $ivd_dlvm_nbr";
					$mail_message = "เรียน คุณ$ivd_sptm_create_by_name,<br>" .
						"จนท.จัดส่งได้เริ่มส่งสินค้าตาม Package หมายเลข $ivd_dlvm_nbr, เลขที่ใบเบิก " . $ivd_sptm_nbr . "<br>" .
						" ขอบคุณค่ะ<br>";
					$mail_message .= $mail_no_reply;
					
					if ($mail_to!="") {
						$sendstatus = mail_normal($mail_from,$mail_from_email,$mail_to,$mail_subject,$mail_message);	
					}
				}
			}
		}
			
	}
	for ($j=$n+1; $j <= 8; $j++) {
		$data = "<tr>" .
				"<td style='border: 1px dotted black;width:35px;text-align:center'>&nbsp;</td>".
				"<td style='border: 1px dotted black;width:70px;text-align:center'>&nbsp;<br>&nbsp;</td>".
				"<td style='border: 1px dotted black;width:100px;text-align:center'></td>".
				"<td style='border: 1px dotted black;width:320px;text-align:center'></td>".
				"<td style='border: 1px dotted black;width:100px;text-align:center'></td>".
				"<td style='border: 1px dotted black;width:50px;text-align:center'></td>".
				"<td style='border: 1px dotted black;text-align:center'></td>".
				"</tr>";
		$pdf->WriteHTML($data);
	}	
	$pdf->WriteHTML("</table>");
	$pdf->SetHTMLFooter($footer);
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