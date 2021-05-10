<template>
    <div>
        <h1>Laboratorium PIBD</h1>
        <navigation/>
        <router-view></router-view>
    </div>
</template>
<style scoped>
    h1{
        text-align: center;
    }
</style>
<script>
import Navigation from './components/Navigation.vue'
    export default {
        created() {
            axios.interceptors.response.use(response => {return response},error => {
                if (error.response.status === 401) {
                    localStorage.removeItem("isLogged");
                    this.$root.$emit("isLogged", false);
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
