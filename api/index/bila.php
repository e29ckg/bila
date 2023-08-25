<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET,HEAD,OPTIONS,POST,PUT");
header("Access-Control-Allow-Headers: Content-Type, Accept");
header("Content-Type: application/json; charset=utf-8");

include "../../config/connect.php";
include "../../config/function.php";

$data = json_decode(file_get_contents("php://input"));

// The request is using the POST method
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if(!isset($_SESSION['__id'])){
        http_response_code(200);
        echo json_encode(array('status' => false, 'messsge' => 'Login Please..'));
        exit;
    }

    $uid = $_SESSION['__id'];
    $datas = array();
    $profile = '';
    $bilas = array();
    $cats = [];

    try{
        $sql = "SELECT u.username,p.*
                FROM profile as p 
                INNER JOIN `user` as u ON u.id = p.user_id
                WHERE u.id = :uid 
                ORDER BY p.st ASC";
        $query = $conn->prepare($sql);
        $query->bindParam('uid',$uid, PDO::PARAM_STR);
        $query->execute();
        $profile = $query->fetch(PDO::FETCH_OBJ);

        $sql = "SELECT b.*
                FROM bila as b
                WHERE b.user_id = :uid 
                ORDER BY b.running DESC,b.date_begin DESC
                LIMIT 100";
        $query = $conn->prepare($sql);
        $query->bindParam('uid',$uid, PDO::PARAM_STR);
        $query->execute();
        $res = $query->fetchAll(PDO::FETCH_OBJ);

      

        foreach($res as $rs){
            array_push($bilas,array(
                "running" => $rs->running,
                "cat" => $rs->cat,
                "date_begin" => DateThai_full($rs->date_begin),
                "date_end" => DateThai_full($rs->date_end),
                "date_total" => (int)$rs->date_total,
            ));
        }

       


        $datas = [
            "profile"   => $profile,
            "bilas"      => $bilas
        ];

                        
            
        http_response_code(200);
        echo json_encode(array('status' => true, 'messsge' => 'สำเร็จ', 'datas' => $datas));
        exit;
        
    
    }catch(PDOException $e){
        http_response_code(400);
        echo json_encode(array('status' => false, 'messsge' => 'เกิดข้อผิดพลาด..' . $e->getMessage()));
        exit;
    }
}