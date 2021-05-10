import Vue from 'vue';
import VueRouter from 'vue-router';
import Home from './views/Home.vue';
import Login from './views/Login.vue';
import Dashboard from './views/Dashboard.vue';
import Register from './views/Register.vue';

Vue.use(VueRouter);

const router = new VueRouter({
    mode: 'history',
    routes: [
        { name: 'home', path: '/', component: Home },
        { name: 'login', path: '/login', component: Login, meta: { guestOnly: true } },
        { name: 'dashboard', path: '/dashboard', component: Dashboard, meta: { requiresAuth: true }},
        { name: 'register', path: '/register', component: Register, meta: { guestOnly: true }},
        { path: '*', redirect: "/" }
    ]
});
    router.beforeEach((to, from, next) => {
        if (to.matched.some(record => record.meta.requiresAuth)) {
            if (!isLogged()) {
                next({path: '/login', query: { redirect: to.fullPath }});
            } else {
                next();
            }
        }else if (to.matched.some(record => record.meta.guestOnly)) {
            if (isLogged()) {
                next({path: '/dashboard',query: { redirect: to.fullPath }});
            } else {
                next();
            }
        }else {
            next();
        }
        });

    function isLogged() {
        return localStorage.getItem("isLogged");
    }

export default router;
