<template>

<div v-on:scroll="handleScroll(event)">
     <navbar></navbar>
     <div class="cover-image-header">
         
         <img v-if="apppath+category.cover" class="cover-image-header__media"  :src="apppath+category.cover"><div class="cover-image-header__overlay"><div class="cover-image-header__rows"><h1 class="cover-image-header__title">{{category.title}}</h1></div></div></div>

<div class="gallery-buttons"><div class="gallery-buttons__filter-buttons">
  <button class="icon-button" @click="follow"><i class="fa fa-plus" v-bind:class="{active:isFollow }"></i>Follow</button>


<div class="dropdown">
  <button class="icon-button dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    <i class="fa fa-sort-amount-desc"></i>Sort
  </button>
  <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
    <a class="dropdown-item" v-on:click="topposts"  v-bind:class="{ 'active': isTop }" href="javascript:void(0)"><i class="fa fa-line-chart"></i> Top</a>
    <a class="dropdown-item" v-on:click="hotposts"  v-bind:class="{ 'active': isHot }" href="javascript:void(0)"><i class="fa fa-free-code-camp"></i> Hot</a>
  </div>
</div>

<div class="dropdown">
  <button class="icon-button dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    <i class="fa fa-filter"></i>Filter
  </button>
  <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">

    <a class="dropdown-item" v-bind:class="[isFilter=='none' ? 'active' : '']" v-on:click="filter('none')" href="javascript:void(0)"> None</a>

    <a class="dropdown-item" v-bind:class="[isFilter=='picture' ? 'active' : '']" v-on:click="filter('picture')" href="javascript:void(0)"> Pictures</a>

    <a class="dropdown-item" v-bind:class="[isFilter=='video' ? 'active' : '']" v-on:click="filter('video')" href="javascript:void(0)"> Videos</a>
    
  </div>
</div>

<a class="icon-button" :href="'https://www.reddit.com/'+category.link" target="__blank" rel="noopener noreferrer"><i class="fa fa-paperclip"></i>Source</a>
</div>
</div>

<div class="data-container container">
    
<!-- Grid row -->

