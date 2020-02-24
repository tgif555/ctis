<?php
$user_login = "KOMSUNYU";

include("../_incs/acunx_metaheader.php");
//include("../_incs/chksession.php");  
include("../_incs/config.php");	
include("../_incs/funcServer.php");	
include("../_incs/acunx_cookie_var.php");
include "../_incs/acunx_csrf_var.php";

if (($_SERVER['REQUEST_METHOD'] == 'POST')) {
	if (!matchToken($csrf_key,$user_login)) {
		echo "System detect CSRF attack!!";
		exit;
	}
}

set_time_limit(0);
$curdate = date('Ymd');

$params = array();
$activeid = decrypt(mssql_escape($_REQUEST['activeid']), $key);

$in_qtm_tmpsubmit = mssql_escape($_POST["in_qtm_tmpsubmit"]);
$in_qtm_nbr = mssql_escape($_POST["in_qtm_nbr"]);
$in_qtm_customer = mssql_escape($_POST["in_qtm_customer"]);
$in_qtm_step_code = mssql_escape($_POST["in_qtm_step_code"]);

If ($in_qtm_tmpsubmit == "") {
	$in_qtm_tmpsubmit = $_COOKIE['in_qtm_tmpsubmit'];	
	$in_qtm_nbr = $_COOKIE['in_qtm_nbr'];
	$in_qtm_customer = $_COOKIE['in_qtm_customer'];
	$in_qtm_step_code = $_COOKIE['in_qtm_step_code'];
}
else {		
	setcookie("in_qtm_tmpsubmit","",0,$ck_path,$ck_dom,$ck_secure,$ck_httponly);
	setcookie("in_qtm_nbr","",0,$ck_path,$ck_dom,$ck_secure,$ck_httponly);
	setcookie("in_qtm_customer","",0,$ck_path,$ck_dom,$ck_secure,$ck_httponly);
	setcookie("in_qtm_select","",0,$ck_path,$ck_dom,$ck_secure,$ck_httponly);
	setcookie("in_qtm_step_code","",0,$ck_path,$ck_dom,$ck_secure,$ck_httponly);
	setcookie("in_qtm_shownpd","",0,$ck_path,$ck_dom,$ck_secure,$ck_httponly);
}
//
if ($in_qtm_nbr != "") {
	if ($criteria != "") { $criteria = $criteria . " AND "; }
	$criteria = $criteria . " qtm_nbr like '%$in_qtm_nbr%'";
}
setcookie("in_qtm_nbr", $in_qtm_nbr,0,$ck_path,$ck_dom,$ck_secure,$ck_httponly);
//
if ($in_qtm_customer != "") {
	if ($criteria != "") { $criteria = $criteria . " AND "; }
	$criteria = $criteria . " (customer_name1 like '%$in_qtm_customer%' OR qtm_to like '%$in_qtm_customer%')";
}
setcookie("in_qtm_customer", $in_qtm_customer,0,$ck_path,$ck_dom,$ck_secure,$ck_httponly);
//
	
if ($in_qtm_step_code != "") {
	if ($criteria != "") { $criteria = $criteria . " AND "; }
	$criteria = $criteria . " qtm_step_code = '$in_qtm_step_code'";
}
setcookie("in_qtm_step_code", $in_qtm_step_code,0,$ck_path,$ck_dom,$ck_secure,$ck_httponly);
//
if ($criteria != "") { $criteria = " AND " . $criteria; }		

