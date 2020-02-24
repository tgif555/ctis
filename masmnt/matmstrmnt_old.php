<?php
include("../_incs/acunx_metaheader.php");
//include("../_incs/chksession.php");  
include("../_incs/config.php");	
include("../_incs/funcServer.php");	
include("../_incs/acunx_cookie_var.php");
include "../_incs/acunx_csrf_var.php";

if (($_SERVER['REQUEST_METHOD'] == 'POST')) {
	if (!matchToken($csrf_key,$user_login)) {
		echo "System detect CSRF attack!!";
		exit;
	}
}

$params = array();
$activeid = html_escape($_REQUEST['activeid']);	

$curdate = date('Ymd');
$action = html_escape($_REQUEST["action"]);
$activeid = html_escape(decrypt($_REQUEST["activeid"],$key));
$edit_mat_code = html_escape(decrypt($_REQUEST["edit_mat_code"],$key));

$allow_admin = false;
if (!inlist($user_role,"ADMIN")) {
	$path = "../ctisbof/authorize.php"; 
	//echo "<meta http-equiv=\"refresh\" content=\"0;URL=".$path."\" />";
}
else {
	$allow_admin = true;
}
$allow_admin = true;


$in_mat_tmpsubmit = html_escape($_POST["in_mat_tmpsubmit"]);
$in_mat_code = html_escape($_POST["in_mat_code"]);
$in_mat_name = html_escape($_POST["in_mat_name"]);
$in_mat_group = html_escape($_POST["in_mat_group"]);

If ($in_mat_tmpsubmit == "") {
	$in_mat_tmpsubmit = html_escape($_COOKIE['in_mat_tmpsubmit']);
	$in_mat_code = html_escape($_COOKIE['in_mat_code']);	
	$in_mat_name = html_escape($_COOKIE['in_mat_name']);	
	$in_mat_group = html_escape($_COOKIE['in_mat_group']);
}
else {		
	setcookie("in_mat_tmpsubmit","",0,$ck_path,$ck_dom,$ck_secure,$ck_httponly);
	setcookie("in_mat_code","",0,$ck_path,$ck_dom,$ck_secure,$ck_httponly);	
	setcookie("in_mat_name","",0,$ck_path,$ck_dom,$ck_secure,$ck_httponly);	
	setcookie("in_mat_group","",0,$ck_path,$ck_dom,$ck_secure,$ck_httponly);	
}
if ($in_mat_code != "") {
	if ($criteria != "") { $criteria = $criteria . " AND "; }
	array_push($params, $in_mat_code);
	$criteria = $criteria . " mat_code like '%'+?+'%'";
}
setcookie("in_mat_code", $in_mat_code,0,$ck_path,$ck_dom,$ck_secure,$ck_httponly);
if ($in_mat_name != "") {
	if ($criteria != "") { $criteria = $criteria . " AND "; }
	array_push($params, $in_mat_name);
	array_push($params, $in_mat_name);
	$criteria = $criteria . " (mat_th_name like '%'+?+'%' OR mat_en_name like '%'+?+'%')";
}
setcookie("in_mat_name", $in_mat_name,0,$ck_path,$ck_dom,$ck_secure,$ck_httponly);

