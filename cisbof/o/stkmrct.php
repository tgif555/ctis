<?php
include("../_incs/chksession.php"); 
include "../_incs/config.php";
include("../_incs/funcServer.php");
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8"> 
    <title><?php echo TITLE; ?></title>
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
	<link href="../_libs/css/bootstrap.css" rel="stylesheet">
    <link href="../_libs/css/bootstrap-responsive.css" rel="stylesheet">
    <link href="../_libs/css/sptm.css" rel="stylesheet">
	<script src="../_libs/js/jquery-2.1.4.min.js"></script>	
	<script src="../_libs/js/bootstrap.min.js"></script>
	<script>
	  document.addEventListener('keydown', function(event) {
		if(event.keyCode == 17 || event.keyCode == 74 )
		  event.preventDefault();
	  });
	</script>
	<script language="javascript">
		function helppopup(prgname,formname,opennerfield_code,txtsearch) {				
			//var help_program = prgname;
			//var help_search = txtsearch;		

			var w = 500;
			var h = 550;
			var winl = (screen.width-w)/2;
			var wint = (screen.height-h)/2;
			var settings ='height='+h+',';
			settings +='width='+w+',';
			settings +='top='+wint+',';
			settings +='left='+winl+',';
			settings +='scrollbars=no,';
			settings +='toolbar=no,';
			settings +='location=no,'; 
			settings +='resizable=yes';		
			var myWindow=window.open(prgname+'?v='+txtsearch+'&formname='+formname+'&opennerfield_code='+opennerfield_code,'windowhelp1',settings);		
			if (!myWindow.opener) myWindow.opener = self;
			
		}	
	</script>
	<script type="text/javascript">
		$(document).ready(function () {
			$('#stkm_mat_code').focus();
			
			$("input[type='text']").click(function () {
			   $(this).select();
			});
			$( "#stkm_mat_code").keypress(function( event ) {
				if ( event.which == 13 ) {
					$.ajax({
						type: 'POST',
						url: '../_chk/chkstkmmatcode.php',
						data: $('#stkm_mat_code').serialize(),
						timeout: 5000,
						success: function(result) {
							//console.log(result);
							
							var json = $.parseJSON(result);
							$('#div_stkm_mat_name').html('<span style="color:red">'+json.mat_name+"</span>");
							$('#div_stkm_qty_oh').html(json.mat_qty_oh);
							$('#stkm_p_per_box').val(json.mat_pcs_per_box);
							$('#stkm_p_per_box_hidden').val(json.mat_pcs_per_box);
							$('#stkm_location').val(json.mat_location);
							if (json.mat_result == '0') {
								showmsg(json.mat_name);
							}
							else {
								$('#stkm_rct_type').focus();
							}
							
						}
					});
						
				}
			});
			$("#btnsave").click(function() {
				$.ajax({
					beforeSend: function () {
						$('body').append('<div id="requestOverlay" class="request-overlay"></div>'); /*Create overlay on demand*/
						$("#requestOverlay").show();/*Show overlay*/
					},
					type: 'POST',
					url: '../serverside/stkmpost.php',
					data: $('#frm_stkm_rct').serialize(),
					timeout: 5000,
					success: function(result) {	
						//console.log(result);
						
						var json = $.parseJSON(result);
						if (json.r == '0') {
							showmsg(json.e);
						}
						else {
							clearform()
							window.opener.location.reload();
						}
						
					},
					complete: function () {
						$("#requestOverlay").remove();/*Remove overlay*/
					}
				});
			});
		});
	</script>
	
	<script language="javascript">	
		
		function loadresult() {
			$('#div_result').html("<center><img id='progress' src='../_images/loading0.gif' width=80 height=80><center>");
		}	
		function clearresult() {
			$('#div_result').html("");
		}
		function showmsg(msg) {
			$("#modal-body").html(msg);
			$("#myModal").modal("show");
		}
		function clearform() {
			$('#stkm_mat_code').val("");
			$('#div_stkm_mat_name').html("**");
			$('#div_stkm_qty_oh').html("0");
			$('#stkm_rct_type').val("");
			$('#stkm_rct_remark').val("");
			$('#stkm_p_per_box').val("");
			$('#stkm_qty').val("");
			$('#stkm_rct_unit').val("");
			$('#stkm_mat_code').focus();
		}		
		
		function stkm_rct_unit_change(v) {
			if (v == 'PAN' || v == 'BOD') {
				document.frm_stkm_rct.stkm_p_per_box.value = 1;
				document.frm_stkm_rct.stkm_p_per_box.readOnly = true;
			} else {
				document.frm_stkm_rct.stkm_p_per_box.value = document.frm_stkm_rct.stkm_p_per_box_hidden.value;
				document.frm_stkm_rct.stkm_p_per_box.readOnly = false;
			}
		}
