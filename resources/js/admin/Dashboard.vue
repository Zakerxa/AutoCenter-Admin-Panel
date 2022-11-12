<template>
    <div class="container-fluid">
        <div class="row">
            <h3 class="p-3 pl-0">Dashboard</h3>
        </div>
        <div class="row" v-show="removeIds.length">
            <div class="col">
                <button class="btn btn-sm btn-outline-dark" @click="selectAllForms()">{{selectAll ? 'Deselect' : 'Select'}} All</button>
            </div>
            <div class="col">
                <button class="btn btn-sm btn-outline-primary ml-1">{{removeIds.length ? 'Selected ' + removeIds.length : ''}}</button>
            </div>
            <div class="col text-end">
              <button class="btn btn-sm btn-outline-danger" @click="delForms">{{removeIds.length >= 2 ? 'Delete All' : 'Delete'}}</button>
            </div>
        </div>

        <!-- Reading Components -->
        <div  v-if="reading" class="row justify-content-center">
            <div class="row p-2" style="background:#fff">
                <div class="col-12 p-1 pl-2">
                    <p class="fw-bold mb-0">Name : {{reader.username}}</p>
                </div>
                <div class="col-12 p-1 pl-2">
                    <p class="fw-bold mb-0">Mail : {{reader.email}}</p>
                </div>
                <div class="col-12 p-1 pl-3 pb-2 border-bottom">
                    <p class="fw-bold mb-0">Tel : {{reader.phone}}</p>
                </div>
                <div class="col-12 pt-2 pb-2">
                    <p class="mb-1 fw-bold">Message</p>
                    {{reader.message}}
                </div>
            </div>

            <div class="col text-center pt-4">
               <p @click="reading=false;vuePaginate(current??null)" class="btn btn-sm btn-outline-primary">Go Back</p>
            </div>
            <div class="col text-center pt-4">
               <p @click="showMailModal(reader)" class="btn btn-sm btn-outline-primary">Reply Mail</p>
            </div>
        </div>

        <!-- All Forms Data -->
        <div v-else class="row">

            <div v-if="forms.length >= 1" class="position-relative">
                <div v-show="readyFormLoading" class="overlayLoading">
                   <div class="row">
                     <img src="/icon/loading.png" style="width:80px;" alt="">
                   </div>
                </div>
                <div class="col-12 border-bottom mailcontainer" v-for="(form, i) in forms" :key="i">
                    <div class="form-check">
                        <input class="form-check-input mt-3" type="checkbox"
                        @change="check($event)"
                        :value="form.id"
                        :id="form.id">
                        <div @click="read(form.id)" class="border-bottom p-2 pt-3">
                            <div class="card-body p-1">
                                <div class="row">
                                    <div class="col-12 col-md-5">
                                      <span class="fw-bold">{{form.username}}</span>
                                      <small class="badge badge-danger ml-2"> {{(form.read==0)? 'New' : ''}}</small>
                                      <small class="float-end text-muted">{{form.created_date}}</small>
                                    </div>
                                    <div class="col-12 col-md-7 order-1">
                                        E-Mail :
                                        <span class="fw-bold">{{ form.email }}</span>
                                    </div>
                                    <div class="d-none d-md-block col-7 text-center ordre-0">
                                       Tel : <span>{{form.phone}}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- No Data Found -->
            <div class="col-12 text-center p-3" v-else>
                <h3 class="pt-5">No Data Found {{search}}</h3>
                <button v-show="removeIds.length" @click="searchOver" class="btn btn-sm btn-outline-dark mt-5">Go Back</button>
            </div>

            <!-- Pagination -->
           <div class="col-12 pt-3 text-center">
              <vue-pagination @event="vuePaginate" :meta-data="paginations" :onSides="1"></vue-pagination>
           </div>
        </div>

        <!-- Modal SuccessForm -->
        <div class="col-12">
           <div class="modal fade" id="mailSuccess" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
              <div class="modal-content boxshadow border-0">
                <div class="modal-header">
                  <h3 class="modal-title fs-5 fw-bold" style="letter-spacing:1px;" id="staticBackdropLabel">
                    <font-awesome-icon icon="fa-solid fa-check-circle" class="text-success" />  Mail Sent
                  </h3>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="row">
            <SendMailFormsVue :currentMail="JSON.parse(JSON.stringify(reader))" :adminMail="mail" @mailSent="mailSent"/>
        </div>
    </div>
</template>

<script>

