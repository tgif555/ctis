<?php
	//Temp
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
	$editprice = html_escape($_REQUEST["ep"]);
	
	if( $conn === false ) {
		die( print_r( sqlsrv_errors(), true));
	}
	$sql = "SELECT * FROM mat_mstr  ".
	" INNER JOIN unit_mstr ON unit_code = mat_unit_code".
	" WHERE (mat_th_name LIKE '%'+?+'%'".
	" OR mat_en_name LIKE '%'+?+'%'".
	" OR mat_code LIKE '%'+?+'%')";  
	
	$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
	$query = sqlsrv_query( $conn, $sql , $params, $options );
	$num_rows = sqlsrv_num_rows($query);
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
		SELECT ROW_NUMBER() OVER(ORDER BY mat_seq,mat_code) AS RowID,*  FROM dbo.mat_mstr ".
		" INNER JOIN unit_mstr ON unit_code = mat_unit_code".
		" WHERE (mat_th_name LIKE '%'+?+'%' OR mat_en_name LIKE '%'+?+'%' OR mat_code LIKE '%'+?+'%')
	) AS c
	WHERE c.RowID > $row_start AND c.RowID <= $row_end";
	$query = sqlsrv_query( $conn, $sql,$params );
?>
<!doctype html>
<html>
    <head>
    <meta charset="utf-8"> 
    <title>รายชื่อสินค้า</title>
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
		function updateOpener(formname,fieldid,mat_code,mat_name,mat_unit,mat_customer_price,mat_contractor_price,editprice) {
			
			window.opener.document.forms[formname].elements["qtd_mat_code"].value = mat_code;
			window.opener.document.forms[formname].elements["qtd_mat_name"].value = mat_name;
			window.opener.document.forms[formname].elements["qtd_unit_code"].value = mat_unit;
			window.opener.document.forms[formname].elements["qtd_customer_price"].value = mat_customer_price;
			window.opener.document.forms[formname].elements["qtd_contractor_price"].value = mat_contractor_price;
			window.opener.document.forms[formname].elements["qtd_qty"].value = "0";
			
			window.opener.document.forms[formname].elements["qtd_customer_disc"].value = "0";
			window.opener.document.forms[formname].elements["qtd_customer_disc_unit"].value = "";
			window.opener.document.forms[formname].elements["qtd_contractor_disc"].value = "0";
			window.opener.document.forms[formname].elements["qtd_contractor_disc_unit"].value = "";
			
			var id_qtd_customer_price = "#qtd_customer_price"+fieldid;
			var id_qtd_contractor_price = "#qtd_contractor_price"+fieldid;
			var id_qtd_mat_name = "#qtd_mat_name"+fieldid;
			if (mat_code == "DUMMY") {
				window.opener.$(id_qtd_customer_price).prop("readonly",false);
				window.opener.$(id_qtd_contractor_price).prop("readonly",false);
				window.opener.$(id_qtd_mat_name).prop("readonly",false);
				window.opener.document.forms[formname].elements["qtd_unit_code"].value = "";
			}
			else {
				if (editprice == "1") {
					window.opener.$(id_qtd_customer_price).prop("readonly",false);
					window.opener.$(id_qtd_contractor_price).prop("readonly",false);
				}
				else {
					window.opener.$(id_qtd_customer_price).prop("readonly",true);
					window.opener.$(id_qtd_contractor_price).prop("readonly",true);
				}
				window.opener.$(id_qtd_mat_name).prop("readonly",true);
			}
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
	<form name="form" autocomplete=OFF method="post" action="getproduct.php">
		<input type="hidden" name="csrf_securecode" value="<?php echo $csrf_securecode?>">
		<input type="hidden" name="csrf_token" value="<?php echo md5($csrf_token)?>">
		<input name="formname" type="hidden" value="<?php echo $formname;?>">
		<input name="fieldid" type="hidden" value="<?php echo $fieldid;?>">
		<input name="editprice" type="hidden" value="<?php echo $editprice;?>">
		<br>
		<table class="table table-bordered table-hover" style='background:white'>		
			<tr>
				<td colspan=3>
					<input name="txtKeyword" type="text" id="txtKeyword" class="form-control form-control-inline" value="<?php echo $strKeyword;?>" style="margin:auto" placeholder="* ระบุ ชื่อ/รหัส สินค้า">
					<!--input type="submit" value="ค้นหา" style="margin:auto"-->
					<input type="submit" class="btn btn-success btn-sm" value="ค้นหา" style='vertical-align: baseline;'>
				</td>
			</tr>
			<tr>
				<th>รหัสสินค้า</th>
				<th>ชื่อสินค้า</th>
				<th>ราคาลูกค้า</th>
				<th>ราคาผู้รับเหมา</th>
				<th>หน่วย</th>
			</tr>
			<?php
			while($row = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC)) {
				$mat_code = html_escape($row["mat_code"]);
				$mat_th_name = html_escape($row['mat_th_name']);
				$mat_en_name = html_escape($row['mat_en_name']);
				$mat_customer_unit_price =html_escape($row['mat_customer_unit_price']);
				$mat_contractor_unit_price = html_escape($row['mat_contractor_unit_price']);
				$mat_unit_code = html_escape($row['unit_code']);
				$mat_unit_name = html_escape($row['unit_name']);
				$mat_customer_unit_price = html_escape($row['mat_customer_unit_price']);
				$mat_contractor_unit_price = html_escape($row['mat_contractor_unit_price']);
				
				?>
				<tr>
					<td width=20% style="vertical-align:middle">
						<a href="#" OnClick="Javascript:updateOpener(
						'<?php echo $formname?>',
						'<?php echo $fieldid?>',
						'<?php echo $mat_code;?>',
						'<?php echo $mat_th_name;?>',
						'<?php echo $mat_unit_code;?>',
						'<?php echo $mat_customer_unit_price?>',
						'<?php echo $mat_contractor_unit_price?>',
						'<?php echo $editprice?>');">
						<?php echo $mat_code;?>
						</a>
					</td>
					<td><?php echo $mat_th_name?></td>   
					<td style="text-align:right"><?php echo number_fmt($mat_customer_unit_price)?></td>
					<td style="text-align:right"><?php echo number_fmt($mat_contractor_unit_price)?></td>
					<td style="text-align:middle"><?php echo $mat_unit_name?></td>
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