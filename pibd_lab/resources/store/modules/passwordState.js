export const passwordState = {
    namespaced: true,
    state: {
        changePassword: false,
    },
    mutations: {
        toggleChangePassword(state, data){
            state.changePassword = data;
        }
    },
    actions: {
        toggleChangePassword({commit}){
            commit('toggleChangePassword');
        }
    },
    getters: {
        getChangePassword: state =>{
            return state.changePassword;
        }
    }
}
