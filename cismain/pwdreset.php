<?php
include("_incs/config.php");
include("_incs/funcServer.php");
$msg = $_REQUEST['msg'];
$sptm_nbr = $_REQUEST['doc'];
$auth = $_REQUEST['auth'];
?>
<!DOCTYPE html>
<html lang="en" >
<head>
	<meta charset="UTF-8">
	<link href="_images/smartxpense.ico" rel="shortcut icon" />
	<title>กระเบื้องตัวอย่าง</title>
	<link href="_libs/css/bootstrap.css" rel="stylesheet">
    <link href="_libs/css/bootstrap-responsive.css" rel="stylesheet">
	<link href="_libs/css/loginstyle.css" rel="stylesheet" >
	
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
				errortxt = errortxt + "กรุณาระบุ 4 digit สุดท้ายของหมายเลขบัตรระชาชน";				
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
	<style>
		body {
            background: url(_images/wood_pattern2.jpg);
			margin-top: 0px;
			background-repeat: no-repeat;
			background-size: 100%;
        }
		#btnlogin {
			cursor: hand;
		}
		.footer {
		   position: fixed;
		   left: 0;
		   bottom: 0;
		   width: 100%;
		   background-color: lightgray;
		   color: black;
		   font-size: 8pt;
		   text-align: center;
		}
	</style>
</head>
<body onload="setfocus()">
	<div class="wrapper fadeInDown">
		<div id="formContent">
			<br>
			<div class="fadeIn first" style="background:#fcd5b4">
				<br>
				<?php if ($msg != "") { ?>
					<font color=red>***** <?php echo $msg; ?> *****</font><br>
				<?php }?>
				<img src="_images/com-logo.jpg"/>
			</div>
			<div class="fadeIn first" style="background:#fcd5b4">
				<img src="_images/sys-name.jpg"/><br>
				<?php echo PROJECT_RELEASE ?><br><br>
			</div>
			<form name="frm_resetpwd" autocomplete=OFF method="post" action="serverside/resetpwdpost.php" class="form-2" style="margin-top:-1px">
				<input type="hidden" name="action" value="resetpwd">
				<center><p><span class="log-in"><font color=red>Reset Your Password For Sampletile</font></span></p><br></center>
				<table border=0>	
					<tbody>																	
					<tr>
						<td style="width:120px;vertical-align: middle;font-size:8pt"><b>User Name:<br>(<font color=red>AD Name</font>)</b></td>
						<td>
							<input type="text" name="user_login" style="font-size:10pt;width:170px;height:25px" maxlength="30">
						</td>
					</tr>
					<tr>
						<td style="vertical-align: middle;font-size:8pt"><b>Birth Date:<br>(<font color=red>31/12/19YY</font>)</b></td>
						<td>
							<input type="text" name="birth_date" style="font-size:10pt;width: 170px;height:25px" maxlength="10">
						</td>
					</tr>
					<tr>
						<td style="vertical-align: middle;font-size:8pt"><b>ID Card:<br>(<font color=red>Last 4 digit</font>)</b></td>
						<td>
							<input type="password" name="card_id" style="font-size:10pt;width: 170px;height:25px" maxlength="13">
						</td>
					</tr>
					<tr>
						<td style="vertical-align: middle;font-size:8pt"><b>Reset Code:</b></td>
						<td>
							<input type="text" name="your_reset_code" style="font-size:10pt;width: 170px;height:25px" maxlength="10">
						</td>
					</tr>
					<tr>
						<td colspan=2>
							<br>
							<input type="button" class="btn btn-danger" style="font-size:8pt;width:28px" value="Reset" onclick="resetpwdpostform()">
							<input type="button" class="btn btn-info" style="font-size:8pt;width:8px" value="Back" onclick="window.location.href='index.php'">
						</td>
					</tr>
					</tbody>
				</table>								
			</form>​​
		</div>
	</div>
	<div class="footer">@2019/09 Development By: IT Business Solution & Icons made by <a href="https://www.flaticon.com/authors/freepik" title="Freepik" target="_blank">Freepik</a> from <a href="https://www.flaticon.com/" target="_blank" title="Flaticon">www.flaticon.com</a> is licensed by <a href="http://creativecommons.org/licenses/by/3.0/" target="_blank" title="Creative Commons BY 3.0" target="_blank">CC 3.0 BY</a></div>
</body>
</html>