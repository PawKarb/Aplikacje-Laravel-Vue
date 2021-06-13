<template>
    <div>
        <h2 class="text-center" v-if="user">Zalogowany użytkownik: {{user.data.name}}</h2>
        <button class="btn btn-primary text-center" @click.prevent="sendNotify">Wyślij powiadomienie</button>
    </div>
</template>
<style scoped>

</style>
<script>
    export default {
        data() {
            return {
                user: null
            };
        },
        created() {
            this.getUser();
        },
        methods: {
            async getUser() {
                try {
                    let user = await axios.get("/api/user");
                    this.user = user;
                } catch (err) {
                    this.$store.commit("errorState/setError", err.message);
                }
            },
            async sendNotify(){
                await axios.get("/sanctum/csrf-cookie");
                await axios.post("/api/notify/"+this.user.data.id).then(response=>{
                    this.$toasted.success("Powiadomienie wysłano",{
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
                })
            }
        }
    }
</script>
