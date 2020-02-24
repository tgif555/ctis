<div>
<?php 
$actiondetail = mssql_escape($_REQUEST['actiondetail']);
$packing_sptd_id = mssql_escape($_REQUEST['packing_sptd_id']);
?>
<table border="0" width="100%" cellspacing="0" cellpadding="4" class="table table-bordered table-condensed">			
	<thead>
	<tr>
		<td colspan=17>
			<?php if ($can_save_dn) {?>
				<div class="btn btn-small" style="float: left;background:blue;color:white;margin-top:5px; margin-bottom:10px; width: 180px;" onclick='javascript:sptd_save_dn();'> 
				<i class="icon-white icon-pencil"></i>
				<span><b>Save DN & NOTE & REMARK</b></span>
				</div>
			<?php }?>
		</td>
	</tr>
	<tr>
		<td colspan=17>
			<?php if ($can_save_dn) {?>
				<div>
				<b>Remark:</b><br>
				<textarea id="sptm_oper_note" name="sptm_oper_note" rows=3 style="width:500px;color:red"><?php echo $sptm_oper_note?></textarea>
				</div>
			<?php }?>
		</td>	
	</tr>
	<tr bgcolor="paleturquoise" style="font-weight: bold;">
		<td style="width:20px">ลำดับ</td>			
		<td style="width:100px;text-align:center;">รหัสกระเบื้อง</td>
		<td style="width:250px;text-align:center;">ชื่อกระเบื้อง</td>
		<td style="width:140px;text-align:center;">หมายเหตุ</td>
		<td style="width:80px;text-align:center;background-color:green;color:white">จำนวนที่สั่ง</td>
		<td style="width:80px;text-align:center;">หน่วย</td>	
		<!--td style="width:50px;text-align:center;">KG</td-->
		
		<td style="width:80px;text-align:center;background-color:green;color:white">รับของแล้ว</td>
		<td style="width:80px;text-align:center;background-color:red;color:white">ไม่รับ</td>
		<td style="width:80px;text-align:center;background-color:orange;color:black">ระหว่างส่ง</td>
		<td style="width:80px;text-align:center;background-color:yellow;color:black">พร้อมส่ง</td>
		
		<td style="width:80px;text-align:center;background-color:red;color:white">*ค้างส่ง *</td>
		
		<td style="width:60px;text-align:center;">Picking</td>
		
		<td style="width:50px;text-align:center;">OH</td>
		<td style="width:50px;text-align:center;">FOC</td>
		<td style="width:60px;text-align:center;color:red" title="** ไม่มีสินค้า **">NGS</td>
		<td style="width:100px;text-align:center;">Action</td>
		<td style="text-align:center;"></td>
	</tr>
	</thead>									
	<?php
	$n = 0;
	$sql = "SELECT * FROM sptd_det" .
		" INNER JOIN material ON mat_code = sptd_mat_code" .
		" INNER JOIN unit_mstr ON unit_code = sptd_unit_code" .
		" where sptd_sptm_nbr = '$sptm_nbr' and sptd_mat_group = 'MT'";
														
	$result = sqlsrv_query( $conn, $sql );											
	while($r_sptd = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {	
		//$sptd_status= $r_sptd['sptd_status'];
		$sptd_id = $r_sptd['sptd_id'];
		$sptd_sptm_nbr = $r_sptd['sptd_sptm_nbr'];																							
		$sptd_mat_code = $r_sptd['sptd_mat_code'];												
		$sptd_mat_name = html_quot($r_sptd['mat_th_name']);
		if ($r_sptd['mat_th_name'] != $r_sptd['mat_en_name']) {
			$sptd_mat_name .= "<br>" . html_quot($r_sptd['mat_en_name']);
		}
		$sptd_qty_order = $r_sptd['sptd_qty_order'];												
		$sptd_unit_code = $r_sptd['sptd_unit_code'];
		$sptd_unit_name = html_quot($r_sptd['unit_name']);
		$sptd_mat_group = $r_sptd['sptd_mat_group'];
		//$sptd_seq = $r_sptd['sptd_seq'];
		$sptd_remark = html_quot($r_sptd['sptd_remark']);
		
		$sptd_dn_nbr = $r_sptd['sptd_dn_nbr'];
		$sptd_oper_note = $r_sptd['sptd_oper_note'];
		
		$stkm_qty_oh = findsqlval("stkm_mstr","stkm_qty_oh","stkm_mat_code",$sptd_mat_code,$conn);
		if ($stkm_qty_oh == "") {$stkm_qty_oh = 0;}
		
		$sptd_qty_received = $r_sptd['sptd_qty_received'];
		$sptd_qty_not_received = $r_sptd['sptd_qty_not_received'];
		$sptd_qty_shipment = $r_sptd['sptd_qty_shipment'];
		$sptd_qty_delivery = $r_sptd['sptd_qty_delivery'];
		$sptd_qty_packing = $r_sptd['sptd_qty_packing'];
		$sptd_qty_nogood = $r_sptd['sptd_qty_nogood'];
		
		$sptd_qty_await = ($sptd_qty_order - $sptd_qty_received - $sptd_qty_not_received - $sptd_qty_shipment - $sptd_qty_delivery);
		
		$sptd_send_manual_foc = $r_sptd['sptd_send_manual_foc'];
		$manual_foc_color = "orange";
		if ($sptd_send_manual_foc) {
			$manual_foc_color = "green";
		}
		$sptd_dn_status = $r_sptd['sptd_dn_status'];
		$dn_status_color = "";
		if ($sptd_dn_status == "10") { $dn_status_color = "background:red;color:white;";}
		if ($sptd_dn_status == "90") { $dn_status_color = "background:green;color:white;";}
		
		if ($sptd_qty_await > 0) {
			//ดึงสถานะจาก focd_det
			$focd_focm_nbr = "";
			$focd_focm_status_code = "";
			$focd_focm_dn_nbr = "";
			$focd_found = false;
			$sql_focd = "SELECT * from focd_det where focd_sptd_id = '$sptd_id' and focd_sptbc_id = ''";
			$result_focd = sqlsrv_query($conn, $sql_focd);	
			$r_focd = sqlsrv_fetch_array($result_focd, SQLSRV_FETCH_ASSOC);		
			if ($r_focd) {
				$focd_found = true;
				$focd_focm_nbr = $r_focd['focd_focm_nbr'];
				//$focd_focm_status_code = $r_focd['focd_focm_status_code'];
				$focd_focm_status_code = $r_focd['focd_status_code'];
				$focd_focm_dn_nbr = html_quot($r_focd['focd_focm_dn_nbr']);
				
			}
			$focd_focm_status_text = "";
			$focd_focm_title_text = "";
			if ($focd_focm_status_code!="") {
				if ($focd_focm_status_code=='10') {
					$focd_focm_status_text = "<span style='background:yellow;color:black'>WAIT</span>";
					$focd_focm_title_text = "** สร้างกลุ่มข้อมูลเพื่อทำ FOC แล้ว\nและรอนำรายการไปเปิด SAP SO (FOC) **";
				}
				if ($focd_focm_status_code=='20') {
					$focd_focm_status_text = "<span style='background:gray;color:yellow;'>&nbsp;&nbsp;SO&nbsp;&nbsp;</span>";
					$focd_focm_title_text = "** เปิด SAP SO (FOC) แล้ว **";
				}
				if ($focd_focm_status_code=='30') {
					$focd_focm_status_text = "<span style='background:orange;color:black'>&nbsp;&nbsp;DN&nbsp;&nbsp;</span>";
					$focd_focm_title_text = "** ได้เลข SAP DN แล้ว \nDN: $focd_focm_dn_nbr**";
				}
				if ($focd_focm_status_code=='90') {
					$focd_focm_status_text = "<span style='background:green;color:white'><b>&nbsp;&nbsp;OK&nbsp;&nbsp;</b></span>";
					$focd_focm_title_text = "** คลังได้ส่งของไปยังห้องตัวอย่างแล้ว\n(รอรับสินค้าและทำรับเข้าระบบ Stock ห้องตัวอย่างค่ะ)\nDN: $focd_focm_dn_nbr**";
				}
			}
			else {
				if ($focd_found) {
					$focd_focm_status_text = "<span style='background:red;color:white'>WAIT</span>";
					$focd_focm_title_text = "** ยังไม่ได้สร้างกลุ่มข้อมูลเพื่อทำ FOC **";
				}
			}
		}
		//Reset foc text when document is close&rejec&cancel
		if (inlist("880,890,990",$sptm_step_code)) {
			$focd_focm_status_text = "";
			$focd_focm_title_text = "";
		}
		//										
		$mat_pcs_per_box = $r_sptd['mat_pcs_per_box'];
		$mat_um_conv = $r_sptd['mat_um_conv'];
		$mat_gross_weight = $r_sptd['mat_gross_weight'];
														
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
														
		$sptd_curprocessor = $r_sptd['sptd_curprocessor'];
		$sptd_step_name = findsqlval("step_mstr", "step_name", "step_code", $sptd_step,$conn);
		
		$n++;																			
		?>
		<tr id="trsptd_<?php echo $sptd_id?>">
			<td style="background-color: lightcyan;text-align:center;"><?=$n?></td>                                            
			<td style="background-color: lightcyan">
				<?php echo $sptd_mat_code; ?>
				<?php if ($can_send_manual_foc) {?>
					<br>
					<input type="hidden" name="sptd_id_dn<?php echo $sptd_id?>" value="<?php echo $sptd_id?>">
					<input type="text" name="sptd_dn<?php echo $sptd_id?>" class="inputtext_s" style="width: 90px;"  maxlength="50" value="<?php echo $sptd_dn_nbr?>">
					<br>
					<span><b>DN Status:</b>
					<select name="sptd_dn_status<?php echo $sptd_id?>" style="font-size:8pt;width:90px;<?php echo $dn_status_color?>">
						<option value="">--</option>
						<option value="10" <?php if($sptd_dn_status == '10') {echo 'selected';}?>>รอ DN</option>
						<option value="90" <?php if($sptd_dn_status == '90') {echo 'selected';}?>>ได้แล้ว</option>
					</select>
				<?php }?>
			</td>
			<td style="background-color: lightcyan">
				<?php echo $sptd_mat_name; ?>
			</td>
			<td style="max-width:140px;overflow:hidden;background-color: lightcyan" title="<?php echo $sptd_remark?>">
				<?php echo $sptd_remark; ?>
				<?php if ($can_sptd_oper_note) {?>
					<br>
					<input type="text" name="sptd_oper_note<?php echo $sptd_id?>" class="inputtext_s" style="width:120px;color:red"  maxlength="120" title="<?php echo $sptd_oper_note?>" value="<?php echo $sptd_oper_note?>">
				<?php }?>
			</td>
			<td style="text-align:center">
				<?php if ($sptd_qty_order == ($sptd_qty_received + $sptd_qty_not_received)) {?>
					<span class="bubbletext" style="background:green;color:white">
				<?php } else {?>
					<span class="bubbletext" style="background:red;color:white">
				<?php }?>
				<?php echo $sptd_qty_order; ?>
				</span>
			</td>
			<td style="text-align:center"><?php echo $sptd_unit_name; ?></td>
			<!--td style="text-align:center"><?php echo $weight_per_pcs; ?></td-->
			
			<td style="text-align:center;">
				<?php if ($sptd_qty_received > 0) { ?>
					<span class="bubbletext"><?php echo $sptd_qty_received; ?></span>
				<?php }?>
			</td>
			<td style="text-align:center;">
				<?php if ($sptd_qty_not_received > 0) { ?>
					<span class="bubbletext"><?php echo $sptd_qty_not_received; ?></span>
				<?php }?>
			</td>
			<td style="text-align:center;">
				<?php if ($sptd_qty_shipment > 0) { ?>
					<span class="bubbletext"><?php echo $sptd_qty_shipment; ?></span>
				<?php }?>
			</td>
			<td style="text-align:center;">
				<?php if ($sptd_qty_delivery > 0) { ?>
					<span class="bubbletext"><?php echo $sptd_qty_delivery; ?></span>
				<?php }?>
			</td>
			
			<td style="background-color: lightcyan;text-align:center;">
				<?php if ($sptd_qty_await > 0) { ?>
					<span class="bubbletext"><?php echo $sptd_qty_await; ?></span>
				<?php }?>
			</td>
			<?php if ($sptd_id != $packing_sptd_id) {?>
				<td style="text-align:right">
					<?php if ($sptd_qty_packing > 0) { ?>
						<span class="bubbletext" style="background:yellow; color:black"><?php echo $sptd_qty_packing; ?></span>
					<?php }?>
				</td>
				<td style="text-align:center;">
					<?php if ($sptd_unit_code == 'P' and $sptd_qty_await > 0 and !inlist("880,890,990",$sptm_step_code)) {?>
						<span class="bubbletext" 
							<?php if ($sptd_qty_await > $stkm_qty_oh) { 
								echo "style='background:red;color:white'";
							} else {
								echo "style='background:white'";}
							?>><?php echo $stkm_qty_oh; ?>
						</span>
					<?php }?>
				</td>
				<!--td style='text-align:center' title="<?php echo $focd_focm_title_text?>"><?php echo $focd_focm_status_text?></td-->
				
				<td style="text-align:center">
					<?php if($can_packing and $sptd_qty_await > 0) {?>
						<?php if($sptd_dn_nbr == '') {?>
							<input type="button" style="width:30px;font-size:8pt;color:white;background:<?php echo $manual_foc_color?>" value="foc" onclick="createmanualfoc('<?php echo $sptd_mat_code?>','<?php echo $sptd_id?>','','<?php echo $sptd_qty_await?>','<?php echo $sptd_unit_code?>')">
						<?php }?>
					<?php }?>
				</td>
				<td style="text-align:center;">
					<?php if ($sptd_qty_nogood > 0) { ?>
						<span class="bubbletext" style="background:red;color:white"><?php echo $sptd_qty_nogood; ?></span>
					<?php }?>
				</td>
				<td style="background-color: lightcyan;text-align:right">
					<center>
						<?php if($can_editing) {?>
							<a href="javascript:void(0)" onclick='del_sptd_product("<?php echo $sptd_id; ?>")'><font color="red">Del</font></a> | 
							<a href="#edit_sptd_product<?php echo $sptd_id; ?>" role="button" data-toggle="modal">Edit</a>
						<?php }?>
					</center>
					<?php if($can_packing and $sptd_qty_await > 0) {?>
						<a href="sptdmnt.php?sptmnumber=<?php echo encrypt($sptm_nbr, $key)?>&packing_sptd_id=<?php echo $sptd_id?>&actiondetail=packing_product#trsptd_<?php echo $sptd_id?>">Picking</a>
						
					<?php }?>
					
					<?php if($can_editing) {?>
						<div id="edit_sptd_product<?php echo $sptd_id; ?>" class="modal hide fade" tabindex="1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">																																																																				
							<form name="frm_edit_sptd_product<?php echo $sptd_id;?>" autocomplete=OFF method="post" action="../serverside/sptdmnt_product_post.php" enctype="multipart/form-data">
								<input type="hidden" name="action" value="edit_sptd_product">					
								<input type="hidden" name="sptm_nbr" value="<?php echo $sptm_nbr;?>">
								<input type="hidden" name="sptd_id" value="<?php echo $sptd_id;?>">																
								<input type="hidden" name="pg" value="<?php echo $pg;?>">
																				
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
									<h3 id="myModalLabel">แก้ไขกระเบื้อง :: <?php echo $sptm_nbr?></h3>
								</div>
								<div class="">
									<table class="table table-c	ondensed table-responsive">	
										<tbody bgcolor=#f5f5ef>																																																																				
										<tr>
											<td style="text-align:right;width:100px;vertical-align: middle;"><b>รหัสกระเบื้อง:</b></td>
											<td> 
												<input type="text" name="sptd_mat_code" value="<?php echo $sptd_mat_code?>" class="inputtext_s" style="width: 150px;" maxlength="30">
												<button type="button" class="btn btn-default" style="margin: auto;" 
													OnClick="helppopup('../_help/getproduct.php','frm_edit_sptd_product<?php echo $sptd_id?>','sptd_mat_code','sptd_mat_name',document.frm_edit_sptd_product<?php echo $sptd_id?>.sptd_mat_code.value)">
													<span class="icon icon-search" aria-hidden="true"></span>
												</button>
												<input name="sptd_mat_name"  value="<?php echo $sptd_mat_name?>" disabled type="text" class="inputtext_s" style="margin:auto;width: 250px;">
											</td> 
										</tr>
										<tr>
											<td style="text-align:right; vertical-align: middle;"><b>จำนวน:</b></td>
											<td style="width:150px">
												<input type="text"  name="sptd_qty_order" value="<?php echo $sptd_qty_order?>" class="inputtext_s" style="width: 50px;">
												<select name="sptd_unit_code" class="inputtext_s" style="margin:auto;width: 100px;color:red" >
													<option value="">หน่วย</option>
													<?php 
													$sql_unit = "SELECT unit_code,unit_name FROM unit_mstr WHERE unit_active = 1 and unit_code <> 'B' order by unit_seq";
													$result_unit_list = sqlsrv_query( $conn,$sql_unit);																													
													while($r_unit_list=sqlsrv_fetch_array($result_unit_list, SQLSRV_FETCH_ASSOC)) {
													?>
														<option  style="color:black" value="<?php echo $r_unit_list['unit_code'];?>"
															<?php if ($sptd_unit_code == $r_unit_list['unit_code']) { echo 'selected';} ?>
															><?php echo $r_unit_list['unit_name'];?>
														</option> 
													<?php } ?>
												</select>															
											</td>
										</tr>					
										<tr>
											<td style="text-align:right; vertical-align: middle;"><b>หมายเหตุ:</b></td>
											<td> 
												<input type="text" name="sptd_remark" value="<?php echo $sptd_remark?>" class="inputtext_s" style="width: 450px;" maxlength="500">
											</td>
										</tr>							
										</tbody>
									</table>					
								</div>
								<div class="modal-footer">
									<button type="submit" class="btn btn-success fileinput-button paddingleftandright10 margintop20 marginleft20" data-toggle="modal" onclick='sptd_product_postform("<?php echo 'frm_edit_sptd_product'.$sptd_id;?>")'>
										<i class="icon-check icon-white"></i>
										<span>Save</span>
									</button>											
								</div>	
							</form>																																																			
						</div>
					<?php }?>
				</td>
			<?php }?>
			
			<?php if ($sptd_id == $packing_sptd_id and $actiondetail=="packing_product") {?>
				<td style="text-align:right">
					<form name="frm_packing_sptd_product" autocomplete=OFF method="post" action="../serverside/sptdmnt_product_post.php" enctype="multipart/form-data">
						<input type="hidden" name="action" value="packing_sptd_product">																		
						<input type="hidden" name="sptm_nbr" value="<?php echo $sptm_nbr;?>">
						<input type="hidden" name="sptd_id" value="<?php echo $sptd_id;?>">
						<input type="hidden" name="pg" value="<?php echo $pg;?>">
						<input type="text" name="sptd_qty_packing" value="<?php if ($sptd_qty_packing>0) {echo $sptd_qty_packing;} else {echo $sptd_qty_await;}?>" class="inputtext_s" style="width: 50px;text-align:center;font-size:12pt;font-weight: bold;color:red">
					</form>
				</td>
				
				<td style="text-align:center;">
					<span class="bubbletext" 
					<?php if ($sptd_qty_await > $stkm_qty_oh) { 
						echo "style='background:red;color:white'";
					} else {
						echo "style='background:white'";}
					?>><?php echo $stkm_qty_oh; ?>
					</span>
				</td>
				<!--td style='text-align:center' title="<?php echo $focd_focm_title_text?>"><?php echo $focd_focm_status_text?></td-->
				<td></td>
				<td style="text-align:center;">
					<?php if ($sptd_qty_nogood > 0) { ?>
						<span class="bubbletext"><?php echo $sptd_qty_nogood; ?></span>
					<?php }?>
				</td>
				<td style="text-align:center">												
					<div class="btn btn-mini btn-success" style="margin-top:0px; margin-bottom:10px; width: 40px;" onclick='packing_sptd_product("<?php echo $sptd_id?>")'>
						<i class="icon-white icon-ok"></i>
						<span>Save</span>
					</div>																	
					<div class="btn btn-mini btn-danger" style="margin-top:0px; margin-bottom:10px; width: 40px;" onclick="window.location.href='sptdmnt.php?sptmnumber=<?php echo encrypt($sptm_nbr, $key)?>&activeid=<?php echo encrypt($sptd_id,$key)?>#trsptd_<?php echo $sptd_id?>'">
						<i class="icon-white icon-remove"></i>
						<span>Cancel</span>															
					</div>
				</td>
			<?php }?>
			<td width=10 align=center bgcolor="white"><?php if($activeid==$sptd_id) {echo "<img src='../_images/icon_dot_left.gif'>";}?></td>																																			
		</tr>
	<?php } ?>															
</table>
</div>


<div id="add_sptd_product" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">																																																																				
	<form name="frm_add_sptd_product" autocomplete=OFF method="post" action="../serverside/sptdmnt_product_post.php" enctype="multipart/form-data">
		<input type="hidden" name="action" value="add_sptd_product">																		
		<input type="hidden" name="sptm_nbr" value="<?php echo $sptm_nbr;?>">
		<input type="hidden" name="sptd_mat_group" value="MT">
		<input type="hidden" name="pg" value="<?php echo $pg;?>">															
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			<h3 id="myModalLabel">เพิ่มกระเบื้อง :: <?php echo $sptm_nbr?></h3>
		</div>
		<!--div class="modal-body"-->
		<div class="">
			<table border=1 class="table table-condensed table-responsive">	
			<tbody bgcolor=#f5f5ef>																	
				<tr>
					<td style="text-align:right;width:100px;vertical-align: middle;"><b>รหัสกระเบื้อง:</b></td>
					<td> 
						<input type="text" name="sptd_mat_code" class="inputtext_s" style="width: 150px;" maxlength="30">
						<button type="button" class="btn btn-default" style="margin: auto;" 
						OnClick="helppopup('../_help/getproduct.php','frm_add_sptd_product','sptd_mat_code','sptd_mat_name',document.frm_add_sptd_product.sptd_mat_code.value)">
							<span class="icon icon-search" aria-hidden="true"></span>
						</button>
						<input name="sptd_mat_name"  disabled type="text" class="inputtext_s" style="margin:auto;width: 250px;">
					</td> 
				</tr>
				<tr>
					<td style="text-align:right; vertical-align: middle;"><b>จำนวน:</b></td>
					<td style="width:150px">
						<input type="text"  name="sptd_qty_order" class="inputtext_s" style="width: 50px;">
						<select name="sptd_unit_code" class="inputtext_s" style="margin:auto;width: 100px;color:red" >
							<option value="">หน่วย</option>
							<?php 
							$sql_unit = "SELECT unit_code,unit_name FROM unit_mstr WHERE unit_active = 1 and unit_code <> 'B' order by unit_seq";
							$result_unit_list = sqlsrv_query( $conn,$sql_unit);																													
							while($r_unit_list=sqlsrv_fetch_array($result_unit_list, SQLSRV_FETCH_ASSOC)) {
							?>
									<option  style="color:black" value="<?php echo $r_unit_list['unit_code'];?>"><?php echo $r_unit_list['unit_name'];?></option> 
								<?php } ?>
						</select>															
					</td>
				</tr>
				<tr>
					<td style="text-align:right; vertical-align: middle;"><b>หมายเหตุ:</b></td>
					<td> 
						<input type="text" name="sptd_remark" class="inputtext_s" style="width: 450px;" maxlength="255">
					</td>
				</tr>
			</tbody>
			</table>					
		</div>
		<?php if ($can_editing) {?>
		<div class="modal-footer">
			<button type="submit" class="btn btn-success fileinput-button paddingleftandright10 margintop20 marginleft20" data-toggle="modal" onclick='sptd_product_postform("<?php echo 'frm_add_sptd_product';?>")'>
				<i class="icon-check icon-white"></i>
				<span>Save</span>
			</button>											
		</div>	
		<?php }?>
	</form>																																																			
</div>

<div id="upload_sptd_product" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">																																																																				
	<form id="frm_upload_sptd_product" name="frm_upload_sptd_product" autocomplete=OFF method="post" enctype="multipart/form-data">
		<input type="hidden" name="action" value="upload_sptd_product">																		
		<input type="hidden" name="sptm_nbr" value="<?php echo $sptm_nbr;?>">
		<input type="hidden" name="sptd_mat_group" value="MT">
		<input type="hidden" name="pg" value="<?php echo $pg;?>">															
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			<h3 id="myModalLabel">นำรายการกระเบื้องเข้าจาก Excel File :: <?php echo $sptm_nbr?></h3>
		</div>
		<!--div class="modal-body"-->
		<div class="">
			<table border=1 class="table table-condensed table-responsive">	
			<tbody bgcolor=#f5f5ef>																	
				<tr>
					<td style="width:150px;text-align:right; vertical-align: middle;"><b>File Excel (รายการกระเบื้อง):</b></td>
					<td><input type="file"  name="fileupload_sptd_product" class="inputtext_s" style="width: 450px;"></td>	 
				</tr>
			</tbody>
			</table>					
		</div>
		<?php if ($can_editing) {?>
		<div class="modal-footer">
			<button type="submit" class="btn btn-success fileinput-button paddingleftandright10 margintop20 marginleft20" data-toggle="modal" onclick="upload_sptd_product_postform()">
				<i class="icon-check icon-white"></i>
				<span>Start Upload</span>
			</button>											
		</div>	
		<?php }?>
	</form>																																																			
</div>

<form name="frm_del_sptd_product" method="post" action="../serverside/sptdmnt_product_post.php">
	<input type="hidden" name="action" value="del_sptd_product">	
	<input type="hidden" name="sptm_nbr" value="<?php echo $sptm_nbr;?>">		
	<input type="hidden" name="sptd_id">	
	<input type="hidden" name="pg" value="<?php echo $pg?>">
</form>
