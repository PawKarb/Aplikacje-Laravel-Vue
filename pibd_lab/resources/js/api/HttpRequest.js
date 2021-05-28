import store from '../store';

class HttpRequest {
    constructor(){}

    async send(httpMethod, url, data){
        try{
            return await axios.request({
                method: httpMethod,
                url: url,
                data: data,
            });
        }catch(err){
            store.commit('errorState/setError');
            throw err;
        }
    }
}

export default new HttpRequest();
