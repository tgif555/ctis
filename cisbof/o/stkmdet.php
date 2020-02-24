<?php
include("../_incs/chksession.php");
include "../_incs/config.php";
include("../_incs/funcServer.php");
$mat_code = decrypt(mssql_escape($_REQUEST["mat_code"]), $key);	
$sql = "select * from stkm_mstr INNER JOIN material ON mat_code = stkm_mat_code where stkm_mat_code = '$mat_code'";
$result = sqlsrv_query($conn,$sql);	
$row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);	
if ($row) {	
	$stkh_mat_th_name = html_quot($row['mat_th_name']);
	$stkm_qty_resv = $row['stkm_qty_resv'];
	$stkm_qty_oh = $row['stkm_qty_oh'];
	$stkm_location = html_quot($row['stkm_location']);
	$stkm_qty_min = $row['stkm_qty_min'];
	$stkm_qty_max = $row['stkm_qty_max'];
}	
?>
<!doctype html>
<html>
    <head>
		<meta charset="utf-8"> 
		<title>Stock Transaction</title>
		<meta http-equiv="X-UA-Compatible" content="IE=edge" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="">
		<meta name="author" content="">
		<link href="../_libs/css/bootstrap.css" rel="stylesheet">
		<link href="../_libs/css/bootstrap-responsive.css" rel="stylesheet">
		<link href="../_libs/css/datepicker.css" rel="stylesheet">	
		<LINK href="../_libs/css/_webstyle.css" "type=text/css" rel="stylesheet">
		<link href="../_libs/css/sptm.css" rel="stylesheet">
		
		<script src="../_libs/js/jquery-2.1.4.min.js"></script>
		<script src="../_libs/js/bootstrap.min.js"></script>
		<script src="../_libs/js/bootstrap-datepicker.js"></script>	
		<script src="../_libs/js/CalendarPopup.js"></script>
		<script type="text/javascript" src="../_libs/js/sptm.js"></script>
	</head>
	<body onblur="javascript:window.close();"> 
		<br>
		<table class="table table-bordered table-hover">	
			<tr <?php echo $focm_status_style;?>><td colspan=5 style='background:lightblue;text-align:center'><b><?php echo $mat_code?><br><?php echo $stkh_mat_th_name?></b></td></tr>
			<tr style='background:gold;font-size:8pt;'>
				<th style='text-align:center' width=27%>วันที่ทำรายการ</th>
				<th style='text-align:center' width=17%>ประเภท</th>
				<th style='text-align:center' >หมายเหตุ</th>
				<th style='text-align:center' width=12%>รับ</th>
				<th style='text-align:center' width=12%>จ่าย</th>
			</tr>
			<?php
			$total_qty_rct = 0;
			$total_qty_iss = 0;
			$total_qty = 0;
			$sql = "SELECT * FROM stkh_hist" .
			" INNER JOIN material ON mat_code = stkh_mat_code" .
			" INNER JOIN unit_mstr ON unit_code = stkh_unit" .
			" WHERE stkh_mat_code = '$mat_code' and substring(stkh_trantypem_code,1,3) in ('RCT','ISS','RET')" .
			" Order by stkh_create_date";
			$result = sqlsrv_query( $conn, $sql);
		
			while($r = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {	
				$stkh_trantypem_code = $r['stkh_trantypem_code'];
				
				$stkh_qty = $r['stkh_qty'];
				$stkh_unit = $r['stkh_unit'];
				$stkh_unit_name = $r['unit_name'];
				$stkh_remark = html_quot($r['stkh_remark']);
				$stkh_create_date = dmyhmsdb($r['stkh_create_date'],'Y');
				$stkh_qty_rct = "";
				$stkh_qty_iss = "";
				if (inlist("RCT,RET",substr($stkh_trantypem_code,0,3))) {
					$stkh_qty_rct = $stkh_qty;
					$total_qty_rct = $total_qty_rct + $stkh_qty;
				}
				else {
					$stkh_qty_iss = $stkh_qty;
					$total_qty_iss = $total_qty_iss + $stkh_qty;
				}
				?>
				<tr>
					<td><?php echo $stkh_create_date?></td>
					<td><?php echo $stkh_trantypem_code;?></td>
					<td><?php echo $stkh_remark?></td>   
					<td style='text-align:center'><?php echo $stkh_qty_rct?></td>		
					<td style='text-align:center'><?php echo $stkh_qty_iss?></td>	
				</tr>
				<?php
			}
			?>
			<tr>
				<td colspan=3></td>
				<td style='background:gold;text-align:center;font-size:10pt'><?php echo $total_qty_rct?></td>		
				<td style='background:gold;text-align:center;font-size:10pt'><?php echo $total_qty_iss?></td>	
			</tr>
			<tr>
				<td colspan=3></td>
				<td colspan=2 style='background:gold;text-align:center;font-size:10pt;font-weight:bold'><?php echo $total_qty_rct - $total_qty_iss?></td>			
			</tr>
		</table>           
	</body>
</html>