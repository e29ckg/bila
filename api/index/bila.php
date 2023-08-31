<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET,HEAD,OPTIONS,POST,PUT");
header("Access-Control-Allow-Headers: Content-Type, Accept");
header("Content-Type: application/json; charset=utf-8");

include "../../config/connect.php";
include "../../config/function.php";

$data = json_decode(file_get_contents("php://input"));

if(!isset($_SESSION['__id'])){
    http_response_code(200);
    echo json_encode(array('status' => false, 'message' => 'Login Please..'));
    exit;
}
$uid = $_SESSION['__id'];
// $uid = 19;

// The request is using the POST method
if ($_SERVER['REQUEST_METHOD'] === 'GET') {    

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
        $query->bindParam('uid',$uid, PDO::PARAM_INT);
        $query->execute();
        $profile = $query->fetch(PDO::FETCH_OBJ);

        $sql = "SELECT b.*
                FROM bila as b
                WHERE b.user_id = :uid 
                ORDER BY b.running DESC,b.date_begin DESC
                LIMIT 100";
        $query = $conn->prepare($sql);
        $query->bindParam(':uid',$uid, PDO::PARAM_STR);
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
            "bilas"      => $res
        ];
            
        http_response_code(200);
        echo json_encode(array('status' => true, 'message' => 'สำเร็จ', 'datas' => $datas));
        exit;
        
    
    }catch(PDOException $e){
        http_response_code(400);
        echo json_encode(array('status' => false, 'message' => 'เกิดข้อผิดพลาด..' . $e->getMessage()));
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $leave = $data->leave;

    /** เรียกใบลาเก่า */
    if($leave->act == 'leave_old'){
        $cat = $leave->cat;
        $sql = "SELECT b.*
                FROM bila as b
                WHERE b.cat = :cat 
                    AND b.status = 1 
                    AND b.user_id = :uid 
                ORDER BY b.date_begin DESC
                LIMIT 1";
        $query = $conn->prepare($sql);
        $query->bindParam(':cat',$cat, PDO::PARAM_STR);
        $query->bindParam(':uid',$uid, PDO::PARAM_INT);
        $query->execute();
        $bila = $query->fetch(PDO::FETCH_OBJ);

        http_response_code(200);
        echo json_encode(array('status' => true, 'message' => 'สำเร็จ...leave_old', 'datas' => $bila));
        exit;
    }

    /** บันทึกใบลา */
    if($leave->act == 'insert'){
        $running = 9999;
        $user_id = $uid;
        $cat    = $leave->cat;
        $p1     = $leave->p1;
        $p2     = $leave->p2;
        $date_begin = $leave->date_begin;
        $date_end   = $leave->date_end;
        $date_total = $leave->date_total;
        $due        = $leave->due;
        $dateO_begin = $leave->dateO_begin;
        $dateO_end  = $leave->dateO_end;
        $dateO_total = $leave->dateO_total;
        $address    = $leave->address;
        $t1 = $leave->t1;
        $t2 = $leave->t2;
        $t3 = $leave->t3;
        $comment = $leave->comment;
        $status = 1;

        $sql = "INSERT INTO bila(running, user_id, cat, p1, p2, date_begin, date_end, date_total, 
                    due, dateO_begin, dateO_end, dateO_total, address, t1, t2, t3, comment, `status`) 
                VALUE(:running, :user_id, :cat, :p1, :p2, :date_begin, :date_end, :date_total, 
                    :due, :dateO_begin, :dateO_end, :dateO_total, :address, :t1, :t2, :t3, :comment, :status);";
        $query = $conn->prepare($sql);
        $query->bindParam(':running', $running, PDO::PARAM_INT);
        $query->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $query->bindParam(':cat', $cat, PDO::PARAM_STR);
        $query->bindParam(':p1', $p1, PDO::PARAM_STR);
        $query->bindParam(':p2', $p2, PDO::PARAM_STR);
        $query->bindParam(':date_begin', $date_begin, PDO::PARAM_STR);
        $query->bindParam(':date_end', $date_end, PDO::PARAM_INT);
        $query->bindParam(':date_total', $date_total, PDO::PARAM_INT);
        $query->bindParam(':due', $due, PDO::PARAM_STR);
        $query->bindParam(':dateO_begin', $dateO_begin, PDO::PARAM_STR);
        $query->bindParam(':dateO_end', $dateO_end, PDO::PARAM_STR);
        $query->bindParam(':dateO_total', $dateO_total, PDO::PARAM_STR);
        $query->bindParam(':address', $address, PDO::PARAM_STR);
        $query->bindParam(':t1', $t1, PDO::PARAM_STR);
        $query->bindParam(':t2', $t2, PDO::PARAM_STR);
        $query->bindParam(':t3', $t3, PDO::PARAM_STR);
        $query->bindParam(':comment', $comment, PDO::PARAM_STR);
        $query->bindParam(':status', $status, PDO::PARAM_INT);
        $query->execute();

        http_response_code(200);
        echo json_encode(array('status' => true, 'message' => 'สำเร็จ...insert', 'datas' => $leave));
        exit;
    }
    
    if($leave->act == 'update'){
        $running = $leave->running;
        $user_id = $uid;
        $cat    = $leave->cat;
        $p1     = $leave->p1;
        $p2     = $leave->p2;
        $date_begin = $leave->date_begin;
        $date_end   = $leave->date_end;
        $date_total = $leave->date_total;
        $due        = $leave->due;
        $dateO_begin = $leave->dateO_begin;
        $dateO_end  = $leave->dateO_end;
        $dateO_total = $leave->dateO_total;
        $address    = $leave->address;
        $t1 = $leave->t1;
        $t2 = $leave->t2;
        $t3 = $leave->t3;
        $comment = $leave->comment;
        $status = 1;
        $id = $leave->id;

        $sql = "UPDATE bila SET
                    running = :running,
                    user_id = :user_id,
                    cat = :cat,
                    p1 = :p1,
                    p2 = :p2,
                    date_begin = :date_begin,
                    date_end = :date_end,
                    date_total = :date_total,
                    due = :due,
                    dateO_begin = :dateO_begin,
                    dateO_end = :dateO_end,
                    dateO_total = :dateO_total,
                    address = :address,
                    t1 = :t1,
                    t2 = :t2,
                    t3 = :t3,
                    comment = :comment,
                    status = :status
                WHERE id = :id";

        $query = $conn->prepare($sql);

        // Bind parameters as before
        $query->bindParam(':running', $running, PDO::PARAM_INT);
        $query->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $query->bindParam(':cat', $cat, PDO::PARAM_STR);
        $query->bindParam(':p1', $p1, PDO::PARAM_STR);
        $query->bindParam(':p2', $p2, PDO::PARAM_STR);
        $query->bindParam(':date_begin', $date_begin, PDO::PARAM_STR);
        $query->bindParam(':date_end', $date_end, PDO::PARAM_INT);
        $query->bindParam(':date_total', $date_total, PDO::PARAM_INT);
        $query->bindParam(':due', $due, PDO::PARAM_STR);
        $query->bindParam(':dateO_begin', $dateO_begin, PDO::PARAM_STR);
        $query->bindParam(':dateO_end', $dateO_end, PDO::PARAM_STR);
        $query->bindParam(':dateO_total', $dateO_total, PDO::PARAM_STR);
        $query->bindParam(':address', $address, PDO::PARAM_STR);
        $query->bindParam(':t1', $t1, PDO::PARAM_STR);
        $query->bindParam(':t2', $t2, PDO::PARAM_STR);
        $query->bindParam(':t3', $t3, PDO::PARAM_STR);
        $query->bindParam(':comment', $comment, PDO::PARAM_STR);
        $query->bindParam(':status', $status, PDO::PARAM_INT);
        $query->bindParam(':id', $id, PDO::PARAM_INT);

        // Execute the update query and check for errors
        if ($query->execute()) {
            // The update was successful
            http_response_code(200);
            echo json_encode(array('status' => true, 'message' => 'Update successful!'));
            exit;
        } else {
            // An error occurred
            http_response_code(200);
            echo json_encode(array('status' => false, 'message' => 'Update failed: '. implode(", ", $query->errorInfo()), 'datas' => $leave));
            exit;
        }

    }



    http_response_code(200);
    echo json_encode(array('status' => true, 'message' => 'สำเร็จ', 'datas' => $leave));
    exit;
}