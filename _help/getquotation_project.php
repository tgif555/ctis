<?php
	//Temp
	$user_login = "NILUBONP";

	include("../_incs/acunx_metaheader.php");
	//include("../_incs/chksession.php");  
	include("../_incs/config.php");	
	include("../_incs/funcServer.php");	
	include("../_incs/acunx_cookie_var.php");
	include "../_incs/acunx_csrf_var.php";
	
	if (($_SERVER['REQUEST_METHOD'] == 'POST')) {
		if (!matchToken($csrf_key,$user_login)) {
			echo "System detect CSRF attack111!!";
			exit;
		}
	}
	$params = array();
	if(isset($_POST["txtKeyword"])) {
		$strKeyword = $_POST["txtKeyword"];	
	}
	else {
		$strKeyword = $_REQUEST["v"];
	}
	$strKeyword = html_escape($strKeyword);
	
	array_push($params, $strKeyword);
	array_push($params, $strKeyword);
	array_push($params, $strKeyword);
	
	$formname = html_escape($_REQUEST["formname"]);
	$fieldid = html_escape($_REQUEST["fieldid"]);
	$opennerfield_code = html_escape($_REQUEST["opennerfield_code"]);
	$opennerfield_code2 = html_escape($_REQUEST["opennerfield_code2"]);
	$txtsearch = html_escape($_REQUEST["v"]);
	
	//pjdmnt.php - Javascript function : helppopup_customer(prgname,formname,opennerfield_code,opennerfield_code2,txtsearch)
	
	if( $conn === false ) {
		die( print_r( sqlsrv_errors(), true));
	}
	$sql_pg = "SELECT * FROM qtm_mstr  ".
	" WHERE (qtm_pjm_nbr ='' or qtm_pjm_nbr is null)".
	" and qtm_is_delete !='1'".
	" and qtm_step_code !='LOSE' and qtm_step_code !='WIN' and qtm_customer_number ='$txtsearch'";  
	
	$params_pg = array();
	$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
	$query_pg = sqlsrv_query( $conn, $sql_pg , $params_pg, $options );
	$num_rows = sqlsrv_num_rows($query_pg);
	$per_page = 10;   // Per Page

	if (isset($_GET["page"])) {
        $page = html_escape($_GET["page"]);
    } else {
        $page = 1;
    }

	$prev_page = $page-1;
	$next_page = $page+1;
	$row_start = (($per_page * $page) - $per_page);
	if($num_rows <= $per_page) { 
		$num_pages =1; 
	}
	else if(($num_rows % $per_page)==0)
    {
      $num_pages =($num_rows/$per_page) ;
    }
	else {
      $num_pages =($num_rows/$per_page)+1;
      $num_pages = (int)$num_pages;
    }

	$row_end = $per_page * $page;
	if($row_end > $num_rows) {
      $row_end = $num_rows;
    }
	
	$sql = " SELECT c.* FROM (
		SELECT ROW_NUMBER() OVER(ORDER BY qtm_nbr desc) AS RowID,* FROM qtm_mstr  ".
	" WHERE (qtm_pjm_nbr ='' or qtm_pjm_nbr is null)".
	" and qtm_is_delete !='1'".
	" and qtm_step_code !='LOSE' and qtm_step_code !='WIN' and qtm_customer_number ='$txtsearch') AS c
	WHERE c.RowID > $row_start AND c.RowID <= $row_end";

	$query = sqlsrv_query( $conn, $sql,$params );
?>
<!doctype html>
<html>
    <head>
    <meta charset="utf-8"> 
    <title>Existing Quotation</title>
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <link href="../_libs/css/bootstrap.css" rel="stylesheet">
    <link href="../_libs/css/bootstrap-responsive.css" rel="stylesheet">
	<link href="../_libs/css/_webstyle.css" "type=text/css" rel="stylesheet">
	<link href="../_libs/prettyPhoto_3.1.6/css/prettyPhoto.css" rel="stylesheet" type="text/css" media="screen" title="prettyPhoto main stylesheet" charset="utf-8" />
	<script src="../_libs/js/jquery-2.1.4.min.js"></script>
	<script src="../_libs/js/bootstrap.min.js"></script>
	<script src="../_libs/js/bootstrap-datepicker.js"></script>	
	<script src="../_libs/js/funcClient.js"></script>
	<script src="../_libs/prettyPhoto_3.1.6/js/jquery.prettyPhoto.js" type="text/javascript" charset="utf-8"></script>
	
	<script type="text/javascript" charset="utf-8">
		$(document).ready(function(){
			$("a[rel^='prettyPhoto']").prettyPhoto();
		});
	</script>
	<script type="text/javascript">
		function updateOpener(formname,fieldid,qtm_nbr,qtm_name,qtm_customer_number,qtm_customer_price) {
			
			window.opener.document.forms[formname].elements["qtm_nbr"].value = qtm_nbr;
			window.opener.document.forms[formname].elements["qtm_name"].value = qtm_name;
			//window.opener.document.forms[formname].elements["qtm_customer_number"].value = qtm_customer_number;
			//window.opener.document.forms[formname].elements["qtm_customer_price"].value = qtm_customer_price;
			window.close();
		}
	</script>
	<style>
		::placeholder { /* Chrome, Firefox, Opera, Safari 10.1+ */
			color: orange;
			opacity: 1; /* Firefox */
		}
		:-ms-input-placeholder { /* Internet Explorer 10-11 */
			color: orange;
		}
		::-ms-input-placeholder { /* Microsoft Edge */
			color: orange;
		}
	</style>