?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8"> 
  <title><?php echo TITLE; ?></title>
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>AdminLTE 3 | Simple Tables</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  
  <link rel="stylesheet" href="../template/plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <link rel="stylesheet" href="../template/dist/css/adminlte.min.css">
  
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
  <script language="javascript">		
		function loadresult() {
			document.all.result.innerHTML = "<center><img id='progress' src='../_images/loading0.gif' width=80 height=80><center>";
		}
				
		function showdata() {			
			var errorflag = false;
			var errortxt = "";
			document.getElementById("msghead").innerHTML = "พบข้อผิดผลาดในการบันทึกข้อมูล";
			if (errorflag ) {			
				document.getElementById("msgbody").innerHTML = "<font color=red>" + errortxt + "</font>";
				$("#myModal").modal("show");
			}
			else {	
				loadresult()
				document.frm.submit();									
			}
		}

		function qtmcopypost(qtm_nbr) {	
			var errorflag = false;
			var errortxt = "";
			document.getElementById("msghead").innerHTML = "พบข้อผิดผลาดในการบันทึกข้อมูล";
			var conf_info = "ระบบจะทำการ COPY ข้อมูลของใบเบิกหมายเลข "+qtm_nbr + " ไปเป็นใบเบิกใบใหม่ ท่านต้องการ COPY ใบเบิกใบนี้ไช่หรือไม่ ?"
			if(confirm(conf_info)) {
				document.frmqtmcopy.qtm_nbr.value = qtm_nbr;
				document.frmqtmcopy.submit();
			}
		}	
	
		function delqtm(qtm_nbr,pg) {
			if(confirm('ท่านต้องการลบข้อมูลการนี้ ไช่หรือไม่ ?')) {	
				document.frmdelete.qtm_nbr.value = qtm_nbr;
				document.frmdelete.pg.value = pg;
				document.frmdelete.submit();
			}
		}		
		
		function gotopage(mypage) {							
			loadresult()
			document.frm.pg.value=mypage;
			document.frm.submit();
		}	
</script>

</head>

