<template>
    <div v-if="!this.token" class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Rejestracja</div>
                <form @submit.prevent="sendRegister()" class="card-body">
                    <div class="form-group">
                        <label for="name">Nazwa użytkownika</label>
                        <input type="text" class="form-control" name="name" v-model="register.name">
                        <div v-if="errors && errors.name" class="text-danger">{{ errors.name[0] }}</div>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="text" class="form-control" name="email" v-model="register.email">
                        <div v-if="errors && errors.email" class="text-danger">{{ errors.email[0] }}</div>
                    </div>
                    <div class="form-group">
                        <label for="password">Hasło</label>
                        <input type="password" class="form-control" name="password" v-model="register.password">
                        <div v-if="errors && errors.password" class="text-danger">{{ errors.password[0] }}</div>
                    </div>
                    <div class="form-group">
                        <label for="password_confirmation">Powtórz hasło</label>
                        <input type="password" class="form-control" name="password_confirmation" v-model="register.password_confirmation">
                        <div v-if="errors && errors.password_confirmation" class="text-danger">{{ errors.password_confirmation[0] }}</div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="submit" class="btn btn-primary" value="Zarejestruj się">
                            </div>
                        </div>
                        <div class="cold-md-6 text-right">
                            <button class="btn btn-secondary" onclick="location.href='/api/login'">Mam już konto</button>
                        </div>
                    </div>
                </form>
            </div>
            <button class="btn btn-secondary" onclick="location.href='/'">Powrót</button>
        </div>
    </div>
</template>
<script>
    export default {
        data(){
            return{
                register:{
                    name: null,
                    email: null,
                    password: null,
                    password_confirmation: null,
                },
                errors: {},
                token: localStorage.getItem('token'),
            }
        },
        methods: {
            sendRegister(){
                this.errors = {};
                axios.post('/api/register/send', this.register).then(response =>{
                    alert("Zarejestrowano pomyślnie");
                    this.register = {};
                    location.href = '/api/login';
                }).catch(error => {
                    if (error.response.status === 422) {
                        this.errors = error.response.data.errors || {};
                    }});
            },
        },
        mounted() {
            if(this.token){
                alert('Jesteś już Zalogowany!!!');
                location.href = '/';
            }
        }
    }
</script>
<style>

</style>
