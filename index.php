<?php
include("_incs/acunx_metaheader.php");
include("_incs/config.php"); 	
include("_incs/funcServer.php");
$msg=html_escape($_REQUEST['msg']);
$qtm_nbr = html_escape($_REQUEST['doc']);
$auth = html_escape($_REQUEST['auth']);
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
     <title><?php echo TITLE?></title>
    <link rel="apple-touch-icon" href="theme/app-assets/images/ico/apple-icon-120.png">
    <link rel="shortcut icon" type="image/x-icon" href="../../../app-assets/images/ico/favicon.ico">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:300,300i,400,400i,500,500i%7COpen+Sans:300,300i,400,400i,600,600i,700,700i" rel="stylesheet">

    <!-- BEGIN: Vendor CSS-->
    <link rel="stylesheet" type="text/css" href="theme/app-assets/vendors/css/vendors.min.css">
    <link rel="stylesheet" type="text/css" href="theme/app-assets/vendors/css/forms/icheck/icheck.css">
    <link rel="stylesheet" type="text/css" href="theme/app-assets/vendors/css/forms/icheck/custom.css">
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
    <link rel="stylesheet" type="text/css" href="theme/app-assets/css/pages/login-register.css">
    <!-- END: Page CSS-->

    <!-- BEGIN: Custom CSS-->
    <link rel="stylesheet" type="text/css" href="theme/assets/css/style.css">
    <!-- END: Custom CSS-->

</head>
<!-- END: Head-->

<!-- BEGIN: Body-->
<body onload="setfocus()" class="vertical-layout vertical-menu 1-column   blank-page blank-page" data-open="click" data-menu="vertical-menu" data-col="1-column">
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
                            <div class="card border-grey border-lighten-3 m-0">
                                <div class="card-header border-0">
                                    <div class="card-title text-center">
                                        <div class="p-1"><img src="theme/app-assets/images/logo/stack-logo-dark.png" alt="branding logo"></div>
                                    </div>
                                    <!--h6 class="card-subtitle line-on-side text-muted text-center font-small-3 pt-2">
										<span>Login with CTIS2</span>
									</h6-->
									<?php if ($msg=="") {?>
									<?php } else {?>
										<h6 class="card-subtitle line-on-side text-muted text-center font-small-3 pt-2">
											<span class="red">*** <?php echo $msg;?> ***</span>
										</h6>
									<?php }?>
                                </div>
                                <div class="card-content">
                                    <div class="card-body">
                                        <form class="form-horizontal form-simple" autocomplete="off" action="_incs/check_login.php" method="post" novalidate>
                                            <input type="hidden" name="qtm_nbr" value="<?php echo $qtm_nbr?>">
											<input type="hidden" name="auth" value="<?php echo $auth?>">
											<fieldset class="form-group position-relative has-icon-left mb-0">
                                                <input type="text" class="form-control form-control-lg" name="user_login" id="user_login" placeholder="Your Username" required>
                                                <div class="form-control-position">
                                                    <i class="feather icon-user"></i>
                                                </div>
                                            </fieldset>
                                            <fieldset class="form-group position-relative has-icon-left">
                                                <input type="password" class="form-control form-control-lg" name="user_passwd" id="user_passwd" placeholder="Enter Password" required>
                                                <div class="form-control-position">
                                                    <i class="fa fa-key"></i>
                                                </div>
                                            </fieldset>
                                            <div class="form-group row">
                                                <div class="col-sm-6 col-12 text-center text-sm-left">
                                                    <!--fieldset>
                                                        <input type="checkbox" id="remember-me" class="chk-remember">
                                                        <label for="remember-me"> Remember Me</label>
                                                    </fieldset-->
													
                                                </div>
                                                <div class="col-sm-6 col-12 text-center text-sm-right"><a href="pwdreset.php" class="card-link">Forgot Password?</a></div>
                                            </div>
                                            <button type="submit" class="btn btn-primary btn-lg btn-block"><i class="feather icon-unlock"></i> Login</button>
                                        </form>
                                    </div>
                                </div>
                                <!--div class="card-footer">
                                    <div class="">
                                        <p class="float-sm-left text-center m-0"><a href="recover-password.html" class="card-link">Recover password</a></p>
                                        <p class="float-sm-right text-center m-0">New to Stack? <a href="register-simple.html" class="card-link">Sign Up</a></p>
                                    </div>
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
    <script src="theme/app-assets/vendors/js/vendors.min.js"></script>
    <!-- BEGIN Vendor JS-->

    <!-- BEGIN: Page Vendor JS-->
    <script src="theme/app-assets/vendors/js/forms/icheck/icheck.min.js"></script>
    <script src="theme/app-assets/vendors/js/forms/validation/jqBootstrapValidation.js"></script>
    <!-- END: Page Vendor JS-->

    <!-- BEGIN: Theme JS-->
    <script src="theme/app-assets/js/core/app-menu.js"></script>
    <script src="theme/app-assets/js/core/app.js"></script>
    <!-- END: Theme JS-->

    <!-- BEGIN: Page JS-->
    <script src="theme/app-assets/js/scripts/forms/form-login-register.js"></script>
    <!-- END: Page JS-->
	<script language="javascript">
		function setfocus() {
			document.getElementById("user_login").focus();
		}
	</script>
</body>
<!-- END: Body-->

</html>