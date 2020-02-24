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
$in_dlvmrpt_nbr1 = $_POST['in_dlvmrpt_nbr1'];
$in_dlvmrpt_nbr2 = $_POST['in_dlvmrpt_nbr2'];
$in_dlvmrpt_dlvs_step_code = $_POST['in_dlvmrpt_dlvs_step_code'];
$in_dlvmrpt_dlvm_transport_car_nbr = $_POST['in_dlvmrpt_dlvm_transport_car_nbr'];
$in_dlvmrpt_sptm_nbr1 = $_POST['in_dlvmrpt_sptm_nbr1'];
$in_dlvmrpt_sptm_nbr2 = $_POST['in_dlvmrpt_sptm_nbr2'];
$in_dlvmrpt_req_date1 = ymd($_POST['in_dlvmrpt_req_date1']);
$in_dlvmrpt_req_date2 = ymd($_POST['in_dlvmrpt_req_date2']);
if ($_POST['in_dlvmrpt_apv_date1']!="") { 
	$in_dlvmrpt_apv_date1 = ymdsql($_POST['in_dlvmrpt_apv_date1']);
} else {
	$in_dlvmrpt_apv_date1 = "";
}
if ($_POST['in_dlvmrpt_apv_date2']!="") { 
	$in_dlvmrpt_apv_date2 = ymdsql($_POST['in_dlvmrpt_apv_date2']);
} else {
	$in_dlvmrpt_apv_date2 = "";
}
$in_dlvmrpt_step_code = $_POST['in_dlvmrpt_step_code'];

$in_dlvmrpt_shownpd = $_POST['in_dlvmrpt_shownpd'];
if ($in_dlvmrpt_shownpd == "on") {
	$in_dlvmrpt_shownpd = "1";
} else {
	$in_dlvmrpt_shownpd = "";
}
$in_dlvmrpt_customer = $_POST['in_dlvmrpt_customer'];
$in_dlvmrpt_delivery_mth = $_POST['in_dlvmrpt_delivery_mth'];
$in_dlvmrpt_reason_code = $_POST['in_dlvmrpt_reason_code'];

$criteria = "";
if ($in_dlvmrpt_nbr1 != "") {
	if ($criteria != "") { $criteria = $criteria . " AND "; }
	$criteria = $criteria . " dlvm_nbr >= '$in_dlvmrpt_nbr1'";
}
if ($in_dlvmrpt_nbr2 != "") {
	if ($criteria != "") { $criteria = $criteria . " AND "; }
	$criteria = $criteria . " dlvm_nbr <= '$in_dlvmrpt_nbr2'";
}
if ($in_dlvmrpt_dlvs_step_code != "") {
	if ($criteria != "") { $criteria = $criteria . " AND "; }
	$criteria = $criteria . " dlvm_dlvs_step_code <= '$in_dlvmrpt_dlvs_step_code'";
}
if ($in_dlvmrpt_dlvm_transport_car_nbr != "") {
	if ($criteria != "") { $criteria = $criteria . " AND "; }
	$criteria = $criteria . " dlvm_transport_car_nbr <= '$in_dlvmrpt_dlvm_transport_car_nbr'";
}
if ($in_dlvmrpt_sptm_nbr1 != "") {
	if ($criteria != "") { $criteria = $criteria . " AND "; }
	$criteria = $criteria . " sptm_nbr >= '$in_dlvmrpt_sptm_nbr1'";
}
if ($in_dlvmrpt_sptm_nbr2 != "") {
	if ($criteria != "") { $criteria = $criteria . " AND "; }
	$criteria = $criteria . " sptm_nbr <= '$in_dlvmrpt_sptm_nbr2'";
}
if ($in_dlvmrpt_req_date1 != "") {
	if ($criteria != "") { $criteria = $criteria . " AND "; }
	$criteria = $criteria . " sptm_req_date >= '$in_dlvmrpt_req_date1'";
}
if ($in_dlvmrpt_req_date2 != "") {
	if ($criteria != "") { $criteria = $criteria . " AND "; }
	$criteria = $criteria . " sptm_req_date <= '$in_dlvmrpt_req_date2'";
}
if ($in_dlvmrpt_apv_date1 != "") {
	if ($criteria != "") { $criteria = $criteria . " AND "; }
	$criteria = $criteria . " sptm_approve_date >= '$in_dlvmrpt_apv_date1'";
}
if ($in_dlvmrpt_apv_date2 != "") {
	if ($criteria != "") { $criteria = $criteria . " AND "; }
	$criteria = $criteria . " sptm_approve_date <= '$in_dlvmrpt_apv_date2'";
}
if ($in_dlvmrpt_step_code != "") {
	if ($criteria != "") { $criteria = $criteria . " AND "; }
	$criteria = $criteria . " sptm_step_code = '$in_dlvmrpt_step_code'";
}
if ($in_dlvmrpt_shownpd != "") {
	if ($criteria != "") { $criteria = $criteria . " AND "; }
	$criteria = $criteria . " sptm_npd = '$in_dlvmrpt_shownpd'";
}
if ($in_dlvmrpt_customer != "") {
	if ($criteria != "") { $criteria = $criteria . " AND "; }
	$criteria = $criteria . " (customer_name1 like '%$in_dlvmrpt_customer%' OR sptm_customer_dummy like '%$in_dlvmrpt_customer%')";
}
if ($in_dlvmrpt_delivery_mth != "") {
	if ($criteria != "") { $criteria = $criteria . " AND "; }
	$criteria = $criteria . " sptm_delivery_mth = '$in_dlvmrpt_delivery_mth'";
}
if ($in_dlvmrpt_delivery_mth != "") {
	if ($criteria != "") { $criteria = $criteria . " AND "; }
	$criteria = $criteria . " sptm_delivery_mth = '$in_dlvmrpt_delivery_mth'";
}

