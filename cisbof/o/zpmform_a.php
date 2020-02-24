<?php
	include("../_incs/showsaving.php");
	include("../_incs/chksession.php");
	include("../_incs/config.php"); 	
	include("../_incs/funcServer.php");	
	date_default_timezone_set('Asia/Bangkok');
	
	$today = date("Y-m-d H:i:s"); 
	$print_date = date("d/m/Y H:i:s");
	$dlvm_nbr_list = $_POST['dlvm_nbr_list'];
	
	$dlvm_nbr_print = "";
	$dlvm_nbr_array = explode(",",$dlvm_nbr_list);
	$dlvm_nbr_count = sizeof($dlvm_nbr_array);
	for($i = 0; $i < $dlvm_nbr_count;$i++) {
		$dlvm_nbr = "'".$dlvm_nbr_array[$i]."'";
		if ($dlvm_nbr_print!="") {$dlvm_nbr_print .= ",";}
		$dlvm_nbr_print .= $dlvm_nbr;
	}
	$max_line = 20;
	
	$sptm_cust_amphur =  "";
	$sptm_cust_province = "";
	$sptm_cust_name = "";
	$sql_sptm = "SELECT * from dlvm_mstr" .
		" INNER JOIN sptm_mstr ON sptm_nbr = dlvm_sptm_nbr" .
		" WHERE dlvm_nbr = '$dlvm_nbr_array[0]'";
	
	$result_sptm = sqlsrv_query($conn, $sql_sptm);	
	$r_sptm = sqlsrv_fetch_array($result_sptm, SQLSRV_FETCH_ASSOC);		
	if ($r_sptm) {
		$sptm_cust_code = $r_sptm['sptm_customer_number'];
		$sptm_cust_dummy = html_quot($r_sptm['sptm_customer_dummy']);
		$sptm_cust_amphur =  html_quot($r_sptm['sptm_customer_amphur']);
		$sptm_cust_province = html_quot($r_sptm['sptm_customer_province']);
							
		if($sptm_cust_code != "DUMMY") {
			$sptm_cust_name = findsqlval("customer","customer_name1", "customer_number", $sptm_cust_code,$conn);
			if ($sptm_cust_name != "") {
				$sptm_cust_name = "[".$sptm_cust_code."] " . $sptm_cust_name;
			}
		}
		else {
			$sptm_cust_name = '<font color=red>*</font>' .$sptm_cust_dummy;
		}
	}													
	/////////////////
	//CREATE PDF FILE
	$header =
		"<table width=100% cellpadding=5 cellspacing=5 style='border: 1px dotted black; border-collapse: separate;font-size:12pt'>" .
			"<tr >".
				"<td style='width:250px'><b>SCG Ceramics PCL.</b><br><span style='font-size:9pt'>ใบจัดโซนกระเบื้องตัวอย่าง<br>(ใช้สำหรับจัดโซน)</span></td>".
				"<td style='width:250px;text-align:right'><span style='font-size:9pt'>**ใช้สำหรับจัดโซนกระเบื้องตัวอย่าง **<br>หน้าที่: {PAGENO}/{nbpg}<br>วันที่พิมพ์: $today ($user_fullname)</span></td>".
			"</tr>".
		"</table>" .
		"<table width=100% style='border: 1px dotted black; border-collapse: collapse;font-size:8pt'>" .
			"<tr><td colspan=4 style='height:5px'></td></tr>" .
			"<tr>".
				"<td style='width:150px' align=right><b>รหัสลูกค้า:</b></td>".
				"<td><span style='font-size:11pt'>$sptm_cust_name</span></td>".
			"</tr>" .
			"<tr>".
				"<td align=right><b>อำเภอ:</b></td>".
				"<td><span style='font-size:11pt'>$sptm_cust_amphur</span></td>".
				"<td align=right><b>จังหวัด:</b></td>".
				"<td><span style='font-size:11pt'>$sptm_cust_province</span></td>".
			"</tr>" .
			
			"<tr><td colspan=4 style='height:5px'></td></tr>" .
		"</table>" .
		
		"<table width=100% cellpadding=5 cellspacing=5 style='border-collapse: collapse;border: 1px dotted black; font-size:8pt'>" .
			"<tr>" .
				"<td style='border: 1px dotted black;width:50px;text-align:center'><b>ลำดับ</b></td>".
				"<td style='border: 1px dotted black;width:140px;text-align:center'><b>Package No</b></td>".
				"<td style='border: 1px dotted black;width:100px;text-align:center'><b>เลขที่ใบเบิก</b></td>".
				"<td style='border: 1px dotted black;width:150px;text-align:center'><b>ชื่อผู้ขอเบิก</b></td>".
				"<td style='border: 1px dotted black;width:150px;text-align:center'><b>ชื่อผู้รับสินค้า</b></td>".
				"<td style='border: 1px dotted black;width:100px;text-align:center'><b>เบอร์โทรผู้รับ</b></td>".
				"<td style='border: 1px dotted black;width:60px;text-align:center'><b>น้ำหนัก<br>(KG)</b></td>".
				"<td style='border: 1px dotted black;width:250px;text-align:center'><b>หหมายเหตุการจัดส่ง</b></td>".
			"</tr>".
		"</table>";
		
	$footer = "<div>
		<table width=100% cellpadding=5 cellspacing=5 style='border-collapse: collapse;border: 1px dotted black; font-size:10pt'>
		<tr>
			<td width=35% valign=top style='border: 1px dotted black;'>
				<table style='border-collapse: separate; font-size:9pt'>
					<tr><td colspan=2><b><u>ข้อมูลการจัดโซน:</u></b></td></tr>
					<tr><td colspan=2 style='height:10px'></td></tr>
					<tr>
						<td style='width: 70px'>บริษัทขนส่ง:</td>
						<td>_________________________</td>
						<td>ทะเบียนรถ:</td>
						<td>_________________________</td>
						<td>เบอร์โทร:</td>
						<td>_________________________</td>
					</tr>
				</table>
			</td>
		</tr>
		</table>
	</div>";
	
			  
	require_once('../_libs/mpdf/mpdf.php');
	//ob_start();
	$pdf = new mPDF('th', 'A4', '0', 'THSaraban'); //การตั้งค่ากระดาษถ้าต้องการแนวตั้ง ก็ A4 เฉยๆครับ ถ้าต้องการแนวนอนเท่ากับ A4-L
	$pdf->SetTitle('ใบจัดโซน');
	//PreDefine
	$pdf->SetHTMLHeader($header);
	//$pdf->SetWatermarkImage("sample.jpg");
	//$pdf->showWatermarkImage = true;
	//$pdf->SetHTMLFooter("<table width=100% style='font-size:8pt'><tr><td align=center><barcode code='$dlvm_nbr' type='C39' size='1.2' height='1'/><br>$dlvm_nbr</td></tr></table>");
	$pdf->AddPage('', // L - landscape, P - portrait 
			'', '', '1', '0',
			10, // margin_left
			10, // margin right
			49, // margin top
			20, // margin bottom
			10, // margin header
			5); // margin footer
	//End PreDefine
	$data = "";
		
	$pdf->WriteHTML("<table width=100% cellpadding=5 cellspacing=5 style='border-collapse: collapse;border: 1px dotted black; font-size:8pt'>");
										
	$n = 0;
	$total_weight = 0;	
	$sql_dlvm = "SELECT * FROM dlvm_mstr WITH (NOLOCK)" .
		" INNER JOIN sptm_mstr ON sptm_nbr = dlvm_sptm_nbr" .
		" WHERE dlvm_nbr in ($dlvm_nbr_print) and sptm_customer_number != 'NPD_NOCUST'";	
	$result_dlvm = sqlsrv_query( $conn, $sql_dlvm);
	while($r_dlvm = sqlsrv_fetch_array($result_dlvm, SQLSRV_FETCH_ASSOC)) {	
		$dlvm_nbr = $r_dlvm['dlvm_nbr'];
		$dlvm_sptm_nbr = $r_dlvm['dlvm_sptm_nbr'];
		$dlvm_packing_weight = $r_dlvm['dlvm_packing_weight'];
		$dlvm_sptm_expect_receiver_name = $r_dlvm['sptm_expect_receiver_name'];
		$dlvm_sptm_expect_receiver_tel = $r_dlvm['sptm_expect_receiver_tel'];
		$dlvm_sptm_delivery_mth_desc = $r_dlvm['sptm_delivery_mth_desc'];
		$dlvm_sptm_req_by = $r_dlvm['sptm_req_by'];
		$dlvm_sptm_req_by_name = findsqlval("emp_mstr","emp_th_firstname+' '+emp_th_lastname","emp_user_id",$dlvm_sptm_req_by,$conn);
		$dlvm_zone_printed = $r_dlvm['dlvm_zone_printed'];
		
		$sptm_npd_brand = html_quot($r_dlvm['sptm_npd_brand']);
		$sptm_npd_brand_name = findsqlval("brand_mstr","brand_name","brand_code",$sptm_npd_brand,$conn);
		$sptm_npd_setno = substr($r_dlvm['sptm_npd_setno'],strpos($r_dlvm['sptm_npd_setno'],'@')+1,strlen($r_dlvm['sptm_npd_setno']));
		
		$dlvm_packing_location = $r_dlvm['dlvm_packing_location'];
		if ($dlvm_packing_location != "") {
			$dlvm_packing_location = "<br><span style='color:blue'>วางไว้ที่ " . $dlvm_packing_location."</span>";
		}
		
		$total_weight = $total_weight + $dlvm_packing_weight;
		
		$dlvm_packing_weight_diff = $dlvm_packing_weight - (int) $dlvm_packing_weight;
		if ($dlvm_packing_weight_diff>0) {$dlvm_packing_weight = number_format($dlvm_packing_weight,2);}
		else {$dlvm_packing_weight = number_format($dlvm_packing_weight,0);}
		
		$sptm_npd = $r_dlvm['sptm_npd'];
		$sptm_npd_text = "";
		if ($sptm_npd) {
			$sptm_npd_text = "<br><span style='color:red;font-size:7pt'>NPD: ".$sptm_npd_brand_name."<br>Lot: ".$sptm_npd_setno;
		}
		else {
			$sptm_npd_text = "";
		}
		
		//UPDATE dlvm_mstr เปลี่ยน status ของ Package zone print
		if (!$dlvm_zone_printed) {
			$sql_update_dlvm = "UPDATE dlvm_mstr SET ".		
				"dlvm_zone_printed = '1'," .	
				"dlvm_zone_print_by = '$user_login'," .	
				"dlvm_zone_print_date = '$today'" .	
				" WHERE dlvm_nbr = '$dlvm_nbr'";		
			$resultupdate_dlvm = sqlsrv_query($conn,$sql_update_dlvm);
		}	
		//
		$n++;
		$data = "<tr>" .
				"<td style='border: 1px dotted black;width:50px;text-align:center'>$n</td>".
				"<td style='border: 1px dotted black;width:140px;'>$dlvm_nbr $sptm_npd_text</td>".
				"<td style='border: 1px dotted black;width:100px;text-align:center'>$dlvm_sptm_nbr</td>".
				"<td style='border: 1px dotted black;width:150px;text-align:center'>$dlvm_sptm_req_by_name  $dlvm_packing_location</td>".
				"<td style='border: 1px dotted black;width:150px;text-align:center'>$dlvm_sptm_expect_receiver_name</td>".
				"<td style='border: 1px dotted black;width:100px;text-align:center'>$dlvm_sptm_expect_receiver_tel</td>".
				"<td style='border: 1px dotted black;width:60px;text-align:center'>$dlvm_packing_weight</td>".
				"<td style='border: 1px dotted black;width:250px;'>".str_replace("\n","<br />",$dlvm_sptm_delivery_mth_desc)."</td>".
				"</tr>";
		if ($n == $dlvm_nbr_count) {
			$total_weight_diff = $total_weight - (int) $total_weight;
			if ($total_weight_diff>0) {$total_weight = number_format($total_weight,2);}
			else {$total_weight = number_format($total_weight,0);}
		
			$data .= "<tr>" .
				"<td colspan=6></td>".
				"<td style='border: 1px dotted black;width:60px;text-align:center'>$total_weight</td>".
				"<td></td>".
				"</tr>";
		}
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
	
?> 