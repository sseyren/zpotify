<template>
  <div>
    <topbar :user="user" :jwt="jwt" v-if="!inLogPage" v-on:userLogout="userLogout"></topbar>
    <router-view v-on:jwtChanged="jwtChanged" :user="user" v-on:userLogout="userLogout" :key="$route.path"></router-view>
  </div>
</template>

<script>
const base64json = require('base64json');
import topbar from "./TopBar.vue";

export default {
  components: {
    topbar
  },
  data() {
    return {
      user: this.$cookie.get("user") ? JSON.parse(this.$cookie.get("user")) : null,
      jwt: this.$cookie.get("jwt") || null,
    };
  },
  computed: {
    inLogPage() {
      if (this.$route.path == "/login" || this.$route.path == "/sign")
        return true;
      else return false;
    }
  },
  methods: {
    setDeadline(){
      if(this.jwt){
        axios.defaults.headers.common = {
          'Authorization': "Bearer " + this.jwt
        }
        
        let jwtRaw = this.jwt.split(".");
        let jwtData = base64json.parse(jwtRaw[1]);
        let exp = jwtData.exp*1000

        let date = new Date();
        date.setDate(exp);
        
        let gmt = date.toGMTString();

        this.$cookie.set("jwt", this.jwt, {expires: gmt});
        this.$cookie.set("user", JSON.stringify(this.user), {expires: gmt});

        clearTimeout(this.timeout);
        this.timeout = setTimeout(this.userLogout, exp - Date.now());
        console.log(exp - Date.now());
      }
    },
    clearDeadLine(){
      clearTimeout(this.timeout);
      axios.defaults.headers.common = {};
    },
    jwtChanged(obj) {
      this.jwt = obj.jwt;
      this.user = obj.user;
      this.setDeadline();
    },
    userLogout(){
      this.user = null
      this.jwt = null
      this.$cookie.delete("jwt");
      this.$cookie.delete("user");
      this.clearDeadLine();
    }
  },
  created(){
    this.setDeadline()
  }
};
</script>

<style lang="scss">
* {
  margin: 0;
  padding: 0;
  color: black;
  font-family: "Montserrat";
}

body {
  background-color: #f8f8f8;
}

a {
  text-decoration: none;
}

@font-face {
  font-family: "Montserrat";
  font-style: normal;
  font-weight: 400;
  src: local("Montserrat-TR"), url(../../fonts/Montserrat-tr.ttf);
}

#icerik {
  margin-top: 30px;
  margin-bottom: 0px; /*40px*/
  overflow: hidden;
}

#icerik p {
  margin-bottom: 20px;
}
</style>
