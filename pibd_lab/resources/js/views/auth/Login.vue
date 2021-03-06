<template>
    <div class="row justify-content-center">
        <div v-if="!catchPasswordReset" class="col-md-6">
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
                            <span v-if="!$v.loginData.password.passwordRegex">Hasło musi posiadać 8 znaków w tym jedną literę, jeden znak specjalny oraz jedną liczbę</span>
                        </div>
                    </div>
                    <div v-if="submitStatus === 'ERROR'" class="alert alert-danger" role="alert">
                        Prosimy o poprawne wypełnienie formularza.
                    </div>
                    <div v-if="submitStatus === 'UNAUTHORIZED'" class="alert alert-danger" role="alert">
                        Niepoprawny email lub hasło.
                    </div>
                    <div v-if="submitStatus === 'ToManyRequest'" class="alert alert-danger" role="alert">
                        Wykonałeś za dużo prób logowania. Spróbuj ponownie później.
                    </div>
                    <div v-if="errors && errors.email" class="alert alert-danger" role="alert">
                        {{ errors.email[0] }}
                    </div>
                    <div v-if="submitStatus === 'Unactivated'" class="alert alert-danger" role="alert">
                        Konto nie jest aktywne. Aby wysłać ponownie mail aktywacyjny <router-link :to="{ name: 'verify-user' }"> Kliknij tutaj</router-link>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="submit" class="btn btn-primary" value = "Zaloguj się"/>
                                <router-link class="btn btn-secondary" :to="{name: 'register'}">Utwórz nowe konto</router-link>
                                <button class="btn btn-link" @click.prevent="setResetPassword">Zapomniałeś hasła?</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <reset-password v-if="catchPasswordReset"></reset-password>
    </div>
</template>
<style scoped>

</style>
<script>
import ResetPassword from '../auth/ResetPassword.vue';
import { required, email, helpers } from 'vuelidate/lib/validators';
const passwordRegex = helpers.regex('passwordRegex', /^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]{8,}$/);
    export default {
        components: { ResetPassword },
        data(){
            return{
                loginData:{
                    email: null,
                    password: null,
                },
                errors: null,
                submitStatus: null,
            }
        },
        methods:{
            setResetPassword(){
                this.$store.commit('resetPasswordState/toggleResetPassword', true);
            },
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
                        this.loginData.password = null;
                        if (error.response.status === 422) {
                            this.errors = error.response.data.errors || {};
                        }else if(error.response.status === 401){
                            this.submitStatus = 'UNAUTHORIZED';
                        }else if(error.response.status === 429){
                            this.submitStatus = 'ToManyRequest';
                        }else if(error.response.status === 450){
                            this.submitStatus = "Unactivated"
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
                    passwordRegex,
                }
            }
        },
        computed:{
        catchPasswordReset(){
            return this.$store.getters['resetPasswordState/getResetPassword'];
        },
    },
    }
</script>
