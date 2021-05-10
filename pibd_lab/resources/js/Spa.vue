<template>
    <div>
        <h1>Laboratorium PIBD</h1>
        <navigation/>
        <router-view></router-view>
    </div>
</template>
<style scoped>
    h1{
        text-align: center;
    }
</style>
<script>
import Navigation from './components/Navigation.vue'
    export default {
  components: { Navigation }, }
</script>
