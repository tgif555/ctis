<div class="table-responsive">
	<table class="table-sm table-bordered">		
		<thead>
		<tr>
			<th colspan=5></th>
			<th colspan=4 style="text-align:center;background:red;color:white">ราคาลูกค้า</td>
			<th colspan=4 style="text-align:center;background:yellow;color:black">ราคากลางผู้รับเหมา</td>
			<th colspan=2 style="text-align:center;background:#80ff80;color:black">Contractor: <?php echo $qtm_tem_name?></td>
			<th colspan=3 class="text-right">
				<?php if ($can_editing) {?>
				<a href="#div_add_qtd_product" data-toggle="modal">
					<div class="btn btn-sm white" style="background:red">	
						<i class="feather icon-file-plus mr-25"></i><span>เพิ่มรายการสินค้า</span>
					</div>
				</a>
				<?php }?>
			</th>
		</tr>
		<tr bgcolor="paleturquoise">
			<th style="width:20px">ลำดับ</th>			
			<th style="width:100px;text-align:center;">รหัสสินค้า</th>
			<th style="width:250px;text-align:center;">ชื่อสินค้า</th>
			<th style="width:80px;text-align:center;">จำนวน</th>
			<th style="width:80px;text-align:center;">หน่วย</th>
			<th style="width:80px;text-align:center;background:red;color:white">ราคา</th>
			<th style="width:50px;text-align:center;background:red;color:white">ส่วนลด</th>
			<th style="width:80px;text-align:center;background-color: lightcyan">ต่อหน่วย</th>
			<th style="width:80px;text-align:center;background:red;color:white">สุทธิ</th>
			<th style="width:80px;text-align:center;background:yellow;color:black">ราคา</th>
			<th style="width:50px;text-align:center;background:yellow;color:black">ส่วนลด</th>
			<th style="width:80px;text-align:center;background-color: lightcyan">ต่อหน่วย</th>
			<th style="width:80px;text-align:center;background:yellow;color:black">สุทธิ</th>
			<th style="width:80px;text-align:center;background-color: lightcyan">ต่อหน่วย</th>
			<th style="width:80px;text-align:center;background:#80ff80;color:black">สุทธิ</th>
			<th style="width:80px;text-align:center;background-color:green;color:white">กำไรสุทธิ</th>
			<th style="width:140px;text-align:center;">Action</th>
			<th style="text-align:center;"></th>
		</tr>
		</thead>									
		<?php
		$n = 0;
		$qtd_customer_price_total = 0;
		$qtd_customer_disc_amt_total = 0;
		$qtd_customer_unit_amt_total = 0;
		$qtd_customer_amt_total = 0;
		$qtd_contractor_price_total = 0;
		$qtd_contractor_disc_amt_total = 0;
		$qtd_contractor_unit_amt_total = 0;
		$qtd_contractor_amt_total = 0;
		$qtd_contractor_auction_amt_total = 0;
		$qtd_margin_amt_total = 0;
		if ($qtm_tem_name == "") {
			$qtm_tem_name = "NOT Contractor";
		}
			
		$params = array($qtm_nbr);
		$sql_qtd = "SELECT * FROM qtd_det" .
			" INNER JOIN mat_mstr ON mat_code = qtd_mat_code" .
			" INNER JOIN unit_mstr ON unit_code = qtd_unit_code" .
			" where qtd_qtm_nbr = ?";													
		$result_qtd = sqlsrv_query( $conn, $sql_qtd,$params );											
		while($rec_qtd = sqlsrv_fetch_array($result_qtd, SQLSRV_FETCH_ASSOC)) {	
			$qtd_id = html_escape($rec_qtd['qtd_id']);
			$qtd_qtm_nbr = html_escape($rec_qtd['qtd_qtm_nbr']);
			$qtd_mat_code = html_escape($rec_qtd['qtd_mat_code']);
			$qtd_mat_name = html_escape($rec_qtd['qtd_mat_name']);
			$qtd_qty = html_escape($rec_qtd['qtd_qty']);
			$qtd_unit_code = html_escape($rec_qtd['qtd_unit_code']);
			$qtd_unit_name = html_escape($rec_qtd['unit_name']);
			$qtd_customer_price = html_escape($rec_qtd['qtd_customer_price']);
			$qtd_customer_disc = html_escape($rec_qtd['qtd_customer_disc']);
			$qtd_customer_disc_unit = html_escape($rec_qtd['qtd_customer_disc_unit']);
			$qtd_contractor_price = html_escape($rec_qtd['qtd_contractor_price']);
			$qtd_contractor_disc = html_escape($rec_qtd['qtd_contractor_disc']);
			$qtd_contractor_disc_unit = html_escape($rec_qtd['qtd_contractor_disc_unit']);
			$qtd_remark = html_escape($rec_qtd['qtd_remark']);
			$qtd_contractor_auction_unit_amt = $rec_qtd['qtd_contractor_auction_unit_amt'];
			
			//Customer Cal
			$qtd_customer_amt = $qtd_customer_price;
			$qtd_customer_unit_amt = 0;
			$qtd_customer_disc_amt = 0;
			if ((double)$qtd_customer_disc > 0) {
				if ($qtd_customer_disc_unit == "P") {
					$qtd_customer_disc_amt = $qtd_customer_price * $qtd_customer_disc /100;
					$qtd_customer_unit_amt = $qtd_customer_price - $qtd_customer_disc_amt;
				}
				if ($qtd_customer_disc_unit == "B") {
					$qtd_customer_disc_amt = $qtd_customer_disc;
					$qtd_customer_unit_amt = $qtd_customer_amt - $qtd_customer_disc;
				}
			}
			else {
				$qtd_customer_unit_amt = $qtd_customer_amt;	
			}
			
			$qtd_customer_text_disc = "";
			if ((double)$qtd_customer_disc > 0) {
				if ($qtd_customer_disc_unit == "P") {
					$qtd_customer_text_disc = "<u>".number_fmt($qtd_customer_disc,2)."%"."</u><br><font color=red>".number_fmt($qtd_customer_disc_amt,2,",")."฿"."</font>";
				}
				if ($qtd_customer_disc_unit == "B") {
					$qtd_customer_text_disc = "<font color=red>".number_fmt($qtd_customer_disc)."฿"."</font>";
				}
			}
			$qtd_customer_amt = $qtd_qty * $qtd_customer_unit_amt;
			
			//Contractor Cal
			$qtd_contractor_amt = $qtd_contractor_price;
			$qtd_contractor_unit_amt = 0;
			$qtd_contractor_disc_amt = 0;
			if ((double)$qtd_contractor_disc > 0) {
				if ($qtd_contractor_disc_unit == "P") {
					$qtd_contractor_disc_amt = $qtd_contractor_price * $qtd_contractor_disc /100;
					$qtd_contractor_unit_amt = $qtd_contractor_price - $qtd_contractor_disc_amt;
				}
				if ($qtd_contractor_disc_unit == "B") {
					$qtd_contractor_disc_amt = $qtd_contractor_disc;
					$qtd_contractor_unit_amt = $qtd_contractor_amt - $qtd_contractor_disc;
				}
			}
			else {
				$qtd_contractor_unit_amt = $qtd_contractor_amt;	
			}
			
			$qtd_contractor_text_disc = "";
			if ((double)$qtd_contractor_disc > 0) {
				if ($qtd_contractor_disc_unit == "P") {
					$qtd_contractor_text_disc = "<u>".number_fmt($qtd_contractor_disc,2)."%"."</u><br><font color=red>".number_fmt($qtd_contractor_disc_amt,2,",")."฿"."</font>";
				}
				if ($qtd_contractor_disc_unit == "B") {
					$qtd_contractor_text_disc = "<font color=red>".number_fmt($qtd_contractor_disc)."฿</font>";
				}
			}
		
			$qtd_contractor_amt = $qtd_qty * $qtd_contractor_unit_amt;
			$qtd_contractor_auction_amt = $qtd_qty * $qtd_contractor_auction_unit_amt;
			
			$qtd_margin_amt = $qtd_customer_amt - $qtd_contractor_auction_amt;
			
			$qtd_customer_price_total = $qtd_customer_price_total + $qtd_customer_price;
			$qtd_customer_disc_amt_total = $qtd_customer_disc_amt_total + $qtd_customer_disc_amt;
			$qtd_customer_unit_amt_total = $qtd_customer_unit_amt_total + $qtd_customer_unit_amt;
			$qtd_customer_amt_total = $qtd_customer_amt_total + $qtd_customer_amt;
			
			$qtd_contractor_price_total = $qtd_contractor_price_total + $qtd_contractor_price;
			$qtd_contractor_disc_amt_total = $qtd_contractor_disc_amt_total + $qtd_contractor_disc_amt;
			$qtd_contractor_unit_amt_total = $qtd_contractor_unit_amt_total + $qtd_contractor_unit_amt;
			$qtd_contractor_amt_total = $qtd_contractor_amt_total + $qtd_contractor_amt;
			$qtd_contractor_auction_amt_total = $qtd_contractor_auction_amt_total + $qtd_contractor_auction_amt;
			$qtd_margin_amt_total = $qtd_margin_amt_total + $qtd_margin_amt;
			
			if (!$gbv_editprice) {
				if ($qtd_mat_code == "DUMMY") {
					$editprice_flag = "";
				}
				else {
					$editprice_flag = "readonly";
				}
			}
			else {
				$editprice_flag = "";
			}
			$n++;																			
			?>
			<tr id="trqtd_<?php echo $qtd_id?>">
				<td style="background-color: lightcyan;text-align:center;"><?=$n?></td>                                            
				<td style="background-color: lightcyan"><?php echo $qtd_mat_code; ?></td>
				<td style="background-color: lightcyan"><?php echo $qtd_mat_name; ?></td>
				<td style="text-align:right;background-color: lightcyan"><?php echo number_fmt($qtd_qty); ?></td>
				<td style="background-color: lightcyan"><?php echo $qtd_unit_name; ?></td>
				<td style="text-align:right"><?php echo number_fmt($qtd_customer_price); ?></td>
				<td style="text-align:right"><?php echo $qtd_customer_text_disc; ?></td>
				<td style="text-align:right;background-color: lightcyan"><?php echo number_fmt($qtd_customer_unit_amt); ?></td>
				<td style="text-align:right;background:red;color:white"><?php echo number_fmt($qtd_customer_amt); ?></td>
				<td style="text-align:right"><?php echo number_fmt($qtd_contractor_price); ?></td>
				<td style="text-align:right"><?php echo $qtd_contractor_text_disc; ?></td>
				<td style="text-align:right;background-color: lightcyan"><?php echo number_fmt($qtd_contractor_unit_amt); ?></td>
				<td style="text-align:right;background-color: yellow;color:black"><?php echo number_fmt($qtd_contractor_amt); ?></td>
				<td style="text-align:right;background-color: lightcyan"><?php echo number_fmt($qtd_contractor_auction_unit_amt); ?></td>
				<td style="text-align:right;background:#80ff80;color:black"><?php echo number_fmt($qtd_contractor_auction_amt); ?></td>
				<td style="text-align:right;background-color:green;color:white"><?php echo number_fmt($qtd_margin_amt); ?></td>
				<td style="background-color: lightcyan;text-align:right">
					<?php if ($can_editing) {?>
					<center>
						<?php if($can_editing) {?>
							<!--a href="javascript:void(0)" onclick='del_qtd_product("<?php echo $qtd_id;?>")'><font color="red">Del</font></a> | 
							<a href="#div_edit_qtd_product<?php echo $qtd_id; ?>" role="button" data-toggle="modal">Edit</a-->	
							<button type="button" class="btn btn-sm btn-default" onclick="del_qtd_product('<?php echo $qtd_id;?>')">
								Del
							</button>
							<button type="button" class="btn btn-sm btn-default" data-toggle="modal" data-target="#div_edit_qtd_product<?php echo $qtd_id; ?>">
								Edit
							</button>
						<?php }?>
					</center>
					<?php }?>
					<?php if($can_editing) {?>
						<div class="modal fade text-left" id="div_edit_qtd_product<?php echo $qtd_id;?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel9" aria-hidden="true">
							<div class="modal-dialog" role="document">
								<div class="modal-content p-0">
									<div class="modal-header bg-success white">
										<h4 class="modal-title" id="myModalLabel9"><i class="feather icon-shopping-cart"></i> แก้ไขสินค้า</h4>
										<button type="button" class="close" data-dismiss="modal" aria-label="Close">
											<span aria-hidden="true">&times;</span>
										</button>
									</div>
									
									<div class="modal-body">
										<div class="tab-pane active" id="account" aria-labelledby="account-tab" role="tabpanel">					
											<form id="frm_edit_qtd_product<?php echo $qtd_id;?>" name="frm_edit_qtd_product<?php echo $qtd_id;?>" autocomplete=OFF>
												<input type="hidden" name="action" value="edit_qtd_product">	
												<input type="hidden" name="csrf_securecode" value="<?php echo $csrf_securecode?>">
												<input type="hidden" name="csrf_token" value="<?php echo md5($csrf_token)?>">
												<input type="hidden" name="qtm_nbr" value="<?php echo $qtm_nbr;?>">
												<input type="hidden" name="qtd_id" value="<?php echo $qtd_id;?>">																
												<input type="hidden" name="pg" value="<?php echo $pg;?>">
											
												<div class="row">
													<div class="col-12 col-sm-12 small">
														<div class="form-group">
															<div class="controls">
																<div class="form-group">
																	<label class="font-weight-bold">รหัสสินค้า:</label>
																	<div class="input-group input-group-sm">
																		<input type="text" name="qtd_mat_code" value="<?php echo $qtd_mat_code?>" readonly maxlength="30" class="form-control form-control-sm">
																		<div class="input-group-append">
																			<span class="input-group-text"
																				OnClick="helppopup_mat('../_help/getproduct_qtd.php','frm_edit_qtd_product<?php echo $qtd_id;?>','<?php echo $qtd_id;?>','<?php echo $gbv_editprice?>','')">
																				<i class="feather icon-search"></i>
																			</span>
																		</div>
																	</div>
																</div>
															</div>
														</div>
														<div class="form-group">
															<div class="controls">
																<label class="font-weight-bold">ชือสินค้า:</label>
																<input type="text" name="qtd_mat_name" id="qtd_mat_name<?php echo $qtd_id;?>" value="<?php echo $qtd_mat_name?>" <?php echo $editprice_flag?> maxlength="150" class="form-control form-control-sm">
															</div>
														</div>
														<div class="form-group">
															<div class="controls">
																<div class="form-group">
																	<label class="font-weight-bold">จำนวน:</label>
																	<div class="input-group input-group-sm">
																		<input type="text"  name="qtd_qty" value="<?php echo $qtd_qty?>" class="form-control">
																		<div class="input-group-append">
																			<select name="qtd_unit_code" class="form-control form-control-sm" style="color:red" >
																				<option value="">หน่วย</option>
																				<?php 
																				$sql_unit = "SELECT unit_code,unit_name FROM unit_mstr order by unit_seq";
																				$result_unit_list = sqlsrv_query( $conn,$sql_unit);																													
																				while($r_unit_list=sqlsrv_fetch_array($result_unit_list, SQLSRV_FETCH_ASSOC)) {
																				?>
																					<option  style="color:black" value="<?php echo $r_unit_list['unit_code'];?>"
																						<?php if ($qtd_unit_code == $r_unit_list['unit_code']) {echo "selected";}?>> 
																						<?php echo $r_unit_list['unit_name'];?>
																					</option> 
																				<?php } ?>
																			</select>															
																		</div>
																	</div>
																</div>
															</div>
														</div>
														<div class="col-12 col-lg-12">
															<div class="row">
																<div class="col-lg-6">
																	<div class="form-group">
																		<fieldset>
																		<center><label class="font-weight-bold text-rught">Price for Customer</label></center>
																		<div class="row">
																			<div class="col-lg-6">
																				<div class="controls">
																					<label class="font-weight-bold">ราคา/หน่วย:</label>
																					<input name="qtd_customer_price" id="qtd_customer_price<?php echo $qtd_id;?>" value="<?php echo $qtd_customer_price?>" <?php echo $editprice_flag?> class="form-control form-control-sm">
																				</div>
																			</div>
																			<div class="col-lg-6">
																				<div class="controls">
																					<div class="form-group">
																						<label class="font-weight-bold">ส่วนลด:</label>
																						<div class="input-group input-group-sm">
																							<input name="qtd_customer_disc" value="<?php echo $qtd_customer_disc?>" class="form-control">
																							<div class="input-group-append">
																								<select name="qtd_customer_disc_unit" class="form-control form-control-sm red">
																									<option value="">หน่วย</option>
																									<option value="P" <?php if ($qtd_customer_disc_unit == "P") {echo "selected";}?>>%</option>
																									<option value="B" <?php if ($qtd_customer_disc_unit == "B") {echo "selected";}?>>บาท</option>
																								</select>															
																							</div>
																						</div>
																					</div>
																				</div>
																			</div>
																		</div>
																		</fieldset>
																	</div>
																</div>
																<div class="col-lg-6">
																	<div class="form-group">
																		<fieldset>
																		<center><label class="font-weight-bold text-rught">Price for Contractor</label></center>
																		<div class="row">
																			<div class="col-lg-6">
																				<div class="controls">
																					<label class="font-weight-bold">ราคา/หน่วย:</label>
																					<input name="qtd_contractor_price" id="qtd_contractor_price<?php echo $qtd_id;?>" value="<?php echo $qtd_contractor_price;?>" <?php echo $editprice_flag?> class="form-control form-control-sm">
																				</div>
																			</div>
																			<div class="col-lg-6">
																				<div class="controls">
																					<div class="form-group">
																						<label class="font-weight-bold">ส่วนลด:</label>
																						<div class="input-group input-group-sm">
																							<input name="qtd_contractor_disc" value="<?php echo $qtd_contractor_disc;?>" class="form-control form-control-sm">
																							<div class="input-group-append">
																								<select name="qtd_contractor_disc_unit" class="form-control form-control-sm red">
																									<option value="">หน่วย</option>
																									<option value="P" <?php if ($qtd_contractor_disc_unit == "P") {echo "selected";}?>>%</option>
																									<option value="B" <?php if ($qtd_contractor_disc_unit == "B") {echo "selected";}?>>บาท</option>
																								</select>															
																							</div>
																						</div>
																					</div>
																				</div>
																			</div>
																		</div>
																		</fieldset>
																	</div>
																</div>	
															</div>
														</div>
														<div class="form-group">
															<div class="controls">
																<label class="font-weight-bold">Remarks:</label>
																<input type="text" name="qtd_remark" value="<?php echo $qtd_remark;?>" class="form-control form-control-sm" maxlength="255">
															</div>
														</div>
													</div>
												</div>
											</form>	
										</div>
									</div>
									<div class="modal-footer">
										<button type="button" class="btn btn-sm grey btn-outline-secondary" data-dismiss="modal">Close</button>
										<button type="button" class="btn btn-sm btn-outline-success" onclick='qtd_product_postform("<?php echo 'frm_edit_qtd_product'.$qtd_id;?>")'>Save changes</button>				
									</div>
								</div>
								<!-- /.modal-content -->
							</div>
						</div>
					<?php }?>
				</td>
				<td></td>
			</tr>
		<?php }?>
		<?php
		if ((double)$qtm_disc > 0) {
			if ($qtm_disc_unit == "P") {
				$qtm_disc_amt = $qtd_customer_amt_total * $qtm_disc /100;
			}
			if ($qtm_disc_unit == "B") {
				$qtm_disc_amt = $qtm_disc;
			}
		}
		else {
			$qtm_disc_amt = 0;	
		}
		?>
		<tr>
			<td colspan=5 style="text-align:right"><b>Total: </b></td>
			<td style="text-align:right"></td>
			<td style="text-align:right;color:red"></td>
			<td style="text-align:right;background-color:lightcyan"></td>
			<td style="text-align:right;color:black"><?php echo number_fmt($qtd_customer_amt_total);?></td>
			<td style="text-align:right"></td>
			<td style="text-align:right;color:red"></td>
			<td style="text-align:right;background-color:lightcyan"></td>
			<td style="text-align:right;color:black"><?php echo number_fmt($qtd_contractor_amt_total);?></td>
			<td style="text-align:right;background-color: lightcyan;color:white"></td>
			<td style="text-align:right;color:black"><?php echo number_fmt($qtd_contractor_auction_amt_total);?></td>
			<td style="text-align:right;color:black"><?php echo number_fmt($qtd_margin_amt_total);?></td>
			<td colspan=2></td>
		</tr>
		<tr>
			<td colspan=8 style="text-align:right;color:red"><b>Site Servey: </b></td>
			<td style="text-align:right;color:red"><?php echo number_fmt($qtm_prepaid_amt);?></td>
			<td colspan=9></td>
		</tr>
		<tr>
			<td colspan=8 style="text-align:right;color:red"><b>Quotation Discount: <span style='color:red'><?php echo number_fmt($qtm_disc)." ".$qtm_disc_unit_name?></span></b></td>
			<td style="text-align:right;color:red"><?php echo number_fmt($qtm_disc_amt);?></td>
			<td colspan=9></td>
		</tr>
		<tr>
			<td colspan=8 style="text-align:right"><b>Total Price: </b></td>
			<td style="text-align:right"><?php echo number_fmt($qtd_customer_amt_total - $qtm_disc_amt - $qtm_prepaid_amt);?></td>	
			<td colspan=9></td>
		</tr>
	</table>
