require("./bootstrap")

import Vue from 'vue'
import VueRouter from "vue-router"
import VueCookie from "vue-cookie"

Vue.use(VueRouter)
Vue.use(VueCookie)

import App from './components/App.vue'
import Home from "./components/Home.vue";
import Login from "./components/Login.vue";
import Track from "./components/Track.vue";
import User from "./components/User.vue";
import Register from "./components/Register.vue";
import Upload from "./components/Upload.vue";

import NotFound from "./components/NotFound.vue";

const router = new VueRouter({
    routes:[
        { path: "/", component: Home },
        { path: "/login", component: Login },
        { path: "/track/:trackID", component: Track },
        { path: "/user/:username", component: User},
        { path: "/sign", component: Register },
        { path: "/upload", component: Upload },
        
        { path: "/404", component: NotFound },        
        { path: "*", redirect: "/404" }
    ]
})

const app = new Vue({
    el: '#app',
    render: h => h(App),
    router
})