<template>
    <div v-if="!this.token" class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Logowanie</div>
                <form @submit.prevent="login()" class="card-body">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="text" class="form-control" name="email" v-model="formData.email">
                        <div class="text-danger" v-text="errors.email"></div>
                    </div>
                    <div class="form-group">
                        <label for="password">Hasło</label>
                        <input type="password" class="form-control" name="password" v-model="formData.password">
                        <div class="text-danger" v-text="errors.password"></div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="submit" class="btn btn-primary" value = "Zaloguj się"/>
                            </div>
                        </div>
                        <div class="cold-md-6">
                            <button class="btn btn-secondary" onclick="location.href='/api/register'">Utwórz nowe konto</button>
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
            return {
                formData:{
                    email: null,
                    password: null,
                    device_name: 'browser',
                },
                errors: {},
                token: localStorage.getItem('token'),
            }
        },
        methods:{
            login(){
                this.errors = {};
                axios.get('/sanctum/csrf-cookie').then(response => {
                    axios.post('/api/login/send',this.formData).then(response =>{
                        alert("Zalogowano pomyślnie");
                        localStorage.setItem('token', response.data);
                        this.formData = {};
                        location.href = '/';
                    }).catch(errors =>{
                        this.errors = errors.response.data.errors;
                    });
                });
            }
        },
        mounted(){
            if(this.token){
                alert('Jesteś już Zalogowany!!!');
                location.href = '/';
            }
        }
    }
</script>
<style>

</style>
