<template>
  <button class="input-buton" type="button">{{ label }}</button>
</template>

<script>
export default {
    name: "input-button",
    props: ["label"]
}
</script>

<style>
.input-buton {
	width: 100%;
	padding: 4px 0;
	font-size: 20px;
	background-color: #2CB835;
	color: white;
	border-radius: 30px;
	border:none;
	margin-top:10px;
	cursor:pointer;
    -webkit-transition: 0.5s;
			transition: 0.5s;
}

.input-buton:hover {
	color: #2CB835;
	background-color: #efefef;
}
</style>
