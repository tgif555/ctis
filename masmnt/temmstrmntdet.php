<?php 
//Temp
	$user_login = "PISACHAM";
	$gbv_editprice = false;
	//$gbv_auction_type = "PRICE"; //PRICE,SEQ
	$gbv_auction_type = "SEQ";
	
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
	clearstatcache();

	$activeid = html_escape(decrypt($_REQUEST['activeid'], $key));
	$tem_code = html_escape(decrypt($_REQUEST['temnumber'], $key));
	//$pg = html_escape($_REQUEST['pg']);
	
	$curdate = date('d/m/Y');
	$filepath_at = "../_fileuploads/at/";
	
	
	$params = array($tem_code);
	$sql_tem = "SELECT * from tem_mstr where tem_code = ? ";
	$result_tem = sqlsrv_query($conn, $sql_tem,$params);	
	$rec_tem = sqlsrv_fetch_array($result_tem, SQLSRV_FETCH_ASSOC);		
	if ($rec_tem) {
		$tem_code = mssql_escape($rec_tem['tem_code']);
		$tem_name = mssql_escape($rec_tem['tem_name']);
		//$tem_engm_code = mssql_escape($rec_tem['tem_engm_code']);
		//$tem_engm_name = findsqlval("engm_mstr"," engm_name", "engm_code", $tem_engm_code,$conn);
		$tem_leader_name = mssql_escape($rec_tem['tem_leader_name']);
		$tem_addr = mssql_escape($rec_tem['tem_addr']);
		$tem_district = mssql_escape($rec_tem['tem_district']);
		$tem_amphur = mssql_escape($rec_tem['tem_amphur']);
		$tem_province = mssql_escape($rec_tem['tem_province']);
		$tem_zip = mssql_escape($rec_tem['tem_zipcode']);	
		$tem_tel = mssql_escape($rec_tem['tem_tel']);
		$tem_lineid = mssql_escape($rec_tem['tem_lineid']);
		$tem_email = mssql_escape($rec_tem['tem_email']);	
			
		$tem_active = mssql_escape($rec_tem['tem_active']);
		if ($tem_active == "1") { $tem_active_text = "ACTIVE"; }
		else {$tem_active_text = "NOT ACTIVE"; }	
		
		
		
		$tem_create_by = mssql_escape($rec_tem['tem_create_by']);	
		$tem_create_by = findsqlval("emp_mstr","emp_th_firstname+' '+emp_th_lastname", "emp_user_id", $tem_create_by,$conn);						
	}
	else {
		
		$path = "authorize.php?msg=ทีม $tem_code ได้ถูกลบออกจากระบบแล้วค่ะ"; 
		echo "<meta http-equiv=\"refresh\" content=\"0;URL=".$path."\" />";
	}	
	
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
    <title>Users Edit - Stack Responsive Bootstrap 4 Admin Template</title>
    <link rel="apple-touch-icon" href="../theme/app-assets/images/ico/apple-icon-120.png">
    <link rel="shortcut icon" type="image/x-icon" href="../theme/app-assets/images/ico/favicon.ico">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:300,300i,400,400i,500,500i%7COpen+Sans:300,300i,400,400i,600,600i,700,700i" rel="stylesheet">

    <!-- BEGIN: Vendor CSS-->
    <link rel="stylesheet" type="text/css" href="../theme/app-assets/vendors/css/vendors.min.css">
    <link rel="stylesheet" type="text/css" href="../theme/app-assets/css/plugins/forms/validation/form-validation.css">
    <link rel="stylesheet" type="text/css" href="../theme/app-assets/vendors/css/forms/selects/select2.min.css">
    <link rel="stylesheet" type="text/css" href="../theme/app-assets/vendors/css/pickers/pickadate/pickadate.css">
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
    <link rel="stylesheet" type="text/css" href="../theme/app-assets/css/pages/page-users.css">
    <!-- END: Page CSS-->

    <!-- BEGIN: Custom CSS-->
    <link rel="stylesheet" type="text/css" href="../theme/assets/css/style.css">
    <!-- END: Custom CSS-->

</head>
<!-- END: Head-->

<!-- BEGIN: Body-->

