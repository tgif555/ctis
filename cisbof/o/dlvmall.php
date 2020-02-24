<?php 
include("../_incs/chksession.php");  
include("../_incs/config.php");	
include("../_incs/funcServer.php");	

set_time_limit(0);
$curdate = date('Ymd');
$action = mssql_escape($_REQUEST['action']);
$activeid = decrypt(mssql_escape($_REQUEST['activeid']), $key);

$in_dlvm_tmpsubmit = mssql_escape($_POST["in_dlvm_tmpsubmit"]);
$in_dlvm_nbr = mssql_escape($_POST["in_dlvm_nbr"]);
$in_dlvm_sptm_nbr = mssql_escape($_POST["in_dlvm_sptm_nbr"]);
$in_dlvm_emp_name = mssql_escape($_POST["in_dlvm_emp_name"]);
$in_dlvm_customer = mssql_escape($_POST["in_dlvm_customer"]);
$in_dlvm_step_code = mssql_escape($_POST["in_dlvm_step_code"]);
$in_dlvm_notshow = $_POST["in_dlvm_notshow"];
$in_dlvm_shownpd = $_POST["in_dlvm_shownpd"];
$in_dlvm_select = mssql_escape($_POST["in_dlvm_select"]);
$in_dlvm_zone_printed = $_POST["in_dlvm_zone_printed"];
$in_dlvm_packing_by = $_POST["in_dlvm_packing_by"];


