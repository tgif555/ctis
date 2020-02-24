<?php 
include("../_incs/chksession.php");  
include("../_incs/config.php");	
include("../_incs/funcServer.php");	

set_time_limit(0);
$curdate = date('Ymd');
$action = mssql_escape($_REQUEST['action']);
$activeid = decrypt(mssql_escape($_REQUEST['activeid']), $key);

$in_ivm_tmpsubmit = mssql_escape($_POST["in_ivm_tmpsubmit"]);
$in_ivm_nbr = mssql_escape($_POST["in_ivm_nbr"]);
$in_ivm_wpm_nbr = mssql_escape($_POST["in_ivm_wpm_nbr"]);
$in_ivm_status = mssql_escape($_POST["in_ivm_status"]);
$in_ivm_date = mssql_escape($_POST["in_ivm_date"]);
$in_ivm_notshow = $_POST["in_ivm_notshow"];

If ($in_ivm_tmpsubmit == "") {
	$in_ivm_tmpsubmit = $_COOKIE['in_ivm_tmpsubmit'];	
	$in_ivm_nbr = $_COOKIE['in_ivm_nbr'];
	$in_ivm_wpm_nbr = $_COOKIE['in_ivm_wpm_nbr'];
	$in_ivm_status = $_COOKIE['in_ivm_status'];
	$in_ivm_date = $_COOKIE['in_ivm_date'];
	$in_ivm_notshow = $_COOKIE['in_ivm_notshow'];
}
else {		
	setcookie("in_ivm_tmpsubmit","",0);
	setcookie("in_ivm_nbr","",0);
	setcookie("in_ivm_wpm_nbr","",0);
	setcookie("in_ivm_status","",0);
	setcookie("in_ivm_date","",0);
	setcookie("in_ivm_notshow","",0);
}
//ใช้เป็นตัวกำหนดค่า default ในการ login ครั้งแรก
if ($user_ivm_first_into == "1") { 
	$in_ivm_notshow = "on";
}
setcookie("spt_ivm_first_into", "0",0, "/");	
//
if ($in_ivm_nbr != "") {
	if ($criteria != "") { $criteria = $criteria . " AND "; }
	$criteria = $criteria . " ivm_nbr like '%$in_ivm_nbr%'";
}
setcookie("in_ivm_nbr", $in_ivm_nbr,0);
//
if ($in_ivm_wpm_nbr != "") {
	if ($criteria != "") { $criteria = $criteria . " AND "; }
	$criteria = $criteria . " wpm_nbr like '%$in_ivm_wpm_nbr%'";
}
setcookie("in_ivm_wpm_nbr", $in_ivm_wpm_nbr,0);
//
if ($in_ivm_date != "") {
	if ($criteria != "") { $criteria = $criteria . " AND "; }
	$in_ivm_date_ymd = ymd($in_ivm_date);
	$criteria = $criteria . " ivm_date like '%$in_ivm_date_ymd%'";
}
setcookie("in_ivm_date", $in_ivm_date,0);
//
if ($in_ivm_status != "") {
	if ($criteria != "") { $criteria = $criteria . " AND "; }
	$criteria = $criteria . " ivm_status_code like '%$in_ivm_status%'";
}
setcookie("in_ivm_status", $in_ivm_status,0);
//
if ($in_ivm_notshow != "") {
	if ($criteria != "") { $criteria = $criteria . " AND "; }
	$criteria = $criteria . " ivm_status_code <> '90' and ivm_status_code <> '80'";	
}
setcookie("in_ivm_notshow", $in_ivm_notshow,0);
//
if ($criteria != "") { $criteria = " WHERE " . $criteria; }	
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
			$("#in_ivm_date").datepicker({
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
		function wpmpostform(formname) {
			var errorflag = false;
			var errortxt = "";
			document.getElementById("msghead").innerHTML = "พบข้อผิดผลาดในการบันทึกข้อมูล";
			
			if (errorflag ) {			
				document.getElementById("msgbody").innerHTML = "<font color=red>" + errortxt + "</font>";
				$("#myModal").modal("show");
			}
			else {				
				document.forms[formname].submit();									
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
			
			loadresult()
			document.frm.pg.value=mypage;
			document.frm.submit();
		}	
	</script>	
</head>
<body>		
	<?php				
	$sql_cnt = "SELECT * FROM ivm_mstr " . 
		" INNER JOIN wpm_mstr ON wpm_nbr = ivm_wpm_nbr" .
		" INNER JOIN ivs_status_mstr ON ivs_status_code = ivm_status_code".
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
							<td><img src='../_images/delivery-icon.png' width=32><span style='font-size:11pt'><b>@ใบส่งของ</b></span>
							</td>
							<td></td>
						</tr>				
						<tr>
							<td width=100% valign=top>
								<table width="100%" border="0" bgcolor="DarkKhaki">
									<form name="frm" method="POST" autocomplete=OFF action="ivmall.php">
									<input type="hidden" name="in_ivm_tmpsubmit" value="search">
									<input type="hidden" name="action">	
									<input type="hidden" name="pg">
		
									<tr>
										<td style="width:70px;text-align:right" class="f_bk8b">ใบส่งสินค้า<font color=red><b>*</b></font>:</td>
										<td style="width:50px">
											<input name="in_ivm_nbr" value="<?php echo $in_ivm_nbr?>" class="inputtext_s" style='color:blue'>												
										</td>
										<td style="width:90px;text-align:right" class="f_bk8b">ใบเตรียมสินค้า<font color=red><b>*</b></font>:</td>
										<td style="width:50px">
											<input name="in_ivm_wpm_nbr" value="<?php echo $in_ivm_wpm_nbr?>" class="inputtext_s" style='color:blue'>												
										</td>
										<td style="width:100px;text-align:right" class="f_bk8b">สถานะใบส่งสินค้า<font color=red><b>*</b></font>:</td>
										<td style="width:100px">
											<select name="in_ivm_status" class="f_bl8" style="width: 100px;margin: auto" >
												<option value="">-- ทั้งหมด --</option>
												<?php 
												$sql_ivs = "SELECT ivs_status_code,ivs_status_name FROM ivs_status_mstr order by ivs_status_seq";												
												$result_ivs_list = sqlsrv_query( $conn,$sql_ivs);																													
												while($r_ivs_list=sqlsrv_fetch_array($result_ivs_list, SQLSRV_FETCH_ASSOC)) {
												?>
													<option  value="<?php echo $r_ivs_list['ivs_status_code'];?>"
													<?php if (trim($r_ivs_list['ivs_status_code']) == "$in_ivm_status") { echo "selected"; } ?>>
													<?php echo $r_ivs_list['ivs_status_name'];?></option> 
												<?php } ?>
											</select>											
										</td>
										<td style="width:120px;text-align:right" class="f_bk8b">วันที่สร้างใบส่งสินค้า<font color=red><b>*</b></font>:</td>
										<td style="width:350px">
											<input name="in_ivm_date" id="in_ivm_date" value="<?php echo $in_ivm_date?>" class="inputtext_s" style='color:blue'>  
											<input name="in_ivm_notshow" type="checkbox" <?php if ($in_ivm_notshow=="on") {echo "checked";}?> class="f_bl8" style="margin:auto"> <span style="color:red"><b>ไม่แสดงรายการที่ส่งแล้วและยกเลิก</b></span>&nbsp;&nbsp;  
										</td>
									
										<td style="">
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
												<tr valign="top" style="background-color:green;color:white" height="25" align="center">
													<th style="width:50px;text-align:center">No</th>
													<th style="width:70px;text-align:center">ใบส่งสินค้า</th>
													<th style="width:60px;text-align:center">วันที่</th>
													<th style="width:80px;text-align:center">ใบเตรียมสินค้า</th>
													<th style="width:90px;text-align:center">ทะเบียนรถ</th>
													<th style="width:350px;text-align:center">ชื่อลูกค้า</th>
													<th style="width:120px;text-align:center">อำเภอ</th>
													<th style="width:120px;text-align:center">จังหวัด</th>
													<th style="width:120px;text-align:center">Package</th>
													<th style="width:120px;text-align:center">น้ำหนัก (KG)</th>
													<th style="width:90px;text-align:center">วันที่พิมพ์</th>
													<th style="width:100px;text-align:center">ผู้พิมพ์</th>
													<th style="width:90px;text-align:center">สถานะ</th>
													<th style="text-align:right">Action</th>
													<th style="width:30px"></th>
												</tr>
												</thead>   
												<tbody>
												<?php																								
												$n = 0;													
												$sql_ivm = "SELECT ivm.* FROM" .
												" (SELECT ROW_NUMBER() OVER(ORDER BY ivm_nbr) AS rownumber,* FROM ivm_mstr" .
												" INNER JOIN wpm_mstr ON wpm_nbr = ivm_wpm_nbr" .
												" INNER JOIN ivs_status_mstr ON ivs_status_code = ivm_status_code".
												" $criteria) as ivm" .
												" WHERE ivm.rownumber > $start_row and ivm.rownumber <= $start_row+$pagesize";																																																														
												
												$result_ivm = sqlsrv_query( $conn, $sql_ivm);
												while($r_ivm = sqlsrv_fetch_array($result_ivm, SQLSRV_FETCH_ASSOC)) {	
													$ivm_nbr = $r_ivm['ivm_nbr'];
													$ivm_date = $r_ivm['ivm_date'];
													$ivm_wpm_nbr = $r_ivm['ivm_wpm_nbr'];
													$ivm_customer_number = $r_ivm['ivm_customer_number'];
													$ivm_customer_dummy = $r_ivm['ivm_customer_dummy'];
													$ivm_customer_type = $r_ivm['ivm_customer_type'];
													$ivm_customer_amphur = $r_ivm['ivm_customer_amphur'];
													$ivm_customer_province = $r_ivm['ivm_customer_province'];
													$ivm_transport_car_nbr = $r_ivm['ivm_transport_car_nbr'];
													$ivm_transport_tspm_other = $r_ivm['ivm_transport_tspm_other'];
													$ivm_transport_ref_no = $r_ivm['ivm_transport_ref_no'];
													$ivm_transport_driver_name = $r_ivm['ivm_transport_driver_name'];
													$ivm_transport_driver_tel = $r_ivm['ivm_transport_driver_tel'];
													$ivm_transport_cmmt = $r_ivm['ivm_transport_cmmt'];
													$ivm_printed = $r_ivm['ivm_printed'];
													$ivm_print_by = $r_ivm['ivm_print_by'];
													$ivm_print_date = $r_ivm['ivm_print_date'];
													$ivm_print_cnt = $r_ivm['ivm_print_cnt'];
													$ivm_status_code = $r_ivm['ivm_status_code'];
													$ivm_status_name = $r_ivm['ivs_status_name'];
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
													$ivd_cnt = cntivddet($ivm_nbr,$conn);
													
													$ivm_weight = sumdlvmweight($ivm_nbr,$conn);
													
													$ivm_weight_diff = $ivm_weight - (int) $ivm_weight;
													if ($ivm_weight_diff>0) {$ivm_weight = number_format($ivm_weight,2);}
													else {$ivm_weight = number_format($ivm_weight,0);}
		
													$n++;																										
													?>						
													<tr ONMOUSEOVER="this.style.backgroundColor ='white'" ONMOUSEOUT = "this.style.backgroundColor = ''">
														<td class="f_bk8" style="text-align:center;"><?php echo $n+($currentpage-1)*$pagesize; ?></td>
														<td class="f_bk8" style="text-align:center"><?php echo $ivm_nbr; ?></td>
														<td class="f_bk8" style="text-align:center"><?php echo dmytx($ivm_date); ?></td>
														<td class="f_bk8" style="text-align:center"><?php echo $ivm_wpm_nbr; ?></td>
														<td class="f_bk8" style=""><?php echo $ivm_transport_car_nbr; ?></td>
														<td class="f_bk8" style=""><?php echo $ivm_customer_name; ?></td>
														<td class="f_bk8" style=""><?php echo $ivm_customer_amphur; ?></td>
														<td class="f_bk8" style=""><?php echo $ivm_customer_province; ?></td>
														<td class="f_bk8" style="text-align:center"><?php echo $ivd_cnt; ?></td>
														<td class="f_bk8" style="text-align:center"><?php echo $ivm_weight; ?></td>
														<td class="f_bk8" style="text-align:center"><?php echo dmydb($ivm_print_date,'Y'); ?></td>
														<td class="f_bk8" style=""><?php echo $ivm_print_by_name; ?></td>
														<td class="f_bk8" style="text-align:center">
															<?php if (inlist("10",$ivm_status_code)) { echo "<span style='color:orange'>$ivm_status_name</span>"; }?>
															<?php if (inlist("20",$ivm_status_code)) { echo "<span style='color:blue'>$ivm_status_name</span>"; }?>
															<?php if (inlist("90",$ivm_status_code)) { echo "<span style='color:green'>$ivm_status_name</span>"; }?>
															<?php if (inlist("80",$ivm_status_code)) { echo "<span style='color:red;text-decoration: line-through'>$ivm_status_name</span>"; }?>
														</td>										
														<td style="text-align:right;">
															<a href="javascript:void(0)" onclick="loadresult();window.location.href='ivmmnt.php?ivmnumber=<?php echo encrypt($ivm_nbr, $key);?>&pg=<?php echo $currentpage?>'">
																<span style='border-radius:50%'><img src='../_images/ivm.png'></span>
															</a>
														</td>
														<td style="text-align:center">
															<?php if($activeid==$ivm_nbr) {echo "<img src='../_images/active-id.png'>";}?>
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
	<form name="frm_ivm_del" method="post" action="../serverside/wpmpost.php">
		<input type="hidden" name="action" value="<?php echo md5('ivm_del'.$user_login)?>">			
		<input type="hidden" name="ivm_nbr">
		<input type="hidden" name="pg">
	</form>	
	</body>
</html>
