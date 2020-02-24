<?php 
include("../_incs/chksession.php");  
include("../_incs/config.php");	
include("../_incs/funcServer.php");	

set_time_limit(0);
$curdate = date('Ymd');
$action = mssql_escape($_REQUEST['action']);
$activeid = decrypt(mssql_escape($_REQUEST['activeid']), $key);

$in_sptm_tmpsubmit = mssql_escape($_POST["in_sptm_tmpsubmit"]);
$in_sptm_nbr = mssql_escape($_POST["in_sptm_nbr"]);
$in_sptm_customer = mssql_escape($_POST["in_sptm_customer"]);
$in_sptm_step_code = mssql_escape($_POST["in_sptm_step_code"]);
$in_sptm_select = mssql_escape($_POST["in_sptm_select"]);
$in_sptm_shownpd = $_POST["in_sptm_shownpd"];

If ($in_sptm_tmpsubmit == "") {
	$in_sptm_tmpsubmit = $_COOKIE['in_sptm_tmpsubmit'];	
	$in_sptm_nbr = $_COOKIE['in_sptm_nbr'];
	$in_sptm_customer = $_COOKIE['in_sptm_customer'];
	$in_sptm_select = $_COOKIE['in_sptm_select'];
	$in_sptm_step_code = $_COOKIE['in_sptm_step_code'];
	$in_sptm_shownpd = $_COOKIE['in_sptm_shownpd'];
}
else {		
	setcookie("in_sptm_tmpsubmit","",0);
	setcookie("in_sptm_nbr","",0);
	setcookie("in_sptm_customer","",0);
	setcookie("in_sptm_select","",0);
	setcookie("in_sptm_step_code","",0);
	setcookie("in_sptm_shownpd","",0);
}
//ใช้เป็นตัวกำหนดค่า default ในการ login ครั้งแรก

if ($user_first_into == "1") { 
	$in_sptm_select = "1"; 
	//$in_sptm_notshow = "on";
}
setcookie("spt_first_into", "0",0, "/");	

//
if ($in_sptm_nbr != "") {
	if ($criteria != "") { $criteria = $criteria . " AND "; }
	$criteria = $criteria . " ((sptm_nbr like '%$in_sptm_nbr%') or (sptm_copy_refer like '%$in_sptm_nbr%'))";
}
//setcookie("in_sptm_nbr", $in_sptm_nbr,0);
setcookie("in_sptm_nbr", $in_sptm_nbr,0);	
//
if ($in_sptm_customer != "") {
	if ($criteria != "") { $criteria = $criteria . " AND "; }
	$criteria = $criteria . " (customer_name1 like '%$in_sptm_customer%' OR sptm_customer_dummy like '%$in_sptm_customer%')";
}
setcookie("in_sptm_customer", $in_sptm_customer,0);	
//

