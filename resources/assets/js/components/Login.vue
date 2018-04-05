<template>
  	<login-form>
        <form-message v-for="message in messages" :key="message.id" :message="message"></form-message>
		<div id="giris-form">
			<text-input label="Kullanıcı Adı: " v-model="username" v-on:keyup.enter.native="sendLoginReq"></text-input>
			<text-input label="Parola: " v-model="password" type="password" v-on:keyup.enter.native="sendLoginReq"></text-input>
			<input-button label="Giriş!" v-on:click.native="sendLoginReq"></input-button>
			<p>Giriş yaparak <u><router-link to="/kk">Kullanım Koşullarını</router-link></u> kabul etmiş olursunuz.</p>
			<p>Hesabın yok mu? <u><router-link to="/sign">Kayıt ol!</router-link></u></p>
		</div>
  	</login-form>
</template>

<script>
import LoginForm from "./LoginForm.vue"
import TextInput from "./TextInput.vue"
import InputButton from "./InputButton.vue"
import FormMessage from "./FormMessage.vue"

export default {
    name: "login",
	components:{
		FormMessage, TextInput, InputButton, LoginForm
	},
	data(){
		return{
			messages: [],
			username: "",
			password: ""
		}
	},
	methods:{
		sendLoginReq(){
			axios.post("/api/token", {
				username: this.username,
				password: this.password
			}).then(res => {
				this.$emit("jwtChanged", {
					user: res.data.data.user[0],
					jwt: res.data.data.token.token
				})
				this.$router.push("/")
			}).catch(err => {
				this.messages = [
					{text: "Kullanıcı adı veya parola hatalı.", color: "red"}
				]
			})
			this.username = this.password = ""
			this.messages = [{text: "Lütfen bekleyin...", color: "green"}]
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
