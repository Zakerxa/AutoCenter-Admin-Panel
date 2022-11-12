import { createRouter, createWebHistory } from "vue-router";

const routes = [{
        path: "/",
        name: "Home",
        component: require('./admin/Zakerxa.vue').default,
        meta: {
            protect: true
        }
    },
    {
        path: "/login",
        name: "Login",
        component: require('./admin/Login.vue').default
    },
    {
        path: "/:pathMatch(.*)*",
        name: "PageNotFound",
        component: require('./admin/PageNotFound.vue').default
    }
]

const router = createRouter({
    history: createWebHistory(process.env.BASH_URL),
    routes
})

router.beforeEach((to, from, next) => {
    const auth = localStorage.getItem('adminThida');
    if (to.name == 'Login' && auth)
        next({ path: '/' })
    else if ((to.name == 'login' || to.name == 'Contact') && auth)
        next({ path: '/' })
    else if (to.meta.protect && !auth)
        next({ path: '/login' })
    else
        next()
})

export default router;