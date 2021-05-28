<template>
    <div>
        <h1 class="text-center">Laboratorium PIBD</h1>
        <navigation/>
        <router-view></router-view>
        <error-modal />
        <footer class="bg-light text-center text-lg-start fixed-bottom">
            <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.2);">
                © 2021 Copyright:
            <span class="text-dark">Paweł Karbowski 112735</span>
            </div>
        </footer>
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
                loggedStorage: null,
            }
        },
        created() {
            this.loggedStorage = localStorage.getItem("vuex");
            axios.interceptors.response.use(response => {return response},error => {
                if (error.response.status === 401 || error.response.status === 419 || this.loggedStorage === null) {
                    this.$store.commit('logged/toggleLogged',false);
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
