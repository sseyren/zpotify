<template>
  <container>
    <slogan>Hiçbir zaman hiçbir yerde!</slogan>
    <text-input placeholder="Ne dinlemek istersin?" big="true" v-model="query"></text-input>
    <list v-if="!isQueryEmpty" :resource="`/api/tracks/search?q=${query}`" name="Sonuçlar"></list>
    <list resource="/api/tracks" name="Tüm Parçalar"></list>
  </container>
</template>

<script>
import TextInput from "./TextInput.vue";
import Container from "./Container.vue"
import Slogan from "./Slogan.vue"
import List from "./List.vue"

export default {
    name: "home",
    data(){
      return{
        query: ""
      }
    },
    computed:{
      isQueryEmpty(){
        return this.query.trim() == ""
      }
    },
    components:{
      TextInput, Container, Slogan, List
    }
}
</script>

<style>

</style>
