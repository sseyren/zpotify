<template>
    <container>
        <div id="yukleme-alani">
            <form-message v-for="message in messages" :key="message.id" :message="message"></form-message>
            <div class="custom-input">
                <label>Parça: *</label>
                <input type="file" accept="audio/mpeg" ref="content" v-on:change="filesChanged">
            </div>
            <div class="custom-input">
                <label>Albüm kapağı: **</label>
                <input type="file" accept="image/*" ref="cover" v-on:change="filesChanged">
            </div>
			<text-input label="Parça Adı: " v-model="name" v-on:keyup.enter.native="sendUploadReq"></text-input>
			<text-input label="Sanatçı: " v-model="artist" v-on:keyup.enter.native="sendUploadReq"></text-input>
			<text-input label="Albüm: " v-model="album" v-on:keyup.enter.native="sendUploadReq" placeholder="Bilinmiyorsa boş bırkın"></text-input>
			<input-button label="Yükle!" v-on:click.native="sendUploadReq"></input-button>
            <p class="form-info">*: Sadece mp3 ve max. 10MB olmalıdır.</p>
            <p class="form-info">**: isteğe bağlıdır, jp(e)g, png veya bmp ve max. 1MB olmalıdır.</p>
        </div>
    </container>
</template>

<script>
import Container from "./Container.vue";
import InputButton from "./InputButton.vue";
import TextInput from "./TextInput.vue";
import FormMessage from "./FormMessage.vue";

export default {
    name: "upload",
    data(){
        return {
            name: "",
            artist: "",
            album: "",
            content: null,
            cover: null,
            messages: []
        }
    },
    methods:{
        sendUploadReq(){
            if(this.content && this.content.size <= 10240000){
                this.messages = [{text: "Lütfen bekleyin...", color: "green"}]
                let formData = new FormData();
                formData.append("name", this.name);
                formData.append("artist", this.artist);
                formData.append("album", this.album);
                formData.append("content", this.content);
                if (this.cover)
                    formData.append("cover", this.cover)
                axios.post("/api/tracks", formData)
                .then(res => {
                    this.messages = [{text: `Parça yüklendi: /track/${res.data.data.id}`, color: "green", link: `/track/${res.data.data.id}`}]
                }).catch(err => {
                    console.log(err);
                    console.log(err.response.data);
                    this.messages = [];
                    let valObj = err.response.data.data;
                    Object.keys(valObj).forEach(element => {
                        this.messages.push({
                            text: valObj[element][0],
                            color: "red"
                        });
                    });
                })
            }else{
				this.messages = [{text: "Geçerli bir dosya seçmelisiniz.", color: "red"}]
            }
        },
        filesChanged(){
            this.content = this.$refs.content.files[0];
            this.cover = this.$refs.cover.files[0];
        }
    },
    components: {
        Container, InputButton, TextInput, FormMessage
    }
}
</script>

<style lang="scss">
@import "../../scss/variables.scss";

#yukleme-alani{
	width: 100%;
	margin: auto;
}

@media screen and (min-width: $medium) {
	#yukleme-alani{
		width: 40%;
	}
}

.custom-input{
    margin-bottom: 20px
}

.custom-input label{
    display: block;
    margin-bottom: 5px
}

.form-info{
    margin: 10px 0px;
    text-align: center;
    font-size: 12px
}
</style>
