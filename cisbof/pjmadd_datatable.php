<?php 
//Temp
$user_login = "KOMSUNYU";

include("../_incs/acunx_metaheader.php");
//include("../_incs/chksession.php");  
include("../_incs/config.php");	
include("../_incs/funcServer.php");	
//include("../_incs/funcServer_project.php");
include("../_incs/acunx_cookie_var.php");
include "../_incs/acunx_csrf_var.php";

if (($_SERVER['REQUEST_METHOD'] == 'POST')) {
	if (!matchToken($csrf_key,$user_login)) {
		echo "System detect CSRF attack!!";
		exit;
	}
}

$activeid = html_escape($_REQUEST['activeid']);
$pg = html_escape($_REQUEST['pg']);
$curdate = date('d/m/Y');
clearstatcache();
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
    <title>Striped Row Forms - Stack Responsive Bootstrap 4 Admin Template</title>
    <link rel="apple-touch-icon" href="../theme/app-assets/images/ico/apple-icon-120.png">
    <link rel="shortcut icon" type="image/x-icon" href="../theme/app-assets/images/ico/favicon.ico">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:300,300i,400,400i,500,500i%7COpen+Sans:300,300i,400,400i,600,600i,700,700i" rel="stylesheet">

    <!-- BEGIN: Vendor CSS-->
    <link rel="stylesheet" type="text/css" href="../theme/app-assets/vendors/css/vendors.min.css">
    <link rel="stylesheet" type="text/css" href="../theme/app-assets/vendors/css/pickers/daterange/daterangepicker.css">
    <link rel="stylesheet" type="text/css" href="../theme/app-assets/vendors/css/pickers/datetime/bootstrap-datetimepicker.css">
    <link rel="stylesheet" type="text/css" href="../theme/app-assets/vendors/css/pickers/pickadate/pickadate.css">
	<link rel="stylesheet" type="text/css" href="../theme/app-assets/vendors/css/tables/datatable/datatables.min.css">
	<!--<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.20/datatables.min.css"/>-->
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
	<link rel="stylesheet" type="text/css" href="../theme/app-assets/fonts/font-awesome/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="../theme/app-assets/css/plugins/pickers/daterange/daterange.css">
    <!-- END: Page CSS-->

    <!-- BEGIN: Custom CSS-->
    <link rel="stylesheet" type="text/css" href="../theme/assets/css/style.css">
	<link rel="stylesheet" type="text/css" href="../theme/assets/css/project_style.css">
	
	<style type="text/css">
		/**/
	</style>
</head>
<!-- END: Head-->

<!-- BEGIN: Body-->

