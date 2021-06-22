<template>
<div class="row justify-content-center">
    <div v-if="isExpired.data === 1" class="card">
        <div class="card-header">Resetowanie Hasła</div>
        <form class="card-body">
            <div class="form-group">
                <label for="password">Nowe hasło</label>
                <input type="password" class="form-control" name="password" v-model="$v.formData.password.$model"
                    :class="{'is-invalid': $v.formData.password.$error, 'is-valid': !$v.formData.password.$invalid}"/>
                <div class="invalid-feedback">
                    <span v-if="!$v.formData.password.required">To pole jest wymagane!</span>
                    <span v-if="!$v.formData.password.passwordRegex">Hasło musi posiadać 8 znaków w tym jedną literę, jeden znak specjalny oraz jedną liczbę</span>
                </div>
            </div>
            <div class="form-group">
                <label for="password">Powtórz hasło</label>
                <input type="password" class="form-control" name="password" v-model="$v.formData.password_confirmation.$model"
                    :class="{'is-invalid': $v.formData.password_confirmation.$error, 'is-valid': !$v.formData.password_confirmation.$invalid}"/>
                <div class="invalid-feedback">
                    <span v-if="!$v.formData.password_confirmation.required">To pole jest wymagane!</span>
                    <span v-if="!$v.formData.password_confirmation.passwordRegex">Hasło musi posiadać 8 znaków w tym jedną literę, jeden znak specjalny oraz jedną liczbę</span>
                    <span v-if="!$v.formData.password_confirmation.sameAs">Hasła nie są takie same!</span>
                </div>
                <div v-if="submitStatus === 'ERROR'" class="alert alert-danger" role="alert">
                    Prosimy o poprawne wypełnienie formularza.
                </div>
                <div v-if="errors" class="alert alert-danger" role="alert">
                    {{ errors }}
                </div>
            </div>
                <button class="btn btn-primary" @click.prevent="updatePassword">Zapisz Hasło</button>
        </form>
    </div>
    <div class="text-center" v-if="isExpired.data === 0">
        <span class="alert alert-danger" role="alert">Link jest nieaktywny lub wygasł</span>
    </div>
</div>
</template>
<style scoped>
    .text-center{
        font-size: 24px;
        padding-top: 128px;
    }
</style>
<script>
import { required, sameAs, helpers } from 'vuelidate/lib/validators';
const passwordRegex = helpers.regex('passwordRegex', /^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]{8,}$/);
export default{
    data(){
        return{
            errors: null,
            submitStatus: null,
            formData:{
                token: null,
                password: null,
                password_confirmation: null,
            },
            isExpired: 0,
        }
    },
    methods:{
        async updatePassword(){
            this.errors = null;
            this.$v.$touch();
            if (this.$v.$invalid) {
                this.submitStatus = 'ERROR';
            }else{
                this.submitStatus = null;
                await axios.get("/sanctum/csrf-cookie");
                this.formData.token = this.$route.params.token;
                await axios.post("/api/reset/password", this.formData).then(response=>{
                    this.formData = {};
                    this.$router.replace({ name: 'login' });
                    this.$toasted.success("Hasło zostało zmienione",{
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
        async getTokenExpiration(){
            try{
                this.isExpired = await axios.get("/api/reset-password-token/"+this.$route.params.token);
            }catch (err) {
                this.$store.commit("errorState/setError", err.message);
            }
        }
    },
    validations:{
        formData:{
            password: {
                required,
                passwordRegex,
            },
            password_confirmation: {
                required,
                passwordRegex,
                sameAsPassword: sameAs('password')
            },
        },
    },
    created(){
        this.getTokenExpiration();
    }
}
</script>

