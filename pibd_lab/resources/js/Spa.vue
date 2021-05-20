<template>
    <div>
        <h1 class="text-center">Laboratorium PIBD</h1>
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
        created() {
            axios.interceptors.response.use(response => {return response},error => {
                if (error.response.status === 401 || error.response.status === 419) {
                    this.$store.commit('toggleLogged',false);
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
