<?php 
//Test Insert Comment PJMALL.PHP by Nilubonp : 24:02:2020 09:02
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

//!-- Nilubonp.Note -- Set Unlimit Time for Execute
set_time_limit(0);
//Current Date for Stamp
$curdate = date('Ymd');

//!-- Nilubonp.Note -- Declare Parameter
$params = array();
$activeid = decrypt(mssql_escape($_REQUEST['activeid']), $key);

//!-- Nilubonp.Note -- Parameter Search Box
$in_pjm_tmpsubmit = mssql_escape($_POST["in_pjm_tmpsubmit"]);
$in_pjm_nbr = mssql_escape($_POST["in_pjm_nbr"]);
$in_pjm_customer = mssql_escape($_POST["in_pjm_customer"]);
$in_pjm_step_code = mssql_escape($_POST["in_pjm_step_code"]);

//!-- Nilubonp.Note -- If $in_pjmtmpsubmit is empty then use $in_pjm_tempsubmit in COOKIE
If ($in_pjm_tmpsubmit == "") {
	$in_pjm_tmpsubmit = $_COOKIE['in_pjm_tmpsubmit'];	
	$in_pjm_nbr = $_COOKIE['in_pjm_nbr'];
	$in_pjm_customer = $_COOKIE['in_pjm_customer'];
	$in_pjm_step_code = $_COOKIE['in_pjm_step_code'];
}
else {		//!-- Nilubonp.Note -- If $in_pjmtmpsubmit is not  empty then setting COOKIE for $in_pjm_tempsubmit
	setcookie("in_pjm_tmpsubmit","",0,$ck_path,$ck_dom,$ck_secure,$ck_httponly);
	setcookie("in_pjm_nbr","",0,$ck_path,$ck_dom,$ck_secure,$ck_httponly);
	setcookie("in_pjm_customer","",0,$ck_path,$ck_dom,$ck_secure,$ck_httponly);
	setcookie("in_pjm_select","",0,$ck_path,$ck_dom,$ck_secure,$ck_httponly);
	setcookie("in_pjm_step_code","",0,$ck_path,$ck_dom,$ck_secure,$ck_httponly);
	setcookie("in_pjm_shownpd","",0,$ck_path,$ck_dom,$ck_secure,$ck_httponly);
}

//!-- Nilubonp.Note -- Setting Whoose Project Can Acccess Only 
// $whoose_project = $user_login;
// if ($whoose_project != "") {
	// if ($criteria != "") { $criteria = $criteria . " AND "; }
	// $criteria = $criteria . " pjm_create_by = '$whoose_project'";
// }

//!-- Nilubonp.Note -- Setting Current $in_pjm_nbr for Searching Box
if ($in_pjm_nbr != "") {
	if ($criteria != "") { $criteria = $criteria . " AND "; }
	$criteria = $criteria . " pjm_nbr like '%$in_pjm_nbr%'";
}
setcookie("in_pjm_nbr", $in_pjm_nbr,0,$ck_path,$ck_dom,$ck_secure,$ck_httponly);

//!-- Nilubonp.Note -- Setting Current $in_pjm_customer for Searching Box
if ($in_pjm_customer != "") {
	if ($criteria != "") { $criteria = $criteria . " AND "; }
	$criteria = $criteria . " ( custpj_name like '%$in_pjm_customer%')";
}
setcookie("in_pjm_customer", $in_pjm_customer,0,$ck_path,$ck_dom,$ck_secure,$ck_httponly);

//!-- Nilubonp.Note -- Setting Current $in_pjm_step_code for Searching Box
if ($in_pjm_step_code != "") {
	if ($criteria != "") { $criteria = $criteria . " AND "; }
	$criteria = $criteria . " pjm_pjst_code = '$in_pjm_step_code'";
}
setcookie("in_pjm_step_code", $in_pjm_step_code,0,$ck_path,$ck_dom,$ck_secure,$ck_httponly);

