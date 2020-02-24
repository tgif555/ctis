<div>
<table width="100%" border="0" cellspacing="0" cellpadding="4" class="table table-bordered table-condensed">			
	<thead>
	<tr bgcolor="gold" style="font-weight: bold;">
		<td style="width:20px">ลำดับ</td>			
		<td style="width:100px;text-align:center;">ประเภท</td>
		<td style="width:100px;text-align:center;">รหัสสินค้า</td>
		<td style="width:250px;text-align:center;">ชื่อสินค้า</td>
		<td style="width:250px;text-align:center;">หมายเหตุ</td>
		<td style="width:80px;text-align:center;">จำนวน</td>
		<td style="width:80px;text-align:center;">หน่วย</td>	
		<td style="width:80px;text-align:center;"></td>
		<td style="width:80px;text-align:center;">ผลการรับ</td>
	</tr>
	</thead>									
	<?php
	$n = 0;
	$sql = "SELECT * FROM dlvd_det" .
		" INNER JOIN material ON mat_code = dlvd_mat_code" .
		" INNER JOIN unit_mstr ON unit_code = dlvd_unit_code" .
		" INNER JOIN sptpg_mstr ON sptpg_code = dlvd_mat_group" .
		" INNER JOIN sptd_det ON sptd_id = dlvd_sptd_id" .
		" WHERE dlvd_dlvm_nbr = '$dlvm_nbr'" .
		" ORDER BY sptpg_seq, sptd_mat_code";	
														
	$result = sqlsrv_query( $conn, $sql );											
	while($r_dlvd = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {	
		$dlvd_id = $r_dlvd['dlvd_id'];
		$dlvd_dlvm_nbr = $r_dlvd['dlvd_dlvm_nbr'];																							
		$dlvd_sptm_nbr = $r_dlvd['dlvd_sptm_nbr'];												
		$dlvd_sptd_id = $r_dlvd['dlvd_sptd_id'];
		$dlvd_receive_status = $r_dlvd['dlvd_receive_status'];
		$dlvd_mat_code = $r_dlvd['dlvd_mat_code'];
		$dlvd_mat_name = html_quot($r_dlvd['mat_th_name']);		
		$dlvd_mat_group = $r_dlvd['dlvd_mat_group'];
		$dlvd_mat_group_name = $r_dlvd['sptpg_name'];	
		
		$dlvd_remark = html_quot($r_dlvd['sptd_remark']);
		$dlvd_qty = $r_dlvd['dlvd_qty'];
		$dlvd_unit_code = $r_dlvd['dlvd_unit_code'];
		$dlvd_unit_name = html_quot($r_dlvd['unit_name']);
		
		$mat_pcs_per_box = $r_dlvd['mat_pcs_per_box'];
		$mat_um_conv = $r_dlvd['mat_um_conv'];
		$mat_gross_weight = $r_dlvd['mat_gross_weight'];
														
		$weight_per_pcs = 0;
		if ($dlvd_mat_group == "MT") {
			if ($dlvd_unit_code == "P") {
				if ($mat_pcs_per_box != "") {
					$pcs_per_box = "";
					$mat_pcs_per_box_array = explode(" ",$mat_pcs_per_box);
					$pcs_pos = sizeof($mat_pcs_per_box_array) - 2;
					$pcs_per_box = $mat_pcs_per_box_array[$pcs_pos];
					$weight_per_pcs = number_format(($mat_um_conv * $mat_gross_weight * $dlvd_qty) / $pcs_per_box,2);
				}
			}
		}
		elseif($dlvd_mat_group == "BS") {
			$weight_per_pcs = $dlvd_qty * $mat_gross_weight;
		}
		
		$dlvd_receive_status_text = "";
		if ($dlvd_receive_status == 'Y') {
			$dlvd_receive_status_text = "<center><div style='background:green;color:white;width:70px;border-radius:4px'>**รับ**</div></center>";
		}
		if ($dlvd_receive_status == 'N') {
			$dlvd_receive_status_text = "<center><div style='background:red;color:white;width:70px;border-radius:4px'>**ไม่รับ**</div></center>";
		}
		$n++;																			
		?>
		<tr>
			<td style="background-color: lightcyan;text-align:center;"><?=$n?></td>                                            
			<td style="background-color: lightcyan"><?php echo $dlvd_mat_group_name; ?></td>
			<td style="background-color: lightcyan"><?php echo $dlvd_mat_code; ?></td>
			<td style="background-color: lightcyan"><?php echo $dlvd_mat_name; ?></td>
			<td style="max-width:250px;overflow:hidden;background-color: lightcyan"><?php echo $dlvd_remark; ?></td>
			<td style="background-color: lightcyan;text-align:center"><?php echo $dlvd_qty; ?></td>
			<td style="background-color: lightcyan;text-align:center"><?php echo $dlvd_unit_name; ?></td>
			<td style="background-color: lightcyan;text-align:center"></td>																																		
			<td style="background-color: lightcyan;text-align:center"><?php echo $dlvd_receive_status_text; ?></td>
		</tr>
	<?php } ?>															
</table>
</div>