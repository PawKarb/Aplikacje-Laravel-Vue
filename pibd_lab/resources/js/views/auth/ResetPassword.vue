<template>
    <div class="row justify-content-center">
            <div class="card">
                <div class="card-header">Resetowanie hasła</div>
                <form class="card-body">
                    <div class="form-group">
                        <label for="email">Podaj adres e-mail</label>
                        <input type="text" class="form-control" name="email" v-model="$v.email.$model"
                            :class="{'is-invalid': $v.email.$error, 'is-valid': !$v.email.$invalid}"/>
                        <div class="invalid-feedback">
                            <span v-if="!$v.email.required">To pole jest wymagane!</span>
                            <span v-if="!$v.email.email">To pole musi być adresem email!</span>
                        </div>
                    </div>
                    <div v-if="submitStatus === 'ERROR'" class="alert alert-danger" role="alert">
                        Prosimy o poprawne wypełnienie formularza.
                    </div>
                    <div v-if="errors && errors.email" class="alert alert-danger" role="alert">
                        {{ errors.email[0] }}
                    </div>
                    <div class="text-center">
                            <div class="form-group">
                                <button class="btn btn-primary" @click.prevent="sendReset">Resetuj hasło</button>
                                <button class="btn btn-danger" @click.prevent="setResetPassword">Powrót</button>
                            </div>
                    </div>
                </form>
            </div>
    </div>
</template>
<style scoped>

</style>
<script>
import { required, email } from 'vuelidate/lib/validators';
    export default {
        data(){
            return{
                email: null,
                errors: null,
                submitStatus: null,
            }
        },
        methods:{
           async sendReset(){
            this.errors = null;
            this.$v.$touch();
            if (this.$v.$invalid) {
                this.submitStatus = 'ERROR';
            }else{
                await axios.get("/sanctum/csrf-cookie");
                await axios.post("/api/reset-password", {email: this.email}).then(response=>{
                    this.$store.commit('resetPasswordState/toggleResetPassword', false);
                    this.$toasted.success("Link do zmiany hasła został wysłany na email",{
                        action : {
                            text : 'OK',
                            onClick : (e, toastObject) => {
                                toastObject.goAway(0);
                            }
                        },
                        duration: 8000,
                        icon: 'done',
                        position: "bottom-right",
                    });
                }).catch(err=>{
                    if (err.response.status === 422) {
                        this.errors = err.response.data.errors || err.response.data;
                    }else{
                        this.$store.commit("errorState/setError", err.message);
                    }
                });
            }
            },
            setResetPassword(){
                this.$store.commit('resetPasswordState/toggleResetPassword', false);
            },
        },
        validations:{
            email:{
                required,
                email,
            },
        },
    }
</script>