<!-- Grid row -->
<div class="row" id="gallery" v-if="!isMobile">

  <!-- Grid column -->
  <div class="col-sm-4  col-6 pl-0">
    <div class="vertical-view__item"  v-for="list in first" v-bind:key="list.key">
      <router-link :to="{ name: 'Post', params: { slug: list.slug }}" >
          <template v-if="list.post_type">
            <template v-if="list.post_type=='image'">
              <img v-bind:src="list.url" class="img-fluid" alt="">
            </template>
           <div class="" v-if="list.post_type=='rich:video'">
                 <!--<div class="media-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="media-icon__play"><path d="M96,52v408l320-204L96,52z"></path></svg></div>-->
                 <video class="" autoplay preload="metadata" >
                          <source :src="list.url" type="video/mp4">
                  </video>
            </div>
          </template>        
      </router-link>
        
      <div class="vertical-overlay">
        <div class="vertical-overlay__container">
          <div class="item-panel">
            <div class="item-panel__left">
              <router-link :to="{ name: 'Post', params: { slug: list.slug }}" class="item-panel__title" >{{list.post_title}}</router-link>
                <div class="item-panel__description">{{list.description}}</div>
                <router-link :to="{ name: 'Home' }" class="item-panel__discover" >
                More like this <i class="fa fa-long-arrow-right item-panel__discover-icon"></i></router-link>
                </div>
                <div class="item-panel__right">
                      <a class="item-panel__button" title="Source" :href="'https://reddit.com/'+list.permalink" target="__blank" rel="noopener noreferrer">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="item-panel__icon"><path d="M74.6 256c0-38.3 31.1-69.4 69.4-69.4h88V144h-88c-61.8 0-112 50.2-112 112s50.2 112 112 112h88v-42.6h-88c-38.3 0-69.4-31.1-69.4-69.4zm85.4 22h192v-44H160v44zm208-134h-88v42.6h88c38.3 0 69.4 31.1 69.4 69.4s-31.1 69.4-69.4 69.4h-88V368h88c61.8 0 112-50.2 112-112s-50.2-112-112-112z"></path></svg>
                            </a>
          <like :post_id="list.post_id" :type="type"></like>
          </div>
          </div>
          </div>
          </div>
        </div>
  </div>
  <!-- Grid column -->

    <!-- Grid column -->
  <div class="col-sm-4  col-6 pl-0">
    <div class="vertical-view__item"  v-for="list in second" v-bind:key="list.key">
      <router-link :to="{ name: 'Post', params: { slug: list.slug }}" >
          <template v-if="list.post_type">
            <template v-if="list.post_type=='image'">
              <img v-bind:src="list.url" class="img-fluid" alt="">
            </template>
           <div class="" v-if="list.post_type=='rich:video'">
                 <!--<div class="media-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="media-icon__play"><path d="M96,52v408l320-204L96,52z"></path></svg></div>-->
                 <video class="" autoplay preload="metadata" >
                          <source :src="list.url" type="video/mp4">
                  </video>
            </div>
          </template>        
      </router-link>
        
      <div class="vertical-overlay">
        <div class="vertical-overlay__container">
          <div class="item-panel">
            <div class="item-panel__left">
              <router-link :to="{ name: 'Post', params: { slug: list.slug }}" class="item-panel__title" >{{list.post_title}}</router-link>
                <div class="item-panel__description">{{list.description}}</div>
                <router-link :to="{ name: 'Home' }" class="item-panel__discover" >
                More like this <i class="fa fa-long-arrow-right item-panel__discover-icon"></i></router-link>
                </div>
                <div class="item-panel__right">
                      <a class="item-panel__button" title="Source" :href="'https://reddit.com/'+list.permalink" target="__blank" rel="noopener noreferrer">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="item-panel__icon"><path d="M74.6 256c0-38.3 31.1-69.4 69.4-69.4h88V144h-88c-61.8 0-112 50.2-112 112s50.2 112 112 112h88v-42.6h-88c-38.3 0-69.4-31.1-69.4-69.4zm85.4 22h192v-44H160v44zm208-134h-88v42.6h88c38.3 0 69.4 31.1 69.4 69.4s-31.1 69.4-69.4 69.4h-88V368h88c61.8 0 112-50.2 112-112s-50.2-112-112-112z"></path></svg>
                            </a>
          <like :post_id="list.post_id" :type="type"></like>
          </div>
          </div>
          </div>
          </div>
        </div>
  </div>
  <!-- Grid column -->
    <!-- Grid column -->
  <div class="col-sm-4 col-6 pl-0">
    <div class="vertical-view__item"  v-for="list in third" v-bind:key="list.key">
      <router-link :to="{ name: 'Post', params: { slug: list.slug }}" >
          <template v-if="list.post_type">
            <template v-if="list.post_type=='image'">
              <img v-bind:src="list.url" class="img-fluid" alt="">
            </template>
           <div class="" v-if="list.post_type=='rich:video'">
                 <!--<div class="media-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="media-icon__play"><path d="M96,52v408l320-204L96,52z"></path></svg></div>-->
                 <video class="" autoplay preload="metadata" >
                          <source :src="list.url" type="video/mp4">
                  </video>
            </div>
          </template>        
      </router-link>
        
      <div class="vertical-overlay">
        <div class="vertical-overlay__container">
          <div class="item-panel">
            <div class="item-panel__left">
              <router-link :to="{ name: 'Post', params: { slug: list.slug }}" class="item-panel__title" >{{list.post_title}}</router-link>
                <div class="item-panel__description">{{list.description}}</div>
                <router-link :to="{ name: 'Home' }" class="item-panel__discover" >
                More like this <i class="fa fa-long-arrow-right item-panel__discover-icon"></i></router-link>
                </div>
                <div class="item-panel__right">
                      <a class="item-panel__button" title="Source" :href="'https://reddit.com/'+list.permalink" target="__blank" rel="noopener noreferrer">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="item-panel__icon"><path d="M74.6 256c0-38.3 31.1-69.4 69.4-69.4h88V144h-88c-61.8 0-112 50.2-112 112s50.2 112 112 112h88v-42.6h-88c-38.3 0-69.4-31.1-69.4-69.4zm85.4 22h192v-44H160v44zm208-134h-88v42.6h88c38.3 0 69.4 31.1 69.4 69.4s-31.1 69.4-69.4 69.4h-88V368h88c61.8 0 112-50.2 112-112s-50.2-112-112-112z"></path></svg>
                            </a>
          <like :post_id="list.post_id" :type="type"></like>
          </div>
          </div>
          </div>
          </div>
        </div>
  </div>
  <!-- Grid column -->
