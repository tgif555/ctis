<?php
include("_incs/config.php");
include("_incs/funcServer.php");
$msg = $_REQUEST['msg'];
$sptm_nbr = $_REQUEST['doc'];
$auth = $_REQUEST['auth'];
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
    <title>Recover Password - Stack Responsive Bootstrap 4 Admin Template</title>
    <link rel="apple-touch-icon" href="theme/app-assets/images/ico/apple-icon-120.png">
    <link rel="shortcut icon" type="image/x-icon" href="theme/app-assets/images/ico/favicon.ico">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:300,300i,400,400i,500,500i%7COpen+Sans:300,300i,400,400i,600,600i,700,700i" rel="stylesheet">

    <!-- BEGIN: Vendor CSS-->
    <link rel="stylesheet" type="text/css" href="theme/app-assets/vendors/css/vendors.min.css">
    <!-- END: Vendor CSS-->

    <!-- BEGIN: Theme CSS-->
    <link rel="stylesheet" type="text/css" href="theme/app-assets/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="theme/app-assets/css/bootstrap-extended.css">
    <link rel="stylesheet" type="text/css" href="theme/app-assets/css/colors.css">
    <link rel="stylesheet" type="text/css" href="theme/app-assets/css/components.css">
    <!-- END: Theme CSS-->

    <!-- BEGIN: Page CSS-->
    <link rel="stylesheet" type="text/css" href="theme/app-assets/css/core/menu/menu-types/vertical-menu.css">
    <link rel="stylesheet" type="text/css" href="theme/app-assets/css/core/colors/palette-gradient.css">
    <!-- END: Page CSS-->

    <!-- BEGIN: Custom CSS-->
    <link rel="stylesheet" type="text/css" href="theme/assets/css/style.css">
    <!-- END: Custom CSS-->

</head>
<!-- END: Head-->

<!-- BEGIN: Body-->

<body class="vertical-layout vertical-menu 1-column   blank-page blank-page" data-open="click" data-menu="vertical-menu" data-col="1-column">
    <!-- BEGIN: Content-->
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="content-wrapper">
            <div class="content-header row">
            </div>
            <div class="content-body">
                <section class="row flexbox-container">
                    <div class="col-12 d-flex align-items-center justify-content-center">
                        <div class="col-lg-4 col-md-8 col-10 box-shadow-2 p-0">
                            <div class="card border-grey border-lighten-3 px-2 py-2 m-0">
                                <div class="card-header border-0 pb-0">
                                    <div class="card-title text-center">
                                        <img src="theme/app-assets/images/logo/stack-logo-dark.png" alt="branding logo">
                                    </div>
                                    <h6 class="card-subtitle line-on-side text-muted text-center font-small-3 pt-2">
										<span>
											Reset Your Password<br>
											<?php if ($msg != "") { ?>
												<font color=red>***** <?php echo $msg; ?> *****</font><br>
											<?php }?>
										</span>
									</h6>
                                </div>
                                <div class="card-content">
                                    <div class="card-body">
                                        <form name="frm_resetpwd" autocomplete=OFF method="post" action="serverside/resetpwdpost.php" class="form-horizontal" novalidate>
											<input type="hidden" name="action" value="resetpwd">
                                            
											<fieldset class="form-group position-relative has-icon-left mb-0">
                                                <input type="text" name="user_login" id="user_login" class="form-control form-control-lg" placeholder="User Login" required>
												<div class="form-control-position">
                                                    <i class="feather icon-user"></i>
                                                </div>
											</fieldset>
											<fieldset class="form-group position-relative has-icon-left mb-0">
												<input type="text" name="birth_date" id="birth_date" maxlength="10" class="form-control form-control-lg" placeholder="Birth Date (dd/mm/yyyy)" required>
												<div class="form-control-position">
                                                    <i class="feather icon-calendar"></i>
                                                </div>
											</fieldset>	
											<fieldset class="form-group position-relative has-icon-left mb-0">	
												<input type="password" name="card_id" id="card_id" maxlength="13" class="form-control form-control-lg" placeholder="ID Card Last 13 digit" required>
												<div class="form-control-position">
                                                    <i class="feather icon-heart"></i>
                                                </div>
											</fieldset>
                                            <button type="button" onclick="resetpwdpostform()" class="btn btn-outline-primary btn-lg btn-block"><i class="feather icon-unlock"></i> Reset Password</button>
											<button type="button" onclick="window.location.href='index.php'" class="btn btn-outline-in fo btn-lg btn-block"><i class="feather icon-chevron-left"></i> Back</button>
											
                                        </form>
                                    </div>
                                </div>
                                <!--div class="card-footer border-0">
                                    <p class="float-sm-left text-center"><a href="login-simple.html" class="card-link">Login</a></p>
                                    <p class="float-sm-right text-center">New to Stack ? <a href="register-simple.html" class="card-link">Create Account</a></p>
                                </div-->
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
    <!-- END: Content-->


    <!-- BEGIN: Vendor JS-->
    <script src="../../../app-assets/vendors/js/vendors.min.js"></script>
    <!-- BEGIN Vendor JS-->

    <!-- BEGIN: Page Vendor JS-->
    <script src="../../../app-assets/vendors/js/forms/validation/jqBootstrapValidation.js"></script>
    <!-- END: Page Vendor JS-->

    <!-- BEGIN: Theme JS-->
    <script src="../../../app-assets/js/core/app-menu.js"></script>
    <script src="../../../app-assets/js/core/app.js"></script>
    <!-- END: Theme JS-->

    <!-- BEGIN: Page JS-->
    <script src="../../../app-assets/js/scripts/forms/form-login-register.js"></script>
    <!-- END: Page JS-->
	
	<script language="javascript">
		function resetpwdpostform() {								
			var errorflag = false;
			var errortxt = "";
			
			var user_login = document.frm_resetpwd.user_login.value;				
			var birth_date = document.frm_resetpwd.birth_date.value;
			var card_id = document.frm_resetpwd.card_id.value;				
			
			if (user_login=="") {
				if (errortxt!="") {errortxt = errortxt + "\n";}
				errorflag = true;
				errortxt = errortxt + "กรุณา User Name";				
			}
			if (birth_date=="") {
				if (errortxt!="") {errortxt = errortxt + "\n";}
				errorflag = true;
				errortxt = errortxt + "กรุณาระบุ วันเดือนปีเกิด ในรูปแบบ วว/ดด/ปปปป ค.ศ.";
			}
			if (card_id=="") {
				if (errortxt!="") {errortxt = errortxt + "\n";}
				errorflag = true;
				errortxt = errortxt + "กรุณาระบุ 4 digit สุดท้ายของหมายเลขบัตรประชาชน";				
			}
			
			if (errorflag ) {							
				alert(errortxt);
			}
			else {	
				if(confirm('ท่านยืนยันการ Reset Password ไช่หรือไม่ ?')) {						
					document.frm_resetpwd.submit();
				}					
			}								
		}
	</script>

</body>
<!-- END: Body-->

</html>