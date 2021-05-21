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
                        <span v-if="errors && errors.name" class="text-danger">{{ errors.name[0] }}</span>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="text" class="form-control" name="email" v-model="$v.registerData.email.$model"
                            :class="{'is-invalid': $v.registerData.email.$error, 'is-valid': !$v.registerData.email.$invalid}"/>
                        <div class="invalid-feedback">
                            <span v-if="!$v.registerData.email.required">To pole jest wymagane!</span>
                            <span v-if="!$v.registerData.email.email">To pole musi być adresem email!</span>
                        </div>
                        <span v-if="errors && errors.email" class="text-danger">{{ errors.email[0] }}</span>
                    </div>
                    <div class="form-group">
                        <label for="password">Hasło</label>
                        <input type="password" class="form-control" name="password" v-model="$v.registerData.password.$model"
                            :class="{'is-invalid': $v.registerData.password.$error, 'is-valid': !$v.registerData.password.$invalid}"/>
                        <div class="invalid-feedback">
                            <span v-if="!$v.registerData.password.required">To pole jest wymagane!</span>
                            <span v-if="!$v.registerData.password.minLength">Pole 'Hasło' musi mieć min - {{$v.registerData.password.$params.minLength.min}} znaków!</span>
                        </div>
                        <span v-if="errors && errors.password" class="text-danger">{{ errors.password[0] }}</span>
                    </div>
                    <div class="form-group">
                        <label for="password_confirmation">Powtórz hasło</label>
                        <input type="password" class="form-control" name="password_confirmation" v-model="$v.registerData.password_confirmation.$model"
                            :class="{'is-invalid': $v.registerData.password_confirmation.$error, 'is-valid': !$v.registerData.password_confirmation.$invalid}"/>
                        <div class="invalid-feedback">
                            <span v-if="!$v.registerData.password_confirmation.required">To pole jest wymagane!</span>
                            <span v-if="!$v.registerData.password_confirmation.minLength">Pole 'Powtórz hasło' musi mieć min - {{$v.registerData.password_confirmation.$params.minLength.min}} znaków!</span>
                            <span v-if="!$v.registerData.password_confirmation.sameAs">Pole 'Powtórz hasło' musi być takie samo jak pole 'Hasło!'</span>
                        </div>
                        <span v-if="errors && errors.password_confirmation" class="text-danger">{{ errors.password_confirmation[0] }}</span>

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
import { required, minLength, email, maxLength, sameAs } from 'vuelidate/lib/validators';
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
                this.errors = {};
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
                        },
                        duration: 8000,
                        icon: 'account_circle',
                        position: "bottom-right",
                    });
                }).catch(error=>{
                    if (error.response.status === 422) {
                        this.errors = error.response.data.errors || {};
                    }else{
                        this.$store.commit("setError", error.message);
                    }
                });
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
                    minLength: minLength(6)

                },
                password_confirmation: {
                    required,
                    minLength: minLength(6),
                    sameAsPassword: sameAs('password')
                },
            },
        }
    }
</script>
