<template>
<div class="col-md-6">
    <div class="card">
        <div class="card-header">Zmiana Hasła</div>
        <form class="card-body">
            <div class="form-group">
                <label for="actual_password">Aktualne hasło</label>
                <input type="password" class="form-control" name="actual_password" v-model="$v.formData.actual_password.$model"
                    :class="{'is-invalid': $v.formData.actual_password.$error, 'is-valid': !$v.formData.actual_password.$invalid}"/>
                <div class="invalid-feedback">
                    <span v-if="!$v.formData.actual_password.required">To pole jest wymagane!</span>
                    <span v-if="!$v.formData.actual_password.minLength">Hasło musi mieć min - {{$v.formData.password.$params.minLength.min}} znaków!</span>
                </div>
            </div>
            <div class="form-group">
                <label for="password">Nowe hasło</label>
                <input type="password" class="form-control" name="password" v-model="$v.formData.password.$model"
                    :class="{'is-invalid': $v.formData.password.$error, 'is-valid': !$v.formData.password.$invalid}"/>
                <div class="invalid-feedback">
                    <span v-if="!$v.formData.password.required">To pole jest wymagane!</span>
                    <span v-if="!$v.formData.password.minLength">Hasło musi mieć min - {{$v.formData.password.$params.minLength.min}} znaków!</span>
                </div>
            </div>
            <div class="form-group">
                <label for="password">Powtórz hasło</label>
                <input type="password" class="form-control" name="password" v-model="$v.formData.password_confirmation.$model"
                    :class="{'is-invalid': $v.formData.password_confirmation.$error, 'is-valid': !$v.formData.password_confirmation.$invalid}"/>
                <div class="invalid-feedback">
                    <span v-if="!$v.formData.password_confirmation.required">To pole jest wymagane!</span>
                    <span v-if="!$v.formData.password_confirmation.minLength">Hasło musi mieć min - {{$v.formData.password_confirmation.$params.minLength.min}} znaków!</span>
                    <span v-if="!$v.formData.password_confirmation.sameAs">Hasła nie są takie same!</span>
                </div>
                <div v-if="submitStatus === 'ERROR'" class="alert alert-danger" role="alert">
                    Prosimy o poprawne wypełnienie formularza.
                </div>
                <div v-if="errors" class="alert alert-danger" role="alert">
                    {{ errors }}
                </div>
            </div>
                <button class="btn btn-primary" @click.prevent="updatePassword">Zapisz zmiany</button>
                <button class="btn btn-danger" @click.prevent="setChangePassword">Anuluj</button>
        </form>
    </div>
</div>
</template>
<style scoped>

</style>
<script>
import { required, minLength, sameAs } from 'vuelidate/lib/validators';
export default{
    data(){
        return{
            errors: null,
            submitStatus: null,
            user: null,
            formData:{
                password: null,
                password_confirmation: null,
                actual_password: null,
            }
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
                await axios.post("/api/user/updatepassword", this.formData).then(response=>{
                    this.formData = {};
                    this.$store.commit('passwordState/toggleChangePassword', false);
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
        setChangePassword(){
            this.$store.commit('passwordState/toggleChangePassword', false);
        },
        async getUser(){
            try{
                let userGet = await axios.get("/api/user");
                this.user = userGet;
            }catch(err){
                this.$store.commit("errorState/setError", error.message);
            }
        }
    },
    created(){
        this.getUser();
    },
    beforeDestroy() {
        this.$store.commit('passwordState/toggleChangePassword', false);
    },
    validations:{
        formData:{
            actual_password:{
                required,
                minLength: minLength(6)
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

