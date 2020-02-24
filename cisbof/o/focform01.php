<?php
	include("../_incs/showsaving.php");
	include("../_incs/chksession.php");
	include("../_incs/config.php"); 	
	include("../_incs/funcServer.php");	
	date_default_timezone_set('Asia/Bangkok');
	$today = date("Y-m-d H:i:s");  	
	$focm_nbr = decrypt($_REQUEST['focmnumber'], $key);
	$max_line = 20;
	$line_cnt = 0;
	$item_cnt = 0;
	$empty_date = "______/______/______";
	
	$sql_focm = "SELECT * from focm_mstr where focm_nbr = '$focm_nbr'";
	$result_focm = sqlsrv_query($conn, $sql_focm);	
	$r_focm = sqlsrv_fetch_array($result_focm, SQLSRV_FETCH_ASSOC);		
	if ($r_focm) {		
		$focm_nbr = $r_focm['focm_nbr'];
		$focm_dn_nbr = $r_focm['focm_dn_nbr'];
		$focm_status_code = $r_focm['focm_status_code'];
		$focm_status_by = $r_focm['focm_status_by'];
		$focm_status_date = $r_focm['focm_status_date'];
		$focm_printed = $r_focm['focm_printed'];
		$focm_qty = $r_focm['focm_qty'];
		$focm_create_by = $r_focm['focm_create_by'];
		$focm_create_by_name = findsqlval("emp_mstr","emp_th_firstname+' '+emp_th_lastname", "emp_user_id", $focm_create_by,$conn);
		$focm_create_date = dmydb($r_focm['focm_create_date'],'Y');
	}
	/////////////////
	//CREATE PDF FILE
	$header = 
		"<table width=100% cellpadding=5 cellspacing=5 style='border: 1px dotted black; border-collapse: separate;font-size:12pt'>" .
			"<tr><td style='text-align:center;height: 40px'><h4>เอกสารเพื่อนำไปเปิด SAP FOC หมายเลข: $focm_nbr</h4>วันที่ทำรายการมา" . $focm_create_date ."</td></tr>" .
		"</table>" .	
		"<table width=100% border=1 cellpadding=5 cellspacing=5 style='border-collapse: collapse; font-size:8pt'>" .
			"<tr>" .
				"<td style='border: 1px dotted black;width:40px;text-align:center'><b>ลำดับ</b></td>".
				"<td style='border: 1px dotted black;width:220px;text-align:center'><b>รหัสสินค้า</b></td>".
				"<td style='border: 1px dotted black;width:250px;text-align:center'><b>ชื่อสินค้า</b></td>".
				"<td style='border: 1px dotted black;width:50px;text-align:center'><b>จำนวน</b></td>".
				"<td style='border: 1px dotted black;width:50px;height:30px;text-align:center'><b>หน่วย</b></td>" .
				"<td style='border: 1px dotted black;text-align:center'>SAP QTY</b></td>".
				"<td style='border: 1px dotted black;width:50px;text-align:center'><b>หน่วย</b></td>" .
			"</tr>".
		"</table>";
	$footer = "<div>" .
			"<table cellpadding=0 cellspacing=0 style='border: 1px dotted black; border-collapse: separate;font-size:8pt'>" .
			"<tr>" .
				"<td style='border: 1px dotted black;width:250px;height:30px;text-align:center'>สถานะ</td>" .
				"<td style='border: 1px dotted black;width:150px;height:30px;text-align:center'>วันที่</td>" .
			"</tr>" .
			"<tr>" .
				"<td style='border: 1px black; border-style: dotted dotted dotted dotted; height:30px;'>&nbsp;[&nbsp;&nbsp;&nbsp;&nbsp;]&nbsp;&nbsp;ยังไม่ได้ทำ</td>" .
				"<td style='border: 1px black; border-style: dotted dotted dotted dotted; height:30px;' align=center>$empty_date</td>" .
			"</tr>" .
			"<tr>" .
				"<td style='border: 1px black; border-style: dotted dotted dotted dotted; height:30px;'>&nbsp;[&nbsp;&nbsp;&nbsp;&nbsp;]&nbsp;&nbsp;สร้าง(SO SAP)แล้ว</td>" .
				"<td style='border: 1px black; border-style: dotted dotted dotted dotted; height:30px;' align=center>$empty_date</td>" .
			"</tr>" .
			
			"<tr>" .
				"<td style='border: 1px black; border-style: dotted dotted dotted dotted; height:30px;'>&nbsp;[&nbsp;&nbsp;&nbsp;&nbsp;]&nbsp;&nbsp;ได้(DN)แล้ว DN No:___________________</td>" .
				"<td style='border: 1px black; border-style: dotted dotted dotted dotted; height:30px;' align=center>$empty_date</td>" .
			"</tr>" .
			
			"<tr>" .
				"<td style='border: 1px black; border-style: dotted dotted dotted dotted; height:30px;'>&nbsp;[&nbsp;&nbsp;&nbsp;&nbsp;]&nbsp;&nbsp;ส่งของไป(ห.ต.ย)แล้ว </td>" .
				"<td style='border: 1px black; border-style: dotted dotted dotted dotted; height:30px;' align=center>$empty_date</td>" .
			"</tr>" .
			"</table>".
			"</div>" .
			"<div>" .
			"<table width=100%  style='font-size: 8pt;'>" .
				"<tr>" .
					"<td>พิมพ์โดย: $user_fullname, วันที่พิมพ์: $today</td>" .
					"<td align=right>หน้า {PAGENO}/{nbpg}</td>" .
				"</tr>" .
			"</table>" .
			"</div>";
	require_once('../_libs/mpdf/mpdf.php');
	//ob_start();
	$pdf = new mPDF('th', 'A4', '0', 'THSaraban'); //การตั้งค่ากระดาษถ้าต้องการแนวตั้ง ก็ A4 เฉยๆครับ ถ้าต้องการแนวนอนเท่ากับ A4-L
	//Title
	$pdf->SetTitle('FOC Print Form');
	//PreDefine
	$pdf->SetHTMLHeader($header);
	//$pdf->SetWatermarkImage("sample.jpg");
	//$pdf->showWatermarkImage = true;
	$pdf->SetHTMLFooter($footer);
	$pdf->AddPage('', // L - landscape, P - portrait 
			'', '', '1', '0',
			10, // margin_left
			10, // margin right
			36, // margin top
			25, // margin bottom
			10, // margin header
			5); // margin footer
	//End PreDefine
	$data = "";
	$pdf->WriteHTML("<table width=100% border=1 cellpadding=5 cellspacing=5 style='border-collapse: collapse; font-size:8pt'>");
	$sql = "SELECT focd_mat_code,focd_unit_code,sum(focd_qty) 'sum_focd_qty' FROM focd_det" .
		" INNER JOIN material ON mat_code = focd_mat_code" .
		" INNER JOIN unit_mstr ON unit_code = focd_unit_code" .
		" where focd_focm_nbr = '$focm_nbr'" .
		" group by focd_mat_code,focd_unit_code".
		" order by focd_mat_code,focd_unit_code";
														
	$result = sqlsrv_query( $conn, $sql );											
	while($r_focd = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
		$line_cnt++;
		$item_cnt++;
		$focd_mat_code = $r_focd['focd_mat_code'];
		$focd_unit_code = $r_focd['focd_unit_code'];
		$focd_mat_name = findsqlval("material","mat_th_name","mat_code",$focd_mat_code,$conn);
		$focd_unit_name = findsqlval("unit_mstr","unit_name","unit_code",$focd_unit_code,$conn);
		$focd_qty = $r_focd['sum_focd_qty'];
		$focd_sptm_nbr = getsptmnbrlist($focm_nbr,$focd_mat_code,$focd_unit_code,$conn);
		$focd_mat_code = $focd_mat_code . chr(10).chr(13);
		$focd_mat_name = $focd_mat_name . chr(10).chr(13) . "<br>" .$focd_sptm_nbr;
		$data = "<tr>".
				"<td style='border: 1px dotted black;width:40px;text-align: center'>$item_cnt</td>".
				"<td style='border: 1px dotted black;width:220px;text-align:center'>" .
				"<barcode code='$focd_mat_code' type='C39' size='0.5' height='2'/>" .
				"<br>$focd_mat_code" .
				"</td>".
				"<td style='border: 1px dotted black;width:250px;'>$focd_mat_name</td>".
				"<td style='border: 1px dotted black;width:50px;text-align: center'>$focd_qty</td>".
				"<td style='border: 1px dotted black;width:50px;text-align: center'>$focd_unit_name</td>".
				"<td style='border: 1px dotted black;text-align: center'>&nbsp;</td>".
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
	$pdf->WriteHTML("</table></body></html>");
	$pdf->Output();
	
	//1. update status on focm_mstr
	
	$sql_focm = "UPDATE focm_mstr SET focm_printed = '1' WHERE focm_nbr = '$focm_nbr'";						
	$result = sqlsrv_query($conn, $sql_focm);
	// END PDF CREATE //
	////////////////////
	
	function getsptmnbrlist($focm_nbr,$focd_mat_code,$focd_unit_code,$conn) {
		$sptm_nbr_list = "";
		$sptm_cnt = 0;
		$sql = "SELECT focd_sptm_nbr FROM focd_det" .
		" INNER JOIN sptm_mstr ON sptm_nbr = focd_sptm_nbr" .
		" where focd_focm_nbr = '$focm_nbr' and " .
		" focd_mat_code = '$focd_mat_code' and " .
		" focd_unit_code = '$focd_unit_code'" .
		" group by focd_sptm_nbr".
		" order by focd_sptm_nbr";
												
		$result = sqlsrv_query( $conn, $sql );											
		while($r_focd = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
			$sptm_cnt++;
			if ($sptm_cnt <= 3) {
				if ($sptm_nbr_list != "") {$sptm_nbr_list .= ","; }
			}
			else {
				$sptm_nbr_list .= "<br>";
				$sptm_cnt = 0;
			}
			$sptm_nbr_list .= $r_focd['focd_sptm_nbr'];
			if ($r_focd['sptm_step_code'] == "990") {
				$sptm_nbr_list .= " (ปิดแล้ว)";
			}
			if ($r_focd['sptm_step_code'] == "880") {
				$sptm_nbr_list .= " (ยกเลิก)";
			}
		}
		return $sptm_nbr_list;
	}
?> 