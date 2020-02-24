<?php 
//Fix for Test Pull
include("../_incs/acunx_metaheader.php");
include("../_incs/chksession.php");  
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

set_time_limit(0);
$curdate = date('Ymd');
$params = array();

$params = array();
$activeid = decrypt(html_escape($_REQUEST['activeid']), $key);

$in_qtm_tmpsubmit = html_escape($_POST["in_qtm_tmpsubmit"]);
$in_qtm_nbr = html_escape($_POST["in_qtm_nbr"]);
$in_qtm_customer = html_escape($_POST["in_qtm_customer"]);
$in_qtm_step_code = html_escape($_POST["in_qtm_step_code"]);

If ($in_qtm_tmpsubmit == "") {
	$in_qtm_tmpsubmit = html_escape($_COOKIE['in_qtm_tmpsubmit']);	
	$in_qtm_nbr = html_escape($_COOKIE['in_qtm_nbr']);
	$in_qtm_customer = html_escape($_COOKIE['in_qtm_customer']);
	$in_qtm_step_code = html_escape($_COOKIE['in_qtm_step_code']);
}
else {		
	setcookie("in_qtm_tmpsubmit","",0,$ck_path,$ck_dom,$ck_secure,$ck_httponly);
	setcookie("in_qtm_nbr","",0,$ck_path,$ck_dom,$ck_secure,$ck_httponly);
	setcookie("in_qtm_customer","",0,$ck_path,$ck_dom,$ck_secure,$ck_httponly);
	setcookie("in_qtm_select","",0,$ck_path,$ck_dom,$ck_secure,$ck_httponly);
	setcookie("in_qtm_step_code","",0,$ck_path,$ck_dom,$ck_secure,$ck_httponly);
	setcookie("in_qtm_shownpd","",0,$ck_path,$ck_dom,$ck_secure,$ck_httponly);
}
//
if ($in_qtm_nbr != "") {
	if ($criteria != "") { $criteria = $criteria . " AND "; }
	array_push($params, $in_qtm_nbr);
	$criteria = $criteria . " qtm_nbr like '%'+?+'%'";
}
setcookie("in_qtm_nbr", $in_qtm_nbr,0,$ck_path,$ck_dom,$ck_secure,$ck_httponly);
//
if ($in_qtm_customer != "") {
	if ($criteria != "") { $criteria = $criteria . " AND "; }
	array_push($params, $in_qtm_customer);
	array_push($params, $in_qtm_customer);
	$criteria = $criteria . " (customer_name1 like '%'+?+'%' OR qtm_to like '%'+?+'%')";
}
setcookie("in_qtm_customer", $in_qtm_customer,0,$ck_path,$ck_dom,$ck_secure,$ck_httponly);
//
	
if ($in_qtm_step_code != "") {
	if ($criteria != "") { $criteria = $criteria . " AND "; }
	array_push($params, $in_qtm_step_code);
	$criteria = $criteria . " qtm_step_code = ?";
}
setcookie("in_qtm_step_code", $in_qtm_step_code,0,$ck_path,$ck_dom,$ck_secure,$ck_httponly);
//
if ($criteria != "") { $criteria = " AND " . $criteria; }		

