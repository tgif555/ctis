<div>
<?php 
$actiondetail = mssql_escape($_REQUEST['actiondetail']);
$packing_sptd_id = mssql_escape($_REQUEST['packing_sptd_id']);
?>
<table width="100%" border="0" cellspacing="0" cellpadding="4" class="table table-bordered table-condensed">			
	<thead>
	<tr bgcolor="paleturquoise" style="font-weight: bold;">
		<td style="width:20px">ลำดับ</td>			
		<td style="width:100px;text-align:center;">รหัสบอร์ด</td>
		<td style="width:280px;text-align:center;">ชื่อบอร์ด</td>
		<td style="width:160px;text-align:center;">หมายเหตุ</td>
		<td style="width:80px;text-align:center;background-color:green;color:white">จำนวนที่สั่ง</td>
		<td style="width:80px;text-align:center;">หน่วย</td>	
		<!--td style="width:80px;text-align:center;">น้ำหนัก</td-->
		<td style="width:80px;text-align:center;background-color:green;color:white">รับของแล้ว</td>
		<td style="width:80px;text-align:center;background-color:red;color:white">ไม่รับ</td>
		<td style="width:80px;text-align:center;background-color:orange;color:black">ระหว่างส่ง</td>
		<td style="width:80px;text-align:center;background-color:yellow;color:black">พร้อมส่ง</td>
		<td style="width:80px;text-align:center;background-color:red;color:white">* ค้างส่ง *</td>
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
		" where sptd_sptm_nbr = '$sptm_nbr' and sptd_mat_group = 'BS'";
														
		$result = sqlsrv_query( $conn, $sql );											
		while($r_sptbs = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {	
			//$sptd_status= $r_sptbs['sptd_status'];
			$sptd_id = $r_sptbs['sptd_id'];
			$sptd_sptm_nbr = $r_sptbs['sptd_sptm_nbr'];
			$sptd_mat_code = $r_sptbs['sptd_mat_code'];
			$sptd_mat_name = html_quot($r_sptbs['mat_th_name']);
			
			$sptd_qty_order = $r_sptbs['sptd_qty_order'];												
			$sptd_unit_code = $r_sptbs['sptd_unit_code'];
			$sptd_unit_name = findsqlval("unit_mstr","unit_name","unit_code",$sptd_unit_code,$conn);
			$sptd_mat_group = $r_sptbs['sptd_mat_group'];
			//$sptd_seq = $r_sptbs['sptd_seq'];
			$sptd_remark = html_quot($r_sptbs['sptd_remark']);

			$stkm_qty_oh = findsqlval("stkm_mstr","stkm_qty_oh","stkm_mat_code",$sptd_mat_code,$conn);
			if ($stkm_qty_oh == "") {$stkm_qty_oh = 0;}
		
			$sptd_qty_received = $r_sptbs['sptd_qty_received'];
			$sptd_qty_not_received = $r_sptd['sptd_qty_not_received'];
			$sptd_qty_shipment = $r_sptbs['sptd_qty_shipment'];	
			$sptd_qty_delivery = $r_sptbs['sptd_qty_delivery'];
			$sptd_qty_packing = $r_sptbs['sptd_qty_packing'];
			$sptd_qty_nogood = $r_sptbs['sptd_qty_nogood'];
			
			$sptd_qty_await = ($sptd_qty_order - $sptd_qty_received - $sptd_qty_not_received - $sptd_qty_shipment - $sptd_qty_delivery);
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
			//Reset foc text when document is close&rejec&cancel
			if (inlist("880,890,990",$sptm_step_code)) {
				$focd_focm_status_text = "";
				$focd_focm_title_text = "";
			}
			$sptd_gross_weight = $sptd_qty_order * $r_sptbs['mat_gross_weight'];
			$n++;																			
			?>
			<tr>
				<td style="background-color: aliceblue;text-align:center;"><?=$n?></td>
				<td style="background-color: aliceblue"><?php echo $sptd_mat_code; ?></td>
				<td style="background-color: aliceblue"><?php echo $sptd_mat_name; ?></td>
				<td style="max-width:160px;overflow:hidden;background-color: aliceblue" title="<?php echo $sptd_remark?>"><?php echo $sptd_remark; ?></td>
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
				<!--td style="text-align:center"><?php echo $sptd_gross_weight . ' KG'; ?></td-->
				<td style="text-align:center">
					<?php if ($sptd_qty_received > 0) { ?>
						<span class="bubbletext"><?php echo $sptd_qty_received; ?></span>
					<?php }?>
				</td>
				<td style="text-align:center;">
					<?php if ($sptd_qty_not_received > 0) { ?>
						<span class="bubbletext"><?php echo $sptd_qty_not_received; ?></span>
					<?php }?>
				</td>
				<td style="text-align:center">
					<?php if ($sptd_qty_shipment > 0) { ?>
						<span class="bubbletext"><?php echo $sptd_qty_shipment; ?></span>
					<?php }?>
				</td>
				<td style="text-align:center">
					<?php if ($sptd_qty_delivery > 0) { ?>
						<span class="bubbletext"><?php echo $sptd_qty_delivery; ?></span>
					<?php }?>
				</td>
				<td style="background-color: aliceblue;text-align:center">
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
						<?php if ($sptd_qty_await > 0 and !inlist("880,890,990",$sptm_step_code)) {?>
						<span class="bubbletext" 
							<?php if ($sptd_qty_await > $stkm_qty_oh) { 
								echo "style='background:red;color:white'";
							} else {
								echo "style='background:white'";}
							?>><?php echo $stkm_qty_oh; ?>
						</span>
						<?php }?>
					</td>
					<td style='text-align:center' title="<?php echo $focd_focm_title_text?>"><?php echo $focd_focm_status_text?></td>
					<td style="text-align:center;">
						<?php if ($sptd_qty_nogood > 0) { ?>
							<span class="bubbletext" style="background:red;color:white"><?php echo $sptd_qty_nogood; ?></span>
						<?php }?>
					</td>
					<td style="background-color: aliceblue;text-align:right">
						<center>
							<?php if($can_editing) {?>
								<a href="javascript:void(0)" onclick='del_board_bs("<?php echo $sptd_id; ?>")'><font color="red">Del</font></a> | 
								<a href="#edit_board_bs<?php echo $sptd_id; ?>" role="button" data-toggle="modal">Edit</a>
							<?php }?>
						</center>
						<?php if($can_packing and $sptd_qty_await > 0) {?>
							<a href="sptdmnt.php?sptmnumber=<?php echo encrypt($sptm_nbr, $key)?>&packing_sptd_id=<?php echo $sptd_id?>&actiondetail=packing_bs#bs_section">Packing</a>
						<?php }?>
						
						<?php if($can_editing) {?>
							<div id="edit_board_bs<?php echo $sptd_id; ?>" class="modal hide fade" tabindex="1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">																																																																				
								<form name="frm_edit_board_bs<?php echo $sptd_id;?>" autocomplete=OFF method="post" action="../serverside/sptdmnt_bs_post.php">
									<input type="hidden" name="action" value="edit_board_bs">					
									<input type="hidden" name="sptm_nbr" value="<?php echo $sptm_nbr;?>">
									<input type="hidden" name="sptd_id" value="<?php echo $sptd_id;?>">																
									<input type="hidden" name="pg" value="<?php echo $pg;?>">
																						
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
										<h3 id="myModalLabel">แก้ไขบอร์ดมาตรฐาน :: <?php echo $sptm_nbr?></h3>
									</div>
									<!--div class="modal-body"-->
									<div class="">
										<table class="table table-c	ondensed table-responsive">	
											<tbody bgcolor=#f5f5ef>																																																																				
											<tr>
												<td style="text-align:right;width:100px;vertical-align: middle;"><b>รหัสบอร์ด:</b></td>
												<td> 
													<input type="text" name="sptd_mat_code" value="<?php echo $sptd_mat_code?>" class="inputtext_s" style="width: 150px;" maxlength="30">
													<button type="button" class="btn btn-default" style="margin: auto;" 
														OnClick="helppopup('../_help/getboardstd.php','frm_edit_board_bs<?php echo $sptd_id?>','sptd_mat_code','sptd_mat_name',document.frm_edit_board_bs<?php echo $sptd_id?>.sptd_mat_code.value)">
														<span class="icon icon-search" aria-hidden="true"></span>
													</button>
													<textarea name="sptd_mat_name" rows=2 disabled style="margin:auto;width: 450px;"><?php echo $sptd_mat_name?></textarea>
												</td> 
											</tr>
											<tr>
												<td style="text-align:right; vertical-align: middle;"><b>จำนวน:</b></td>
												<td style="width:150px">
													<input type="text"  name="sptd_qty_order" value="<?php echo $sptd_qty_order?>" class="inputtext_s" style="width: 50px;">
													<select name="sptd_unit_code" class="inputtext_s" style="margin:auto;width: 60px;color:red" >
														<?php 
														$sql_unit = "SELECT unit_code,unit_name FROM unit_mstr WHERE unit_active = 1 and unit_code = 'B' order by unit_seq";
														$result_unit_list = sqlsrv_query( $conn,$sql_unit);																													
														while($r_unit_list=sqlsrv_fetch_array($result_unit_list, SQLSRV_FETCH_ASSOC)) {
														?>
															<option  style="color:black" value="<?php echo $r_unit_list['unit_code'];?>"
															<?php if ($sptd_unit_code == $r_unit_list['unit_code']) { echo 'selected';}?>><?php echo $r_unit_list['unit_name'];?></option> 
														<?php } ?>
													</select>															
												</td>
											</tr>												
											<tr>
												<td style="text-align:right; vertical-align: middle;"><b>หมายเหยุ:</b></td>
												<td> 
													<input type="text" name="sptd_remark" value="<?php echo $sptd_remark?>" class="inputtext_s" style="width: 450px;" maxlength="255">
												</td>
											</tr>											
											</tbody>
										</table>					
									</div>
									<div class="modal-footer">
										<button type="submit" class="btn btn-success fileinput-button paddingleftandright10 margintop20 marginleft20" data-toggle="modal" onclick='board_bs_postform("<?php echo 'frm_edit_board_bs'.$sptd_id;?>")'>
											<i class="icon-check icon-white"></i>
											<span>Save</span>
										</button>											
									</div>												
								</form>																																																			
							</div>
						<?php }?>
					</td>
				<?php }?>
				<?php if ($sptd_id == $packing_sptd_id and $actiondetail=="packing_bs") {?>
					<td style="text-align:right">
						<form name="frm_packing_board_bs" autocomplete=OFF method="post" action="../serverside/sptdmnt_bs_post.php">
							<input type="hidden" name="action" value="packing_board_bs">																		
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
					<td style='text-align:center' title="<?php echo $focd_focm_title_text?>"><?php echo $focd_focm_status_text?></td>
					<td style="text-align:center;">
						<?php if ($sptd_qty_nogood > 0) { ?>
							<span class="bubbletext"><?php echo $sptd_qty_nogood; ?></span>
						<?php }?>
					</td>
					<td style="text-align:center">												
						<div class="btn btn-mini btn-success" style="margin-top:0px; margin-bottom:10px; width: 40px;" onclick='packing_board_bs("<?php echo $sptd_id?>")'>
							<i class="icon-white icon-ok"></i>
							<span>Save</span>
						</div>																	
						<div class="btn btn-mini btn-danger" style="margin-top:0px; margin-bottom:10px; width: 40px;" onclick="window.location.href='sptdmnt.php?sptmnumber=<?php echo encrypt($sptm_nbr, $key)?>&activeid=<?php echo encrypt($sptd_id,$key)?>#bs_section'">
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

<div id="add_board_bs" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<form name="frm_add_board_bs" autocomplete=OFF method="post" action="../serverside/sptdmnt_bs_post.php">	
		<input type="hidden" name="action" value="add_board_bs">
		<input type="hidden" name="sptd_mat_group" value="BS">
		<input type="hidden" name="sptd_unit_code" value="B">
		<input type="hidden" name="sptm_nbr" value="<?php echo $sptm_nbr;?>">										
		<input type="hidden" name="pg" value="<?php echo $pg;?>">
																	
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			<h3 id="myModalLabel">เพิ่มบอร์ดมาตรฐาน:: <?php echo $sptm_nbr?></h3>
		</div>
		<!--div class="modal-body"-->
		<div class="">
			<table class="table table-c	ondensed table-responsive">	
				<tbody bgcolor=#f5f5ef>																																																																				
				<tr>
					<td style="text-align:right;width:100px;vertical-align: middle;"><b>รหัสบอร์ด:</b></td>
					<td> 
						<input type="text" name="sptd_mat_code" class="inputtext_s" style="width: 150px;" maxlength="30">
						<button type="button" class="btn btn-default" style="margin: auto;" 
							OnClick="helppopup('../_help/getboardstd.php','frm_add_board_bs','sptd_mat_code','sptd_mat_name',document.frm_add_board_bs.sptd_mat_code.value)">
							<span class="icon icon-search" aria-hidden="true"></span>
						</button>
						
					</td> 
				</tr>
				<tr>
					<td style="text-align:right;width:100px;vertical-align: middle;"><b>ชื่อบอร์ด:</b></td>
					<td><textarea name="sptd_mat_name" rows=2 disabled style="margin:auto;width: 450px;"></textarea></td>
				</tr>
				<tr>
					<td style="text-align:right; vertical-align: middle;"><b>จำนวน:</b></td>
					<td style="width:150px">
						<input type="text"  name="sptd_qty_order" class="inputtext_s" style="width: 50px;"> 
						<select name="sptd_unit_code" class="inputtext_s" style="margin:auto;width: 100px;color:red" >
							<?php 
							$sql_unit = "SELECT unit_code,unit_name FROM unit_mstr WHERE unit_active = 1 and unit_code = 'B' order by unit_seq";
							$result_unit_list = sqlsrv_query( $conn,$sql_unit);																													
							while($r_unit_list=sqlsrv_fetch_array($result_unit_list, SQLSRV_FETCH_ASSOC)) {
							?>
								<option  style="color:black" value="<?php echo $r_unit_list['unit_code'];?>"><?php echo html_quot($r_unit_list['unit_name']);?></option> 
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
			<button type="submit" class="btn btn-success fileinput-button paddingleftandright10 margintop20 marginleft20" data-toggle="modal" onclick='board_bs_postform("<?php echo 'frm_add_board_bs';?>")'>
				<i class="icon-check icon-white"></i>
				<span>Save</span>
			</button>											
		</div>
		<?php }?>		
	</form>																																															
</div>

<form name="frm_del_board_bs" method="post" action="../serverside/sptdmnt_bs_post.php">
	<input type="hidden" name="action" value="del_board_bs">	
	<input type="hidden" name="sptm_nbr" value="<?php echo $sptm_nbr;?>">		
	<input type="hidden" name="sptd_id">	
	<input type="hidden" name="pg" value="<?php echo $pg?>">
</form>