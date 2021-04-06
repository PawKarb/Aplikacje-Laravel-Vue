<template>
    <div>
        <ul>
            <li><a href="/">Strona Główna</a></li>
            <li><a href="list">Lista czynności</a></li>
            <li><a href="author">O Autorze</a></li>
            <li><a href="/api/login">Logowanie</a></li>
            <li><a href="/api/register">Rejestracja</a></li>
        </ul>
        <h1>Witaj na stronie startowej</h1>
        <h2 v-if="currentUser.name">Witaj {{currentUser.name}}</h2>
        <div class="text-center" v-if="currentUser.name">
            <button class="btn btn-danger" @click.prevent="logout()">Wyloguj się</button>
        </div>
        <footer>
            <p>Autor: Paweł Karbowski</p>
        </footer>
    </div>
</template>
<script>
export default {
    data(){
        return{
            currentUser: {},
            token: localStorage.getItem('token'),
        }
    },
    methods:{
        logout(){
            axios.post('/api/logout').then(response =>{
                localStorage.removeItem('token');
                alert("Wylogowoano");
                location.href='/';
            }).catch(errors =>{
                console.log(errors);
            });
        }
    },
    mounted(){
        window.axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`;
        axios.get('/api/user').then(response =>{
            this.currentUser = response.data;
        }).catch(errors =>{
            console.log(errors);
        });
    }
}
</script>

<style>
        ul {
            list-style-type: none;
            padding: 0;
            overflow: hidden;
            width: 26%;
        }
        li {
            float: left;
        }
        li a {
            color: #0000FF;
            text-align: center;
            padding: 14px 16px;
            text-decoration: none;
        }
        li a:hover {
            color: #FF0000;
        }
        h1,h2{
            text-align: center;
        }
        footer {
            position: absolute;
            right: 0;
            bottom: 0;
            left: 0;
            text-align: center;
        }
</style>
