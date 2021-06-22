<template>
   <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Rejestracja</div>
                <form @submit.prevent="sendRegister()" class="card-body">
                    <div class="form-group">
                        <label for="name">Nazwa użytkownika</label>
                        <input type="text" class="form-control" name="name" v-model="$v.registerData.name.$model"
                            :class="{'is-invalid': $v.registerData.name.$error, 'is-valid': !$v.registerData.name.$invalid}"/>
                        <div class="invalid-feedback">
                            <span v-if="!$v.registerData.name.required">To pole jest wymagane!</span>
                            <span v-if="!$v.registerData.name.minLength">Twoja nazwa nie może być krótsza niż {{$v.registerData.name.$params.minLength.min}} znaki</span>
                            <span v-if="!$v.registerData.name.maxLength">Twoja nazwa nie może być dłuższa niż {{$v.registerData.name.$params.maxLength.max}} znaków</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="text" class="form-control" name="email" v-model="$v.registerData.email.$model"
                            :class="{'is-invalid': $v.registerData.email.$error, 'is-valid': !$v.registerData.email.$invalid}"/>
                        <div class="invalid-feedback">
                            <span v-if="!$v.registerData.email.required">To pole jest wymagane!</span>
                            <span v-if="!$v.registerData.email.email">To pole musi być adresem email!</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="password">Hasło</label>
                        <input type="password" class="form-control" name="password" v-model="$v.registerData.password.$model"
                            :class="{'is-invalid': $v.registerData.password.$error, 'is-valid': !$v.registerData.password.$invalid}"/>
                        <div class="invalid-feedback">
                            <span v-if="!$v.registerData.password.required">To pole jest wymagane!</span>
                            <span v-if="!$v.registerData.password.passwordRegex">Hasło musi posiadać 8 znaków w tym jedną literę, jeden znak specjalny oraz jedną liczbę</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="password_confirmation">Powtórz hasło</label>
                        <input type="password" class="form-control" name="password_confirmation" v-model="$v.registerData.password_confirmation.$model"
                            :class="{'is-invalid': $v.registerData.password_confirmation.$error, 'is-valid': !$v.registerData.password_confirmation.$invalid}"/>
                        <div class="invalid-feedback">
                            <span v-if="!$v.registerData.password_confirmation.required">To pole jest wymagane!</span>
                            <span v-if="!$v.registerData.password_confirmation.passwordRegex">Hasło musi posiadać 8 znaków w tym jedną literę, jeden znak specjalny oraz jedną liczbę</span><br/>
                            <span v-if="!$v.registerData.password_confirmation.sameAs">Hasła nie są takie same!</span>
                        </div>
                    </div>
                    <div v-if="submitStatus === 'ERROR'" class="alert alert-danger" role="alert">
                        Prosimy o poprawne wypełnienie formularza.
                    </div>
                    <div v-if="errors && errors.name" class="alert alert-danger" role="alert">
                        {{ errors.name[0] }}
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

</style>
<script>
import { required, minLength, email, maxLength, sameAs, helpers } from 'vuelidate/lib/validators';
const passwordRegex = helpers.regex('passwordRegex', /^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]{8,}$/);
    export default {
        data(){
            return{
                registerData:{
                    name: null,
                    email: null,
                    password: null,
                    password_confirmation: null,
                },
                errors: null,
                submitStatus: null,
            }
        },
        methods:{
            async sendRegister(){
                this.errors = null;
                this.$v.$touch();
                if (this.$v.$invalid) {
                    this.submitStatus = 'ERROR';
                }else{
                    this.submitStatus = null;
                    await axios.get("/sanctum/csrf-cookie");
                    await axios.post("/api/register", this.registerData).then(response=>{
                        this.registerData = {};
                        this.$router.push({name: 'login'});
                        this.$toasted.success("Zarejestrowano pomyślnie",{
                            action : {
                                text : 'OK',
                                onClick : (e, toastObject) => {
                                    toastObject.goAway(0);
                                }
                            },
                            duration: 8000,
                            icon: 'account_circle',
                            position: "bottom-right",
                        });
                    }).catch(error=>{
                        if (error.response.status === 422) {
                            this.errors = error.response.data.errors || {};
                        }else{
                            this.$store.commit("errorState/setError", error.message);
                        }
                    });
                }
            }
        },
        validations:{
            registerData:{
                name: {
                    required,
                    minLength: minLength(3),
                    maxLength: maxLength(30),
                },
                email: {
                    required,
                    email
                },
                password: {
                    required,
                    passwordRegex

                },
                password_confirmation: {
                    required,
                    passwordRegex,
                    sameAsPassword: sameAs('password')
                },
            },
        }
    }
</script>
