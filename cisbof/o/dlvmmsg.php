<?php
$msg=$_REQUEST['msg'];
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8"> 
		<title></title>
		<meta http-equiv="X-UA-Compatible" content="IE=edge" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="">
		<meta name="author" content="">
		<link href="../_images/sampletiles.ico" rel="shortcut icon" />
		<link href="../_libs/css/bootstrap_4.3.1/css/bootstrap.css" rel="stylesheet">
		<script src="../_libs/js/jquery-2.1.4.min.js"></script>
		<script src="../_libs/js/bootstrap.min.js"></script>
		<script language=javascript>
			function closemyself() {
			 window.opener=self;
			 window.close();
			}
		</script>
	</head>
	<body onLoad="setTimeout('closemyself()',5000);">
		<center><p><?php echo $msg?></p><center>
	</body>
</html>