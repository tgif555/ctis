<div>
<table border="0" cellspacing="0" cellpadding="4" class="table table-bordered table-condensed">			
	<thead>
	<tr bgcolor="gold" style="font-weight: bold;">
		<td style="width:50px;text-align:center">ลำดับ</td>
		<td style="width:100px;">Package No</td>
		<td style="width:100px;text-align:center">หมายเลขใบเบิก</td>
		<td style="width:100px;text-align:center">รายการสินค้า</td>
		<td style="width:100px;text-align:center">จำนวนสินค้า</td>
		<td style="width:100px;text-align:center">น้ำหนัก (KG)</td>
		<td style="width:80px;text-align:center;">ผลการรับ</td>
		
		<td></td>
	</tr>
	</thead>									
	<?php
	$n = 0;
	$sql = "SELECT * FROM ivd_det" .
		" INNER JOIN ivm_mstr ON ivm_nbr = ivd_ivm_nbr " .
		" INNER JOIN dlvm_mstr ON dlvm_nbr = ivd_dlvm_nbr " .
		" INNER JOIN sptm_mstr ON sptm_nbr = dlvm_sptm_nbr " .
		" WHERE ivd_ivm_nbr = '$ivm_nbr'" .
		" ORDER BY ivd_dlvm_nbr";
														
	$result = sqlsrv_query( $conn, $sql );											
	while($r_ivd = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
		$ivd_id = $r_ivd['ivd_id'];
		$ivd_ivm_nbr = $r_ivd['ivd_ivm_nbr'];
		$ivd_wpm_nbr = $r_ivd['ivd_wpm_nbr'];
		$ivd_dlvm_nbr = $r_ivd['ivd_dlvm_nbr'];
		$ivd_sptm_nbr = $r_ivd['ivd_sptm_nbr'];
		$ivd_dlvm_packing_weight = $r_ivd['dlvm_packing_weight'];
		$ivd_receive_status = $r_ivd['ivd_receive_status'];
		$ivd_receive_status_cmmt = html_quot($r_ivd['ivd_receive_status_cmmt']);
		$ivd_create_by = $r_ivd['ivd_create_by'];
		
		$ivd_receive_status_text = "";
		if ($ivd_receive_status == 'Y') {
			$ivd_receive_status_text = "<center><div style='background:green;color:white;width:70px;border-radius:4px'>**รับ**</div></center>";
		}
		if ($ivd_receive_status == 'N') {
			$ivd_receive_status_text = "<center><div style='background:red;color:white;width:70px;border-radius:4px'>**ไม่รับ**</div></center>";
		}
		$ivd_dlvm_packing_weight_diff = $ivd_dlvm_packing_weight - (int) $ivd_dlvm_packing_weight;
		if ($ivd_dlvm_packing_weight_diff>0) {$ivd_dlvm_packing_weight = number_format($ivd_dlvm_packing_weight,2);}
		else {$ivd_dlvm_packing_weight = number_format($ivd_dlvm_packing_weight,0);}
		
		$n++;																			
		?>
		<tr>
			<td style="background-color: lightcyan;text-align:center;"><?=$n?></td>                                            
			<td style="background-color: lightcyan"><?php echo $ivd_dlvm_nbr; ?></td>
			<td style="background-color: lightcyan"><?php echo $ivd_sptm_nbr; ?></td>
			<td style="background-color: lightcyan;text-align:center"><?php echo sumdlvddet($ivd_dlvm_nbr,$conn); ?></td>
			<td style="background-color: lightcyan;text-align:center"><?php echo sumdlvddetqty($ivd_dlvm_nbr,$conn); ?></td>
			<td style="background-color: lightcyan;text-align:center"><?php echo $ivd_dlvm_packing_weight; ?></td>
			<td style="background-color: lightcyan;text-align:center"><?php echo $ivd_receive_status_text; ?></td>
		</tr>
	<?php } ?>															
</table>
</div>