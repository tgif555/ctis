<table width="100%" border="0" cellspacing="0" class="table table-bordered table-condensed">			
	<thead>
	<tr bgcolor="gold" style="font-weight: bold;">
			
		<td style="text-align:center;width:20px">ลำดับ</td>			
		<td style="text-align:center;width:400px">รายการ</td>
		<td style="text-align:center;">จำนวน</td>
		<td style="background:green;color:white;text-align:center;width:20px">รับ</td>	
		<td style="background:red;color:white;text-align:center;width:20px">ไม่</td>
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
		if ($sptd_unit_code == "P") {
			if ($mat_pcs_per_box != "") {
				$pcs_per_box = "";
				$mat_pcs_per_box_array = explode(" ",$mat_pcs_per_box);
				$pcs_pos = sizeof($mat_pcs_per_box_array) - 2;
				$pcs_per_box = $mat_pcs_per_box_array[$pcs_pos];
				$weight_per_pcs = number_format(($mat_um_conv * $mat_gross_weight * $sptd_qty_order) / $pcs_per_box,2);
			}
		}
		$n++;																			
		?>
		<input type="hidden" name="dlvd_id_<?php echo $dlvd_id?>" value="<?php echo $dlvd_id;?>">
		<tr>
			<td style="background-color: lightcyan;text-align:center;"><?=$n?></td>                                            
			<td style="background-color: lightcyan;"><?php echo '['.$dlvd_mat_code.'] ' .$dlvd_mat_name; ?></td>
			<td style="background-color: lightcyan;text-align:center"><?php echo $dlvd_qty . ' ' . $dlvd_unit_name; ?></td>	
			<td style="background-color: lightcyan;text-align:center;">
				<input type="radio" name="radio_rec_<?php echo $dlvd_id?>" checked="checked" value="Y" style='background: #00ff00' onclick="RadioHighLightColor(document.all.radio_rec_<?php echo $dlvd_id?>,'#00ff00')">
			</td>
			<td style="background-color: lightcyan;text-align:center;">
				<input type="radio" name="radio_rec_<?php echo $dlvd_id?>" value="N" onclick="RadioHighLightColor(document.all.radio_rec_<?php echo $dlvd_id?>,'red')">
			</td>			
		</tr>
	<?php } ?>															
</table>
