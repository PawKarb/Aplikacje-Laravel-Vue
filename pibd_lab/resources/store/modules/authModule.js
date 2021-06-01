import HttpRequest from '../../js/api/HttpRequest';
export const authModule = {
    namespaced: true,
    mutations: {
        login(form){
            HttpRequest.send("GET", "/sanctum/csrf-cookie");
            HttpRequest.send("POST","/api/login",{
                email: form.email,
                password: form.password
            });
        }
    },
    actions: {
        sendLogin({commit}){
            commit('login');
        }
    },
}
