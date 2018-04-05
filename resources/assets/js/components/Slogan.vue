<template>
  <div id="slogan">
		<slot/>
  </div>
</template>

<script>
export default {
    name: "slogan"
}
</script>

<style lang="scss">
@import "../../scss/variables.scss";

#slogan {
	display: none;
	font-size: 72px;
	color: #999999;
	text-align: center;
	margin-bottom: 20px;
	font-weight: normal;
}

@media screen and (min-width: $large) {
	#slogan {
		display:block;
    }
}
</style>
