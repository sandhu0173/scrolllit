<template>
<div>
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
<div class="container-fluid page-content">
 

      <div class="row">  
<div v-for="cat in cats" v-bind:key="cat.key" class="col-md-12 mb-3">
    

<slider :cat="cat" :type="type" :filter="isFilter"></slider>                       
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
      isTop:true,
      isHot:false,
      type:top,
      isFilter:"picture",
      resources:"",
       searchQuery:"",
    }
  },
  components: {
            slider,navbar
        },
  beforeMount(){
    this.categories();
    this.topposts();
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
        .catch(error=>"something went wrong");
  },
    categories:function(){
      this.cats="";
        axios.get(this.appurl+'api/categories')
        .then((response)=>{ 
          this.cats=response.data.categories;
          
        })
        .catch(error=>"something went wrong")
    },
    adultcategories:function(){
      this.cats="";
        axios.get(this.appurl+'api/adultcategories')
        .then((response)=>{ 
          this.cats=response.data.categories;
          //console.log(this.cats);
          
        })
        .catch(error=>"something went wrong")
    },
    topposts:function(){
      this.isTop=true;
      this.isHot=false;
      this.type="top";
      this.categories();
    },
    hotposts:function(){
      this.isTop=false;
      this.isHot=true;
      this.type="hot";
      this.categories();
      
    },
    filter(f){
      this.isFilter=f;
      if(this.isFilter=='adults')
      {
        this.adultcategories();
      }else{
        this.categories();
      }
      

    }
    
  },
  
  
}
</script>
<style scoped>


</style>