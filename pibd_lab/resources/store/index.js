import Vue from 'vue';
import Vuex from 'vuex';
import createPersistedState from "vuex-persistedstate";
import {errorState} from "./modules/errorState";
import {loggedState} from "./modules/loggedState";
import {passwordState} from "./modules/passwordState";
import {resetPasswordState} from "./modules/resetPasswordState";
import SecureLS from "secure-ls";
var ls = new SecureLS({ isCompression: false });

Vue.use(Vuex);


const store = new Vuex.Store({
    plugins: [createPersistedState({
        paths:['errorState.errorData', 'loggedState.loggedData'],
        storage: {
            getItem: (key) => ls.get(key),
            setItem: (key, value) => ls.set(key, value),
            removeItem: (key) => ls.remove(key),
          },
    })],
    modules: {
        errorState,
        loggedState,
        passwordState,
        resetPasswordState,
    },
});

export default store;
