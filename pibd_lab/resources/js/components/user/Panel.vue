<template>
    <div v-if="user" class="row justify-content-center">
        <div v-if="!catchPasswordState" class="col-md-6">
            <div class="card">
                <div class="card-header">Dane Użytkownika</div>
                <form class="card-body">
                    <div class="form-group">
                        <label for="name">Nazwa użytkownika</label>
                        <input type="text" class="form-control" :placeholder="user.data.name" name="name" v-model="$v.formData.name.$model"
                            :class="{'is-invalid': $v.formData.name.$error}"/>
                        <div class="invalid-feedback">
                            <span v-if="!$v.formData.name.minLength">Twoja nazwa nie może być krótsza niż {{$v.formData.name.$params.minLength.min}} znaki</span>
                            <span v-if="!$v.formData.name.maxLength">Twoja nazwa nie może być dłuższa niż {{$v.formData.name.$params.maxLength.max}} znaków</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="email">E-mail</label>
                        <input type="text" class="form-control" :placeholder="user.data.email" name="email" v-model="$v.formData.email.$model"
                            :class="{'is-invalid': $v.formData.email.$error}"/>
                        <div class="invalid-feedback">
                            <span v-if="!$v.formData.email.email">To pole musi być adresem email!</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="first_name">Imię</label>
                        <input type="text" class="form-control" :placeholder="user.data.first_name" name="first_name" v-model="$v.formData.first_name.$model"
                            :class="{'is-invalid': $v.formData.first_name.$error}"/>
                        <div class="invalid-feedback">
                            <span v-if="!$v.formData.first_name.alpha">W tym polu muszą być znaki alfabetyczne!</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="last_name">Nazwisko</label>
                        <input type="text" class="form-control" :placeholder="user.data.last_name" name="last_name" v-model="$v.formData.last_name.$model"
                            :class="{'is-invalid': $v.formData.last_name.$error}"/>
                        <div class="invalid-feedback">
                            <span v-if="!$v.formData.last_name.alpha">W tym polu muszą być znaki alfabetyczne!</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="address">Adres</label>
                        <input type="text" class="form-control" :placeholder="user.data.address" name="address" v-model="formData.address"/>
                    </div>
                    <div class="form-group">
                        <label for="date">Data urodzenia</label>
                        <input type="text" class="form-control" :placeholder="user.data.born_date" onfocus="(this.type='date')" name="date" v-model="formData.born_date"/>
                    </div>
                    <div v-if="submitStatus === 'ERROR'" class="alert alert-danger" role="alert">
                        Prosimy o poprawne wypełnienie formularza.
                    </div>
                    <div v-if="errors" class="alert alert-danger" role="alert">
                        {{ errors }}
                    </div>
                        <button class="btn btn-primary" @click.prevent="updateUser">Zapisz zmiany</button>
                        <button class="btn btn-danger" @click.prevent="deleteUser">Usuń konto</button>
                </form>
            </div>
            <div class="text-center">
                <button class="btn btn-secondary" @click.prevent="setChangePassword">Zmiana hasła</button>
            </div>
        </div>
        <change-password v-if="catchPasswordState"></change-password>
    </div>
</template>
<style scoped>
</style>
<script>
import changePassword from '../user/changePassword.vue';
import { required, minLength, email, maxLength, alpha } from 'vuelidate/lib/validators';
export default{
    data(){
        return{
            user: null,
            formData:{},
            errors: null,
            submitStatus: null,
        }
    },
    methods: {
        setChangePassword(){
            this.$store.commit('passwordState/toggleChangePassword', true);
        },
        async updateUser(){
            this.errors = null;
            this.submitStatus = null;
            this.$v.$touch();
            if (this.$v.$invalid || !this.formData) {
                this.submitStatus = 'ERROR';
            }else{
                this.submitStatus = null;
                await axios.get("/sanctum/csrf-cookie");
                await axios.put("/api/user/"+this.user.data.id, this.formData).then(response=>{
                    this.formData = {};
                    this.$store.commit('passwordState/toggleChangePassword', false);
                    this.$router.push({name: 'dashboard'});
                    this.$toasted.success("Dane zostały zmienione",{
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
        async deleteUser(){
            if(confirm("Czy na pewno chcesz usunąć konto?")){
                await axios.get("/sanctum/csrf-cookie");
                await axios.delete("/api/user/"+this.user.data.id).then(response=>{
                    this.$store.commit('loggedState/toggleLogged', false);
                    this.$store.commit('passwordState/toggleChangePassword', false);
                    this.$router.push({name: 'home'});
                    this.$toasted.success("Konto zostało usunięte",{
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
                    this.$store.commit("errorState/setError", err.message);
                });
            }
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
    computed:{
        catchPasswordState(){
            return this.$store.getters['passwordState/getChangePassword'];
        }
    },
    created(){
        this.getUser();
    },
    components: {changePassword},
    validations:{
        formData:{
            name: {
                minLength: minLength(3),
                maxLength: maxLength(30),
            },
            email: {
                email
            },
            first_name:{
                alpha,
            },
            last_name:{
                alpha,
            },
        },
    }
}
</script>
