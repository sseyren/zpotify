<template>
  	<login-form>
        <form-message v-for="message in messages" :key="message.id" :message="message"></form-message>
		<div id="giris-form">
			<text-input label="Kullanıcı Adı: " v-model="username" v-on:keyup.enter.native="sendRegisterReq"></text-input>
            <text-input label="Ad Soyad: " v-model="name" v-on:keyup.enter.native="sendRegisterReq"></text-input>
            <text-input label="E-Posta: " v-model="email" v-on:keyup.enter.native="sendRegisterReq"></text-input>
			<text-input label="Parola: " v-model="password" type="password" v-on:keyup.enter.native="sendRegisterReq"></text-input>
            <text-input label="Parola (tekrar): " v-model="passwordAgain" type="password" v-on:keyup.enter.native="sendRegisterReq"></text-input>
			<input-button label="Giriş!" v-on:click.native="sendRegisterReq"></input-button>
			<p>Kayıt olarak <u><router-link to="/kk">Kullanım Koşullarını</router-link></u> kabul etmiş olursunuz.</p>
			<p>Hesabın var mı? <u><router-link to="/login">Giriş yap!</router-link></u></p>
		</div>
  	</login-form>
</template>

<script>
import LoginForm from "./LoginForm.vue"
import TextInput from "./TextInput.vue"
import InputButton from "./InputButton.vue"
import FormMessage from "./FormMessage.vue"

export default {
    name: "register",
	components:{
		FormMessage, TextInput, InputButton, LoginForm
	},
	data(){
		return{
			messages: [],
            username: "",
            name: "",
            email: "",
            password: "",
            passwordAgain: ""
		}
	},
	methods:{
		sendRegisterReq(){
            this.messages = [{text: "Lütfen bekleyin...", color: "green"}]
            if (this.passwordAgain == this.password){
                axios.post("/api/users", {
                    username: this.username,
                    name: this.name,
                    email: this.email,
                    password: this.password
                }).then(res => {
                    this.$router.push("/login")
                }).catch(err => {
                    this.messages = [];
                    let valObj = err.response.data.data;
                    console.log(valObj);
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
		}
	}
}
</script>

<style>
#giris-form {
	margin-top: 20px;
}

#giris-form p {
	font-size: 14px;
	text-align: center;
	margin: 15px 0px 0px;
}
</style>
