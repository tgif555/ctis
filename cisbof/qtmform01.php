<?php
	include("../_incs/acunx_metaheader.php");
	include("../_incs/chksession.php");  
	include("../_incs/config.php");	
	include("../_incs/funcServer.php");	
	include("../_incs/acunx_cookie_var.php");
	include "../_incs/acunx_csrf_var.php";
	date_default_timezone_set('Asia/Bangkok');
	// if (($_SERVER['REQUEST_METHOD'] == 'POST')) {
		// if (!matchToken($csrf_key,$user_login)) {
			// echo "System detect CSRF attack!!";
			// exit;
		// }
	// }
	// else {
		// echo "Allow for POST Only";
		// exit;
	// }
	
	$today = date("Y-m-d H:i:s");  	
	$qtm_nbr = decrypt($_REQUEST['qtmnumber'], $key);
	$total_record = cntqtddet($qtm_nbr,$conn);
	$max_line = 20;
	$line_cnt = 0;
	$item_cnt = 0;
	
	$params = array($qtm_nbr);
	$sql_qtm = "SELECT * from qtm_mstr where qtm_nbr = ?";
	$result_qtm = sqlsrv_query($conn, $sql_qtm,$params);	
	$rec_qtm = sqlsrv_fetch_array($result_qtm, SQLSRV_FETCH_ASSOC);		
	if ($rec_qtm) {		
		$qtm_nbr = html_escape($rec_qtm['qtm_nbr']);
		$qtm_to = html_escape($rec_qtm['qtm_to']);
		$qtm_name = html_escape($rec_qtm['qtm_name']);
		$qtm_customer_number = html_escape($rec_qtm['qtm_customer_number']);
		$qtm_customer_name = html_escape($rec_qtm['qtm_customer_name']);
		if ($qtm_customer_number != "DUMMY") {
			$qtm_customer_name = html_escape($rec_qtm['custpj_name']);
		}
		else {
			$qtm_customer_name = $qtm_to;
		}
													
		$qtm_date = html_escape($rec_qtm['qtm_date']);
		$qtm_expire_date = html_escape($rec_qtm['qtm_expire_date']);
		$qtm_address = str_replace(chr(10),"<br>",html_escape($rec_qtm['qtm_address']));
		$qtm_amphur = html_escape($rec_qtm['qtm_amphur']);
		$qtm_province = html_escape($rec_qtm['qtm_province']);
		$qtm_zip_code = html_escape($rec_qtm['qtm_zip_code']);
		$qtm_lineid = html_escape($rec_qtm['qtm_lineid']);
		$qtm_email = html_escape($rec_qtm['qtm_email']);
		$qtm_tel_contact = html_escape($rec_qtm['qtm_tel_contact']);
		
		$qtm_detail = str_replace(chr(10),"<br>",html_escape($rec_qtm['qtm_detail']));
		$qtm_remark = str_replace(chr(10),"<br>",html_escape($rec_qtm['qtm_remark']));
		$qtm_disc = html_escape($rec_qtm['qtm_disc']);
		$qtm_disc_unit = html_escape($rec_qtm['qtm_disc_unit']);
		if ($qtm_disc_unit == "P") {
			$qtm_disc_unit_name = "%";
		}
		if ($qtm_disc_unit == "B") {
			$qtm_disc_unit_name = "บาท";
		}
		$qtm_customer_amt = html_escape($rec_qtm['qtm_customer_amt']);
		$qtm_contractor_amt = html_escape($rec_qtm['qtm_contractor_amt']);
		$qtm_auction_amt = html_escape($rec_qtm['qtm_auction_amt']);
		$qtm_sale_code = html_escape($rec_qtm['qtm_sale_code']);
		$qtm_pjm_nbr = html_escape($rec_qtm['qtm_pjm_nbr']);
		$qtm_ref_nbr = html_escape($rec_qtm['qtm_ref_nbr']);
		$qtm_revsion = html_escape($rec_qtm['qtm_revsion']);
		$qtm_tem_code = html_escape($rec_qtm['qtm_tem_code']);
		$qtm_tem_name = findsqlval("tem_mstr","tem_name","tem_code",$qtm_tem_code,$conn);
		$qtm_step_code = html_escape($rec_qtm['qtm_step_code']);
		$qtm_step_name = findsqlval("qtm_step_mstr","qtm_step_name", "qtm_step_code", $qtm_step_code,$conn);
		$qtm_step_by = html_escape($rec_qtm['qtm_step_by']);
		$qtm_step_date = $rec_qtm['qtm_step_date'];
		$qtm_step_cmmt = html_escape($rec_qtm['qtm_step_cmmt']);
		$qtm_whocanread = html_escape($rec_qtm['qtm_whocanread]']);
		$qtm_curprocessor = html_escape($rec_qtm['qtm_curprocessor']);
		$qtm_create_by = html_escape($rec_qtm['qtm_create_by']);	
		$qtm_create_by_name = findsqlval("emp_mstr","emp_th_firstname+' '+emp_th_lastname", "emp_user_id", $qtm_create_by,$conn);
		$qtm_prepaid_amt = html_escape($rec_qtm['qtm_prepaid_amt']);
		
		$qtm_first_print_date = $rec_qtm['qtm_first_print_date'];
		if (empty($qtm_first_print_date)) {
			$qtm_first_print_date = $today;
		} else {
			$qtm_first_print_date = date_format($qtm_first_print_date,'Y-m-d H:i:s');
		}
	}
	/////////////////
	//CREATE PDF FILE
	$header =
		"<table width=100% cellpadding=5 cellspacing=5 style='border: 1px dotted black; border-collapse: separate;font-size:9pt'>" .
			"<tr>".
				"<td colspan=6>".
					"<table>".
						"<tr><td><h4>QUOTATION#: $qtm_nbr</h4></td></tr>".
						"<tr><td>งานปรับปรุงบ้านตุณคมสัน ปทุมธานี</td></tr>".
						"<tr><td>Valid: 01/01/2020 - 15/01/2020</td>".
					"</table>".
				"</td>".
				"<td colspan=6 align=right>".
					"<img src='../_images/cotto-logo.png'>".
				"</td>".
			"</tr>".
		"</table>" .
		"<table width=100% cellpadding=5 cellspacing=5 style='border: 1px dotted black; border-collapse: separate;font-size:9pt'>" .
			"<tr>".
				"<td colspan=4>".
					"<table width=100%>".
						"<tr><td><b>Quotation From:</b></td></tr>".
						"<tr><td>SCG Ceramics Public Company Limited.</td></tr>".
						"<tr><td>61 Moo 1 Nongkhae Industrial Estate</td></tr>".
						"<tr><td>Kokyae, Nongkhae Saraburi 18230</td></tr>".
						"<tr><td>036-376100</td></tr>".
					"</table>".	
				"</td>" .
				"<td colspan=4>".
					"<table width=100%>".
						"<tr><td><b>Quotation To:</b></td></tr>".
						"<tr><td>คุณคมสัน ยุวการุณย์</td></tr>".
						"<tr><td>88/124 ม.6 คูบางหลวง อ.ลาดหลุมแก้ว</td></tr>".
						"<tr><td>จ.ปทุมธานี 12140</td></tr>".
						"<tr><td>089-6941534, email: komsun_yu@hotmail.com</td></tr>".
					"</table>".	
				"</td>" .
				"<td colspan=4 valign=top>".
					"<table width=100%>".
						"<tr><td><b>Payments:</b></td></tr>".
						"<tr><td>Method: เงินสด</td></tr>".
						"<tr><td>Term: ชำระ 4 งวด</td></tr>".
					"</table>".	
				"</td>" .
			"</tr>".
		"</table>" .
		"<table width=100% border=1 cellpadding=5 cellspacing=5 style='border-collapse: collapse; font-size:11pt'>" .
			"<tr>".
				"<td colspan=4 style='border: 1px dotted black; height:30px;text-align:center'><b></td>".
				"<td colspan=4 style='border: 1px dotted black; height:30px;text-align:center;background:#ECEEEA'><b>ราคากลางต่อหน่วยส่วนผู้รับเหมา</td>".
				"<td colspan=4 style='border: 1px dotted black; height:30px;text-align:center;background:#ECEEEA'><b>ราคาต่อหน่วยส่วนลูกค้า</td>".
			"</tr>".
			"<tr>" .
				"<td style='border: 1px dotted black; width:50px; height:20px;text-align:center'><b>ลำดับ</b></td>".
				"<td style='border: 1px dotted black; width:250px; height:20px;text-align:center'><b>รายการ</b></td>".	
				"<td style='border: 1px dotted black; width:80px; height:20px;text-align:center'><b>จำนวน</b></td>".
				"<td style='border: 1px dotted black; width:50px; height:20px; text-align:center'><b>หน่วย</b></td>" .
				"<td style='border: 1px dotted black; width:80px; height:20px;text-align:center'><b>ราคา</b></td>".
				"<td style='border: 1px dotted black; width:80px; height:20px;text-align:center'><b>ส่วนลด</b></td>".
				"<td style='border: 1px dotted black; width:80px; height:20px;text-align:center'><b>ต่อหน่วย</b></td>".
				"<td style='border: 1px dotted black; width:80px; height:20px;text-align:center'><b>สุทธิ</b></td>".
				"<td style='border: 1px dotted black; width:80px; height:20px;text-align:center'><b>ราคา</b></td>".
				"<td style='border: 1px dotted black; width:80px; height:20px;text-align:center'><b>ส่วนลด</b></td>".
				"<td style='border: 1px dotted black; width:80px; height:20px;text-align:center'><b>ต่อหน่วย</b></td>".
				"<td style='border: 1px dotted black; width:80px; height:20px;text-align:center'><b>สุทธิ</b></td>".
			"</tr>".
		"</table>";
	$footer = "<div style='text-align:center;font-size: 9pt;'>Page {PAGENO}/{nbpg}</div>";
	
	require_once('../_libs/mpdf/mpdf.php');
	//ob_start();
	$pdf = new mPDF('th', 'A4', '0', 'THSaraban'); //การตั้งค่ากระดาษถ้าต้องการแนวตั้ง ก็ A4 เฉยๆครับ ถ้าต้องการแนวนอนเท่ากับ A4-L
	//PreDefine
	$pdf->SetHTMLHeader($header);
	//$pdf->SetWatermarkImage("sample.jpg");
	//$pdf->showWatermarkImage = true;
	$pdf->SetHTMLFooter($footer);
	$pdf->AddPage('', // L - landscape, P - portrait 
			'', '', '1', '0',
			10, // margin_left
			10, // margin right
			73, // margin top
			20, // margin bottom
			10, // margin header
			0); // margin footer
	//End PreDefine
	$data = "";
	
	$pdf->WriteHTML("<table width=100% border=1 cellpadding=5 cellspacing=5 style='border-collapse: collapse; font-size:11pt'>");
	$params = array($qtm_nbr);
	$sql = "SELECT * FROM qtd_det" .
		" INNER JOIN mat_mstr ON mat_code = qtd_mat_code" .
		" INNER JOIN unit_mstr ON unit_code = qtd_unit_code" .
		" where qtd_qtm_nbr = ?";
	
	$result = sqlsrv_query( $conn, $sql, $params );											
	while($rec_qtd = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
		$qtd_id = html_escape($rec_qtd['qtd_id']);
		$qtd_qtm_nbr = html_escape($rec_qtd['qtd_qtm_nbr']);
		$qtd_mat_code = html_escape($rec_qtd['qtd_mat_code']);
		$qtd_mat_name = html_escape($rec_qtd['qtd_mat_name']);
		$qtd_qty = html_escape($rec_qtd['qtd_qty']);
		$qtd_unit_code = html_escape($rec_qtd['qtd_unit_code']);
		$qtd_unit_name = html_escape($rec_qtd['unit_name']);
		$qtd_customer_price = html_escape($rec_qtd['qtd_customer_price']);
		$qtd_customer_disc = html_escape($rec_qtd['qtd_customer_disc']);
		$qtd_customer_disc_unit = html_escape($rec_qtd['qtd_customer_disc_unit']);
		$qtd_contractor_price = html_escape($rec_qtd['qtd_contractor_price']);
		$qtd_contractor_disc = html_escape($rec_qtd['qtd_contractor_disc']);
		$qtd_contractor_disc_unit = html_escape($rec_qtd['qtd_contractor_disc_unit']);
		$qtd_remark = html_escape($rec_qtd['qtd_remark']);
		$qtd_contractor_auction_unit_amt = $rec_qtd['qtd_contractor_auction_unit_amt'];
		//Customer Cal
		$qtd_customer_amt = $qtd_customer_price;
		$qtd_customer_unit_amt = 0;
		$qtd_customer_disc_amt = 0;
		if ((double)$qtd_customer_disc > 0) {
			if ($qtd_customer_disc_unit == "P") {
				$qtd_customer_disc_amt = $qtd_customer_price * $qtd_customer_disc /100;
				$qtd_customer_unit_amt = $qtd_customer_price - $qtd_customer_disc_amt;
			}
			if ($qtd_customer_disc_unit == "B") {
				$qtd_customer_disc_amt = $qtd_customer_disc;
				$qtd_customer_unit_amt = $qtd_customer_amt - $qtd_customer_disc;
			}
		}
		else {
			$qtd_customer_unit_amt = $qtd_customer_amt;	
		}
			
		$qtd_customer_text_disc = "";
		if ((double)$qtd_customer_disc > 0) {
			if ($qtd_customer_disc_unit == "P") {
				$qtd_customer_text_disc = "<u>".number_fmt($qtd_customer_disc,2)."%"."</u><br><font color=red>".number_fmt($qtd_customer_disc_amt,2,",")."฿"."</font>";
			}
			if ($qtd_customer_disc_unit == "B") {
				$qtd_customer_text_disc = "<font color=red>".number_fmt($qtd_customer_disc)."฿"."</font>";
			}
		}
		$qtd_customer_amt = $qtd_qty * $qtd_customer_unit_amt;
			
		//Contractor Cal
		$qtd_contractor_amt = $qtd_contractor_price;
		$qtd_contractor_unit_amt = 0;
		$qtd_contractor_disc_amt = 0;
		if ((double)$qtd_contractor_disc > 0) {
			if ($qtd_contractor_disc_unit == "P") {
				$qtd_contractor_disc_amt = $qtd_contractor_price * $qtd_contractor_disc /100;
				$qtd_contractor_unit_amt = $qtd_contractor_price - $qtd_contractor_disc_amt;
			}
			if ($qtd_contractor_disc_unit == "B") {
				$qtd_contractor_disc_amt = $qtd_contractor_disc;
				$qtd_contractor_unit_amt = $qtd_contractor_amt - $qtd_contractor_disc;
			}
		}
		else {
			$qtd_contractor_unit_amt = $qtd_contractor_amt;	
		}
		
		$qtd_contractor_text_disc = "";
		if ((double)$qtd_contractor_disc > 0) {
			if ($qtd_contractor_disc_unit == "P") {
				$qtd_contractor_text_disc = "<u>".number_fmt($qtd_contractor_disc,2)."%"."</u><br><font color=red>".number_fmt($qtd_contractor_disc_amt,2,",")."฿"."</font>";
			}
			if ($qtd_contractor_disc_unit == "B") {
				$qtd_contractor_text_disc = "<font color=red>".number_fmt($qtd_contractor_disc)."฿</font>";
			}
		}
		$qtd_contractor_amt = $qtd_qty * $qtd_contractor_unit_amt;
		$qtd_contractor_auction_amt = $qtd_qty * $qtd_contractor_auction_unit_amt;
		
		$qtd_margin_amt = $qtd_customer_amt - $qtd_contractor_auction_amt;
		
		$qtd_qty_text = number_fmt($qtd_qty);
		$qtd_customer_price_text = number_fmt($qtd_customer_price);
		$qtd_customer_unit_amt_text = number_fmt($qtd_customer_unit_amt);
		$qtd_customer_amt_text = number_fmt($qtd_customer_amt);
		$qtd_contractor_price_text = number_fmt($qtd_contractor_price);
		$qtd_contractor_unit_amt_text = number_fmt($qtd_contractor_unit_amt);
		$qtd_contractor_amt_text = number_fmt($qtd_contractor_amt);
		$qtd_margin_amt_text = number_fmt($qtd_margin_amt);
			
		$qtm_customer_price_total = $qtm_customer_price_total + $qtd_customer_price;
		$qtm_customer_disc_amt_total = $qtm_customer_disc_amt_total + $qtd_customer_disc_amt;
		$qtm_customer_unit_amt_total = $qtm_customer_unit_amt_total + $qtd_customer_unit_amt;
		$qtm_customer_amt_total = $qtm_customer_amt_total + $qtd_customer_amt;
			
		$qtm_contractor_price_total = $qtm_contractor_price_total + $qtd_contractor_price;
		$qtm_contractor_disc_amt_total = $qtm_contractor_disc_amt_total + $qtd_contractor_disc_amt;
		$qtm_contractor_unit_amt_total = $qtm_contractor_unit_amt_total + $qtd_contractor_unit_amt;
		$qtm_contractor_amt_total = $qtm_contractor_amt_total + $qtd_contractor_amt;

		$line_cnt++;
		$item_cnt++;				
		$data = "<tr>".
			"<td style='border: 1px dotted black; width:50px; height:20px;text-align:center'>$item_cnt</td>".
			"<td style='border: 1px dotted black; width:250px; height:20px;'>$qtd_mat_name</td>".	
			"<td style='border: 1px dotted black; width:80px; height:20px;text-align:right'>$qtd_qty_text</td>".
			"<td style='border: 1px dotted black; width:50px; height:20px; text-align:center'>$qtd_unit_name</td>" .
			"<td style='border: 1px dotted black; width:80px; height:20px;text-align:right'>$qtd_contractor_price_text</td>".
			"<td style='border: 1px dotted black; width:80px; height:20px;text-align:right'>$qtd_contractor_text_disc</td>".
			"<td style='border: 1px dotted black; width:80px; height:20px;text-align:right'>$qtd_contractor_unit_amt_text</td>".
			"<td style='border: 1px dotted black; width:80px; height:20px;text-align:right'>$qtd_contractor_amt_text</td>".
			"<td style='border: 1px dotted black; width:80px; height:20px;text-align:right'>$qtd_customer_price_text</td>".
			"<td style='border: 1px dotted black; width:80px; height:20px;text-align:right'>$qtd_customer_text_disc</td>".
			"<td style='border: 1px dotted black; width:80px; height:20px;text-align:right'>$qtd_customer_unit_amt_text</td>".
			"<td style='border: 1px dotted black; width:80px; height:20px;text-align:right'>$qtd_customer_amt_text</td>".
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
	//Discount Cal
	$qtm_disc_amt = 0;
	if ((double)$qtm_disc > 0) {
		if ($qtm_disc_unit == "P") {
			$qtm_disc_amt = $qtm_customer_amt_total * $qtm_disc /100;
				$qtm_disc_unit_text = "%";
		}
		if ($qtm_disc_unit == "B") {
			$qtm_disc_amt = $qtm_disc;
			$qtm_disc_unit_text = "฿";
		}
		$qtm_disc_text = number_fmt($qtm_disc);
		$qtm_disc_amt_text = number_fmt($qtm_disc_amt);
	}
	$qtm_contractor_amt_total_text = number_fmt($qtm_contractor_amt_total);
	$qtm_customer_amt_total_text = number_fmt($qtm_customer_amt_total);
	$qtm_customer_pay_amt =  $qtm_customer_amt_total - $qtm_disc_amt;
	$qtm_customer_pay_amt_text =  number_fmt($qtm_customer_pay_amt);
	$qtm_margin = $qtm_customer_pay_amt - $qtm_contractor_amt_total;
	$qtm_margin_text = number_fmt($qtm_margin);
	$data = "<tr>".
			"<td colspan=7 style='border: 1px dotted black; height:20px;'></td>".	
			"<td style='border: 1px dotted black; width:80px; height:20px;text-align:right'><b>$qtm_contractor_amt_total_text</b></td>".
			"<td colspan=3 style='border: 1px dotted black; height:20px;'></td>".
			"<td style='border: 1px dotted black; width:80px; height:20px;text-align:right'><b>$qtm_customer_amt_total_text</b></td>".
			"</tr>".
			"<tr>".
			"<td colspan=9 style='border: none; height:20px;'></td>".
			"<td colspan=2 style='border: none;border-bottom: 2px double black;height:20px;text-align:right'>ส่วนลด $qtm_disc_text$qtm_disc_unit_text:</td>".
			"<td style='border: none;border-bottom: 2px double black; width:80px; height:20px;text-align:right;color:red'><b>$qtm_disc_amt_text</b></td>".
			"</tr>".
			"<tr>".
			"<td colspan=9 style='border: none; height:20px;'></td>".
			"<td colspan=2 style='border: none;border-bottom: 2px double black;height:20px;text-align:right'>ลูกค้าชำระสุทธิ:</td>".
			"<td style='border: none;border-bottom: 2px double black; width:80px; height:20px;text-align:right;color:blue'><b>$qtm_customer_pay_amt_text</b></td>".
			"</tr>".
			"<tr>".
			"<td colspan=9 style='border: none; height:20px;'></td>".	
			"<td colspan=2 style='border: none;border-top: 1px solid black;border-bottom: 2px double black; height:20px;text-align:right'>กำไรสุทธิ:</td>".
			"<td style='border: none;border-top: 1px solid black;border-bottom: 2px double black; width:80px; height:20px;text-align:right'><h3>$qtm_margin_text</h3></td>".
			"</tr>";
	$pdf->WriteHTML("</table>");
	$pdf->Output();
	// END PDF CREATE //
	////////////////////
	//Update Print Flag
	$params = array($qtm_nbr);
	$sql = "UPDATE qtm_mstr SET ".		
		"qtm_printed='1'," .			
		"qtm_first_print_date = '$qtm_first_print_date'," .
		"qtm_printed_cnt = qtm_printed_cnt + 1," .
		"qtm_last_print_date = '$today'," .
		"qtm_last_print_by = '$user_login'" .
		" WHERE qtm_nbr = ?";			
	$result = sqlsrv_query($conn,$sql,$params);		
?>