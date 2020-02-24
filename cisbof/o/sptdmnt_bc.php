<div>
<?php 
$actiondetail = mssql_escape($_REQUEST['actiondetail']);
$packing_sptd_id = mssql_escape($_REQUEST['packing_sptd_id']);
?>
<table width="100%" border="0" cellspacing="1" cellpadding="4" class="table table-bordered table-condensed">			
	<thead>
		<tr bgcolor="paleturquoise" style="font-weight: bold;">
			<td style="width:20px">ลำดับ</td>			
			<td style="width:120px;text-align:center;">ชื่อบอร์ด</td>
			<td style="width:350px;text-align:center;">กระเบื้อง</td>
			<td style="width:80px;text-align:center;background-color:green;color:white">จำนวนที่สั่ง</td>
			<td style="width:50px;text-align:center;">หน่วย</td>	
			<!--td style="width:50px;text-align:center;">น้ำหนัก</td-->
			<td style="width:80px;text-align:center;background-color:green;color:white">รับของแล้ว</td>
			<td style="width:80px;text-align:center;background-color:red;color:white">ไม่รับ</td>
			<td style="width:80px;text-align:center;background-color:orange;color:black">ระหว่างส่ง</td>
			<td style="width:80px;text-align:center;background-color:yellow;color:black">พร้อมส่ง</td>
			<td style="width:80px;text-align:center;background-color:red;color:white">* ค้างส่ง *</td>
			<td style="width:100px;text-align:center;">Picking</td>
			<td style="width:60px;text-align:center;color:red" title="** ไม่มีสินค้า **">NGS</td>
			<td style="width:150px;text-align:center;">Action</td>
			<td style="width:20px;text-align:center;"></td>
		</tr>											
	</thead>
	<?php
	$uploadpath = "../_fileuploads/bc";
	$board_no = 0;
	$sql_sptbc = "SELECT * FROM sptd_det where sptd_sptm_nbr = '$sptm_nbr' and sptd_mat_group = 'BC'";
	$result_sptbc = sqlsrv_query( $conn, $sql_sptbc);											
	while($r_sptbc = sqlsrv_fetch_array($result_sptbc, SQLSRV_FETCH_ASSOC)) {
		//$sptd_status= $r_sptbc['sptd_status'];
		$sptd_id = $r_sptbc['sptd_id'];
		$sptd_sptm_nbr = $r_sptbc['sptd_sptm_nbr'];
		$sptd_mat_code = $r_sptbc['sptd_mat_code'];
		$sptd_mat_name = "BOARD Customize";
		$sptd_mat_group = $r_sptbc['sptd_mat_group'];
		$sptd_qty_order = $r_sptbc['sptd_qty_order'];												
		$sptd_unit_code = $r_sptbc['sptd_unit_code'];
		$sptd_unit_name = findsqlval("unit_mstr","unit_name","unit_code",$sptd_unit_code,$conn);
		$sptd_s_filename = $r_sptbc['sptd_s_filename'];
		$sptd_o_filename = $r_sptbc['sptd_o_filename'];
		//$sptd_seq = $r_sptbc['sptd_seq'];
		$sptd_remark = html_quot($r_sptbc['sptd_remark']);
		
		
		$stkm_qty_oh = findsqlval("stkm_mstr","stkm_qty_oh","stkm_mat_code",$sptd_mat_code,$conn);
		if ($stkm_qty_oh == "") {$stkm_qty_oh = 0;}
			
		$sptd_qty_received = $r_sptbc['sptd_qty_received'];	
		$sptd_qty_not_received = $r_sptd['sptd_qty_not_received'];
		$sptd_qty_shipment = $r_sptbc['sptd_qty_shipment'];
		$sptd_qty_delivery = $r_sptbc['sptd_qty_delivery'];
		$sptd_qty_packing = $r_sptbc['sptd_qty_packing'];
		$sptd_qty_nogood = $r_sptbc['sptd_qty_nogood'];
		
		$sptd_qty_await = ($sptd_qty_order - $sptd_qty_received - $sptd_qty_not_received - $sptd_qty_shipment - $sptd_qty_delivery);
		
		$sptd_s_filename_ext = strtoupper(explode(".",$sptd_s_filename)[1]);
		if (inlist("JPG,PNG,BMP",$sptd_s_filename_ext)) { $showstyle = "rel='prettyPhoto'"; }
		else { $showstyle = "target='_blank'";}
		
		$board_no++;
		?>
		<tr id="trsptd_<?php echo $sptd_id?>" style="background-color:#EEE8AA">
			<td style="background-color: mintcream;text-align:center;"><?=$board_no?>
			<?php if ($sptd_s_filename != "") {?>
				<br>
				<a href="<?php echo $uploadpath.'/'.$sptd_s_filename?>" <?php echo $showstyle?> title="<?php echo $sptd_o_filename?>">
					<img src="../_images/attachment.png" width=16>
				</a>
			<?php }?>
			</td>
			<td style="max-width:120px;overflow:hidden;background-color: mintcream;" title="<?php echo $sptd_remark?>"><?php echo $sptd_remark; ?></td>
			
			<td style="background-color: #FFFFE0;">
				<table width="100%">
					<tr style="height:25px;color:black">
						<td style="background-color:#FFFACD;width:20px">ลำดับ</td>	
						<td style="background-color:#FFFACD;width:300px;">กระเบื้อง 
							<?php if ($can_editing) {?>
							<a href="#upload_board_bc_product" onclick='setvalue_upload_board_bc_product("frm_upload_board_bc_product","<?php echo $sptd_id?>")' role="button" data-toggle="modal"><font color=red>[Upload]</font></a> |
							<a href="#add_bc_product" onclick='setvalue_board_bc_product("frm_add_bc_product","<?php echo $sptd_id?>")' role="button" data-toggle="modal"><font color=red>[เพิ่มกระเบื้อง]</font></a>
							<?php }?>
						</td>
						<td style="background-color:#FFFACD;width:50px;text-align:center;">OH</td>
						<td style="background-color:#FFFACD;width:50px;text-align:center;">FOC</td>
						<td style="background-color:#FFFACD;text-align:center">Action</td>										
					</tr>												
					<?php
					$bc_product_cnt = 0;
					$sql_bcdet = "SELECT * FROM sptbc_det" .
						" INNER JOIN material ON mat_code = sptbc_mat_code" .
						" where sptbc_sptm_nbr = '$sptm_nbr' and sptbc_sptd_id = '$sptd_id'";
																
					$result_bcdet = sqlsrv_query( $conn, $sql_bcdet );											
					while($r_bcdet = sqlsrv_fetch_array($result_bcdet, SQLSRV_FETCH_ASSOC)) {	
						$sptbc_id = $r_bcdet['sptbc_id'];
						$sptbc_mat_code = $r_bcdet['sptbc_mat_code'];
						$sptbc_mat_name = html_quot($r_bcdet['mat_th_name']);
						$sptbc_remark = html_quot($r_bcdet['sptbc_remark']);
						
						$sptbc_dn_nbr = html_quot($r_bcdet['sptbc_dn_nbr']);
						$sptbc_send_manual_foc = html_quot($r_bcdet['sptbc_send_manual_foc']);
						$sptbc_manual_foc_color = "orange";
						if ($sptbc_send_manual_foc) {
							$sptbc_manual_foc_color = "green";
						}
						$sptbc_oper_note = html_quot($r_bcdet['sptbc_oper_note']);
						$sptbc_dn_status = html_quot($r_bcdet['sptbc_dn_status']);
						$sptbc_dn_status_color = "";
						if ($sptbc_dn_status == "10") { $sptbc_dn_status_color = "background:red;color:white;";}
						if ($sptbc_dn_status == "90") { $sptbc_dn_status_color = "background:green;color:white;";}
						$sptbc_qty_await = $sptd_qty_await;
						$sptbc_unit_code = "C";

						//ดึงสถานะจาก focd_det
						$focd_focm_nbr = "";
						$focd_focm_status_code = "";
						$focd_focm_dn_nbr = "";
						$focd_found = false;
						$sql_focd = "SELECT * from focd_det where focd_sptd_id = '$sptd_id' and focd_sptbc_id = '$sptbc_id'";
						
						$result_focd = sqlsrv_query($conn, $sql_focd);	
						$r_focd = sqlsrv_fetch_array($result_focd, SQLSRV_FETCH_ASSOC);		
						if ($r_focd) {
							$focd_found = true;
							$focd_focm_nbr = $r_focd['focd_focm_nbr'];
							$focd_focm_status_code = $r_focd['focd_focm_status_code'];
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
						//Reset foc text when document is close&rejec&cancel
						if (inlist("880,890,990",$sptm_step_code)) {
							$focd_focm_status_text = "";
							$focd_focm_title_text = "";
						}
						
						$stkm_qty_oh = findsqlval("stkm_mstr","stkm_qty_oh","stkm_mat_code",$sptbc_mat_code,$conn);
						if ($stkm_qty_oh == "") {$stkm_qty_oh = 0;}
						
						$bc_product_cnt++;																			
						?>
						<tr style="background-color: #EFF5FB">
							<td style="text-align:center;"><?=$bc_product_cnt?></td>                                            
							<td style="max-width:160px;overflow:hidden;">
								<?php echo "[".$sptbc_mat_code."]<br>" . $sptbc_mat_name;?>
								<?php if ($sptbc_remark!="") {echo "<br><span style='color:red' title='".$sptbc_remark."'>*หมายเหตุ:*<br>".$sptbc_remark."</span>";}?>
								
								<?php if ($can_send_manual_foc) {?>
									<br>
									<input type="hidden" name="sptbc_sptd_id_dn<?php echo $sptbc_id?>" value="<?php echo $sptd_id?>">
									<input type="hidden" name="sptbc_id_dn<?php echo $sptbc_id?>" value="<?php echo $sptbc_id?>">
									<input type="text" name="sptbc_dn<?php echo $sptbc_id?>" class="inputtext_s" style="width: 90px;"  maxlength="50" value="<?php echo $sptbc_dn_nbr?>">
									<br>
									<span><b>DN Status:</b><br>
									<select name="sptbc_dn_status<?php echo $sptbc_id?>" style="font-size:8pt;width:90px;<?php echo $sptbc_dn_status_color?>">
										<option value="">--</option>
										<option value="10" <?php if($sptbc_dn_status == '10') {echo 'selected';}?>>รอ DN</option>
										<option value="90" <?php if($sptbc_dn_status == '90') {echo 'selected';}?>>ได้แล้ว</option>
									</select>
									<br>
									<?php if ($can_sptd_oper_note) {?>
									<span><b>NOTE:</b><br> <input type="text" name="sptbc_oper_note<?php echo $sptbc_id?>" class="inputtext_s" style="width:120px;color:red"  maxlength="120" title="<?php echo $sptbc_oper_note?>" value="<?php echo $sptbc_oper_note?>">
									<?php }?>
								<?php }?>
							</td>

							<td style="text-align:center;">
								<?php if ($sptbc_qty_await > 0 and !inlist("880,890,990",$sptm_step_code)) {?>
								<span class="bubbletext" 
									<?php if ($sptbc_qty_await > $stkm_qty_oh) { 
										echo "style='background:red;color:white'";
									} else {
										echo "style='background:white'";}
									?>><?php echo $stkm_qty_oh; ?>
								</span>
								<?php }?>
							</td>
							<td style="text-align:center">
								<?php if($can_packing and $sptbc_qty_await > 0) {?>
									<?php if($sptbc_dn_nbr == '') {?>
										<input type="button" style="width:30px;font-size:8pt;color:white;background:<?php echo $sptbc_manual_foc_color?>" value="foc" onclick="createmanualfoc('<?php echo $sptbc_mat_code?>','<?php echo $sptd_id?>','<?php echo $sptbc_id?>','<?php echo $sptbc_qty_await?>','<?php echo $sptbc_unit_code?>')">
									<?php }?>
								<?php }?>
							</td>
							
							
							<td style="text-align:center;color:red">
								<?php if ($can_editing) {?>
								<a href="javascript:void();" onclick='del_board_bc_product("<?php echo $sptd_id?>","<?php echo $sptbc_id?>")'><font color=red>Del</font></a>
								<?php }?>
							</td>
						</tr>
					<?php }?>
				</table>
			</td>
			<td style="background-color: white;text-align:center">
				<?php if ($sptd_qty_order == ($sptd_qty_received + $sptd_qty_not_received)) {?>
					<span class="bubbletext" style="background:green;color:white">
				<?php } else {?>
					<span class="bubbletext" style="background:red;color:white">
				<?php }?>
				<?php echo $sptd_qty_order; ?>
				</span>
			</td>
			<td style="background-color: white;text-align:center"><?php echo $sptd_unit_name; ?></td>
			<!--td style="background-color: white;text-align:right"><?php echo '0 KG'; ?></td-->
			<td style="background-color: white;text-align:center">
				<?php if ($sptd_qty_received > 0) { ?>
					<span class="bubbletext"><?php echo $sptd_qty_received; ?></span>
				<?php }?>
			</td>
			<td style="background-color: white;text-align:center">
					<?php if ($sptd_qty_not_received > 0) { ?>
						<span class="bubbletext"><?php echo $sptd_qty_not_received; ?></span>
					<?php }?>
				</td>
			<td style="background-color: white;text-align:center">
				<?php if ($sptd_qty_shipment > 0) { ?>
					<span class="bubbletext"><?php echo $sptd_qty_shipment; ?></span>
				<?php }?>
			</td>
			<td style="background-color: white;text-align:center">
				<?php if ($sptd_qty_delivery > 0) { ?>
					<span class="bubbletext"><?php echo $sptd_qty_delivery; ?></span>
				<?php }?>
			</td>
			<td style="background-color: mintcream;text-align:center">
				<?php if ($sptd_qty_await > 0) { ?>
					<span class="bubbletext"><?php echo $sptd_qty_await; ?></span>
				<?php }?>
			</td>
			<?php if ($sptd_id != $packing_sptd_id) {?>
				<td style="background-color: white;text-align:center">
					<?php if ($sptd_qty_packing > 0) { ?>
						<span class="bubbletext" style="background:yellow; color:black"><?php echo $sptd_qty_packing; ?></span>
					<?php }?>
				</td>
				<td style="text-align:center;">
					<?php if ($sptd_qty_nogood > 0) { ?>
						<span class="bubbletext" style="background:red;color:white"><?php echo $sptd_qty_nogood; ?></span>
					<?php }?>
				</td>
				<td style="background-color: mintcream;text-align:right">
					<center>
				
						<?php if($can_editing) {?>
							<a href="javascript:void(0)" onclick='del_board_bc("<?php echo $sptd_id; ?>")'><font color="red">Del</font></a> | 
							<a href="#edit_board_bc<?php echo $sptd_id; ?>" role="button" data-toggle="modal">Edit</a>
						<?php }?>
					</center>
					<?php if($can_packing and $sptd_qty_await > 0) {?>
						<a href="sptdmnt.php?sptmnumber=<?php echo encrypt($sptm_nbr, $key)?>&packing_sptd_id=<?php echo $sptd_id?>&actiondetail=packing_bc#trsptd_<?php echo $sptd_id?>">Picking</a>
					<?php }?>
					
					
					
					<?php if($can_editing) {?>
						<div id="edit_board_bc<?php echo $sptd_id?>" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">																																																																				
							<form name="frm_edit_board_bc<?php echo $sptd_id?>" autocomplete=OFF method="post" action="../serverside/sptdmnt_bc_post.php" enctype="multipart/form-data">
								<input type="hidden" name="action" value="edit_board_bc">
								<input type="hidden" name="sptd_id" value="<?php echo $sptd_id;?>">
								<input type="hidden" name="sptm_nbr" value="<?php echo $sptm_nbr;?>">															
								<input type="hidden" name="pg" value="<?php echo $pg;?>">															
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
									<h3 id="myModalLabel">แก้ไขบอร์ดตกแต่ง  บอร์ดที่:: <?php echo $board_no?></h3>
								</div>
								<!--div class="modal-body"-->
								<div class="">
									<table border=1 class="table table-condensed table-responsive">	
										<tbody>																	
										<tr>
											<td style="text-align:right; vertical-align: middle;"><b>ชื่อบอร์ด:</b></td>
											<td colspan=5>
												<input name="sptd_remark" class="inputtext_s form-control" style="width:400px" value="<?php echo $sptd_remark;?>">
											</td>
										</tr>
										<tr>
											<td style="text-align:right; vertical-align: middle;"><b>จำนวน:</b></td>
											<td>
												<input type="text"  name="sptd_qty_order" value="<?php echo $sptd_qty_order;?>" class="inputtext_s" style="width: 50px;">	[บอร์ด]												
											</td>
										</tr>
										<tr>
											<td style="width:150px;text-align:right; vertical-align: middle;"><b>รูปภาพ (<font color=red>แบบบอร์ด</font>):</b></td>
											<td>
												<?php if ($sptd_s_filename!="") {?>
												<a href="javascript:void(0);" onclick='del_board_bc_image("<?php echo $sptd_id?>")'><font color=red>(Del)</font></a>&nbsp;
												<font color=blue><?php echo $sptd_o_filename?></font><hr>
												<?php }?>
												<input type="file"  name="sptd_file" class="inputtext_s" style="width: 450px;">													
											</td>
										</tr>
										</tbody>
									</table>					
								</div>
								<?php if ($can_editing) {?>
								<div class="modal-footer">
									<button type="submit" class="btn btn-success fileinput-button paddingleftandright10 margintop20 marginleft20" data-toggle="modal" onclick='board_bc_postform("<?php echo 'frm_edit_board_bc'.$sptd_id;?>")'>
										<i class="icon-check icon-white"></i>
										<span>Save</span>
									</button>											
								</div>		
								<?php }?>
							</form>
						</div>
					<?php } ?>
				</td>
			<?php }?>
			<?php if ($sptd_id == $packing_sptd_id and $actiondetail=="packing_bc") {?>
				<td style="text-align:right">
					<form name="frm_packing_board_bc" autocomplete=OFF method="post" action="../serverside/sptdmnt_bc_post.php">
						<input type="hidden" name="action" value="packing_board_bc">																		
						<input type="hidden" name="sptm_nbr" value="<?php echo $sptm_nbr;?>">
						<input type="hidden" name="sptd_id" value="<?php echo $sptd_id;?>">
						<input type="hidden" name="pg" value="<?php echo $pg;?>">
						<input type="text" name="sptd_qty_packing" value="<?php if ($sptd_qty_packing>0) {echo $sptd_qty_packing;} else {echo $sptd_qty_await;}?>" class="inputtext_s" style="width: 50px;text-align:center;font-size:12pt;font-weight: bold;color:red">
					</form>
				</td>
				<td style="text-align:center;">
					<?php if ($sptd_qty_nogood > 0) { ?>
						<span class="bubbletext" style="background:red;color:white"><?php echo $sptd_qty_nogood; ?></span>
					<?php }?>
				</td>
				<td style="text-align:center">												
					<div class="btn btn-mini btn-success" style="margin-top:0px; margin-bottom:10px; width: 40px;" onclick='packing_board_bc("<?php echo $sptd_id?>")'>
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

<div id="add_board_bc" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">																																																																				
	<form name="frm_add_board_bc" method="post" autocomplete="OFF" action="../serverside/sptdmnt_bc_post.php" enctype="multipart/form-data">
		<input type="hidden" name="action" value="add_board_bc">																		
		<input type="hidden" name="sptm_nbr" value="<?php echo $sptm_nbr;?>">
		<input type="hidden" name="pg" value="<?php echo $pg;?>">															
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			<h3 id="myModalLabel">เพิ่มบอร์ดตกแต่ง :: <?php echo $expm_nbr?></h3>
		</div>
		<!--div class="modal-body"-->
		<div class="">
			<table border=1 class="table table-condensed table-responsive">	
			<tbody>	
				<tr>
					<td style="text-align:right; vertical-align: middle;"><b>ชื่อบอร์ด:</b></td>
					<td colspan=5> 
						<input name="sptd_remark" class="inputtext_s form-control" style="width:400px" maxlength="60">
					</td>
				</tr>
				<tr>
					<td style="text-align:right; vertical-align: middle;"><b>จำนวน:</b></td>
					<td>
						<input type="text"  name="sptd_qty_order" class="inputtext_s" style="width: 50px;">	[บอร์ด]												
					</td>
				</tr>
				<tr>
					<td style="width:150px;text-align:right; vertical-align: middle;"><b>รูปภาพ (<font color=red>แบบบอร์ด</font>):</b></td>
					<td>
						<input type="file"  name="sptd_file" class="inputtext_s" style="width: 450px;">													
					</td>
				</tr>
			</tbody>
			</table>					
		</div>
		<?php if ($can_editing) {?>
		<div class="modal-footer">
			<button type="submit" class="btn btn-success fileinput-button paddingleftandright10 margintop20 marginleft20" data-toggle="modal" onclick='board_bc_postform("<?php echo "frm_add_board_bc";?>")'>
				<i class="icon-check icon-white"></i>
				<span>Save</span>
			</button>											
		</div>			
		<?php }?>
	</form>																																																			
</div>

<div id="add_bc_product" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">																																																																				
	<form name="frm_add_bc_product" autocomplete=OFF method="post" action="../serverside/sptdmnt_bc_post.php">
		<input type="hidden" name="action" value="add_board_bc_product">																		
		<input type="hidden" name="sptm_nbr" value="<?php echo $sptm_nbr;?>">
		<input type="hidden" name="sptd_id">

		<input type="hidden" name="pg" value="<?php echo $pg;?>">															
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			<h3 id="myModalLabel">เพิ่มกระเบื้องในบอร์ดตกแต่ง :: <?php echo $sptm_nbr?></h3>
		</div>
		<!--div class="modal-body"-->
		<div class="">
			<table border=1 class="table table-condensed">	
			<tbody>																	
				<tr>
					<td style="text-align:right;width:100px"><b>รหัสกระเบื้อง:</b></td>
					<td style="width:300pxวvertical-align: middle;"> 
						<input type="text" name="sptbc_mat_code" class="inputtext_s" style="width: 150px;" maxlength="30">
						<button type="button" class="btn btn-default" style="margin: auto;" 
						OnClick="helppopup('../_help/getproduct.php','frm_add_bc_product','sptbc_mat_code','sptbc_mat_name',document.frm_add_bc_product.sptbc_mat_code.value)">
							<span class="icon icon-search" aria-hidden="true"></span>
						</button>
						<input name="sptbc_mat_name"  disabled type="text" class="inputtext_s" style="margin:auto;width: 250px;">
					</td> 
				</tr>
				<tr>
					<td style="text-align:right; vertical-align: middle;"><b>หมายเหตุ:</b></td>
					<td> 
						<input type="text" name="sptbc_remark" class="inputtext_s" style="width: 450px;" maxlength="255">
					</td>
				</tr>
			</tbody>
			</table>					
		</div>
		<?php if ($can_editing) {?>
		<div class="modal-footer">
			<button type="submit" class="btn btn-success fileinput-button paddingleftandright10 margintop20 marginleft20" data-toggle="modal" onclick='board_bc_product_postform("<?php echo 'frm_add_bc_product';?>")'>
				<i class="icon-check icon-white"></i>
				<span>Save</span>
			</button>											
		</div>
		<?php }?>
	</form>																																																			
</div>
<div id="upload_board_bc_product" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">																																																																				
	<form id="frm_upload_board_bc_product" name="frm_upload_board_bc_product" autocomplete=OFF method="post" enctype="multipart/form-data">
		<input type="hidden" name="action" value="upload_board_bc_product">																																			
		<input type="hidden" name="sptm_nbr" value="<?php echo $sptm_nbr;?>">
		<input type="hidden" name="sptd_id">
		<input type="hidden" name="pg" value="<?php echo $pg;?>">															
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			<h3 id="myModalLabel">บอร์ดตกแต่ง :: นำรายการกระเบื้องเข้าจาก Excel File ::</h3>
		</div>
		<!--div class="modal-body"-->
		<div class="">
			<table border=1 class="table table-condensed table-responsive">	
			<tbody bgcolor=#f5f5ef>																	
				<tr>
					<td style="width:150px;text-align:right; vertical-align: middle;"><b>File Excel (รายการกระเบื้อง):</b></td>
					<td><input type="file"  name="fileupload_board_bc_product" class="inputtext_s" style="width: 450px;"></td>	 
				</tr>
			</tbody>
			</table>					
		</div>
		<?php if ($can_editing) {?>
		<div class="modal-footer">
			<button type="submit" class="btn btn-success fileinput-button paddingleftandright10 margintop20 marginleft20" data-toggle="modal" onclick="upload_board_bc_product_postform()">
				<i class="icon-check icon-white"></i>
				<span>Start Upload</span>
			</button>											
		</div>	
		<?php }?>
	</form>																																																			
</div>
<form name="frm_del_board_bc" method="post" action="../serverside/sptdmnt_bc_post.php">
	<input type="hidden" name="action" value="del_board_bc">	
	<input type="hidden" name="sptm_nbr" value="<?php echo $sptm_nbr;?>">		
	<input type="hidden" name="sptd_id">	
	<input type="hidden" name="pg" value="<?php echo $pg?>">
</form>
<form name="frm_del_board_bc_product" method="post" action="../serverside/sptdmnt_bc_post.php">
	<input type="hidden" name="action" value="del_board_bc_product">	
	<input type="hidden" name="sptm_nbr" value="<?php echo $sptm_nbr;?>">		
	<input type="hidden" name="sptd_id">	
	<input type="hidden" name="sptbc_id">
	<input type="hidden" name="pg" value="<?php echo $pg?>">
</form>
<form name="frm_del_board_bc_image" method="post" action="../serverside/sptdmnt_bc_post.php">
	<input type="hidden" name="action" value="del_board_bc_image">	
	<input type="hidden" name="sptm_nbr" value="<?php echo $sptm_nbr;?>">		
	<input type="hidden" name="sptd_id">	
	<input type="hidden" name="pg" value="<?php echo $pg?>">
</form>