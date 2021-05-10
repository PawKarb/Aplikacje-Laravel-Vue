<template>
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Logowanie</div>
                <form @submit.prevent="sendLogin()" class="card-body">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="text" class="form-control" name="email" v-model="loginData.email">
                        <div class="text-danger" v-text="errors.email"></div>
                    </div>
                    <div class="form-group">
                        <label for="password">Hasło</label>
                        <input type="password" class="form-control" name="password" v-model="loginData.password">
                        <div class="text-danger" v-text="errors.password"></div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="submit" class="btn btn-primary" value = "Zaloguj się"/>
                                <router-link class="btn btn-secondary" :to="{name: 'register'}">Utwórz nowe konto</router-link>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
<style scoped>

</style>
<script>
    export default {
        data(){
            return{
                loginData:{
                    email: null,
                    password: null,
                },
                errors:{}
            }
        },
        methods:{
            async sendLogin(){
                this.errors = {};
                await axios.get("/sanctum/csrf-cookie");
                await axios.post("/api/login", this.loginData).then(response=>{
                    this.loginData = {};
                    this.$router.push({name: 'dashboard'});
                    this.$toasted.success("Zalogowano pomyślnie!!!",{
                        action : {
                            text : 'OK',
                            onClick : (e, toastObject) => {
                                toastObject.goAway(0);
                            }
                        }
                    });
                }).catch(error =>{
                    if (error.response.status === 422) {
                    this.errors = error.response.data.errors || {};
                }});
            }
        }
    }
</script>
