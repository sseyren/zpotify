<template>
    <div>
        <form-message v-for="message in messages" :key="message.id" :message="message"></form-message>
        <div class="custom-input" v-if="!removeCover">
            <label>Kapak Fotoğrafı: **</label>
            <input type="file" accept="image/*" ref="trackCover" v-on:change="filesChanged">
        </div>
        <div class="custom-input">
            <span>Kapak Fotoğrafını Kaldır: </span><input type="checkbox" v-model="removeCover">
        </div>
		<text-input label="Sanatçı: " v-model="artist" v-on:keyup.enter.native="sendEditReq"></text-input>
		<text-input label="Parça: " v-model="name" v-on:keyup.enter.native="sendEditReq"></text-input>
		<text-input label="Albüm: " v-model="album" v-if="!removeAlbum" v-on:keyup.enter.native="sendEditReq"></text-input>
        <div class="custom-input">
            <span>Albüm'ü Bilinmeyen Yap: </span><input type="checkbox" v-model="removeAlbum">
        </div>
		<input-button label="Güncelle!" v-on:click.native="sendEditReq"></input-button>
        <p class="form-info">Boş bıraktığınız yerler aynı kalacaktır.</p>
        <p class="form-info">**: jp(e)g, png veya bmp ve max. 1MB olmalıdır.</p>
    </div>
</template>

<script>
import FormMessage from "./FormMessage.vue";
import TextInput from "./TextInput.vue";
import InputButton from "./InputButton.vue";

export default {
  name: "edit-track",
  data() {
    return {
      artist: "",
      name: "",
      album: "",
      trackCover: null,
      removeCover: false,
      removeAlbum: false,
      messages: []
    };
  },
  props: ["track"],
  methods: {
    sendEditReq() {
      this.messages = [{ text: "Lütfen bekleyin...", color: "green" }];
      let editObj = new FormData();
      if (this.artist.trim()) editObj.append("artist", this.artist);
      if (this.name.trim()) editObj.append("name", this.name);
      if (this.removeAlbum) editObj.append("album", "");
      else if (this.album.trim()) editObj.append("album", this.album);
      if (this.removeCover) editObj.append("coverExist", "0");
      else if (this.trackCover) editObj.append("cover", this.trackCover);
      axios
        .post(`/api/tracks/${this.track.id}/edit`, editObj)
        .then(res => {
          this.messages = [{ text: "Parça güncellendi.", color: "green" }];
          this.$emit("trackEditComplete");
        })
        .catch(err => {
          console.log(err);
          this.messages = [];
          let valObj = err.response.data.data;
          Object.keys(valObj).forEach(element => {
            this.messages.push({
              text: valObj[element][0],
              color: "red"
            });
          });
        });
    },
    filesChanged() {
      this.trackCover = this.$refs.trackCover.files[0];
    }
  },
  components: {
    FormMessage,
    TextInput,
    InputButton
  }
};
</script>

<style>
.custom-input {
  margin-bottom: 20px;
}

.custom-input label {
  display: block;
  margin-bottom: 5px;
}

.form-info {
  margin: 10px 0px;
  text-align: center;
  font-size: 12px;
}
</style>
