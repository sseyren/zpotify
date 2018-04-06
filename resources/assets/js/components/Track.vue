<template>
    <container>
        <h2 style="text-align:center" v-if="isLoading">{{ loadingMessage }}</h2>
        <template v-else>
            <content-area position="left" style="margin-bottom: 40px">
                <img :src="`/storage/covers/${track.id}.jpg`" v-if="track.coverExist"/>
                <img src="../../images/kf.png" v-else/>
                <list type="custom" style="margin-bottom: 10px" v-if="editView">
                    <div class="element"><h2>Parça Bilgisi</h2></div>
                    <div class="element">
                        <div class="sol"><span><i class="fa fa-user" aria-hidden="true"></i>Sanatçı: {{ track.artist }}</span></div>
                    </div>
                    <div class="element">
                        <div class="sol"><span><i class="fa fa-music" aria-hidden="true"></i>Parça: {{ track.name }}</span></div>
                    </div>
                    <div class="element">
                        <div class="sol"><span><i class="fa fa-play-circle" aria-hidden="true"></i>Albüm: {{ track.album || "Bilinmeyen" }}</span></div>
                    </div>
                    <div class="element">
                        <div class="sol"><span><i class="fa fa-calendar" aria-hidden="true"></i>Yüklendiği Tarih: {{ track.created_at }} </span></div>
                    </div>
                    <router-link :to="`/user/${track.uploader}`"><div class="element">
                        <div class="sol"><span><i class="fa fa-share" aria-hidden="true"></i>Yükleyen: <u>{{ track.uploader }}</u></span></div>
                    </div></router-link>
                </list>
            </content-area>
            <content-area position="right">
                <template v-if="editView">
                    <edit-track :track="this.track" v-on:trackEditComplete="trackEditComplete"></edit-track>
                    <div id="editButtons">
                        <menu-area color="green" v-on:click.native="toggleEditView">
                            <p class="son"><i class="fa fa-random" aria-hidden="true"></i>Vazgeç</p>
                        </menu-area>
                        <menu-area color="red" v-on:click.native="deleteTrack">
                            <p class="son"><i class="fa fa-random" aria-hidden="true"></i>Parçayı Sil</p>
                        </menu-area>
                    </div>
                </template>
                <template  v-if="!editView">
                    <list type="custom" style="margin-bottom: 10px">
                        <div class="element"><h2>Parça Bilgisi</h2></div>
                        <div class="element">
                            <div class="sol"><span><i class="fa fa-user" aria-hidden="true"></i>Sanatçı: {{ track.artist }}</span></div>
                        </div>
                        <div class="element">
                            <div class="sol"><span><i class="fa fa-music" aria-hidden="true"></i>Parça: {{ track.name }}</span></div>
                        </div>
                        <div class="element">
                            <div class="sol"><span><i class="fa fa-play-circle" aria-hidden="true"></i>Albüm: {{ track.album || "Bilinmeyen" }}</span></div>
                        </div>
                        <div class="element">
                            <div class="sol"><span><i class="fa fa-calendar" aria-hidden="true"></i>Yüklendiği Tarih: {{ track.created_at }} </span></div>
                        </div>
                        <router-link :to="`/user/${track.uploader}`"><div class="element">
                            <div class="sol"><span><i class="fa fa-share" aria-hidden="true"></i>Yükleyen: <u>{{ track.uploader }}</u></span></div>
                        </div></router-link>
                    </list>
                    <menu-area v-if="autoPlay" color="green" v-on:click.native="toggleAutoPlay">
                        <p class="son"><i class="fa fa-toggle-on" aria-hidden="true"></i>Otomatik Oynatma: {{ autoPlayStatus }}</p>
                    </menu-area>
                    <menu-area v-else v-on:click.native="toggleAutoPlay">
                        <p class="son"><i class="fa fa-toggle-off" aria-hidden="true"></i>Otomatik Oynatma: {{ autoPlayStatus }}</p>
                    </menu-area>
                    <audio controls autoplay preload="false" id="oynatici" v-on:ended="trackEnded">
                        <source :src="`/storage/contents/${track.id}.mp3`" type="audio/mpeg">
                        Tarayıcınız bu ses dosyasını desteklemmiyor.
                    </audio>
                    <menu-area v-if="trackIsForMe" v-on:click.native="toggleEditView" style="float:left">
                        <p class="son"><i class="fa fa-random" aria-hidden="true"></i>Düzenle</p>
                    </menu-area>
                    <menu-area color="green" v-on:click.native="goRandomTrack">
                        <p class="son"><i class="fa fa-random" aria-hidden="true"></i>Rastgele</p>
                    </menu-area>
                </template>
            </content-area>
        </template>
    </container>
</template>

<script>
import Container from "./Container.vue";
import ContentArea from "./ContentArea.vue";
import List from "./List.vue";
import MenuArea from "./MenuArea.vue";
import EditTrack from "./EditTrack.vue";

export default {
  name: "track-view",
  data() {
    return {
      track: {},
      isLoading: true,
      loadingMessage: "Yükleniyor...",
      autoPlay: this.$cookie.get("autoPlay") == "true" ? true : false,
      trackIsForMe: false,
      editView: false
    };
  },
  props: ["user"],
  computed: {
    autoPlayStatus() {
      return this.autoPlay ? "AÇIK" : "KAPALI";
    }
  },
  methods: {
    getTrack(){
      this.isLoading = true;
      axios
      .get(`/api/tracks/${this.$route.params.trackID}`)
      .then(res => {
        this.track = res.data.data;
        this.isLoading = false;
        this.trackIsForMe =
          this.user && this.user.username == this.track.uploader ? true : false;
      })
      .catch(err => {
        this.loadingMessage = `Bir hata oluştu: ${err}`;
      });
    },
    toggleAutoPlay() {
      this.autoPlay = this.autoPlay ? false : true;
      this.$cookie.set("autoPlay", this.autoPlay, { expires: "1Y" });
    },
    goRandomTrack() {
      this.$router.push(`/track/${this.track.randomTrack}`);
    },
    toggleEditView() {
      this.editView = (this.editView) ? false : true;
    },
    trackEditComplete(){
        this.getTrack();
        this.editView = false;
    },
    deleteTrack(){
        let confirm = window.confirm("Parçayı silmek istediğinizden emin misiniz?\nBu işlem geri alınamaz!");
        if(confirm){
            axios.delete(`/api/tracks/${this.track.id}`);
            this.$router.push(`/`);
        }
    },
    trackEnded(){
        if(this.autoPlay)
            this.goRandomTrack();
    }
  },
  created() {
    this.getTrack();
  },
  components: {
    Container,
    ContentArea,
    List,
    MenuArea,
    EditTrack
  }
};
</script>

<style>
#oynatici {
  width: 100%;
  margin-bottom: 10px;
}

#editButtons{
    overflow: hidden;
}
</style>