if ($in_sptm_select != "") {
	$sptm_curprocessor_role_access = "";
	$sptm_curprocessor_role_array = explode(",",$user_role);
	for ($c=0;$c<count($sptm_curprocessor_role_array);$c++) {														
		if ($sptm_curprocessor_role_access != "") { $sptm_curprocessor_role_access = $sptm_curprocessor_role_access . " OR "; }
		$sptm_curprocessor_role_access = $sptm_curprocessor_role_access . "(sptm_curprocessor like ". "'%" . $sptm_curprocessor_role_array[$c]."%')";														
	}
	//Who can Read
	$sptm_whocanread_role_access = "";
	$sptm_whocanread_role_array = explode(",",$user_role);																																							
	for ($c=0;$c<count($sptm_whocanread_role_array);$c++) {														
		if ($sptm_whocanread_role_access != "") { $sptm_whocanread_role_access = $sptm_whocanread_role_access . " OR "; }
		$sptm_whocanread_role_access = $sptm_whocanread_role_access . "(sptm_whocanread like ". "'%" . $sptm_whocanread_role_array[$c]."%')";														
	}
	
	if ($criteria != "") { $criteria = $criteria . " AND "; }
	switch ($in_sptm_select) {
		case "1" : //งานที่รอคุณดำเนินการ				
			$criteria = $criteria . " ((sptm_curprocessor like '%$user_login%') OR $sptm_curprocessor_role_access)";
			break;
		case "2" :				
			$criteria = $criteria . " ((sptm_whocanread like '%$user_login%') OR $sptm_whocanread_role_access)";
			break;		
	}				
}
setcookie("in_sptm_select", $in_sptm_select,0);	
//	
if ($in_sptm_step_code != "") {
	if ($criteria != "") { $criteria = $criteria . " AND "; }
	$criteria = $criteria . " sptm_step_code = '$in_sptm_step_code'";
}
setcookie("in_sptm_step_code", $in_sptm_step_code,0);	
//
if ($in_sptm_shownpd != "") {
	if ($criteria != "") { $criteria = $criteria . " AND "; }
	$criteria = $criteria . " sptm_npd = '1'";	
}
setcookie("in_sptm_shownpd", $in_sptm_shownpd,0);
//
if ($criteria != "") { $criteria = " AND " . $criteria; }		

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
	<link href="../_libs/css/sptm.css" rel="stylesheet">
	<script src="../_libs/js/jquery-2.1.4.min.js"></script>
	<script src="../_libs/js/bootstrap.min.js"></script>
	<script src="../_libs/js/CalendarPopup.js"></script>
	<script type="text/javascript" src="../_libs/js/sptm.js"></script>	
	<script type="text/javascript" src="../_libs/datepicker/jquery-ui.min.js"></script>
	<script type="text/javascript" src="../_libs/datepicker/jquery-ui-timepicker-addon.js"></script>
	<script type="text/javascript" src="../_libs/datepicker/jquery-ui-sliderAccess.js"></script>
	<script type="text/javascript">
		$(document).ready(function () {     				                         				
			$("#in_sptm_date").datepicker({
				dateFormat: 'dd/mm/yy',
				numberOfMonths: 1
			});			
		});		
	</script>
	
	<script language="javascript">		
		function loadresult() {
			document.all.result.innerHTML = "<center><img id='progress' src='../_images/loading0.gif' width=80 height=80><center>";
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
	
		function sptmcopypost(sptm_nbr) {	
			var errorflag = false;
			var errortxt = "";
			document.getElementById("msghead").innerHTML = "พบข้อผิดผลาดในการบันทึกข้อมูล";
			var conf_info = "ระบบจะทำการ COPY ข้อมูลของใบเบิกหมายเลข "+sptm_nbr + " ไปเป็นใบเบิกใบใหม่ ท่านต้องการ COPY ใบเบิกใบนี้ไช่หรือไม่ ?"
			if(confirm(conf_info)) {
				document.frmsptmcopy.sptm_nbr.value = sptm_nbr;
				document.frmsptmcopy.submit();
			}
		}	
	
		function delsptm(sptm_nbr,pg) {
			if(confirm('ท่านต้องการลบข้อมูลการนี้ ไช่หรือไม่ ?')) {	
				document.frmdelete.sptm_nbr.value = sptm_nbr;
				document.frmdelete.pg.value = pg;
				document.frmdelete.submit();
			}
		}		
		function oncurprocessor_click(){
			document.frm.in_sptm_nbr.value = "";
			document.frm.in_sptm_customer.value = "";
			document.frm.in_sptm_step_code.value = "";
			document.frm.in_sptm_select.value = "1";
			loadresult()
			document.frm.submit();
		}
		function gotopage(mypage) {					
			var in_sptm_nbr = document.frm.in_sptm_nbr.value;	
			var in_sptm_customer = document.frm.in_sptm_customer.value;
			var in_sptm_step_code = document.frm.in_sptm_step_code.value;
			var in_sptm_select = document.frm.in_sptm_select.value;				
			loadresult()
			document.frm.pg.value=mypage;
			document.frm.submit();
		}	
</script>	
</head>
<body>		
	<?php				
	//นับจำนวน Record ของงานที่รอคุณทำ
	$total_curprocessor = 0;

	$sql_cnt =  "SELECT count(*) 'cnt' FROM sptm_mstr INNER JOIN customer ON customer_number = sptm_customer_number WHERE sptm_is_delete = '0' and  ((sptm_curprocessor like '%$user_login%') OR $sptm_curprocessor_role_access)";
	$result_cnt = sqlsrv_query($conn, $sql_cnt); 
	$row_cnt = sqlsrv_fetch_array($result_cnt, SQLSRV_FETCH_ASSOC);		
	if ($row_cnt) {
		$total_curprocessor = (int)$row_cnt['cnt'];
	}
	
	if (inlist($user_role,"SPT_ROOM")) {
		$sum_sptd_qty_order = 0;
		$sum_sptd_qty_received = 0;
		$sum_sptd_qty_not_received = 0;
		$sum_sptd_qty_shipment = 0;
		$sum_sptd_qty_delivery = 0;
		$sum_sptd_qty_nogood = 0;
		$sum_sptd_qty_packing = 0;
		$sum_pending = 0;
		
		$sql_sum =  "SELECT 
		sum(sptd_qty_order) 'sum_sptd_qty_order',
		sum(sptd_qty_received) 'sum_sptd_qty_received',
		sum(sptd_qty_not_received) 'sum_sptd_qty_not_received',
		sum(sptd_qty_shipment) 'sum_sptd_qty_shipment',
		sum(sptd_qty_delivery) 'sum_sptd_qty_delivery',
		sum(sptd_qty_nogood) 'sum_sptd_qty_nogood',
		sum(sptd_qty_packing) 'sum_sptd_qty_packing'
		FROM sptd_det INNER JOIN sptm_mstr ON sptm_nbr = sptd_sptm_nbr WHERE sptm_is_delete = '0' and  ((sptm_curprocessor like '%$user_login%') OR $sptm_curprocessor_role_access)";

		$result_sum = sqlsrv_query($conn, $sql_sum); 
		$row_sum = sqlsrv_fetch_array($result_sum, SQLSRV_FETCH_ASSOC);	
		if ($row_sum) {
			$sum_sptd_qty_order = (int)$row_sum['sum_sptd_qty_order'];
			$sum_sptd_qty_received = (int)$row_sum['sum_sptd_qty_received'];
			$sum_sptd_qty_not_received = (int)$row_sum['sum_sptd_qty_not_received'];
			$sum_sptd_qty_shipment = (int)$row_sum['sum_sptd_qty_shipment'];
			$sum_sptd_qty_delivery = (int)$row_sum['sum_sptd_qty_delivery'];
			$sum_sptd_qty_nogood =  (int)$row_sum['sum_sptd_qty_nogood'];
			$sum_sptd_qty_packing =  (int)$row_sum['sum_sptd_qty_packing'];
			$sum_pending = ($sum_sptd_qty_order - $sum_sptd_qty_received - $sum_sptd_qty_not_received - $sum_sptd_qty_shipment - $sum_sptd_qty_delivery - $sum_sptd_qty_nogood - $sum_sptd_qty_packing);
			$sum_ship_deliver = ($sum_sptd_qty_order - $sum_sptd_qty_received - $sum_sptd_qty_not_received - $sum_sptd_qty_shipment - $sum_sptd_qty_delivery - $sum_sptd_qty_nogood - $sum_sptd_qty_packing);
		}
	}
	//นับจำนวนตาม criteria
	$sql_cnt =  "SELECT * FROM sptm_mstr INNER JOIN customer ON customer_number = sptm_customer_number WHERE sptm_is_delete = '0' $criteria";
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
						<tr bgcolor="lightgray">
							<td><img src='../_images/sample-icon.png' width=32>
								<span style='font-size:11pt'><b>@กระเบื้องตัวอย่าง</b></span>
								<?php if((inlist($user_role,"NORMAL_USER") && $user_channel != "") || inlist($user_role,"NPD")) {?>
								<?php if (date('Ymd') >= "20191202") {?>
								<a href="javascript:void(0)" role="button" style="color:#FFF; text-decoration:none;" data-toggle="modal">
									<div class="btn btn-small btn-primary paddingleftandright10" style="margin:auto;" onclick="window.location.href='sptmadd.php?pg=<?php echo $currentpage?>'">
										<i class="icon-plus icon-white"></i>														
										<span>สร้างใบเบิกใหม่</span>
									</div>
								</a>
								<?php }?>
								<?php }?>
							</td>
							<td>
								
							</td>
						</tr>				
						<tr>
							<td width=75% valign=top>
								<table width="80%" border="0" bgcolor="DarkKhaki">
									<form name="frm" method="POST" autocomplete=OFF action="sptmall.php">
									<input type="hidden" name="in_sptm_tmpsubmit" value="search">
									<input type="hidden" name="action">	
									<input type="hidden" name="pg">
									<tr>
										<td style="width:90px;text-align:right" class="f_bk8b">Request No<font color=red><b>*</b></font>:</td>
										<td style="width:50px">
											<input name="in_sptm_nbr" value="<?php echo $in_sptm_nbr?>" class="inputtext_s" style='color:blue'>												
										</td>	
										<td style="width:80px;text-align:right" class="f_bk8b">สถานะ<font color=red><b>*</b></font>:</td>
										<td colspan=3 style="width:160px">
											<select name="in_sptm_step_code" class="f_bl8" style="width: 150px;margin: auto" >
												<option value="">-- ทั้งหมด --</option>
												<?php 
												$sql_step = "SELECT step_code,step_name FROM step_mstr order by step_seq";												
												$result_step_list = sqlsrv_query( $conn,$sql_step);																													
												while($r_step_list=sqlsrv_fetch_array($result_step_list, SQLSRV_FETCH_ASSOC)) {
												?>
													<option  value="<?php echo $r_step_list['step_code'];?>"
													<?php if (trim($r_step_list['step_code']) == "$in_sptm_step_code") { echo "selected"; } ?>>
													<?php echo html_quot($r_step_list['step_name']);?></option> 
												<?php } ?>
											</select>
											<input name="in_sptm_shownpd" type="checkbox" <?php if ($in_sptm_shownpd=="on") {echo "checked";}?> class="f_bl8" style="margin:auto"> <span style="color:red"><b>แสดงเฉพาะ NPD</b></span>											
										</td>
										
									</tr>
									<tr>
										<td style="text-align:right" class="f_bk8b">ชื่อลูกค้า<font color=red><b>*</b></font>:</td>
										<td style=""><input name="in_sptm_customer" value="<?php echo $in_sptm_customer?>" class="inputtext_s" style='color:blue'></td>
										<td style="text-align:right" class="f_bk8b">กลุ่มเอกสาร<font color=red><b>*</b></font>:</td>
										<td style="" colspan=3>
											<select name="in_sptm_select" class="f_bl8" style="width:150px;margin: auto">
												<option value="1" <?php if ($in_sptm_select=="1") { echo "selected"; }?>>เอกสารที่รอคุณดำเนินการ</option>										
												<option value="2" <?php if ($in_sptm_select=="2") { echo "selected"; }?>>เอกสารเกี่ยวกับคุณ</option>												
											</select>
											<input type="image" name="showdata" id="showdata" src="../_images/find_25x25.png" alt="Submit" onclick="showdata()">											
										</td>
									</tr>
									</form>									
								</table>
							</td>
							<td valign=middle align=right>
								<table cellpadding=5 cellspacing=5>
									<tr>
										<td>
											<a href='javascript:void(0)' style="text-decoration: none" onclick="oncurprocessor_click()">
											<div style='background:red;width:200px;height:50px;border-radius:4px;font-size:12pt;color:white;text-align:center'>
											เอกสารที่รอคุณดำเนินการ <br><font size='8'><?php echo $total_curprocessor?></font>
											</div>
											</a>
										</td>
									</tr>
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
												<tr valign="top" bgcolor="#fecf03">
													<td style="width:50px;text-align:center">No</td>
													<td style="width:65px;text-align:center">Request No</td>
													<td style="width:180px;text-align:center">ชื่อผู้เบิก</td>
													<td style="width:250px;text-align:center">ชื่อลูกค้า</td>
													<td style="width:40px;text-align:center">ขอเบิก</td>
													<td style="width:40px;text-align:center">ขอรับ</td>
													<td style="width:40px;text-align:center">อนุมัติ</td>
													<td style="width:40px;text-align:center">เริ่มทำ</td>
													<td style="width:50px;text-align:center">รับครบ</td>
													<td style="width:180px;text-align:center">สถานะ</td>
													<td style="width:130px;text-align:center">สถานะโดย</td>
													<td style="width:60px;text-align:center;">รอคอย</td>
													<td style="width:40px;text-align:center;background-color:gray;color:white">สั่ง</td>
													<td style="width:40px;text-align:center;background-color:green;color:white">รับ</td>
													<td style="width:30px;text-align:center;background-color:red;color:white">ไม่รับ</td>
													<td style="width:55px;text-align:center;background-color:orange;color:black">ระหว่างส่ง</td>
													<td style="width:50px;text-align:center;background-color:yellow;color:black">พร้อมส่ง<br><?php echo $sum_sptd_qty_delivery?></td>
													<td style="width:40px;text-align:center;background-color:red;color:white">ค้างส่ง<br><?php echo $sum_pending?></td>
													<td style="width:50px;text-align:center;background-color:lightcyan;color:black">Pack</td>
													<td style="width:50px;text-align:center;background-color:lightcyan;color:red">ไม่มีของ</td>
													<td style="width:30px;text-align:center">Action</td>
													<td style="width:10px;">&nbsp;</td>
												</tr>
												</thead>   
												<tbody>
												<?php
												$n = 0;													
												$sql_sptm = "SELECT sptm.* FROM" .
												" (SELECT ROW_NUMBER() OVER(ORDER BY case when sptm_approve_date IS NULL THEN 1 ELSE 0 END,sptm_nbr) AS rownumber,* FROM sptm_mstr INNER JOIN customer ON customer_number = sptm_customer_number WHERE sptm_is_delete = 0 $criteria) as sptm" .
												" WHERE sptm.rownumber > $start_row and sptm.rownumber <= $start_row+$pagesize";																																																														
												
												$result_sptm = sqlsrv_query( $conn, $sql_sptm);
												while($r_sptm = sqlsrv_fetch_array($result_sptm, SQLSRV_FETCH_ASSOC)) {	
													$sptm_nbr = $r_sptm['sptm_nbr'];																	
													$sptm_cust_code = $r_sptm['sptm_customer_number'];
													$sptm_cust_dummy = html_quot($r_sptm['sptm_customer_dummy']);
													$sptm_cust_type = $r_sptm['sptm_cust_type'];
													$sptm_cust_amphur =  html_quot($r_sptm['sptm_customer_amphur']);
													$sptm_cust_province = html_quot($r_sptm['sptm_customer_province']);
													$sptm_reason_code = $r_sptm['sptm_reason_code'];
													$sptm_expect_receipt_date = $r_sptm['sptm_expect_receipt_date'];
													$sptm_expect_receiver_name = html_quot($r_sptm['sptm_expect_receiver_name']);
													$sptm_expect_receiver_tel = html_quot($r_sptm['sptm_expect_receiver_tel']);
													$sptm_delivery_mth = html_quot($r_sptm['sptm_delivery_mth']);
													$sptm_delivery_mth_desc = html_quot($r_sptm['sptm_delivery_mth_desc']);
													$sptm_req_by = $r_sptm['sptm_req_by'];
													$sptm_req_by_name = html_quot(findsqlval("emp_mstr","emp_th_firstname+' '+emp_th_lastname", "emp_user_id", $sptm_req_by,$conn));
													$sptm_req_by_sec = html_quot(findsqlval("emp_mstr","emp_en_sec", "emp_user_id", $sptm_req_by,$conn));
													$sptm_req_date = $r_sptm['sptm_req_date'];
													$sptm_req_year = $r_sptm['sptm_req_year'];
													$sptm_req_month = $r_sptm['sptm_req_month'];
													$sptm_submit_date = $r_sptm['sptm_submit_date '];
													$sptm_approve_by = $r_sptm['sptm_approve_by'];
													$sptm_approve_by_name = html_quot(findsqlval("emp_mstr","emp_th_firstname+' '+emp_th_lastname", "emp_user_id", $sptm_approve_by,$conn));
													$sptm_approve_date = $r_sptm['sptm_approve_date'];
													$sptm_approve_cmmt = html_quot($r_sptm['sptm_approve_cmmt']);
													$sptm_first_print_date = $r_sptm['sptm_first_print_date'];
													$sptm_npd = $r_sptm['sptm_npd'];
													$sptm_printed = $r_sptm['sptm_printed'];
													$sptm_copy_refer = $r_sptm['sptm_copy_refer'];
													$sptm_copy_refer_text = "";
													if ($sptm_copy_refer != "") {
														$sptm_copy_refer_text = "Reference: $sptm_copy_refer";
													}
													
													$show_printed = "";
													if (inlist($user_role,"SPT_ROOM")) {
														if ($sptm_printed || $sptm_npd) {
															$show_printed = "background:green;color:white'";
															if ($sptm_npd) {
																if ($sptm_nbr == $sptm_copy_refer) {
																	$show_printed = "background:blue;color:white'";
																}
															}
														}
														else {
															$show_printed = "background:red;color:white'";
														}
													}
													
													$sptm_receive_complete_date = $r_sptm['sptm_receive_complete_date'];
															
													$sptm_step_code = $r_sptm['sptm_step_code'];
													$sptm_step_name = findsqlval("step_mstr","step_name", "step_code", $sptm_step_code,$conn);
													
													$sptm_step_by = $r_sptm['sptm_step_by'];
													$sptm_step_by_name = findsqlval("emp_mstr","emp_th_firstname", "emp_user_id", $sptm_step_by,$conn);
													$sptm_step_date = $r_sptm['sptm_step_date'];
													$sptm_step_cmmt = html_quot($r_sptm['sptm_step_cmmt']);	
													$sptm_remark = html_quot($r_sptm['sptm_remark']);
													$sptm_force_close = $r_sptm['sptm_force_close'];

													$sptm_input_type = $r_sptm['sptm_input_type'];
													$sptm_whocanread = $r_sptm['sptm_whocanread'];
													$sptm_curprocessor = $r_sptm['sptm_curprocessor'];
													if (inlist("0,10,880,890",$sptm_step_code )) {
														if ($sptm_curprocessor!="") {
															$sptm_curprocessor_name = "คุณ" . findsqlval("emp_mstr","emp_th_firstname", "emp_user_id", $sptm_curprocessor,$conn);	
														}
														else {
															$sptm_curprocessor_name = "คุณ" . $sptm_step_by_name; 
														}
													}
													else {
														if ($sptm_step_code == '20') {
															//Get Approver Name ซึ่งอาจจะมีมากกว่า 1 คน
															$sptm_curprocessor_name = "";
															$sptm_curprocessor_array = explode(",",$sptm_curprocessor);
															for ($c=0;$c<count($sptm_curprocessor_array);$c++) {
																$sptm_th_firstname = findsqlval("emp_mstr","emp_th_firstname","emp_user_id",$sptm_curprocessor_array[$c],$conn);
																if ($sptm_th_firstname != "") {
																	if ($sptm_curprocessor_name!="") {$sptm_curprocessor_name .= "<br>";}
																	$sptm_curprocessor_name .= "(".($c+1).") " ."คุณ$sptm_th_firstname";
																}
															}
														}
														if ($sptm_step_code == '30') {
															$sptm_curprocessor_name = "ผู้ดำเนินการ";
															//if (!$sptm_printed) { $sptm_step_name = "กำลังดำเนินการ"; }
														}
														if ($sptm_step_code == '990') {
															if ($sptm_force_close) {
																$sptm_curprocessor_name = "<font color=red>ห้องตัวอย่าง</font>";
															} else {
																$sptm_curprocessor_name = "<font color=green>Deliveried</font>";
															}
														}
													}
													$sptm_create_by = $r_sptm['sptm_create_by'];	
													$sptm_create_by_name = findsqlval("emp_mstr","emp_th_firstname+' '+emp_th_lastname", "emp_user_id", $sptm_create_by,$conn);
													if($sptm_cust_code != "DUMMY") {
														$sptm_cust_name = findsqlval("customer","customer_name1", "customer_number", $sptm_cust_code,$conn);
														if ($sptm_cust_name != "") {
															$sptm_cust_name = '['.$sptm_cust_code.'] ' . $sptm_cust_name;
														}
													}
													else {
														$sptm_cust_name = '<font color=red>[DUMMY]</font> ' .$sptm_cust_dummy;
													}
													
													$sql_qty = " SELECT sum(sptd_qty_order) 'qty_order',sum(sptd_qty_received) 'qty_received',sum(sptd_qty_not_received) 'qty_not_received',sum(sptd_qty_shipment) 'qty_shipment',sum(sptd_qty_delivery) 'qty_delivery',sum(sptd_qty_packing) 'qty_packing',sum(sptd_qty_nogood) 'qty_nogood' FROM sptd_det WHERE sptd_sptm_nbr = '$sptm_nbr'";
													$result_qty = sqlsrv_query($conn, $sql_qty); 
													$row_qty = sqlsrv_fetch_array($result_qty, SQLSRV_FETCH_ASSOC);		
													if ($row_qty) {
														$qty_order = (int)$row_qty['qty_order'];
														$qty_received = (int)$row_qty['qty_received'];
														$qty_not_received = (int)$row_qty['qty_not_received'];
														$qty_shipment = (int)$row_qty['qty_shipment'];
														$qty_delivery = (int)$row_qty['qty_delivery'];
														$qty_packing = (int)$row_qty['qty_packing'];
														$qty_nogood = (int)$row_qty['qty_nogood'];
														$qty_pending = ($qty_order - $qty_received - $qty_not_received - $qty_shipment - $qty_delivery - $qty_packing - $qty_nogood);
													}
													else {
														$qty_order = 0;
														$qty_received = 0;
														$qty_not_received = 0;
														$qty_shipment = 0;
														$qty_delivery = 0;
														$qty_packing = 0;
														$qty_pending = 0;
														$qty_nogood = 0;
													}
													$day_wait = "";
													if (inlist('30',$sptm_step_code)) {
														if ($sptm_approve_date != "") {
															$day_wait = day_diff(date_format($r_sptm['sptm_approve_date'],'Ymd'),date('Ymd')) . ' วัน';
														}
													}
													if (inlist('990',$sptm_step_code)) {
														if ($sptm_receive_complete_date != "") {
															$day_wait = day_diff(date_format($r_sptm['sptm_approve_date'],'Ymd'),date_format($r_sptm['sptm_receive_complete_date'],'Ymd')) . ' วัน';
														}
													}
													$step_img = "";
													$step_icon = "";
													
													switch ($sptm_step_code) {
														case "0": 
															$step_icon = "<img src='../_images/draft.jpg' style='border-radius:50%' width=24>";
															break;
														case "10": 
															$step_icon = "<img src='../_images/draft.jpg' style='border-radius:50%' width=24>";
															break;
														case "20": 
															$step_icon = "<img src='../_images/man.png' style='border-radius:50%' width=24>";
															
															break;
														case "30": 
															$step_icon = "<img src='../_images/work.png' style='border-radius:50%' width=24>";
															$step_img = "<img src='../_images/group.png' style='border-radius:50%' width=24>";
															break;
														case "880": 
															$step_icon = "<img src='../_images/cancel.png' style='border-radius:50%' width=24>";
															break;
														case "890": 
															$step_icon = "<img src='../_images/not-ok1.jpg' style='border-radius:50%' width=24>";
															break;
														case "990": 
															$step_icon = "<img src='../_images/recok.png' style='border-radius:50%' width=24>";
															break;
													}
													$n++;																										
													?>	
													<tr ONMOUSEOVER="this.style.backgroundColor ='white'" ONMOUSEOUT = "this.style.backgroundColor = ''">
														<td class="f_bk8" style="text-align:center;"><?php echo $n+($currentpage-1)*$pagesize; ?>
														<br>
														<?php if (!$sptm_npd) {?>
															<?php if ($sptm_create_by == $user_login) {?>
																<a href="javascript:void(0)" onclick="sptmcopypost('<?php echo $sptm_nbr?>')"><img src="../_images/copy.png" width=18 title="Copy To New Document"></a>
															<?php }?>
														<?php } else {?>
															<font color=red>[NPD]</font>
														<?php }?>
														</td>
														<td class="f_bk8" style="text-align:center;"  title="<?php echo $sptm_copy_refer_text?>">
															<span style='<?php echo $show_printed?>'><?php echo $sptm_nbr; ?></span>
															<?php if (inlist('0,10',$sptm_step_code)) {?>
																<center>
																	<a href='javascript:void(0)' onclick='delsptm("<?php echo $sptm_nbr;?>","<?php echo $currentpage;?>")'>
																		<img src='../_images/del.png' width=24>
																	</a>
																</center>
															<?php }?>
														</td>
														<td class="f_bk8" style="padding-left:5px;"><?php echo $sptm_req_by_name."<br>"."[".$sptm_req_by_sec."]"; ?></td>
														<td class="f_bk8" style="padding-left:5px;"><?php echo $sptm_cust_name."<br><span style='color:red;'>".$sptm_cust_amphur." ".$sptm_cust_province."</span>"; ?></td>
														<td><?php echo dmyty($sptm_req_date); ?></td>
														<td><?php echo dmyty($sptm_expect_receipt_date); ?></td>
														<td><?php echo dmydb($sptm_approve_date,'y'); ?></td>
														<td><?php echo dmydb($sptm_first_print_date,'y'); ?></td>
														<td><?php echo dmydb($sptm_receive_complete_date,'y'); ?></td>
														<td style=""><?php echo $step_icon?> <?php echo $sptm_step_name; ?></td>
														<td style="text-align:center"><?php echo $step_img?> <?php echo $sptm_curprocessor_name; ?></td>
														<td style="text-align:center"><?php echo $day_wait; ?></td>
														<td style="text-align:center;">
															<?php if ($qty_order == ($qty_received + $qty_not_received) and $qty_order > 0) {?>
																<span class="bubbletext" style="background:green;color:white">
															<?php } else {?>
																<span class="bubbletext" style="background:red;color:white">
															<?php }?>
															<?php echo $qty_order; ?>
															</span>
														</td>
														
														<td style="text-align:center">
															<?php if ($qty_received > 0) { ?>
																<span class="bubbletext"><?php echo $qty_received; ?></span>
															<?php }?>
														</td>
														<td style="text-align:center">
															<?php if ($qty_not_received > 0) { ?>
																<span class="bubbletext"><?php echo $qty_not_received; ?></span>
															<?php }?>
														</td>
														<td style="text-align:center">
															<?php if ($qty_shipment > 0) { ?>
																<span class="bubbletext"><?php echo $qty_shipment; ?></span>
															<?php }?>
														</td>
														<td style="text-align:center">
															<?php if ($qty_delivery > 0) { ?>
																<span class="bubbletext"><?php echo $qty_delivery; ?></span>
															<?php }?>
														</td>
														<td style="text-align:center">
															<?php if ($qty_pending > 0) { ?>
																<span class="bubbletext" style='background-color: lightcyan'><?php echo $qty_pending; ?></span>
															<?php }?>
														</td>	
														<td style="text-align:center">
															<?php if ($qty_packing > 0) { ?>
																<span class="bubbletext" style='background-color: yellow'><?php echo $qty_packing; ?></span>
															<?php }?>
														</td>
														<td style="text-align:center">
															<?php if ($qty_nogood > 0) { ?>
																<span class="bubbletext" style='background-color: red;color:white'><?php echo $qty_nogood; ?></span>
															<?php }?>
														</td>
														<td width=2% style="text-align:center">
															<center>
															<a href="javascript:void(0)" onclick="loadresult();window.location.href='sptdmnt.php?sptmnumber=<?php echo encrypt($sptm_nbr, $key);?>&pg=<?php echo $currentpage?>'">
																<img style="border-radius:50%" src='../_images/sptm.png'>
															</a>
															</center>
														</td>
														<td style="text-align:center">
															<?php if($activeid==$sptm_nbr) {echo "<img src='../_images/active-id.png'>";}?>
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
	<form name="frmsptmcopy" method="post" action="../serverside/sptmcopypost.php">
		<input type="hidden" name="action" value="<?php echo md5('sptmcopy'.$user_login)?>">			
		<input type="hidden" name="sptm_nbr">
		<input type="hidden" name="pg" value="<?php echo $currentpage?>">
	</form>	
	<form name="frmdelete" method="post" action="../serverside/sptmpost.php">
		<input type="hidden" name="action" value="<?php echo md5('delete'.$user_login)?>">			
		<input type="hidden" name="sptm_nbr">
		<input type="hidden" name="pg">
	</form>		
	</body>
</html>
