import Vue from 'vue';
import Vuex from 'vuex';
import createPersistedState from "vuex-persistedstate";
import {errorState} from "./modules/error";
import {logged} from "./modules/logged";

Vue.use(Vuex);

const store = new Vuex.Store({
    plugins: [createPersistedState()],
    modules: {
        errorState,
        logged,
    },
});

export default store;
