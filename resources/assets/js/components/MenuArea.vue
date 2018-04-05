<template>
    <div id="menu" :class="color">
        <slot />
    </div>
</template>

<script>
export default {
	name: "menu-area",
	props: ["color"]
}
</script>

<style>
#menu {
	height: 20px;
	background-color: #efefef;
	border-radius: 20px;
	padding: 10px 15px;
	cursor: pointer;
	float: right;
	margin-bottom: 10px;
	-webkit-user-select: none;      
	   -moz-user-select: none;
		-ms-user-select: none;
		 -o-user-select: none;
			user-select: none;
}

.green{
	background-color: #B0FFAD !important;
}

.red{
	background-color: #FFB0AD !important;
}

#menu p {
	display:inline-block;
}

#menu p:not(.son) {
	margin-right:10px;
	padding-right:10px;
	border-right: 1px solid #c3c3c3;
}

#menu i, .element i {
	margin-right:5px;
}
</style>
