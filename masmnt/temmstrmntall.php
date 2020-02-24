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
                                <li class="breadcrumb-item active"><a href="temmstrmntall.php">All Master </a>
                                </li>
                            </ol>
                        </div>
                    </div>
                    <h3 class="content-header-title mb-0">C'TIS Master</h3>
                </div>
                <div class="content-header-right col-md-6 col-12">
                    <div class="btn-group float-md-right" role="group" aria-label="Button group with nested dropdown">
						<a class="btn btn-primary white" href="temmstrmntadd.php"><!--i class="feather icon-file-plus icon-left"--> </i>Add New Team</a>
						<? include("menu_mstr.php");	?>
					</div>
					
                </div>
            </div>
			
            <div class="content-body">
                <!-- File export table -->
                <section id="file-export">
					<div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header ">
									
									<h4 class="card-title form-section text-primary "><i class="fa fa-users"></i> Master Team Information</h4>
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
										
										 <table id="teamdatatable" class="table table-striped">
											<thead>
												<tr class="bg-primary font-weight-bold white text-center ">
													<th >No.</th>
													<th >หมายเลขทีม</th>
													<th >ชื่อทีม</th>
													<th >ชื่อหัวหน้าทีม</th>
													<th >จำนวนสมาชิก</th>
													<th >เบอร์โทร</th>
													<th >line</th>
													<th >Action</th>
													
												</tr>
											</thead>   
												<tbody>
												<?php
												$n = 0;																																																																									
												
												$sql_team = "SELECT tem_mstr.tem_code, tem_mstr.tem_name, tem_mstr.tem_leader_name, ".
												"temb_det.temb_tem_code, COUNT(temb_det.temb_tem_code) AS 'numteam', ".
												"tem_mstr.tem_tel,tem_mstr.tem_lineid ".
												"FROM tem_mstr LEFT JOIN ".
												"temb_det ON tem_mstr.tem_code = temb_det.temb_tem_code ".
												"GROUP BY tem_mstr.tem_code, tem_mstr.tem_name, tem_mstr.tem_leader_name, ".
												"temb_det.temb_tem_code, tem_mstr.tem_tel, tem_mstr.tem_lineid";
												
												$result_team = sqlsrv_query( $conn, $sql_team,$params);																					
												while($r_team = sqlsrv_fetch_array($result_team, SQLSRV_FETCH_ASSOC)) {	
													$tem_code = html_escape($r_team['tem_code']);
													$tem_name = html_escape($r_team['tem_name']);
													$tem_leader_name = html_escape($r_team['tem_leader_name']);
													$numteam = html_escape($r_team['numteam']);
													$tem_lineid = html_escape($r_team['tem_lineid']);
													$tem_tel = html_escape($r_team['tem_tel']);
													
													$n++;																										
													?>	
													<tr >
													 
														<td class="text-center"><?php echo $n+($currentpage-1)*$pagesize; ?></td>
														<td ><?php echo $tem_code; ?></td>
														<td ><?php echo $tem_name; ?></td>
														<td ><?php echo $tem_leader_name; ?></td>
														<td class="text-center"><?php echo $numteam; ?></td>
														<td ><?php echo $tem_tel; ?></td>
														<td ><?php echo $tem_lineid; ?></td>
														<td >
															<!-- Start btn-group -->
															<div >
																<button type="button" class="btn btn-info dropdown-toggle btn-sm " data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>
																<div class="dropdown-menu">
																	<a class="dropdown-item" href="javascript:void(0)" onclick="loadresult();window.location.href='temmstrmntdet.php?temnumber=<?php echo encrypt($tem_code, $key);?>&pg=<?php echo $currentpage?>'"><i class="fa fa-pencil-square-o"></i> Manage Team</a>
																	<a class="dropdown-item" id="btdel" data-id="<?php echo $tem_code; ?>" href="javascript:void(0)"><i class="fa fa-trash-o"></i> Delete</a>
																	
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
					</div>
				</section>
                <!-- File export table -->
				
            </div>
        </div>
	
    </div>
	 
	 
	 
    <!-- END: Content-->
		
		
		<form name="frmdelete" id="frmdelete" method="post" action="">
			<input type="hidden" name="action" value="teamdel">
			<input type="hidden" name="csrf_securecode" value="<?php echo $csrf_securecode?>">
			<input type="hidden" name="csrf_token" value="<?php echo md5($csrf_token)?>">
			<input type="hidden" name="tem_code">
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
	
	<script src="../theme/app-assets/js/scripts/pages/page-users.js"></script>
    <!--<script src="../theme/app-assets/js/scripts/tables/datatables/datatable-advanced.js"></script>-->
	
	
    <!-- END: Page JS-->
	<script>
		$(document).ready(function() {
			$('#teamdatatable').DataTable({
				
			});
			
			$(document).on('click', '#btdel', function(e){
				
				var tem_code = $(this).data('id');
				
				SwalDelete(tem_code);
				e.preventDefault();
			});
			
		});
		function SwalDelete(tem_code){
			Swal.fire({
				title: "Are you sure?",
				text: "คุณต้องการลบข้อมูลนี้ใช่หรือไหม่ !!!! ",
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
						document.frmdelete.tem_code.value = tem_code;  
						$.ajax({
							type: 'POST',
							url: '../serverside/temmstrpost.php',
							data: $('#frmdelete').serialize(),
							//data: 'delete='+tem_code,
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
									 //$(location).attr('href', 'temmstrmntall.php?pg='+json.pg)
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
	
		function readProducts(){
			$('#load-products').load('temmstrmntall.php');	//loads เฉพพราะdiv นี้
		}
		
	</script>

	<script language="javascript">		
		function loadresult() {
			document.all.result.innerHTML = "<center><img id='progress' src='../_images/loading0.gif' width=80 height=80><center>";
		}
				
		function showdata() {													
			document.frm.submit();												
		}
				
		function gotopage(mypage) {							
			document.frm.pg.value=mypage;
			document.frm.submit();
		}
		//function loadresult() {
		//	$('#div_result').html("<center><img id='progress' src='../_images/loading0.gif' width=80 height=80><center>");
		//}
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