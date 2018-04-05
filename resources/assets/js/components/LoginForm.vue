<template>
  <div id="giris">
      <router-link to="/"><logo></logo></router-link>
      <slot/>
  </div>
</template>

<script>
import Logo from "./Logo.vue"

export default {
    name: "login-form",
    components: {
        Logo
    }
}
</script>

<style>
#giris {
	position: absolute;
	top:50%;
	left:50%;
	border-top-left-radius: 20px;
	border-top-right-radius: 20px;
	border-bottom: 4px solid #35dc40;
	background-color: #ffffff;
	padding: 20px;
	-webkit-box-shadow: 0px 0px 50px 0px rgba(0,0,0,0.3);
	   -moz-box-shadow: 0px 0px 50px 0px rgba(0,0,0,0.3);
			box-shadow: 0px 0px 50px 0px rgba(0,0,0,0.3);
	transform: translate(-50%, -50%);
}
</style>
