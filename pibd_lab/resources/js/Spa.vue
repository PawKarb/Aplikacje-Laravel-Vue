<template>
    <div>
        <navigation/>
        <router-view></router-view>
        <error-modal />
    </div>
</template>
<style scoped>
</style>
<script>
import Navigation from './components/Navigation.vue';
import ErrorModal from './components/Error.vue';
    export default {
        data(){
            return{
                storage: null,
            }
        },
        created() {
            this.storage = sessionStorage.getItem("vuex");
            axios.interceptors.response.use(response => {return response},error => {
                if (error.response.status === 401 || error.response.status === 419 || this.storage === null) {
                    this.$store.commit('loggedState/toggleLogged',false);
                    if (this.$route.path != "/login") {
                        this.$router.push({ name: "login" });
                    }
                }
                return Promise.reject(error);
            });
        },
        components: { Navigation, ErrorModal},
    }
</script>
