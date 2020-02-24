<?php 
include("../_incs/chksession.php");  
include("../_incs/config.php");	
include("../_incs/funcServer.php");	

set_time_limit(0);
$curdate = date('Ymd');
$action = mssql_escape($_REQUEST['action']);
$activeid = decrypt(mssql_escape($_REQUEST['activeid']), $key);

$in_wpm_tmpsubmit = mssql_escape($_POST["in_wpm_tmpsubmit"]);
$in_wpm_nbr = mssql_escape($_POST["in_wpm_nbr"]);
$in_wpm_date = mssql_escape($_POST["in_wpm_date"]);
$in_wpm_status = mssql_escape($_POST["in_wpm_status"]);
$in_wpm_notshow = $_POST["in_wpm_notshow"];

If ($in_wpm_tmpsubmit == "") {
	$in_wpm_tmpsubmit = $_COOKIE['in_wpm_tmpsubmit'];	
	$in_wpm_nbr = $_COOKIE['in_wpm_nbr'];
	$in_wpm_date = $_COOKIE['in_wpm_date'];
	$in_wpm_status = $_COOKIE['in_wpm_status'];
	$in_wpm_notshow = $_COOKIE['in_wpm_notshow'];
}
else {		
	setcookie("in_wpm_tmpsubmit","",0);
	setcookie("in_wpm_nbr","",0);
	setcookie("in_wpm_date","",0);
	setcookie("in_wpm_status","",0);
	setcookie("in_wpm_notshow","",0);
}
//ใช้เป็นตัวกำหนดค่า default ในการ login ครั้งแรก
if ($user_wpm_first_into == "1") { 
	$in_wpm_notshow = "on";
}
//setcookie("user_wpm_first_into", "0",0, "/");
setcookie("spt_wpm_first_into", "0",0, "/");	
//
if ($in_wpm_nbr != "") {
	if ($criteria != "") { $criteria = $criteria . " AND "; }
	$criteria = $criteria . " wpm_nbr like '%$in_wpm_nbr%'";
}
setcookie("in_wpm_nbr", $in_wpm_nbr,0);
//
if ($in_wpm_date != "") {
	if ($criteria != "") { $criteria = $criteria . " AND "; }
	$in_wpm_date_ymd = ymd($in_wpm_date);
	$criteria = $criteria . " wpm_date like '%$in_wpm_date_ymd%'";
}
setcookie("in_wpm_date", $in_wpm_date,0);
//
if ($in_wpm_status != "") {
	if ($criteria != "") { $criteria = $criteria . " AND "; }
	$criteria = $criteria . " wpm_status_code like '%$in_wpm_status%'";
}
setcookie("in_wpm_status", $in_wpm_status,0);
//
if ($in_wpm_notshow != "") {
	if ($criteria != "") { $criteria = $criteria . " AND "; }
	$criteria = $criteria . " wpm_status_code <> '90' and wpm_status_code <> '80'";	
}
setcookie("in_wpm_notshow", $in_wpm_notshow,0);
//
if ($criteria != "") { $criteria = " AND " . $criteria; }	


