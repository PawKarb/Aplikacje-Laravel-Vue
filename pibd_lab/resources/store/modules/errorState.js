export const errorState = {
    namespaced: true,
    state: {
        errorData: null,
    },
    mutations: {
        setError(state, data){
            state.errorData = data;
        }
    },
    actions: {
        setError({commit}){
            commit('setError');
        }
    },
    getters: {
        getError: state =>{
            return state.errorData;
        }
    }
}