</head>
<body onblur="javascript:window.close();" style='background:#f5f5ef'>  	
	<form name="form" autocomplete=OFF method="post" action="getquotation_project.php">
		<input type="hidden" name="csrf_securecode" value="<?php echo $csrf_securecode?>">
		<input type="hidden" name="csrf_token" value="<?php echo md5($csrf_token)?>">
		<input name="formname" type="hidden" value="<?php echo $formname;?>">
		<input name="fieldid" type="hidden" value="<?php echo $fieldid;?>">
		<br>
		<table class="table table-bordered table-hover" style='background:white'>		
			<tr>
				<td colspan=3>
					<input name="txtKeyword" type="text" id="txtKeyword" class="form-control form-control-inline" value="<?php echo $strKeyword;?>" style="margin:auto" placeholder="* ระบุชื่อหรือรหัส Quotation">
					<!--input type="submit" value="ค้นหา" style="margin:auto"-->
					<input type="submit" class="btn btn-success btn-sm" value="ค้นหา" style='vertical-align: baseline;'>
				</td>
			</tr>
			<tr>
				<th>Quotation No.</th>
				<th>Quotation Name.</th>
				<th>Customer Name</th>
				<th>Price</th>
			</tr>
			<?php
			while($row = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC)) {
				$qtm_nbr = html_escape($row["qtm_nbr"]);
				$qtm_name = html_escape($row['qtm_name']);
				$qtm_customer_number = html_escape($row['qtm_customer_number']);
				$qtm_customer_name = html_escape($row['qtm_customer_name']);
				$qtm_customer_price =html_escape($row['qtm_customer_price']);
			?>
				<tr>
					<td width=20% style="vertical-align:middle">
						<a href="#" OnClick="Javascript:updateOpener(
						'<?php echo $formname?>',
						'<?php echo $fieldid?>',
						'<?php echo $qtm_nbr;?>',
						'<?php echo $qtm_name;?>',
						'<?php echo $qtm_customer_number;?>',
						'<?php echo $mat_customer_price?>');">
						<?php echo $qtm_nbr;?>
						</a>
					</td>
					<td><?php echo $qtm_name?></td>   
					<td style="text-align:right"><?php echo $qtm_customer_name; ?></td>
					<td style="text-align:right">
						<?php 
							if($qtm_customer_price!="" and $qtm_customer_price != null)
								echo number_fmt($qtm_customer_price);
							else
								echo 0;
						?>
					</td>
				</tr>
			<?php
			}
			?>
			<tr><td colspan=5 style='text-align:center'>Total <?php echo $num_rows; ?> Record : <?php echo $num_pages; ?> Page</td></tr>
		</table>           
		<?php
			echo "<center>";
			if($prev_page) {
				echo " <a href='$_SERVER[SCRIPT_NAME]?page =$prev_page&v=$strKeyword&formname=$formname&opennerfield_code=$opennerfield_code&opennerfield_name=$opennerfield_name'><button type='button' class='btn btn-default btn-sm' style='font-size:8pt'>Prev</button></a> ";
			}
			for($i=1; $i <= $num_pages; $i++){
				$page1 = $page-2;
				$page2 = $page+2;
				if($i != $page && $i >= $page1 && $i <= $page2) {
					echo "<a href='$_SERVER[SCRIPT_NAME]?page=$i&v=$strKeyword&formname=$formname&opennerfield_code=$opennerfield_code&opennerfield_name=$opennerfield_name'><button type='button' class='btn btn-primary btn-sm' style='font-size:8pt'>$i</button></a> ";
				}
				elseif($i == $page) {
					echo "<button type='button' class='btn btn-danger btn-sm' style='font-size:8pt'>$i</button> ";
				}
			}
			if($page != $num_pages) {  
				echo " <a href ='$_SERVER[SCRIPT_NAME]?page=$num_pages&v=$strKeyword&formname=$formname&opennerfield_code=$opennerfield_code&opennerfield_name=$opennerfield_name'><button type='button' class='btn btn-default btn-sm' style='font-size:8pt'>Last</button</a> ";
			}
			echo "</center>";
		?>
    </form>   
</body>
</html>