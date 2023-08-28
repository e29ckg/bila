<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js" integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V" crossorigin="anonymous"></script>
</head>
<body>

    <div id="app" >
        
        <div class="spinner-border" role="status" v-if="loading">
            <span class="visually-hidden">Loading...</span>
        </div>
        <table class="table">
            <tr v-for="bila,index in datas.bilas">
                <td>{{bila.running}}</td>
                <td>{{bila.cat}}</td>
                <td>{{bila.date_begin}}</td>
                <td>{{bila.date_end}}</td>
                <td>{{bila.date_total}}</td>
                <td></td>
            </tr>
        </table>
        {{datas}}
        {{loading}}
    </div>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
    <script>
    const app = Vue.createApp({
        data() {
            return {
                message: "Hello World!" ,
                datas: '',
                loading: false
            }
        },
        mounted(){
            this.get_data() 
        },
        methods: {
            get_data() {
                this.loading = true;
                axios.get('./api/index/bila.php')
                    .then(response => {
                        // handle success
                        this.datas = response.data.datas
                    })
                    .catch(error =>  {
                        // handle error
                        console.log(error);
                    })
                    .finally( ()=> {
                        // always executed
                        this.loading = false;
                    });
            }
        }
    })

   app.mount('#app')

  </script>
</body>

</html>