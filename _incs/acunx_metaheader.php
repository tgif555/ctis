<?php
header("X-Frame-Options: SAMEORIGIN"); //SAMEORIGIN,DENY,ALLOW-FROM URL
header("Content-Security-Policy: script-src 'self' object-src 'self' 'unsafe-inline'");
header("Content-Security-Policy: frame-ancestors 'self'");
header("X-XSS-Protection: 1; mode=block");
?>