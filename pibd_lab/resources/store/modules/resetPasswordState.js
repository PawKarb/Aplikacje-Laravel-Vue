export const resetPasswordState = {
    namespaced: true,
    state: {
        resetPassword: false,
    },
    mutations: {
        toggleResetPassword(state, data){
            state.resetPassword = data;
        }
    },
    actions: {
        toggleResetPassword({commit}){
            commit('toggleResetPassword');
        }
    },
    getters: {
        getResetPassword: state =>{
            return state.resetPassword;
        }
    }
}
