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
        { name: 'login', path: '/login', component: Login },
        { name: 'dashboard', path: '/dashboard', component: Dashboard},
        { name: 'register', path: '/register', component: Register},
        { path: '*', redirect: "/" }
    ]
});

export default router;
