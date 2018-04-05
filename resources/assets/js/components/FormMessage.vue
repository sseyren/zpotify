<template>
  <div>
    <router-link v-if="message.link" :to="message.link">
        <div id="mesaj" v-bind:class="message.color">{{ message.text }}</div>
    </router-link>
    <div id="mesaj" v-if="visible && !message.link" v-on:click="hide" v-bind:class="message.color">{{ message.text }}</div>
  </div>
</template>

<script>
export default {
    name: "form-message",
    props: {
        message: {
            type: Object,
            required: true
        }
    },
    data(){
        return {
            visible: true
        }
    },
    methods:{
        hide(){
            this.visible = false
        }
    }
}
</script>

<style>
#mesaj{
	width: calc(100% - 40px);
	margin-top:20px;
	margin-bottom:20px;
    color:white !important;
	padding: 6px 20px;
	cursor: pointer;
	border-radius: 100px;
}

.red{
    background-color: #FF5454;
}

.green{
    background-color: #2CB835;
}
</style>
