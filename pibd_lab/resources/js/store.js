import Vue from 'vue';
import Vuex from 'vuex';
import createPersistedState from "vuex-persistedstate";

Vue.use(Vuex);

export default new Vuex.Store({
    state:{
        isLogged: false,
        error: null,
    },
    getters:{
        isLogged: state =>{
            return state.isLogged;
        },
        getError: state =>{
            return state.error;
        }
    },
    mutations:{
        toggleLogged(state, data){
            state.isLogged = data;
        },
        setError(state, data){
            state.error = data;
        }
    },
    actions:{
        toggleLogged({commit}){
            commit('toggleLogged');
        },
        setError({commit}){
            commit('setError')
        }
    },
    plugins: [createPersistedState()],
});
