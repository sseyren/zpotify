<template>
  <div>
    <label>{{ label }}</label>
    <input ref="input" v-bind:value="value" v-on:input="updateValue($event.target.value)" :class="customClass" :type="type" :placeholder="placeholder">
  </div>
</template>

<script>
export default {
    name: "text-input",
		props: ["value", "label", "placeholder", "type", "big"],
		methods: {
			updateValue: function (value) {
				this.$emit('input', value)
			}
		},
		computed:{
			customClass(){
				let className = "arama-cubugu";
				return (this.big) ? className + " ana-sorgu" : className;
			}
		}
}
</script>

<style>
.arama-cubugu {
	width: calc(100% - 28px);
	padding: 6px 10px;
	font-size: 16px;
	margin: 8px 0 15px;
	border: 2px solid #999999;
    -webkit-transition: 0.5s;
			transition: 0.5s;
	border-radius: 60px;
	background-color: #ffffff;
}

.arama-cubugu:focus {
	border: 2px solid #35dc40;
}

label {
	font-size: 16px;
	font-weight: normal;
}

.ana-sorgu{
	width: calc(100% - 45px);
	padding: 20px;
  font-size: 20px;
  margin-bottom: 40px
}
</style>
