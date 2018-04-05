<template>
  <div class="liste">
    <slot v-if="isTypeCustom"/>
    <template v-else>

      <div class="element"><h2>{{ name }}</h2></div>

      <div class="element" v-if="isLoading">
          <div class="orta"><span>Yükleniyor...</span></div>
      </div>

      <div class="element" v-if="isEmpty && !isLoading">
          <div class="orta"><span>Yok pek bişi...</span></div>
      </div>

      <div class="element" v-show="!isLoading && !isEmpty">
        <div class="sol">
          <span v-if="list.current_page == 1" class="disabled"><i class="fa fa-arrow-left disabled" aria-hidden="true"></i>Önceki</span>
          <span v-else v-on:click="getResource(list.prev_page_url)"><i class="fa fa-arrow-left" aria-hidden="true"></i>Önceki</span>
        </div>
        <div class="orta"><span>Sayfa {{ list.current_page }} / {{ list.last_page }}</span></div>
        <div class="sag">
          <span v-if="list.current_page == list.last_page" class="disabled">Sonraki<i class="fa fa-arrow-right disabled" aria-hidden="true"></i></span>
          <span v-else v-on:click="getResource(list.next_page_url)">Sonraki<i class="fa fa-arrow-right" aria-hidden="true"></i></span>
        </div>
      </div>

      <router-link :to="`/track/${track.id}`" v-for="track in list.data" v-bind:key="track.id" v-show="!isLoading">
        <div class="element">
          <div class="sol"><span><i class="fa fa-play-circle" aria-hidden="true"></i>{{track.artist}} - {{track.name}}</span></div>
          <div class="sag"><span></span></div>
          <div class="sag"><span></span></div>
        </div>
      </router-link>

      <div class="element son" v-show="!isLoading && !isEmpty">
        <div class="sol">
          <span v-if="list.current_page == 1" class="disabled"><i class="fa fa-arrow-left disabled" aria-hidden="true"></i>Önceki</span>
          <span v-else v-on:click="getResource(list.prev_page_url)"><i class="fa fa-arrow-left" aria-hidden="true"></i>Önceki</span>
        </div>
        <div class="orta"><span>Sayfa {{ list.current_page }} / {{ list.last_page }}</span></div>
        <div class="sag">
          <span v-if="list.current_page == list.last_page" class="disabled">Sonraki<i class="fa fa-arrow-right disabled" aria-hidden="true"></i></span>
          <span v-else v-on:click="getResource(list.next_page_url)">Sonraki<i class="fa fa-arrow-right" aria-hidden="true"></i></span>
        </div>
      </div>

    </template>
  </div>
</template>

<script>
export default {
  name: "list",
  data() {
    return {
      list: {},
      isLoading: true,
      resourceURL: ""
    };
  },
  props: {
    resource: {
      type: String,
      required: false
    },
    name: {
      type: String,
      required: false
    },
    type: {
      type: String,
      required: false
    }
  },
  methods: {
    getResource(resource) {
      this.isLoading = true;
      if (resource) this.resourceURL = resource;
      axios.get(this.resourceURL).then(res => {
        this.list = res.data.data;
        this.isLoading = false;
      });
    },
    refreshResource(){
      this.resourceURL = this.resource;
      this.getResource();
    }
  },
  computed: {
    isTypeCustom(){
      return (this.type == "custom") ? true : false
    },
    isEmpty(){
      return (this.list.total == 0) ? true : false
    }
  },
  watch:{
    resource(){
      this.refreshResource();
    }
  },
  created() {
    this.refreshResource();
  }
};
</script>

<style>
.liste {
  margin-bottom: 40px;
}

.liste h2 {
  color: #999999;
  text-align: center;
  font-size: 36px;
}

.liste .element {
  padding: 12px 8px;
  overflow: hidden;
  text-align: center;
  border-bottom: 1px solid #c3c3c3;
  -webkit-transition: 0.5s;
  transition: 0.5s;
}

.liste .element .sol {
  float: left;
  margin-right: 10px;
}

.liste .element .disabled {
  color: #999999;
}

.liste .element .sag {
  float: right;
  margin-left: 10px;
}

.liste .element .sag span {
  float: left;
}

.liste .element .orta {
  margin: 0 auto;
  display: inline-block;
}

.liste .element:hover {
  background-color: #b0ffad;
}

.liste .son {
  border: none;
  -webkit-user-select: none;
  -moz-user-select: none;
  -ms-user-select: none;
  -o-user-select: none;
  user-select: none;
}

.liste .son i {
  margin-left: 5px;
}

.liste i {
  margin-left: 0;
}
</style>