<body class="vertical-layout vertical-menu 2-columns menu-collapsed   fixed-navbar" data-open="hover" data-menu="vertical-menu" data-col="2-columns">

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
                                 <li class="breadcrumb-item"><a href="pjmall.php">Home</a>
                                </li>
                                <li class="breadcrumb-item"><a href="pjmall.php">Project</a>
                                </li>
                                <li class="breadcrumb-item active"><a href="pjmadd.php">New Project</a>
                                </li>
                            </ol>
                        </div>
                    </div>
                    <h3 class="content-header-title mb-0">New Project</h3>
                </div>
                <div class="content-header-right col-md-6 col-12">
                    <div class="btn-group float-md-right" role="group" aria-label="Button group with nested dropdown">
                        <div class="btn-group" role="group">
                            <!--<button class="btn btn-outline-primary dropdown-toggle dropdown-menu-right" id="btnGroupDrop1" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="feather icon-settings icon-left"></i>New Project</button>
							<div class="dropdown-menu" aria-labelledby="btnGroupDrop1"><a class="dropdown-item" href="card-bootstrap.html">New Project With Quatation</a><a class="dropdown-item" href="component-buttons-extended.html">New Project Without Quatation</a></div>-->
							<a class="btn btn-outline-primary" href="pjmadd.php"><i class="fa fa-file-o icon-left"></i>New Project</a>
						</div>
						<a class="btn btn-outline-primary" href="full-calender-basic.html"><i class="fa fa-download"></i></a>
						<a class="btn btn-outline-primary" href="full-calender-events.html"><i class="fa fa-calendar"></i></a>
                    </div>
                </div>
            </div>
            <div class="content-body">
                <!-- users edit start -->
                <section class="new-project">
					<div class="row">
                        <div class="col-12">
							<!--<div class="card">
								<div class="card-header border-0-bottom ">
									<h4 class="card-title">ADD New Project</h4>
									<a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
									<div class="heading-elements">
										<ul class="list-inline mb-0">
											<!--<li>
												<div class="btn btn-sm btn-danger" style="width:70px" onclick="loadresult();window.location.href='qtmall.php?activeid=<?php echo encrypt($qtm_nbr, $key);?>&pg=<?php echo $pg;?>'">													
													<i class="feather icon-skip-back mr-25"></i><span>Black<?php echo $qtm_nbr; ?></span>
												</div>
											</li>
											<li>
												<div class="btn btn-sm btn-warning" style="width:70px" onclick="loadresult();window.location.href='qtmedit.php?qtmnumber=<?php echo encrypt($qtm_nbr, $key);?>&pg=<?php echo $pg;?>'">
													<i class="feather icon-edit mr-25"></i><span>Edit</span>
												</div>
											</li>
											<li>
												<div class="btn btn-sm btn-info" style="width:70px" onclick="printform('qtmformrq.php?qtmnumber=<?php echo encrypt($qtm_nbr, $key);?>')">		
													<i class="feather icon-printer mr-25"></i><span>Print</span>
												</div>
											</li>
											<li><a data-action="expand"><i class="feather icon-maximize"></i></a></li>
											<li><a data-action="reload"><i class="feather icon-rotate-cw"></i></a></li>
										</ul>
									</div>
								</div>-->
							<div class="card">
								<div class="card-header mt-1 pt-0 pb-0" >
									<a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
									<div class="heading-elements">
                                        <ul class="list-inline mb-0">
                                            <li><a href="#div_add_qtd_product" class="btn btn-sm" data-toggle="modal">ADD QUOTATION</a></li>
                                            <li><a data-action="reload"><i class="feather icon-rotate-cw"></i></a></li>
                                            <li><a data-action="expand"><i class="feather icon-maximize"></i></a></li>
                                            <!--<li><a data-action="close"><i class="feather icon-x"></i></a></li>-->
                                        </ul>
                                    </div>
                                </div>
								<div class="card-content">                                    		
									<div class="card-body card-dashboard" style="margin-top:-20px;">
										<ul class="nav nav-tabs mb-2 mt-0" role="tablist">
											<li class="nav-item">
												<a class="nav-link d-flex align-items-center  active" id="project-tab" data-toggle="tab" href="#project" aria-controls="project" role="tab" aria-selected="true">
													<i class="fa fa-cube mr-25"></i><span class="d-none d-sm-block">Project Info</span>
												</a>
											</li>
											<li class="nav-item">
												<a class="nav-link d-flex align-items-center" id="customer-tab" data-toggle="tab" href="#customer" aria-controls="customer" role="tab" aria-selected="false">
													<i class="fa fa-user-o mr-25"></i><span class="d-none d-sm-block">Customer</span>
												</a>
											</li>
											<li class="nav-item">
												<a class="nav-link d-flex align-items-center" id="quotation-tab" data-toggle="tab" href="#quotation" aria-controls="quotation" role="tab" aria-selected="false">
													<i class="fa fa-file-text-o mr-25"></i><span class="d-none d-sm-block">Quatation List</span>
												</a>
											</li>	
										</ul>
										<!-- Start Project Tab -->
										<div class="tab-content">
											<div class="tab-pane active" id="project" aria-labelledby="project-tab" role="tabpanel">
												<form class="form">
												<div class="form-body">
													<h4 class="form-section"><i class="fa fa-cube"></i> Project Information</h4>
													<div class="form-group row">
														<div class="col-md-4">
															<div class="card">
																<div class="card-content">
																	<div class="media align-items-stretch">
																		<div class="p-2 text-center bg-warning">
																			<i class="icon-wallet font-large-2 white"></i>
																		</div>
																		<div class="py-1 px-2 media-body">
																			<h5 class="warning">Total Cost</h5>
																			<h5 class="text-bold-400">xx Baht</h5>
																			<div class="progress mt-1 mb-0" style="height: 7px;">
																				<div class="progress-bar bg-warning" role="progressbar" style="width: 60%" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"></div>
																			</div>
																		</div>
																	</div>
																</div>
															</div>													
															<div class="card">
																<div class="card-content">
																	<div class="media align-items-stretch">
																		<div class="p-2 text-center bg-warning bg-darken-2">
																			<i class="icon-wallet font-large-2 white"></i>
																		</div>
																		<div class="p-2 bg-warning white media-body">
																			<h5>Total Profit</h5>
																			<h5 class="text-bold-400 mb-0">5.6 M</h5>
																		</div>
																	</div>
																</div>
															</div>
														</div>
														<div class="col-md-8">
															<div class="row">
																<div class="col-md-4">
																	<div class="form-group">
																		<label >Project Number</label>
																		<input type="text" class="form-control " placeholder="Project Number" value="<?php echo $pjm_nbr = getpjmnbr("PJ-",$conn); ?>">
																	</div>
																</div>
																<div class="col-md-8">
																	<div class="form-group">
																		<label >Project Name</label>
																		<input type="text" class="form-control " placeholder="Project Name">
																	</div>
																</div>
															</div>
															<div class="row">
																<div class="col-md-4">
																	<div class="form-group">
																		<label >Project Type</label>
																		<select data-placeholder="Select a project type ..." class="select2-icons form-control" id="select2-icons">
																			<option value="" selected>(select)</option>
																				<?php 
																				$sql_pjt = "SELECT * FROM pjt_mstr order by pjt_seq";
																				$result_pjt = sqlsrv_query( $conn,$sql_pjt);																													
																				while($r_pjt=sqlsrv_fetch_array($result_pjt, SQLSRV_FETCH_ASSOC)) {
																				?>
																					<option value="<?php echo $r_pjt['pjt_code'];?>" data-icon="fa fa-wordpress"><?php echo $r_pjt['pjt_name'];?></option> 
																				<?php } ?>																																	
																		</select>
																	</div>
																</div>														
																<div class="col-md-8">
																	<div class="form-group">																											
																		<!-- -->
																	</div>
																</div>														
															</div>
															<div class="row">                                                        													
																<div class="col-md-8">
																	<div class="form-group">																											
																		<div class="sales pr-2 pt-0">
																			<div class="sales-today mb-2">
																				<p class="m-0">Project Status<span class="primary float-right"><i class="feather icon-arrow-up primary"></i> 6.89%</span></p>
																				<!--<div class="progress progress-sm mt-1 mb-0">
																					<div class="progress-bar progress-bar-striped progress-bar-animated bg-primary" role="progressbar" style="width: 70%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
																				</div>-->
																				<div class="progress my-75" style="height: 20px;">
																					<div class="progress-bar progress-bar-striped bg-primary" heightrole="progressbar" aria-valuenow="20" aria-valuemin="20" aria-valuemax="100" style="width:55%">55%</div>
																				</div>
																			</div>																	
																		</div>
																	</div>
																</div>
																<div class="col-md-4">
																	<!-- -->
																</div>															
															</div>
														</div>
													</div>		
													<h4 class="form-section"><i class="fa fa-map-o"></i> Project Address</h4>
													<div class="form-group row">                                                
														<div class="col-md-6">
															<div class="row">
																<div class="col-md-12">
																	<fieldset class="form-group">
																		<label for="placeTextarea">Project Address</label>
																		<textarea class="form-control" id="placeTextarea" rows="5" placeholder="Project Address"></textarea>
																	</fieldset>
																</div>
															</div>
															<div class="row">
																<div class="col-md-4">
																	<div class="form-group">
																		<label >Amphure</label>
																		<input type="textarea" class="form-control" placeholder="Project Number">
																	</div>
																</div>
																 <div class="col-md-4">
																	<div class="form-group">
																		<label >Province</label>
																		<input type="text" class="form-control" placeholder="Project Number">
																	</div>
																</div>
																<div class="col-md-4">
																	<div class="form-group">
																		<label >Post Code</label>
																		<input type="text" class="form-control" placeholder="Project Number">
																	</div>
																</div>
															</div>
														</div>
														<div class="col-md-6">
															<div class="row">
																<div class="col-md-6">
																	<fieldset class="form-group">
																		<label for="placeTextarea">Project Map</label>
																		<img src="../_images/wood_pattern1.jpg" border="0" style="width:220px; height:180px;">
																		<i class="fa fa-download"><font style="line-height:30px;"> Download</font></i>
																	</fieldset>
																	
																</div>
																<div class="col-md-6">														
																	<div class="form-group">
																		<label >Latitude</label>
																		<input type="textarea" class="form-control" placeholder="Project Number">
																	</div>															
																	<div class="form-group">
																		<label >Longtitude</label>
																		<input type="text" class="form-control" placeholder="Project Number">
																	</div>	
																	<div class="form-group">
																		<label >Google Map</label>
																		<input type="text" class="form-control" placeholder="Project Number">
																	</div>			
																</div>
															 </div>
														</div>
													</div>	
													<div class="form-group row"> <!-- Submit Button -->
														<div class="col-12 d-flex flex-sm-row flex-column justify-content-end mt-1">
															<button type="submit" class="btn btn-primary glow mb-1 mb-sm-0 mr-0 mr-sm-1">Save
																changes</button>
															<button type="reset" class="btn btn-light">Cancel</button>
														</div>
													</div>
												</div>
												</form>
												<!-- users edit project form ends -->
											</div>
											<div class="tab-pane" id="customer" aria-labelledby="customer-tab" role="tabpanel">									
												<!--<form class="form">
												<div class="form-body">											
													<h4 class="form-section"><i class="fa fa-id-card-o"></i> Customer Information</h4>
													<div class="form-group row">
														<label class="col-md-2">Customer Info :</label>
														<div class="col-md-10">
															<div class="row">
																<div class="col-md-3">
																	<div class="form-group">
																		<label >Customer Code</label>
																		<input type="text" class="form-control" placeholder="Project Number">
																	</div>
																</div>
																<div class="col-md-9">
																	<div class="form-group">
																		<label >Customer Name</label>
																		<input type="text" class="form-control" placeholder="Project Name">
																	</div>
																</div>
															</div>
															<div class="row">
																<div class="col-md-12">
																	<div class="form-group">
																		<label >Customer Address</label>
																		<input type="text" class="form-control" placeholder="Project Number">
																	</div>
																</div>
															</div>
															<div class="row">
																<div class="col-md-4">
																	<div class="form-group">
																		<label >Line ID</label>
																		<input type="text" class="form-control" placeholder="Project Number">
																	</div>
																</div>
																 <div class="col-md-4">
																	<div class="form-group">
																		<label >Tel.</label>
																		<input type="text" class="form-control" placeholder="Project Number">
																	</div>
																</div>
																<div class="col-md-4">
																	<div class="form-group">
																		<label >Email</label>
																		<input type="text" class="form-control" placeholder="Project Number">
																	</div>
																</div>
															</div>
														</div>
													</div>	
													<h4 class="form-section"><i class="fa fa-phone"></i> Contact Information</h4>
													<div class="form-group row">
														<label class="col-md-2">Customer Info :</label>
														<div class="col-md-10">
															<div class="row">
																<div class="col-md-12">
																	<div class="form-group">
																		<label >Customer Name</label>
																		<input type="text" class="form-control" placeholder="Project Name">
																	</div>
																</div>
															</div>
															<div class="row">
																<div class="col-md-12">
																	<div class="form-group">
																		<label >Customer Address</label>
																		<input type="text" class="form-control" placeholder="Project Number">
																	</div>
																</div>
															</div>
															<div class="row">
																<div class="col-md-4">
																	<div class="form-group">
																		<label >Line ID</label>
																		<input type="text" class="form-control" placeholder="Project Number">
																	</div>
																</div>
																 <div class="col-md-4">
																	<div class="form-group">
																		<label >Tel.</label>
																		<input type="text" class="form-control" placeholder="Project Number">
																	</div>
																</div>
																<div class="col-md-4">
																	<div class="form-group">
																		<label >Email</label>
																		<input type="text" class="form-control" placeholder="Project Number">
																	</div>
																</div>
															</div>
														</div>
													</div>										
													<div class="form-group row">
														<div class="col-12 d-flex flex-sm-row flex-column justify-content-end mt-1">
															<button type="submit" class="btn btn-primary glow mb-1 mb-sm-0 mr-0 mr-sm-1">Save
																changes</button>
															<button type="reset" class="btn btn-light">Cancel</button>
														</div>
													</div>
												</div>
												</form>-->
												<form>
												<div class="row">
													<div class="col-lg-6 col-md-12">
														<!--<form action="#">-->
															<div class="form-body">
															<h4 class="form-section"><i class="fa fa-user-o"></i> Customer Information</h4>
																<div class="form-group">
																	<div class="row">
																		<label class="col-lg-4">Customer Code</label>
																		<div class="col-lg-8">
																			<div class="row">
																				<div class="col-md-12">
																					<input type="text" id="custpj_code" name ="custpj_code" class="form-control" placeholder="Customer Code">
																				</div>																				
																			</div>
																		</div>
																	</div>
																</div>
																<div class="form-group">
																	<div class="row">
																		<label class="col-lg-4">Customer Name</label>
																		<div class="col-lg-8">
																			<div class="row">
																				<div class="col-md-12">
																					<input type="text" id="custpj_name" name ="custpj_name" class="form-control" placeholder="Customer Name">
																				</div>																				
																			</div>
																		</div>
																	</div>
																</div>
																<div class="form-group">
																	<div class="row">
																		<label class="col-lg-4">Customer Address</label>
																		<div class="col-lg-8">
																			<div class="row">
																				<div class="col-md-12">
																					<input type="text" id="custpj_addr" name ="custpj_addr"  class="form-control" placeholder="Customer Address">
																				</div>																				
																			</div>
																		</div>
																	</div>
																</div>
																<div class="form-group">
																	<div class="row">
																		<label class="col-lg-4">Line ID</label>
																			<div class="col-lg-8">
																			<div class="row">
																				<div class="col-md-12">
																					<input type="text" id="custpj_lineid" name ="custpj_lineid"  class="form-control" placeholder="Line ID">
																				</div>																				
																			</div>
																		</div>
																	</div>
																</div>
																<div class="form-group">
																	<div class="row">
																		<label class="col-lg-4">Telephone</label>
																		<div class="col-lg-8">
																			<div class="row">
																				<div class="col-md-12">
																					<input type="text" id="custpj_tel" name ="custpj_tel"  class="form-control" placeholder="Telephone">
																				</div>																				
																			</div>
																		</div>
																	</div>
																</div>
																<div class="form-group">
																	<div class="row">
																		<label class="col-lg-4">Email</label>
																		<div class="col-lg-8">
																			<div class="row">
																				<div class="col-md-12">
																					<input type="text" id="custpj_email" name ="custpj_email"  class="form-control" placeholder="Email">
																				</div>																				
																			</div>
																		</div>
																	</div>
																</div>																
															</div>
															<!--<div class="form-actions">
																<div class="text-right">
																	<button type="submit" class="btn btn-primary">Submit <i class="feather icon-thumbs-up position-right"></i></button>
																	<button type="reset" class="btn btn-warning">Reset <i class="feather icon-refresh-cw position-right"></i></button>
																</div>
															</div>-->
														<!--</form>-->
													</div>
													<div class="col-lg-6 col-md-12">
														<!--<form action="#">-->
															<div class="form-body">
															<h4 class="form-section"><i class="fa fa-address-book-o"></i> Customer Information</h4>																
																<div class="form-group">
																	<div class="row">
																		<label class="col-lg-4">Customer Name</label>
																		<div class="col-lg-8">
																			<div class="row">
																				<div class="col-md-12">
																					<input type="text" id="custpj_contact_name" name="custpj_contact_name" class="form-control" placeholder="Customer Name">
																				</div>																				
																			</div>
																		</div>
																	</div>
																</div>
																<div class="form-group">
																	<div class="row">
																		<label class="col-lg-4">Customer Address</label>
																		<div class="col-lg-8">
																			<div class="row">
																				<div class="col-md-12">
																					<input type="text" id="custpj_contact_addr" name="custpj_contact_addr"  class="form-control" placeholder="Customer Address">
																				</div>																				
																			</div>
																		</div>
																	</div>
																</div>
																<div class="form-group">
																	<div class="row">
																		<label class="col-lg-4">Line ID</label>
																			<div class="col-lg-8">
																			<div class="row">
																				<div class="col-md-12">
																					<input type="text" id="custpj_contact_lineid" name="custpj_contact_lineid"  class="form-control" placeholder="Line ID">
																				</div>																				
																			</div>
																		</div>
																	</div>
																</div>
																<div class="form-group">
																	<div class="row">
																		<label class="col-lg-4">Telephone</label>
																		<div class="col-lg-8">
																			<div class="row">
																				<div class="col-md-12">
																					<input type="text" id="custpj_contact_tel" name="custpj_contact_tel"  class="form-control" placeholder="Telephone">
																				</div>																				
																			</div>
																		</div>
																	</div>
																</div>
																<div class="form-group">
																	<div class="row">
																		<label class="col-lg-4">Email</label>
																		<div class="col-lg-8">
																			<div class="row">
																				<div class="col-md-12">
																					<input type="text" id="custpj_contact_email" name="custpj_contact_email"  class="form-control" placeholder="Email">
																				</div>																				
																			</div>
																		</div>
																	</div>
																</div>
															</div>
															<div class="form-actions">
																<div class="text-right">
																	<button type="submit" class="btn btn-primary">Submit <i class="feather icon-thumbs-up position-right"></i></button>
																	<button type="reset" class="btn btn-warning">Reset <i class="feather icon-refresh-cw position-right"></i></button>
																</div>
															</div>
														<!--</form>-->
													</div>
												</div>
												</form>
											</div>
											<div class="tab-pane" id="quotation" aria-labelledby="quotation-tab" role="tabpanel">
												<form class="form">
												<div class="form-body">											
													<div class="form-group row">
														<div class="col-md-12">
														<!-- Datatable -->
															<div class="table-responsive">
															<table id="quotationlist" class="table table-sm table-hover compact nowrap"  style="width:100%;" > <!--dt-responsive nowrap-->
																<thead >
																	<tr class="bg-primary white text-center" style="width:100%;" >	
																		<th>Quotation No.</th>
																		<th>Quotation Name</th>
																		<th>Project No.</th>
																		<!--<th>pjm_name</th>
																		<th>Cust No.</th>-->
																		<th>Customer Name</th>
																		<!--<th>payterm_code</th>-->
																		<th>Payterm Term</th>
																		<th>Start</th>
																		<th>End</th>
																		<th>Amount</th>
																		<th>Discount</th>
																		<!--<th>qtst_code</th>-->
																		<th>Status</th>
																		
																	</tr>
																</thead>
																<tbody>															
																</tbody>														
															</table> 
														</div>
														<!-- End Datatable -->													
														</div>                                               
													</div>		
													<!--<div class="form-group row">
														<div class="col-12 d-flex flex-sm-row flex-column justify-content-end mt-1">
															<button type="submit" class="btn btn-primary glow mb-1 mb-sm-0 mr-0 mr-sm-1">Save
																changes</button>
															<button type="reset" class="btn btn-light">Cancel</button>
														</div>
													</div>-->
												</div>
												</form>
											</div>
										</div>
										<!-- End Project Tab -->
									</div>
								</div>
							</div>
						</div>
					</div>
				</section>
                <!-- users edit ends -->
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
	
	<!-- BEGIN: Vendor JS-->
    <script src="../theme/app-assets/vendors/js/vendors.min.js"></script>
    <!-- BEGIN Vendor JS-->

    <!-- BEGIN: Page Vendor JS-->
	<!--<script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.20/datatables.min.js"></script>-->
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
    <!--<script src="../theme/app-assets/js/scripts/tables/datatables/datatable-advanced.js"></script>-->
    <!-- END: Page JS-->
	
	<!-- BEGIN Nilubonp Custom CSS-->
	
	<!-- BEGIN Nilubonp Custom JS-->
	<script type="text/javascript" language="javascript" class="init">	
		$(document).ready(function() {
			//$('#quotationlist2').DataTable({});
			var table = $('#quotationlist').DataTable( {	
				//"dom": '<"top row"<"col-12"if><"col-12"lp><"clear">>rt<"bottom row mt-1"<"col-12"if><"col-12"lp><"clear">>',
				"ajax":{
                     // url: "../serverside/load_project_quotationlist.php?pjm_nbr=<?php echo $pjm_nbr; ?>",// json datasource
					url: "../serverside/load_project_quotationlist.php?custpj_code=CT00000001",					  
					  type: "post",  // method  , by default get
                      error: function(){  // error handling
                          $("#quotationlist-error").html("");
                          $("#quotationlist").append('<tbody ><tr><th colspan="10">No data available in table</th></tr></tbody>');
                          $("#quotationlist processing").css("display","none"); 
						  $("#quotationlist").css("display","none"); 
                      }
                  },
				"deferRender": true,			
				"columnDefs" :[
					{ "className" : "dt-center", "targets": [1, 2, 3]},
					{ "className" : "dt-right", "targets": [9]}				
					//{ "width": "200", "targets" : [0,1,2,3,4,5,6,7,8,9] }
				],				
				"columns": [				
					{ "data" : 'qtm_nbr'},
					{ "data" : 'qtm_name'},
					{ "data" : 'pjm_nbr'},					
					{ "data" : 'qtm_customer_name'},				
					{ "data" : 'payterm_name'},
					{ "data" : 'qtm_date'},
					{ "data" : 'qtm_expire_date'},
					{ "data" : 'qtm_customer_price'},
					{ "data" : 'qtm_customer_disc'},					
					{ "data" : 'qtst_name'}					
					
				],	
			
				"lengthMenu": [ [10, 25, 50, -1], [10, 25, 50, "All"] ],					
				"pageLength": 10,				
				"pagingType": "simple_numbers",
				//"processing": true,
				"scrollX": true,
				"order": [[ 0, "desc" ]],		
				"ordering": true,
				"autowidth" : true
			} );
		} );	
	</script>
</body>
<!-- END: Body-->

</html>