import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex);

export default new Vuex.Store({
    state:{
        isLogged: false
    },
    getters:{
        isLogged: state =>{
            return state.isLogged;
        }
    },
    mutations:{
        toggleLogged(state, data){
            state.isLogged = data;
        }
    },
    actions:{
        toggleLogged({commit}){
            commit('toggleLogged');
        }
    }
});
