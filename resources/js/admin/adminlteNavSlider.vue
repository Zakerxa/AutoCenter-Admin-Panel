<template>
    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a
                    class="nav-link"
                    data-widget="pushmenu"
                    role="button"
                    ><font-awesome-icon icon="fas fa-bars"
                /></a>
            </li>
            <li class="nav-item d-none d-sm-inline-block">
                <a href="index3.html" class="nav-link">Home</a>
            </li>
        </ul>

        <!-- SEARCH FORM -->
        <form @submit.prevent="search" class="form-inline ml-3">
            <div class="input-group input-group-sm">
                <input
                    class="form-control form-control-navbar"
                    type="text"
                    placeholder="Search"
                    aria-label="Search"
                    v-model="name"
                />
                <div class="input-group-append">
                    <span @click="search('close')" v-show="name.length >= 1" class="btn btn-navbar">X</span>
                    <button class="btn btn-navbar" type="submit">
                        <font-awesome-icon icon="fas fa-search"></font-awesome-icon>
                    </button>
                </div>
            </div>
        </form>

        <!-- Right navbar links -->
        <ul class="navbar-nav ml-auto">
            <!-- Notifications Dropdown Menu -->
            <li class="nav-item dropdown">
                <a class="nav-link" data-toggle="dropdown" href="#">
                    <span v-show="noti" class="badge badge-danger navbar-badge"><font-awesome-icon icon="fas fa-bell"></font-awesome-icon> {{noti}}</span>
                </a>
            </li>
            <li class="nav-item">
                <a
                    class="nav-link"
                    data-widget="control-sidebar"
                    data-slide="true"
                    href="#"
                    role="button"
                    ><i class="fas fa-th-large"></i
                ></a>
            </li>
        </ul>
    </nav>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <!-- Brand Logo -->
        <a class="brand-link">
            <span class="brand-text font-weight-light">Admin Panel</span>
            <font-awesome-icon
                class="d-block d-lg-none"
                icon="fas fa-bars"
                data-widget="pushmenu"
                style="float:right;font-size:20px;padding-top:5px;"
            ></font-awesome-icon>
        </a>

        <!-- Sidebar -->
        <div class="sidebar">
            <!-- Sidebar user panel (optional) -->
            <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                <div class="image">
                    <img
                        src="https://booleanstrings.com/wp-content/uploads/2021/10/profile-picture-circle-hd.png"
                        class="img-circle elevation-2"
                        alt="User Image"
                    />
                </div>
                <div class="info">
                    <a href="#" class="d-block">{{ admin.name }}</a>
                </div>
            </div>

            <!-- Sidebar Menu -->
            <nav class="mt-2">
                <ul
                    class="nav nav-pills nav-sidebar flex-column"
                    data-widget="treeview"
                    role="menu"
                >
                    <!-- Add icons to the links using the .nav-icon class
                   with font-awesome or any other icon font library -->
                    <li class="nav-item has-treeview menu-open">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>
                                DashBoard
                                <span class="right badge badge-danger" v-show="noti">New</span>
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a @click="logout()" class="nav-link">
                            <font-awesome-icon icon="fas fa-sign-out" />
                            <p>
                                Log Out
                            </p>
                        </a>
                    </li>
                </ul>
            </nav>
            <!-- /.sidebar-menu -->
        </div>
        <!-- /.sidebar -->
    </aside>
    <!-- Main Sidebar End -->
</template>

<script>
export default {
    data () {
        return {
            admin: [],
            name : ''
        }
    },
    props:['noti','searchend'],
    methods: {
        logout () {
            this.$http('api/logout').then(res => {
                console.log(res.data)
                if (res.data.response == 'logout') {
                    localStorage.removeItem('adminThida')
                    this.$router.push(this.$route.query.redirect || '/login')
                }
            })
        },
        search(e){
            if(e =='close') this.name = '';
            this.$emit('searching',this.name);
        }
    },
    watch:{
      noti(newvalue,old){
        return newvalue;
      }
    },
    created () {
        this.$http
            .get('/api/user')
            .then(response => (this.admin = response.data))
    }
}
</script>

<style></style>