//!-- Nilubonp.Note -- Merge $criteria summary
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
    <title>Advanced DataTable - Stack Responsive Bootstrap 4 Admin Template</title>
    <link rel="apple-touch-icon" href="../theme/app-assets/images/ico/apple-icon-120.png">
    <link rel="shortcut icon" type="image/x-icon" href="../theme/app-assets/images/ico/favicon.ico">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:300,300i,400,400i,500,500i%7COpen+Sans:300,300i,400,400i,600,600i,700,700i" rel="stylesheet">

    <!-- BEGIN: Vendor CSS-->
    <link rel="stylesheet" type="text/css" href="../theme/app-assets/vendors/css/vendors.min.css">
    <link rel="stylesheet" type="text/css" href="../theme/app-assets/vendors/css/tables/datatable/datatables.min.css">
	
	<link rel="stylesheet" type="text/css" href="../theme/app-assets/vendors/css/animate/animate.css">
	<link rel="stylesheet" type="text/css" href="../theme/app-assets/vendors/css/extensions/sweetalert2.min.css">
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
    <? include("../cismain/menu_header.php"); ?>	
	<? include("../cismain/menu_leftsidebar.php"); ?>
	
	<?php		
	
		//นับจำนวนตาม criteria
		$sql_cnt =  "SELECT pjm_mstr.pjm_nbr, pjm_mstr.pjm_name, pjm_mstr.pjm_custpj_code, custpj_mstr.custpj_name, pjm_mstr.pjm_addr, pjm_mstr.pjm_district, pjm_mstr.pjm_amphur, pjm_mstr.pjm_province, pjm_mstr.pjm_zipcode, 
                      pjm_mstr.pjm_start_date, pjm_mstr.pjm_end_date, (pjm_mstr.pjm_latitude + '+' + pjm_mstr.pjm_longtitude) as pjm_latlong, pjm_mstr.pjm_pjst_code, pjst_mstr.pjst_name, pjm_mstr.pjm_budget, pjm_mstr.pjm_amt_disc,pjm_mstr.pjm_budget-pjm_mstr.pjm_amt_disc as pjm_netamounti
