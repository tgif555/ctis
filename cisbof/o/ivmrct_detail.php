<table width="100%" border="0" cellspacing="0" class="table table-bordered table-condensed">			
	<thead>
	<tr bgcolor="gold" style="font-weight: bold;">
			
		<td style="width:50px;text-align:center">ลำดับ</td>			
		<td style="width:100px;">Delivery No</td>
		<td style="width:100px;text-align:center">หมายเลขใบเบิก</td>
		<td style="width:100px;text-align:center">รายการสินค้า</td>
		<td style="width:100px;text-align:center">จำนวนสินค้า</td>
		<td style="width:50px;background:green;color:white;text-align:center;width:20px">รับ</td>	
		<td style="width:50px;background:red;color:white;text-align:center;width:20px">ไม่</td>
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
		$ivd_receive_status = $r_ivd['ivd_receive_status'];
		$ivd_receive_status_cmmt = html_quot($r_ivd['ivd_receive_status_cmmt']);
		$ivd_create_by = $r_ivd['ivd_create_by'];
		
		$n++;																			
		?>
		<input type="hidden" name="ivd_id_<?php echo $ivd_id?>" value="<?php echo $ivd_id;?>">
		<tr>
			<td style="background-color: lightcyan;text-align:center;"><?=$n?></td>                                            
			<td style="background-color: lightcyan;"><?php echo $ivd_dlvm_nbr; ?></td>
			<td style="background-color: lightcyan;"><?php echo $ivd_sptm_nbr; ?></td>
			<td style="background-color: lightcyan;text-align:center"><?php echo sumdlvddet($ivd_dlvm_nbr,$conn); ?></td>
			<td style="background-color: lightcyan;text-align:center"><?php echo sumdlvddetqty($ivd_dlvm_nbr,$conn); ?></td>
			<td style="background-color: lightcyan;text-align:center;">
				<input type="radio" name="radio_rec_<?php echo $ivd_id?>" checked="checked" value="Y" style='background: #00ff00' onclick="RadioHighLightColor(document.all.radio_rec_<?php echo $ivd_id?>,'#00ff00')">
			</td>
			<td style="background-color: lightcyan;text-align:center;">
				<input type="radio" name="radio_rec_<?php echo $ivd_id?>" value="N" onclick="RadioHighLightColor(document.all.radio_rec_<?php echo $ivd_id?>,'red')">
			</td>			
		</tr>
	<?php } ?>															
</table>
