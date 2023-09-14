const app = Vue.createApp({
    data() {
        return {
            message: "Hello World!" ,
            datas: '',
            profile: '',
            leave_cat :['ลาป่วย','ลากิจส่วนตัว','ลาพักผ่อน'],
            leave : {
                id:'',
                cat:'ลาป่วย',
                date_begin:'',
                date_end:'',
                date_total:0,
                due:'',
                dateO_begin:'',
                dateO_end:'',
                dateO_total:0,
                address:'',
                t1:0,
                t2:0,
                t3:0,
                comment:'',
                po:'',
                bigbooss:'',
                date_create:'',
                act:'',
            },
            leave_old : '',
            st:['รอดำเนินการ','อยู่ระหว่างตรวจสอบ','รออนุมัติ','เรียบร้อย','ยกเลิก'],
            loading: false,
            sms: 'sms',
        }
    },
    mounted(){
        this.get_data() 
    },
    watch:{
        // leave(){
        //     this.sms = this.leave.cat
        // } 
    },
    methods: {
        get_data() {
            this.loading = true;
            axios.get('./api/index/bila.php')
                .then(response => {
                    // handle success
                    this.datas = response.data.datas.bilas
                    this.profile = response.data.datas.profile
                })
                .catch(error =>  {
                    // handle error
                    console.log(error);
                })
                .finally( ()=> {
                    // always executed
                    this.loading = false;
                });
        },
        get_leave_old(){
            this.loading = true;
            this.leave.act = 'leave_old'
            axios.post('./api/index/bila.php',{leave:this.leave})
                .then(response => {
                    // handle success
                    this.leave_old = response.data.datas
                    // ตัวอย่างการใช้งาน
                    
                    if(this.leave.cat === 'ลาป่วย' || this.leave.cat === 'ลากิจส่วนตัว'){
                        this.leave.dateO_begin = this.leave_old.date_begin
                        this.leave.dateO_end = this.leave_old.date_end
                        this.leave.dateO_total = parseFloat(this.leave_old.date_total)
                        this.leave.due = ''
                        this.leave.p1 = 0
                        this.leave.p2 = 0
                        this.leave.t1 = parseFloat(this.leave_old.t3)
                        this.leave.address = this.leave_old.address
                    }
                    if(this.leave.cat === 'ลาพักผ่อน'){
                        this.leave.dateO_begin = this.leave_old.date_begin
                        this.leave.dateO_end = this.leave_old.date_end
                        this.leave.dateO_total = parseFloat(this.leave_old.date_total)
                        this.leave.due = ''
                        this.leave.p1 = parseFloat(this.leave_old.p1)
                        this.leave.p2 = this.leave.p1 + 10
                        this.leave.t1 = parseFloat(this.leave_old.t3)
                        this.leave.address = this.leave_old.address
                    }
                    this.leave.act = 'insert'
                })
                .catch(error =>  {
                    // handle error
                    console.log(error);
                })
                .finally( ()=> {
                    // always executed
                    this.loading = false;
                });
        },
        
        leave_cat_ch(){
            this.get_leave_old()
            this.leave.date_begin = '';
            this.leave.date_end = '';
        },
        input_date_begin_end_ch(){
            if(!(this.leave.date_begin == '' || this.leave.date_end == '' ||this.leave.date_begin == null ||this.leave.date_end == null)){
                this.leave.date_total = 0
                var date1 = new Date(this.leave.date_begin);
                var date2 = new Date(this.leave.date_end);
    
                var diffTime = date2.getTime() - date1.getTime();
                var hld = this.countWeekendHolidays(this.leave.date_begin,this.leave.date_end) //วันเสาร์อาทิตย์
                this.leave.date_total = (diffTime / (1000 * 3600 * 24)) + 1 - hld;

                
                if(this.leave.date_total < 1){
                    if(this.leave.date_begin >= this.leave.date_end){
                        this.leave.date_end = this.leave.date_begin;
                        this.leave.date_total = 1
                    }
                    if(this.leave.date_begin <= this.leave.date_end){
                        this.leave.date_begin = this.leave.date_end; 
                        this.leave.date_total = 1
                    }
                    // alert('ลาถึงวันที่ ต้องมากกว่าวันเริ่มต้น')
                }
                this.fiscalyear2();
                // console.log(this.leave.date_total)
            }else{
                this.leave.date_total = 0
            }
        },
        leave_new(){
            this.get_leave_old()
            this.leave.act = 'insert'
            this.leave.date_begin = ''
            this.leave.date_end  = ''
            this.leave.date_total = 0
            this.$refs.bt_open_modal.click()
        },
        b_update(idx){
            console.log('b_update.' + idx)
            this.leave = this.datas[idx]
            this.leave.act = 'update'
            this.$refs.bt_open_modal.click()

        },
        onSubmit(){
            this.loading = true;
            if(!(this.leave.date_begin == '' || this.leave.date_end == '') ){
                axios.post('./api/index/bila.php',{leave:this.leave})
                    .then(response => {
                        // handle success
                        this.$refs.bt_close_modal.click()
                        this.$refs.bt_leave.click()
                        this.get_data()
                        // alert('ok');
                    })
                    .catch(error =>  {
                        // handle error
                        console.log(error);
                    })
                    .finally( ()=> {
                        // always executed
                        this.loading = false;
                    });
            }else{
                alert('กรุณากรอกวันที่ลา');
                this.loading = false;
            }

        },
        odCancel(idx){
            // this.loading = true;
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, cancel it!'
              }).then((result) => {
                if (result.isConfirmed) {

                    this.leave = this.datas[idx]
                    this.leave.act = 'cancel'
                    axios.post('./api/index/bila.php',{leave:this.leave})
                        .then(response => {
                            // handle success
                            this.$refs.bt_leave.click()
                            this.get_data()
                            // alert('ok');
                        })
                        .catch(error =>  {
                            // handle error
                            console.log(error);
                        })
                        .finally( ()=> {
                            // always executed
                            this.loading = false;
                        });

                        Swal.fire(
                            'Cancel!',
                            'Your file has been deleted.',
                            'success'
                        )
        
                }
              })
              
            

        },
        bt_close_modal(){
            this.get_data()
            this.$refs.bt_close_modal.click()
        },
        print(idx){
            this.loading = true;
            axios.post('./api/index/bila_print.php',
                {
                    leave:this.datas[idx],
                    profile:this.profile
                })
                .then(response => {
                    // handle success
                    if(response.data.status){
                        url = response.data.url
                        window.open('./web/viewer.html?file='+url,'_blank')
                    }
                })
                .catch(error =>  {
                    // handle error
                    console.log(error);
                })
                .finally( ()=> {
                    // always executed
                    this.loading = false;
                });
        },
        convertToThaiDate(gregorianDate) {
            // Parse the Gregorian date
            const parts = gregorianDate.split('-');
            const year = parseInt(parts[0]);
            const month = parseInt(parts[1]);
            const day = parseInt(parts[2]);
          
            // Add 543 years to the year
            const thaiYear = year - 2500 + 543;
          
            // Define an array of Thai month names
            const thaiMonths = [
              'มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน',
              'พฤษภาคม', 'มิถุนายน', 'กรกฎาคม', 'สิงหาคม',
              'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'
            ];
            const thaiMonthsAbbreviated = [
                'ม.ค.', 'ก.พ.', 'มี.ค.', 'เม.ย.',
                'พ.ค.', 'มิ.ย.', 'ก.ค.', 'ส.ค.',
                'ก.ย.', 'ต.ค.', 'พ.ย.', 'ธ.ค.'
              ];
          
            // Format the Thai date
            const thaiDate = `${day} ${thaiMonthsAbbreviated[month - 1]} ${thaiYear}`;
          
            return thaiDate;
        },
        fiscalyear(gregorianDate){
            const parts = gregorianDate.split('-');
            const year = parseInt(parts[0]);
            const month = parseInt(parts[1]);
            const thaiYear = year + 543;

            if(month >= 10){
                return thaiYear + 1
            }else{
                return thaiYear ;

            }
            
        },
        fiscalyear2(){
            const partsO = this.leave.dateO_begin.split('-');
            const yearO = parseInt(partsO[0]);
            const monthO = parseInt(partsO[1]);
            const thaiYearO = yearO + 543;

            const parts = this.leave.date_begin.split('-');
            const year = parseInt(parts[0]);
            const month = parseInt(parts[1]);
            const thaiYear = year + 543;

            if(monthO >= 10){
                yO = thaiYearO + 1
            }else{
                yO = thaiYearO ;

            }
            if(month >= 10){
                yn = thaiYear + 1
            }else{
                yn = thaiYear ;

            }
            if(yO !== yn){
                this.leave.t1 = 0;
            }else{
                this.leave.t1 = this.leave_old.t3;
            }
            this.leave.m2 = yn;
            this.leave.m1 = yO;
            
        },
        countWeekendHolidays(startDate, endDate) {
            startDate = new Date(startDate);
            endDate = new Date(endDate);

            let holidays = 0;
            let currentDate = startDate;

            while (currentDate <= endDate) {
                const dayOfWeek = currentDate.getDay(); // หาวันของสัปดาห์ (0=อาทิตย์, 1=จันทร์, ..., 6=เสาร์)

                if (dayOfWeek === 0 || dayOfWeek === 6) { // ตรวจสอบว่าเป็นวันอาทิตย์หรือเสาร์
                holidays++;
                }

                currentDate.setDate(currentDate.getDate() + 1); // เลื่อนไปวันถัดไป
            }

            return holidays;
        }
        
    }
})

app.mount('#app')