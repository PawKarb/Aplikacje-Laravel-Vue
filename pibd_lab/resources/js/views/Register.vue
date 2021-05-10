<template>
   <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Rejestracja</div>
                <form @submit.prevent="sendRegister()" class="card-body">
                    <div class="form-group">
                        <label for="name">Nazwa użytkownika</label>
                        <input type="text" class="form-control" name="name" v-model="registerData.name">
                        <div v-if="errors && errors.name" class="text-danger">{{ errors.name[0] }}</div>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="text" class="form-control" name="email" v-model="registerData.email">
                        <div v-if="errors && errors.email" class="text-danger">{{ errors.email[0] }}</div>
                    </div>
                    <div class="form-group">
                        <label for="password">Hasło</label>
                        <input type="password" class="form-control" name="password" v-model="registerData.password">
                        <div v-if="errors && errors.password" class="text-danger">{{ errors.password[0] }}</div>
                    </div>
                    <div class="form-group">
                        <label for="password_confirmation">Powtórz hasło</label>
                        <input type="password" class="form-control" name="password_confirmation" v-model="registerData.password_confirmation">
                        <div v-if="errors && errors.password_confirmation" class="text-danger">{{ errors.password_confirmation[0] }}</div>
                    </div>
                    <div class="row">
                        <div class="text-center">
                            <div class="form-group">
                                <input type="submit" class="btn btn-primary" value="Zarejestruj się">
                                <router-link class="btn btn-secondary" :to="{ name: 'login' }" tag="button">Mam już konto</router-link>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
<style scoped>
    h2{
        text-align: center;
    }
</style>
<script>
    export default {
        data(){
            return{
                registerData:{
                    name: null,
                    email: null,
                    password: null,
                    password_confirmation: null,
                },
                errors:{}
            }
        },
        methods:{
            async sendRegister(){
                await axios.get("/sanctum/csrf-cookie");
                await axios.post("/api/register", this.registerData).then(response=>{
                    this.registerData = {};
                    this.$router.push({name: 'login'});
                    this.$toasted.success("Zarejestrowano pomyślnie, teraz możesz się zalogować",{
                        action : {
                            text : 'OK',
                            onClick : (e, toastObject) => {
                                toastObject.goAway(0);
                            }
                        }
                    });
                }).catch(error=>{
                    if (error.response.status === 422) {
                    this.errors = error.response.data.errors || {};
                }});
            }
        }
    }
</script>
