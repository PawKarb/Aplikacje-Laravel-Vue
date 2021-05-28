<template>
<div v-if="catchError !== null" id="error-modal" class="modal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Upps, coś poszło nie tak!</h4>
      </div>
      <div class="modal-body text-center">
        <h5>{{catchError}}</h5>
      </div>
      <div class="modal-footer">
        <button class="btn btn-primary" data-dismiss="modal" @click.prevent="hideRedirect">Strona główna</button>
        <button class="btn btn-secondary" data-dismiss="modal" @click.prevent="hideNormal">Ukryj</button>
      </div>
    </div>
  </div>
</div>
</template>
<style scoped>
h4{
  color: aliceblue;
}
h5{
  color: aliceblue;
}
.modal-header{
  background-color: rgb(231, 14, 14);
}
.modal-body{
  background-color: indianred;
}
.modal-footer{
  background-color: indianred;
}
</style>
<script>
export default {
  data(){
    return {
      isError: null,
    }
  },
  computed:{
    catchError(){
      this.isError = this.$store.getters['errorState/getError'];
      return this.$store.getters['errorState/getError'];
    }
  },
  methods:{
    hideNormal(){
      this.isError = null;
      this.$store.commit('errorState/setError', null);
    },
    hideRedirect(){
      this.isError = null;
      this.$store.commit('errorState/setError', null);
      this.$router.push('/');
    }
  },
  watch:{
    isError: function(){
        if(this.isError !== null){
            $("#error-modal").modal('show');
        }
    }
  }
}
</script>
