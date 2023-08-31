<?php
session_start();
if(isset($_SESSION["__expire"])){
	if(time() > $_SESSION["__expire"]){
		//goto logout page
        header('Location: /auth/logout.php');
		exit;
	}
}

if( !isset($_SESSION['__id']) ){
    $redirect = $_SERVER['REQUEST_SCHEME'] . '://'. $_SERVER['HTTP_HOST'] . $_SERVER["REQUEST_URI"];
    header('Location: /auth/login/index.php?redirect=' . $redirect);  
    exit;
}
?>