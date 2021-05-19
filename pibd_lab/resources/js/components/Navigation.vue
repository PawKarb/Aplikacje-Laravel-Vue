<template>
    <div id="navigation-buttons">
        <router-link class="btn btn-sm btn-outline-secondary" :to="{ name: 'home' }" tag="button" >Home</router-link>
        <router-link v-if="isLogged" class="btn btn-sm btn-outline-secondary" :to="{ name: 'dashboard' }" tag="button">Dashboard</router-link>
        <router-link v-if="!isLogged" class="btn btn-sm btn-outline-secondary" :to="{ name: 'login' }" tag="button">Logowanie</router-link>
        <router-link v-if="!isLogged" class="btn btn-sm btn-outline-secondary" :to="{ name: 'register' }" tag="button">Rejestracja</router-link>
        <button v-if="isLogged" class="btn btn-danger float-right btn-logout" @click.prevent="logout()">Wyloguj się</button>
    </div>
</template>
<style scoped>
    #navigation-buttons{
        text-align: center;
        padding: 0.5%;
    }
    .btn-logout{
        padding: 1px;
        margin-right: 0.5px;
    }
</style>
<script>
    export default {
        data() {
            return { isLogged: null};
        },
        created() {
            //this.isLogged = localStorage.getItem("isLogged");
            this.isLogged = this.$store.state.isLogged;
        },
        methods:{
            async logout(){
                await axios.post("/api/logout").then(response=>{
                    localStorage.removeItem("isLogged");
                    this.$store.commit('toggleLogged', 'false');
                    this.$router.push({name: 'home'});
                    this.$toasted.success("Wylogowano pomyślnie!!!",{
                        action : {
                            text : 'OK',
                            onClick : (e, toastObject) => {
                                toastObject.goAway(0);
                            }
                        },
                        duration: 8000,
                        icon: 'logout',
                        position: "bottom-right",

                    });
                }).catch(error=>{
                    console.log(error);
                });
            }
        }
    }
</script>