?>
<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
<!-- BEGIN: Head-->

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="description" content="Stack admin is super flexible, powerful, clean &amp; modern responsive bootstrap 4 admin template with unlimited possibilities.">
    <meta name="keywords" content="admin template, stack admin template, dashboard template, flat admin template, responsive admin template, web app">
    <meta name="author" content="PIXINVENT">
    <title><?php echo TITLE;?></title>
    <link rel="apple-touch-icon" href="../theme/app-assets/images/ico/apple-icon-120.png">
    <link rel="shortcut icon" type="image/x-icon" href="../theme/app-assets/images/ico/favicon.ico">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:300,300i,400,400i,500,500i%7COpen+Sans:300,300i,400,400i,600,600i,700,700i" rel="stylesheet">

    <!-- BEGIN: Vendor CSS-->
    <link rel="stylesheet" type="text/css" href="../theme/app-assets/vendors/css/vendors.min.css">
    <link rel="stylesheet" type="text/css" href="../theme/app-assets/vendors/css/tables/datatable/datatables.min.css">
    <!-- END: Vendor CSS-->

    <!-- BEGIN: Theme CSS-->
    <link rel="stylesheet" type="text/css" href="../theme/app-assets/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="../theme/app-assets/css/bootstrap-extended.css">
    <link rel="stylesheet" type="text/css" href="../theme/app-assets/css/colors.css">
    <link rel="stylesheet" type="text/css" href="../theme/app-assets/css/components.css">
    <!-- END: Theme CSS-->

    <!-- BEGIN: Page CSS-->
    <link rel="stylesheet" type="text/css" href="../theme/app-assets/css/core/menu/menu-types/vertical-menu.css">
    <link rel="stylesheet" type="text/css" href="../theme/app-assets/css/core/colors/palette-gradient.css">
	<!--<link rel="stylesheet" type="text/css" href="../theme/app-assets/fonts/simple-line-icons/style.min.css">-->
	<link rel="stylesheet" type="text/css" href="../theme/app-assets/fonts/font-awesome/css/font-awesome.min.css">
    <!-- END: Page CSS-->

    <!-- BEGIN: Custom CSS-->
    <link rel="stylesheet" type="text/css" href="../theme/assets/css/style.css">
	<link rel="stylesheet" type="text/css" href="../theme/assets/css/project_style.css">
	<link href="../_libs/css/cisbof.css" rel="stylesheet">
    <!-- END: Custom CSS-->
	
</head>
<!-- END: Head-->

<!-- BEGIN: Body-->

