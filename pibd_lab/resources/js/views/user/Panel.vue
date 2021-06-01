<template>
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Dane Użytkownika</div>
                <form class="card-body">
                    <div class="form-group">
                        <label for="name">Nazwa użytkownika</label>
                        <input type="text" class="form-control" :placeholder="user.data.name" name="name" v-model="formData.name"/>
                    </div>
                    <div class="form-group">
                        <label for="email">E-mail</label>
                        <input type="text" class="form-control" :placeholder="user.data.email" name="email" v-model="formData.email"/>
                    </div>
                    <div class="form-group">
                        <label for="first_name">Imię</label>
                        <input type="text" class="form-control" :placeholder="user.data.first_name" name="first_name" v-model="formData.first_name"/>
                    </div>
                    <div class="form-group">
                        <label for="last_name">Nazwisko</label>
                        <input type="text" class="form-control" :placeholder="user.data.last_name" name="last_name" v-model="formData.last_name"/>
                    </div>
                    <div class="form-group">
                        <label for="address">Adres</label>
                        <input type="text" class="form-control" :placeholder="user.data.address" name="address" v-model="formData.address"/>
                    </div>
                    <div class="form-group">
                        <label for="date">Data urodzenia</label>
                        <input type="text" class="form-control" :placeholder="user.data.born_date" onfocus="(this.type='date')" name="date" v-model="formData.born_date"/>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Zmiana Hasła</div>
                    <form class="card-body">
                        <div class="form-group">
                            <label for="password">Nowe hasło</label>
                            <input type="text" class="form-control" name="password" v-model="formData.password"/>
                        </div>
                        <div class="form-group">
                            <label for="password">Powtórz hasło</label>
                            <input type="text" class="form-control" name="password" v-model="formData.password_confirmation"/>
                        </div>
                    </form>
            </div>
        </div>
        <button class="btn btn-primary" @click.prevent="updateUser">Zapisz zmiany</button>
        <button class="btn btn-danger" @click.prevent="deleteUser">Usuń konto</button>
    </div>
</template>
<style scoped>
</style>
<script>
export default{
    data(){
        return{
            user: null,
            formData:{
                name: null,
                email: null,
                first_name: null,
                last_name: null,
                address: null,
                born_date: null,
                password: null,
                password_confirmation: null,
            },
        }
    },
    methods:{
        async updateUser(){

        },
        async deleteUser(){
        },
        async getUser(){
            try{
                this.user = await axios.get("/api/user");
            }catch(err){
                this.$store.commit("errorState/setError", error.message);
            }
        }
    },
    created(){
        this.getUser();
    }
}
</script>