If ($in_dlvm_tmpsubmit == "") {
	$in_dlvm_tmpsubmit = $_COOKIE['in_dlvm_tmpsubmit'];	
	$in_dlvm_nbr = $_COOKIE['in_dlvm_nbr'];
	$in_dlvm_sptm_nbr = $_COOKIE['in_dlvm_sptm_nbr'];
	$in_dlvm_emp_name = $_COOKIE['in_dlvm_em_name'];
	$in_dlvm_customer = $_COOKIE['in_dlvm_customer'];
	$in_dlvm_step_code = $_COOKIE['in_dlvm_step_code'];
	$in_dlvm_notshow = $_COOKIE['in_dlvm_notshow'];
	$in_dlvm_shownpd = $_COOKIE['in_dlvm_shownpd'];
	$in_dlvm_select = $_COOKIE['in_dlvm_select'];
	$in_dlvm_zone_printed = $_COOKIE['in_dlvm_zone_printed'];
	$in_dlvm_packing_by = $_COOKIE['in_dlvm_packing_by'];
}
else {		
	setcookie("in_dlvm_tmpsubmit","",0);
	setcookie("in_dlvm_nbr","",0);
	setcookie("in_dlvm_sptm_nbr","",0);
	setcookie("in_dlvm_customer","",0);
	setcookie("in_dlvm_emp_name","",0);
	setcookie("in_dlvm_step_code","",0);
	setcookie("in_dlvm_notshow","",0);
	setcookie("in_dlvm_shownpd","",0);
	setcookie("in_dlvm_select","",0);
	setcookie("in_dlvm_zone_printed","",0);
	setcookie("in_dlvm_packing_by","",0);
}
//ใช้เป็นตัวกำหนดค่า default ในการ login ครั้งแรก
if ($user_dlv_first_into == "1") { 
	$in_dlvm_select = "1"; 
	$in_dlvm_notshow = "on";
}
setcookie("spt_dlv_first_into", "0",0, "/");	
//
if ($in_dlvm_nbr != "") {
	if ($criteria != "") { $criteria = $criteria . " AND "; }
	$criteria = $criteria . " dlvm_nbr like '%$in_dlvm_nbr%'";
}
setcookie("in_dlvm_nbr", $in_dlvm_nbr,0);
//
if ($in_dlvm_sptm_nbr != "") {
	if ($criteria != "") { $criteria = $criteria . " AND "; }
	$criteria = $criteria . " ((sptm_nbr like '%$in_dlvm_sptm_nbr%') OR (sptm_copy_refer like '%$in_dlvm_sptm_nbr%'))";
}
setcookie("in_dlvm_sptm_nbr", $in_dlvm_sptm_nbr,0);
//
if ($in_dlvm_customer != "") {
	if ($criteria != "") { $criteria = $criteria . " AND "; }
	$criteria = $criteria . " (customer_name1 like '%$in_dlvm_customer%' OR sptm_customer_dummy like '%$in_dlvm_customer%' OR sptm_customer_number like '%$in_dlvm_customer%')";
}
setcookie("in_dlvm_customer", $in_dlvm_customer,0);	
//
if ($in_dlvm_select != "") {
	$dlvm_curprocessor_role_access = "";
	$dlvm_curprocessor_role_array = explode(",",$user_role);
	for ($c=0;$c<count($dlvm_curprocessor_role_array);$c++) {														
		if ($dlvm_curprocessor_role_access != "") { $dlvm_curprocessor_role_access = $dlvm_curprocessor_role_access . " OR "; }
		$dlvm_curprocessor_role_access = $dlvm_curprocessor_role_access . "(dlvm_curprocessor like ". "'%" . $dlvm_curprocessor_role_array[$c]."%')";														
	}
	//Who can Read
	$dlvm_whocanread_role_access = "";
	$dlvm_whocanread_role_array = explode(",",$user_role);																																							
	for ($c=0;$c<count($dlvm_whocanread_role_array);$c++) {														
		if ($dlvm_whocanread_role_access != "") { $dlvm_whocanread_role_access = $dlvm_whocanread_role_access . " OR "; }
		$dlvm_whocanread_role_access = $dlvm_whocanread_role_access . "(dlvm_whocanread like ". "'%" . $dlvm_whocanread_role_array[$c]."%')";														
	}
	
	if ($criteria != "") { $criteria = $criteria . " AND "; }
	switch ($in_dlvm_select) {
		case "1" : //งานที่รอคุณดำเนินการ				
			$criteria = $criteria . " ((dlvm_curprocessor like '%$user_login%') OR $dlvm_curprocessor_role_access)";
			break;
		case "2" :				
			$criteria = $criteria . " ((dlvm_whocanread like '%$user_login%') OR $dlvm_whocanread_role_access)";
			break;		
	}				
}
setcookie("in_dlvm_select", $in_dlvm_select,0);
//
if ($dlvm_emp_name != "") {
	if ($criteria != "") { $criteria = $criteria . " AND "; }
	$criteria = $criteria . " emp_th_firstname like '%$in_dlvm_emp_name%'";
}
setcookie("in_dlvm_emp_name", $in_dlvm_emp_name,0);
//
if ($in_dlvm_packing_by != "") {
	if ($criteria != "") { $criteria = $criteria . " AND "; }
	$criteria = $criteria . " (dlvm_packing_by = '$in_dlvm_packing_by')";
}
setcookie("in_dlvm_packing_by", $in_dlvm_packing_by,0);	
//
if ($in_dlvm_notshow != "") {
	if ($criteria != "") { $criteria = $criteria . " AND "; }
	$criteria = $criteria . " dlvm_dlvs_step_code <> '90' and dlvm_dlvs_step_code <> '80'";	
}
setcookie("in_dlvm_notshow", $in_dlvm_notshow,0);
//
if ($in_dlvm_shownpd != "") {
	if ($criteria != "") { $criteria = $criteria . " AND "; }
	$criteria = $criteria . " sptm_npd = '1'";	
}
setcookie("in_dlvm_shownpd", $in_dlvm_shownpd,0);
//
if ($in_dlvm_zone_printed != "") {
	if ($criteria != "") { $criteria = $criteria . " AND "; }
	$criteria = $criteria . " dlvm_zone_printed = '1'";	
}
setcookie("in_dlvm_zone_printed", $in_dlvm_zone_printed,0);
//
if ($in_dlvm_step_code != "") {
	if ($criteria != "") { $criteria = $criteria . " AND "; }
	$criteria = $criteria . " dlvm_dlvs_step_code = '$in_dlvm_step_code'";
}
setcookie("in_dlvm_step_code", $in_dlvm_step_code,0);	
//
if ($criteria != "") { 
	$criteria = " WHERE sptm_customer_number <> 'NPD_NOCUST' AND " . $criteria; 
} else {
	$criteria = " WHERE sptm_customer_number <> 'NPD_NOCUST'";
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8"> 
    <title><?php echo TITLE; ?></title>
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
	<link href="../_images/smartxpense.ico" rel="shortcut icon" />
	<link href="../_libs/css/_webstyle.css" type="text/css" rel="stylesheet">
    <link href="../_libs/css/bootstrap.css" rel="stylesheet">
    <link href="../_libs/css/bootstrap-responsive.css" rel="stylesheet">
	<link href="../_libs/datepicker/jquery-ui.css" rel="stylesheet" media="all" type="text/css" />
	<link href="../_libs/datepicker/jquery-ui-timepicker-addon.css" rel="stylesheet" media="all" type="text/css" />
	
	<link href="../_libs/css/sptm.css" rel="stylesheet" type="text/css" />		
	<script src="../_libs/js/jquery-2.1.4.min.js"></script>
	<script src="../_libs/js/bootstrap.min.js"></script>
	<script src="../_libs/js/CalendarPopup.js"></script>
	<script type="text/javascript" src="../_libs/js/sptm.js"></script>
	<script type="text/javascript" src="../_libs/datepicker/jquery-ui.min.js"></script>
	<script type="text/javascript" src="../_libs/datepicker/jquery-ui-timepicker-addon.js"></script>
	<script type="text/javascript" src="../_libs/datepicker/jquery-ui-sliderAccess.js"></script>
	
	<script type="text/javascript">
		$(document).ready(function () {     				                         				
			$("#in_dlvm_date").datepicker({
				dateFormat: 'dd/mm/yy',
				numberOfMonths: 1
			});		
			$("#in_dlvm_date").datepicker({
				dateFormat: 'dd/mm/yy',
				numberOfMonths: 1
			});			
		});		
	</script>
	
	<script language="javascript">		
		function loadresult() {
			$('#result').html("<center><img id='progress' src='../_images/loading0.gif' width=80 height=80><center>");
		}		
		function showdata() {			
			var errorflag = false;
			var errortxt = "";
			document.getElementById("msghead").innerHTML = "พบข้อผิดผลาดในการบันทึกข้อมูล";
			if (errorflag ) {			
				document.getElementById("msgbody").innerHTML = "<font color=red>" + errortxt + "</font>";
				$("#myModal").modal("show");
			}
			else {	
				loadresult()
				document.frm.submit();									
			}
		}
		
		function mappostform(doc_sap_current) {	
			var errorflag = false;
			var errortxt = "";
			document.getElementById("msghead").innerHTML = "พบข้อผิดผลาดในการบันทึกข้อมูล";
			var dlvm_doc_sap = document.frm_expensemap.dlvm_doc_sap.value;
			
			if (dlvm_doc_sap=="") {
				if (errortxt!="") {errortxt = errortxt + "<br>";}
				errorflag = true;
				errortxt = errortxt + "กรุณาระบุ SAP Document";				
			}
			if (errorflag ) {			
				document.getElementById("msgbody").innerHTML = "<font color=red>" + errortxt + "</font>";
				$("#myModal").modal("show");
			}
			else {				
				document.frm_expensemap.submit();									
			}			
		}
		
		function delexpense(dlvm_nbr,pg) {
			if(confirm('ท่านต้องการลบข้อมูลการนี้ ไช่หรือไม่ ?')) {	
				document.frmdelete.dlvm_nbr.value = dlvm_nbr;
				document.frmdelete.pg.value = pg;
				document.frmdelete.submit();
			}
		}		
		function gotopage(mypage) {					
			var in_dlvm_nbr = document.frm.in_dlvm_nbr.value;	
			var in_dlvm_sptm_nbr = document.frm.in_dlvm_sptm_nbr.value;
			var in_dlvm_customer = document.frm.in_dlvm_customer.value;
			var in_dlvm_emp_name = document.frm.in_dlvm_emp_name.value;
			var in_dlvm_step_code = document.frm.in_dlvm_step_code.value;
			var in_dlvm_select = document.frm.in_dlvm_select.value;		
			
			loadresult()
			document.frm.pg.value=mypage;
			document.frm.submit();
		}	
	</script>	
</head>
<body>		
	<?php				
	$sql_cnt = "SELECT * FROM dlvm_mstr " .
		" INNER JOIN sptm_mstr ON sptm_nbr = dlvm_sptm_nbr " .
		" INNER JOIN customer ON sptm_customer_number = customer_number " .
		" INNER JOIN emp_mstr ON emp_user_id = sptm_req_by ".
		$criteria;
	$result = sqlsrv_query( $conn,$sql_cnt, array(), array( "Scrollable" => 'keyset' ));	
	$max = sqlsrv_num_rows($result);	 	
	
	$pagesize = 15;
	$totalrow = $max;
	$totalpage = ($totalrow/$pagesize) - (int)($totalrow/$pagesize);
	if ($totalpage > 0) {
		$totalpage = ((int)($totalrow/$pagesize)) + 1;
	} else {
		$totalpage = (int)$totalrow/$pagesize;
	}					
	if ($_REQUEST["pg"]=="") {
		$currentpage = 1;	
		$end_row = ($currentPage * $pagesize) - 1;
		if ($end_row > ($totalrow - 1)) { $end_row = $totalrow - 1; }
		$start_row = 0;
	} else {
		$currentpage = $_REQUEST["pg"];
		if ((int)$currentpage < 1) { $currentpage = 1; }
		if ((int)$currentpage > (int)$totalpage) { $currentpage = $totalpage; }
		$end_row = ($currentpage * $pagesize) - 1;
		$start_row = $end_row - $pagesize + 1;
		if ($end_row > ($totalrow - 1)) { $end_row = $totalrow - 1; }					
	}
	
	$maxpage = 11; //-- ใส่ได้เฉพาะเลขคี่เท่านั้น
	$slidepage = (int)($maxpage/2); //-มีไว้สำหรับเลื่อน	
	if ((int)($totalpage) <= (int)($maxpage)) {
		$maxpage = $totalpage;
	}		
	if ($currentpage < $maxpage) {
		$start_page = 1;
		$end_page = $maxpage;	
	} else {		
		$start_page = $currentpage - $slidepage;
		$end_page = $currentpage + $slidepage;
		if ($start_page <= 1) {
			$start_page = 1;
			$end_page = $maxpage;
		} 
		if ($end_page >= $totalpage) {
			$start_page = $totalpage - $maxpage + 1;
			$end_page = $totalpage;
		}
	}	
	?>	
	<div id="result"></div>
	<div>			
		<TABLE width="100%" border=0 height="100%" align=center cellpadding=0 cellspacing=0>
			<tr><td><?php include("../menu.php"); ?></td></tr>				
			<tr>
				<td height="100%" align=center valign=top>
					<table border=0	width="100%" cellpadding="1" cellspacing="0">				
						<tr style="height:30px" bgcolor="lightgray">
							<td><img src='../_images/delivery-icon.png' width=32><span style='font-size:11pt'><b>@Package Data</b></span></td>
							<td></td>
						</tr>				
						<tr>
							<td width=100% valign=top>
								<table width="100%" border="0" bgcolor="DarkKhaki">
									<form name="frm" method="POST" autocomplete=OFF action="dlvmall.php">
									<input type="hidden" name="in_dlvm_tmpsubmit" value="search">
									<input type="hidden" name="action">	
									<input type="hidden" name="pg">
		
									<tr>
										<td style="width:90px;text-align:right" class="f_bk8b">Package No<font color=red><b>*</b></font>:</td>
										<td style="width:50px">
											<input name="in_dlvm_nbr" value="<?php echo $in_dlvm_nbr?>" class="inputtext_s" style='color:blue'>												
										</td>
										<td style="width:80px;text-align:right" class="f_bk8b">สถานะ<font color=red><b>*</b></font>:</td>
										<td style="width:160px">
											<select name="in_dlvm_step_code" class="f_bl8" style="width: 150px;margin: auto" >
												<option value="">-- ทั้งหมด --</option>
												<?php 
												$sql_dlvs_step = "SELECT dlvs_step_code,dlvs_step_name FROM dlvs_mstr order by dlvs_step_seq";												
												$result_dlvs_step_list = sqlsrv_query( $conn,$sql_dlvs_step);																													
												while($r_dlvs_step_list=sqlsrv_fetch_array($result_dlvs_step_list, SQLSRV_FETCH_ASSOC)) {
												?>
													<option  value="<?php echo $r_dlvs_step_list['dlvs_step_code'];?>"
													<?php if (trim($r_dlvs_step_list['dlvs_step_code']) == "$in_dlvm_step_code") { echo "selected"; } ?>>
													<?php echo $r_dlvs_step_list['dlvs_step_name'];?></option> 
												<?php } ?>
											</select>												
										</td>
										<td style="width:60px;text-align:right" class="f_bk8b">ผู้ขอเบิก<font color=red><b>*</b></font>:</td>
										<td style="" colspan=3>
											<input name="in_dlvm_emp_name" value="<?php echo $in_dlvm_emp_name?>" class="inputtext_s" style='color:blue'>  
											<input name="in_dlvm_notshow" type="checkbox" <?php if ($in_dlvm_notshow=="on") {echo "checked";}?> class="f_bl8" style="margin:auto"> <span style="color:red"><b>ไม่แสดงรายการที่รับหรือยกเลิกแล้ว </b></span>&nbsp;&nbsp;  
											<input name="in_dlvm_shownpd" type="checkbox" <?php if ($in_dlvm_shownpd=="on") {echo "checked";}?> class="f_bl8" style="margin:auto"> <span style="color:red"><b>แสดงเฉพาะ NPD</b></span>
											<input name="in_dlvm_zone_printed" type="checkbox" <?php if ($in_dlvm_zone_printed=="on") {echo "checked";}?> class="f_bl8" style="margin:auto"> <span style="color:green"><b>แสดงที่พิมพ์ใบจัดโซนแล้ว</b></span>
										</td>
									</tr>
									<tr>
										<td style="text-align:right" class="f_bk8b">Request No<font color=red><b>*</b></font>:</td>
										<td style="">
											<input name="in_dlvm_sptm_nbr" value="<?php echo $in_dlvm_sptm_nbr?>" class="inputtext_s" style='color:blue'>												
										</td>
										<td style="text-align:right" class="f_bk8b">กลุ่มเอกสาร<font color=red><b>*</b></font>:</td>
										<td style="">
											<select name="in_dlvm_select" class="f_bl8" style="width:150px;margin: auto">
												<option value="1" <?php if ($in_dlvm_select=="1") { echo "selected"; }?>>เอกสารที่รอคุณดำเนินการ</option>										
												<option value="2" <?php if ($in_dlvm_select=="2") { echo "selected"; }?>>เอกสารเกี่ยวกับคุณ</option>												
											</select>											
										</td>
										<td style="text-align:right" class="f_bk8b">ชื่อลูกค้า<font color=red><b>*</b></font>:</td>
										<td style="width:90px">
											<input name="in_dlvm_customer" value="<?php echo $in_dlvm_customer?>" class="inputtext_s" style='color:blue'>
											
										</td>
										<td style="width:90px;text-align:right" class="f_bk8b">ผู้ทำ Package<font color=red><b>*</b></font>:</td>
										<td style="">
											<select name="in_dlvm_packing_by" class="f_bl8" style="width: 100px;margin: auto" >
												<option value="">-- ทั้งหมด --</option>
												<?php 
												$sql_worker = "SELECT worker_code,worker_name FROM worker_mstr order by worker_seq";												
												$result_worker_list = sqlsrv_query( $conn,$sql_worker);																													
												while($r_worker_list=sqlsrv_fetch_array($result_worker_list, SQLSRV_FETCH_ASSOC)) {
												?>
													<option  value="<?php echo $r_worker_list['worker_code'];?>"
													<?php if (trim($r_worker_list['worker_code']) == "$in_dlvm_packing_by") { echo "selected"; } ?>>
													<?php echo $r_worker_list['worker_name'];?></option> 
												<?php } ?>
											</select>
											
											<input type="image" name="showdata" id="showdata" src="../_images/find_25x25.png" alt="Submit" onclick="showdata()">
										</td>
									</tr>
									</form>									
								</table>
							</td>
						</tr>
						<tr bgcolor="lightgray">
							<td width=100% colspan=2>
							<table width="99%" border="0" align="center" cellpadding="1" cellspacing="1" bgcolor="#cccccc">
								<tr>
									<td width=30%>
									(Total <font color=red><?php echo $totalpage;?></font>&nbsp;Pages&nbsp;<font color=red><?php echo $totalrow;?></font>&nbsp;items)								
									<b>Jump To Page:</b>&nbsp;<input name="jumto" class="inputtext_s" style="width:30px;">&nbsp;<input name="go" type="button" class="paging" style="margin:auto" value="go" onclick="gotopage(document.all.jumto.value)">
									</td>
									<td width=40% class="f_bk8" align=right>
									<?php
									if ($start_page > 1) {																				
										echo "<A href='javascript:gotopage(1)' class='paging'>First</a>&nbsp;";
									}														
									for ($pg=$start_page; $pg<=$end_page; $pg++) {											
										if ((int)($currentpage) == (int)($pg)) {											
											echo "<A href='javascript:gotopage(" . $pg . ")' class='pageselected'><u><b>" . $pg . "</b></u></a>";
										} else {											
											echo "<A href='javascript:gotopage(" . $pg . ")' class='paging'>" . $pg . "</a>";
										}									
										if ($pg<>$totalpage) {
											echo "&nbsp;";
										}
									}												
									if ($end_page < $totalpage) {										
										echo "<A href='javascript:gotopage(" . $totalpage . ")' class='paging'>Last</a>";
									}
									?>																		
									</td>
								</tr>
							</table> 							
							</td>						
						</tr>
						<tr>
							<td width=100% colspan=2>
								<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">									
									<tr>													
										<td bgcolor="white">											
											<table class="table table-striped table-bordered table-condensed" width="100%" border="0" cellspacing="1" cellpadding="4">
												<thead>
												<tr valign="top" style=""height="25" align="center">
													<td colspan=7></td>
													<td colspan=3 style="background-color:gold;text-align:center"><b>ข้อมูลการขนส่ง</b></td>
													<td colspan=3 style="background-color:gold;text-align:center"><b>จำนวน</b></td>
												</tr>
												<tr valign="top" style="background-color:#D4EFBA;" height="25" align="center">
													<td style="width:30px;text-align:center">No</td>
													<td style="width:90px;text-align:center">Package No<br>
														<span style='color:orange;font-weight:bold'>วันที่ส่งมา</span><br>
													</td>
													
													<td style="width:150px;text-align:center">Request No<br>
														<span style='color:orange;font-weight:bold'>Copy Refer</span><br>
													</td>
													<td style="width:250px;">ชื่อลูกค้า<br><span style='color:orange'>ผู้ขอเบิก</span></td>
													<td style="width:200px;">วิธีการจัดส่ง<br><span style='color:orange;font-weight:bold'>อำเภอ/จังหวัด</span></td>
													<td style="width:200px;">ชื่อผู้รับ<br><span style='color:orange;font-weight:bold'>เบอร์โทรผู้รับ</span></td>
													<td style="width:90px;text-align:center"><u>ขอเบิก</u><br><span style='color:orange;font-weight:bold'>[ขอรับ]</span></td>
													
									
													<td style="width:200px;text-align:center">บริษัทขนส่ง<br><span style='color:orange;font-weight:bold'>หมายเลขอ้างอิง</span></td>
											
													<td style="width:80px;text-align:center">ทะเบียนรถ<br><span style='color:orange;font-weight:bold'>เบอร์ขนส่ง</span></td>
													<td style="width:60px;text-align:center">น้ำหนัก<br>(KG)</td>
													<td style="background:lightgray;width:30px;text-align:center">ขน</td>
													<td style="background:green;color:white;width:30px;text-align:center">รับ</td>
													<td style="background:red;color:white;width:30px;text-align:center">ไม่รับ</td>
													<td style="width:40px;text-align:center">รอคอย</td>
													<td style="width:150px;text-align:center">สถานะ<br><span style='color:green;font-weight:bold'>[วันที่รับสินค้า]</span></td>
													<td style="width:80px;text-align:center">Action</td>
													<td></td>
												</tr>
												</thead>   
												<tbody>
												<?php																								
												$n = 0;													
												$sql_dlvm = "SELECT dlvm.* FROM" .
												" (SELECT ROW_NUMBER() OVER(ORDER BY dlvm_dlvs_step_code,dlvm_nbr) AS rownumber,* FROM dlvm_mstr " .
												" INNER JOIN sptm_mstr ON sptm_nbr = dlvm_sptm_nbr " .
												" INNER JOIN customer ON sptm_customer_number = customer_number " .
												" INNER JOIN emp_mstr ON emp_user_id = sptm_req_by " .
												" $criteria) as dlvm" .
												" WHERE dlvm.rownumber > $start_row and dlvm.rownumber <= $start_row+$pagesize";																																																														
												
												$result_dlvm = sqlsrv_query( $conn, $sql_dlvm);
												while($r_dlvm = sqlsrv_fetch_array($result_dlvm, SQLSRV_FETCH_ASSOC)) {	
													$dlvm_nbr = $r_dlvm['dlvm_nbr'];
													$dlvm_sptm_nbr = $r_dlvm['dlvm_sptm_nbr'];
													$dlvm_postdlv_date = $r_dlvm['dlvm_postdlv_date'];
													$dlvm_postdlv_by = $r_dlvm['dlvm_postdlv_by'];
													$dlvm_postdlv_by_name = html_quot(findsqlval("emp_mstr","emp_th_firstname+' '+emp_th_lastname", "emp_user_id", $dlvm_postdlv_by,$conn));
													$dlvm_packing_weight = html_quot($r_dlvm['dlvm_packing_weight']);
													$dlvm_postdlv_cmmt = html_quot($r_dlvm['dlvm_postdlv_cmmt']);
													$dlvm_transport_tspm_code = $r_dlvm['dlvm_transport_tspm_code'];
													$dlvm_transport_tspm_name = html_quot(findsqlval("tspm_mstr","tspm_name", "tspm_code", $dlvm_transport_tspm_code,$conn));
													$dlvm_transport_ref_no = html_quot($r_dlvm['dlvm_transport_ref_no']);
													$dlvm_transport_driver_name = html_quot($r_dlvm['dlvm_transport_driver_name']);
													$dlvm_transport_driver_tel = html_quot($r_dlvm['dlvm_transport_driver_tel']);
													$dlvm_transport_car_nbr = html_quot($r_dlvm['dlvm_transport_car_nbr']);
													$dlvm_transport_cmmt = html_quot($r_dlvm['dlvm_transport_cmmt']);
													$dlvm_transport_amt = html_quot($r_dlvm['dlvm_transport_amt']);
													if ($dlvm_transport_amt!="" && $dlvm_transport_amt!=0) {
														$dlvm_transport_amt_text = "<br><span style='color:red'>(ค่าใช้จ่าย: " .$dlvm_transport_amt . " บาท)</span>";
													}
													$dlvm_receive_by = $r_dlvm['dlvm_receive_by'];
													$dlvm_receive_date = $r_dlvm['dlvm_receive_date'];
													$dlvm_receive_cmmt = html_quot($r_dlvm['dlvm_receive_cmmt']);
													$dlvm_receive_s_filename = $r_dlvm['dlvm_receive_s_filename'];
													$dlvm_receive_o_filename = $r_dlvm['dlvm_receive_o_filename'];
													$dlvm_receive_lat = html_quot($r_dlvm['dlvm_receive_lat']);
													$dlvm_receive_lon = html_quot($r_dlvm['dlvm_receive_lon']);
													$dlvm_dlvs_step_code = $r_dlvm['dlvm_dlvs_step_code'];
													$dlvm_step_name = findsqlval("dlvs_mstr","dlvs_step_name", "dlvs_step_code", $dlvm_dlvs_step_code,$conn);
													$dlvm_step_by = $r_dlvm['dlvm_step_by'];
													$dlvm_step_by_name = findsqlval("emp_mstr","emp_th_firstname+' '+emp_th_lastname", "emp_user_id", $dlvm_step_by,$conn);
													$dlvm_step_date = $r_dlvm['dlvm_step_date'];
													$dlvm_step_cmmt = html_quot($r_dlvm['dlvm_step_cmmt']);
													$dlvm_curprocessor = $r_dlvm['dlvm_curprocessor'];
													
													$sptm_customer_number = $r_dlvm['sptm_customer_number'];
													$sptm_customer_dummy = $r_dlvm['sptm_customer_dummy'];
													$sptm_customer_amphur = html_quot($r_dlvm['sptm_customer_amphur']);
													$sptm_customer_province = html_quot($r_dlvm['sptm_customer_province']);
													$sptm_expect_receiver_name = html_quot($r_dlvm['sptm_expect_receiver_name']);
													$sptm_expect_receiver_tel = html_quot($r_dlvm['sptm_expect_receiver_tel']);
													$sptm_reason_code = $r_dlvm['sptm_reason_code'];
													$sptm_expect_receipt_date = $r_dlvm['sptm_expect_receipt_date'];
													$sptm_delivery_mth = html_quot($r_dlvm['sptm_delivery_mth']);
													$sptm_delivery_mth_name = html_quot(findsqlval("delivery_mth","delivery_name", "delivery_code", $sptm_delivery_mth,$conn));
													$sptm_delivery_mth_desc = html_quot($r_dlvm['sptm_delivery_mth_desc']);
													$sptm_req_by = $r_dlvm['sptm_req_by'];
													$sptm_req_by_name = findsqlval("emp_mstr","emp_th_firstname+' '+emp_th_lastname", "emp_user_id", $sptm_req_by,$conn);
													$sptm_req_date = $r_dlvm['sptm_req_date'];
													
													$dlvm_zone_printed = $r_dlvm['dlvm_zone_printed'];
													$dlvm_zone_print_by = $r_dlvm['dlvm_zone_print_by'];
													$dlvm_zone_print_by_name = findsqlval("emp_mstr","emp_th_firstname+' '+emp_th_lastname","emp_user_id",$dlvm_zone_print_by,$conn);
													$dlvm_zone_print_date = $r_dlvm['dlvm_zone_print_date'];
													$dlvm_wh_status = $r_dlvm['dlvm_wh_status'];
													if ($dlvm_zone_printed) {
														$dlvm_zone = "<br><span style='color:green' title='พิมพ์โดย: ".$dlvm_zone_print_by_name."\n".dmyhmsdb($dlvm_zone_print_date,"Y")."'>พิมพ์ใบจัดโซนแล้ว</span>";
													} else {
														$dlvm_zone = "";
													}
													if ($dlvm_wh_status == "N") {
														$dlvm_wh_status_text = "<span style='color:red' title='ขึ้นสินค้าไม่ได้'>*ขึ้นไม่ได้*</span><br>";
													}
													else {
														$dlvm_wh_status_text = "";
													}

													//NPD
													$sptm_npd = $r_dlvm['sptm_npd'];
													$sptm_npd_com = $r_dlvm['sptm_npd_com'];
													$sptm_npd_type = $r_dlvm['sptm_npd_type'];
													$sptm_npd_brand = $r_dlvm['sptm_npd_brand'];
													$sptm_npd_setno = $r_dlvm['sptm_npd_setno'];
													$sptm_npd_customer_total = $r_dlvm['sptm_npd_customer_total'];
													if ($sptm_npd) {
														$sptm_npd_text = " [*NPD*]";
													}
													//
													$sptm_copy_refer = $r_dlvm['sptm_copy_refer'];
													$sptm_copy_refer_text = "";
													if ($sptm_copy_refer != "") {
														$sptm_copy_refer_text = "<span style='color:red;font-size:8pt;'>[Ref: $sptm_copy_refer] $sptm_npd_text</span>";
													}

													$sptm_customer_name = html_quot(findsqlval("customer","customer_name1", "customer_number", $sptm_customer_number,$conn));
													if ($sptm_customer_name != "") {
														$sptm_customer_name = '['.$sptm_customer_number.'] ' . $sptm_customer_name;
													}
													else {
														$sptm_customer_name = $sptm_customer_dummy;
													}
													//DETAIL
													$dlvd_qty_delivery = 0;
													$dlvd_qty_received = 0;
													$dlvd_qty_not_received = 0;
													$sql_dlvd = "SELECT * FROM dlvd_det WHERE dlvd_dlvm_nbr = '$dlvm_nbr'";
													$result_dlvd = sqlsrv_query( $conn, $sql_dlvd );											
													while($r_dlvd = sqlsrv_fetch_array($result_dlvd, SQLSRV_FETCH_ASSOC)) {	
														$dlvd_qty = $r_dlvd['dlvd_qty'];
														$dlvd_receive_status = $r_dlvd['dlvd_receive_status'];
														if ($dlvd_receive_status == 'Y') { 
															$dlvd_qty_received = $dlvd_qty_received + $dlvd_qty; 
														}
														if ($dlvd_receive_status == 'N') { 
															$dlvd_qty_not_received = $dlvd_qty_not_received + $dlvd_qty; 
														}
														$dlvd_qty_delivery = $dlvd_qty_delivery + $dlvd_qty;
													}
													$day_expect_work = day_diff($sptm_req_date,$sptm_expect_receipt_date)+1;
													$day_wait = "";
													if (inlist('10,20',$dlvm_dlvs_step_code)) {
														$day_wait = day_diff(date_format($dlvm_postdlv_date,'Ymd'),date('Ymd'))+1 . ' วัน';
													}
													if (inlist('90',$dlvm_dlvs_step_code)) {
														if ($dlvm_receive_date != "") {
															$day_wait = day_diff(date_format($dlvm_postdlv_date,'Ymd'),$dlvm_receive_date)+1 . ' วัน';
														}
													}
													$n++;																										
													?>													
													<tr ONMOUSEOVER="this.style.backgroundColor ='white'" ONMOUSEOUT = "this.style.backgroundColor = ''">
														<td class="f_bk8" style="text-align:center;"><?php echo $n+($currentpage-1)*$pagesize; ?></td>
														<td class="f_bk8" style="text-align:center;"><?php echo $dlvm_nbr; ?><br><span style='color:orange'>[<?php echo dmydb($dlvm_postdlv_date,'y'); ?>]</span></td>
														
														<td class="f_bk8" style="text-align:center;"><?php echo $dlvm_sptm_nbr; ?><br><span style='color:orange'><?php echo $sptm_copy_refer_text?></span></td>
														<td style=""><b><?php echo $sptm_customer_name; ?></b><br><span style='color:orange'><?php echo $sptm_req_by_name; ?></span></td>
														<td style="" title="<?php echo $sptm_delivery_mth_desc; ?>"><?php echo $sptm_delivery_mth_name; ?>
															<span style='color:orange'>
															<?php 
															if ($sptm_delivery_mth=="MCUS") {
																echo "<br>".$sptm_customer_amphur."/".$sptm_customer_province; 
															}	
															?>
															</span>
														</td>
														<td style=""><?php echo $sptm_expect_receiver_name; ?><br><?php echo $sptm_expect_receiver_tel; ?></td>
														<td style="text-align:center"><u><?php echo dmyty($sptm_req_date); ?></u><br><span style='color:red'>[<?php echo dmyty($sptm_expect_receipt_date); ?>=<?php echo $day_expect_work?>D]</span></td>
														<td style="max-width:200px;overflow:hidden;" title="<?php echo "Refer NO: " .$dlvm_transport_ref_no?>"><?php echo $dlvm_transport_tspm_name; ?><br><span style='color:orange'><?php echo $dlvm_transport_ref_no?><?php echo $dlvm_transport_amt_text?></span></td>
														<td style="max-width:80px;overflow:hidden;"><span title="<?php echo "ทะเบียนรถ: " . $dlvm_transport_car_nbr?>"><?php echo $dlvm_transport_car_nbr; ?></span><br><span title="<?php echo "เบอร์โทร: " . $dlvm_transport_driver_tel . '&#013' . $dlvm_transport_driver_name?>" style='color:orange'><?php echo $dlvm_transport_driver_tel; ?></span></td>
														<td class="f_bk8" style="text-align:center;"><?php echo $dlvm_packing_weight; ?></td>
														<td style="text-align:center">
															<?php if ($dlvd_qty_delivery > 0) { ?>
																<span class="bubbletext"
																<?php
																if ($dlvd_qty_received + $dlvd_qty_not_received > 0) {
																	if ($dlvd_qty_delivery == $dlvd_qty_received) { echo "style='background:green;color:white'";}
																	elseif ($dlvd_qty_delivery == $dlvd_qty_not_received) { echo "style='background:red;color:white'";}
																	else { echo "style='background:orange;color:black'";}
																}
																?>>
																<?php echo $dlvd_qty_delivery; ?></span>
															<?php }?>
														</td>
														<td style="text-align:center">
															<?php if ($dlvd_qty_received > 0) { ?>
																<span class="bubbletext" style='background:green;color:white'><?php echo $dlvd_qty_received; ?></span>
															<?php }?>
														</td>
														<td style="text-align:center">
															<?php if ($dlvd_qty_not_received > 0) { ?>
																<span class="bubbletext" style='background:red;color:white'><?php echo $dlvd_qty_not_received; ?></span>
															<?php }?>
														</td>
														<td style="text-align:center"><?php echo $day_wait; ?></td>
														<td style="text-align:center">
															<?php if($dlvm_receive_date!="") {?>
																<center>
																	<div style='background:green;color:white;width:90px;border-radius:4px'>
																		<?php echo $dlvm_step_name; ?>
																	</div>
																	<span style='color:green'>[<?php echo dmyty($dlvm_receive_date); ?>]</span>
																</center>
															<?php } else {?>
																<center>
																	<?php echo $dlvm_wh_status_text?>
																	<?php if (inlist("10",$dlvm_dlvs_step_code)) { 
																		echo "<span style='color:orange'>$dlvm_step_name</span>$dlvm_zone"; 
																	}?>
																	<?php if (inlist("20,30,40,50,60",$dlvm_dlvs_step_code)) { echo "<span style='color:blue'>$dlvm_step_name</span>"; }?>
																	<?php if ($dlvm_dlvs_step_code == '80') { echo "<span style='color:red;text-decoration: line-through'>$dlvm_step_name</span>"; }?>
																</center>
															<?php }?>
														</td>													
														<td>
															<center>
																<a href="javascript:void(0)" onclick="loadresult();window.location.href='dlvdmnt.php?dlvmnumber=<?php echo encrypt($dlvm_nbr, $key);?>&pg=<?php echo $currentpage?>'">
																	<span style='border-radius:50%'><img src='../_images/dlvm.png'></span>
																</a>
															</center>
														</td>
														<td style="text-align:center;width:15px">
															<?php if($activeid==$dlvm_nbr) {echo "<img src='../_images/active-id.png'>";}?>
														</td>
													</tr>
												<?php }?>	
												</tbody>
											</table>  
											
										</td>										
									</tr>
								</table>
								
							</td>
						</tr>
					</table>
				</td>
			</tr>							
		</table>	
	</div>																	
	<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			<h5 id="msghead"></h5>
		</div>
		<center><div id="msgbody" class="modal-body"></div></center>
		<div class="modal-footer">
			<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>			
		</div>
	</div>
	<form name="frmdelete" method="post" action="../serverside/expensepost.php">
		<input type="hidden" name="action" value="delete">			
		<input type="hidden" name="dlvm_nbr">
		<input type="hidden" name="pg">
	</form>	
	</body>
</html>
