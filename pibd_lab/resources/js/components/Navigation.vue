<template>
    <div id="navigation-buttons">
        <router-link class="btn btn-sm btn-outline-secondary" :to="{ name: 'home' }" tag="button" >Home</router-link>
        <router-link class="btn btn-sm btn-outline-secondary" :to="{ name: 'dashboard' }" tag="button">Dashboard</router-link>
        <router-link class="btn btn-sm btn-outline-secondary" :to="{ name: 'login' }" tag="button">Login</router-link>
        <router-link class="btn btn-sm btn-outline-secondary" :to="{ name: 'register' }" tag="button">Register</router-link><br/>
        <button class="btn btn-danger" @click.prevent="logout()">Wyloguj się</button>
    </div>
</template>
<style scoped>
    #navigation-buttons{
        text-align: center;
    }
</style>
<script>
    export default {
        methods:{
            async logout(){
                await axios.post("/api/logout").then(response=>{
                    this.$router.push({name: 'home'});
                    this.$toasted.success("Wylogowano pomyślnie!!!",{
                        action : {
                            text : 'OK',
                            onClick : (e, toastObject) => {
                                toastObject.goAway(0);
                            }
                        }
                    });
                }).catch(error=>{
                    console.log(error);
                });
            }
        }
    }
</script>
