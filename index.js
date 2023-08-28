const app = Vue.createApp({
    data() {
        return {
            message: "Hello World!" ,
            datas: '',
            leave_cat :['ลาป่วย','ลากิจส่วนตัว','ลาพักผ่อน'],
            leave : {
                id:'',
                cat:'ลาป่วย',
                begin:'',
                end:'',
                pp_ss_num:'',
                pp_ss_limit:'',
                l_num:'',
            },
            leave_old : {},
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
                    this.test()
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
        get_leave_old(){},
        leave_cat_ch(){},
        leave_begin_end_ch(){
            if(this.leave.begin !== '' && this.leave.end !== ''){
                var date1 = new Date(this.leave.begin);
                var date2 = new Date(this.leave.end);
    
                var diffTime = date2.getTime() - date1.getTime();
                this.leave.l_num = (diffTime / (1000 * 3600 * 24)) + 1;

                if(this.leave.l_num < 1){
                    this.leave.end  = this.leave.begin
                    alert('ลาถึงวันที่ ต้องมากกว่าวันเริ่มต้น')
                }
                console.log(this.leave.l_num)
            }else{
                this.leave.l_num = 0
            }
        },
        leave_new(){

        },
        onSubmit(){},
        test(){
          console.log('KO.')
        }
    }
})

app.mount('#app')