<body class="vertical-layout vertical-menu 2-columns menu-collapsed  fixed-navbar" data-open="hover" data-menu="vertical-menu" data-col="2-columns">
	<!-- BEGIN: Header-->
    <? include("../cismain/menu_header.php"); ?>	
	<!-- END: Header-->
	
	<!-- BEGIN: Main Menu-->
	<? include("../cismain/menu_leftsidebar.php"); ?>
	<!-- END: Main Menu-->
	

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
                                <li class="breadcrumb-item active"><a href="temmstmntall.php">All Master </a>
                                </li>
                            </ol>
                        </div>
                    </div>
                    <h3 class="content-header-title mb-0">C'TIS Team</h3>
                </div>
                <div class="content-header-right col-md-6 col-12">
                    <div class="btn-group float-md-right" role="group" aria-label="Button group with nested dropdown">
						<a class="btn btn-primary white" href="temmstrmntadd.php"><!--i class="feather icon-file-plus icon-left"--> </i>Add New Team</a>
						<? include("menu_mstr.php");	?>
					</div>
					
                </div>
            </div>
            <div class="content-body">
                <!----- content -->
				<div class="content-body">
					<!-- users view start -->
					<section class="users-view">
						
						<!-- users view card data start -->
						<div class="card">
							<div class="card-header ">
                                   <h4 class="form-section text-primary"><i class="fa fa-users"></i> Team Maintenance</h4>
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
							<div class="card-content">
								
								<div class="card-body" style="margin-top:-20px;">
											
									<div class="row" style="margin-top:-20px;">
										<div class="col-12 col-md-6 ">
											<form class="form form-horizontal form-bordered">
												<div class="form-body " >
												
													<h4 class="form-section"><i class="feather icon-user"></i> Team Info</h4>
												
													<div class="form-group row" style="margin-top:-20px;">
														<label class="col-md-3 label-control" >ชื่อทีม :  </label>
														<div class="col-md-9">
															<?php echo $tem_name; ?>
														</div>
													</div>
													<div class="form-group row">
														<label class="col-md-3 label-control" >หัวหน้าทีม :  </label>
														<div class="col-md-9">
															<?php echo $tem_leader_name; ?>
														</div>
													</div>

													<div class="form-group row">
														<label class="col-md-3 label-control" >Tel: </label>
														<div class="col-md-3">
															<?php echo $tem_tel; ?> 
														</div>
														<label class="col-md-3 label-control" >Line id : </label>
														<div class="col-md-3">
															<?php echo $tem_lineid; ?> 
														</div>
													</div>

													<div class="form-group row">
														<label class="col-md-3 label-control" for="projectinput4">ที่อยู่ : </label>
														<div class="col-md-9">
															<?php echo $tem_addr."  ตำบล/แขวง ".$tem_district." อำเภอ/เขต ".$tem_amphur ?><br><? echo " จังหวัด ".$tem_province."  ".$tem_zip; ?>
														</div>
													</div>
													
													<div class="form-group row last">
														<label class="col-md-3 label-control" for="projectinput4">Status: </label>
														<div class="col-md-9">
															<? if($tem_active == "1"){?>
																<span class="badge badge-success"><?php echo $tem_active_text; ?></span><?
																}else{?>
																	<span class="badge badge-danger"><?php echo $tem_active_text; ?></span><?
																}
																?>
														</div>
													</div>
												</div>

                                                

                                           
											</form>
											
											<div class="form-group text-right">
												<button type="button" class="btn btn-min-width mr-1 mb-1 btn-danger btn-sm" onclick="document.location.href='temmstrmntall.php'" ><i class="feather icon-skip-back"></i>Back</button>
												<button type="button" class="btn btn-min-width mr-1 mb-1 btn-warning btn-sm" onclick="document.location.href='temmstrmntedit.php?temnumber=<?php echo encrypt($tem_code, $key);?>'" ><i class="feather icon-edit"></i>Edit</button>
                                            </div>
											
										</div>
										
										
										<div class="col-12 col-md-6 ">
											<form class="form form-horizontal form-bordered">
												<div class="form-body">
													<h4 class="form-section"><i class="fa fa-calendar"></i> Schedule </h4>
													
												</div>

												<div class="table-responsive">
										
													<table id="schedule" class="table table-striped" >
															<thead>
																<tr class="bg-primary font-weight-bold white text-center">
																	<th >Quotation No.</th>
																	<th >Quotation Name.</th>
																	<th >Code Project </th>
																	<th >Name Project </th>
																	<th >Date Start</th>
																	<th >Date End</th>
																	<th >Status</th>
																	<th >Action</th>
																	
																</tr>
															</thead>   
															<tbody>
																<?php
																	$n = 0;																																																																									
																	//SELECT     temb_id, temb_tem_code, temb_name, temb_age, 
																	//temb_gender, temb_detail, temb_create_by, temb_create_date, 
																	//temb_update_by, temb_update_date
																	//FROM         temb_det
																	$params = array($tem_code);
																														
																	$sql_sche = "SELECT distinct pjm_mstr.pjm_nbr, pjm_mstr.pjm_name,  ".
																	"pjm_mstr.pjm_start_date, pjm_mstr.pjm_end_date, qtm_mstr.qtm_tem_code, ".
																	"tem_mstr.tem_name, tem_mstr.tem_engm_code, pjm_mstr.pjm_pjst_code, ".
																	"qtm_mstr.qtm_nbr,qtm_mstr.qtm_name, qtm_mstr.qtm_start_date, qtm_mstr.qtm_end_date ".
																	"FROM  pjm_mstr right JOIN ".
																	"qtm_mstr ON pjm_mstr.pjm_nbr = qtm_mstr.qtm_pjm_nbr INNER JOIN ".
																	"tem_mstr ON qtm_mstr.qtm_tem_code = tem_mstr.tem_code where tem_code = ? ";
																	
																	$result_sche = sqlsrv_query( $conn, $sql_sche,$params);																					
																	while($r_sche = sqlsrv_fetch_array($result_sche, SQLSRV_FETCH_ASSOC)) {	
																		$pjm_nbr = html_escape($r_sche['pjm_nbr']);
																		$pjm_name = html_escape($r_sche['pjm_name']);
																		$qtm_nbr = html_escape($r_sche['qtm_nbr']);
																		$qtm_name = html_escape($r_sche['qtm_name']);
																		$pjm_start_date = html_escape($r_sche['pjm_start_date']);
																		$pjm_end_date = html_escape($r_sche['pjm_end_date']);
																		$pjm_pjst_code = html_escape($r_sche['pjm_pjst_code']);
																		
																		$qtm_start_date = html_escape($r_sche['qtm_start_date']);
																		$qtm_end_date = html_escape($r_sche['qtm_end_date']);
																		$n++;																										
																		?>	
																		<tr >
																			<td class="text-center "><?php echo $qtm_nbr; ?></td>
																			<td class="text-center "><?php echo $qtm_name; ?></td>
																			<td class="text-center "><?php echo $pjm_nbr; ?></td>
																			<td ><?php echo $pjm_name; ?></td>
																			<td class="text-center"><?php echo dmytx($qtm_start_date); ?></td>
																			<td class="text-center"><?php echo dmytx($qtm_end_date); ?></td>
																			<td class="text-center"><?php echo $pjm_pjst_code; ?></td>
																			<td class="text-center">
																				<!-- Start btn-group -->
																				<div >
																					<button type="button" class="btn btn-info btn-sm "  aria-haspopup="true" aria-expanded="false">View</button>
																					
																				</div>
																			<!-- /btn-group -->
																			</td>
																		</tr>
																	<?php }?>		
															</tbody>
													</table>
												</div>
                                                

                                           
											</form>
											
											
										</div>
										
									</div>
								</div>
							</div>
						</div>
						<!-- users view card data ends -->
						
					</section>
                <!-- users view ends -->
				</div>
				
				<!----end Content -->
				<!-- Headings -->
                <section id="html-headings-default" class="row match-height">
                    <div class="col-sm-12 col-md-6">
                        <div class="card">
                            <div class="card-header border" >
								<!--<h4 class="form-section text-primary"><i class="fa fa-cube"></i> Team Maintenance</h4>-->
                                <h4 class="card-title text-primary"> <i class="feather icon-users"></i> สมาชิกในทีม : </h4>
                                <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                                <div class="heading-elements">
                                   
									<ul class="list-inline mb-0">
										<button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#div_frm_memteam_add" >Add New</button>
                                        <li><a data-action="expand"><i class="feather icon-maximize"></i></a></li>
                                    </ul>
									
							</button>	
                                </div>
                            </div>
                            <div class="card-content">
								
								<div class="card-body" >
                                    <div class="table-responsive">
										<!--<table id="temb_det" class="table table-sm m-0 p-0 compact">-->
										<table id="temb_det" class="table table-striped">
												<thead>
													<tr class="bg-primary font-weight-bold white text-center">
														<th >No.</th>
														<th >รหัส</th>
														<th >ชื่อ</th>
														<th >อายุ</th>
														<th >เพศ</th>
														
														<th >Action</th>
														
													</tr>
												</thead>   
												<tbody>
													<?php
														$n = 0;																																																																									
														//SELECT     temb_id, temb_tem_code, temb_name, temb_age, 
														//temb_gender, temb_detail, temb_create_by, temb_create_date, 
														//temb_update_by, temb_update_date
														//FROM         temb_det
														$params = array($tem_code);
																											
														$sql_temb_det = "SELECT * from temb_det where temb_tem_code = ? ";
														
														$result_temb_det = sqlsrv_query( $conn, $sql_temb_det,$params);																					
														while($r_temb_det = sqlsrv_fetch_array($result_temb_det, SQLSRV_FETCH_ASSOC)) {	
															$temb_id = html_escape($r_temb_det['temb_id']);
															$temb_name = html_escape($r_temb_det['temb_name']);
															$temb_age = html_escape($r_temb_det['temb_age']);
															$temb_gender = html_escape($r_temb_det['temb_gender']);
															$temb_detail = html_escape($r_temb_det['temb_detail']);
															
															$n++;																										
															?>	
															<tr >
																<td class="text-center"><?php echo $n; ?></td>
																<td class="text-center"><?php echo $temb_id; ?></td>
																<td ><?php echo $temb_name; ?></td>
																<td class="text-center"><?php echo $temb_age; ?></td>
																<td class="text-center"><?php echo $temb_gender; ?></td>
																
																<td class="text-center">
																	<!-- Start btn-group -->
																	<div >
																		<button type="button" class="btn btn-info dropdown-toggle btn-sm " data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>
																		<div class="dropdown-menu">
																			<a class="dropdown-item" href="#"><i class="fa fa-search-plus"></i> View</a>
																			<a class="dropdown-item" href="#div_frm_mat_edit<?php echo $mat_code?>" data-toggle="modal"><i class="fa fa-pencil-square-o"></i> Edit/Update</a>
																			<a class="dropdown-item" onclick="deltmemb('<?php echo $temb_id; ?>','<?php echo $currentpage;?>')"><i class="fa fa-trash-o"></i> Delete</a>
																			
																			
																		
																		</div>
																	</div>
																<!-- /btn-group -->
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
					<!-- ++++++++++++++ ความถนัด++++++++++++++++++++ -->
                    <div class="col-sm-12 col-md-6">
                        <div class="card">
                            <div class="card-header border" >
                                <h4 class="card-title text-primary"><i class="fa fa-flask"></i> ความถนัดของทีม : </h4>
                                <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                                <div class="heading-elements">
                                   
									<ul class="list-inline mb-0">
										<button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#div_frm_teap_add" >Add New</button>
                                        <li><a data-action="expand"><i class="feather icon-maximize"></i></a></li>
                                    </ul>
									
							</button>	
                                </div>
                            </div>
                            <div class="card-content">
								
								<div class="card-body">
                                    <div class="table-responsive">
										<!--<table id="temb_det" class="table table-sm m-0 p-0 compact">-->
										<table id="teap_det" class="table table-striped">
											<thead>
													<tr class="bg-primary font-weight-bold white text-center">
														<th >No.</th>
														<th >รหัส</th>
														<th >ความถนัด</th>
														<th >ราคา</th>
														<th >หน่วย</th>
														<th >Action</th>
														
													</tr>
												</thead>   
												<tbody>
													<?php
														$n = 0;																																																																									
														$params = array($tem_code);
														$sql_teap_det = "SELECT * from teap_det where teap_tem_code = ? ";
														
														$result_teap_det = sqlsrv_query( $conn, $sql_teap_det,$params);																					
														while($r_teap_det = sqlsrv_fetch_array($result_teap_det, SQLSRV_FETCH_ASSOC)) {	
															$teap_id = html_escape($r_teap_det['teap_id']);
															$teap_matcat_code = html_escape($r_teap_det['teap_matcat_code']);
															$teap_matcat_name  = findsqlval("matcat_mstr", "matcat_name", "matcat_code",$teap_matcat_code,$conn);
															$teap_price = html_escape($r_teap_det['teap_price']);
															$teap_unit = html_escape($r_teap_det['teap_unit']);
															
															
															if ((double)$teap_price > 0) {
																if ($teap_unit == "P") {
																	$teap_unit_text_disc = "%";
																}
																if ($teap_unit == "B") {
																	$teap_unit_text_disc = "บาท";
																}
															}
															
															
															$n++;																										
															?>	
															<tr >
																<td class="text-center"><?php echo $n; ?></td>
																<td class="text-center"><?php echo $teap_id; ?></td>
																<td ><?php echo $teap_matcat_name; ?></td>
																<td class="text-center"><?php echo $teap_price; ?></td>
																<td class="text-center"><?php echo $teap_unit_text_disc; ?></td>
																
																<td class="text-center">
																	<!-- Start btn-group -->
																	<div >
																		<button type="button" class="btn btn-info dropdown-toggle btn-sm " data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>
																		<div class="dropdown-menu">
																			<a class="dropdown-item" href="#"><i class="fa fa-search-plus"></i> View</a>
																			<a class="dropdown-item" href="#div_frm_mat_edit<?php echo $mat_code?>" data-toggle="modal"><i class="fa fa-pencil-square-o"></i> Edit/Update</a>
																			<a class="dropdown-item" onclick="delteap('<?php echo $teap_id; ?>','<?php echo $currentpage;?>')"><i class="fa fa-trash-o"></i> Delete</a>
																		</div>
																	</div>
																<!-- /btn-group -->
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
                </section>
            <!--/ Headings -->
				
				
            </div>
			
        </div>
    </div>
    <!-- END: Content-->
	
		<!-- Modal add team -->
		<div class="modal fade" id="div_frm_memteam_add">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header bg-primary white">
						<h4 class="modal-title"> <i class="feather icon-user-plus"></i> New Member Team</h4>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">																																																														
						<form name="frm_memteam_add" id="frm_memteam_add" autocomplete=OFF>
							<input type="hidden" name="action" value="memteamadd">	
							<input type="hidden" name="csrf_securecode" value="<?php echo $csrf_securecode?>">
							<input type="hidden" name="csrf_token" value="<?php echo md5($csrf_token)?>">

							<div class="text-sm">
								<div class="col-lg-12 row">
									<div  class="col-sm-6">
										<label>รหัสทีม :</label>
										<input type="text" name="temb_tem_code" id="temb_tem_code" value="<? echo $tem_code ?>" maxlength="30" class="form-control form-control-sm" readonly >
									</div>
									
								</div>
								<div class="col-lg-12 row">
									<div  class="col-sm-12">
										<label>ชื่อ :</label>
										<input type="text" name="memb_name" id="memb_name" maxlength="30" class="form-control form-control-sm" required data-validation-required-message="This name field is required" >
									</div>
									
								</div>
								
								<div class="col-lg-12 row">
									<div  class="col-sm-6">
										<label>อายุ :</label>
										<input type="text" name="memb_age" id="memb_age" maxlength="30" class="form-control form-control-sm" required data-validation-required-message="This name field is required" >
									</div>
									<div  class="col-sm-6">
										<label>เพศ :</label>
										<select name="memb_gender" id="memb_gender" class="form-control  form-control-sm select2">
											<option value="">--Select--</option>
											<option value="หญิง">หญิง</option>
											<option value="ชาย">ชาย</option>
										</select>
									</div>
								</div>
							</div>
						</form>																																																			
					</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-success fileinput-button paddingleftandright10 margintop20 marginleft20" data-toggle="modal" onclick="memtempostform('<?php echo "frm_memteam_add";?>')">
								<i class="icon-check icon-white"></i>
								<span>Save</span>
							</button>	

														
						</div>	
						
				</div>					
			</div>
		</div>
	<!--End Modal add team-->
	
	<!-- Modal add teap ความถนัด -->
		<div class="modal fade" id="div_frm_teap_add">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header bg-primary white">
						<h4 class="modal-title"><i class="fa fa-plus"></i> Add New ความถนัดของทีม </h4>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">																																																														
						<form name="frm_teap_add" id="frm_teap_add" autocomplete=OFF>
							<input type="hidden" name="action" value="teapadd">	
							<input type="hidden" name="csrf_securecode" value="<?php echo $csrf_securecode?>">
							<input type="hidden" name="csrf_token" value="<?php echo md5($csrf_token)?>">

							<div class="text-sm">
								<div class="col-lg-12 row">
									<div  class="col-sm-6">
										<label>รหัสทีม :</label>
										<input type="text" name="teap_tem_code" id="teap_tem_code" value="<? echo $tem_code ?>" maxlength="30" class="form-control form-control-sm" readonly >
									</div>
									
								</div>
								<div class="col-lg-12 row">
									<div  class="col-sm-12">
										<label>ความถนัด :</label>
										<select name="teap_matcat_code" id="teap_matcat_code" class="form-control form-control-sm select2">
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
								
								<div class="col-lg-12 row">
									<div  class="col-sm-6">
										<label>ราคา :</label>
										<input type="text" name="teap_price" id="teap_price" maxlength="30" class="form-control form-control-sm" required data-validation-required-message="This name field is required" >
									</div>
									<div  class="col-sm-6">
										<label>หน่วย :</label>
										<select name="teap_unit" id="teap_unit" class="form-control form-control-sm select2">
											<option value="">(select)</option>
											<option value="P">%</option>
											<option value="B">บาท</option>
										</select>
									</div>
								</div>
							</div>
						</form>																																																			
					</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-success fileinput-button paddingleftandright10 margintop20 marginleft20" data-toggle="modal" onclick="teaptempostform('<?php echo "frm_teap_add";?>')">
								<i class="icon-check icon-white"></i>
								<span>Save</span>
							</button>	

														
						</div>	
						
				</div>					
			</div>
		</div>
	<!--End Modal add teap ความถนัด -->
	
	<!--From delete temp สมาชิกในทีม -->
	<form name="frmtembdelete" id="frmtembdelete" method="post" action="../serverside/memtempost.php">
			<input type="hidden" name="action" value="memtembdel">
			<input type="hidden" name="csrf_securecode" value="<?php echo $csrf_securecode?>">
			<input type="hidden" name="csrf_token" value="<?php echo md5($csrf_token)?>">
			<input type="hidden" name="temb_id">
			<input type="hidden" name="pg">
	</form>	
	<!--End From delete temp สมาชิกในทีม -->
	
	<!--From delete teap ความถนัด -->
	<form name="frmteapdelete" id="frmteapdelete" method="post" action="../serverside/teaptempost.php">
			<input type="hidden" name="action" value="teapdel">
			<input type="hidden" name="csrf_securecode" value="<?php echo $csrf_securecode?>">
			<input type="hidden" name="csrf_token" value="<?php echo md5($csrf_token)?>">
			<input type="hidden" name="teap_id">
			<input type="hidden" name="pg">
	</form>	
	<!--End From delete teap ความถนัด -->
	
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
    <script src="../theme/app-assets/vendors/js/forms/select/select2.full.min.js"></script>
    <script src="../theme/app-assets/vendors/js/pickers/pickadate/picker.js"></script>
    <script src="../theme/app-assets/vendors/js/pickers/pickadate/picker.date.js"></script>
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
	
    <script src="../theme/app-assets/js/scripts/pages/page-users.js"></script>
    <script src="../theme/app-assets/js/scripts/navs/navs.js"></script>
	
    <!-- END: Page JS-->

</body>
<!-- END: Body-->
	<script>
		$(document).ready(function() {
			$('#temb_det').DataTable({
				"scrollX": true,
				paging: true,
				"autoWidth": false,
				columnDefs: [{
					targets: "datatable-nosort",
					orderable: false,
					"visible": false, "targets": [1] 
				}],
				 
				dom: 'frtip',
				"pageLength": 8
			});
			$('#teap_det').DataTable({
				"scrollX": true,
				paging: true,
				"autoWidth": false,
				columnDefs: [{
					"visible": false, "targets": [1] 
				}],
				dom: 'frtip',
				"pageLength": 8
			});
			$('#schedule').DataTable({
				"scrollX": true,
				 dom: 'frtip',
				 "pageLength": 4
			 });
		});
	</script>
	
	<script language="javascript">		
			
		function showdata() {													
			document.frm.submit();												
		}
		function memtempostform(formid) {
			$.ajax({
				beforeSend: function () {
					$('body').append('<div id="requestOverlay" class="request-overlay"></div>'); /*Create overlay on demand*/
					$("#requestOverlay").show();/*Show overlay*/
				},
				type: 'POST',
				url: '../serverside/memtempost.php',
				data: $('#'+formid).serialize(),
				timeout: 50000,
				error: function(xhr, error){
					showmsg('['+xhr+'] '+ error);
				},
				success: function(result) {
					
					var json = $.parseJSON(result);
					if (json.r == '0') {
						clearloadresult();
						//alert(json.e);
						showmsg(json.e);
					}
					else {
						clearloadresult();
						location.reload(true);
						//$(location).attr('href', 'temmstrmntdet.php?activeid='+json.nb+'&pg='+json.pg)
					}
					
				},
				complete: function () {
					$("#requestOverlay").remove();/*Remove overlay*/
				}
			});
		}
		function teaptempostform(formid) {
			
			$.ajax({
				beforeSend: function () {
					$('body').append('<div id="requestOverlay" class="request-overlay"></div>'); /*Create overlay on demand*/
					$("#requestOverlay").show();/*Show overlay*/
				},
				type: 'POST',
				url: '../serverside/teaptempost.php',
				data: $('#'+formid).serialize(),
				timeout: 50000,
				error: function(xhr, error){
					showmsg('['+xhr+'] '+ error);
				},
				success: function(result) {
					var json = $.parseJSON(result);
					if (json.r == '0') {
						clearloadresult();
						//alert(json.e);
						showmsg(json.e);
					}
					else {
						clearloadresult();
						location.reload(true);
						//$(location).attr('href', 'temmstrmntdet.php?activeid='+json.nb+'&pg='+json.pg)
					}
					
				},
				complete: function () {
					$("#requestOverlay").remove();/*Remove overlay*/
				}
			});
		}
		
		function delteap(teap_id,pg) {
			if(confirm('ท่านต้องการลบข้อมูลการนี้ ไช่หรือไม่ ?')) {
				document.frmteapdelete.teap_id.value = teap_id;
				$.ajax({
					beforeSend: function () {
						$('body').append('<div id="requestOverlay" class="request-overlay"></div>'); /*Create overlay on demand*/
						$("#requestOverlay").show();/*Show overlay*/
					},
					type: 'POST',
					url: '../serverside/teaptempost.php',
					data: $('#frmteapdelete').serialize(),
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
							location.reload(true);
							
						}
					},
					complete: function () {
						$("#requestOverlay").remove();/*Remove overlay*/
					}
				});
			}
		}
		
		function deltmemb(temb_id,pg) {
			if(confirm('ท่านต้องการลบข้อมูลการนี้ ไช่หรือไม่ ?')) {
				document.frmtembdelete.temb_id.value = temb_id;
				$.ajax({
					beforeSend: function () {
						$('body').append('<div id="requestOverlay" class="request-overlay"></div>'); /*Create overlay on demand*/
						$("#requestOverlay").show();/*Show overlay*/
					},
					type: 'POST',
					url: '../serverside/memtempost.php',
					data: $('#frmtembdelete').serialize(),
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
							location.reload(true);
							
						}
					},
					complete: function () {
						$("#requestOverlay").remove();/*Remove overlay*/
					}
				});
			}
		}		
		
		
		function loadresult() {
			$('#result').html("<center><img id='progress' src='../_images/loading0.gif' width=80 height=80><center>");
		}
		function clearloadresult() {
			$('#result').html("");
		}
		function showmsg(msg) {
			$("#modal-body").html(msg);
			$("#myModal").modal("show");
			
		}
		
	</script>
</html>