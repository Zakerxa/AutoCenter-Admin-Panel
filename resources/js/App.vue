<template>

    <div v-if="loading" class="loading">
       <!-- Show Loading if Data not ready yet -->
       <div class="row d-flex align-items-center p-0 m-0" style="height:100vh;width:100vw;">
           <div class="col-12 text-center p-0">
               <h3 class="fw-bold">{{loadingIcon}}</h3>
           </div>
       </div>
    </div>
    <div v-else>
        <router-view/>
    </div>


</template>

<script>
export default {
    data(){
        return{
            loadingIcon : '<ThidaAutoCenter/>',
            loading : true,
            admin : ''
        }
    },
        watch:{
      $route (to, from){
        this.admin = localStorage.getItem('bearer');
      }
    },
    mounted(){
       document.addEventListener('DOMContentLoaded', () => setTimeout(() => {
        this.loading = false
       }, 200));
       this.$nextTick(()=> console.log("Render has been loaded"));
    }
}
</script>

<style>
.loading{
    background: #fff;
    z-index:10;
    position:fixed;
    height:100vh;
    width:100vw;
}
</style>
