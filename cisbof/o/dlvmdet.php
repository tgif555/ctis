<?php
    include "../_incs/config.php";
	include("../_incs/funcServer.php");
	$dlvm_nbr = $_REQUEST["dlvm_nbr"];
	$filepath = "../_fileuploads/rct";

	$dlvm_receive_s_filename= findsqlval("dlvm_mstr","dlvm_receive_s_filename","dlvm_nbr",$dlvm_nbr,$conn);
	
	if($dlvm_receive_s_filename!='') {
		$dlvm_receive_s_filename_ext = strtoupper(explode(".",$dlvm_receive_s_filename)[1]);
		if (inlist("JPG,PNG,BMP",$dlvm_receive_s_filename_ext)) { $showstyle = "rel='prettyPhoto'"; }
		else { $showstyle = "target='_blank'";}
	}
?>
<!doctype html>
<html>
    <head>
		<meta charset="utf-8"> 
		<title>ข้อมูลสินค้าใน Package</title>
		<meta http-equiv="X-UA-Compatible" content="IE=edge" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="">
		<meta name="author" content="">
		<link href="../_libs/css/bootstrap.css" rel="stylesheet">
		<link href="../_libs/css/bootstrap-responsive.css" rel="stylesheet">
		<link href="../_libs/css/datepicker.css" rel="stylesheet">	
		<link href="../_libs/css/_webstyle.css" "type=text/css" rel="stylesheet">
		<link href="../_libs/css/sptm.css" rel="stylesheet">
		<link href="../_libs/prettyPhoto_3.1.6/css/prettyPhoto.css" rel="stylesheet" type="text/css" media="screen" title="prettyPhoto main stylesheet" charset="utf-8" />
		
		<script src="../_libs/js/jquery-2.1.4.min.js"></script>
		<script src="../_libs/js/bootstrap.min.js"></script>
		<script src="../_libs/js/bootstrap-datepicker.js"></script>	
		<script src="../_libs/js/CalendarPopup.js"></script>
		<script type="text/javascript" src="../_libs/js/sptm.js"></script>
		<script src="../_libs/prettyPhoto_3.1.6/js/jquery.prettyPhoto.js" type="text/javascript" charset="utf-8"></script>	
		
		<script type="text/javascript">
			$(document).ready(function () {  
				$("a[rel^='prettyPhoto']").prettyPhoto();
			});		
		</script>
		<script language="javascript">
			function printform(url) {				
				window.open(url);						
			}	
		</script>
	</head>
	<body onblur="javascript:window.close();">  	
		<table class="table table-bordered table-hover">		
			<tr valign="top" style="background-color:#D4EFBA;font-size:8pt;font-weight:bold" height="25" align="center">
				<td colspan=3 align=center style='color:orange'><h4>Delivery NO: <?php echo $dlvm_nbr?></h4></td>
				<td align=right><a href="javascript:void(0)" onclick="printform('dlvmform01.php?dlvmnumber=<?php echo encrypt($dlvm_nbr, $key);?>')">Print</a></td>
			</tr>
			<tr valign="top" style="background-color:white;" align="center">
				<td colspan=4 align=center>
					<?php if ($dlvm_receive_s_filename!='') {?>
						<a href="<?php echo $filepath.'/'.$dlvm_receive_s_filename?>" <?php echo $showstyle?>>
						<img src="../_images/attachment.png" width=16>
						</a>
					<?php }?>
				</td>
			</tr>
			<tr valign="top" style="background-color:#D4EFBA;font-size:8pt;font-weight:bold" height="25" align="center">
				<th>รหัสสินค้า</th>
				<th>ชื่อสินค้า</th>
				<th style='text-align:center'>สถานะ</th>
				<th style='text-align:center'>จำนวน</th>
			</tr>			
			<?php
			//DETAIL
			
			$sql_dlvd = "SELECT dlvd_det.*,material.mat_th_name FROM dlvd_det INNER JOIN material ON mat_code = dlvd_mat_code WHERE dlvd_dlvm_nbr = '$dlvm_nbr'";
			$result_dlvd = sqlsrv_query( $conn, $sql_dlvd );											
			while($r_dlvd = sqlsrv_fetch_array($result_dlvd, SQLSRV_FETCH_ASSOC)) {
				$dlvd_receive_status = $r_dlvd['dlvd_receive_status'];
				$dlvd_qty = $r_dlvd['dlvd_qty'];
				if ($dlvd_receive_status == 'Y') { 
					$dlvd_receive_status_text = "<font color=green>รัย</font>";
				}
				if ($dlvd_receive_status == 'N') { 
					$dlvd_receive_status_text = "<font color=red>ไม่รับ</font>";
				}
				?>
				<tr>
					<td width=20%><?php echo $r_dlvd["dlvd_mat_code"];?></td>
					<td><?php echo html_quot($r_dlvd['mat_th_name'])?></td>
					<td style='text-align:center'><?php echo $dlvd_receive_status_text?></td>	
					<td style='text-align:center'>
						<?php if ($dlvd_receive_status == 'Y') { ?>
							<span class="bubbletext" style='background:green;color:white'><?php echo $dlvd_qty; ?></span>
						<?php } else {?>
							<span class="bubbletext" style='background:red;color:white'><?php echo $dlvd_qty; ?></span>
						<?php }?>
					<?php ?>
					</td>				
				</tr>
			<?php
			}
			?>
		</table>           
	</body>
</html>