</div>
<div class="row" id="gallery" v-if="isMobile">

  <!-- Grid column -->
  <div class="col-6 pr-0 pl-0 pb-0 pt-0">
    <div class="vertical-view__item"  v-for="list in first" v-bind:key="list.key">
      <router-link :to="{ name: 'Post', params: { slug: list.slug }}" >
          <template v-if="list.post_type">
            <template v-if="list.post_type=='image'">
              <img v-bind:src="list.url" class="img-fluid" alt="">
            </template>
           <div class="" v-if="list.post_type=='rich:video'">
                 <!--<div class="media-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="media-icon__play"><path d="M96,52v408l320-204L96,52z"></path></svg></div>-->
                 <video class="" autoplay preload="metadata" >
                          <source :src="list.url" type="video/mp4">
                  </video>
            </div>
          </template>        
      </router-link>
        
      <div class="vertical-overlay">
        <div class="vertical-overlay__container">
          <div class="item-panel">
            <div class="item-panel__left">
              <router-link :to="{ name: 'Post', params: { slug: list.slug }}" class="item-panel__title" >{{list.post_title}}</router-link>
                <div class="item-panel__description">{{list.description}}</div>
                <router-link :to="{ name: 'Home' }" class="item-panel__discover" >
                More like this <i class="fa fa-long-arrow-right item-panel__discover-icon"></i></router-link>
                </div>
                <div class="item-panel__right">
                      <a class="item-panel__button" title="Source" :href="'https://reddit.com/'+list.permalink" target="__blank" rel="noopener noreferrer">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="item-panel__icon"><path d="M74.6 256c0-38.3 31.1-69.4 69.4-69.4h88V144h-88c-61.8 0-112 50.2-112 112s50.2 112 112 112h88v-42.6h-88c-38.3 0-69.4-31.1-69.4-69.4zm85.4 22h192v-44H160v44zm208-134h-88v42.6h88c38.3 0 69.4 31.1 69.4 69.4s-31.1 69.4-69.4 69.4h-88V368h88c61.8 0 112-50.2 112-112s-50.2-112-112-112z"></path></svg>
                            </a>
          <like :post_id="list.post_id" :type="type"></like>
          </div>
          </div>
          </div>
          </div>
        </div>
  </div>
  <!-- Grid column -->

    <!-- Grid column -->
  <div class="col-6 pl-0 pr-0 pb-0 pt-0">
    <div class="vertical-view__item"  v-for="list in second" v-bind:key="list.key">
      <router-link :to="{ name: 'Post', params: { slug: list.slug }}" >
          <template v-if="list.post_type">
            <template v-if="list.post_type=='image'">
              <img v-bind:src="list.url" class="img-fluid" alt="">
            </template>
           <div class="" v-if="list.post_type=='rich:video'">
                 <!--<div class="media-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="media-icon__play"><path d="M96,52v408l320-204L96,52z"></path></svg></div>-->
                 <video class="" autoplay preload="metadata" >
                          <source :src="list.url" type="video/mp4">
                  </video>
            </div>
          </template>        
      </router-link>
        
      <div class="vertical-overlay">
        <div class="vertical-overlay__container">
          <div class="item-panel">
            <div class="item-panel__left">
              <router-link :to="{ name: 'Post', params: { slug: list.slug }}" class="item-panel__title" >{{list.post_title}}</router-link>
                <div class="item-panel__description">{{list.description}}</div>
                <router-link :to="{ name: 'Home' }" class="item-panel__discover" >
                More like this <i class="fa fa-long-arrow-right item-panel__discover-icon"></i></router-link>
                </div>
                <div class="item-panel__right">
                      <a class="item-panel__button" title="Source" :href="'https://reddit.com/'+list.permalink" target="__blank" rel="noopener noreferrer">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="item-panel__icon"><path d="M74.6 256c0-38.3 31.1-69.4 69.4-69.4h88V144h-88c-61.8 0-112 50.2-112 112s50.2 112 112 112h88v-42.6h-88c-38.3 0-69.4-31.1-69.4-69.4zm85.4 22h192v-44H160v44zm208-134h-88v42.6h88c38.3 0 69.4 31.1 69.4 69.4s-31.1 69.4-69.4 69.4h-88V368h88c61.8 0 112-50.2 112-112s-50.2-112-112-112z"></path></svg>
                            </a>
          <like :post_id="list.post_id" :type="type"></like>
          </div>
          </div>
          </div>
          </div>
        </div>
  </div>
  <!-- Grid column -->
</div>
<!-- Grid row -->
</div>
</div>
</template>

