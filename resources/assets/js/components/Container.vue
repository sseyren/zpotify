<template>
  <div class="ortala">
    <slot/>
  </div>
</template>

<script>
export default {
    name: "container"
}
</script>

<style lang="scss">
@import "../../scss/variables.scss";

.ortala {
	width: calc(100%-20px);
	margin:auto;
	padding-left: 10px;
	padding-right: 10px;
}

@media screen and (min-width: $large) {
	.ortala {
		width:1000px;
    }
}
</style>
