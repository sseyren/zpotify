<template>
<div>
	<div id="baslik-dis">
		<container id="baslik">
			<div id="bas-sol"><router-link to="/"><logo></logo></router-link></div>
			<div id="masaustu">
				<div id="bas-sag">
					<menu-area class="topbar-menu-area">
						<img id="pp" :src="userImage">
						<p id="ka" class="son">{{ userName }}</p>
					</menu-area>
				</div>
				<div id="bas-orta">
					<menu-area class="topbar-menu-area">
						<template v-if="userStatus">
							<router-link to="/upload"><p><i class="fa fa-upload" aria-hidden="true"></i>Yükle</p></router-link>
							<router-link :to="`/user/${user.username}`"><p><i class="fa fa-user" aria-hidden="true"></i>Profil</p></router-link>
							<a v-on:click="userLogout"><p class="son"><i class="fa fa-sign-out" aria-hidden="true"></i>Çıkış</p></a>
						</template>
						<template v-if="!userStatus">
							<router-link to="/sign"><p><i class="fa fa-user-plus" aria-hidden="true"></i>Kayıt</p></router-link>
							<router-link to="/login"><p class="son"><i class="fa fa-sign-in" aria-hidden="true"></i>Giriş</p></router-link>
						</template>
					</menu-area>
				</div>
			</div>
		</container>
	</div>
	<container id="mobil">
		<div id="mobil-menu" v-on:click="toggleMobileMenu">
			<menu-area style="float: none">
				<img id="pp" :src="userImage">
				<p id="ka" class="son">{{ userName }}</p>
			</menu-area>
			<div class="liste" id="mobil-liste" v-if="mobileMenu">
				<template v-if="userStatus">
					<div class="element">
						<div class="sol"><router-link to="/upload"><span><i class="fa fa-upload" aria-hidden="true"></i>Yükle</span></router-link></div>
						<div class="sag"><router-link :to="`/user/${user.username}`"><span><i class="fa fa-user" aria-hidden="true"></i>Profil</span></router-link></div>
					</div>
					<div class="element son">
						<div class="sol"><a v-on:click="userLogout"><span><i class="fa fa-sign-out" aria-hidden="true"></i>Çıkış</span></a></div>
					</div>
				</template>
				<template v-if="!userStatus">
					<div class="element son">
					<div class="sol"><router-link to="/sign"><span><i class="fa fa-user-plus" aria-hidden="true"></i>Kayıt</span></router-link></div>
					<div class="sag"><router-link to="/login"><span><i class="fa fa-sign-in" aria-hidden="true"></i>Giriş</span></router-link></div>
				</div>
				</template>
			</div>
		</div>
	</container>
</div>
</template>

<script>
import Logo from "./Logo.vue"
import MenuArea from "./MenuArea.vue"
import Container from "./Container.vue"

export default {
    name: "topbar",
    data(){
        return{
			mobileMenu: false
        }
	},
	props: {
		user:{
			type: Object,
			required: false
		}
	},
	methods:{
		toggleMobileMenu(){
			this.mobileMenu = (this.mobileMenu) ? false : true
		},
		userLogout(){
			this.$emit("userLogout")
		}
	},
	computed:{
		userImage(){
			if (this.user && this.user.ppExist)
				return `/storage/users/${this.user.username}.jpg`;
			else
				return "../../images/pp.png";
		},
		userName(){
			if(this.user)
				return this.user.username
			else
				return "Ziyaretçi"
		},
		userStatus(){
			if(this.user)
				return true
			else
				return false
		}
	},
	components:{
		Logo, MenuArea, Container
	}
}
</script>

<style lang="scss">
@import "../../scss/variables.scss";

#baslik-dis {
	width:100%;
	height: 90px;
	border-bottom: 2px solid #35dc40;
	background-color: #ffffff;
	margin-bottom: 30px;
	-webkit-box-shadow: 0px 10px 50px 0px rgba(0,0,0,0.2);
	   -moz-box-shadow: 0px 10px 50px 0px rgba(0,0,0,0.2);
			box-shadow: 0px 10px 50px 0px rgba(0,0,0,0.2);
}

#baslik {
	overflow:hidden;
}

#bas-sag, #bas-orta, #ka {
	float:right;
}

#pp {
	height:200%;
	width:40px;
	float:left;
	border-radius:20px;
	margin: -10px 10px 0 -15px;
}

.topbar-menu-area{
	margin: 24px 20px 0px 0px;
}

#mobil-menu {
	width:250px;
	margin-left:auto;
	margin-right:auto;
}

#mobil-menu .liste i {
	margin-left: 0;
}

#mobil-menu #kullanici {
	background-color: #dedede;
}

#bas-sol {
	float: none;
	text-align:center;
}

#masaustu {
	display:none;
}

@media screen and (min-width: $large) {
	#masaustu {
		display:inline;
	}
	
	#mobil {
		display:none;
	}

	#bas-sol {
		float:left;
	}
}
</style>