FROM         pjm_mstr INNER JOIN
                      custpj_mstr ON pjm_mstr.pjm_custpj_code = custpj_mstr.custpj_code INNER JOIN
                      pjst_mstr ON pjm_mstr.pjm_pjst_code = pjst_mstr.pjst_code $criteria";
					  
		//echo $criteria."<br>";
		//echo "<div class='row text-center' style='border:1px solid red;'>$sql_cnt</div>";

		$result = sqlsrv_query( $conn,$sql_cnt, array(), array( "Scrollable" => 'keyset' ));	
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
		
		$maxpage = 10; //-- ใส่ได้เฉพาะเลขคี่เท่านั้น
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
            <div class="content-header row">
                <div class="content-header-left col-md-6 col-12 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="breadcrumb-wrapper col-12">
                              <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="pjmall.php">Home</a>
                                </li>
                                <li class="breadcrumb-item"><a href="pjmall.php">Project</a>
                                </li>
                            </ol>
                        </div>
                    </div>
                    <h3 class="content-header-title mb-0">C'TIS Projects</h3>
                </div>
                <div class="content-header-right col-md-6 col-12">
                    <div class="btn-group float-md-right" role="group" aria-label="Button group with nested dropdown">
                        <div class="btn-group" role="group">
                             <!--<button class="btn btn-outline-primary dropdown-toggle dropdown-menu-right" id="btnGroupDrop1" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="feather icon-settings icon-left"></i>New Project</button>
                           <div class="dropdown-menu" aria-labelledby="btnGroupDrop1"><a class="dropdown-item" href="card-bootstrap.html">New Project With Quatation</a><a class="dropdown-item" href="component-buttons-extended.html">New Project Without Quatation</a></div>-->
							<a class="btn btn-outline-primary" href="pjmadd.php?pg=<?php echo $currentpage?>"><i class="fa fa-file-o icon-left"></i>New Project</a>
						</div>
						<a class="btn btn-outline-primary" href="full-calender-basic.html"><i class="fa fa-download"></i></a>
						<a class="btn btn-outline-primary" href="full-calender-events.html"><i class="fa fa-calendar"></i></a>
                    </div>
                </div>
            </div>
            <div class="content-body">
                <!-- Project All -->
                <section id="project-all">
				<div class="row">
                        <div class="col-12">
                            <div class="card">
								<div class="card-header mt-1 pt-0 pb-0" >
									<div class="card-title p-0" style="font-size:1rem;">
										<!-- Search Box -->
										<form name="frm" method="POST" autocomplete=OFF action="pjmall.php">
											<input type="hidden" name="csrf_securecode" value="<?php echo $csrf_securecode?>">
											<input type="hidden" name="csrf_token" value="<?php echo md5($csrf_token)?>">
											<input type="hidden" name="in_pjm_tmpsubmit" value="search">
											<input type="hidden" name="action">	
											<input type="hidden" name="pg">
											<div class="form-group row mb-0 pt-0">
												<div class="form-group col-sm-2 pr-0">
													<label for="in_pjm_nbr">Project No:</label>
													<input type="text" name="in_pjm_nbr" id="in_pjm_nbr" class="form-control form-control-sm font-small-4" value="<?php echo $in_pjm_nbr; ?>">
												</div>	
												<div class="form-group col-sm-2 pr-0">
													<label for="in_pjm_customer">Customer Name:</label>
													<input type="text" name="in_pjm_customer" id="in_pjm_customer" class="form-control form-control-sm font-small-4" value="<?php echo $in_pjm_customer?>">
												</div>
												<div class="form-group col-sm-3 pr-0">
													<label for="in_pjm_step_code">Project Status:</label>
													<div class="input-group">
														<select class="form-control form-control-sm font-small-4" name="in_pjm_step_code" id="in_pjm_step_code">
															<option value=""  selected>-- All --</option>
															<?php
															$sql_step = "SELECT pjst_code,pjst_name FROM pjst_mstr order by pjst_seq";												
															$result_step_list = sqlsrv_query( $conn,$sql_step);																													
															while($r_step_list=sqlsrv_fetch_array($result_step_list, SQLSRV_FETCH_ASSOC)) {
															?>
																<option  value="<?php echo $r_step_list['pjst_code'];?>"
																<?php if (trim($r_step_list['pjst_code']) == "$in_pjm_step_code") { echo "selected"; } ?>>
																<?php echo html_quot($r_step_list['pjst_name']);?></option> 
															<?php } ?>
														</select>
														<div class="input-group-append p-0 m-0">															
															<div class="fonticon-container " >
																<button class="btn btn-navbar btn-sm" type="submit" onclick="showdata()">
																	<div class="fonticon-wrap "><i class="fa fa-search-plus" style="font-size:2rem;"></i></div>
																</button>																	
															</div>
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
                                        </ul>
                                    </div>
                                    <div class="card-title p-0" style="font-size:1rem;">
										<div class="row">
											<div class="col-sm-12 col-md-6 m-0">
												(Total <font color=red><?php echo $totalpage;?></font>&nbsp;Pages&nbsp;<font color=red><?php echo $totalrow;?></font>&nbsp;items)								
												&nbsp;&nbsp;&nbsp;
												<input name="jumto" autocomplete=OFF style="width:30px;">&nbsp;<input name="GO" type="button" value="GO" onclick="gotopage(document.all.jumto.value)">
											</div>
											<div class="col-sm-12 col-md-6 m-0">
												<ul class="pagination pagination-md float-right">
												&nbsp;
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
                                <div class="card-content collapse show">
                                    <div class="card-body card-dashboard" style="margin-top:-20px;">
										<div class="table-responsive">
										<table class="table tabledata font-medium-1 text-center ">
											<thead>
												<tr class="bg-primary  white font-weight-bold">
													<th >No.</td>
													<th >Proj No.</td>
													<th >Proj. Name</td>
													<th >Customer</td>
													<th >Duration</td>													
													<th >Locations</td>													
													<th >Status</td>
													<th >Amount</td>
													<th >Discount</td>
													<th >Net Amt</td>
													<th >Action</td>
												</th>
											</thead>   
											<tbody>
											<?php
												$n = 0;	
												$sql_pjm = "SELECT pjm.* FROM(SELECT ROW_NUMBER() OVER(ORDER BY pjm_nbr) AS rownumber,pjm_mstr.pjm_nbr, pjm_mstr.pjm_name, pjm_mstr.pjm_custpj_code, custpj_mstr.custpj_name, pjm_mstr.pjm_addr, pjm_mstr.pjm_district, pjm_mstr.pjm_amphur, pjm_mstr.pjm_province, pjm_mstr.pjm_zipcode, 
                      pjm_mstr.pjm_start_date, pjm_mstr.pjm_end_date, (pjm_mstr.pjm_latitude + '+' + pjm_mstr.pjm_longtitude) as pjm_latlong, pjm_mstr.pjm_pjst_code, pjst_mstr.pjst_name, pjm_mstr.pjm_budget, pjm_mstr.pjm_amt_disc,pjm_mstr.pjm_budget-pjm_mstr.pjm_amt_disc as pjm_netamounti