<body class="hold-transition" style="font-size:10pt">
	<?php				
	//นับจำนวน Record ของงานที่รอคุณทำ
	$total_curprocessor = 0;

	$sql_cnt =  "SELECT count(*) 'cnt' FROM qtm_mstr INNER JOIN customer ON customer_number = qtm_customer_number WHERE qtm_is_delete = '0' and  (qtm_curprocessor like '%$user_login%')";
	$result_cnt = sqlsrv_query($conn, $sql_cnt); 
	$row_cnt = sqlsrv_fetch_array($result_cnt, SQLSRV_FETCH_ASSOC);	

	if ($row_cnt) {
		$total_curprocessor = (int)$row_cnt['cnt'];
	}

	//นับจำนวนตาม criteria
	$sql_cnt =  "SELECT * FROM qtm_mstr INNER JOIN customer ON customer_number = qtm_customer_number WHERE qtm_is_delete = '0' $criteria";
	$result = sqlsrv_query( $conn,$sql_cnt, array(), array( "Scrollable" => 'keyset' ));	
	$max = sqlsrv_num_rows($result);
	
	$pagesize = 2;
	$totalrow = $max;
	$totalpage = ($totalrow/$pagesize) - (int)($totalrow/$pagesize);
	if ($totalpage > 0) {
		$totalpage = ((int)($totalrow/$pagesize)) + 1;
	} else {
		$totalpage = (int)$totalrow/$pagesize;
	}					
	if ($_REQUEST["pg"]=="") {
		$currentpage = 1;	
		$end_row = ($currentPage * $pagesize) - 1;
		if ($end_row > ($totalrow - 1)) { $end_row = $totalrow - 1; }
		$start_row = 0;
	} else {
		$currentpage = $_REQUEST["pg"];
		if ((int)$currentpage < 1) { $currentpage = 1; }
		if ((int)$currentpage > (int)$totalpage) { $currentpage = $totalpage; }
		$end_row = ($currentpage * $pagesize) - 1;
		$start_row = $end_row - $pagesize + 1;
		if ($end_row > ($totalrow - 1)) { $end_row = $totalrow - 1; }					
	}
	
	
	$maxpage = 11; //-- ใส่ได้เฉพาะเลขคี่เท่านั้น
	$slidepage = (int)($maxpage/2); //-มีไว้สำหรับเลื่อน	
	if ((int)($totalpage) <= (int)($maxpage)) {
		$maxpage = $totalpage;
	}		
	if ($currentpage < $maxpage) {
		$start_page = 1;
		$end_page = $maxpage;	
	} else {		
		$start_page = $currentpage - $slidepage;
		$end_page = $currentpage + $slidepage;
		if ($start_page <= 1) {
			$start_page = 1;
			$end_page = $maxpage;
		} 
		if ($end_page >= $totalpage) {
			$start_page = $totalpage - $maxpage + 1;
			$end_page = $totalpage;
		}
	}	
	?>	
	<div id="result"></div>
  <!-- Navbar -->
  <nav class="navbar navbar-expand navbar-white navbar-light"  style="background:gray">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="../../index3.html" class="nav-link">Home</a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="#" class="nav-link">Contact</a>
      </li>
    </ul>

    <!-- SEARCH FORM -->
    <form class="form-inline ml-3">
      <div class="input-group input-group-sm">
        <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
        <div class="input-group-append">
          <button class="btn btn-navbar" type="submit">
            <i class="fas fa-search"></i>
          </button>
        </div>
      </div>
    </form>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <!-- Messages Dropdown Menu -->
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="far fa-comments"></i>
          <span class="badge badge-danger navbar-badge">3</span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <a href="#" class="dropdown-item">
            <!-- Message Start -->
            <div class="media">
              <img src="../../dist/img/user1-128x128.jpg" alt="User Avatar" class="img-size-50 mr-3 img-circle">
              <div class="media-body">
                <h3 class="dropdown-item-title">
                  Brad Diesel
                  <span class="float-right text-sm text-danger"><i class="fas fa-star"></i></span>
                </h3>
                <p class="text-sm">Call me whenever you can...</p>
                <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
              </div>
            </div>
            <!-- Message End -->
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <!-- Message Start -->
            <div class="media">
              <img src="../../dist/img/user8-128x128.jpg" alt="User Avatar" class="img-size-50 img-circle mr-3">
              <div class="media-body">
                <h3 class="dropdown-item-title">
                  John Pierce
                  <span class="float-right text-sm text-muted"><i class="fas fa-star"></i></span>
                </h3>
                <p class="text-sm">I got your message bro</p>
                <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
              </div>
            </div>
            <!-- Message End -->
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <!-- Message Start -->
            <div class="media">
              <img src="../../dist/img/user3-128x128.jpg" alt="User Avatar" class="img-size-50 img-circle mr-3">
              <div class="media-body">
                <h3 class="dropdown-item-title">
                  Nora Silvester
                  <span class="float-right text-sm text-warning"><i class="fas fa-star"></i></span>
                </h3>
                <p class="text-sm">The subject goes here</p>
                <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
              </div>
            </div>
            <!-- Message End -->
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item dropdown-footer">See All Messages</a>
        </div>
      </li>
      <!-- Notifications Dropdown Menu -->
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="far fa-bell"></i>
          <span class="badge badge-warning navbar-badge">15</span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <span class="dropdown-item dropdown-header">15 Notifications</span>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="fas fa-envelope mr-2"></i> 4 new messages
            <span class="float-right text-muted text-sm">3 mins</span>
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="fas fa-users mr-2"></i> 8 friend requests
            <span class="float-right text-muted text-sm">12 hours</span>
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="fas fa-file mr-2"></i> 3 new reports
            <span class="float-right text-muted text-sm">2 days</span>
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
        </div>
      </li>
      
    </ul>
  </nav>
  

  <!-- Content Wrapper. Contains page content -->
  <div>
    <!-- Content Header (Page header) -->
    <section class="content-header"  style="background:#d3d3d3">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h5>Quotation Management</h5>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Quotation Management</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <!--h3 class="card-title">Simple Full Width Table</h-->
				<a href="javascript:void(0)" role="button" style="color:#FFF; text-decoration:none;" data-toggle="modal">
					<div class="btn btn-small btn-primary paddingleftandright10" style="margin:auto;" onclick="window.location.href='qtmadd.php?pg=<?php echo $currentpage?>'">
						<i class="icon-plus icon-white"></i>														
						<span>สร้างใบเสนอราคา</span>
					</div>
				</a>
              </div>
              <!-- /.card-header -->
              <div class="card-body p-0">
				<table width="100%" border=0>				
								
						<tr>
							<td width=75% valign=top>
								<table width="100%" class="table table-sm table-bordered">
									<form name="frm" method="POST" autocomplete=OFF action="simple.php">
									<input type="hidden" name="csrf_securecode" value="<?php echo $csrf_securecode?>">
									<input type="hidden" name="csrf_token" value="<?php echo md5($csrf_token)?>">
									<input type="hidden" name="in_qtm_tmpsubmit" value="search">
									<input type="hidden" name="action">	
									<input type="hidden" name="pg">
									<tr>
										<td style="width:120px;text-align:right" class="f_bk8b">Quotation No<font color=red><b>*</b></font>:</td>
										<td style="width:50px">
											<input name="in_qtm_nbr" value="<?php echo $in_qtm_nbr?>" class="inputtext_s" style='color:blue'>												
										</td>	
										<td style="width:80px;text-align:right" class="f_bk8b">Status<font color=red><b>*</b></font>:</td>
										<td colspan=3 style="width:160px">
											<select name="in_qtm_step_code" class="f_bl8" style="width: 150px;margin: auto" >
												<option value="">-- All --</option>
												<?php
												$sql_step = "SELECT step_code,step_name FROM step_mstr order by step_seq";												
												$result_step_list = sqlsrv_query( $conn,$sql_step);																													
												while($r_step_list=sqlsrv_fetch_array($result_step_list, SQLSRV_FETCH_ASSOC)) {
												?>
													<option  value="<?php echo $r_step_list['step_code'];?>"
													<?php if (trim($r_step_list['step_code']) == "$in_qtm_step_code") { echo "selected"; } ?>>
													<?php echo html_quot($r_step_list['step_name']);?></option> 
												<?php } ?>
											</select>		
										</td>
										
									</tr>
									<tr>
										<td style="text-align:right" class="f_bk8b">ชื่อลูกค้า<font color=red><b>*</b></font>:</td>
										<td style=""><input name="in_qtm_customer" value="<?php echo $in_qtm_customer?>" class="inputtext_s" style='color:blue'></td>
										<td><input type="image" name="showdata" id="showdata" src="../_images/find_25x25.png" alt="Submit" onclick="showdata()"></td>
									</tr>
									</form>									
								</table>
							</td>
						</tr>
						<tr bgcolor="lightgray">
							<td width=100% colspan=2>
								<div class="card-tools">
									(Total <font color=red><?php echo $totalpage;?></font>&nbsp;Pages&nbsp;<font color=red><?php echo $totalrow;?></font>&nbsp;items)								
									<b>Jump To Page:</b>&nbsp;<input name="jumto" style="width:30px;">&nbsp;<input name="go" type="button" value="go" onclick="gotopage(document.all.jumto.value)">
									<ul class="pagination pagination-sm float-right">
										<?php
										if ($start_page > 1) {
											echo "<li class='page-item'><a class='paging' href='javascript:gotopage(1)'>&laquo;</a></li>";
										}														
										for ($pg=$start_page; $pg<=$end_page; $pg++) {											
											echo "<li class='page-item'><a class='page-link' href='javascript:gotopage(" . $pg . ")'>$pg</a></li>";
										}												
										if ($end_page < $totalpage) {		
											echo "<li class='page-item'><a class='page-link' href='javascript:gotopage(" . $totalpage . ")'>&raquo;</a></li>";
										}
										?>
									</ul>
								</div>							
							</td>						
						</tr>
						<tr>
							<td width=100% colspan=2>
								<table width="100%">									
									<tr>													
										<td bgcolor="white">											
											<table class="table table-sm table-bordered" width="100%">
												<thead>
												<tr valign="top" bgcolor="#fecf03">
													<td style="width:50px;text-align:center">No</td>
													<td style="width:100px;text-align:center">Quotation No</td>
													<td style="width:180px;text-align:center">ชื่องาน</td>
													<td style="width:180px;text-align:center">โครงการ</td>
													<td style="width:200px;text-align:center">ชื่อลูกค้า</td>
													<td style="width:100px;text-align:center">อำเภอ</td>
													<td style="width:100px;text-align:center">จังหวัด</td>
													<td style="width:80px;text-align:center">วันที่</td>
													<td style="width:80px;text-align:center">วันหมดอายุ</td>
													<td style="width:150px;text-align:center">รายละเอียด</td>
													<td style="width:80px;text-align:center">ส่วนลด(%)</td>
													<td style="width:80px;text-align:center">ส่วนลด(บาท)</td>
													<td style="width:100px;text-align:center">สถานะ</td>
													<td style="width:30px;text-align:center">Action</td>
													<td style="width:10px;">&nbsp;</td>
												</tr>
												</thead>   
												<tbody>
												<?php
												$n = 0;													
												$sql_qtm = "SELECT qtm.* FROM" .
												" (SELECT ROW_NUMBER() OVER(ORDER BY qtm_nbr) AS rownumber,* FROM qtm_mstr INNER JOIN customer ON customer_number = qtm_customer_number WHERE qtm_is_delete = 0 $criteria) as qtm" .
												" WHERE qtm.rownumber > $start_row and qtm.rownumber <= $start_row+$pagesize";																																																														
												
												$result_qtm = sqlsrv_query( $conn, $sql_qtm,$params);
												while($rec_qtm = sqlsrv_fetch_array($result_qtm, SQLSRV_FETCH_ASSOC)) {
													$qtm_nbr = html_escape($rec_qtm['qtm_nbr']);
													$qtm_to = html_escape($rec_qtm['qtm_to']);
													$qtm_name = html_escape($rec_qtm['qtm_name']);
													$qtm_customer_number = html_escape($rec_qtm['qtm_customer_number']);
													if ($qtm_customer_number != "DUMMY") {
														$qtm_customer_name = findsqlval("customer","customer_name1", "customer_number", $qtm_customer_number,$conn);
													}
													else {
														$qtm_customer_name = $qtm_to;
													}
													$qtm_date = html_escape($rec_qtm['qtm_date']);
													$qtm_expire_date = html_escape($rec_qtm['qtm_expire_date']);
													$qtm_address = html_escape($rec_qtm['qtm_address']);
													$qtm_amphur = html_escape($rec_qtm['qtm_amphur']);
													$qtm_province = html_escape($rec_qtm['qtm_province']);
													$qtm_zip_code = html_escape($rec_qtm['qtm_zip_code']);
													$qtm_lineid = html_escape($rec_qtm['qtm_lineid']);
													$qtm_email = html_escape($rec_qtm['qtm_email']);
													$qtm_tel_contact = html_escape($rec_qtm['qtm_tel_contact']);
													$qtm_payterm_code = html_escape($rec_qtm['qtm_payterm_code']);
													$qtm_detail = html_escape($rec_qtm['qtm_detail']);
													$qtm_remark = html_escape($rec_qtm['qtm_remark']);
													$qtm_per_disc = html_escape($rec_qtm['qtm_per_disc']);
													$qtm_amt_disc = html_escape($rec_qtm['qtm_amt_disc']);
													$qtm_whocanread = html_escape($rec_qtm['qtm_whocanread']);
													$qtm_curprocessor = html_escape($rec_qtm['qtm_curprocessor']);
													$qtm_create_by = html_escape($rec_qtm['qtm_create_by']);	
													$qtm_create_by = findsqlval("emp_mstr","emp_th_firstname+' '+emp_th_lastname", "emp_user_id", $qtm_create_by,$conn);
													
													$n++;																										
													?>	
													<tr>
														<td class="f_bk8" style="text-align:center;"><?php echo $n+($currentpage-1)*$pagesize; ?></td>
														<td class="f_bk8" style=""><?php echo $qtm_nbr; ?></td>
														<td class="f_bk8" style=""><?php echo $qtm_name; ?></td>
														<td class="f_bk8" style=""><?php echo $qtm_pjm_nbr; ?></td>
														<td class="f_bk8" style=""><?php echo $qtm_customer_name; ?></td>
														<td class="f_bk8" style=""><?php echo $qtm_amphur; ?></td>
														<td class="f_bk8" style=""><?php echo $qtm_province; ?></td>
														<td class="f_bk8" style=""><?php echo dmyty($qtm_date); ?></td>
														<td class="f_bk8" style=""><?php echo dmyty($qtm_expire_date); ?></td>
														<td class="f_bk8" style=""><?php echo $qtm_detail; ?></td>
														<td class="f_bk8" style=""><?php echo $qtm_per_disc; ?></td>
														<td class="f_bk8" style=""><?php echo $qtm_amt_disc; ?></td>
														<td class="f_bk8" style=""><?php echo $qtm_step_code; ?></td>
														<td width=2% style="text-align:center">
															<center>
															<a href="javascript:void(0)" onclick="loadresult();window.location.href='qtdmnt.php?qtmnumber=<?php echo encrypt($qtm_nbr, $key);?>&pg=<?php echo $currentpage?>'">
																<img style="border-radius:50%" src='../_images/qt.png' width=24>
															</a>
															</center>
														</td>
														<td style="text-align:center">
															<?php if($activeid==$qtm_nbr) {echo "<img src='../_images/active-id.png'>";}?>
														</td>
													</tr>
												<?php }?>	
												</tbody>
											</table>  
											
										</td>										
									</tr>
								</table>
								
							</td>
						</tr>
					</table>
					
                
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
		
          <!-- /.col -->
        </div>
        
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <!--footer class="main-footer">
    <div class="float-right d-none d-sm-block">
      <b>Version</b> 3.0.2
    </div>
    <strong>Copyright &copy; 2014-2019 <a href="http://adminlte.io">AdminLTE.io</a>.</strong> All rights
    reserved.
  </footer-->

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="../template/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="../template/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="../template/dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="../template/dist/js/demo.js"></script>

</body>
</html>
