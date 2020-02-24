<?php 
//include("../_incs/chksession.php");
include("../_incs/config.php");	  
include("../_incs/funcServer.php");
$msg = $_REQUEST["msg"];
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
	<link href="../_images/smartxpense.ico" rel="shortcut icon" />	
	<link href="../_libs/css/_webstyle.css" type="text/css" rel="stylesheet">
    <link href="../_libs/css/bootstrap.css" rel="stylesheet">
    <link href="../_libs/css/bootstrap-responsive.css" rel="stylesheet">
	<link href="../_libs/css/datepicker.css" rel="stylesheet">
    <link href="../_libs/css/admin.css" rel="stylesheet">
    <link href="../_libs/css/style.css" rel="stylesheet">			
	<script src="../_libs/js/jquery-2.1.4.min.js"></script>
	<script src="../_libs/js/bootstrap.min.js"></script>
	<script src="../_libs/js/bootstrap-datepicker.js"></script>	
	<script src="../_libs/js/CalendarPopup.js"></script>
	<script src="../_libs/js/funcClient.js"></script>	
	
</head>
<body >			
	<table width="100%" border=0 height="100%" align=center cellpadding=0 cellspacing=0>
		<tr><td><?php include("../menu.php"); ?></td></tr>			
		<tr>
			<td valign=top>								
				<table class="box_gy" width="100%" border=0	 bgcolor=DarkKhaki>
					<tr>
						<td style="color:red; text-align:center">
							<h3>
								
								<strong>
								<?php if ($msg == "") {?>
								ต้องขออภัย<br>คุณไม่มีสิทธิในการใช้งานเอกสารฉบับนี้ ขอบคุณค่ะ
								<?php } else {?>
								ต้องขออภัยค่ะ<br><?php echo $msg;?>
								<?php }?>
								</strong>
							</h3>
							<a href="dlvmall.php"  role="button" style="color:white; text-decoration:none;">
								<div class="btn btn-success fileinput-button paddingleftandright10 margintop20 marginleft20">
									<i class="icon-check icon-white"></i>
									<span>ย้อนกลับ</span>												
								</div>
							</a>
						</td>
					</tr>
				</table>		
			</td>
		</tr>		
	</table>														
</body>
</html>
