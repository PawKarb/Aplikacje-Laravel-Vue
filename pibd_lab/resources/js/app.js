import Vue from 'vue';
import Spa from './Spa.vue';
import router from './router';
import Toasted from 'vue-toasted';
import store from './store';

Vue.use(Toasted)

require('./bootstrap');

const app = new Vue({
    el: '#app',
    template: '<spa/>',
    components:{
        Spa
    },
    router,
    store
});