FROM         pjm_mstr INNER JOIN
                      custpj_mstr ON pjm_mstr.pjm_custpj_code = custpj_mstr.custpj_code INNER JOIN
                      pjst_mstr ON pjm_mstr.pjm_pjst_code = pjst_mstr.pjst_code $criteria) as pjm
                      WHERE pjm.rownumber > $start_row and pjm.rownumber <= $start_row+$pagesize";
												
												$result_pjm = sqlsrv_query( $conn, $sql_pjm,$params);
												while($rec_pjm = sqlsrv_fetch_array($result_pjm, SQLSRV_FETCH_ASSOC)) {
													$pjm_nbr = html_escape($rec_pjm['pjm_nbr']);
													$pjm_name = html_escape($rec_pjm['pjm_name']);
													$pjm_custpj_code = html_escape($rec_pjm['pjm_custpj_code']);											
													$custpj_name	 = html_escape($rec_pjm['custpj_name']);
													$pjm_start_date = html_escape($rec_pjm['pjm_start_date']);
													$pjm_end_date = html_escape($rec_pjm['pjm_end_date']);
													$pjm_district = html_escape($rec_pjm['$pjm_district']);
													$pjm_amphur = html_escape($rec_pjm['pjm_amphur']);
													$pjm_province = html_escape($rec_pjm['pjm_province']);
													$pjm_zipcode = html_escape($rec_pjm['pjm_zipcode']);
													$pjm_latlong = html_escape($rec_pjm['pjm_latlong']);
													$pjm_pjst_code = html_escape($rec_pjm['pjm_pjst_code']);													
													$pjm_budget = html_escape($rec_pjm['pjm_budget']);
													$pjm_amt_disc = html_escape($rec_pjm['pjm_amt_disc']);
													$pjm_netamount = html_escape($rec_pjm['pjm_netamount']);
													
													//$pjm_whocanread = html_escape($rec_pjm['pjm_whocanread']);
													//$pjm_curprocessor = html_escape($rec_pjm['pjm_curprocessor']);
													//$pjm_create_by = html_escape($rec_pjm['pjm_create_by']);	
													//$pjm_create_by = findsqlval("emp_mstr","emp_th_firstname+' '+emp_th_lastname", "emp_user_id", $pjm_create_by,$conn);
																				
													$n++;																										
													?>	
													<tr class="font-small-3">
														<td class="pl-0 pr-0"><?php echo $n+($currentpage-1)*$pagesize; ?></td>
														<td class="pl-1 pr-0"><?php echo $pjm_nbr; ?></td>
														<td class="text-left pl-1 pr-0"><?php echo $pjm_name; ?></td>
														<td class="text-left pl-1 pr-0"><?php echo $pjm_custpj_code; ?>
															<br><?php echo $custpj_name; ?></td>	
														<td class="pl-1 pr-0"><?php echo dmytx($pjm_start_date); ?> - 
															<br><?php echo dmytx($pjm_end_date); ?></td>
														<td class="pl-1 pr-0"><?php echo $pjm_province; ?>
															<br><?php echo $pjm_amphur; ?>
															<br><?php echo $pjm_district; ?>
															<br><a href="https://www.google.com/maps/place/<? echo $pjm_latlong; ?>" target="_blank">Google Map</a></td>														
														<td class="pl-1 pr-0"><div class="badge badge-pill badge-success badge-sm"><?php echo $pjm_pjst_code; ?></div></td>
														<?php 	
															if($pjm_budget !="") 
																$pjm_budget = number_fmt($pjm_budget,2,',',2);
															else 
																$pjm_budget = 0;
															
															if($pjm_amt_disc !="") 
																$pjm_amt_disc = number_fmt($pjm_amt_disc,2,',',2);
															else 
																$pjm_amt_disc = 0;
															
															if($pjm_netamount !="") 
																$pjm_netamount = number_fmt($pjm_netamount,2,',',2);
															else 
																$pjm_netamount = 0;
														?>
														<td class="text-right pl-1 pr-0"><?php echo $pjm_budget; ?></td>
														<td class="text-right pl-1 pr-0"><?php echo $pjm_amt_disc; ?></td>
														<td class="text-right pl-1 pr-0"><?php echo $pjm_netamount; ?></td>														
														<td class="text-center">
															<!-- Start btn-group -->
															<div class="btn-group cus-dropdown-action" >
																<button type="button" class="btn btn-success btn-md dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>
																<div class="dropdown-menu">
																	<a class="dropdown-item" onclick="loadresult();window.location.href='pjdmnt.php?pjmnumber=<?php echo encrypt($pjm_nbr, $key);?>&pg=<?php echo $pg;?>'"><i class="fa fa-search-plus"></i> View</a>
																	<a class="dropdown-item" onclick="loadresult();window.location.href='pjdedit.php?pjmnumber=<?php echo encrypt($pjm_nbr, $key);?>&pg=<?php echo $pg;?>'"><i class="fa fa-pencil-square-o"></i> Edit/Update</a>
																	<!--<a class="dropdown-item" onclick="del_project('frm_del_project','<?php echo encrypt($pjm_nbr, $key);?>','<?php echo $pg;?>');"><i class="fa fa-trash-o"></i> Delete</a>-->
																	<div class='dropdown-divider'></div>
																		<a class='dropdown-item' id='btdelpjm' data-pjmnumber='<?php echo $pjm_nbr; ?>' data-pg='<?php echo $pg; ?>' href='javascript:void(0)'>											
																			<i class='fa fa-trash-o fa-sm '></i> Delete
																		</a>
																	</div>
																</div>
															</div>
															<!-- /btn-group -->
														</td>
													</tr>
													<?php 												
													}
													if($n==0)
													{
													?>
														<tr>
															<td class="text-center" colspan="11"><h4>-- No Result -- </h4></td>
														</tr>
													<?php
													}
													?>	
												</tbody>
										</table>
										</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                </div>
				<form name="frm_del_project" id="frm_del_project" action="../serverside/pjmpost.php">
					<input type="hidden" name="action" value="pjmdel">
					<input type="hidden" name="csrf_securecode" value="<?php echo $csrf_securecode?>">
					<input type="hidden" name="csrf_token" value="<?php echo md5($csrf_token)?>">
					<input type="hidden" name="pjm_nbr" value="">
					<input type="hidden" name="pg" value="">
				</form>
                </section>
                <!-- File export table -->
            </div>
        </div>
    </div>
    <!-- END: Content-->

    <div class="sidenav-overlay"></div>
    <div class="drag-target"></div>

    <!-- BEGIN: Footer-->
    <footer class="footer footer-static footer-light navbar-border">
        <p class="clearfix blue-grey lighten-2 text-sm-center mb-0 px-2"><span class="float-md-left d-block d-md-inline-block">Copyright &copy; 2020 <a class="text-bold-800 grey darken-2" href="https://1.envato.market/pixinvent_portfolio" target="_blank">PIXINVENT </a></span><span class="float-md-right d-none d-lg-block">Hand-crafted & Made with <i class="feather icon-heart pink"></i></span></p>
    </footer>
    <!-- END: Footer-->
	
	<?php include("../cismain/modal.php");?>

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
	
	<script src="../theme/app-assets/vendors/js/extensions/sweetalert2.all.min.js"></script>
    <script src="../theme/app-assets/vendors/js/extensions/polyfill.min.js"></script>
    <!-- END: Page Vendor JS-->

    <!-- BEGIN: Theme JS-->
    <script src="../theme/app-assets/js/core/app-menu.js"></script>
    <script src="../theme/app-assets/js/core/app.js"></script>
    <!-- END: Theme JS-->

    <!-- BEGIN: Page JS-->
    <script src="../theme/app-assets/js/scripts/tables/datatables/datatable-advanced.js"></script>
    <!-- END: Page JS-->
	<script language="javascript">	
		$(document).ready(function () {
			$(document).on('click', '#btdelpjm', function(e){
				var pjmnumber = $(this).data('pjmnumber');	
				var pg = $(this).data('pg');					
				//SwalDelete(qtmnumber,pjmnumber);
				//Sweet Alert
				Swal.fire({
					title: "Are you sure?",
					html: "คุณต้องการลบโปรเจค  " + pjmnumber + " นี้ใช่หรือไหม่ !!!! ",
					type: "warning",
					showCancelButton: true,
					//confirmButtonColor: "#3085d6",
					//cancelButtonColor: "#d33",
					confirmButtonText: "Yes, delete it!",
					confirmButtonClass: "btn btn-primary",
					cancelButtonClass: "btn btn-danger ml-1",
					buttonsStyling: false,
					showLoaderOnConfirm: true,
					preConfirm: function() {
						return new Promise(function(resolve) {
							document.frm_del_project.pjm_nbr.value = pjmnumber;
							document.frm_del_project.pg.value = pg;
							//alert(pjmnumber);
							$.ajax({
								beforeSend: function () {
									//$('body').append('<div id="requestOverlay" class="request-overlay"></div>'); /*Create overlay on demand*/
									//$("#requestOverlay").show();/*Show overlay*/
								},
								type: 'POST',
								url: '../serverside/pjmpost.php',
								data: $('#frm_del_project').serialize(),
								timeout: 50000,
								error: function(xhr, error){
									showmsg('['+xhr+'] '+ error);
								},
								success: function(result) {	
									//console.log(result);
									//alert(result);
									var json = $.parseJSON(result);
									if (json.r == '0') {
										clearloadresult();
										Swal.fire({
											  title: "Error!",
											  html: json.e,
											  type: "error",
											  confirmButtonClass: "btn btn-danger",
											  buttonsStyling: false
											});
									}
									else {
										clearloadresult();
										Swal.fire({
											//position: "top-end",
											type: "success",
											title: "Delete Successful",
											showConfirmButton: false,
											timer: 1500,
											//customClass: "animated flipInX",
											confirmButtonClass: "btn btn-primary",
											buttonsStyling: false,			
											animation: false,
										});
										location.reload(true);
										//$(location).attr('href', 'qtdmnt.php?qtmnumber='+json.nb+'&pg='+json.pg)
									}
								},
								complete: function () {
									$("#requestOverlay").remove();/*Remove overlay*/
								}
							});		 
						});
					},
				allowOutsideClick: false			  
				});			
				e.preventDefault();
			});
		});	
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
		function pjmcopypost(pjm_nbr) {	
			var errorflag = false;
			var errortxt = "";
			document.getElementById("msghead").innerHTML = "พบข้อผิดผลาดในการบันทึกข้อมูล";
			var conf_info = "ระบบจะทำการ COPY ข้อมูลของใบเบิกหมายเลข "+pjm_nbr + " ไปเป็นใบเบิกใบใหม่ ท่านต้องการ COPY ใบเบิกใบนี้ไช่หรือไม่ ?"
			if(confirm(conf_info)) {
				document.frmpjmcopy.pjm_nbr.value = pjm_nbr;
				document.frmpjmcopy.submit();
			}
		}	
		//Disable This Function
		function delpjm(formname,pjm_nbr,pg) {
			if(confirm('ท่านต้องการลบข้อมูลการนี้ ไช่หรือไม่ ?')) {	
				document.forms[formname].pjm_nbr.value = pjm_nbr;
				document.forms[formname].pg.value = pg;
				document.forms[formname].submit();
			}
		}		
		
		function del_project(formname,pjm_nbr,pg) {
			if(confirm('ท่านต้องการลบ Project หมายเลขนี้ ไช่หรือไม่ ?')) {	
				document.forms[formname].pjm_nbr.value = pjm_nbr;
				document.forms[formname].pg.value = pg;
				//alert(pjm_nbr);
				$.ajax({
					beforeSend: function () {
						$('body').append('<div id="requestOverlay" class="request-overlay"></div>'); /*Create overlay on demand*/
						$("#requestOverlay").show();/*Show overlay*/
					},
					type: 'POST',
					url: '../serverside/pjmpost.php',
					data: $('#'+formname).serialize(),
					timeout: 50000,
					error: function(xhr, error){
						showmsg('['+xhr+'] '+ error);
					},
					success: function(result) {	
						console.log(result);
						//alert(result);
						var json = $.parseJSON(result);
						if (json.r == '0') {
							clearloadresult();
							showmsg(json.e);
						}
						else {
							clearloadresult();
							location.reload(true);
							//$(location).attr('href', 'qtdmnt.php?qtmnumber='+json.nb+'&pg='+json.pg)
						}
					},
					complete: function () {
						$("#requestOverlay").remove();/*Remove overlay*/
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