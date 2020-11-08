<template>
<div>
  <navbar></navbar>
   <div class="mb-2 mt-3 text-center">
         <h2 class="text-light "><i class="fa fa-heart"></i> Favorites</h2>
         </div>    
     
    <div class="mt-1 mb-1">
        
        <div class="filter-buttons filter-buttons--big-font">
            <button class="filter-buttons__button" v-on:click="topposts"  v-bind:class="{ 'filter-buttons__button--active': isTop }"><i class="fa fa-line-chart"></i> Top</button>
            <button class="filter-buttons__button" v-on:click="hotposts"  v-bind:class="{ 'filter-buttons__button--active': isHot }"><i class="fa fa-free-code-camp"></i> Hot</button>
        </div>
    </div>
<div class="container page-content">
 <div class="text-center mt-5" v-if="!isLogin">
          <h4 class="text-light " >You must log in to show following</h4>  
  </div>

      <div class="row" v-else>
<!-- Grid row -->
<div class="gallery" id="gallery">

  <!-- Grid column -->
  <div class="mb-2 pics all" v-for="list in lists" v-bind:key="list.key">
    <div class="vertical-view__item">
      
      <router-link :to="{ name: 'Post', params: { slug: list.slug }}" >
        
        <div class="vertical-view__placeholder"></div>
        
  <template v-if="list.post_type">
                        <template v-if="list.post_type=='image'">
                            <img v-bind:src="list.url" class="img-fluid post-image" alt="">
                        </template>

                         <div class="vertical-view__media" v-if="list.post_type=='rich:video'">
<!--<div class="media-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="media-icon__play"><path d="M96,52v408l320-204L96,52z"></path></svg></div>-->
<video class="vertical-view__media" autoplay preload="metadata" >
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
</div>
      </div>
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
      isLogin:"",
    lists:"",
    isTop:true,
    isHot:false,
    liked:false,
    type:"",
    offset:"",
    searchQuery:"",
    category_id:"",
    isFollow:false
    }
  },
  
  beforeMount(){
    this.apppath=this.appurl;
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
  },
   isLogin(after, before) {
    this.topposts();
  }
},

   methods: {
    topposts:function(){
      this.isTop=true;
      this.isHot=false;
      this.type='top';
      this.lists="";
        axios.get(this.appurl+'api/top/favorites',{
              headers: {
                Authorization: `Bearer ${localStorage.getItem("access_token")}`
              }
            })
        .then((response)=>{
        
        this.isLogin=true;
        //console.log(this.isLogin);
            this.lists=response.data.posts;
            this.type=response.data.type;
            this.offset=response.data.offset;
           // console.log(this.lists);
        } )
        .catch(error => {
          this.isLogin=false;
              $('.login-modal').modal('show');
           ////   this.checklogin();
            });
    },
    hotposts:function(){
      this.isTop=false;
      this.lists="";
      this.type='hot';
      this.isHot=true;
        axios.get(this.appurl+'api/hot/favorites',{
              headers: {
                Authorization: `Bearer ${localStorage.getItem("access_token")}`
              }
            })
        .then((response)=>{
          //console.log(response);
        this.isLogin=true;
            this.lists=response.data.posts;
            this.type=response.data.type;
            this.offset=response.data.offset;
        } )
        .catch(error => {
             // console.log(error);
              this.isLogin=false;
              this.cats="";
              $('.login-modal').modal('show');
              this.checklogin();
            });
    },
         checklogin(){
        var that=this;
        var url=that.appurl+'api/check/login';
        var pi=setInterval(function(){
            axios.get(url,{
              headers: {
                Authorization: `Bearer ${localStorage.getItem("access_token")}`
              }
            })
            .then(response => {
              //console.log(response.data);
              that.isLogin=true;
              //that.getFollowing();
             clearInterval(pi); 
            })
            .catch(error => {
              //console.log(error);
              that.isLogin=false;
            });
            

        },1000);
      },
     handleScroll(event) {
       var that=this;
       window.onscroll = () => {
    let bottomOfWindow = Math.max(window.pageYOffset, document.documentElement.scrollTop, document.body.scrollTop) + window.innerHeight === document.documentElement.offsetHeight

    if (bottomOfWindow) {
     axios.get(this.appurl+'api/loadmore/favorites/'+this.type+'/'+this.offset,{
              headers: {
                Authorization: `Bearer ${localStorage.getItem("access_token")}`
              }
            })
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

  
}
</script>
<style scoped>
.gallery-view {
    min-height: calc(100vh - 14.5rem);
    padding-top: 0;
    margin-bottom: 4rem;
    margin-top: 0;
}
</style>