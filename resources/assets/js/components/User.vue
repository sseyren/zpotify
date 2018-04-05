<template>
    <container>
        <h2 style="text-align:center" v-if="isLoading">{{ loadingMessage }}</h2>
        <template v-else>
            <content-area position="left">
                <img v-if="reqUser.ppExist" :src="`/storage/users/${reqUser.username}.jpg`"/>                
                <img v-else src="../../images/pp.png"/>
            </content-area>
            <content-area position="right">
                <template v-if="editView">
                    <edit-user :user="this.user" v-on:userEditComplete="userEditComplete"></edit-user>
                    <div id="editButtons">
                        <menu-area color="green" v-on:click.native="toggleEditView">
                            <p class="son"><i class="fa fa-random" aria-hidden="true"></i>Vazgeç</p>
                        </menu-area>
                    </div>
                </template>
                <template v-if="!editView">
                    <list type="custom" style="margin-bottom: 10px">
                        <div class="element"><h2>Profil Bilgisi</h2></div>
                        <div class="element">
                            <div class="sol"><span><i class="fa fa-user" aria-hidden="true"></i>Kullanıcı Adı: {{ reqUser.username }}</span></div>
                        </div>
                        <div class="element">
                            <div class="sol"><span><i class="fa fa-user" aria-hidden="true"></i>Adı: {{ reqUser.name }}</span></div>
                        </div>
                        <div class="element">
                            <div class="sol"><span><i class="fa fa-share" aria-hidden="true"></i>Paylaşım Sayısı: {{ reqUser.uploadCount }}</span></div>
                        </div>
                        <div class="element">
                            <div class="sol"><span><i class="fa fa-calendar" aria-hidden="true"></i>Katıldığı Tarih: {{ reqUser.created_at }} </span></div>
                        </div>
                    </list>
                    <menu-area color="green" v-on:click.native="toggleEditView" v-if="userIsMe">
                        <p class="son"><i class="fa fa-random" aria-hidden="true"></i>Düzenle</p>
                    </menu-area>
                </template>
            </content-area>
            <text-input placeholder="Kullanıcının yükledikleri..." v-model="query" big="true"></text-input>
            <list v-if="!isQueryEmpty" :resource="`/api/tracks/search?q=${query}&user=${reqUser.username}`" name="Sonuçlar"></list>
            <list :resource="`/api/tracks/search?user=${reqUser.username}`" name="Kullanıcının Yükledikleri"></list>
        </template>
    </container>
</template>

<script>
import Container from "./Container.vue";
import ContentArea from "./ContentArea.vue";
import List from "./List.vue";
import MenuArea from "./MenuArea.vue";
import TextInput from "./TextInput.vue";
import EditUser from "./EditUser.vue";

export default {
  name: "user-view",
  data() {
    return {
      reqUser: {},
      isLoading: true,
      loadingMessage: "Yükleniyor...",
      userIsMe: false,
      query: "",
      editView: false
    };
  },
  props: ["user"],
  computed:{
    isQueryEmpty(){
      return this.query.trim() == ""
    }
  },
  methods:{
    toggleEditView(){
      this.editView = (this.editView) ? false : true;
    },
    getUser(){
      this.isLoading = true;
      axios
      .get(`/api/users/${this.$route.params.username}`)
      .then(res => {
        this.reqUser = res.data.data;
        this.isLoading = false;
        this.userIsMe =
          this.user && this.user.username == this.reqUser.username ? true : false;
      })
      .catch(err => {
        this.loadingMessage = `Bir hata oluştu: ${err}`;
        console.log(err);
      });
    },
    userEditComplete(){
      this.getUser();
      this.editView = false;
    }
  },
  created() {
    this.getUser();
  },
  components: {
    Container, ContentArea, List, MenuArea, TextInput, EditUser
  }
};
</script>

<style>
#editButtons{
    overflow: hidden;
}
</style>
