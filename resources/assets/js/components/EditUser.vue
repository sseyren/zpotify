<template>
    <div>
        <form-message v-for="message in messages" :key="message.id" :message="message"></form-message>
        <div class="custom-input" v-if="!removePP">
            <label>Kullanıcı Fotoğrafı: **</label>
            <input type="file" accept="image/*" ref="userPP" v-on:change="filesChanged">
        </div>
        <div class="custom-input">
            <span>Profil Fotoğrafını Kaldır: </span><input type="checkbox" v-model="removePP">
        </div>
		<text-input label="İsim: " v-model="name" v-on:keyup.enter.native="sendEditReq"></text-input>
		<text-input label="E-Posta: " v-model="email" v-on:keyup.enter.native="sendEditReq"></text-input>
		<text-input label="Parola: " v-model="password" v-on:keyup.enter.native="sendEditReq" type="password"></text-input>
        <text-input label="Parola (tekrar): " v-model="passwordAgain" v-on:keyup.enter.native="sendEditReq" type="password"></text-input>
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
    name: "edit-user",
    data(){
        return {
            name: "",
            email: "",
            password: "",
            passwordAgain: "",
            pp: null,
            removePP: false,
            messages: []
        }
    },
    props:["user"],
    methods:{
        sendEditReq(){
            this.messages = [{text: "Lütfen bekleyin...", color: "green"}]
            if (this.passwordAgain == this.password){
                let editObj = new FormData();
                if(this.name.trim())
                    editObj.append("name", this.name);
                if(this.email.trim())
                    editObj.append("email", this.email);
                if(this.password.trim())
                    editObj.append("password", this.password);
                if(this.removePP)
                    editObj.append("ppExist", "0");
                else if(this.pp)
                    editObj.append("pp", this.pp);
                axios.post(`/api/users/${this.user.username}/edit`, editObj)
                .then(res => {
                    this.messages = [{text: "Profiliniz güncellendi.", color: "green"}];
                    this.$emit("userEditComplete");
                }).catch(err => {
                    console.log(err);
                    this.messages = [];
                    let valObj = err.response.data.data;
                    Object.keys(valObj).forEach(element => {
                        this.messages.push({
                            text: valObj[element][0],
                            color: "red"
                        });
                    });
                }).then(()=>{
                    this.password = this.passwordAgain = ""
                })
            }else{
                this.messages = [{text: "Parolalar eşleşmiyor", color: "red"}]
            }
        },
        filesChanged(){
            this.pp = this.$refs.userPP.files[0];
        }
    },
    components: {
        FormMessage, TextInput, InputButton
    }
}
</script>

<style>
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
