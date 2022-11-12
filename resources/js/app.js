require('bootstrap')
import { createApp } from 'vue';
import App from "./App.vue";
// import VuePagnation
import { VuePagination } from '@zakerxa/vue-laravel-pagination';
// import CkEditor
import CKEditor from '@ckeditor/ckeditor5-vue';
// import Router
import router from './router';
// import Authorization api
import authorize from './authorize';
/* import the fontawesome core */
import { library } from '@fortawesome/fontawesome-svg-core'
/* import font awesome icon component */
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
/* import specific icons */
import { faEnvelope } from '@fortawesome/free-solid-svg-icons';
import { faBars } from '@fortawesome/free-solid-svg-icons';
import { faCircleLeft } from '@fortawesome/free-solid-svg-icons';
import { faCross } from '@fortawesome/free-solid-svg-icons';
import { faMailForward } from '@fortawesome/free-solid-svg-icons';
import { faBell } from '@fortawesome/free-solid-svg-icons';
import { faSignOut } from '@fortawesome/free-solid-svg-icons';
import { faSearch } from '@fortawesome/free-solid-svg-icons';
import { faIdCardClip } from '@fortawesome/free-solid-svg-icons';
import { faEye } from '@fortawesome/free-solid-svg-icons';
import { faEyeSlash } from '@fortawesome/free-solid-svg-icons';
import { faCheckCircle } from '@fortawesome/free-solid-svg-icons';
import { faHistory } from '@fortawesome/free-solid-svg-icons';
/* add icons to the library */
library.add(faMailForward, faCircleLeft, faCross, faSignOut, faBell, faBars, faHistory, faCheckCircle, faEye, faEyeSlash, faIdCardClip, faSearch, faEnvelope)


const app = createApp(App).use(CKEditor).use(router);
app.component('vue-pagination', VuePagination);
app.component('font-awesome-icon', FontAwesomeIcon);
app.mount('#app');

app.config.errorHandler = (err) => {
    /* handle error */
    console.log(err);
}

// Create Global Vue Property
const global = app.config.globalProperties;
// Define Global Property
global.csrfToken = document.head.querySelector("[name~=csrf-token][content]").content;
global.token = localStorage.getItem('adminThida');
global.mail = 'info@thidaautocenter.com';
global.$http = authorize;


// Array remove Function
Array.prototype.remove = function() {
    var what, a = arguments,
        L = a.length,
        ax;
    while (L && this.length) {
        what = a[--L];
        while ((ax = this.indexOf(what)) !== -1) {
            this.splice(ax, 1);
        }
    }
    return this;
}