if ($in_mat_group != "") {
	if ($criteria != "") { $criteria = $criteria . " AND "; }
	array_push($params, $in_mat_group);
	array_push($params, $in_mat_group);
	$criteria = $criteria . " (mat_mag_code like '%'+?+'%' OR mag_name like '%'+?+'%')";
}
setcookie("in_mat_group", $in_mat_group,0,$ck_path,$ck_dom,$ck_secure,$ck_httponly);

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
	<link href="../_images/sampletiles.ico" rel="shortcut icon" />
	<link href="../_libs/css/_webstyle.css" type="text/css" rel="stylesheet">
	<link href="../_libs/css/bootstrap.css" rel="stylesheet">
	<link href="../_libs/css/bootstrap-responsive.css" rel="stylesheet">
	<link href="../_libs/datepicker/jquery-ui.css" rel="stylesheet" media="all" type="text/css" />
	<link href="../_libs/datepicker/jquery-ui-timepicker-addon.css" rel="stylesheet" media="all" type="text/css" />
	<link href="../_libs/css/cisbof.css" rel="stylesheet">		
	<script src="../_libs/js/jquery-2.1.4.min.js"></script>
	<script src="../_libs/js/bootstrap.min.js"></script>
	<script src="../_libs/js/CalendarPopup.js"></script>
	<script type="text/javascript" src="../_libs/js/cisbof.js"></script>		
	<script type="text/javascript" src="../_libs/datepicker/jquery-ui.min.js"></script>
	<script type="text/javascript" src="../_libs/datepicker/jquery-ui-timepicker-addon.js"></script>
	<script type="text/javascript" src="../_libs/datepicker/jquery-ui-sliderAccess.js"></script>
	<script src="../_libs/js/bootbox.min.js"></script>	
		
	<script language="javascript">			
		function showdata() {													
			document.frm.submit();												
		}
		function matpostform(formname) {
			
			var errorflag = false;
			var errortxt = "";
			document.getElementById("msghead").innerHTML = "พบข้อผิดผลาดในการบันทึกข้อมูล";		
			var mat_code = document.forms[formname].mat_code.value;
			var mat_th_name = document.forms[formname].mat_th_name.value;
						
			var mat_en_name = document.forms[formname].mat_en_name.value;	
			
			var mat_mag_code = document.forms[formname].mat_mag_code.value;
			var mat_unit_code = document.forms[formname].mat_unit_code.value;
			
			var mat_customer_unit_cost = document.forms[formname].mat_customer_unit_cost.value;
			var mat_customer_unit_price = document.forms[formname].mat_customer_unit_price.value;
			var mat_contractor_unit_cost = document.forms[formname].mat_contractor_unit_cost.value;
			var mat_contractor_unit_price = document.forms[formname].mat_contractor_unit_price.value;
			var mat_standard_unit_cost = document.forms[formname].mat_standard_unit_cost.value;
			var mat_standard_unit_price = document.forms[formname].mat_standard_unit_price.value;
			var mat_active = document.forms[formname].mat_active.value;
			
			if (formname == "frm_mat_add") {
				if (mat_code=="") {
					if (errortxt!="") {errortxt = errortxt + "<br>";}
					errorflag = true;
					errortxt = errortxt + "กรุณาระบุ รหัสสินค้า";				
				}
				else {
					var xhttp = new XMLHttpRequest();
					xhttp.onreadystatechange = function() {								
						if (xhttp.readyState == 4 && xhttp.status == 200) {							
							if (xhttp.responseText == 1) {							
								if (errortxt!="") {errortxt = errortxt + "<br>";}
								errorflag = true;
								errortxt = errortxt + "รหัสบัญชีที่ระบุมีในระบบแล้ว";
							}									
						}
					}
					xhttp.open("POST", "../_chk/chkmatcodeexist.php",false);
					xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
					xhttp.setRequestHeader("Cache-Control", "no-cache, must-revalidate");
					xhttp.setRequestHeader("Pragma", "no-cache");
					xhttp.setRequestHeader("Expires", "Sat, 26 Jul 1997 05:00:00 GMT");				
					xhttp.send("mat_code="+mat_code);					
				}
			}		
			
			if (mat_th_name == "") {
				if (errortxt!="") {errortxt = errortxt + "<br>";}
				errorflag = true;
				errortxt = errortxt + "กรุณาระบุ ชื่อสินค้าภาษาไทย";				
			}
			
			if (mat_en_name == "") {
				if (errortxt!="") {errortxt = errortxt + "<br>";}
				errorflag = true;
				errortxt = errortxt + "กรุณาระบุ ชื่อสินค้าภาษาอังกฤษ";				
			}
			
			if (mat_mag_code=="") {
				if (errortxt!="") {errortxt = errortxt + "<br>";}
				errorflag = true;
				errortxt = errortxt + "กรุณาระบุ กลุ่มสินค้า";	
			}
			if (mat_unit_code=="") {
				if (errortxt!="") {errortxt = errortxt + "<br>";}
				errorflag = true;
				errortxt = errortxt + "กรุณาระบุ หน่วยสินค้า";	
			}
			//
			if (mat_customer_unit_cost == "") {
				if (errortxt!="") {errortxt = errortxt + "<br>";}
				errorflag = true;
				errortxt = errortxt + "กรุณาระบุต้นทุนต่อหน่วยในส่วนของลูกค้า";
			}
			else {
				if (!isnumeric(mat_customer_unit_cost)) {
					if (errortxt!="") {errortxt = errortxt + "<br>";}
					errorflag = true;
					errortxt = errortxt + "ต้นทุนต่อหน่วยในส่วนของลูกค้าที่ระบุต้องเป็นตัวเลขเท่านั้น";
				}
			}
			//
			if (mat_customer_unit_price == "") {
				if (errortxt!="") {errortxt = errortxt + "<br>";}
				errorflag = true;
				errortxt = errortxt + "กรุณาระบุราคาต่อหน่วยในส่วนของลูกค้า";
			}
			else {
				if (!isnumeric(mat_customer_unit_price)) {
					if (errortxt!="") {errortxt = errortxt + "<br>";}
					errorflag = true;
					errortxt = errortxt + "ราคาต่อหน่วยในส่วนของลูกค้าที่ระบุต้องเป็นตัวเลขเท่านั้น";
				}
			}
			//
			if (mat_contractor_unit_cost == "") {
				if (errortxt!="") {errortxt = errortxt + "<br>";}
				errorflag = true;
				errortxt = errortxt + "กรุณาระบุต้นทุนต่อหน่วยในส่วนของผู้รับเหมา";
			}
			else {
				if (!isnumeric(mat_contractor_unit_cost)) {
					if (errortxt!="") {errortxt = errortxt + "<br>";}
					errorflag = true;
					errortxt = errortxt + "ต้นทุนต่อหน่วยในส่วนของผู้รับเหมาที่ระบุต้องเป็นตัวเลขเท่านั้น";
				}
			}
			//
			if (mat_contractor_unit_price == "") {
				if (errortxt!="") {errortxt = errortxt + "<br>";}
				errorflag = true;
				errortxt = errortxt + "กรุณาระบุราคาต่อหน่วยในส่วนของผู้รับเหมา";
			}
			else {
				if (!isnumeric(mat_contractor_unit_price)) {
					if (errortxt!="") {errortxt = errortxt + "<br>";}
					errorflag = true;
					errortxt = errortxt + "ราคาต่อหน่วยในส่วนของผู้รับเหมาที่ระบุต้องเป็นตัวเลขเท่านั้น";
				}
			}
			//
			if (mat_standard_unit_cost == "") {
				if (errortxt!="") {errortxt = errortxt + "<br>";}
				errorflag = true;
				errortxt = errortxt + "กรุณาระบุราคาต่อหน่วยในส่วนของราคามาตรฐาน";
			}
			else {
				if (!isnumeric(mat_standard_unit_cost)) {
					if (errortxt!="") {errortxt = errortxt + "<br>";}
					errorflag = true;
					errortxt = errortxt + "ต้นทุนต่อหน่วยในส่วนของราคามาตรฐานที่ระบุต้องเป็นตัวเลขเท่านั้น";
				}
			}
			//
			if (mat_standard_unit_price == "") {
				if (errortxt!="") {errortxt = errortxt + "<br>";}
				errorflag = true;
				errortxt = errortxt + "กรุณาระบุต้นทุนต่อหน่วยในส่วนของราคามาตรฐาน";
			}
			else {
				if (!isnumeric(mat_standard_unit_price)) {
					if (errortxt!="") {errortxt = errortxt + "<br>";}
					errorflag = true;
					errortxt = errortxt + "ราคาต่อหน่วยในส่วนของราคามาตรฐานที่ระบุต้องเป็นตัวเลขเท่านั้น";
				}
			}
			
			if (mat_active=="") {
				if (errortxt!="") {errortxt = errortxt + "<br>";}
				errorflag = true;
				errortxt = errortxt + "กรุณาระบุสถานะสินค้า";				
			}
				
			if (errorflag ) {			
				document.getElementById("msgbody").innerHTML = "<font color=red>" + errortxt + "</font>";
				$("#myModal").modal("show");
			}
			else {				
				document.forms[formname].submit();
				
			}	
		}
		function setvalue_frm_mat_edit(
			mat_code,mat_th_name,mat_en_name,mat_mag_code,mat_unit_code,mat_detail,
			mat_customer_unit_cost,mat_customer_unit_price,
			mat_contractor_unit_cost,mat_contractor_unit_price,
			mat_standard_unit_cost,mat_standard_unit_price,
			mat_active) {
			alert(mat_detail);
			document.frm_mat_edit.mat_code.value = mat_code;
			document.frm_mat_edit.mat_th_name.value = mat_th_name;
			document.frm_mat_edit.mat_en_name.value = mat_en_name;
			document.frm_mat_edit.mat_mag_code.value = mat_mag_code;
			document.frm_mat_edit.mat_unit_code.value = mat_unit_code;
			document.frm_mat_edit.mat_detail.value = mat_detail;
			document.frm_mat_edit.mat_customer_unit_cost.value = mat_customer_unit_cost;
			document.frm_mat_edit.mat_customer_unit_price.value = mat_customer_unit_price;
			document.frm_mat_edit.mat_contractor_unit_cost.value = mat_contractor_unit_cost;
			document.frm_mat_edit.mat_contractor_unit_price.value = mat_contractor_unit_price;
			document.frm_mat_edit.mat_standard_unit_cost.value = mat_standard_unit_cost;
			document.frm_mat_edit.mat_standard_unit_price.value = mat_standard_unit_price;
			document.frm_mat_edit.mat_active.value = mat_active;
			$('#div_mat_code').html(mat_code);
		}
		function delac(ac_code,pg) {
			var errorflag = false;
			var errortxt = "";
			document.getElementById("msghead").innerHTML = "พบข้อผิดผลาดในการบันทึกข้อมูล";
			
			var xhttp = new XMLHttpRequest();
			xhttp.onreadystatechange = function() {								
				if (xhttp.readyState == 4 && xhttp.status == 200) {								
					if (xhttp.responseText == 1) {							
						if (errortxt!="") {errortxt = errortxt + "<br>";}
						errorflag = true;
						errortxt = errortxt + "ไม่อนุญาติให้ลบรหัสบัญชีนี้เนื่องจากมีการใช้งานอยู่";
					}									
				}
			}
			xhttp.open("POST", "../_chk/chkacmstrcodeused.php",false);
			xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			xhttp.setRequestHeader("Cache-Control", "no-cache, must-revalidate");
			xhttp.setRequestHeader("Pragma", "no-cache");
			xhttp.setRequestHeader("Expires", "Sat, 26 Jul 1997 05:00:00 GMT");				
			xhttp.send("ac_code="+ac_code);
			
			if (errorflag ) {			
				document.getElementById("msgbody").innerHTML = "<font color=red>" + errortxt + "</font>";
				$("#myModal").modal("show");
			}
			else {
				if(confirm('ท่านต้องการลบข้อมูลการนี้ ไช่หรือไม่ ?')) {
					document.frmdelete.ac_code.value = ac_code;
					document.frmdelete.pg.value = pg;
					document.frmdelete.submit();
				}
			}
		}		
		function gotopage(mypage) {							
			document.frm.pg.value=mypage;
			document.frm.submit();
		}
	</script>	
	<style>
		.paging {      
		  border-radius:20%;
		  background-color:#a6a6a6;
		  padding:4px 4px 4px 4px;
		  color:#fff;
		  text-decoration:none;	  	  
		}
		.paging:hover{
			background-color:#1e8d12;
			color:#fff;
		}
		.pageselected {      
		  border-radius:20%;
		  background-color:#1e8d12;
		  padding:4px 4px 4px 4px;
		  color:#fff;
		  text-decoration:none;	  	  
		}
		.modal {
			margin-top:-320px;
		}
	</style>
