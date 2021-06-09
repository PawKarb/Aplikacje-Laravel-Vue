<template>
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Logowanie</div>
                <form @submit.prevent="sendLogin()" class="card-body">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="text" class="form-control" name="email" v-model="$v.loginData.email.$model"
                            :class="{'is-invalid': $v.loginData.email.$error, 'is-valid': !$v.loginData.email.$invalid}"/>
                        <div class="invalid-feedback">
                            <span v-if="!$v.loginData.email.required">To pole jest wymagane!</span>
                            <span v-if="!$v.loginData.email.email">To pole musi być adresem email!</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="password">Hasło</label>
                        <input type="password" class="form-control" name="password" v-model.trim="$v.loginData.password.$model"
                            :class="{'is-invalid': $v.loginData.password.$error, 'is-valid': !$v.loginData.password.$invalid}"/>
                        <div class="invalid-feedback">
                            <span v-if="!$v.loginData.password.required">To pole jest wymagane!</span>
                            <span v-if="!$v.loginData.password.minLength">Pole 'Hasło' musi mieć min - {{$v.loginData.password.$params.minLength.min}} znaków!</span>
                        </div>
                    </div>
                    <div v-if="submitStatus === 'ERROR'" class="alert alert-danger" role="alert">
                        Prosimy o poprawne wypełnienie formularza.
                    </div>
                    <div v-if="submitStatus === 'UNAUTHORIZED'" class="alert alert-danger" role="alert">
                        Niepoprawny email lub hasło.
                    </div>
                    <div v-if="errors && errors.email" class="alert alert-danger" role="alert">
                        {{ errors.email[0] }}
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
import { required, minLength, email } from 'vuelidate/lib/validators';
    export default {
        data(){
            return{
                loginData:{
                    email: null,
                    password: null,
                },
                errors: null,
                submitStatus: null
            }
        },
        methods:{
            async sendLogin(){
                this.errors = null;
                this.$v.$touch();
                if (this.$v.$invalid) {
                    this.submitStatus = 'ERROR'
                } else {
                    this.submitStatus = null;
                    await axios.get("/sanctum/csrf-cookie");
                    await axios.post("/api/login", this.loginData).then(response=>{
                        this.$store.commit("loggedState/toggleLogged", true);
                        this.loginData = {};
                        this.$router.push({name: 'dashboard'});
                        this.$toasted.success("Zalogowano pomyślnie!!!",{
                            action : {
                                text : 'OK',
                                onClick : (e, toastObject) => {
                                    toastObject.goAway(0);
                                }
                            },
                            duration: 8000,
                            icon: 'fingerprint',
                            position: "bottom-right",
                        });
                    }).catch(error =>{
                        if (error.response.status === 422) {
                            this.errors = error.response.data.errors || {};
                        }else if(error.response.status === 401){
                            this.submitStatus = 'UNAUTHORIZED';
                        }else{
                            this.$store.commit("errorState/setError", error.message);
                        }
                    });
                }
            }
        },
        validations:{
            loginData:{
                email:{
                    required,
                    email,
                },
                password:{
                    required,
                    minLength: minLength(6),
                }
            }
        },
    }
</script>
