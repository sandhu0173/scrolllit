<template>
<div v-on:scroll="handleScroll(event)">
    <navbar></navbar>
       <div class="search-bar">
         <div class="search-bar__bar">
           <input  v-model="searchQuery" type="search"  aria-label="Search field" class="search-bar__input" placeholder="Search" value=""><i class="fa fa-search search-bar__icon"></i> 
           <div v-if="resources.length" class="search-result container-fluid" >
  
  <ul class="search-list">
    
    <li v-for="resource in resources" v-bind:key="resource.key">
      <router-link  :to="{ name: 'Subreddit', params: { slug: resource.slug } }" >
      <h3 class="title">{{resource.post.post_title}}</h3>
      <p class="desc">{{resource.link}} </p>
      </router-link>
    </li>
  </ul>
</div>
           </div>
    </div>
    
    <div class="mt-1 mb-1">
        
        <div class="filter-buttons filter-buttons--big-font">
            <button v-on:click="filter('picture')" class="filter-buttons__button " v-bind:class="[isFilter=='picture' ? 'filter-buttons__button--active' : '']"><i class="fa fa-picture-o"></i> Pictures</button>
        <button  v-on:click="filter('videos')" v-bind:class="[isFilter=='videos' ? 'filter-buttons__button--active' : '']" class="filter-buttons__button filter-buttons__button--gifs"><i class="fa fa-video-camera"></i> Videos</button>
        
        <button  v-on:click="filter('adults')" v-bind:class="[isFilter=='adults' ? 'filter-buttons__button--active' : '']" class="filter-buttons__button filter-buttons__button--nsfw"><span class="filter-buttons__icon">18+</span>NSFW</button></div>
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
    <main class="gallery-view">
<div class="horizontal-view">

<div v-for="(cat,index) in cats" v-bind:key="index" class="horizontal-view__row" v-bind:class="{'horizontal-view__row--first':(index==0)}">
    

<slider :cat="cat" :type="type" :filter="isFilter" :topFill="topFill"></slider>                       
                    </div>
                    </div>
    </main>
</div>



</template>

<script>
import debounce from 'lodash/debounce';

import slider from './slider.vue'
import navbar from './navbar.vue'
   export default{
        
  data(){
    return {
      cats:"",
      isTop:true,
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
    this.categories();
    this.allposts();
  },
  watch: {
  searchQuery(after, before) {
    this.searchSubreddit();
  }
},
computed: {
    axiosParams() {
        const params = new URLSearchParams();
        params.append('search', this.searchQuery);
        return params;
    }
},
  methods: {
    searchSubreddit() {
                axios.get(this.appurl+'api/search/subreddit/',{params: this.axiosParams})
        .then((response)=>{
          //console.log(response.data);
          this.resources=response.data.lists;
        } )
  },
    categories:function(){
      this.cats="";
      var that=this;
      that.offset=0;
        axios.get(that.appurl+'api/categories/'+that.offset)
        .then((response)=>{ 
          that.cats=response.data.categories;
          console.log(that.cats);
          that.offset=response.data.offset;
        })
        .catch(error=>"something went wrong")
    },
    adultcategories:function(){
      this.cats="";
        axios.get(this.appurl+'api/adultcategories/'+this.offset)
        .then((response)=>{ 
          this.cats=response.data.categories;
          this.offset=response.data.offset;
        })
    },
    allposts:function(){
      this.isTop=false;
      this.isHot=false;
      this.type="all";
      this.categories();
      this.offset=0;
    },
    topposts:function(){
      this.isTop=true;
      this.isHot=false;
      this.type="top";
      this.categories();
      this.offset=0;
    },
    hotposts:function(){
      this.offset=0;
      this.isTop=false;
      this.isHot=true;
      this.type="hot";
      this.categories();
    },
    filter(f){
      this.isFilter=f;
      if(this.isFilter=='adults')
      {
        this.offset=0;
        this.adultcategories();
      }else{
        this.offset=0;
        this.categories();
      }
      

    },
    topfilter(f){
      this.topFill=f;
      if(this.isFilter=='adults')
      {
        this.offset=0;
        this.adultcategories();
      }else{
        this.offset=0;
        this.categories();
      }
      
    },
    handleScroll(event) {
       var that=this;
       window.onscroll = () => {
    let bottomOfWindow = Math.max(window.pageYOffset, document.documentElement.scrollTop, document.body.scrollTop) + window.innerHeight === document.documentElement.offsetHeight

    if (bottomOfWindow) {
     axios.get(that.appurl+'api/categories/'+that.offset)
        .then((response)=>{ 
          for (let i = 0; i < response.data.categories.length; i++){
                   that.cats.push(response.data.categories[i]); 
                }
          that.offset=response.data.offset;
          
        })
        .catch(error=>"something went wrong")
    }
 }
     
    }
    
    
    
  },
  created() {
    this.handleDebouncedScroll = debounce(this.handleScroll, 100);
    window.addEventListener('scroll', this.handleDebouncedScroll);
  },
  beforeDestroy() {
    // I switched the example from `destroyed` to `beforeDestroy`
    // to exercise your mind a bit. This lifecycle method works too.
    window.removeEventListener('scroll', this.handleDebouncedScroll);
  },
  
  
}
</script>
<style scoped>


</style>