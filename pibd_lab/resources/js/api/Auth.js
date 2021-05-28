import store from '../../store';
import HttpRequest from './HttpRequest';
import router from '../router';
import toasted from 'vue-toasted';

class Auth{
    constructor() {
        this.HttpRequest = HttpRequest;
    }

    csrf_cookie = await this.HttpRequest.send("GET", "/sanctum/csrf-cookie");

    async login(form){
        this.csrf_cookie;
        await this.HttpRequest.send("POST","/api/login",{
            email: form.email,
            password: form.password
        });
        store.commit("logged/toggleLogged", true);
        router.push({name: 'dashboard'});
        toasted.success("Zalogowano pomyślnie!!!",{
            action : {
                text : 'OK',
                onClick : (e, toastObject) => {
                    toastObject.goAway(0);
                }
            },
            duration: 8000,
            icon: 'fingerprint',
            position: "bottom-right",
        });
    }
}

export default Auth();
