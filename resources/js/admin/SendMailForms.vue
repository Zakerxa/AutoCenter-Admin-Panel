<template>
 <div class="modal fade" id="sendMailModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content boxshadow border-0 position-relative">
        <div v-show="modalMailSendLoading" class="overlayLoading">
            <div class="row">
              <img src="/icon/loading.png" style="width:80px;" alt="">
            </div>
        </div>
        <div class="modal-header">
          <h3 class="modal-title fs-5 fw-bold" style="letter-spacing:1px;" id="staticBackdropLabel"><font-awesome-icon icon="fa-solid fa-mail-forward" class="text-success" /> Send Mail </h3>
        </div>
        <div class="modal-body">
            <div class="col-12 p-0">
                <p v-if="errors.length >= 1" class="bg-danger text-light p-2" style="border-radius:4px;">{{errors[0]}}</p>
               <div class="input-group mb-3">
                 <span class="input-group-text" id="inputGroup-sizing-default" style="min-width:63px;">To</span>
                 <input type="text" disabled :value="forms.email" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default">
               </div>
               <div class="input-group mb-3">
                 <span class="input-group-text" id="inputGroup-sizing-default" style="min-width:63px;">From</span>
                 <input type="text" disabled :value="forms.adminMail" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default">
               </div>
               <div class="input-group mb-3">
                 <span class="input-group-text" id="inputGroup-sizing-default" style="min-width:63px;">Title</span>
                 <input type="text" v-model="forms.title" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default">
               </div>
               <div class="input-group mb-3">
                 <span class="input-group-text" id="inputGroup-sizing-default" style="min-width:63px;">Sub</span>
                 <input type="text" v-model="forms.subject" class="form-control" placeholder="Need Subject " aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default">
               </div>
               <div class="mb-3">
                  <label for="editor" class="form-label">Body</label>
                  <ckeditor tag-name="textarea" id="editor" aria-placeholder="Body" :editor="editor" v-model="forms.body" :config="editorConfig"></ckeditor>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <button type="button" @click="sendMail" class="btn btn-sm w-100 btn-primary">Send Now</button>
                </div>
                <div class="col">
                    <button type="button" @click="hideModal('back')" class="btn btn-sm w-100 btn-dark">Go Back</button>
                </div>
            </div>
        </div>
      </div>
    </div>
</div>
</template>

<script>
import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
export default {
   data(){
    return{
        modalMailSendLoading : false,
        errors : [],
        forms : {
            title: 'Thida AutoCenter',
            name: '',
            email: '',
            adminMail : this.adminMail,
            subject : '',
            body : '',
        },
        editor: ClassicEditor,
        editorConfig: {
            toolbar: [ 'heading', '|', 'bold', 'italic', 'bulletedList', 'numberedList', 'blockQuote','|', 'undo', 'redo', ],
            heading: {
                options: [
                    { model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph' },
                    { model: 'heading2', view: 'h2', title: 'Heading 1', class: 'ck-heading_heading2' },
                    { model: 'heading3', view: 'h3', title: 'Heading 2', class: 'ck-heading_heading3' },
                    { model: 'heading4', view: 'h4', title: 'Heading 3', class: 'ck-heading_heading4' },
                    { model: 'heading5', view: 'h5', title: 'Heading 4', class: 'ck-heading_heading5' }
                ]
            }
        }
    }
   },
   props:['currentMail','adminMail'],
   watch:{
     currentMail(newvalue,oldvalue){
        this.forms.email = newvalue.email;
        this.forms.name = newvalue.username;
        return newvalue;
     }
   },
   methods:{
     sendMail(e){
        e.preventDefault();
        this.modalMailSendLoading = true;
        this.$http.post('/api/clients/email',this.forms)
        .then(res=>{
            if(res.data.response == 'success')  this.hideModal();
            else this.error();
        })
        .catch(() => this.error())
     },
     error(){
        this.errors.push("Something went wrong !")
        this.modalMailSendLoading = false;
     },
     hideModal(e){
        if(e != 'back') this.$emit('mailSent');
        this.errors = [];
        this.forms.subject = '';
        this.forms.body = '';
        this.modalMailSendLoading = false;
        $('#sendMailModal').modal('hide');
     }
   }
}
</script>

<style lang="scss">
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
