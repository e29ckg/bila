<?php
session_start();
session_destroy(); 
header('Location: /auth/login/index.php?redirect=/bila/');
exit;
?>