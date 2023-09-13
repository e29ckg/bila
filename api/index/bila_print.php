<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET,HEAD,OPTIONS,POST,PUT");
header("Access-Control-Allow-Headers: Content-Type, Accept");
header("Content-Type: application/json; charset=utf-8");

include "../../config/connect.php";
include "../../config/function.php";

require_once '../../vendor/autoload.php';

$mpdf = new \Mpdf\Mpdf([
	'default_font_size' => 16,
	'default_font' => 'sarabun'
]);
$mpdf->useDictionaryLBR = false;

$data = json_decode(file_get_contents("php://input"));

if(!isset($_SESSION['__id'])){
    http_response_code(200);
    echo json_encode(array('status' => false, 'message' => 'Login Please..'));
    exit;
}

$uid = $_SESSION['__id'];
// $uid = 19;

// The request is using the POST method

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(!isset($data)){
        http_response_code(200);
        echo json_encode(array('status' => false, 'message' => 'No Data..'));
        exit;
    }
    $leave = $data->leave;
    $profile = $data->profile;

    $id = $leave->id;
    $running = $leave->running;
    $cat = $leave->cat;
    $date_begin = $leave->date_begin;
    $date_end = $leave->date_end;
    $date_total = $leave->date_total;
    $dateO_begin = $leave->dateO_begin;
    $dateO_end = $leave->dateO_end;
    $dateO_total = $leave->dateO_total;
    $due = $leave->due;
    $comment = $leave->comment;
    $p1 = $leave->p1;
    $p2 = $leave->p2;
    $po = $leave->po;
    $t1 = $leave->t1;
    $t2 = $leave->t2;
    $t3 = $leave->t3;
    $address = $leave->address;
    $bigboss = $leave->bigboss;
    $user_id = $leave->user_id;
    $date_create = $leave->date_create;
    
    
    $mpdf->SetTitle('bila-'.$running);
    $mpdf->SetAuthor('pkkjc');
    $mpdf->SetSubject('pkkjc-leave');
    $mpdf->SetCreator('pkkjc.coj');
    $mpdf->SetKeywords('pkkjc');
    
    $mpdf->AddPage(); 
    
    $name_file = $leave->running.'.pdf';
    $output = '../../uploads/bila/'.$name_file;

    $tm = '../../uploads/bila/template/TP112.pdf'; //ลาพักผ่อน    
    
    if($leave->cat == 'ลาป่วย' || $leave->cat == 'ลากิจส่วนตัว'){
        $tm = '../../uploads/bila/template/TP111.pdf'; //ลาป่วย ลากิจส่วนตัว
    }
    
    $pagecount = $mpdf->setSourceFile($tm);
    $tplId = $mpdf->importPage($pagecount);
    
    $actualsize = $mpdf->useTemplate($tplId);

    $to = 'ผู้อำนวยการสำนักงานประจำศาลเยาวชนและครอบครัวจังหวัดประจวบคีรีขันธ์';
    $bb_stamp = true ;
    if($profile->dep == 'ผู้อำนวยการฯ' || $profile->workgroup =='ผู้พิพากษา'){
        $to = 'ผู้พิพากษาหัวหน้าศาลเยาวชนและครอบครัวจังหวัดประจวบคีรีขันธ์';
    }
    if($profile->dep == 'ผู้พิพากษาหัวหน้าศาลเยาวชนและครอบครัวจังหวัดประจวบคีรีขันธ์'){
        $to = 'อธิบดีผู้พิพากษาภาค 7';
        $bb_stamp = false ;
    }

    //Test
    // $data_text = '<div style="text-align:left; border-style: solid; border-width: 2px;">1 พฤศจิกายน 2566</div>';
    // $mpdf->WriteFixedPosHTML($data_text, 20, 109, 165, 20, 'auto')

    if($leave->cat == 'ลาป่วย' || $leave->cat == 'ลากิจส่วนตัว'){
        $data_text = '<div style="text-align:right;">'.$leave->running.'</div>';
        $mpdf->WriteFixedPosHTML($data_text, 175, 24, 14, 15, 'auto'); 
    
        //วันที่เขียน
        $data_text = '<div style="text-align:center;">'.DateThai_D($date_create).'</div>';
        $mpdf->WriteFixedPosHTML($data_text, 110, 43, 25, 20, 'auto'); 
        $data_text = '<div style="text-align:center; ">'.DateThai_M($date_create).'</div>';
        $mpdf->WriteFixedPosHTML($data_text, 135, 43, 35, 20, 'auto'); 
        $data_text = '<div style="text-align:center; ">'.DateThai_Y($date_create).'</div>';
        $mpdf->WriteFixedPosHTML($data_text, 172, 43, 20, 20, 'auto'); 
        //เรื่อง ขออนุญาต
        $data_text = '<div style="text-align:left; ">ขออนุญาต'.$leave->cat.'</div>';
        $mpdf->WriteFixedPosHTML($data_text, 32, 52 , 63, 20, 'auto');         
        //เรียน        
        $data_text = '<div style="text-align:left; ">'.$to.'</div>';
        $mpdf->WriteFixedPosHTML($data_text, 32, 62 , 200, 20, 'auto');         
        //ข้าพเจ้า  ตำแหน่ง
        $data_text = '<div style="text-align:center;">'.$profile->fname.$profile->name.' '.$profile->sname.'</div>';
        $mpdf->WriteFixedPosHTML($data_text, 56, 72 , 53, 20, 'auto'); 
        $data_text = '<div style="text-align:center; ">'.$profile->dep.'ตำแหน่ง</div>';
        $mpdf->WriteFixedPosHTML($data_text, 125, 72, 63, 20, 'auto'); 
        //สังกัด
        $data_text = '<div style="text-align:left;">สำนักงานประจำศาลเยาวชนและครอบครัวจังหวัดประจวบคีรีขันธ์</div>';
        $mpdf->WriteFixedPosHTML($data_text, 40, 80, 150, 20, 'auto'); 

        //ประเภทการลา
        if($leave->cat == 'ลาป่วย'){
            $data_text = '<div style="text-align:center;"> / </div>';
            $mpdf->WriteFixedPosHTML($data_text, 45, 89, 8, 20, 'auto'); 

            $data_text = '<div style="text-align:center;"> / </div>';
            $mpdf->WriteFixedPosHTML($data_text, 45, 122, 8, 20, 'auto'); 
        }
        if($leave->cat == 'ลากิจส่วนตัว'){
            $data_text = '<div style="text-align:center;"> / </div>';
            $mpdf->WriteFixedPosHTML($data_text, 45, 98, 8, 20, 'auto'); 

            $data_text = '<div style="text-align:center;"> / </div>';
            $mpdf->WriteFixedPosHTML($data_text, 79, 122, 8, 20, 'auto'); 
        }
        if($leave->cat == 'ลาคลอดบุตร'){
            $data_text = '<div style="text-align:center; "> / </div>';
            $mpdf->WriteFixedPosHTML($data_text, 45, 106, 8, 20, 'auto'); 

            $data_text = '<div style="text-align:center;"> / </div>';
            $mpdf->WriteFixedPosHTML($data_text, 110, 122, 8, 20, 'auto'); 
        }

        //เนื่องจาก
        $data_text = '<div style="text-align:left; ">'.$leave->due.'</div>';
        $mpdf->WriteFixedPosHTML($data_text, 105, 97, 95, 20, 'auto'); 
        //วันลาตั้งแต่
        $data_text = '<div style="text-align:center; ">'.DateThai_full($leave->date_begin).'</div>';
        $mpdf->WriteFixedPosHTML($data_text, 35, 113, 49, 20, 'auto'); 
        $data_text = '<div style="text-align:center; ">'.DateThai_full($leave->date_end).'</div>';
        $mpdf->WriteFixedPosHTML($data_text, 95, 113, 49, 20, 'auto'); 
        $data_text = '<div style="text-align:center;">'.$leave->date_total.'</div>';
        $mpdf->WriteFixedPosHTML($data_text, 160, 113, 20, 20, 'auto'); 
        
        //วันลาครั้งสุดท้าย
        $data_text = '<div style="text-align:center; ">'.DateThai_full($leave->dateO_begin).'</div>';
        $mpdf->WriteFixedPosHTML($data_text, 35, 130, 49, 20, 'auto'); 
        $data_text = '<div style="text-align:center; ">'.DateThai_full($leave->dateO_end).'</div>';
        $mpdf->WriteFixedPosHTML($data_text, 95, 130, 49, 20, 'auto'); 
        $data_text = '<div style="text-align:center;">'.$leave->dateO_total.'</div>';
        $mpdf->WriteFixedPosHTML($data_text, 160, 130, 20, 20, 'auto'); 
        //ติดต่อข้าพเจาได้ที่
        $data_text = '<div style="text-align:center; ">'.$leave->address.'</div>';
        $mpdf->WriteFixedPosHTML($data_text, 65, 137, 128, 20, 'auto'); 
    
        //ติดต่อข้าพเจาได้ที่2
        // $data_text = '<div style="text-align:left; border-style: solid; border-width: 2px;">1 พฤศจิกายน 2566</div>';
        // $mpdf->WriteFixedPosHTML($data_text, 20, 109, 165, 20, 'auto'); 
        
        //หมายเหตุ
        if($leave->comment != ''){
            $data_text = '<div style="text-align:left;">หมายเหตุ : </div>';
            $mpdf->WriteFixedPosHTML($data_text, 20, 151, 165, 20, 'auto'); 
        }
        //ขอแสดงความนับถือ
        $data_text = '<div style="text-align:center;">('.$profile->fname.$profile->name.' '.$profile->sname.')</div>';
        $mpdf->WriteFixedPosHTML($data_text, 125, 177, 59, 20, 'auto'); 
        
        //สถิติการลา
        if($leave->cat == 'ลาป่วย'){
            $data_text = '<div style="text-align:center;">'.$leave->t1.'</div>';
            $mpdf->WriteFixedPosHTML($data_text, 45, 209, 25, 20, 'auto'); 
            $data_text = '<div style="text-align:center;">'.$leave->t2.'</div>';
            $mpdf->WriteFixedPosHTML($data_text, 65, 209, 25, 20, 'auto'); 
            $data_text = '<div style="text-align:center;">'.$leave->t3.'</div>';
            $mpdf->WriteFixedPosHTML($data_text, 86, 209, 25, 20, 'auto');         
        }
        if($leave->cat == 'ลากิจส่วนตัว'){
            $data_text = '<div style="text-align:center;">'.$leave->t1.'</div>';
            $mpdf->WriteFixedPosHTML($data_text, 45, 217, 25, 20, 'auto'); 
            $data_text = '<div style="text-align:center;">'.$leave->t2.'</div>';
            $mpdf->WriteFixedPosHTML($data_text, 65, 217, 25, 20, 'auto'); 
            $data_text = '<div style="text-align:center;">'.$leave->t3.'</div>';
            $mpdf->WriteFixedPosHTML($data_text, 86, 217, 25, 20, 'auto');         
        }
        if($leave->cat == 'ลาคลอดบุตร'){
            $data_text = '<div style="text-align:center;">'.$leave->t1.'</div>';
            $mpdf->WriteFixedPosHTML($data_text, 45, 224, 25, 20, 'auto'); 
            $data_text = '<div style="text-align:center;">'.$leave->t2.'</div>';
            $mpdf->WriteFixedPosHTML($data_text, 65, 224, 25, 20, 'auto'); 
            $data_text = '<div style="text-align:center;">'.$leave->t3.'</div>';
            $mpdf->WriteFixedPosHTML($data_text, 86, 224, 25, 20, 'auto');         
        }
    }

    if($leave->cat == 'ลาพักผ่อน'){
        $data_text = '<div style="text-align:right;">'.$leave->running.'</div>';
        $mpdf->WriteFixedPosHTML($data_text, 175, 19, 14, 15, 'auto'); 
    
        //วันที่เขียน
        $data_text = '<div style="text-align:center;">'.DateThai_D($date_create).'</div>';
        $mpdf->WriteFixedPosHTML($data_text, 110, 39, 25, 20, 'auto'); 
        $data_text = '<div style="text-align:center; ">'.DateThai_M($date_create).'</div>';
        $mpdf->WriteFixedPosHTML($data_text, 135, 39, 35, 20, 'auto'); 
        $data_text = '<div style="text-align:center; ">'.DateThai_Y($date_create).'</div>';
        $mpdf->WriteFixedPosHTML($data_text, 172, 39, 20, 20, 'auto'); 
        //เรียน        
        $data_text = '<div style="text-align:left; ">'.$to.'</div>';
        $mpdf->WriteFixedPosHTML($data_text, 32, 58 , 200, 20, 'auto'); 
        //ข้าพเจ้า  ตำแหน่ง
        $data_text = '<div style="text-align:center;">'.$profile->fname.$profile->name.' '.$profile->sname.'</div>';
        $mpdf->WriteFixedPosHTML($data_text, 56, 68, 63, 20, 'auto'); 
        $data_text = '<div style="text-align:center;">'.$profile->dep.'</div>';
        $mpdf->WriteFixedPosHTML($data_text, 130, 68, 63, 20, 'auto'); 
        //สังกัด
        $data_text = '<div style="text-align:left;">สำนักงานประจำศาลเยาวชนและครอบครัวจังหวัดประจวบคีรีขันธ์</div>';
        $mpdf->WriteFixedPosHTML($data_text, 40, 76, 150, 20, 'auto'); 
        //วันลาพักผ่อนสะสม
        $data_text = '<div style="text-align:center;">'.$leave->p1.'</div>';
        $mpdf->WriteFixedPosHTML($data_text, 52, 84, 20, 20, 'auto'); 
        $data_text = '<div style="text-align:center;">'.$leave->p2.'</div>';
        $mpdf->WriteFixedPosHTML($data_text, 160, 84, 20, 20, 'auto'); 
        //วันลาตั้งแต่
        $data_text = '<div style="text-align:center;">'.DateThai_full($leave->date_begin).'</div>';
        $mpdf->WriteFixedPosHTML($data_text, 55, 94, 40, 20, 'auto'); 
        $data_text = '<div style="text-align:center;">'.DateThai_full($leave->date_end).'</div>';
        $mpdf->WriteFixedPosHTML($data_text, 107, 94, 39, 20, 'auto'); 
        $data_text = '<div style="text-align:center;">'.$leave->date_total.'</div>';
        $mpdf->WriteFixedPosHTML($data_text, 163, 94, 20, 20, 'auto'); 
        //ติดต่อข้าพเจาได้ที่
        $data_text = '<div style="text-align:center;">'.$leave->address.'</div>';
        $mpdf->WriteFixedPosHTML($data_text, 65, 103, 128, 20, 'auto'); 
    
        //ติดต่อข้าพเจาได้ที่2
        // $data_text = '<div style="text-align:left; border-style: solid; border-width: 2px;">1 พฤศจิกายน 2566</div>';
        // $mpdf->WriteFixedPosHTML($data_text, 20, 109, 165, 20, 'auto'); 
        
        //หมายเหตุ
        $data_text = '<div style="text-align:left;">หมายเหตุ : </div>';
        $mpdf->WriteFixedPosHTML($data_text, 20, 116, 165, 20, 'auto'); 
        
        //ขอแสดงความนับถือ
        $data_text = '<div style="text-align:center;">('.$profile->fname.$profile->name.' '.$profile->sname.')</div>';
        $mpdf->WriteFixedPosHTML($data_text, 125, 149, 59, 20, 'auto'); 
        
        //สถิติการลา
        $data_text = '<div style="text-align:center;">'.$leave->t1.'</div>';
        $mpdf->WriteFixedPosHTML($data_text, 20, 185, 25, 20, 'auto'); 
        $data_text = '<div style="text-align:center;">'.$leave->t2.'</div>';
        $mpdf->WriteFixedPosHTML($data_text, 46, 185, 25, 20, 'auto'); 
        $data_text = '<div style="text-align:center;">'.$leave->t3.'</div>';
        $mpdf->WriteFixedPosHTML($data_text, 74, 185, 25, 20, 'auto');         
    }




    
	!empty($_SERVER['HTTPS']) && ('on' == $_SERVER['HTTPS']) ? $uri = 'https://' : $uri = 'http://';
	$uri .= $_SERVER['HTTP_HOST'];
    $uri .= '/bila/uploads/bila/'.$name_file;
	
    
    $mpdf->Output($output);
    
    http_response_code(200);
    echo json_encode(array(
        'status' => true, 
        'massege' => 'สำเร็จ', 
        'url' => $uri,
        'leave' => $leave
    ));
    exit;    
    

    
    http_response_code(200);
    echo json_encode(array('status' => true, 'message' => 'สำเร็จ', 'datas' => $leave));
    exit;
}