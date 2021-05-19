<template>
    <div>
        <h2 class="text-center" v-if="user">Zalogowany użytkownik: {{user}}</h2>
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
                    this.user = user.data.name;
                } catch (err) {
                    console.log(err);
                }
            }
        }
    }
</script>
