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
		<script language="javascript">
			function focdsavedn() {
				var errorflag = false;
				var errortxt = "";
				//document.getElementById("msghead").innerHTML = "พบข้อผิดผลาดในการบันทึกข้อมูล";
				
				var focd_id_list = "";
				var focd_cnt = 0;
				$('input[name^=focd_id_]').each(function() {
					if (focd_id_list != "") { focd_id_list = focd_id_list + ","; }
					focd_id_list = focd_id_list + this.value;
					focd_cnt++;
				});
				var focd_dn_list = "";
				$('input[name^=focd_dn_]').each(function() {
					if (focd_dn_list != "") { focd_dn_list = focd_dn_list + ","; }
					focd_dn_list = focd_dn_list + this.value;
				});
				document.frm_focd_update_dn.focd_id_list.value = focd_id_list;
				document.frm_focd_update_dn.focd_dn_list.value = focd_dn_list;
				document.frm_focd_update_dn.submit();
			}
		</script>
	</head>
	<body onblur="javascript:window.close();"> 
		<table class="table table-bordered table-hover">	
			<tr <?php echo $focm_status_style;?>><td colspan=5 style='text-align:center'><b>FOC: <?php echo $focm_nbr?> :: Status = <?php echo $focm_status_name?></b></td></tr>
			<tr bgcolor=gold style='font-size:8pt'>
				<th>เลขที่ใบเบิก</th>
				<th>รหัสสินค้า</th>
				<th>จำนวน</th>
				<th>หน่วย</th>
				<th style="text-align:center">DN NO - <input type="button" class="btn btn-sm" value="Save DN" style="width:80px;" onclick="focdsavedn()"></th>
			</tr>
			<?php
			$sql = "SELECT * FROM focd_det" .
			" INNER JOIN material ON mat_code = focd_mat_code" .
			" INNER JOIN unit_mstr ON unit_code = focd_unit_code" .
			" WHERE focd_focm_nbr = '$focm_nbr' " .
			" Order by focd_sptm_nbr,focd_mat_code";
			$result = sqlsrv_query( $conn, $sql);
		
			while($r = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
				$focd_id = $r['focd_id'];
				$focd_mat_code = $r['focd_mat_code'];
				$focd_focm_dn_nbr = $r['focd_focm_dn_nbr'];
				$focd_qty = $r['focd_qty'];
				$focd_unit_name = html_quot($r['unit_name']);
				$focd_sptm_nbr = $r['focd_sptm_nbr'];
				
				$focd_mat_name = html_quot($r['mat_th_name']);
				if ($r['mat_th_name'] != $r['mat_en_name']) {
					$focd_mat_name .= "<br>" . html_quot($r['mat_en_name']);
				}
				?>
				
				<tr>
					<td style="width:40px"><?php echo $focd_sptm_nbr;?></td>
					<td style="width:230px"><?php echo $focd_mat_code;?><br><?php echo $focd_mat_name?></td>
					 
					<td><?php echo $focd_qty?></td>		
					<td><?php echo $focd_unit_name?></td>
					<td>
						<input type="hidden" name="focd_id_<?php echo $focd_id?>" value="<?php echo $focd_id?>">
						<input type="text" name="focd_dn_<?php echo $focd_id?>"	class="inputtext_s" style="width:100px" value="<?php echo $focd_focm_dn_nbr?>">
					</td>
				</tr>
				<?php
			}
			?>
		</table>
		<form name="frm_focd_update_dn" method="post" action="../serverside/focpost.php">
			<input type="hidden" name="action" value="update_dn">
			<input type="hidden" name="focm_nbr" value="<?php echo $focm_nbr?>">
			<input type="hidden" name="focd_id_list">
			<input type="hidden" name="focd_dn_list">
		</form>
	</body>
</html>