if ($in_dlvmrpt_reason_code != "") {
	if ($criteria != "") { $criteria = $criteria . " AND "; }
	$criteria = $criteria . " sptm_reason_code = '$in_dlvmrpt_reason_code'";
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
				->setCellValue('A1', 'Package No')
				->setCellValue('B1', 'Package By')
				->setCellValue('C1', 'Package Date')
				->setCellValue('D1', 'Pacage Status')
				->setCellValue('E1', 'น้ำหนัก')
				->setCellValue('F1', 'ค่าใช้จ่าย')
				->setCellValue('G1', 'การขึ้นสินค้า')
				->setCellValue('H1', 'หมายเลขใบส่งของ')
				->setCellValue('I1', 'วันที่ส่งของ')
				->setCellValue('J1', 'วันที่รับของ')
				->setCellValue('K1', 'บริษัทขนส่ง')
				->setCellValue('L1', 'หมายเลขอ้างอิง')
				->setCellValue('M1', 'ทะเบียนรถ')
				->setCellValue('N1', 'จำนวนที่ส่ง')
				->setCellValue('O1', 'หมายเลขใบเบิก')
				->setCellValue('P1', 'ประเภทใบเบิก')
				->setCellValue('Q1', 'NPD Brand')
				->setCellValue('R1', 'NPD Set No')
				->setCellValue('S1', 'อ้างอิงใบเบิก')
				->setCellValue('T1', 'ชื่อผู้ขอเบิก')
				->setCellValue('U1', 'ชื่อลูกค้า')
				->setCellValue('V1', 'อำเภอ')
				->setCellValue('W1', 'จังหวัด')
				->setCellValue('X1', 'วิธีการจัดสส่ง')
				->setCellValue('Y1', 'วัตถุประสงค์ของการเบิก')
				->setCellValue('Z1', 'วันที่ขอเบิก')
				->setCellValue('AA1', 'วันที่ขอรับ')
				->setCellValue('AB1', 'วันที่อนุมัติ')
				->setCellValue('AC1', 'สถานะใบเบิก');
	$excel_row = 2;
	$sql_dlvd = "SELECT * from dlvm_mstr WITH (NOLOCK)".
		" INNER JOIN sptm_mstr WITH (NOLOCK) ON sptm_nbr = dlvm_sptm_nbr".
		" INNER JOIN customer WITH (NOLOCK) ON customer_number = sptm_customer_number".
		" INNER JOIN dlvs_mstr ON dlvs_step_code = dlvm_dlvs_step_code".
		" INNER JOIN step_mstr ON step_code = sptm_step_code".
		" INNER JOIN delivery_mth ON delivery_code = sptm_delivery_mth".
		" INNER JOIN reason_mstr ON reason_code = sptm_reason_code".
		" WHERE sptm_customer_number NOT IN ('NPD','NPD_NOCUST') and sptm_is_delete = 0 and sptm_step_code IN ('30','990') and dlvm_dlvs_step_code <> '80' $criteria";
		" ORDER BY dlvm_nbr";

	$result_dlvd = sqlsrv_query( $conn, $sql_dlvd);
	
	while($r_dlvd = sqlsrv_fetch_array($result_dlvd, SQLSRV_FETCH_ASSOC)) {
		$sptm_nbr = $r_dlvd['sptm_nbr'];																	
		$sptm_customer_number = $r_dlvd['sptm_customer_number'];
		$sptm_customer_dummy = html_quot($r_dlvd['sptm_customer_dummy']);
		$sptm_cust_type = $r_dlvd['sptm_cust_type'];
		$sptm_customer_amphur =  html_quot($r_dlvd['sptm_customer_amphur']);
		$sptm_customer_province = html_quot($r_dlvd['sptm_customer_province']);
		$sptm_reason_code = $r_dlvd['sptm_reason_code'];
		$sptm_reason_name = $r_dlvd['reason_name'];
		$sptm_expect_receipt_date = $r_dlvd['sptm_expect_receipt_date'];
		$sptm_expect_receiver_name = html_quot($r_dlvd['sptm_expect_receiver_name']);
		$sptm_expect_receiver_tel = html_quot($r_dlvd['sptm_expect_receiver_tel']);
		$sptm_delivery_mth = html_quot($r_dlvd['sptm_delivery_mth']);
		$sptm_delivery_mth_name = html_quot($r_dlvd['delivery_name']);
		$sptm_delivery_mth_desc = html_quot($r_dlvd['sptm_delivery_mth_desc']);
		$sptm_req_by = $r_dlvd['sptm_req_by'];
		$sptm_req_by_name = findsqlval("emp_mstr","emp_th_firstname+' '+emp_th_lastname","emp_user_id",$sptm_req_by,$conn);
		$sptm_req_by_sec = $r_dlvd['emp_en_sec'];
		$sptm_req_date = $r_dlvd['sptm_req_date'];
		$sptm_req_year = $r_dlvd['sptm_req_year'];
		$sptm_req_month = $r_dlvd['sptm_req_month'];
		$sptm_submit_date = $r_dlvd['sptm_submit_date '];
		$sptm_approve_by = $r_dlvd['sptm_approve_by'];
		//$sptm_approve_by_name = html_quot(findsqlval("emp_mstr","emp_th_firstname+' '+emp_th_lastname", "emp_user_id", $dlvd_approve_by,$conn));
		$sptm_approve_date = $r_dlvd['sptm_approve_date'];
		$sptm_approve_cmmt = html_quot($r_dlvd['sptm_approve_cmmt']);
		$sptm_first_print_date = $r_dlvd['sptm_first_print_date'];
		$sptm_npd = $r_dlvd['sptm_npd'];
		$sptm_printed = $r_dlvd['sptm_printed'];
		$sptm_copy_refer = $r_dlvd['sptm_copy_refer'];
		
		$sptm_type = "";
		$sptm_npd_brand = "";
		$sptm_npd_setno = "";
		$sptm_npd_brand_name = "";
		if ($sptm_npd) {
			$sptm_type = "NPD";
			$sptm_npd_brand = html_quot($r_dlvd['sptm_npd_brand']);
			$sptm_npd_brand_name = findsqlval("brand_mstr","brand_name","brand_code",$sptm_npd_brand,$conn);
			$sptm_npd_setno = substr($r_dlvd['sptm_npd_setno'],strpos($r_dlvd['sptm_npd_setno'],'@')+1,strlen($r_dlvd['sptm_npd_setno']));
		}
									
		$sptm_receive_complete_date = $r_dlvd['sptm_receive_complete_date'];
															
		$sptm_step_code = $r_dlvd['sptm_step_code'];
		$sptm_step_name = $r_dlvd['step_name'];

		$sptm_remark = html_quot($r_dlvd['sptm_remark']);
		$sptm_force_close = $r_dlvd['sptm_force_close'];

		$sptm_input_type = $r_dlvd['sptm_input_type'];

		if($sptm_customer_number != "DUMMY") {
			$sptm_cust_name = $r_dlvd['customer_name1'];
			if ($sptm_cust_name != "") {
				$sptm_cust_name = '['.$sptm_customer_number.'] ' . $sptm_cust_name;
			}
		}
		else {
			$sptm_cust_name = '[DUMMY] ' .$sptm_customer_dummy;
		}
		$dlvm_nbr = $r_dlvd['dlvm_nbr'];
		$dlvm_dlvs_step_code = $r_dlvd['dlvm_dlvs_step_code'];
		$dlvm_dlvs_step_name = $r_dlvd['dlvs_step_name'];
		$dlvm_packing_by = $r_dlvd['dlvm_packing_by'];
		$dlvm_packing_by_name = findsqlval("worker_mstr","worker_name","worker_code",$dlvm_packing_by,$conn);
		$dlvm_packing_weight = $r_dlvd['dlvm_packing_weight'];
		$dlvm_packing_date = $r_dlvd['dlvm_packing_date'];
		$dlvm_transport_amt = $r_dlvd['dlvm_transport_amt'];
		$dlvm_ivm_nbr = $r_dlvd['dlvm_ivm_nbr'];

		$dlvm_ivm_print_date = dmydb($r_dlvd['dlvm_ivm_print_date'],"Y");
		$dlvm_receive_date = dmytx($r_dlvd['dlvm_receive_date']);
		
		$dlvm_transport_tspm_code = $r_dlvd['dlvm_transport_tspm_code'];
		if ($dlvm_transport_tspm_code != "OTHER") {
			$dlvm_transport_tspm_name = findsqlval("tspm_mstr","tspm_name","tspm_code",$dlvm_transport_tspm_code,$conn);
		} else {
			$dlvm_transport_tspm_name = $r_dlvd['dlvm_transport_tspm_other'];
		}
		$dlvm_transport_ref_no = $r_dlvd['dlvm_transport_ref_no'];
		$dlvm_transport_car_nbr = $r_dlvd['dlvm_transport_car_nbr'];
		$dlvm_wh_status = $r_dlvd['dlvm_wh_status'];
		
		if (!is_null($dlvm_wh_status)) {
			if ($dlvm_wh_status) {
				$dlvm_wh_status_text = "ขึ้นสินค้าได้";
			} else {
				$dlvm_wh_status_text = "ขึ้นสินค้าไม่ได้";
			}
		} else { 
			$dlvm_wh_status_text = ""; 
		}
		$dlvm_qty = sumdlvddetqty($dlvm_nbr,$conn);
		
		$objPHPExcel->getActiveSheet()->setCellValue('A' . $excel_row, $dlvm_nbr,PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()->setCellValueExplicit('B'. $excel_row, $dlvm_packing_by_name,PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()->setCellValueExplicit('C'. $excel_row, dmytx($dlvm_packing_date),PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()->setCellValueExplicit('D'. $excel_row, $dlvm_dlvs_step_name,PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()->setCellValueExplicit('E'. $excel_row, $dlvm_packing_weight,PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()->setCellValueExplicit('F'. $excel_row, $dlvm_transport_amt,PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()->setCellValueExplicit('G'. $excel_row, $dlvm_wh_status_text,PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()->setCellValueExplicit('H'. $excel_row, $dlvm_ivm_nbr,PHPExcel_Cell_DataType::TYPE_STRING);
		
		$objPHPExcel->getActiveSheet()->setCellValueExplicit('I'. $excel_row, $dlvm_ivm_print_date,PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()->setCellValueExplicit('J'. $excel_row, $dlvm_receive_date,PHPExcel_Cell_DataType::TYPE_STRING);
		
		$objPHPExcel->getActiveSheet()->setCellValueExplicit('K'. $excel_row, $dlvm_transport_tspm_name,PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()->setCellValueExplicit('L'. $excel_row, $dlvm_transport_ref_no,PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()->setCellValueExplicit('M'. $excel_row, $dlvm_transport_car_nbr,PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()->setCellValueExplicit('N'. $excel_row, $dlvm_qty,PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()->setCellValueExplicit('O'. $excel_row, $sptm_nbr,PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()->setCellValueExplicit('P'. $excel_row, $sptm_type,PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()->setCellValueExplicit('Q'. $excel_row, $sptm_npd_brand_name,PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()->setCellValueExplicit('R'. $excel_row, $sptm_npd_setno,PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()->setCellValueExplicit('S'. $excel_row, $sptm_copy_refer,PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()->setCellValueExplicit('T'. $excel_row, $sptm_req_by_name,PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()->setCellValueExplicit('U'. $excel_row, $sptm_cust_name,PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()->setCellValueExplicit('V'. $excel_row, $sptm_customer_amphur,PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()->setCellValueExplicit('W'. $excel_row, $sptm_customer_province,PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()->setCellValueExplicit('X'. $excel_row, $sptm_delivery_mth_name,PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()->setCellValueExplicit('Y'. $excel_row, $sptm_reason_name,PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()->setCellValueExplicit('Z'. $excel_row, dmytx($sptm_req_date),PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()->setCellValueExplicit('AA'. $excel_row, dmytx($sptm_expect_receipt_date),PHPExcel_Cell_DataType::TYPE_STRING);	
		$objPHPExcel->getActiveSheet()->setCellValueExplicit('AB'. $excel_row, date_format($sptm_approve_date,'d/m/Y'),PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()->setCellValueExplicit('AC'. $excel_row, $sptm_step_name,PHPExcel_Cell_DataType::TYPE_STRING);
		$excel_row++;
	}
	$objPHPExcel->getActiveSheet()->setTitle('Sheet1');
	// Set active sheet index to the first sheet, so Excel opens this as the first sheet
	$objPHPExcel->setActiveSheetIndex(0);

	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');

	$savepath = PROJECT_ROOT . "\_filedownloads/";	
	$strfilename = "dlvm_report01_sum_".$curdate."_".rand()."-".$in_dlvmrpt_req_date1."-".$in_dlvmrpt_req_date2.".xlsx";	
	$savefile = $savepath.$strfilename;
	$objWriter->save($savefile);	
	//-----------------------------------------------------------------------------------
	$r="1";
	$errortxt="";
	echo '{"res":"'.$r.'","err":"'.$errortxt.'","fileoutput":"'.$strfilename.'"}';
}
?>