import SendMailFormsVue from './SendMailForms.vue';
export default {
    data () {
        return {
            endpoint    : 'api/clients?page=',
            perPage     : [5],/* default path => '&per_page=' (Or) change [2,'&UrPath='] */
            paginations : {},
            forms: [],
            reader : [],
            reading : false,
            current : null,
            vsearch : '',

            readyFormLoading : false,

            selectAll: false,
            removeIds: [],
            delIcon  : [],
            // Mailing System
        }
    },
    components:{
      SendMailFormsVue
    },
    props:['search'],
    created(){
        this.startInit();
    },
    methods: {
        vuePaginate(e){
          this.current = e;
          console.log(this.current)
          e ? this.startInit(e[0]+e[1]) : this.startInit();
        },
        startInit(e){
          let endpoint = e??(this.perPage[1]??'&per_page=')+this.perPage[0];
          this.getPaginateWithUsers(endpoint + (this.vsearch ? '&search='+this.vsearch :'')).then(res=>this.insertData(res));
        },
        insertData(res){
            console.log(res);
          //  If something is change Reselect All check
          if(this.removeIds.length >= 1 && this.selectAll)this.selectAllForms();
          // If something is change refresh checkbox
          this.checkBoxDef();
          // When you delete all checkbox last pagination that will refetch data back
          if((!this.vsearch) && (!res.data.length)) {
            console.log("No search & Data");
            this.readyFormLoading = false;
            if(!this.current) {
                this.vsearch = '';
                this.forms = [];
                return;
            }
            if((this.current[0]-1)) this.vuePaginate([this.current[0] - 1,this.current[1]]);
          }

        //   this.vsearch = '';
          this.forms = res.data;
          this.paginations = {
               current_page : res.current_page,
               last_page : res.last_page,
               prev_page_url : res.prev_page_url,
               next_page_url : res.next_page_url,
               per_page      : this.perPage
           }
          this.readyFormLoading = false;
        },
        searchOver(){
            this.vuePaginate(this.current??null);
            this.$emit('searchOver');
        },
        check (e) {
          this.$nextTick(() => {
            let id = e.target.value;
            console.log(id);
            if(e.target.checked)this.removeIds.push(parseInt(id));
            else this.removeIds.remove(parseInt(id));
            this.delIcon = this.removeIds;
          });
        },
        checkBoxDef(e,ctn){
            this.removeIds = [];
            if(this.forms.length >= 1){
               this.forms.map(res=>{
                  document.getElementById(res.id).checked = e??false;
                  if(ctn) ctn.push(res.id);
               });
            }
        },
        selectAllForms(){
          this.selectAll = !this.selectAll;
          if(this.selectAll){
            let ctn = [];
            this.checkBoxDef(true,ctn)
            const sameId = this.removeIds.filter(e => {
               for (var i = 0; i < ctn.length; i++)  if (e.id == ctn[i].id) return true;
               return null;
            });
            if (sameId.length > 0)  for (let i = 0; i < sameId.length; i++) this.removeIds.remove(sameId[i]);
            this.removeIds = ctn;
          }else this.checkBoxDef();
          this.delIcon = this.removeIds;
        },
        delForms(){
          if(this.removeIds.length >= 1 && confirm('Are you sure to delete?')){
            this.readyFormLoading = true;
            this.$http.post('api/delete/'+this.removeIds)
            .then(res=>{
              if(res.data.response == 'success') this.vuePaginate(this.current??null);
            })
            .catch(err=>console.log("Error",err));
          }else alert('Reselect form again.');
        },
        getPaginateWithUsers(url){
            return this.$http
            .get(this.endpoint+url)
            .then(res =>  res.data.data)
            .catch(err => console.log(err))
        },
        read(e){
            //  If something is change Reselect All check
            if(this.removeIds.length >= 1 && this.selectAll)this.selectAllForms();
            // If something is change refresh checkbox
            this.checkBoxDef();
            // Reading Forms
            this.forms.find(res=>{
                if(res.id == e){
                    this.reader = res;
                    this.reading = true;
                    this.$http.post('api/clients/read/'+res.id)
                    .then(res=>{
                        if(res.data.response == 'success') this.$emit('reading');
                        else console.log("No Data Found")
                    })
                    .catch(err=>console.log(err))
                }
                else return null;
            });
        },
        showMailModal(e){
            $('#sendMailModal').modal('show');
        },
        mailSent(){
            $('#mailSuccess').modal('show');
            setTimeout(() =>  $('#mailSuccess').modal('hide'), 2000);
        }
    },
    watch:{
      search(newvalue,old){
        this.vsearch = newvalue;
        this.vuePaginate();
        return newvalue;
      }
    },
    mounted () {
      console.log(this.token)
    }
}
</script>

<style lang="scss" scoped>
.mailcontainer{
    cursor: pointer;
}
.overlayLoading{
    position: absolute;
    height: 100%;
    width: 100%;
    z-index:9999;
    border:none;
    border-radius: 5px;
    background: rgba(255, 255, 255, 0.749);
    div{
        justify-content: center;
        position: absolute;
        align-items: center;
        height: 100%;
        width:100%;
        margin: 0;
        padding: 0;
        img{
          -webkit-animation:spin 2s linear infinite;
          -moz-animation:spin 2s linear infinite;
          animation:spin 2s linear infinite;
        }
        @-moz-keyframes spin {
            100% { -moz-transform: rotate(360deg); }
        }
        @-webkit-keyframes spin {
            100% { -webkit-transform: rotate(360deg); }
        }
        @keyframes spin {
            100% {
                -webkit-transform: rotate(360deg);
                transform:rotate(360deg);
            }
        }
    }
}

</style>
