<form id="frm_final_approve" name="frm_final_approve" autocomplete="OFF">
	<input type="hidden" name="action" value="<?php echo md5('qtm_final_approve'.$user_login)?>">
	<input type="hidden" name="csrf_securecode" value="<?php echo $csrf_securecode?>">
	<input type="hidden" name="csrf_token" value="<?php echo md5($csrf_token)?>">
	<input type="hidden" name="qtm_nbr" value="<?php echo $qtm_nbr?>">
	<input type=hidden name="pg" value="<?php echo $pg?>">
	<div class="row skin skin-square col-12">
		<div class="row col-lg-4">
			<div class="col-lg-12">
				<label class="font-weight-bold">ผลการอนุมัติ:</label>
			</div>
			<div class="col-lg-4">
				<input type="radio" class="bg-primary" name="qtm_approve_select" value="90">
				<label class="bg-teal bg-dark font-weight-bold text-white">&nbsp;&nbsp;อนุมัติ&nbsp;&nbsp;</label>
			</div>
			<div class="col-lg-4">
				<input type="radio" name="qtm_approve_select" value="35">
				<label class="bg-blue font-weight-bold text-white">&nbsp;แก้ไขใหม่&nbsp;</label>
			</div>
			<div class="col-lg-4">
				<input type="radio" name="qtm_approve_select" value="890">
				<label class="bg-red bg-dark font-weight-bold text-white">&nbsp;&nbsp;ไม่อนุมัติ&nbsp;&nbsp;</label>
			</div>
		</div>									
	</div>
	<div class="row col-12">
		<div class="row col-lg-4">
			<div class="col-lg-12">
				<label  class="font-weight-bold">Comment:</label>
				<textarea name="qtm_approve_cmmt" rows="4" class="form-control form-control-sm"></textarea>
			</div>
		</div>
	</div>
	<div style="height:5px"></div>
	<div class="row col-12">
		<div class="row col-lg-4">
			<div class="col-lg-12">
				<div class="btn btn-sm btn-success" style="width:70px" onclick="final_approve_postform()">
					<i class="feather icon-check-square mr-25"></i><span>Save</span>
				</div>
				<div class="btn btn-sm btn-danger" style="width:70px" onclick="loadresult();window.location='qtmall.php?activeid=<?php echo $qtm_nbr?>&page=<?php echo $page?>'">
					<i class="feather icon-chevrons-left mr-25"></i><span>Cancel</span>
				</div>
			</div>								
		</div>
	</div>
</form>