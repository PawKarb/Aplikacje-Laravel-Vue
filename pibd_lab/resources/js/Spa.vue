<template>
    <div>
        <h1 class="text-center">Laboratorium PIBD</h1>
        <navigation/>
        <router-view></router-view>
    </div>
</template>
<style scoped>
</style>
<script>
import Navigation from './components/Navigation.vue'
    export default {
        created() {
            axios.interceptors.response.use(response => {return response},error => {
                if (error.response.status === 401) {
                    localStorage.removeItem("isLogged");
                    this.$store.commit('changeLogged','false');
                    if (this.$route.path != "/login") {
                        this.$router.push({ name: "login" });
                    }
                }
                return Promise.reject(error);
            });
    },
        components: { Navigation },
    }
</script>
