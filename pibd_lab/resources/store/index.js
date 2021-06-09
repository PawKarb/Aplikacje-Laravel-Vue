import Vue from 'vue';
import Vuex from 'vuex';
import createPersistedState from "vuex-persistedstate";
import {errorState} from "./modules/errorState";
import {loggedState} from "./modules/loggedState";
import {passwordState} from "./modules/passwordState"

Vue.use(Vuex);


const store = new Vuex.Store({
    plugins: [createPersistedState()],
    modules: {
        errorState,
        loggedState,
        passwordState,
    },
});

export default store;