<body class="vertical-layout vertical-menu 2-columns menu-collapsed  fixed-navbar" data-open="hover" data-menu="vertical-menu" data-col="2-columns">

	<div id="result"></div>
    <?php include("../cismain/menu_header.php"); ?>	
	<?php include("../cismain/menu_leftsidebar.php"); ?>
	
	<?php				
	//นับจำนวน Record ของงานที่รอคุณทำ
	$total_curprocessor = 0;

	$sql_cnt =  "SELECT count(*) 'cnt' FROM qtm_mstr INNER JOIN custpj_mstr ON custpj_code = qtm_customer_number WHERE qtm_is_delete = '0' and  (qtm_curprocessor like '%$user_login%')";
	$result_cnt = sqlsrv_query($conn, $sql_cnt,$params); 
	$row_cnt = sqlsrv_fetch_array($result_cnt, SQLSRV_FETCH_ASSOC);	

	if ($row_cnt) {
		$total_curprocessor = (int)$row_cnt['cnt'];
	}

	//นับจำนวนตาม criteria
	$sql_cnt =  "SELECT * FROM qtm_mstr INNER JOIN custpj_mstr ON custpj_code = qtm_customer_number WHERE qtm_is_delete = '0' $criteria";
	$options = array("Scrollable" => 'keyset');
	$result = sqlsrv_query( $conn,$sql_cnt,$params,$options);	
	$max = sqlsrv_num_rows($result);
	
	
		
	$pagesize = 8;
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

    <!-- BEGIN: Content-->
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="content-wrapper">
            <div class="content-header row mt-n1">
                <div class="content-header-left col-md-6 col-12 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="breadcrumb-wrapper col-12">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                                <li class="breadcrumb-item"><a href="#">All Quotation</a></li>
                            </ol>
                        </div>
                    </div>
                    <h3 class="content-header-title mb-0">Quotation</h3>
                </div>
                <div class="content-header-right col-md-6 col-12">
                    <?php if(inlist($user_role,"QT_CREATE")) {?>
					<div class="btn-group float-md-right" role="group" aria-label="Button group with nested dropdown">
                        <div class="btn-group" role="group">
                            <button class="btn btn-outline-primary dropdown-toggle dropdown-menu-right" id="btnGroupDrop1" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="feather icon-settings icon-left"></i> Actions</button>
                            <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
								<a class="dropdown-item" href="../cisbof/qtmadd.php?pg=<?php echo $currentpage?>">New Quotation</a>
								<!--a class="dropdown-item" href="component-buttons-extended.html">xxxx</a-->
							</div>
                        </div><a class="btn btn-outline-primary" href="full-calender-basic.html"><i class="feather icon-mail"></i></a><a class="btn btn-outline-primary" href="timeline-center.html"><i class="feather icon-pie-chart"></i></a>
                    </div>
					<?php }?>
                </div>
            </div>
            <div class="content-body mt-n1">
                <!-- File export table -->
                <section id="file-export">
					<div class="row">
                        <div class="col-12">
                            <div class="card">
								<div class="card-header mt-1 pt-0 pb-0">
									<div class="card-title p-0">
										<form name="frm" method="POST" autocomplete=OFF action="qtmall.php">
											<input type="hidden" name="csrf_securecode" value="<?php echo $csrf_securecode?>">
											<input type="hidden" name="csrf_token" value="<?php echo md5($csrf_token)?>">
											<input type="hidden" name="in_qtm_tmpsubmit" value="search">
											<input type="hidden" name="action">	
											<input type="hidden" name="pg">
											<div class="form-group row mb-0 pt-0"  style="font-size:8pt">
												<div class="form-group col-sm-2">
													<label for="in_qtm_nbr" class="font-weight-bold">Quotation No:</label>
													<input type="text" name="in_qtm_nbr" id="in_qtm_nbr" class="form-control form-control-sm font-small-4" value="<?php echo $in_qtm_nbr?>">
												</div>	
												<div class="form-group col-sm-2">
													<label for="in_qtm_customer" class="font-weight-bold">Customer:</label>
													<input type="text" name="in_qtm_customer" id="in_qtm_customer" class="form-control form-control-sm font-small-4" value="<?php echo $in_qtm_customer?>">
												</div>
												<div class="form-group col-sm-3">
													<label for="in_qtm_step_code" class="font-weight-bold">Status:</label>
													<div class="input-group">
														<select class="form-control form-control-sm" name="in_qtm_step_code" id="in_qtm_step_code">
															<option value="">-- All --</option>
															<?php
															$sql_step = "SELECT qtm_step_code,qtm_step_name FROM qtm_step_mstr order by qtm_step_seq";												
															$result_step_list = sqlsrv_query( $conn,$sql_step);																													
															while($r_step_list=sqlsrv_fetch_array($result_step_list, SQLSRV_FETCH_ASSOC)) {
															?>
																<option  value="<?php echo $r_step_list['qtm_step_code'];?>"
																	<?php if (trim($r_step_list['qtm_step_code']) == "$in_qtm_step_code") { echo "selected"; } ?>>
																	<?php echo html_quot($r_step_list['qtm_step_name']);?></option> 
															<?php } ?>
														</select>
														<div class="input-group-append">
															<button class="btn btn-navbar" type="submit" onclick="showdata()">
																<i class="feather icon-search"></i>
																
															</button>		
														</div>
													</div>
												</div>
											</div>
										</form>
									</div>
									<div class="heading-elements">
                                        <ul class="list-inline mb-0">
                                            <li><a data-action="collapse"><i class="feather icon-minus"></i></a></li>
                                            <li><a data-action="reload"><i class="feather icon-rotate-cw"></i></a></li>
                                            <li><a data-action="expand"><i class="feather icon-maximize"></i></a></li>
                                            <!--<li><a data-action="close"><i class="feather icon-x"></i></a></li>-->
                                        </ul>
                                    </div>
                                    <div class="card-title p-0 mt-n1" style="font-size:10px">
										<div class="row black">
											<div class="col-sm-12 col-md-6 m-0">
											(Total <font color=red><?php echo $totalpage;?></font>&nbsp;Pages&nbsp;<font color=red><?php echo $totalrow;?></font>&nbsp;items)								
											&nbsp;&nbsp;&nbsp;<input name="jumto" autocomplete=OFF style="width:30px;">&nbsp;<input name="GO" type="button" value="GO" onclick="gotopage(document.all.jumto.value)">
											</div>
											<div class="col-sm-12 col-md-6 m-0">
											<ul class="pagination pagination-md float-right" style="margin:auto">
											<?php
											if ($start_page > 1) {
												echo "<li class='page-item'><a class='paging' href='javascript:gotopage(1)'>&laquo;</a></li>";
											}														
											for ($pg=$start_page; $pg<=$end_page; $pg++) {	
												if ((int)($currentpage) == (int)($pg)) {
													echo "<li class='page-item active'><a class='page-link' href='javascript:gotopage(" . $pg . ")'>$pg</a></li>";
												}
												else {
													echo "<li class='page-item'><a class='page-link' href='javascript:gotopage(" . $pg . ")'>$pg</a></li>";
												}
											}												
											if ($end_page < $totalpage) {		
												echo "<li class='page-item'><a class='page-link' href='javascript:gotopage(" . $totalpage . ")'>&raquo;</a></li>";
											}
											?>
											</ul>
											</div>
										</div>
                                    </div>
                                </div>
                                <div class="card-content collapse show mt-n1">
                                    <div class="card-body card-dashboard" style="margin:auto;">
										<div class="table-responsive">
										<table class="table-sm table-striped m-0 p-0 table-bordered tabledata" width=100%>
											<thead>
												<tr class="bg-primary font-weight-bold p-0">
													<th>No.</th>
													<th>Quotation No</th>
													<th>Quotation Name</th>
													<th>Customer</th>
													<th>Date</th>
													<th>Expire</th>
													<th>Project No</th>
													<th>Prepaid Amt</th>
													<th>Customer Amt</th>
													<th>Contractor Amt</th>
													<th>Auction Amt</th>
													<th>Status</th>
													<th>Status By</th>
													<th>Action</th>
													<th>&nbsp;</th>
												</th>
												</thead>   
												<tbody>
												<?php
												$n = 0;													
												$sql_qtm = "SELECT qtm.* FROM" .
												" (SELECT ROW_NUMBER() OVER(ORDER BY qtm_step_code,qtm_nbr) AS rownumber,* FROM qtm_mstr INNER JOIN custpj_mstr ON custpj_code = qtm_customer_number WHERE qtm_is_delete = 0 $criteria) as qtm" .
												" WHERE qtm.rownumber > $start_row and qtm.rownumber <= $start_row+$pagesize";																																																														
														
												$result_qtm = sqlsrv_query( $conn, $sql_qtm,$params);
												while($rec_qtm = sqlsrv_fetch_array($result_qtm, SQLSRV_FETCH_ASSOC)) {
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
													$qtm_address = html_escape($rec_qtm['qtm_address']);
													$qtm_amphur = html_escape($rec_qtm['qtm_amphur']);
													$qtm_province = html_escape($rec_qtm['qtm_province']);
													$qtm_zip_code = html_escape($rec_qtm['qtm_zip_code']);
													$qtm_lineid = html_escape($rec_qtm['qtm_lineid']);
													$qtm_email = html_escape($rec_qtm['qtm_email']);
													$qtm_tel_contact = html_escape($rec_qtm['qtm_tel_contact']);
													$qtm_detail = html_escape($rec_qtm['qtm_detail']);
													$qtm_remark = html_escape($rec_qtm['qtm_remark']);
													$qtm_prepaid_amt = html_escape($rec_qtm['qtm_prepaid_amt']);
													$qtm_prepaid_date = html_escape($rec_qtm['qtm_prepaid_date']);
													if ($qtm_prepaid_date!="") {$qtm_prepaid_date = dmyty($qtm_prepaid_date); }
													$qtm_disc = html_escape($rec_qtm['qtm_disc']);
													$qtm_disc_unit = html_escape($rec_qtm['qtm_disc_unit']);
													$qtm_customer_amt = html_escape($rec_qtm['qtm_customer_amt']);
													$qtm_contractor_amt = html_escape($rec_qtm['qtm_contractor_amt']);
													$qtm_auction_amt = html_escape($rec_qtm['qtm_auction_amt']);
													$qtm_sale_code = html_escape($rec_qtm['qtm_sale_code']);
													$qtm_pjm_nbr = html_escape($rec_qtm['qtm_pjm_nbr']);
													$qtm_ref_nbr = html_escape($rec_qtm['qtm_ref_nbr']);
													$qtm_revsion = html_escape($rec_qtm['qtm_revsion']);
													$qtm_tem_code = html_escape($rec_qtm['qtm_tem_code']);
													$qtm_step_code = html_escape($rec_qtm['qtm_step_code']);
													$qtm_step_name = findsqlval("qtm_step_mstr","qtm_step_name", "qtm_step_code", $qtm_step_code,$conn);
													$qtm_step_by = html_escape($rec_qtm['qtm_step_by']);
													$qtm_step_date =$rec_qtm['qtm_step_date'];
													$qtm_step_cmmt = html_escape($rec_qtm['qtm_step_cmmt']);
													$qtm_whocanread = html_escape($rec_qtm['qtm_whocanread]']);
													$qtm_curprocessor = html_escape($rec_qtm['qtm_curprocessor']);
													
													$qtm_curprocessor_name = "";
													if ($qtm_curprocessor != "") {
														$qtm_curprocessor_name = findsqlval("emp_mstr","emp_th_firstname", "emp_user_id", $qtm_curprocessor,$conn);
														if ($qtm_curprocessor_name == "") {
															$qtm_curprocessor_name = $qtm_curprocessor;
														}
													}
													$qtm_create_by = html_escape($rec_qtm['qtm_create_by']);	
													$qtm_create_by = findsqlval("emp_mstr","emp_th_firstname+' '+emp_th_lastname", "emp_user_id", $qtm_create_by,$conn);
													switch ($qtm_step_code) {
														case "0" : $badge_type = "badge badge-pill badge-secondary badge-sm"; break; 
														case "10": $badge_type = "badge badge-pill badge-warning badge-sm"; break; 
														case "20": $badge_type = "badge badge-pill badge-info badge-sm black"; break; 
														case "30": $badge_type = "badge badge-pill badge-primary badge-sm"; break; 
														case "40": $badge_type = "badge badge-pill badge-warning badge-sm"; break; 
														case "90": $badge_type = "badge badge-pill badge-success badge-sm"; break;
														case "800": $badge_type = "badge badge-pill badge-danger badge-sm"; break; 														
													}
													
													$n++;																										
													?>
													<tr class="black">
														<td class="pl-0 pr-0"><?php echo $n+($currentpage-1)*$pagesize; ?></td>
														<td class="pl-0 pr-0"><?php echo $qtm_nbr; ?></td>
														<td class="pl-0 pr-0 text-left <?php if ($qtm_customer_number == 'DUMMY') { echo 'red';}?>"><?php echo $qtm_customer_number?><br><?php echo $qtm_name; ?></td>
														<td class="pl-0 pr-0 text-left"><?php echo $qtm_customer_name; ?></td>	
														<td class="pl-0 pr-0"><?php echo dmyty($qtm_date); ?></td>
														<td class="pl-0 pr-0"><?php echo dmyty($qtm_expire_date); ?></td>
														<td class="pl-0 pr-0"><?php echo $qtm_pjm_nbr; ?></td>
														<td class="pl-0 pr-1 text-right"><?php echo number_fmt($qtm_prepaid_amt); ?></td>
														<td class="pl-0 pr-1 text-right"><?php echo number_fmt($qtm_customer_amt); ?></td>	
														<td class="pl-0 pr-1 text-right"><?php echo number_fmt($qtm_contractor_amt); ?></td>
														<td class="pl-0 pr-1 text-right"><?php echo number_fmt($qtm_auction_amt); ?></td>
														<td class="pl-0 pr-0"><div class="text-left <?php echo $badge_type?>"><?php echo $qtm_step_name; ?></div></td>
														<td class="pl-0 pr-0 red"><?php echo $qtm_curprocessor_name; ?></td>
														<td>
															<div class="btn-group" style="margin-top:8px">
																<button type="button" class="btn btn-success dropdown-toggle mr-1 mb-1 btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>
																<div class="dropdown-menu">
																	<a class="dropdown-item small blue" href="javascript:void(0)" onclick="loadresult();window.location.href='qtdmnt.php?qtmnumber=<?php echo encrypt($qtm_nbr, $key);?>&pg=<?php echo $currentpage?>'"><i class="fa fa-pencil-square-o"></i> Data Detail</a>
																	<?php if ($qtm_curprocessor == $user_login && inlist('0,10',$qtm_step_code)) {?>
																		<a class="dropdown-item small red" href='javascript:void(0)' onclick='delqtm("<?php echo $qtm_nbr;?>","<?php echo $currentpage;?>")'><i class="fa fa-trash-o"></i> Delete</a>
																	<?php }?>
																</div>
															</div>
														</td>
														<td class="pl-0 pr-0">
															<?php if($activeid==$qtm_nbr) {echo "<img src='../_images/active-id.png'>";}?>
														</td>
													</tr>
												<?php }?>	
												</tbody>
										</table>
										</div>
                                    </div>
                                </div>
                            </div>
                        </div>
					</div>
                </section>
                <!-- File export table -->
            </div>
        </div>
    </div>
    <!-- END: Content-->
    <div class="sidenav-overlay"></div>
    <div class="drag-target"></div>
	<?php include("../cismain/menu_footer.php");?>
	<?php include("../cismain/modal.php");?>
	
	<form name="frm_qtm_delete" id="frm_qtm_delete">
		<input type="hidden" name="action" value="qtmdel">
		<input type="hidden" name="csrf_securecode" value="<?php echo $csrf_securecode?>">
		<input type="hidden" name="csrf_token" value="<?php echo md5($csrf_token)?>">		
		<input type="hidden" name="qtm_nbr">
		<input type="hidden" name="pg">
	</form>
	
    <!-- BEGIN: Vendor JS-->
    <script src="../theme/app-assets/vendors/js/vendors.min.js"></script>
    <!-- BEGIN Vendor JS-->

    <!-- BEGIN: Page Vendor JS-->
    <script src="../theme/app-assets/vendors/js/tables/datatable/datatables.min.js"></script>
    <script src="../theme/app-assets/vendors/js/tables/datatable/dataTables.buttons.min.js"></script>
    <script src="../theme/app-assets/vendors/js/tables/buttons.flash.min.js"></script>
    <script src="../theme/app-assets/vendors/js/tables/jszip.min.js"></script>
    <script src="../theme/app-assets/vendors/js/tables/pdfmake.min.js"></script>
    <script src="../theme/app-assets/vendors/js/tables/vfs_fonts.js"></script>
    <script src="../theme/app-assets/vendors/js/tables/buttons.html5.min.js"></script>
    <script src="../theme/app-assets/vendors/js/tables/buttons.print.min.js"></script>
    <!-- END: Page Vendor JS-->

    <!-- BEGIN: Theme JS-->
    <script src="../theme/app-assets/js/core/app-menu.js"></script>
    <script src="../theme/app-assets/js/core/app.js"></script>
    <!-- END: Theme JS-->

    <!-- BEGIN: Page JS-->
    <script src="../theme/app-assets/js/scripts/tables/datatables/datatable-advanced.js"></script>
    <!-- END: Page JS-->
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
		
		function delqtm(qtm_nbr,pg) {
			if(confirm('ท่านต้องการลบ Quotation นี้ไช่หรือไม่ ?')) {	
				document.frm_qtm_delete.qtm_nbr.value = qtm_nbr;
				document.frm_qtm_delete.pg.value = pg;
				$.ajax({
					beforeSend: function () {
						$('body').append('<div id="requestOverlay" class="request-overlay"></div>');
						$("#requestOverlay").show();
					},
					type: 'POST',
					url: '../serverside/qtmpost.php',
					data: $('#frm_qtm_delete').serialize(),
					timeout: 50000,
					error: function(xhr, error){
						showmsg('['+xhr+'] '+ error);
					},
					success: function(result) {
						var json = $.parseJSON(result);
						if (json.r == '0') {
							clearloadresult();
							showmsg(json.e);
						}
						else {
							clearloadresult();
							$(location).attr('href', 'qtmall.php?activeid='+json.nb+'&pg='+json.pg);
						}
					},
					complete: function () {
						$("#requestOverlay").remove();
					}
				});
			}
		}
		
		function gotopage(mypage) {							
			loadresult()
			document.frm.pg.value=mypage;
			document.frm.submit();
		}
		function loadresult() {
			$('#div_result').html("<center><img id='progress' src='../_images/loading0.gif' width=80 height=80><center>");
		}
		function clearloadresult() {
			$('#div_result').html("");
		}
		function showmsg(msg) {
			$("#modal-body").html(msg);
			$("#myModal").modal("show");
		}
	</script>
</body>
<!-- END: Body-->

</html>