</script>	
</head>
<body>		
	<div id="div_result"></div>
	<form name="frm_stkm_rct" id="frm_stkm_rct" autocomplete=OFF method="post">	
		<input type="hidden" name="action" value="<?php echo md5('stkm_rct_barcode'.$user_login)?>">						
		<input type="hidden" id="stkm_p_per_box_hidden" name="stkm_p_per_box_hidden">
		<!--div class="modal-body"-->
		<div class="">
			<table><tr><td style='height:10px'></td></tr></table>
			<table class="table table-condensed table-bordered" border=0 cellpadding=2 cellspacing=2>	
				<tbody>
				<tr>
					<td colspan=2 style='text-align:center;background:red;font-size:8pt;color:white'>รับสต๊อคสินค้าตัวอย่าง:</td>
				</tr>
				<tr>
					<td width=25% style="text-align:right;font-size:8pt;vertical-align: middle;">รหัสสินค้า:</td>
					<td style="font-size:8pt">
						<input type="text" name="stkm_mat_code" id="stkm_mat_code" style='margin: auto;'>
					</td>
				</tr>
				<tr>
					<td style="text-align:right;font-size:8pt;vertical-align: middle;">ชื่อสินค้า:</td>
					<td style="font-size:8pt;color:gray"><span id='div_stkm_mat_name'>**</span></td>
				</tr>
				<tr>
					<td style="text-align:right;font-size:8pt;vertical-align: middle;">ปริมาณคงเหลือ:</td>
					<td style="font-size:8pt"><span id='div_stkm_qty_oh'>0</span></td>
				</tr>
				<tr>
					<td style="text-align:right;font-size:8pt;vertical-align: middle;">ประเภทรับ:</td>
					<td>
						<select name="stkm_rct_type" id="stkm_rct_type" style="font-size:8pt;color:blue;margin:auto">
							<option value="">-ประเภท-</option>
							<option value="RCT-FOC">Foc</option>
							<option value="RCT-UNP">Unplan</option>
						</select>
					</td>
				</tr>
				<tr>
					<td style="text-align:right;font-size:8pt;vertical-align: middle;">หมายเหตุ:</td>
					<td>
						<input type="text" name="stkm_rct_remark" id="stkm_rct_remark" style='width:170px;font-size:8pt;margin:auto;color:red' maxlength=255 placeholder="* เหตุผลการรับ Unplan *">
						<button type="button" class="btn btn-default" style="margin: auto;" 
							OnClick="helppopup('../_help/getstkrmstr.php','frm_stkm_rct','stkm_rct_remark',document.frm_stkm_rct.stkm_rct_remark.value)">
							<span class="icon icon-search" aria-hidden="true"></span>
						</button>
					</td>
				</tr>
				<tr>
					<td style="text-align:right;font-size:8pt;vertical-align: middle;">สถานที่จัดเก็บ:</td>
					<td style="font-size:8pt">
						<input type="text" name="stkm_location" id="stkm_location" style='margin:auto;'>
					</td>
				</tr>
				<tr>
					<td style="text-align:right;font-size:8pt;vertical-align: middle;">จำนวนรับ:</td>
					<td style="background:white;font-size:6pt;font-weight:bold">
						<input type="text" name="stkm_qty" id="stkm_qty" style='color:green;width:90px;margin:auto;text-align:center;font-size:12pt;font-weight:bold'>
						
						<select name="stkm_rct_unit" id="stkm_rct_unit" style="width:100px;font-size:8pt;color:blue;margin:auto" onchange="javascript:stkm_rct_unit_change(this.value)">
							<option value="">-หน่วย-</option>
							<option value="BOX">กล่อง</option>
							<option value="PAN">แผ่น</option>
							<option value="BOD">บอร์ด</option>
						</select>
					</td>
				</tr>	
				<tr>
					<td style="text-align:right;font-size:8pt;vertical-align: middle;">แผ่น/กล่อง:</td>
					<td style="font-size:8pt">
						<input type="text" name="stkm_p_per_box" id="stkm_p_per_box" style='margin:auto;text-align:center;width:90px;color:red;font-weight:bold'>
					</td>
				</tr>
				</tbody>
			</table>					
		</div>
		<div style='text-align:center;'>
			<input id="btnsave" type="button" class='btn-success' style='width:80px;font-size:8pt;' value="Save">					
		</div>
	</form>																																															
	
	<!-- Modal -->
	<div class="modal fade" id="myModal" role="dialog">
		<div class="modal-dialog modal-sm">
			<div class="modal-content">
				<div class="modal-header">
				  <button type="button" class="close" data-dismiss="modal">&times;</button>
				  <h4 class="modal-title">Message</h4>
				</div>
				<div id='modal-body' class="modal-body" style='color:red'>
				  <p></p>
				</div>
				<div class="modal-footer">
				  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>
</body>
</html>
