<?php
$csrf_key = "Scgc2018";
$csrf_securecode = encrypt(rand(),$csrf_key);
$csrf_token = $csrf_securecode.$user_login;
?>