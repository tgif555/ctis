<?php
include("../_incs/chksession.php");	
include("../_incs/config.php"); 	
include("../_incs/funcServer.php");		
date_default_timezone_set('Asia/Bangkok');
define('PROJECT_ROOT', dirname(dirname(__FILE__)));	
set_time_limit(0);
if (!inlist($user_role,"CS") && !inlist($user_role,"SPT_ROOM") && !inlist($user_role,"ADMIN")) {
	echo "Not allow for run report";
	exit;
}

$curdate = date('Ymd_Hms');
$action = $_POST['action'];
//
$in_sptmrpt_nbr1 = $_POST['in_sptmrpt_nbr1'];
$in_sptmrpt_nbr2 = $_POST['in_sptmrpt_nbr2'];
$in_sptmrpt_req_date1 = ymd($_POST['in_sptmrpt_req_date1']);
$in_sptmrpt_req_date2 = ymd($_POST['in_sptmrpt_req_date2']);
if ($_POST['in_sptmrpt_apv_date1']!="") { 
	$in_sptmrpt_apv_date1 = ymdsql($_POST['in_sptmrpt_apv_date1']);
} else {
	$in_sptmrpt_apv_date1 = "";
}
if ($_POST['in_sptmrpt_apv_date2']!="") { 
	$in_sptmrpt_apv_date2 = ymdsql($_POST['in_sptmrpt_apv_date2']);
} else {
	$in_sptmrpt_apv_date2 = "";
}
$in_sptmrpt_step_code = $_POST['in_sptmrpt_step_code'];

$in_sptmrpt_shownpd = $_POST['in_sptmrpt_shownpd'];
if ($in_sptmrpt_shownpd == "on") {
	$in_sptmrpt_shownpd = "1";
} else {
	$in_sptmrpt_shownpd = "";
}
$in_sptmrpt_customer = $_POST['in_sptmrpt_customer'];
$in_sptmrpt_delivery_mth = $_POST['in_sptmrpt_delivery_mth'];
$in_sptmrpt_reason_code = $_POST['in_sptmrpt_reason_code'];

$criteria = "";
if ($in_sptmrpt_nbr1 != "") {
	if ($criteria != "") { $criteria = $criteria . " AND "; }
	$criteria = $criteria . " sptm_nbr >= '$in_sptmrpt_nbr1'";
}
if ($in_sptmrpt_nbr2 != "") {
	if ($criteria != "") { $criteria = $criteria . " AND "; }
	$criteria = $criteria . " sptm_nbr <= '$in_sptmrpt_nbr2'";
}
if ($in_sptmrpt_req_date1 != "") {
	if ($criteria != "") { $criteria = $criteria . " AND "; }
	$criteria = $criteria . " sptm_req_date >= '$in_sptmrpt_req_date1'";
}
if ($in_sptmrpt_req_date2 != "") {
	if ($criteria != "") { $criteria = $criteria . " AND "; }
	$criteria = $criteria . " sptm_req_date <= '$in_sptmrpt_req_date2'";
}
if ($in_sptmrpt_apv_date1 != "") {
	if ($criteria != "") { $criteria = $criteria . " AND "; }
	$criteria = $criteria . " sptm_approve_date >= '$in_sptmrpt_apv_date1'";
}
if ($in_sptmrpt_apv_date2 != "") {
	if ($criteria != "") { $criteria = $criteria . " AND "; }
	$criteria = $criteria . " sptm_approve_date <= '$in_sptmrpt_apv_date2'";
}
if ($in_sptmrpt_step_code != "") {
	if ($criteria != "") { $criteria = $criteria . " AND "; }
	$criteria = $criteria . " sptm_step_code = '$in_sptmrpt_step_code'";
}
if ($in_sptmrpt_shownpd != "") {
	if ($criteria != "") { $criteria = $criteria . " AND "; }
	$criteria = $criteria . " sptm_npd = '$in_sptmrpt_shownpd'";
}
if ($in_sptmrpt_customer != "") {
	if ($criteria != "") { $criteria = $criteria . " AND "; }
	$criteria = $criteria . " (customer_name1 like '%$in_sptmrpt_customer%' OR sptm_customer_dummy like '%$in_sptmrpt_customer%')";
}
if ($in_sptmrpt_delivery_mth != "") {
	if ($criteria != "") { $criteria = $criteria . " AND "; }
	$criteria = $criteria . " sptm_delivery_mth = '$in_sptmrpt_delivery_mth'";
}
if ($in_sptmrpt_delivery_mth != "") {
	if ($criteria != "") { $criteria = $criteria . " AND "; }
	$criteria = $criteria . " sptm_delivery_mth = '$in_sptmrpt_delivery_mth'";
}

