<?php
include("../_incs/chksession.php");
include "../_incs/config.php";
include("../_incs/funcServer.php");
$focm_nbr = decrypt($_REQUEST["focmnumber"], $key);	

$sql = "select * from focm_mstr where focm_nbr = '$focm_nbr'";
$result = sqlsrv_query($conn,$sql);	
$row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);	
if ($row) {	
	$focm_status_code = $row['focm_status_code'];
	$focm_status_name = findsqlval("focs_mstr","focs_status_name","focs_status_code",$focm_status_code,$conn);
	$focm_dn_nbr = html_quot($row['focm_dn_nbr']);
	if ($focm_status_code == '10') {$focm_status_style = "style='background:red;color:white'";}
	elseif ($focm_status_code == '20' || $focm_status_code == '30') {$focm_status_style = "style='background:yellow;color:black'";}
	else {$focm_status_style = "style='background:green;color:white'";}
}	
?>
<!doctype html>
<html>
    <head>
		<meta charset="utf-8"> 
		<title>FOC Material List</title>
		<meta http-equiv="X-UA-Compatible" content="IE=edge" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="">
		<meta name="author" content="">
		<link href="../_libs/css/bootstrap.css" rel="stylesheet">
		<link href="../_libs/css/bootstrap-responsive.css" rel="stylesheet">
		<link href="../_libs/css/datepicker.css" rel="stylesheet">
		<link href="../_libs/css/_webstyle.css" "type=text/css" rel="stylesheet">
		<link href="../_libs/css/sptm.css" rel="stylesheet">
		<script src="../_libs/js/jquery-2.1.4.min.js"></script>
		<script src="../_libs/js/bootstrap.min.js"></script>
		<script src="../_libs/js/bootstrap-datepicker.js"></script>	
		<script src="../_libs/js/CalendarPopup.js"></script>
		<script type="text/javascript" src="../_libs/js/sptm.js"></script>
	</head>
	<body onblur="javascript:window.close();"> 
		<table class="table table-bordered table-hover">	
			<tr <?php echo $focm_status_style;?>><td colspan=5 style='text-align:center'><b>FOC: <?php echo $focm_nbr?> :: Status = <?php echo $focm_status_name?></b></td></tr>
			<tr bgcolor=gold style='font-size:8pt'><th>รหัสสินค้า</th><th>ชื่อสินค้า</th><th>หมายเลขใบเบิก</th><th>จำนวน</th><th>หน่วย</th></tr>
			<?php
			$sql = "SELECT * FROM focd_det" .
			" INNER JOIN material ON mat_code = focd_mat_code" .
			" INNER JOIN unit_mstr ON unit_code = focd_unit_code" .
			" WHERE focd_focm_nbr = '$focm_nbr' " .
			" Order by focd_mat_code";
			$result = sqlsrv_query( $conn, $sql);
		
			while($r = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {	
				$focd_mat_code = $r['focd_mat_code'];
				$focd_mat_name = html_quot($r['mat_th_name']);
				$focd_sptm_nbr = $r['focd_sptm_nbr'];
				$focd_qty = $r['focd_qty'];
				$focd_unit_name = html_quot($r['unit_name']);
				?>
				<tr>
					<td width=20%><?php echo $focd_mat_code;?></td>
					<td><?php echo $focd_mat_name?></td>   
					<td><?php echo $focd_sptm_nbr?></td>
					<td><?php echo $focd_qty?></td>		
					<td><?php echo $focd_unit_name?></td>	
				</tr>
				<?php
			}
			?>
		</table>           
	</body>
</html>