<div class="table-responsive">
	<table width=100% class="table-sm table-bordered">
		<tr>
			<td style="text-align:right; width: 200px;"><b>Quotation For:</b></td>
			<td><?php echo $qtm_to?></td>
			<td style="text-align:right; width: 150px;"><b>Customer:</b></td>
			<td><?php echo $qtm_customer_number?></td>
		</tr>
		<tr>
			<td style="text-align:right; width: 150px;"><b>Quotation Name:</b></td>
			<td><?php echo $qtm_name?></td>
			<td style="text-align:right; width: 150px;"><b>Customer Name:</b></td>
			<td><?php echo $qtm_customer_name?></td>
		</tr>													
		<tr>
			<td style="text-align:right; width: 150px;"><b>Detail:</b></td>
			<td><?php echo $qtm_detail?></td>
			<td style="text-align:right; width: 150px;"><b>Address:</b></td>
			<td><?php echo $qtm_address?></textarea></td>
		</tr>
		<tr>
			<td style="text-align:right; width: 150px;"><b>Date:</b></td>
			<td><?php echo dmytx($qtm_date)?></td>
			<td style="text-align:right; width: 150px;"><b>Amphur:</b></td>
			<td><?php echo $qtm_amphur;?></td>
		</tr>
		<tr>
			<td style="text-align:right; width: 150px;"><b>Expire Date:</b></td>
			<td><?php echo dmytx($qtm_expire_date)?></td>
			<td style="text-align:right; width: 150px;"><b>Province:</b></td>
			<td><?php echo $qtm_province;?></td>
		</tr>
		<tr>
			<td style="text-align:right; width: 150px;"><b>Pre-Paid Amount:</b></td>
			<td><?php echo number_fmt($qtm_prepaid_amt);?></td>
			<td style="text-align:right; width: 150px;"><b>Zip Code:</b></td>
			<td><?php echo $qtm_zip_code;?></td>
		</tr>
		<tr>
			<td style="text-align:right; width: 150px;"><b>Pre-Paid Date:</b></td>
			<td><?php echo dmytx($qtm_prepaid_date);?></td>
			<td style="text-align:right; width: 150px;"><b>Tel Contact:</b></td>
			<td><?php echo $qtm_tel_contact;?></td>
		</tr>
		<tr>
			<td style="text-align:right; width: 150px;"><b>Discount:</b></td>
			<td><?php echo number_fmt($qtm_disc);?></td>
			<td style="text-align:right; width: 150px;"><b>Line-ID:</b></td>
			<td><?php echo $qtm_lineid;?></td>
		</tr>
		<tr>
			<td style="text-align:right; width: 150px;"><b>Discount Unit:</b></td>
			<td><?php echo $qtm_disc_unit_name;?></td>
			<td style="text-align:right; width: 150px;"><b>Email:</b></td>
			<td><?php echo $qtm_email;?></td>
		</tr>
		<tr>
			<td style="text-align:right; width: 150px;"><b>Remarks:</b></td>
			<td colspan=3><?php echo $qtm_remark;?></td>		
		</tr>			
		<?php if ($qtm_attach_link != "") {?>
			<?php $qtm_attach = $qtm_attach_link;?>
			<tr>
				<td style="text-align:right; width: 150px;"><b>Attach File:</b></td>
				<td colspan=2 bgcolor=#ffe6e6><?php echo $qtm_attach;?></td>
				<td></td>
			</tr>												
		<?php }?>																		
	</table>
</div>