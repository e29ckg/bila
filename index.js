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
                date_total:'',
                due:'',
                dateO_begin:'',
                dateO_end:'',
                dateO_total:'',
                address:'',
                t1:'',
                t2:'',
                t3:'',
                comment:'',
                po:'',
                bigbooss:'',
                date_create:'',
                act:'',
            },
            leave_old : '',
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
                    if(this.leave.cat === 'ลาป่วย' || this.leave.cat === 'ลากิจส่วนตัว'){
                        this.leave.dateO_begin = this.leave_old.date_begin
                        this.leave.dateO_end = this.leave_old.date_end
                        this.leave.dateO_total = this.leave_old.date_total
                        this.leave.due = ''
                        this.leave.p1 = 0
                        this.leave.p2 = 0
                        this.leave.t1 = parseFloat(this.leave_old.t3)
                        this.leave.address = this.leave_old.address
                    }
                    if(this.leave.cat === 'ลาพักผ่อน'){
                        this.leave.dateO_begin = ''
                        this.leave.dateO_end = ''
                        this.leave.dateO_total = ''
                        this.leave.due = ''
                        this.leave.p1 = parseFloat(this.leave_old.p1)
                        this.leave.p2 = (parseFloat(this.leave_old.p1) + 10) - parseFloat(this.leave_old.t3)
                        this.leave.t1 = parseFloat(this.leave_old.t3)
                        this.leave.address = this.leave_old.address
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
        
        leave_cat_ch(){
            this.get_leave_old()
        },
        input_date_begin_end_ch(){
            if(this.leave.date_begin !== '' && this.leave.date_end !== ''){
                var date1 = new Date(this.leave.date_begin);
                var date2 = new Date(this.leave.date_end);
    
                var diffTime = date2.getTime() - date1.getTime();
                this.leave.date_total = (diffTime / (1000 * 3600 * 24)) + 1;

                if(this.leave.date_total < 1){
                    this.leave.date_begin = ''
                    this.leave.date_end  = ''
                    this.leave.date_total = 0
                    alert('ลาถึงวันที่ ต้องมากกว่าวันเริ่มต้น')
                }
                console.log(this.leave.date_total)
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
            axios.post('./api/index/bila.php',{leave:this.leave})
                .then(response => {
                    // handle success
                    this.$refs.bt_close_modal.click()
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
            }
            
            return thaiYear ;
        }
    }
})

app.mount('#app')