<template>
    <div>
        <div v-if="position == 'left'" id="icerik-sol">
            <slot/>
        </div>
        <div v-else id="icerik-sag">
            <slot/>
        </div>
    </div>
</template>

<script>
export default {
    name: "content-area",
    props: ["position"]
}
</script>

<style lang="scss">
@import "../../scss/variables.scss";

#icerik-sol {
	width:90%;
	max-width:500px;
	float: none;
	margin-left:auto;
	margin-right:auto;
	margin-bottom:10px;
}

#icerik-sol img{
	width: 100%;
}

#icerik-sag {
	width:100%;
	float: none;
}

@media screen and (min-width: $large) {
    #icerik-sol {
		width:40%;
		float:left;
		margin-left:0;
		margin-right:0;
	}
	
	#icerik-sag {
		width:55%;
		float:right;
	}
}
</style>
