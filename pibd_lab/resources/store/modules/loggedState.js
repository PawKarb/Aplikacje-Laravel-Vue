export const loggedState = {
    namespaced: true,
    state:{
        loggedData: false,
    },
    getters:{
        isLogged: state =>{
            return state.loggedData;
        },
    },
    mutations:{
        toggleLogged(state, data){
            state.loggedData = data;
        },
    },
    actions:{
        toggleLogged({commit}){
            commit('toggleLogged');
        },
    }
}
