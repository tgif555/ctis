<?php
include('../_libs/phpqrcode/qrlib.php'); 
QRcode::png($_GET['w']);
?>