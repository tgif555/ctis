<?php 
//Temp
$user_login = "PISACHAM";

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

set_time_limit(0);
$curdate = date('Ymd');

$params = array();
$activeid = decrypt(mssql_escape($_REQUEST['activeid']), $key);

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
	<link rel="stylesheet" type="text/css" href="../theme/app-assets/css/plugins/forms/validation/form-validation.css">
	 
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
	
    <!-- BEGIN: Content-->
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-6 col-12 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="breadcrumb-wrapper col-12">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="index.html">Home</a>
                                </li>
                                <li class="breadcrumb-item"><a href="#">Master</a>
                                </li>
                                <li class="breadcrumb-item active"><a href="pjmall.php">All Master material</a>
                                </li>
                            </ol>
                        </div>
                    </div>
                    <h3 class="content-header-title mb-0">C'TIS Master</h3>
                </div>
                <div class="content-header-right col-md-6 col-12">
                    <div class="btn-group float-md-right" role="group" aria-label="Button group with nested dropdown">
						<a class="btn btn-primary white" data-toggle="modal" data-target="#div_frm_mat_add"><!--i class="feather icon-file-plus icon-left"--> </i>Add New Material</a>
						<? include("menu_mstr.php");	?>
					</div>
					>
                </div>
            </div>
			
            <div class="content-body">
                <!-- File export table -->
                <section id="file-export">
				<div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header ">
									<h4 class="card-title form-section text-primary "><i class="fa fa-cube"></i> Master Material</h4>
                                    <!--<a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a> -->
                                    <div class="heading-elements">
                                        <ul class="list-inline mb-0">
                                            <li><a data-action="collapse"><i class="feather icon-minus"></i></a></li>
                                            <li><a data-action="reload"><i class="feather icon-rotate-cw"></i></a></li>
                                            <li><a data-action="expand"><i class="feather icon-maximize"></i></a></li>
                                            <!--<li><a data-action="close"><i class="feather icon-x"></i></a></li>-->
                                        </ul>
                                    </div>
                                </div>
								
                                <div class="card-content collapse show">
                                    <div class="card-body card-dashboard" style="margin-top:-20px;">
										<div class="table-responsive"><!--class="table-responsive" -->
										<!-- table table-sm m-0 p-0 compact -->
										<table  id="matmstr" class="table table-striped" width="100%">
											<thead>
												<tr class="bg-primary font-weight-bold white text-center">
													
													<th >No.</th>
													<th >Material <br> Code</th>
													<th >Material Name</th>
													<th >Material Name <br> Eng</th>
													<th >Material <br> Unit</th>
													<th >Material <br> Group</th>
													<th >Material <br> cat</th>
													<th >Cost</th>
													<th >Price</th>
													<th >Status</th>
													<th >Action</th>
													
												</tr>
											</thead>   
												<tbody>
												<?php
												$n = 0;																																																																									
												
												$sql_mat = "SELECT * from mat_mstr INNER JOIN mag_mstr ON mag_code = mat_mag_code";
												
												$result_mat = sqlsrv_query( $conn, $sql_mat,$params);																					
												while($r_mat = sqlsrv_fetch_array($result_mat, SQLSRV_FETCH_ASSOC)) {	
													$mat_code = html_escape($r_mat['mat_code']);
													$mat_th_name = html_escape($r_mat['mat_th_name']);
													$mat_en_name = html_escape($r_mat['mat_en_name']);
													$mat_mag_code = html_escape($r_mat['mat_mag_code']);
													$mat_mag_name = html_escape($r_mat['mag_name']);
													$mat_cat_code = html_escape($r_mat['mat_cat_code']);
													$mat_matcat_name =findsqlval("matcat_mstr", "matcat_name", "matcat_code",$mat_cat_code,$conn);
													$mat_detail = html_escape($r_mat['mat_detail']);
													$mat_unit_code = html_escape($r_mat['mat_unit_code']);
													$mat_customer_unit_price = html_escape($r_mat['mat_customer_unit_price']);	
													$mat_contractor_unit_price = html_escape($r_mat['mat_contractor_unit_price']);
													
													$mat_active = $r_mat['mat_active'];
													if ($mat_active == "1") { $mat_active_text = "ACTIVE"; }
													else {$mat_active_text = "NOT ACTIVE"; }	
													
												
													$n++;																										
													?>	
													<tr >
														
														<td class="text-center"><?php echo $n+($currentpage-1)*$pagesize; ?></td>
														<td ><?php echo $mat_code; ?></td>
														<td ><?php echo $mat_th_name; ?></td>
														<td ><?php echo $mat_en_name; ?></td>
														<td class="text-center"><?php echo $mat_unit_code; ?></td>
														<td ><?php echo $mat_mag_name; ?></td>
														<td ><?php echo $mat_matcat_name; ?></td>
														<td class="text-right"><?php echo number_fmt($mat_contractor_unit_price,2); ?></td>
														<td class="text-right"><?php echo number_fmt($mat_customer_unit_price,2); ?></td>
														<td class="text-center">
															<? if($mat_active == "1"){?>
																<span class="badge badge-success"><?php echo $mat_active_text; ?></span><?
																}else{?>
																	<span class="badge badge-danger"><?php echo $mat_active_text; ?></span><?
																}
																?>
														
														</td>														
																	
														<td >
															<!-- Start btn-group -->
															<div >
																<button type="button" class="btn btn-info dropdown-toggle btn-sm " data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>
																<div class="dropdown-menu">
																	<a class="dropdown-item" id="confirdm-text"><i class="fa fa-search-plus"></i>View</a>
																	<a class="dropdown-item" href="#div_frm_mat_edit<?php echo $mat_code?>" data-toggle="modal"><i class="fa fa-pencil-square-o"></i> Edit/Update</a>
																	<a class="dropdown-item" id="btdel" data-id="<?php echo $mat_code; ?>" href="javascript:void(0)"><i class="fa fa-trash-o"></i> Delete</a>
																</div>
																<!-- <button type="button" class="btn btn-outline-danger mr-1 mb-1" id="confirm-text">
																	Error
																</button> -->
																
															</div>
															<!-- /btn-group -->
															
															<div class="modal fade" id="div_frm_mat_edit<?php echo $mat_code?>">
																<div class="modal-dialog modal-lg">
																<div class="modal-content">
																	<div class="modal-header bg-warning white">
																		<h4 class="modal-title">Edit Material</h4>
																		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
																			<span aria-hidden="true">&times;</span>
																		</button>
																	</div>
																						
																	<div class="modal-body">
																		<form name="frm_mat_edit<?php echo $mat_code?>" id="frm_mat_edit<?php echo $mat_code?>" autocomplete=OFF>
																			<input name="action" type=hidden value="matedit">
																			<input type="hidden" name="csrf_securecode" value="<?php echo $csrf_securecode?>">
																			<input type="hidden" name="csrf_token" value="<?php echo md5($csrf_token)?>">																
																			<input name="pg" type="hidden" value="<?php echo $currentpage;?>">
																			
																			<div class="">
																				<div class="form-group col-lg-12 row">
																					<div  class="col-sm-6">
																						<label class="text-bold-600">Material Code:</label>
																						<input type="text" class="form-control"  name="mat_code" id="mat_code" value="<?php echo $mat_code?>" readonly maxlength="30">
																					</div>
																				</div>
																				<div class="form-group col-lg-12 row">
																					<div  class="col-sm-6">
																						<label class="text-bold-600">Material Name(TH):</label>
																						<input type="text" class="form-control" name="mat_th_name" id="mat_th_name" value="<?php echo $mat_th_name?>" maxlength="30" >
																					</div>
																					<div  class="col-sm-6">
																						<label class="text-bold-600">Material Name(EN):</label>
																						<input type="text" class="form-control" name="mat_en_name" id="mat_en_name" value="<?php echo $mat_en_name?>" maxlength="30" >
																					</div>
																				</div>
																				<div class="form-group col-lg-12 row">
																					<div class="col-sm-6">
																						<label class="text-bold-600">Material Group:</label>
																						<select name="mat_mag_code" class="form-control form-control-sm select2">
																							<option value="">--Select--</option>
																							<?php 
																							$sql_mag = "SELECT * FROM mag_mstr order by mag_seq";
																							$result_mag_list = sqlsrv_query( $conn,$sql_mag);																													
																							while($r_mag_list=sqlsrv_fetch_array($result_mag_list, SQLSRV_FETCH_ASSOC)) {
																							?>
																								<option value="<?php echo $r_mag_list['mag_code'];?>"
																									<?php if ($mat_mag_code == $r_mag_list['mag_code']) {echo "selected='selected'";}?>>
																									<?php echo $r_mag_list['mag_name'];?>
																								</option> 
																							<?php } ?>
																						</select>
																					</div>
																					<div class="col-sm-6">
																						<label class="text-bold-600">Material Category:</label>
																						<select name="mat_cat_code" id="mat_cat_code" class="form-control select2">
																							<option value="">--Select--</option>
																							<?php 
																							$sql_matcat = "SELECT * FROM  matcat_mstr order by matcat_seq";
																							$result_matcat_list = sqlsrv_query( $conn,$sql_matcat);																														
																							while($r_matcat_list=sqlsrv_fetch_array($result_matcat_list, SQLSRV_FETCH_ASSOC)) {
																							?>
																								<option value="<?php echo $r_matcat_list['matcat_code'];?>"
																									<?php if ($mat_cat_code == $r_matcat_list['matcat_code']) {echo "selected='selected'";}?>>
																									<?php echo $r_matcat_list['matcat_name'];?>
																								</option> 
																							<?php } ?>
																						</select>
																					</div>
																				</div>
																				<div class="form-group col-lg-12 row">
																					<div class="col-sm-6">
																						<label class="text-bold-600">Material Unit:</label>
																						<select name="mat_unit_code" class="form-control select2">
																								<option value="">--Select--</option>
																								<?php 
																								$sql_unit = "SELECT * FROM unit_mstr order by unit_seq";
																								$result_unit_list = sqlsrv_query( $conn,$sql_unit);																													
																								while($r_unit_list=sqlsrv_fetch_array($result_unit_list, SQLSRV_FETCH_ASSOC)) {
																								?>
																									<option value="<?php echo $r_unit_list['unit_code'];?>"
																										<?php if ($mat_unit_code == $r_unit_list['unit_code']) {echo "selected='selected'";}?>>
																										<?php echo $r_unit_list['unit_name'];?>
																									</option> 
																								<?php } ?>
																						</select>
																					
																					</div>
																					
																					<div class="col-sm-6">
																						<label class="text-bold-600">Status:</label>
																						<select name="mat_active" class="form-control select2">
																							<option value="">--Select--</option>
																							<option value="0" <?php if ($mat_active == "0") {echo "selected=selected'";}?>>NOT</option>
																							<option value="1" <?php if ($mat_active == "1") {echo "selected=selected'";}?>>ACTIVE</option>
																						</select>
																					</div>
																				</div>	
																					
																				
																				<div class="form-group col-lg-12 row">
																							
																						<div class="col-sm-6">
																							<label class="text-bold-600">Cost:</label>
																							<input class="form-control text-right" name="mat_contractor_unit_price" value="<?php echo $mat_contractor_unit_price?>" >
																						</div>
																						<div class="col-sm-6">
																							<label >Price:</label>
																							<input class="form-control text-right" name="mat_customer_unit_price" value="<?php echo $mat_customer_unit_price?>" >
																						</div>
																					
																				</div>
																				<div class="form-group col-lg-12 row">
																					<div class="col-sm-12">
																						<label class="text-bold-600" >Detail:</label>
																						<textarea name="mat_detail" rows=3 class="form-control"><?php echo $mat_detail?></textarea>
																					</div>
																					
																				</div>
																				
																			</div>
																		</form>
																		<div class="modal-footer">
																			<button type="button" class="btn btn-warning" onclick="matpostform('<?php echo "frm_mat_edit".$mat_code;?>')">
																				<i class="icon-check icon-white"></i>
																				<span>Save Changes</span>
																			</button>											
																		</div>
																	</div>
																</div>
																</div>
															</div>
															
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
	
	<!-- Modal add Mat-->
		<div class="modal fade" id="div_frm_mat_add">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-header bg-primary white">
						<h4 class="modal-title"><i class="fa fa-file-text-o"></i> New Material</h4>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">																																																														
						<form name="frm_mat_add" id="frm_mat_add" autocomplete=OFF>
							<input type="hidden" name="action" value="matadd">	
							<input type="hidden" name="csrf_securecode" value="<?php echo $csrf_securecode?>">
							<input type="hidden" name="csrf_token" value="<?php echo md5($csrf_token)?>">

							<div class="text-sm">
								<div class="form-group col-lg-12 row">
									<div  class="col-sm-6">
										<label class="text-bold-600">Material Code:</label>
										<input class="form-control text-uppercase" type="text" name="mat_code" id="mat_code" placeholder="MAT-00X" maxlength="30"  required data-validation-required-message="This name field is required" >
									</div>
									
								</div>
								
								<div class="form-group col-lg-12 row">
									<div  class="col-sm-6">
										<label class="text-bold-600">Material Name(TH):</label>
										<input type="text" name="mat_th_name" id="mat_th_name" maxlength="30" class="form-control" required data-validation-required-message="This name field is required" >
									</div>
									<div  class="col-sm-6">
										<label class="text-bold-600">Material Name(EN):</label>
										<input type="text" name="mat_en_name" id="mat_en_name" maxlength="30" class="form-control" >
									</div>
								</div>
								
								<div class="form-group col-lg-12 row">
									<div class="col-sm-6">
										<label class="text-bold-600">Material Group:</label>
										<select name="mat_mag_code" class="form-control form-control-sm select2">
											<option value="">--Select--</option>
											<?php 
											$sql_mag = "SELECT * FROM mag_mstr order by mag_seq";
											$result_mag_list = sqlsrv_query( $conn,$sql_mag);																													
											while($r_mag_list=sqlsrv_fetch_array($result_mag_list, SQLSRV_FETCH_ASSOC)) {
											?>
												<option value="<?php echo $r_mag_list['mag_code'];?>"><?php echo $r_mag_list['mag_name'];?></option> 
											<?php } ?>
										</select>
									
									</div>
									<div class="col-sm-6">
										<label class="text-bold-600">Material Category:</label>
										<select name="mat_cat_code" id="mat_cat_code" class="form-control form-control-sm select2">
												<option value="">--Select--</option>
												<?php 
												$sql_matcat = "SELECT * FROM  matcat_mstr order by matcat_seq";
												$result_matcat_list = sqlsrv_query( $conn,$sql_matcat);																													
												while($r_matcat_list=sqlsrv_fetch_array($result_matcat_list, SQLSRV_FETCH_ASSOC)) {
												?>
													<option value="<?php echo $r_matcat_list['matcat_code'];?>"><?php echo $r_matcat_list['matcat_name'];?></option> 
												<?php } ?>
										</select>
									
									</div>
								</div>
								<div class="form-group col-lg-12 row">
									<div class="col-sm-6">
										<label class="text-bold-600">Material Unit:</label>
										<select name="mat_unit_code" id="mat_unit_code" class="form-control form-control-sm select2">
												<option value="">--Select--</option>
												<?php 
												$sql_unit = "SELECT * FROM unit_mstr order by unit_seq";
												$result_unit_list = sqlsrv_query( $conn,$sql_unit);																													
												while($r_unit_list=sqlsrv_fetch_array($result_unit_list, SQLSRV_FETCH_ASSOC)) {
												?>
													<option value="<?php echo $r_unit_list['unit_code'];?>"><?php echo $r_unit_list['unit_name'];?></option> 
												<?php } ?>
										</select>
									
									</div>
									<div  class="col-sm-6">
										<label class="text-bold-600">Status:</label>
										<select name="mat_active" id="mat_active" class="form-control  	form-control-sm select2">
											<option value="">--Select--</option>
											<option value="0">NOT</option>
											<option value="1">ACTIVE</option>
										</select>
									</div>
								</div>
								<div class="form-group col-lg-12 row">
									<div  class="col-sm-6">
										<label class="text-bold-600">Cost: (ราคาต้นทุน) </label>
										<input class="form-control text-right" name="mat_contractor_unit_price" id="mat_contractor_unit_price" >
									</div>
									<div  class="col-sm-6">
										<label class="text-bold-600">Price: (ราคาขาย) </label>
										<input class="form-control text-right" name="mat_customer_unit_price" id="mat_customer_unit_price" >
									</div>
								</div>
								<div class="form-group col-lg-12 row">
									<div class="col-lg-12">
										<label class="text-bold-600">Detail:</label>
										<textarea name="mat_detail" id="mat_detail" rows=3 class="form-control"></textarea>
									</div>
									
								</div>
								
								
												
							</div>
						</form>																																																			
					</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-success fileinput-button paddingleftandright10 margintop20 marginleft20" data-toggle="modal" onclick="matpostform('<?php echo "frm_mat_add";?>')">
								<i class="icon-check icon-white"></i>
								<span>Save</span>
							</button>											
						</div>	
						
				</div>					
			</div>
		</div>
	<!--End Modal add Mat-->	
		<form name="frmdelete" id="frmdelete" method="post" action="../serverside/matmstrpost.php">
			<input type="hidden" name="action" value="matdel">
			<input type="hidden" name="csrf_securecode" value="<?php echo $csrf_securecode?>">
			<input type="hidden" name="csrf_token" value="<?php echo md5($csrf_token)?>">
			<input type="hidden" name="mat_code">
			<input type="hidden" name="pg">
		</form>	
	
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
	<script src="../theme/app-assets/vendors/js/forms/validation/jqBootstrapValidation.js"></script>
	<script src="../theme/app-assets/vendors/js/extensions/sweetalert2.all.min.js"></script>
    <script src="../theme/app-assets/vendors/js/extensions/polyfill.min.js"></script>
    <!-- END: Page Vendor JS-->

    <!-- BEGIN: Theme JS-->
    <script src="../theme/app-assets/js/core/app-menu.js"></script>
    <script src="../theme/app-assets/js/core/app.js"></script>
    <!-- END: Theme JS-->

    <!-- BEGIN: Page JS-->
  
	 <!-- <script src="../theme/app-assets/js/scripts/tables/datatables/datatable-mstr.js"></script>
	<script src="../theme/app-assets/js/scripts/extensions/sweet-alerts-matmstr.js"></script>-->
	
    <!-- END: Page JS-->
	

	<script language="javascript">		
			
		function showdata() {													
			document.frm.submit();												
		}
		function matpostform(formid) {
			$(document).ready(function () {   
				$.ajax({
					beforeSend: function () {
						$('body').append('<div id="requestOverlay" class="request-overlay"></div>'); /*Create overlay on demand*/
						$("#requestOverlay").show();/*Show overlay*/
					},
					type: 'POST',
					url: '../serverside/matmstrpost.php',
					data: $('#'+formid).serialize(),
					timeout: 50000,
					error: function(xhr, error){
						showmsg('['+xhr+'] '+ error);
					},
					success: function(result) {
						
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
							$(location).attr('href', 'matmstrmnt.php?activeid='+json.nb+'&pg='+json.pg)
						}
						
					},
					complete: function () {
						$("#requestOverlay").remove();/*Remove overlay*/
					}
				});
			});
		}
				
		function gotopage(mypage) {							
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
	<script language="javascript">	
		$(document).ready(function() {
			$('#matmstr').DataTable({
				"scrollX": true,
				paging: true,
				columnDefs: [{
					"visible": false, "targets": [6] 
				}],
				
			});
			
			// confirm options
		
		  $(document).on('click', '#btdel', function(e){
				var mat_code = $(this).data('id');
				SwalDelete(mat_code);
				e.preventDefault();
			});
		  
		});
		function SwalDelete(mat_code){
			Swal.fire({
				title: "Are you sure?",
				text: "คุณต้องการลบรหัส "+ mat_code +" นี้ใช่หรือไหม่ !!!! ",
				type: "warning",
				showCancelButton: true,
				confirmButtonColor: "#3085d6",
				cancelButtonColor: "#d33",
				confirmButtonText: "Yes, delete it!",
				confirmButtonClass: "btn btn-primary",
				cancelButtonClass: "btn btn-danger ml-1",
				buttonsStyling: false,
				showLoaderOnConfirm: true,
				preConfirm: function() {
					return new Promise(function(resolve) {
						document.frmdelete.mat_code.value = mat_code;  
						$.ajax({
							type: 'POST',
							url: '../serverside/matmstrpost.php',
							data: $('#frmdelete').serialize(),
							ฝฝdata: 'mat_code='+mat_code,
							//dataType: 'json'
							success: function(result) {	
								var json = $.parseJSON(result);
								if (json.r == '0') {
									clearloadresult();
									Swal.fire({
									  title: "Error!",
									  text: json.e,
									  type: "error",
									  confirmButtonClass: "btn btn-danger",
									  buttonsStyling: false
									});
								}
								else {
									clearloadresult();
										Swal.fire({
											position: "top-end",
											type: "success",
											title: "ลบเรียบร้อยค่ะ",
											showConfirmButton: false,
											timer: 1500,
											confirmButtonClass: "btn btn-primary",
											buttonsStyling: false
										});
									 
									 window.location.reload();
								}
							},
							complete: function () {
								$("#requestOverlay").remove();/*Remove overlay*/
							}
						})
					 
					});
				},
			allowOutsideClick: false			  
			});	
		
		}
		
		
	</script>
	
</body>
<!-- END: Body-->

</html>