$can_wpm = false;
if (inlist($user_role,"CS")) {
	$can_wpm = true;
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
			$("#in_wpm_date").datepicker({
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
		// function setvalue_edit(wpm_nbr,wpm_group_type,wpm_remark) {
			// document.frm_wpm_edit.wpm_nbr.value = wpm_nbr;
			// document.frm_wpm_edit.wpm_group_type.value = wpm_group_type;
			// document.frm_wpm_edit.wpm_remark.value = wpm_remark;
		// }
		function wpmpostform(formname) {
			var errorflag = false;
			var errortxt = "";
			document.getElementById("msghead").innerHTML = "พบข้อผิดผลาดในการบันทึกข้อมูล";
			
			var wpm_group_type = document.forms[formname].wpm_group_type.value;
			if (wpm_group_type == "") {
				if (errortxt!="") {errortxt = errortxt + "<br>";}
				errorflag = true;
				errortxt = errortxt + "กรุณาเลือกการจัดกลุ่มข้อมูล";
			}
			
			if (errorflag ) {			
				alert(errortxt);
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
	$sql_cnt = "SELECT * FROM wpm_mstr WHERE wpm_is_delete = 0 " . $criteria;
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
							<td><img src='../_images/delivery-icon.png' width=32> <span style='font-size:11pt'><b>@ใบเตรียมขึ้นสินค้า</b></span>
								<?php if ($can_wpm) {?>
								<a href="#wpm_add" role="button" style="color:white; text-decoration:none" data-toggle="modal">
									<div class="btn btn-small btn-primary paddingleftandright10" style="margin:auto;">
										<i class="icon-plus icon-white"></i>														
										<span>สร้างใบเตรียมขึ้นสินค้า</span>
									</div>
								</a>
								<?php }?>
							</td>
							<td></td>
						</tr>				
						<tr>
							<td width=100% valign=top>
								<table width="80%" border="0" bgcolor="DarkKhaki">
									<form name="frm" method="POST" autocomplete=OFF action="wpmall.php">
									<input type="hidden" name="in_wpm_tmpsubmit" value="search">
									<input type="hidden" name="action">	
									<input type="hidden" name="pg">
		
									<tr>
										<td style="width:100px;text-align:right" class="f_bk8b">หมายเลขใบเตรียม<font color=red><b>*</b></font>:</td>
										<td style="width:50px">
											<input name="in_wpm_nbr" value="<?php echo $in_wpm_nbr?>" class="inputtext_s" style='color:blue'>												
										</td>
										<td style="width:70px;text-align:right" class="f_bk8b">สถานะ<font color=red><b>*</b></font>:</td>
										<td style="width:120px">
											<select name="in_wpm_status" class="f_bl8" style="width: 100px;margin: auto" >
												<option value="">-- ทั้งหมด --</option>
												<?php 
												$sql_wps = "SELECT wps_status_code,wps_status_name FROM wps_status_mstr order by wps_status_seq";												
												$result_wps_list = sqlsrv_query( $conn,$sql_wps);																													
												while($r_wps_list=sqlsrv_fetch_array($result_wps_list, SQLSRV_FETCH_ASSOC)) {
												?>
													<option  value="<?php echo $r_wps_list['wps_status_code'];?>"
													<?php if (trim($r_wps_list['wps_status_code']) == "$in_wps_status") { echo "selected"; } ?>>
													<?php echo $r_wps_list['wps_status_name'];?></option> 
												<?php } ?>
											</select>											
										</td>
										<td style="width:100px;text-align:right" class="f_bk8b">วันที่สร้างใบเตรียม<font color=red><b>*</b></font>:</td>
										<td style="width:280px;">
											<input name="in_wpm_date" id="in_wpm_date" value="<?php echo $in_wpm_date?>" class="inputtext_s" style='color:blue'>  
											<input name="in_wpm_notshow" type="checkbox" <?php if ($in_wpm_notshow=="on") {echo "checked";}?> class="f_bl8" style="margin:auto"> <span style="color:red"><b>ไม่แสดงรายการที่ขึ้นสินค้าแล้ว </b></span>&nbsp;&nbsp;  
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
												<tr valign="top" style="background-color:#ffff99;" height="25" align="center">
													<th style="width:30px;text-align:center">No</th>
													<th style="width:90px;text-align:center">หมายเลขใบเตรียม</th>
													<th style="width:170px;text-align:center">จัดกลุ่มข้อมูลตาม</th>
													<th style="width:90px;text-align:center">วันที่สร้าง</th>
													<th style="width:90px;text-align:center">วันที่พิมพ์</th>
													<th style="width:250px;text-align:center">ผู้พิมพ์</th>
													<th style="width:90px;text-align:center">สถานะ</th>
													<th style="width:90px;background:green;color:white;text-align:center">ขึ้นได้</th>
													<th style="width:90px;background:red;color:white;text-align:center">ขึ้นไม่ได้</th>
													<th style="width:90px;background:orange;color:white;text-align:center">ยังไม่ขึ้น</th>
													<th style="text-align:right">Action</th>
													<th style="width:30px"></th>
												</tr>
												</thead>   
												<tbody>
												<?php																								
												$n = 0;													
												$sql_wpm = "SELECT wpm.* FROM" .
												" (SELECT ROW_NUMBER() OVER(ORDER BY wpm_nbr) AS rownumber,* FROM wpm_mstr INNER JOIN wps_status_mstr ON wps_status_code = wpm_status_code" .
												" WHERE wpm_is_delete = 0 $criteria) as wpm" .
												" WHERE wpm.rownumber > $start_row and wpm.rownumber <= $start_row+$pagesize";																																																														
												
												$result_wpm = sqlsrv_query( $conn, $sql_wpm);
												while($r_wpm = sqlsrv_fetch_array($result_wpm, SQLSRV_FETCH_ASSOC)) {	
													$wpm_nbr = $r_wpm['wpm_nbr'];
													$wpm_date = $r_wpm['wpm_date'];
													$wpm_group_type = $r_wpm['wpm_group_type'];
													$wpm_remark = $r_wpm['wpm_remark'];
													$wpm_status_code = $r_wpm['wpm_status_code'];
													$wpm_status_name = $r_wpm['wps_status_name'];
													$wpm_printed = $r_wpm['wpm_printed'];
													$wpm_print_by = $r_wpm['wpm_print_by'];
													$wpm_print_by_name = findsqlval("emp_mstr","emp_th_firstname+ ' '+ emp_th_lastname","emp_user_id",$wpm_print_by,$conn);
													$wpm_print_date = $r_wpm['wpm_print_date'];
													$wpm_group_type_text = "";
													if ($wpm_group_type == "A") {
														$wpm_group_type_text = "(A)-ลูกค้า+ทะเบียน";
													}
													if ($wpm_group_type == "B") {
														$wpm_group_type_text = "(B)-วิธีการจัดส่ง";
													}
													if ($wpm_group_type == "C") {
														$wpm_group_type_text = "(C)-วิธีการจัดส่ง+ผู้ขอเบิก";
													}
													$cnt_y = cntwpdstatus($wpm_nbr,"Y",$conn);
													if ($cnt_y == "0") { $cnt_y = ""; }
													$cnt_n = cntwpdstatus($wpm_nbr,"N",$conn);
													if ($cnt_n == "0") { $cnt_n = ""; }
													$cnt_b = cntwpdstatus($wpm_nbr,"",$conn);
													if ($cnt_b == "0") { $cnt_b = ""; }
													
													$n++;																										
													?>													
													<tr ONMOUSEOVER="this.style.backgroundColor ='white'" ONMOUSEOUT = "this.style.backgroundColor = ''">
														<td class="f_bk8" style="text-align:center;"><?php echo $n+($currentpage-1)*$pagesize; ?></td>
														<td class="f_bk8" style="text-align:center;"><?php echo $wpm_nbr; ?></td>
														<td class="f_bk8" style=""><?php echo $wpm_group_type_text; ?></td>
														<td class="f_bk8" style="text-align:center;"><?php echo dmytx($wpm_date); ?></td>
														<td class="f_bk8" style="text-align:center;"><?php echo dmydb($wpm_print_date,'Y'); ?></td>
														<td class="f_bk8" style="text-align:center;"><?php echo $wpm_print_by_name; ?></td>
														<td class="f_bk8" style="text-align:center;">
															<?php if (inlist("10",$wpm_status_code)) { echo "<span style='color:black'>$wpm_status_name</span>"; }?>
															<?php if (inlist("20",$wpm_status_code)) { echo "<span style='color:orange'>$wpm_status_name</span>"; }?>
															<?php if (inlist("30",$wpm_status_code)) { echo "<span style='color:blue'>$wpm_status_name</span>"; }?>
															<?php if (inlist("90",$wpm_status_code)) { echo "<span style='color:green'>$wpm_status_name</span>"; }?>
															<?php if (inlist("80",$wpm_status_code)) { echo "<span style='color:red;text-decoration: line-through'>$wpm_status_name</span>"; }?>
														</td>
														<td style="text-align:center;font-size:10pt;font-weight:bold;color:green"><?php echo $cnt_y?></td>
														<td style="text-align:center;font-size:10pt;font-weight:bold;color:red"><?php echo $cnt_n?></td>
														<td style="text-align:center;font-size:10pt;font-weight:bold;color:orange"><?php echo $cnt_b?></td>
														<td style="text-align:center;">
															<?php if ($can_wpm) {?>
															<a href="javascript:void(0)" onclick="loadresult();window.location.href='wpmmnt.php?wpmnumber=<?php echo encrypt($wpm_nbr, $key);?>&pg=<?php echo $currentpage?>'">
																<span style='border-radius:50%'><img src='../_images/wpm.png'></span>
															</a>
															<?php }?>
														</td>
														<td style="text-align:center">
															<?php if($activeid==$wpm_nbr) {echo "<img src='../_images/active-id.png'>";}?>
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
	<div id="wpm_add" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">																																																																				
		<form name="frm_wpm_add" autocomplete=OFF method="post" action="../serverside/wpmpost.php">
			<input name="action" type=hidden value="<?php echo md5('wpm_add'.$user_login)?>">
			<input name="pg" type="hidden" value="<?php echo $pg?>">	
			
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h3 id="myModalLabel">สร้างใบเตรียมขึ้นสินค้า ::</h3>
			</div>
			<!--div class="modal-body"-->
			<div class="">
				<table class="table table-c	ondensed table-responsive">	
				<tbody>																	
					<tr>
						<td style="text-align:right; vertical-align: middle;"><b>วันที่สร้างใบเตรียม:</b></td>
						<td><?php echo date("d/m/Y");?></td>
					</tr>
					<tr>
						<td style="text-align:right; vertical-align: middle;"><b>จัดกลุ่มข้อมูล:</b></td>
						<td>
							<select name="wpm_group_type" style="width:250px">
								<option value="">--เลือก--</option>
								<option value="A">(A)-ตาม ลูกค้า+ทะเบียนรถ</option>
								<option value="B">(B)-ตาม วิธีการจัดสส่ง</option>
								<option value="C">(C)-ตาม วิธีการจัดส่ง+ผู้ขอเบิก</option>
							</select>
						</td>
					</tr>
					<tr>
						<td style="text-align:right; vertical-align: middle;"><b>หมายเหตุ:</b></td>
						<td>
							<input type="text" name="wpm_remark" class="inputtext_s" style="width: 250px;" maxlength="100">
						</td>
					</tr>
				</tbody>
				</table>					
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success fileinput-button paddingleftandright10 margintop20 marginleft20" data-toggle="modal" onclick="wpmpostform('<?php echo "frm_wpm_add";?>')">
					<i class="icon-check icon-white"></i>
					<span>Save</span>
				</button>											
			</div>												
		</form>																																																			
	</div>
	
	
	
	<form name="frm_wpm_del" method="post" action="../serverside/wpmpost.php">
		<input type="hidden" name="action" value="<?php echo md5('wpm_del'.$user_login)?>">			
		<input type="hidden" name="wpm_nbr">
		<input type="hidden" name="pg">
	</form>	
	</body>
</html>
