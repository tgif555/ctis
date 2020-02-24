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
	
	$formname = html_escape($_REQUEST["formname"]);
	$opennerfield_code = html_escape($_REQUEST["opennerfield_code"]);
	$opennerfield_code2 = html_escape($_REQUEST["opennerfield_code2"]);
	
	if( $conn === false ) {
		die( print_r( sqlsrv_errors(), true));
	}
	$sql = "SELECT * FROM amphur_mstr " .
	" INNER JOIN province_mstr ON province_id = amphur_province_id " .
	" WHERE amphur_th_name LIKE '%'+?+'%' OR province_th_name LIKE '%'+?+'%'";

	$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
	$query = sqlsrv_query( $conn, $sql , $params, $options );
	$num_rows = sqlsrv_num_rows($query);
	$per_page = 10;   // Per Page

	if (isset($_GET["page"])) {
        $page = $_GET["page"];
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
	$sql = " SELECT c.* FROM (" .
		" SELECT ROW_NUMBER() OVER(ORDER BY amphur_seq,amphur_th_name) AS RowID,*  FROM dbo.amphur_mstr " .
		" INNER JOIN province_mstr ON province_id = amphur_province_id " .
		" WHERE amphur_th_name LIKE '%'+?+'%' OR province_th_name LIKE '%'+?+'%'" .
	") AS c" .
	" WHERE c.RowID > $row_start AND c.RowID <= $row_end";
	$query = sqlsrv_query( $conn, $sql, $params );
?>
<!doctype html>
<html>
    <head>
    <meta charset="utf-8"> 
    <title>รายชื่ออำภอ</title>
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <link href="../_libs/css/bootstrap.css" rel="stylesheet">
    <link href="../_libs/css/bootstrap-responsive.css" rel="stylesheet">
	<LINK href="../_libs/css/_webstyle.css" "type=text/css" rel="stylesheet">
	<link href="../_libs/css/sptm.css" rel="stylesheet">
	<script src="../_libs/js/jquery-2.1.4.min.js"></script>
	<script src="../_libs/js/bootstrap.min.js"></script>
	<script src="../_libs/js/bootstrap-datepicker.js"></script>	
	<script src="../_libs/js/funcClient.js"></script>
	<script type="text/javascript">
		function replaceAll(str, oldchar, newchar) {
			return str.split(oldchar).join(newchar);
		}
		function updateOpener(formname,opennerfeild_code,opennerfeild_code2,name,name2) {
			window.opener.document.forms[formname].elements[opennerfeild_code].value = name;
			window.opener.document.forms[formname].elements[opennerfeild_code2].value = name2;
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
<body onblur="javascript:window.close();">  	
	<form name="form" autocomplete=OFF method="post" action="getamphur.php">
		<input type="hidden" name="csrf_securecode" value="<?php echo $csrf_securecode?>">
		<input type="hidden" name="csrf_token" value="<?php echo md5($csrf_token)?>">
		<input name="formname" type="hidden" id="formname" value="<?php echo $formname;?>">
		<input name="opennerfield_code" type="hidden" id="opennerfield_code" value="<?php echo $opennerfield_code;?>">
		<input name="opennerfield_code2" type="hidden" id="opennerfield_code2" value="<?php echo $opennerfield_code2;?>">
		<br>
		<table class="table table-bordered table-hover">		
			<tr>
				<td colspan=2>
					<input name="txtKeyword" type="text" id="txtKeyword" value="<?php echo $strKeyword;?>" style="margin:auto" placeholder="* ชื่ออำเภอหรือชื่อจังหวัด">
					<!--input type="submit" class="btn btn-success" value="ค้นหา" style="margin:auto"-->
					<input type="submit" class="btn btn-success btn-sm" value="ค้นหา" style='vertical-align: baseline;'>
				</td>
			</tr>
			<tr>
				<th>ชื่ออำเภอ</th>
				<th>ชื่อจังหวัด</th>
			</tr>			
			<?php
			while($row = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC)) {
				$amphur_th_name = html_escape($row["amphur_th_name"]);
				$province_th_name = html_escape($row["province_th_name"]);
			?>
				<tr>
					<td width=50%>
						<a href="#" OnClick="Javascript:updateOpener('<?php echo $formname?>','<?php echo $opennerfield_code?>','<?php echo $opennerfield_code2?>','<?php echo $amphur_th_name;?>','<?php echo $province_th_name;?>');">
							<?php echo $amphur_th_name;?>
						</a>
					</td>     
					<td>
						<?php echo $province_th_name;?>
					</td>					
				</tr>
			<?php
			}
			?>
			<tr><td colspan=2 style='text-align:center'>Total <?php echo $num_rows; ?> Record : <?php echo $num_pages; ?> Page</td></tr>
		</table>           
		<?php
		echo "<center>";
		if($prev_page) {
			echo " <a href='$_SERVER[SCRIPT_NAME]?page =$prev_page&v=$strKeyword&formname=$formname&opennerfield_code=$opennerfield_code&opennerfield_code2=$opennerfield_code2'><button type='button' class='btn btn-default btn-sm' style='font-size:8pt'>Prev</button></a> ";
		}
		for($i=1; $i <= $num_pages; $i++){
			$page1 = $page-2;
			$page2 = $page+2;
			if($i != $page && $i >= $page1 && $i <= $page2) {
				echo "<a href='$_SERVER[SCRIPT_NAME]?page=$i&v=$strKeyword&formname=$formname&opennerfield_code=$opennerfield_code&opennerfield_code2=$opennerfield_code2'><button type='button' class='btn btn-primary btn-sm' style='font-size:8pt'>$i</button></a> ";
			}
			elseif($i == $page) {
				echo "<button type='button' class='btn btn-danger btn-sm' style='font-size:8pt'>$i</button> ";
			}
		}
		if($page != $num_pages) {  
			echo " <a href ='$_SERVER[SCRIPT_NAME]?page=$num_pages&v=$strKeyword&formname=$formname&opennerfield_code=$opennerfield_code&opennerfield_code2=$opennerfield_code2'><button type='button' class='btn btn-default btn-sm' style='font-size:8pt'>Last</button</a> ";
		}
		echo "</center>";
		?>
    </form>   
</body>
</html>