if ($in_sptmrpt_reason_code != "") {
	if ($criteria != "") { $criteria = $criteria . " AND "; }
	$criteria = $criteria . " sptm_reason_code = '$in_sptmrpt_reason_code'";
}

if ($criteria != "") { $criteria = " AND " . $criteria; }

$allow_report = false;
if (!inlist($user_role,"AC_REPORT")) {
	$path = "../expense/expenseauthorize.php"; 
	//echo "<meta http-equiv=\"refresh\" content=\"0;URL=".$path."\" />";
}
else {
	$allow_report = true;
}
$allow_report = true;

if ($action == "REPORT" && $allow_report) {
	/** PHPExcel */
	require_once '../_libs/ExcelClass/PHPExcel.php';	
	$objPHPExcel = new PHPExcel(); // Create new PHPExcel object	
	$objPHPExcel->getProperties()->setCreator("Admin Expenses")
								 ->setLastModifiedBy("SmartXpense Admin")
								 ->setTitle("Office 2007 XLSX Test Document")
								 ->setSubject("Office 2007 XLSX Test Document")
								 ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
								 ->setKeywords("office 2007 openxml php")
								 ->setCategory("SAP P2P");	
	$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('A1', 'หมายเลขใบเบิก')
				->setCellValue('B1', 'ประเภทใบเบิก')
				->setCellValue('C1', 'อ้างอิงใบเบิก')
				->setCellValue('D1', 'ชื่อผู้ขอเบิก')
				->setCellValue('E1', 'ชื่อลูกค้า')
				->setCellValue('F1', 'อำเภอ')
				->setCellValue('G1', 'จังหวัด')
				->setCellValue('H1', 'วิธีการจัดสส่ง')
				->setCellValue('I1', 'วัตถุประสงค์ของการเบิก')
				->setCellValue('J1', 'NPD Brand')
				->setCellValue('K1', 'NPD Set No')
				->setCellValue('L1', 'วันที่ขอเบิก')
				->setCellValue('M1', 'วันที่ขอรับ')
				->setCellValue('N1', 'วันที่อนุมัติ')
				->setCellValue('O1', 'สถานะ')
				->setCellValue('P1', 'รหัสสินค้า')
				->setCellValue('Q1', 'ชื่อสินค้า-ไทย')
				->setCellValue('R1', 'ชื่อสินค้า-อังกฤษ')
				->setCellValue('S1', 'หน่วย')
				->setCellValue('T1', 'จำนวนที่สั่ง')
				->setCellValue('U1', 'จำนวนที่รับ')
				->setCellValue('V1', 'จำนวนที่ไม่รับ')
				->setCellValue('W1', 'ระหว่างส่ง')
				->setCellValue('X1', 'พร้อมส่ง')
				->setCellValue('Y1', 'ค้างส่ง')
				->setCellValue('Z1', 'Pick')
				->setCellValue('AA1', 'ไม่มีสินค้า')
				->setCellValue('AB1', 'หมายเหตุการจัดส่ง');
	$excel_row = 2;
	$sql_sptd = "SELECT * from sptd_det WITH (NOLOCK)".
		" INNER JOIN sptm_mstr WITH (NOLOCK) ON sptm_nbr = sptd_sptm_nbr".
		" INNER JOIN customer WITH (NOLOCK) ON customer_number = sptm_customer_number".
		" INNER JOIN material WITH (NOLOCK) ON mat_code = sptd_mat_code".
		" INNER JOIN unit_mstr ON unit_code = sptd_unit_code".
		" INNER JOIN step_mstr ON step_code = sptm_step_code".
		" INNER JOIN emp_mstr ON emp_user_id = sptm_create_by".
		" INNER JOIN delivery_mth ON delivery_code = sptm_delivery_mth".
		" INNER JOIN reason_mstr ON reason_code = sptm_reason_code".
		" WHERE sptm_is_delete = 0 and sptm_step_code IN ('30','990') $criteria";
		" ORDER BY sptm_nbr";
	//echo $sql_sptd;
	$result_sptd = sqlsrv_query( $conn, $sql_sptd);
	
	while($r_sptd = sqlsrv_fetch_array($result_sptd, SQLSRV_FETCH_ASSOC)) {
		$sptd_sptm_nbr = $r_sptd['sptm_nbr'];																	
		$sptd_cust_code = $r_sptd['sptm_customer_number'];
		$sptd_cust_dummy = html_quot($r_sptd['sptm_customer_dummy']);
		$sptd_cust_type = $r_sptd['sptm_cust_type'];
		$sptd_cust_amphur =  html_quot($r_sptd['sptm_customer_amphur']);
		$sptd_cust_province = html_quot($r_sptd['sptm_customer_province']);
		$sptd_reason_code = $r_sptd['sptm_reason_code'];
		$sptd_reason_name = $r_sptd['reason_name'];
		$sptd_expect_receipt_date = $r_sptd['sptm_expect_receipt_date'];
		$sptd_expect_receiver_name = html_quot($r_sptd['sptm_expect_receiver_name']);
		$sptd_expect_receiver_tel = html_quot($r_sptd['sptm_expect_receiver_tel']);
		$sptd_delivery_mth = html_quot($r_sptd['sptm_delivery_mth']);
		$sptd_delivery_mth_name = html_quot($r_sptd['delivery_name']);
		$sptd_delivery_mth_desc = html_quot($r_sptd['sptm_delivery_mth_desc']);
		$sptd_req_by = $r_sptd['sptm_req_by'];
	
		$sptd_req_by_name = $r_sptd['emp_th_firstname']. " " .$r_sptd['emp_th_lastname'];
		$sptd_req_by_sec = $r_sptd['emp_en_sec'];
		$sptd_req_date = $r_sptd['sptm_req_date'];
		$sptd_req_year = $r_sptd['sptm_req_year'];
		$sptd_req_month = $r_sptd['sptm_req_month'];
		$sptd_submit_date = $r_sptd['sptm_submit_date '];
		$sptd_approve_by = $r_sptd['sptm_approve_by'];
		//$sptd_approve_by_name = html_quot(findsqlval("emp_mstr","emp_th_firstname+' '+emp_th_lastname", "emp_user_id", $sptd_approve_by,$conn));
		$sptd_approve_date = $r_sptd['sptm_approve_date'];
		$sptd_approve_cmmt = html_quot($r_sptd['sptm_approve_cmmt']);
		$sptd_first_print_date = $r_sptd['sptm_first_print_date'];
		$sptd_npd = $r_sptd['sptm_npd'];
		$sptd_printed = $r_sptd['sptm_printed'];
		$sptd_copy_refer = $r_sptd['sptm_copy_refer'];
		
		$sptd_sptm_type = "";
		$sptm_npd_brand = "";
		$sptm_npd_setno = "";
		$sptm_npd_brand_name = "";
		if ($sptd_npd) {
			$sptd_sptm_type = "NPD";
			$sptm_npd_brand = html_quot($r_sptd['sptm_npd_brand']);
			$sptm_npd_brand_name = findsqlval("brand_mstr","brand_name","brand_code",$sptm_npd_brand,$conn);
			$sptm_npd_setno = substr($r_sptd['sptm_npd_setno'],strpos($r_sptd['sptm_npd_setno'],'@')+1,strlen($r_sptd['sptm_npd_setno']));
		}							
		$sptd_receive_complete_date = $r_sptd['sptm_receive_complete_date'];
															
		$sptd_step_code = $r_sptd['sptm_step_code'];
		$sptd_step_name = $r_sptd['step_name'];
													
		$sptd_step_by = $r_sptd['sptm_step_by'];
		//$sptd_step_by_name = findsqlval("emp_mstr","emp_th_firstname", "emp_user_id", $sptd_step_by,$conn);
		$sptd_step_date = $r_sptd['sptm_step_date'];
		$sptd_step_cmmt = html_quot($r_sptd['sptm_step_cmmt']);	
		$sptd_remark = html_quot($r_sptd['sptm_remark']);
		$sptd_force_close = $r_sptd['sptm_force_close'];

		$sptd_input_type = $r_sptd['sptm_input_type'];
		$sptd_whocanread = $r_sptd['sptm_whocanread'];
		$sptd_curprocessor = $r_sptd['sptm_curprocessor'];
		
		$sptd_create_by = $r_sptd['sptm_create_by'];	
		$sptd_create_by_name = $r_sptd['emp_th_firstname']. " " .$r_sptd['emp_th_lastname'];
		if($sptd_cust_code != "DUMMY") {
			$sptd_cust_name = $r_sptd['customer_name1'];
			if ($sptd_cust_name != "") {
				$sptd_cust_name = '['.$sptd_cust_code.'] ' . $sptd_cust_name;
			}
		}
		else {
			$sptd_cust_name = '[DUMMY] ' .$sptd_cust_dummy;
		}
		$sptd_mat_code = $r_sptd['sptd_mat_code'];
		$sptd_mat_th_name = $r_sptd['mat_th_name'];
		$sptd_mat_en_name = $r_sptd['mat_en_name'];
		if ($sptd_mat_code == "BC") {
			$sptd_mat_th_name = $r_sptd['sptd_remark'];
			$sptd_mat_en_name = "";
		}
		$sptd_unit_code = $r_sptd['sptd_unit_code'];
		$sptd_unit_name = $r_sptd['unit_name'];
		$sptd_qty_order = $r_sptd['sptd_qty_order'];
		$sptd_qty_received = $r_sptd['sptd_qty_received'];
		$sptd_qty_not_received = $r_sptd['sptd_qty_not_received'];
		$sptd_qty_shipment = $r_sptd['sptd_qty_shipment'];
		$sptd_qty_delivery = $r_sptd['sptd_qty_delivery'];
		$sptd_qty_packing = $r_sptd['sptd_qty_packing'];
		$sptd_qty_nogood = $r_sptd['sptd_qty_nogood'];
		$sptd_qty_pending = ($sptd_qty_order - $sptd_qty_received - $sptd_qty_not_received - $sptd_qty_shipment - $sptd_qty_delivery - $sptd_qty_packing - $qty_packing - $sptd_qty_nogood);
		
		$day_wait = "";
		if (inlist('30',$sptd_step_code)) {
			if ($sptd_approve_date != "") {
				$day_wait = day_diff(date_format($r_sptd['sptm_approve_date'],'Ymd'),date('Ymd')) . ' วัน';
			}
		}
		if (inlist('990',$sptd_step_code)) {
			if ($sptd_receive_complete_date != "") {
				$day_wait = day_diff(date_format($r_sptd['sptm_approve_date'],'Ymd'),date_format($r_sptd['sptm_receive_complete_date'],'Ymd')) . ' วัน';
			}
		}
		
		$objPHPExcel->getActiveSheet()->setCellValue('A' . $excel_row, $sptd_sptm_nbr,PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()->setCellValueExplicit('B'. $excel_row, $sptd_sptm_type,PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()->setCellValueExplicit('C'. $excel_row, $sptd_copy_refer,PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()->setCellValueExplicit('D'. $excel_row, $sptd_req_by_name,PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()->setCellValueExplicit('E'. $excel_row, $sptd_cust_name,PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()->setCellValueExplicit('F'. $excel_row, $sptd_cust_amphur,PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()->setCellValueExplicit('G'. $excel_row, $sptd_cust_province,PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()->setCellValueExplicit('H'. $excel_row, $sptd_delivery_mth_name,PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()->setCellValueExplicit('I'. $excel_row, $sptd_reason_name,PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()->setCellValueExplicit('J'. $excel_row, $sptm_npd_brand_name,PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()->setCellValueExplicit('K'. $excel_row, $sptm_npd_setno,PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()->setCellValueExplicit('L'. $excel_row, dmytx($sptd_req_date),PHPExcel_Cell_DataType::TYPE_STRING);		
		$objPHPExcel->getActiveSheet()->setCellValueExplicit('M'. $excel_row, dmytx($sptd_expect_receipt_date),PHPExcel_Cell_DataType::TYPE_STRING);	
		$objPHPExcel->getActiveSheet()->setCellValueExplicit('N'. $excel_row, date_format($sptd_approve_date,'d/m/Y'),PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()->setCellValueExplicit('O' . $excel_row, $sptd_step_name,PHPExcel_Cell_DataType::TYPE_STRING);	
		$objPHPExcel->getActiveSheet()->setCellValueExplicit('P' . $excel_row, $sptd_mat_code,PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()->setCellValueExplicit('Q' . $excel_row, $sptd_mat_th_name,PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()->setCellValueExplicit('R' . $excel_row, $sptd_mat_en_name,PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()->setCellValueExplicit('S' . $excel_row, $sptd_unit_name,PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()->setCellValueExplicit('T' . $excel_row, $sptd_qty_order,PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()->setCellValueExplicit('U' . $excel_row, $sptd_qty_received,PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()->setCellValueExplicit('V' . $excel_row, $sptd_qty_not_received,PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()->setCellValueExplicit('W' . $excel_row, $sptd_qty_shipment,PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()->setCellValueExplicit('X' . $excel_row, $sptd_qty_delivery,PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()->setCellValueExplicit('Y' . $excel_row, $sptd_qty_pending,PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()->setCellValueExplicit('Z' . $excel_row, $sptd_qty_packing,PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()->setCellValueExplicit('AA' . $excel_row, $sptd_qty_nogood,PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()->setCellValueExplicit('AB' . $excel_row, $sptd_delivery_mth_desc,PHPExcel_Cell_DataType::TYPE_STRING);
		$excel_row++;
	}
	$objPHPExcel->getActiveSheet()->setTitle('Sheet1');
	// Set active sheet index to the first sheet, so Excel opens this as the first sheet
	$objPHPExcel->setActiveSheetIndex(0);

	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');

	$savepath = PROJECT_ROOT . "\_filedownloads/";	
	$strfilename = "sptm_report_".$curdate."_".rand()."-".$in_sptmrpt_req_date1."-".$in_sptmrpt_req_date2.".xlsx";	
	$savefile = $savepath.$strfilename;
	$objWriter->save($savefile);	
	//-----------------------------------------------------------------------------------
	$r="1";
	$errortxt="";
	echo '{"res":"'.$r.'","err":"'.$errortxt.'","fileoutput":"'.$strfilename.'"}';
}
?>
