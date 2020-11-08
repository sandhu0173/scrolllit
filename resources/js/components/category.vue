<template>
<div>
    <navbar></navbar>
       <div class="mb-2 mt-1 text-center">
         <h2 class="site-title text-light ">{{sitetitle}}</h2>
         </div>    
    <div class="mt-1 mb-1">
        
        <div class="filter-buttons filter-buttons--big-font">
            <button v-on:click="filter('picture')" class="filter-buttons__button " v-bind:class="[isFilter=='picture' ? 'filter-buttons__button--active' : '']"><i class="fa fa-picture-o"></i> Pictures</button>
        <button  v-on:click="filter('videos')" v-bind:class="[isFilter=='videos' ? 'filter-buttons__button--active' : '']" class="filter-buttons__button filter-buttons__button--gifs"><i class="fa fa-video-camera"></i> Videos</button>
        
        </div>
    </div>
    <div class="mt-1 mb-1">
        
        <div class="filter-buttons filter-buttons--big-font">
            <button class="filter-buttons__button" v-on:click="topposts"  v-bind:class="{ 'filter-buttons__button--active': isTop }"><i class="fa fa-line-chart"></i> Top</button>
            <button class="filter-buttons__button" v-on:click="hotposts"  v-bind:class="{ 'filter-buttons__button--active': isHot }"><i class="fa fa-free-code-camp"></i> Hot</button>
        </div>
    </div>
    <div class="mt-1 mb-1" v-if="isTop">
        
        <div class="filter-buttons filter-buttons--big-font">
          <div class="btn-group">
  <button type="button" class="btn label">Popular: </button>
  <button type="button" class="btn btn-secondary" v-bind:class="[topFill=='today' ? 'active' : '']" v-on:click="topfilter('today')">Today</button>
  <button type="button" class="btn btn-secondary"  v-bind:class="[topFill=='week' ? 'active' : '']" v-on:click="topfilter('week')" >week</button>
  <button type="button" class="btn btn-secondary"  v-bind:class="[topFill=='all' ? 'active' : '']" v-on:click="topfilter('all')">all time</button>
</div>
        </div>
    </div>
<div class="container-fluid pl-0">
 

      <div class="row">  
<div v-for="cat in cats" v-bind:key="cat.key" class="col-md-12 mb-3">
    

<slider :cat="cat" :type="type" :filter="isFilter" :topFill="topFill" ></slider>                       
                    </div>
                    </div>
  </div>
  </div>

</template>

<script>
import slider from './slider.vue'
import navbar from './navbar.vue'
   export default{
        
  data(){
    return {
      cats:"",
      isTop:false,
      isHot:false,
      type:'all',
      isFilter:"all",
      resources:"",
       searchQuery:"",
       offset:0,
       topFill:'all'
    }
  },
  components: {
            slider,navbar
        },
  beforeMount(){
    this.cat=this.$route.params.slug;
    this.subcats();
    //this.allposts();
  },
  watch: {
  searchQuery(after, before) {
    this.searchSubreddit();
  }
},

  methods: {

    subcats:function(){
      this.cats="";
        axios.get(this.appurl+'api/subcats/'+this.cat+'/'+this.offset)
        .then((response)=>{ 
          this.cats=response.data.categories;
          
        })
        .catch(error=>"something went wrong")
    },
    topfilter(f){
      this.topFill=f;
      this.subcats();
    },
    topposts:function(){
      this.isTop=true;
      this.isHot=false;
      this.type="top";
      this.subcats();
    },
    hotposts:function(){
      this.isTop=false;
      this.isHot=true;
      this.type="hot";
      this.subcats();
      
    },
    filter(f){
      this.isFilter=f;
        this.subcats();
    }
    
  },
  
  
}
</script>
<style scoped>


</style>