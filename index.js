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
                    this.datas = response.data.datas
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
        },
        onSubmit(){},
        test(){
          console.log('KO.')
        }
    }
})

app.mount('#app')