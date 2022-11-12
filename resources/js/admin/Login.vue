<template>
    <div class="container pt-5">
        <div class="row pt-5 justify-content-center">
            <div class="col-11 col-md-6 col-lg-4 mt-5 mb-5 pt-4 pb-5 card">
               <form @submit.prevent="login">
                    <h3 class="fw-bold text-center mb-3">Login</h3>
                    <small class="text-danger pb-2">{{error}}</small>
                    <div class="form-floating mb-3">
                        <input
                            type="email"
                            v-model="admin.email"
                            class="form-control"
                            id="floatingInput"
                            autocomplete="off"
                            autofocus="false"
                            placeholder="name@example.com"
                            required
                        />
                        <label for="floatingInput">Email address</label>
                    </div>
                    <div class="form-floating">
                        <input
                            type="password"
                            v-model="admin.password"
                            class="form-control"
                            id="floatingPassword"
                            autocomplete="off"
                            autofocus="false"
                            placeholder="Password"
                            required
                        />
                        <label for="floatingPassword">Password</label>
                    </div>
                    <div class="pt-4 text-center">
                        <button
                            type="submit"
                            class="btn fw-bold w-100 btn-primary">
                            Login
                        </button>
                    </div>
               </form>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data () {
        return {
            admin: {
                email: '',
                password: ''
            },
            error : ''
        }
    },
    methods: {
        login (e) {
            e.preventDefault();
            fetch('api/login',{
                method : 'post',
                credentials: "same-origin",
                headers: {
                  Accept : 'application/json',
                    'Content-Type' : 'application/json',
                    "X-Requested-With": "XMLHttpRequest",
                    "X-CSRF-Token": this.csrfToken
                },
                body: JSON.stringify(this.admin)
            })
            .then(res => res.json())
            .then(res => {
               if(res.response == 'success')  this.authRedirect(res.token);
               else this.error = 'Incorrect Credentials';
            })
            .catch(error => this.clearData(error))
        },
        clearData(e){
            if(e) console.log(e);
            this.admin.email = '';
            this.admin.password = '';
            this.error = '';
        },
        register () {
            this.$http
            .post('api/register', this.admin)
            .then(res => {
                if (res.data.response == 'success') {
                    this.admin.email = ''
                    this.admin.password = ''
                }
            })
            .catch(error => console.log('This is error', error))
        },
        authRedirect (token) {
            localStorage.setItem('adminThida', token);
            if(localStorage.getItem('adminThida')){
              this.clearData();
              this.$router.push(this.$route.query.redirect || '/')
            }
        }
    }
}
</script>

<style></style>
