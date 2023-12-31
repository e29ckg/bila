
<?php
include "./core/auth.php";
?>
<!DOCTYPE html>
<html lang="th">
  <head>

    <title>โปรแกรมเขียนใบลา</title>   
    <link rel="shortcut icon" href="./favicon.ico" type="image/x-icon">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;500;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="./node_modules/sweetalert2/dist/sweetalert2.min.css">
    
    <!--
      TemplateMo 570 Chain App Dev
      
      https://templatemo.com/tm-570-chain-app-dev
      
    -->
    
    <!-- Additional CSS Files -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
    <link rel="stylesheet" href="assets/css/templatemo-chain-app-dev.css">
    <link rel="stylesheet" href="assets/css/animated.css">
    <link rel="stylesheet" href="assets/css/owl.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    
    <!-- Bootstrap core CSS -->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Prompt&display=swap" rel="stylesheet">
    <style>
      body {font-family: 'Prompt', sans-serif;}
      .nav-link {
        padding: 0;
      }
      .background-header .nav-link a:hover {
      color: #4a33bf !important;
    }
    </style>

</head>

<body>
  <div id="app" >
  
  <!-- ***** Preloader Start ***** -->
  <div id="js-preloader" class="js-preloader" v-if="loading">
    <div class="preloader-inner">
      <span class="dot"></span>
      <div class="dots">
        <span></span>
        <span></span>
        <span></span>
      </div>
    </div>
  </div>
  <!-- ***** Preloader date_end ***** -->
 
  <div id="top">

  </div>
  <!-- ***** Header Area Start ***** -->
  <header class="header-area header-sticky wow slideInDown" data-wow-duration="0.75s" data-wow-delay="0s">
    <div class="container">
      <div class="row">
        <div class="col-12">
          <nav class="main-nav">
            <!-- ***** Logo Start ***** -->
            <a href="index.html" class="logo">
              <img src="assets/images/bila logo.png" alt="chain App Dev">
            </a>
            <!-- ***** Logo date_end ***** -->
            <!-- ***** Menu Start ***** -->
            <ul class="nav">
              <li class="nav-item">
                <a href="#top" class="nav-link active">หน้าแรก</a>
              </li>
              <li class="nav-item">
                  <a  href="#" class="nav-link" @click="leave_new()" >
                    <i class="far fa-edit"></i> เขียนใบลา
                  </a>
              </li>               
              <li class="nav-item">
                <a class="nav-link" href="#leave" ref="bt_leave">ประวัติการลา</a>
              </li>
              <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                  <i class="far fa-edit"></i> {{profile.fname + profile.name + ' ' + profile.sname}}
                </a>
                <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                  <!-- <li><a class="dropdown-item" href="#">Action</a></li> -->
                  <!-- <li><a class="dropdown-item" href="#">Another action</a></li> -->
                  <!-- <li><hr class="dropdown-divider"></li> -->
                  <li><a href="./core/logout.php" >Logout</a></li>
                </ul>
              </li>
              
                            
            </ul>        
            <a class='menu-trigger'>
                <span>Menu</span>
            </a>
            <!-- ***** Menu date_end ***** -->

          </nav>
        </div>
      </div>
    </div>
  </header>
  <!-- ***** Header Area date_end ***** -->  

    <!-- Button trigger modal -->
    <div class="row">       
      <button hidden class=" "data-bs-toggle="modal" data-bs-target="#exampleModal" ref="bt_open_modal" >bt</button>
      <!-- Modal -->
      <div class="modal fade" id="exampleModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">บันทึกการลา @ {{profile.name}} : {{leave.cat}}</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" @click="bt_close_modal"></button>
            </div>
            <div class="modal-body">
              <form @submit.prevent="onSubmit()">
                <div class="input-group mb-5">
                  <label class="input-group-text" for="cat">เลือกประเภทการลา</label>               
                  <select class="form-select" id="cat" v-model="leave.cat" @change="leave_cat_ch()">
                    <option v-for="lc in leave_cat" :value="lc">{{lc}}</option>
                  </select>
                  
                </div>
                
                <div class="row mb-3 justify-content-between" v-if="leave.cat == 'ลาพักผ่อน'">
                  <div class="col-2">
                    <label for="p1" >วันลาพักผ่อนสะสม</label>
                    <input type="number" class="form-control" id="p1" placeholder="วันลาพักผ่อนสะสม" v-model="leave.p1">
                  </div>
                  <div class="col-2">
                    <label for="p2">วันลาคงเหลือ</label>
                    <input type="number" disabled class="form-control" id="p2" placeholder="วันลาคงเหลือ" :value="leave.p2 - leave.t1">
                  </div>
                </div>
  
                <div class="row mb-5">
                  <div class="col">
                    <label for="date_begin" >ลาตั้งแต่วันที่</label>
                    <input type="date" class="form-control" id="date_begin" placeholder="ลาตั้งแต่วันที่" v-model="leave.date_begin" @change="input_date_begin_end_ch()">
                  </div>
                  <div class="col">
                    <label for="date_end" >ถึงวันที่</label>
                    <input type="date" class="form-control" id="date_end" placeholder="ถึงวันที่" v-model="leave.date_end" @change="input_date_begin_end_ch()">
                  </div>
                  <div class="col-2">
                    <label for="date_total">จำนวนวัน</label>
                    <input type="number" min="0" class="form-control" id="date_total" placeholder="จำนวนวัน" v-model="leave.date_total">
                  </div>
                </div>
  
                <div v-if="leave.cat == 'ลาป่วย' || leave.cat == 'ลากิจส่วนตัว'">
                  <div class="row mb-5" >
                    <div class="col">
                      <label for="due" >เนื่องจาก</label>
                      <input type="text" class="form-control" id="due" placeholder="เนื่องจาก" v-model="leave.due">
    
                    </div>
                  </div>
                  
                  <div class="row mb-3">
                    <div class="col">
                      <label for="dateO_begin">ลาครั้งสุดท้ายตั้งแต่</label>
                      <input type="date" class="form-control" id="dateO_begin" placeholder="ลาครั้งสุดท้ายตั้งแต่" v-model="leave.dateO_begin">
                    </div>
                    <div class="col">
                      <label for="dateO_end" >ลาครั้งสุดถึงวันที่</label>
                      <input type="date" class="form-control" id="dateO_end" placeholder="ลาครั้งสุดถึงวันที่" v-model="leave.dateO_end">
                    </div>
                    <div class="col-2">
                      <label for="dateO_total">จำนวนวัน</label>
                      <input type="number" min="0" class="form-control" id="dateO_total" placeholder="จำนวนวัน" v-model="leave.dateO_total">
                    </div>
                  </div>
                </div>
  
                <div class="row mb-4">
                  <div class="col-2">
                    <label for="t1">เคยลาแล้ว</label>
                    <input type="number" min="0" class="form-control" placeholder="เคยลาแล้ว" v-model="leave.t1">
                  </div>
                  <div class="col">
                    <label for="address">ระหว่างลาติดต่อได้ที่</label>
                    <input type="text" class="form-control" id="address" placeholder="ระหว่างลาติดต่อได้ที่" v-model="leave.address" >
                  </div>
                  <div class="col">
                    <label for="comment">หมายเหตุ</label>
                    <input type="text" class="form-control" id="comment" placeholder="หมายเหตุ" v-model="leave.comment" >
                  </div>
                </div>
  
  
  
                <!-- {{leave}} -->
                <!-- {{leave_old}} -->
                <div class="row">
                  <button type="submit" class="btn btn-primary">บันทึก</button>
  
                </div>
              </form>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" ref="bt_close_modal" @click="bt_close_modal">Close</button>
              <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
            </div>
          </div>
        </div>
      </div>
    </div>
  

  <div class="main-banner wow fadeIn" data-wow-duration="1s" data-wow-delay="0.5s">
    <div class="container">
      <div class="row">
        <div class="col-lg-12">
          <div class="row">
            <div class="col-lg-6 align-self-center">
              <div class="left-content show-up header-text wow fadeInLeft" data-wow-duration="1s" data-wow-delay="1s">
                <div class="row">
                  <div class="col-lg-12">
                    <h2>โปรแกรมเขียนใบลา</h2>
                    <p>สวัสดี คุณ{{profile.name +' '+ profile.sname}} .</p>
                  </div>
                  <div class="col-lg-12">
                    <div class="white-button first-button scroll-to-section">
                      <a href="#" @click="leave_new()">
                        <i class="far fa-edit"></i> เขียนใบลา 
                      </a>
                    </div>
                    
                    <div class="white-button scroll-to-section ">
                      <a href="#leave">ประวัติการลา <i class="fab fa-apple"></i></a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-6">
              <div class="right-image wow fadeInRight" data-wow-duration="1s" data-wow-delay="0.5s">
                <img src="assets/images/bila.png" alt="">
              </div>
            </div>
          </div>
        </div>
      </div>
      
    </div>
  </div>

  <div id="leave" class="the-clients">
    <div class="container">
      <div class="row">
        <div class="col-lg-8 offset-lg-2">
          <div class="section-heading">
            <h1>ประวัติการลา</h1>
            <img src="assets/images/heading-line-dec.png" alt="">
            <p>..</p>
          </div>
        </div>
        <div class="col-lg-12">
          <table class="table">
            <thead class="text-center">
                <td>รหัสใบลา</td>
                <td>ปีงบประมาณ</td>
                <td>ประเภทการลา</td>
                <td>ระหว่างวันที่</td>
                <td>จำนวนวัน</td>
                <td>สถานะ</td>
                <td>Act</td>
            </thead>
            <tbody>
              <tr v-for="b,index in datas" class="text-center">
                <td>{{b.running}}</td>
                <td>{{fiscalyear(b.date_begin)}}</td>
                <td>{{b.cat}}</td>
                <td>{{convertToThaiDate(b.date_begin)}} - {{convertToThaiDate(b.date_end)}}</td>
                <td>{{b.date_total}}</td>
                <td>{{b.status}}</td>
                <td class="text-start">
                  <button class="btn btn-warning me-2 mb-1" @click="b_update(index)">แก้ไข</button>
                  <button class="btn btn-primary me-2 mb-1" @click="print(index)">พิมพ์</button>
                  <button class="btn btn-block btn-danger" v-if="b.status === 'รอดำเนินการ'" @click="odCancel(index)">ยกเลิกการลา</button>
                </td>
              </tr>

            </tbody>
            
          </table>

        </div>
      </div>
    </div>
  </div>
  
  
  <footer id="newsletter">
    <div class="container">
      
      <div class="row">
        
        <div class="col-lg-12">
          <div class="copyright-text">
            <p>Copyright © 2022 Chain App Dev Company. All Rights Reserved. lo 
          <br>Design: <a href="https://templatemo.com/" target="_blank" title="css templates">TemplateMo</a></p>
          </div>
        </div>
      </div>
    </div>
  </footer>
</div>

  <!-- Scripts -->
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/js/owl-carousel.js"></script>
  <script src="assets/js/animation.js"></script>
  <script src="assets/js/imagesloaded.js"></script>
  <script src="assets/js/popup.js"></script>
  <script src="assets/js/custom.js"></script>
  <script src="./node_modules/sweetalert2/dist/sweetalert2.all.min.js"></script>

  <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
    <script src="./index.js"></script>
</body>
</html>