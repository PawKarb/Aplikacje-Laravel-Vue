<template>
    <div v-if="this.token">
    <form @submit.prevent="send">
        <div class="form-group">
            <label for="title">Tytuł</label>
            <input type="text" v-model="addFields.title"/>
            <div v-if="errors && errors.title" class="text-danger">{{ errors.title[0]}}</div>
        </div>
        <div  class="form-group">
            <label for="text">Tekst</label>
            <textarea rows="3" v-model="addFields.text"></textarea>
            <div v-if="errors && errors.text" class="text-danger">{{ errors.text[0] }}</div>
        </div>
        <div class="form-group">
            <label for="data_zakonczenia">Data zakończenia</label>
            <input type="date" v-model="addFields.data_zakonczenia" />
            <div v-if="errors && errors.data_zakonczenia" class="text-danger">{{ errors.data_zakonczenia[0] }}</div>
        </div>
        <input type="hidden" name="_token" :value="csrf">
        <div v-if="!isEdit">
            <input class="btn btn-primary" type="submit" value = "Zapisz"/>
            <button class="btn btn-secondary" type="reset">Anuluj</button>
        </div>
        <div v-else>
            <button class="btn btn-primary" @click.prevent="editRecord()">Edytuj</button>
            <button class="btn btn-secondary" v-on:click="clearData()">Cofnij</button>
        </div>
    </form>
    <table>
        <tr>
            <th>Lp.</th>
            <th>Tytuł</th>
            <th>Tekst</th>
            <th>Data utworzenia</th>
            <th>Data zakończenia</th>
            <th>Wykonano</th>
            <th>Akcja</th>
        </tr>
        <tr v-for="list in lists" v-bind:key="list.id">
            <td>{{list.id}}</td>
            <td>{{list.title}}</td>
            <td>{{list.text}}</td>
            <td>{{list.data_utworzenia}}</td>
            <td>{{list.data_zakonczenia}}</td>
            <td v-if="list.is_end==0" v-on:click="makeDone(list.id,0)">
                NIE
            </td>
            <td v-else v-on:click="makeDone(list.id,1)">
                TAK
            </td>
            <td>
                <button class="btn btn-primary" v-on:click="editDataForm(list,list.id)">Edytuj</button>
                <button class="btn btn-primary" v-on:click="deleteRecord(list.id)">Usuń</button>
            </td>
        </tr>
    </table>
    <div>
    <button class="btn btn-secondary" onclick="location.href='/'">Powrót</button>
    </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                currentUser: {},
                token: localStorage.getItem('token'),
                lists: [],
                fields:{
                    done:0,
                },
                addFields: {
                    id: null,
                    title:null,
                    text:null,
                    data_zakonczenia:null,
                },
                errors: {},
                isEdit: 0,
            }
        },
        methods: {
            read() {
                axios.get('/api/list').then(({ data }) => {
                    this.lists = data;
                }).catch((err) => console.error(err));
            },
            deleteRecord(id){
                if(confirm("Czy na pewno chcesz usunąć ten element?")){
                    axios.delete('/delete/'+id).then(response =>{
                        window.location.reload();
                    }).catch(error => {
                        console.log(error);
                })
                }
            },
            makeDone(id,is_end){
                if(is_end){
                    this.fields.done = 0;
                }else{
                    this.fields.done = 1;
                }
                axios.post('/list/makedone/'+id,this.fields).then(response=>{
                    window.location.reload();
                }).catch(error => {
                    console.log(error);
            })
            },
            send() {
                this.errors = {};
                axios.post('/savelist', this.addFields).then(response => {
                    alert("Zapisano");
                    this.addFields = {};
                    window.location.reload();
                }).catch(error => {
                    if (error.response.status === 422) {
                        this.errors = error.response.data.errors || {};
                    }});
            },
            editDataForm(editList,id){
                this.isEdit = 1;
                this.addFields.id = id;
                this.addFields.title = editList.title;
                this.addFields.text = editList.text;
                this.addFields.data_zakonczenia = editList.data_zakonczenia;
            },
            clearData(){
                this.isEdit = 0;
                this.addFields = {};
            },
            editRecord(){
                this.errors = {};
                axios.post('/list/edit/'+this.addFields.id, this.addFields).then(response => {
                    alert("Zaktualizowano dane");
                    this.addFields = {};
                    window.location.reload();
                }).catch(error => {
                    if (error.response.status === 422) {
                        this.errors = error.response.data.errors || {};
                    }});
            }
        },
        mounted() {
            if(!this.token){
                alert('Jesteś niezalogowany!!!');
                location.href = '/';
            }
            window.axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`;
            axios.get('/api/user').then(response =>{
                this.currentUser = response.data;
            }).catch(errors =>{
                console.log(errors);
            });
            this.read();
            }
    }
</script>
<style>
    h1{
        text-align: center;
    }
    table{
        margin-left: auto;
        margin-right: auto;
    }
    table, th, td{
        border: 1px solid black;
    }
    tr{
        text-align:center;
    }
    td{
        width: 200px;
    }
    form{
        text-align: center;
    }
</style>
