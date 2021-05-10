<template>
    <div id="navigation-buttons">
        <router-link class="btn btn-sm btn-outline-secondary" :to="{ name: 'home' }" tag="button" >Home</router-link>
        <router-link class="btn btn-sm btn-outline-secondary" :to="{ name: 'dashboard' }" tag="button">Dashboard</router-link>
        <router-link class="btn btn-sm btn-outline-secondary" :to="{ name: 'login' }" tag="button">Login</router-link>
        <router-link class="btn btn-sm btn-outline-secondary" :to="{ name: 'register' }" tag="button">Register</router-link><br/>
    </div>
</template>
<style scoped>
    #navigation-buttons{
        text-align: center;
    }
</style>
<script>
    export default { }
</script>