<script>
import debounce from 'lodash/debounce';
import navbar from './navbar.vue'
import Like from './likes.vue'
   export default{
  components: {
            navbar,Like
        },      
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
    isFilter:"none",
    category_id:"",
    isFollow:false,
    isMobile:false,
    first:"",
    second:"",
    third:""
    }
  },
  
  beforeMount(){
    this.apppath=this.appurl;
    this.cat=this.$route.params.slug;
    this.catdetail();
    this.topposts();
  },
  beforeRouteUpdate(to, from, next) {
            next();
             this.apppath=this.appurl;
    this.cat=to.params.slug;
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
     checkfollow(){
       var url=this.appurl+'api/check/follow/subreddit/'+this.category_id;
            axios.get(url,{
              headers: {
                Authorization: `Bearer ${localStorage.getItem("access_token")}`
              }
            })
            .then(response => {
              //console.log(response.data);
              if(response.data.status=='1')
              {
                this.isFollow=true;
              }else{
                this.isFollow=false;
              }
            })
            .catch(error => {
              //console.log(error);
              this.isFollow=false;
              //this.isLogin=false;
            });
     },
     follow:function(){
  
       var url=this.appurl+'api/follow/subreddit/'+this.category_id;
            axios.get(url,{
              headers: {
                Authorization: `Bearer ${localStorage.getItem("access_token")}`
              }
            })
            .then(response => {
              if(response.data.status=='1')
              {
                this.isFollow=true;
              }else{
                this.isFollow=false;
              }
              
              
            })
            .catch(error => {
              //console.log(error);
              this.isFollow=false;
              $('.login-modal').modal('show');
              //this.isLogin=false;
            });
     },
      searchSubreddit() {
                axios.get(this.appurl+'api/search/subreddit/',{params: this.axiosParams})
        .then((response)=>{
          //console.log(response.data);
          this.resources=response.data.lists;
            
        } )
        .catch(error=>"something went wrong");
  },
    topposts:function(){
      
      this.isTop=true;
      this.isHot=false;
      this.type='top';
      this.lists="";
      if(screen.width>600)
      {
        this.isMobile=false;
        axios.get(this.appurl+'api/top/'+this.cat+"/"+this.isFilter)
        .then((response)=>{
        //console.log(response.data);
            this.first=response.data.first;
            this.second=response.data.second;
            this.third=response.data.third;
            this.type=response.data.type;
            this.offset=response.data.offset;
           // console.log(this.lists);
        })
      }else{
        this.isMobile=true;
        axios.get(this.appurl+'api/mobile/top/'+this.cat+"/"+this.isFilter)
        .then((response)=>{
        //console.log(response.data);
            this.first=response.data.first;
            this.second=response.data.second;
            this.type=response.data.type;
            this.offset=response.data.offset;
           // console.log(this.lists);
        })
      }
    },
    hotposts:function(){
      this.isMobile=true;
      this.isTop=false;
      this.lists="";
      this.type='hot';
      this.isHot=true;
      if(screen.width>600)
      {
        axios.get(this.appurl+'api/hot/'+this.cat+"/"+this.isFilter)
        .then((response)=>{
          //console.log(response);
            this.first=response.data.first;
            this.second=response.data.second;
            this.third=response.data.third;
            this.type=response.data.type;
            this.offset=response.data.offset;
        } )
      }else{
        this.isMobile=true;
         axios.get(this.appurl+'api/mobile/hot/'+this.cat+"/"+this.isFilter)
        .then((response)=>{
          //console.log(response);
            this.first=response.data.first;
            this.second=response.data.second;
            this.type=response.data.type;
            this.offset=response.data.offset;
        } )
      }
    },
    catdetail:function(){
        axios.get(this.appurl+'api/subcatdetail/'+this.cat)
        .then((response)=> {
            this.category=response.data.category;
            this.category_id=response.data.category.id;
            this.subcategories=response.data.subcategories;
            this.checkfollow();
            }
        )
        .catch(error=> console.log(error))
        
    },
    filter(f){
      this.isFilter=f;
      if(this.type=="top")
      {
        this.topposts();
      }else{
        this.hotposts();
      }
    },
     handleScroll(event) {
       var that=this;
       window.onscroll = () => {
         //console.log('console');
    let bottomOfWindow = Math.max(window.pageYOffset, document.documentElement.scrollTop, document.body.scrollTop) + window.innerHeight === document.documentElement.offsetHeight

    if (bottomOfWindow) {
      if(screen.width>600)
      {
        this.isMobile=false;
        axios.get(this.appurl+'api/loadmore/'+this.type+'/'+this.cat+'/'+this.offset+"/"+this.isFilter)
        .then((response)=>{
          //console.log(response.data);
            // Loop on data and push in posts
                for (let i = 0; i < response.data.first.length; i++){
                   that.first.push(response.data.first[i]); 
                }
                for (let i = 0; i < response.data.second.length; i++){
                   that.second.push(response.data.second[i]); 
                } 
                for (let i = 0; i < response.data.third.length; i++){
                   that.third.push(response.data.third[i]); 
                } 
            this.offset=response.data.offset;
        } )
      }else{
        this.isMobile=true;
        axios.get(this.appurl+'api/mobile/loadmore/'+this.type+'/'+this.cat+'/'+this.offset+"/"+this.isFilter)
        .then((response)=>{
          //console.log(response.data);
            // Loop on data and push in posts
                for (let i = 0; i < response.data.first.length; i++){
                   that.first.push(response.data.first[i]); 
                }
                for (let i = 0; i < response.data.second.length; i++){
                   that.second.push(response.data.second[i]); 
                } 

            this.offset=response.data.offset;
        } )
      }
    }
 }
    }
  },
}
</script>
<style scoped>
.dropdown-toggle::after{
  display: none !important;
}

</style>