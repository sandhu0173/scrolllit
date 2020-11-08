<template>

<div v-on:scroll="handleScroll(event)">
     <navbar></navbar>
     <div class="cover-image-header">
         
         <img v-if="apppath+category.cover" class="cover-image-header__media"  :src="apppath+category.cover"><div class="cover-image-header__overlay"><div class="cover-image-header__rows"><h1 class="cover-image-header__title">{{category.title}}</h1></div></div></div>

<div class="gallery-buttons"><div class="gallery-buttons__filter-buttons"><button class="icon-button"><i class="fa fa-plus"></i>Follow</button><button class="icon-button"><i class="fa fa-sort-amount-desc"></i>Sort</button><button class="icon-button"><i class="fa fa-filter"></i>Filter</button><a class="icon-button" href="https://www.reddit.com/r/ImaginaryDC" target="__blank" rel="noopener noreferrer"><i class="fa fa-paperclip"></i>Source</a></div></div>

<div class="data-container container">
    
<!-- Grid row -->
<div class="row">

  <!-- Grid column -->
  <div class="col-md-12 d-flex justify-content-center mb-1">

   <div class="filter-buttons filter-buttons--big-font">
            <button class="filter-buttons__button" v-on:click="hotposts"  v-bind:class="{ 'filter-buttons__button--active': isTop }"><i class="fa fa-line-chart"></i> Top</button>
            <button class="filter-buttons__button" v-on:click="hotposts"  v-bind:class="{ 'filter-buttons__button--active': isHot }"><i class="fa fa-free-code-camp"></i> Hot</button>
        </div>

  </div>
  <!-- Grid column -->

</div>
<!-- Grid row -->

<!-- Grid row -->
<div class="gallery" id="gallery">

  <!-- Grid column -->
  <div class="mb-3 pics all" v-for="list in lists" v-bind:key="list.key">
       <router-link :to="{ name: 'Post', params: { slug: list.slug }}" >
     <template v-if="list.post_type">
                        <template v-if="list.post_type=='image'">
                            <img v-bind:src="list.url" class="img-fluid post-image" alt="">
                        </template>

                         <template v-if="list.post_type=='rich:video'">
      
                        <video v-if="list.url" controls>
                        <source :src="list.url" type="video/mp4" autostart="false">
                        <source :src="list.url" type="video/ogg" autostart="false">
                        Your browser does not support the video tag.
                        </video>
                        </template>
  </template>
       </router-link>
  </div>
  <!-- Grid column -->

  <!-- Grid column -->

</div>
<!-- Grid row -->
</div>
</div>
</template>

<script>
import debounce from 'lodash/debounce';
import navbar from './navbar.vue'
   export default{
        
  data(){
    return {
      resources:"",
    cat:"",
    category:"",
    subcategories:"",
      lists:"",
      isTop:true,
      isHot:false,
      apppath:"",
      liked:false,
      type:"",
      offset:"",
      searchQuery:"",
    }
  },
  components: {
            navbar
        },
  beforeMount(){
    this.apppath=this.appurl;
    this.cat=this.$route.params.slug;
    this.catdetail();
    this.topposts();
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
          console.log(response.data.lists);
          this.resources=response.data.lists;
            
        } )
        .catch(error=>"something went wrong");
  },
    topposts:function(){
      this.isTop=true;
      this.isHot=false;
      this.lists="";
        axios.get(this.appurl+'api/cat/top/'+this.cat)
        .then((response)=>{
        //console.log(response.data);
            this.lists=response.data.posts;
            this.type=response.data.type;
            this.offset=response.data.offset;
            console.log(this.lists);
        } )
        .catch(error=>"something went wrong");
    },
    hotposts:function(){
      this.isTop=false;
      this.lists="";
      this.isHot=true;
        axios.get(this.appurl+'api/cat/hot/'+this.cat)
        .then((response)=>{
          //console.log(response);
            this.lists=response.data.posts;
            this.type=response.data.type;
            this.offset=response.data.offset;
        } )
        .catch(error=>"something went wrong")
    },
    catdetail:function(){

        axios.get(this.appurl+'api/catdetail/'+this.cat)
        .then((response)=> {
            this.category=response.data.category;
            this.subcategories=response.data.subcategories;
            }
        )
        .catch(error=> console.log(error))
        
    },
     handleScroll(event) {
       var that=this;
       window.onscroll = () => {
    let bottomOfWindow = Math.max(window.pageYOffset, document.documentElement.scrollTop, document.body.scrollTop) + window.innerHeight === document.documentElement.offsetHeight

    if (bottomOfWindow) {
     axios.get(this.appurl+'api/loadmore/cat/'+this.type+'/'+this.cat+'/'+this.offset)
        .then((response)=>{
            // Loop on data and push in posts
                for (let i = 0; i < response.data.posts.length; i++){
                   that.lists.push(response.data.posts[i]); 
                } 
            this.offset=response.data.offset;
        } )
        .catch(error=>"something went wrong")
    }
 }
     
    }
    
  },
  filters: {
    addhtml (val) {
     if(val)
     {
      var j=val.replace("&lt;", "<");
      var m=j.replace("&gt;", ">");
      //console.log(m);
      return m;
     }else{
       return val;
     }
    },
    
  },
  
}
</script>
<style scoped>

</style>