</head>
<body >		
	<?php	
	$sqlmax = "SELECT * FROM mat_mstr $criteria";
	$options = array("Scrollable" => 'keyset');
	$result = sqlsrv_query( $conn,$sqlmax,$params,$options);
	$max = sqlsrv_num_rows($result);	 	
	
	$pagesize = 15;
	$totalrow = $max;
	$totalpage = ($totalrow/$pagesize) - (int)($totalrow/$pagesize);
	if ($totalpage > 0) {
		$totalpage = ((int)($totalrow/$pagesize)) + 1;
	} else {
		$totalpage = (int)$totalrow/$pagesize;
	}					
	if (html_escape($_REQUEST["pg"])=="") {
		$currentpage = 1;	
		$end_row = ($currentPage * $pagesize) - 1;
		if ($end_row > ($totalrow - 1)) { $end_row = $totalrow - 1; }
		$start_row = 0;
	} else {
		$currentpage = html_escape($_REQUEST["pg"]);
		if ((int)$currentpage < 1) { $currentpage = 1; }
		if ((int)$currentpage > (int)$totalpage) { $currentpage = $totalpage; }
		$end_row = ($currentpage * $pagesize) - 1;
		$start_row = $end_row - $pagesize + 1;
		if ($end_row > ($totalrow - 1)) { $end_row = $totalrow - 1; }					
	}
	
	
	$maxpage = 5; //-- ใส่ได้เฉพาะเลขคี่เท่านั้น
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
	<div>			
		<TABLE width="100%" border=0 height="100%" align=center cellpadding=0 cellspacing=0>
			<tr><td><?php include("../menu.php"); ?></td></tr>				
			<tr>
				<td height="100%" align=center valign=top>
					<table border=0 width="100%" cellpadding="0" cellspacing="0">				
						<tr bgcolor="#5495D5" height=5><td><h5><font color=white>++Material Master</font></h5></td>
						<td height=45>														
							<div class="btn btn-small btn-primary paddingleftandright10 pull-right" style="margin-top:5px; margin-bottom:10px;" >														
								<i class=" icon-white icon-plus"></i>
								<a href="#div_frm_mat_add" role="button" style="color:white; text-decoration:none" data-toggle="modal">New Material</a>
							</div>
						</td>
						</tr>				
						<tr>
							<td valign=top colspan=2>
								<form name="frm" method="POST" autocomplete=OFF action="matmstrmnt.php">
								<input type="hidden" name="csrf_securecode" value="<?php echo $csrf_securecode?>">
								<input type="hidden" name="csrf_token" value="<?php echo md5($csrf_token)?>">
								<input type="hidden" name="in_mat_tmpsubmit" value="search">
								<input type="hidden" name="action">	
								<input type="hidden" name="pg">
								<table class="box_gy" width="100%" border=0 bgcolor=DarkKhaki>
									<tr>
										<td class="f_bk8b" width=7%>&nbsp;&nbsp;Maetial Code:<font color=red><b>*</b></font>:</td>
										<td width=5%>
											<input name="in_mat_code" value="<?php echo $in_mat_code?>" style="width:80px;margin:auto" class="f_bl8" style="width:150px">												
										</td>
										<td class="f_bk8b" width=7%>&nbsp;&nbsp;Description<font color=red><b>*</b></font>:</td>
										<td width=5%>
											<input name="in_mat_name" value="<?php echo $in_mat_name?>" style="width:150px;margin:auto" class="f_bl8" style="width:150px">												
										</td>	
										<td class="f_bk8b" width=15%>&nbsp;&nbsp;Material Group<font color=red><b>*</b></font>:</td>
										<td width=5%>
											<input name="in_mat_group" value="<?php echo $in_mat_group?>" style="width:150px;margin:auto" class="f_bl8" style="width:150px">										
										</td>
										<td>																						
											<input type="image" name="showdata" id="showdata" src="../_images/find_25x25.png" alt="Submit" onclick="showdata()">											
										</td>
									</tr>									
								</table>
								</form>
							</td>
						</tr>
						
						<tr>
							<td width=100% colspan=2>
							<table width="99%" border="0" align="center" cellpadding="1" cellspacing="1" bgcolor="#cccccc">
								<tr>
									<td width=30%>
									(Total <font color=red><?php echo $totalpage;?></font>&nbsp;Pages&nbsp;<font color=red><?php echo $totalrow;?></font>&nbsp;items)								
									<b>Jump To Page:</b>&nbsp;<input name="jumto" class="f_bk8" style="width: 30px;">&nbsp;<input name="go" type="button" class="paging" value="go" onclick="gotopage(document.all.jumto.value)">
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
										<td bgcolor="#FFFFFF">					
											<table class="table-striped table-bordered" width="100%" border="0" align="center" cellpadding="2" cellspacing="10">												
												<tr>
													<td colspan=5></td>
													<td colspan=2 align=center class="f_bk8b" >Standard</td>
													<td colspan=2 align=center class="f_bk8b" >Customer</td>
													<td colspan=2 align=center class="f_bk8b" >Contractor</td>
													<td colspan=3></td>
												</tr>
												<tr height=35 bgcolor=#f1f1f1>			
													<td class="f_bk8b" align=center>No</td>
													<td class="f_bk8b" align=center>Material Code</td>
													<td class="f_bk8b">Description(TH)</td>
													<td class="f_bk8b">Description(EN)</td>	
													<td class="f_bk8b">Material Group</td>
													<td class="f_bk8b">Cost</td>
													<td class="f_bk8b">Price</td>
													<td class="f_bk8b">Cost</td>
													<td class="f_bk8b">Price</td>
													<td class="f_bk8b">Cost</td>
													<td class="f_bk8b">Price</td>
													<td class="f_bk8b">Margin</td>
													<td class="f_bk8b" align=center>Status</td>													
													<td class="f_bk8b"><center>Action</center></td>	
													<td> </td>
												</tr>                        												
												<?php												
												$n = 0;													
												$sql_mat = "SELECT mat.* FROM" .
												" (SELECT ROW_NUMBER() OVER(ORDER BY mat_code) AS rownumber,* FROM mat_mstr $criteria) as mat" .
												" INNER JOIN mag_mstr ON mag_code = mat_mag_code".
												" WHERE mat.rownumber > $start_row and mat.rownumber <= $start_row+$pagesize";												
												
												$result_mat = sqlsrv_query( $conn, $sql_mat,$params);																					
												while($r_mat = sqlsrv_fetch_array($result_mat, SQLSRV_FETCH_ASSOC)) {	
													$mat_code = html_escape($r_mat['mat_code']);
													$mat_th_name = html_escape($r_mat['mat_th_name']);
													$mat_en_name = html_escape($r_mat['mat_en_name']);
													$mat_mag_code = html_escape($r_mat['mat_mag_code']);
													$mat_mag_name = html_escape($r_mat['mag_name']);
													$mat_detail = html_escape($r_mat['mat_detail']);
													$mat_unit_code = html_escape($r_mat['mat_unit_code']);
													$mat_standard_unit_cost = html_escape($r_mat['mat_standard_unit_cost']);
													$mat_standard_unit_price = html_escape($r_mat['mat_standard_unit_price']);
													$mat_customer_unit_cost = html_escape($r_mat['mat_customer_unit_cost']);
													$mat_customer_unit_price = html_escape($r_mat['mat_customer_unit_price']);	
													$mat_contractor_unit_cost = html_escape($r_mat['mat_contractor_unit_cost']);
													$mat_contractor_unit_price = html_escape($r_mat['mat_contractor_unit_price']);
													
													$mat_active = $r_mat['mat_active'];
													if ($mat_active == "1") { $mat_active_text = "ACTIVE"; }
													else {$mat_active_text = "NOT"; }													
													$n++;																										
													?>													
													<tr ONMOUSEOVER="this.style.backgroundColor ='white'" ONMOUSEOUT = "this.style.backgroundColor = ''">
														<td class="f_bk8" style="width:30px;text-align:center;"><?php echo $n+($currentpage-1)*$pagesize; ?></td>
														<td class="f_bk8" style="width:80px;text-align:center;"><?php echo $mat_code; ?></td>
														<?php if ($edit_mat_code != $mat_code) {?>																
															<td class="f_bk8" style="width:150px;padding-left:5px;"><?php echo $mat_th_name; ?></td>
															<td class="f_bk8" style="width:150px;padding-left:5px;"><?php echo $mat_en_name; ?></td>
															<td class="f_bk8" style="width:150px;padding-left:5px;"><?php echo $mat_mag_name; ?></td>
															<td class="f_bk8" style="width:80px;padding-left:5px;"><?php echo $mat_standard_unit_cost; ?></td>
															<td class="f_bk8" style="width:80px;padding-left:5px;"><?php echo $mat_standard_unit_price; ?></td>
															<td class="f_bk8" style="width:80px;padding-left:5px;"><?php echo $mat_customer_unit_cost; ?></td>
															<td class="f_bk8" style="width:80px;padding-left:5px;"><?php echo $mat_customer_unit_price; ?></td>
															<td class="f_bk8" style="width:80px;padding-left:5px;"><?php echo $mat_contractor_unit_cost; ?></td>
															<td class="f_bk8" style="width:80px;padding-left:5px;"><?php echo $mat_contractor_unit_price; ?></td>
															<td class="f_bk8" style="width:80px;padding-left:5px;"><?php echo $mat_margin_price; ?></td>
															
															<td class="<?php if($mat_active_text=="NOT") {echo "f_red8";} else {echo "f_bk8";}?>" style="text-align:center;width:100px;padding-left:5px;"><?php echo $mat_active; ?></td>																																																							
															<td style="padding-right:10px;">
																<a href="#div_frm_mat_edit<?php echo $mat_code?>" style='color:red' data-toggle="modal">
																	<div class="btn btn-warning btn-mini paddingleftandright10 pull-right" style="margin-right:10px;">																
																		<i class="icon-white icon-edit"></i>
																		<span style="color:#000;">Edit</span>																
																	</div>
																</a>
																<button type="button" class="btn btn-mini btn-danger btn-primary paddingleftandright5" onclick="delac('<?php echo $ac_code; ?>','<?php echo $currentpage;?>')">
																	<i class="icon-trash icon-white"></i>
																	<span>Del</span>
																</button>
																<div id="div_frm_mat_edit<?php echo $mat_code?>" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
																	<form name="frm_mat_edit<?php echo $mat_code?>" autocomplete=OFF method="post" action="../serverside/matmstrpost.php">
																		<input name="action" type=hidden value="edit">
																		<input type="hidden" name="csrf_securecode" value="<?php echo $csrf_securecode?>">
																		<input type="hidden" name="csrf_token" value="<?php echo md5($csrf_token)?>">
																		<input type="hidden" name="mat_code" value="<?php echo $mat_code?>">																	
																		<input name="pg" type="hidden" value="<?php echo $currentpage;?>">
																		<div class="modal-header">
																			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
																			<h3 id="myModalLabel">Edit Material ::</h3>
																		</div>
																		<!--div class="modal-body"-->
																		<div class="">
																			<table class="table table-condensed" border=0>	
																			<tbody>
																				<tr>
																					<td style="width:150px;text-align:right; vertical-align: middle;"><b>Material Code:</b></td>
																					<td colspan=3 style="color:blue">
																						<?php echo $mat_code?>
																					</td>
																				</tr>
																				<tr>
																					<td style="text-align:right; vertical-align: middle;"><b>Description(TH):</b></td>
																					<td colspan=3 style="color:blue">
																						<input type="text" name="mat_th_name" value="<?php echo $mat_th_name?>" class="span5" style="color:blue;width: 250px;" maxlength="255">
																					</td>
																				</tr>
																				<tr>
																					<td style="text-align:right; vertical-align: middle;"><b>Description(EN):</b></td>
																					<td colspan=3>
																						<input type="text" name="mat_en_name" value="<?php echo $mat_en_name?>" class="span5" style="color:blue; width: 250px;" maxlength="255">
																					</td>
																				</tr>
																				<tr>
																					<td style="text-align:right; vertical-align: middle;"><b>Material Group:</b></td>
																					<td colspan=3>
																						<select name="mat_mag_code" class="f_bl8"  style="width: 150px;" >
																							<option value="">--Select--</option>
																							<?php 
																							$sql_mag = "SELECT * FROM mag_mstr order by mag_seq";
																							$result_mag_list = sqlsrv_query( $conn,$sql_mag);																													
																							while($r_mag_list=sqlsrv_fetch_array($result_mag_list, SQLSRV_FETCH_ASSOC)) {
																							?>
																								<option value="<?php echo $r_mag_list['mag_code'];?>" 
																									<?php if ($mat_mag_code == $r_mag_list['mag_code']) {echo "selected";}?>>
																									<?php echo $r_mag_list['mag_name'];?>
																								</option> 
																							<?php } ?>
																						</select>	
																					</td>
																				</tr>
																				<tr>
																					<td style="text-align:right; vertical-align: middle;"><b>Material Unit:</b></td>
																					<td colspan=3>
																						<select name="mat_unit_code" class="f_bl8"  style="width: 150px;" >
																							<option value="">--Select--</option>
																							<?php 
																							$sql_unit = "SELECT * FROM unit_mstr order by unit_seq";
																							$result_unit_list = sqlsrv_query( $conn,$sql_unit);																													
																							while($r_unit_list=sqlsrv_fetch_array($result_unit_list, SQLSRV_FETCH_ASSOC)) {
																							?>
																								<option value="<?php echo $r_unit_list['unit_code'];?>" 
																									<?php if ($mat_unit_code == $r_unit_list['unit_code']) {echo "selected";}?>>
																									<?php echo $r_unit_list['unit_name'];?>
																								</option> 
																							<?php } ?>
																						</select>	
																					</td>
																				</tr>
																				<tr>
																					<td style="text-align:right; vertical-align: middle;"><b>Detail:</b></td>
																					<td colspan=3>
																						<textarea name="mat_detail" rows=3 style="color:blue; width: 250px"><?php echo $mat_detail?></textarea>
																					</td>
																				</tr>
																				<tr>
																					<td></td>
																					<td style="width:120px;"><b>Cost</b></td>
																					<td style="width:120px;"><b>Price</b></td>
																					<td></td>
																				</tr>
																				<tr>	
																					<td style="text-align:right; vertical-align: middle;"><b>Customer:</td>
																					<td style=""><input name="mat_customer_unit_cost" value="<?php echo $mat_customer_unit_cost?>" style="color:blue;width:80px"></td>
																					<td style=""><input name="mat_customer_unit_price" value="<?php echo $mat_customer_unit_price?>" style="color:blue;width:80px"></td>
																					<td></td>
																				</tr>
																				<tr>
																					<td style="text-align:right; vertical-align: middle;"><b>Contractor:</td>
																					<td style=""><input name="mat_contractor_unit_cost" value="<?php echo $mat_contractor_unit_cost?>" style="color:blue;width:80px"></td>
																					<td style=""><input name="mat_contractor_unit_price" value="<?php echo $mat_contractor_unit_price?>" style="color:blue;width:80px"></td>
																					<td></td>
																				</tr>
																				<tr>
																					<td style="text-align:right; vertical-align: middle;"><b>Standard:</td>
																					<td style=""><input name="mat_standard_unit_cost" value="<?php echo $mat_standard_unit_cost?>" style="color:blue;width:80px"></td>
																					<td style=""><input name="mat_standard_unit_price" value="<?php echo $mat_standard_unit_price?>" style="color:blue;width:80px"></td>
																					<td></td>
																				</tr>				
																				<tr>
																					<td style="text-align:right; vertical-align: middle;"><b>Status:</b></td>
																					<td colspan=3>
																						<select name="mat_active" class="f_bl8" style="text-align:center;width:100px;" >
																							<option value="">--Select--</option>
																							<option value="0" <?php if (!$mat_active) {echo  "selected";}?>>NOT</option>
																							<option value="1" <?php if ($mat_active) {echo  "selected";}?>>ACTIVE</option>																		
																						</select>
																					</td>
																				</tr>																																												
																			</tbody>
																			</table>					
																		</div>
																	</form>
																	<div class="modal-footer">
																		<button type="submit" class="btn btn-success fileinput-button paddingleftandright10 margintop20 marginleft20" data-toggle="modal" onclick="matpostform('<?php echo "frm_mat_edit".$mat_code;?>')">
																			<i class="icon-check icon-white"></i>
																			<span>Save</span>
																		</button>											
																	</div>
																</div>
															</td>														
															<td width=10><?php if($activeid==$ac_code) {echo "<img src='../_images/active-id.png'>";}?></td>
														<?php }?>														
													</tr>
												<?php }?>
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
	<div id="div_frm_mat_add" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">																																																																				
		<form name="frm_mat_add" autocomplete=OFF method="post" action="../serverside/matmstrpost.php">
			<input type="hidden" name="action" value="add">	
			<input type="hidden" name="csrf_securecode" value="<?php echo $csrf_securecode?>">
			<input type="hidden" name="csrf_token" value="<?php echo md5($csrf_token)?>">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h3 id="myModalLabel">Add Material ::</h3>
			</div>
			<!--div class="modal-body"-->
			<div class="">
				<table class="table table-condensed" border=0>	
				<tbody>																	
					<tr>
						<td style="width:150px;text-align:right; vertical-align: middle;"><b>Material Code:</b></td>
						<td colspan=3>
							<input type="text" name="mat_code" class="span5" style="width: 100px;" maxlength="30">
						</td>
					</tr>
					<tr>
						<td style="text-align:right; vertical-align: middle;"><b>Description(TH):</b></td>
						<td colspan=3>
							<input type="text" name="mat_th_name" class="span5" style="width: 250px;" maxlength="255">
						</td>
					</tr>
					<tr>
						<td style="text-align:right; vertical-align: middle;"><b>Description(EN):</b></td>
						<td colspan=3>
							<input type="text" name="mat_en_name" class="span5" style="width: 250px;" maxlength="255">
						</td>
					</tr>
					<tr>
						<td style="text-align:right; vertical-align: middle;"><b>Material Group:</b></td>
						<td colspan=3>
							<select name="mat_mag_code" class="f_bl8"  style="width: 150px;" >
								<option value="">--Select--</option>
								<?php 
								$sql_mag = "SELECT * FROM mag_mstr order by mag_seq";
								$result_mag_list = sqlsrv_query( $conn,$sql_mag);																													
								while($r_mag_list=sqlsrv_fetch_array($result_mag_list, SQLSRV_FETCH_ASSOC)) {
								?>
									<option value="<?php echo $r_mag_list['mag_code'];?>"><?php echo $r_mag_list['mag_name'];?></option> 
								<?php } ?>
							</select>	
						</td>
					</tr>
					<tr>
						<td style="text-align:right; vertical-align: middle;"><b>Material Unit:</b></td>
						<td colspan=3>
							<select name="mat_unit_code" class="f_bl8"  style="width: 150px;" >
								<option value="">--Select--</option>
								<?php 
								$sql_unit = "SELECT * FROM unit_mstr order by unit_seq";
								$result_unit_list = sqlsrv_query( $conn,$sql_unit);																													
								while($r_unit_list=sqlsrv_fetch_array($result_unit_list, SQLSRV_FETCH_ASSOC)) {
								?>
									<option value="<?php echo $r_unit_list['unit_code'];?>"><?php echo $r_unit_list['unit_name'];?></option> 
								<?php } ?>
							</select>	
						</td>
					</tr>
					<tr>
						<td style="text-align:right; vertical-align: middle;"><b>Detail:</b></td>
						<td colspan=3>
							<textarea name="mat_detail" rows=3 style="width: 250px"></textarea>
						</td>
					</tr>
					<tr>
						<td></td>
						<td style="width:120px;"><b>Cost</b></td>
						<td style="width:120px;"><b>Price</b></td>
						<td></td>
					</tr>
					<tr>	
						<td style="text-align:right; vertical-align: middle;"><b>Customer:</td>
						<td style=""><input name="mat_customer_unit_cost" style="width:80px"></td>
						<td style=""><input name="mat_customer_unit_price" style="width:80px"></td>
						<td></td>
					</tr>
					<tr>
						<td style="text-align:right; vertical-align: middle;"><b>Contractor:</td>
						<td style=""><input name="mat_contractor_unit_cost" style="width:80px"></td>
						<td style=""><input name="mat_contractor_unit_price" style="width:80px"></td>
						<td></td>
					</tr>
					<tr>
						<td style="text-align:right; vertical-align: middle;"><b>Standard:</td>
						<td style=""><input name="mat_standard_unit_cost" style="width:80px"></td>
						<td style=""><input name="mat_standard_unit_price" style="width:80px"></td>
						<td></td>
					</tr>				
					<tr>
						<td style="text-align:right; vertical-align: middle;"><b>Status:</b></td>
						<td colspan=3>
							<select name="mat_active" class="f_bl8" style="text-align:center;width:100px;" >
								<option value="">--Select--</option>
								<option value="0">NOT</option>
							    <option value="1">ACTIVE</option>																		
							</select>
						</td>
					</tr>																																												
				</tbody>
				</table>					
			</div>
			<div class="modal-footer">
				<button type="submit" class="btn btn-success fileinput-button paddingleftandright10 margintop20 marginleft20" data-toggle="modal" onclick="matpostform('<?php echo "frm_mat_add";?>')">
					<i class="icon-check icon-white"></i>
					<span>Save</span>
				</button>											
			</div>												
		</form>																																																			
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
	<form name="frmdelete" method="post" action="../serverside/matmstrpost.php">
		<input type="hidden" name="action" value="delete">
		<input type="hidden" name="csrf_securecode" value="<?php echo $csrf_securecode?>">
		<input type="hidden" name="csrf_token" value="<?php echo md5($csrf_token)?>">
		<input type="hidden" name="mat_code">
		<input type="hidden" name="pg">
	</form>		
	</body>
</html>
