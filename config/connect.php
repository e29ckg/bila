<?php

session_start();
error_reporting(E_ALL);
// error_reporting(0);
date_default_timezone_set("Asia/Bangkok");
define("__GOOGLE_CALENDAR__",true);
define("__ENV__","dev");            //   dev : production 

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "main";

if(__ENV__ == 'dev'){
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "main_dev"; 
}

/** เชื่อมต่อฐานข้อมูลด้วย PHP PDO */
try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
} catch(PDOException $e) {
    // echo "การเชื่อมต่อฐานข้อมูลล้มเหลว: " . $e->getMessage();
    http_response_code(200);
    $response = array('status'=>false,'message' => 'การเชื่อมต่อฐานข้อมูลล้มเหลว:'  . $e->getMessage());
    echo json_encode($response);
    exit();
}

//error handler function
function customError($errno, $errstr) {
    // echo "Error: [$errno] $errstr";
    http_response_code(200);
    $response = array('status'=>false,'message' => "Error: [$errno] $errstr");
    echo json_encode($response);
    exit();
}

//set error handler
// set_error_handler("customError");

//trigger error
// echo($test);
