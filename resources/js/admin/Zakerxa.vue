<template>
  <div>

      <div class="sidebar-mini layout-fixed">
        <div class="wrapper">

            <adminlteNavSlider @searching="searching" :noti="noti"/>

            <!-- Contect -->
            <div class="content-wrapper">
                <div class="content-header">
                   <Dashboard @searchOver="searchOver" @reading="reading" :search="search"/>
                </div>
            </div>

            <div class="main-footer">
                <div class="row justify-content-center">
                     <div class="col-12 text-center pt-4 pb-4">
                        <small class="text-muted">Copyright @2022 Zakerxa-Portfolio.All rights reserved.</small>
                    </div>
                </div>
            </div>

        </div>
       </div>


  </div>
</template>

<script>
import adminlteNavSlider from './adminlteNavSlider.vue';
import Dashboard from './Dashboard.vue'
export default {
    data () {
        return {
            adminLoading : true,
            search       : '',
            noti         : 0,
            searchend    : '',
        }
    },
    components:{
        adminlteNavSlider,
        Dashboard
    },
    methods: {
        logout () {
            this.$http('api/logout').then(res => {
                if(res.data.response == 'logout'){
                    localStorage.removeItem('bearer');
                    this.$router.push(this.$route.query.redirect || '/login');
                }
            });
        },
        searching(e){
          this.search = e
        },
        searchOver(){
          // Refresh the search key
          this.search = '';
        },
        reading(){
            // Accepting reading cause of noti change
            this.$http.get('/api/clients').then( (response) => this.noti = response.data.noti);
        }
    },
    created () {
        this.$http.get('/api/clients').then( (response) => this.noti = response.data.noti);
    },
    mounted () {
        this.$nextTick(()=>  setTimeout(() => this.adminLoading = false, 300));
        console.log("Zakerxa.vue",localStorage.getItem('bearer'));
    }
}
</script>

<style></style>