</div>

<!--ADD Material Modal-->

<div class="modal fade text-left" id="div_add_qtd_product" tabindex="-1" role="dialog" aria-labelledby="myModalLabel9" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content p-0">
			<div class="modal-header bg-success white">
				<h4 class="modal-title" id="myModalLabel9"><i class="feather icon-shopping-cart"></i> เพิ่มสินค้า</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			
			<div class="modal-body">
				
				<div class="tab-pane active" id="account" aria-labelledby="account-tab" role="tabpanel">					
					<form id="frm_add_qtd_product" name="frm_add_qtd_product" autocomplete=OFF>	
						<input type="hidden" name="action" value="add_qtd_product">
						<input type="hidden" name="csrf_securecode" value="<?php echo $csrf_securecode?>">
						<input type="hidden" name="csrf_token" value="<?php echo md5($csrf_token)?>">		
						<input type="hidden" name="qtm_nbr" value="<?php echo $qtm_nbr;?>">
						<input type="hidden" name="pg" value="<?php echo $pg;?>">
						<div class="row">
							<div class="col-12 col-sm-12 small">
								<div class="form-group">
									<div class="controls">
										<div class="form-group">
											<label class="font-weight-bold">รหัสสินค้า:</label>
											<div class="input-group input-group-sm">
												<input type="text" name="qtd_mat_code" readonly maxlength="30" class="form-control form-control-sm">
												<div class="input-group-append">
													<span class="input-group-text"
														OnClick="helppopup_mat('../_help/getproduct_qtd.php','frm_add_qtd_product','','<?php echo $gbv_editprice?>','')">
														<i class="feather icon-search"></i>
													</span>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="form-group">
									<div class="controls">
										<label class="font-weight-bold">ชือสินค้า:</label>
										<input type="text" name="qtd_mat_name" id="qtd_mat_name" readonly maxlength="150" class="form-control form-control-sm">
									</div>
								</div>
								<div class="form-group">
									<div class="controls">
										<div class="form-group">
											<label class="font-weight-bold">จำนวน:</label>
											<div class="input-group input-group-sm">
												<input type="text"  name="qtd_qty" class="form-control">
												<div class="input-group-append">
													<select name="qtd_unit_code" class="form-control form-control-sm" style="color:red" >
														<option value="">หน่วย</option>
														<?php 
														$sql_unit = "SELECT unit_code,unit_name FROM unit_mstr order by unit_seq";
														$result_unit_list = sqlsrv_query( $conn,$sql_unit);																													
														while($r_unit_list=sqlsrv_fetch_array($result_unit_list, SQLSRV_FETCH_ASSOC)) {
														?>
															<option  style="color:black" value="<?php echo $r_unit_list['unit_code'];?>"> 
																<?php echo $r_unit_list['unit_name'];?>
															</option> 
														<?php } ?>
													</select>															
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="col-12 col-lg-12">
									<div class="row">
										<div class="col-lg-6">
											<div class="form-group">
												<fieldset>
												<center><label class="font-weight-bold text-rught">Price for Customer</label></center>
												<div class="row">
													<div class="col-lg-6">
														<div class="controls">
															<label class="font-weight-bold">ราคา/หน่วย:</label>
															<input name="qtd_customer_price" id="qtd_customer_price" <?php if (!$gbv_editprice) {echo "readonly";}?> class="form-control form-control-sm">
														</div>
													</div>
													<div class="col-lg-6">
														<div class="controls">
															<div class="form-group">
																<label class="font-weight-bold">ส่วนลด:</label>
																<div class="input-group input-group-sm">
																	<input name="qtd_customer_disc" class="form-control">
																	<div class="input-group-append">
																		<select name="qtd_customer_disc_unit" class="form-control form-control-sm red">
																			<option value="">หน่วย</option>
																			<option value="P">%</option>
																			<option value="B">บาท</option>
																		</select>															
																	</div>
																</div>
															</div>
														</div>
													</div>
												</div>
												</fieldset>
											</div>
										</div>
										<div class="col-lg-6">
											<div class="form-group">
												<fieldset>
												<center><label class="font-weight-bold text-rught">Price for Contractor</label></center>
												<div class="row">
													<div class="col-lg-6">
														<div class="controls">
															<label class="font-weight-bold">ราคา/หน่วย:</label>
															<input name="qtd_contractor_price" id="qtd_contractor_price" <?php if (!$gbv_editprice) {echo "readonly";}?> class="form-control form-control-sm">
														</div>
													</div>
													<div class="col-lg-6">
														<div class="controls">
															<div class="form-group">
																<label class="font-weight-bold">ส่วนลด:</label>
																<div class="input-group input-group-sm">
																	<input name="qtd_contractor_disc" class="form-control form-control-sm">
																	<div class="input-group-append">
																		<select name="qtd_contractor_disc_unit" class="form-control form-control-sm red">
																			<option value="">หน่วย</option>
																			<option value="P">%</option>
																			<option value="B">บาท</option>
																		</select>															
																	</div>
																</div>
															</div>
														</div>
													</div>
												</div>
												</fieldset>
											</div>
										</div>	
									</div>
								</div>
								<div class="form-group">
									<div class="controls">
										<label class="font-weight-bold">Remarks:</label>
										<input type="text" name="qtd_remark" class="form-control form-control-sm" maxlength="255">
									</div>
								</div>
							</div>
						</div>
					</form>	
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-sm grey btn-outline-secondary" data-dismiss="modal">Close</button>
				<button type="button" class="btn btn-sm btn-outline-success" onclick='qtd_product_postform("<?php echo 'frm_add_qtd_product';?>")'>Save changes</button>				
			</div>
		</div>					
	</div>
</div>
<form name="frm_del_qtd_product" id="frm_del_qtd_product">
	<input type="hidden" name="action" value="del_qtd_product">
	<input type="hidden" name="csrf_securecode" value="<?php echo $csrf_securecode?>">
	<input type="hidden" name="csrf_token" value="<?php echo md5($csrf_token)?>">
	<input type="hidden" name="qtm_nbr" value="<?php echo $qtm_nbr;?>">		
	<input type="hidden" name="qtd_id">	
	<input type="hidden" name="pg" value="<?php echo $pg?>">
</form>