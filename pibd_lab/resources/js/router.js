import Vue from 'vue';
import VueRouter from 'vue-router';
import Home from './views/Home.vue';
import Login from './views/auth/Login.vue';
import Dashboard from './views/Dashboard.vue';
import Register from './views/auth/Register.vue';
import store from '../store';
import Panel from './views/user/Panel';
import ResetPasswordForm from './views/auth/ResetPasswordForm';
import VerifyEmail from './views/auth/VerifyEmail';


Vue.use(VueRouter);

const router = new VueRouter({
    mode: 'history',
    routes: [
        { name: 'home', path: '/', component: Home },
        { name: 'login', path: '/login', component: Login, meta: { guestOnly: true } },
        { name: 'dashboard', path: '/dashboard', component: Dashboard, meta: { requiresAuth: true }},
        { name: 'register', path: '/register', component: Register, meta: { guestOnly: true }},
        { name: 'reset-password-form', path: '/reset-password/:token', component: ResetPasswordForm, meta: { guestOnly: true }},
        { name: 'verify-user', path: '/verify-user', component: VerifyEmail, meta: { guestOnly: true }},
        { name: 'panel', path: '/panel', component: Panel, meta: {requiresAuth: true}},
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
        return store.getters['loggedState/isLogged'];
    }

export default router;
