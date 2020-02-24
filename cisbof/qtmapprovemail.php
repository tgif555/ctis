<?php 
include("../_incs/config.php");
include("../_incs/funcServer.php");
clearstatcache();
$qtm_auth_code = $_REQUEST['auth'];
$qtm_nbr = $_REQUEST['qtmnumber'];
$qtm_approve_select = $_REQUEST['act'];
$qtm_approved_by = $_REQUEST['id'];

$qtm_approved_by_csrf = decrypt(mssql_escape($_POST['qtm_approved_by']), $dbkey);
$csrf_key = "Scgc2018";
$csrf_securecode = encrypt(rand(),$csrf_key);
$csrf_token = $csrf_securecode.$qtm_approved_by_csrf;
//
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
	<link href="../_libs/css/sptm.css" rel="stylesheet">
	<script src="../_libs/js/jquery-2.1.4.min.js"></script>
	<script src="../_libs/js/bootstrap.min.js"></script>
	
	<script type="text/javascript">
		$(document).ready(function () {  
			var result_text = "";
			$('#div_result').html("<img src='../_images/process.gif' width=150>");
			$.ajax({
				type: 'POST',
				url: '../serverside/sptmapprovepost.php',
				data: $('#frmapprove').serialize(),
				timeout: 50000,
				error: function(xhr, error){
					$('#div_result').html("<span style='color:red'>["+xhr+"] "+ error+"</span>");
					setTimeout(function(){ window.close() (); }, 3000);
				},
				success: function(result) {	
					var json = $.parseJSON(result);
					if (json.res == '0') {
						result_text += "<span style='color:red'><h4>[ ** ดำเนินการไม่สำเร็จ ** ] </h4></span>";
						result_text += json.err;
						$('#div_result').html(result_text);
					}
					else {
						result_text += "<span style='color:green'><h4>[ ** ดำเนินการสำเร็จ ** ] </h4></span>";
						if (json.err != "") {
							result_text += "<br><span style='color:red'>" + json.err + "</span>";
						}
						$('#div_result').html(result_text);
						setTimeout(function(){ window.close() (); }, 3000);
					}
				}
			});
		});		
	</script>
	
</head>
<body>	
	<div id="div_result"></div>
	<form id="frmapprove" name="frmapprove" method="post">
		<input type="hidden" name="csrf_securecode" value="<?php echo $csrf_securecode?>">
		<input type="hidden" name="csrf_token" value="<?php echo md5($csrf_token)?>">
		<input type="hidden" name="qtm_auth_code" value="<?php echo $qtm_auth_code?>">
		<input type="hidden" name="qtm_nbr" value="<?php echo $qtm_nbr?>">
		<input type="hidden" name="qtm_approve_select" value="<?php echo $qtm_approve_select?>">
		<input type="hidden" name="qtm_approved_by" value="<?php echo $qtm_approved_by?>">
	</form>																										
</body>
</html>
