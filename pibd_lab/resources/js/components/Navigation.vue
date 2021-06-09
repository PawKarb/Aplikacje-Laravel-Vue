<template>
    <div id="navigation-buttons">
        <router-link class="btn btn-sm btn-outline-secondary" :to="{ name: 'home' }" tag="button" >Home</router-link>
        <router-link v-if="catchLogged" class="btn btn-sm btn-outline-secondary" :to="{ name: 'dashboard' }" tag="button">Dashboard</router-link>
        <router-link v-if="!catchLogged" class="btn btn-sm btn-outline-secondary" :to="{ name: 'login' }" tag="button">Logowanie</router-link>
        <router-link v-if="!catchLogged" class="btn btn-sm btn-outline-secondary" :to="{ name: 'register' }" tag="button">Rejestracja</router-link>
        <div v-if="catchLogged" class="btn-group float-right dropleft">
            <button class="btn btn-outline-secondary btn-sm material-icons" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                settings
            </button>
            <div class="dropdown-menu">
                <router-link v-if="this.$route.path != '/panel'" class="dropdown-item text-center" :to="{ name: 'panel' }" tag="a">Profil <span class="material-icons">manage_accounts</span></router-link>
                <a class="dropdown-item text-center" @click.prevent="logout">Wyloguj się <span class="material-icons">logout</span></a>
            </div>
        </div>
    </div>
</template>
<style scoped>
    #navigation-buttons{
        text-align: center;
        padding: 0.5%;
    }
    .material-icons{
        display: inline-flex;
        vertical-align: top;
    }
</style>
<script>
    export default {
        methods:{
            async logout(){
                await axios.post("/api/logout").then(response=>{
                    this.$store.commit('loggedState/toggleLogged', false);
                    this.$store.commit('passwordState/toggleChangePassword', false);
                    if(this.$route.path != "/"){
                        this.$router.push({name: 'home'});
                    }
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
                    this.$store.commit("errorState/setError", error.message);
                });
            }
        },
        computed:{
            catchLogged(){
                return this.$store.getters['loggedState/isLogged'];
            }
        },
    }
</script>
