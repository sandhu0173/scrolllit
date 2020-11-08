<template>
<div>
    <navbar></navbar>
    <div class="fullscreen-view" @scroll="handleScroll">
        <div class="fullscreen-view__menu"><button class="fullscreen-view__menu-button"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="fullscreen-view__menu-icon"><path d="M296 136c0-22.002-17.998-40-40-40s-40 17.998-40 40 17.998 40 40 40 40-17.998 40-40zm0 240c0-22.002-17.998-40-40-40s-40 17.998-40 40 17.998 40 40 40 40-17.998 40-40zm0-120c0-22.002-17.998-40-40-40s-40 17.998-40 40 17.998 40 40 40 40-17.998 40-40z"></path></svg></button></div>
        <button class="btn btn-previous" v-on:click="previouspost"><i class="fa fa-arrow-left"></i></button>
        <button class="btn btn-next"  v-on:click="nextpost"><i class="fa fa-arrow-right"></i></button>
        <div class="fullscreen-view__scrollable" >
            <!---Previous block---->
                <div class="fullscreen-view__entry-container" >
                    
                    <div class="fullscreen-view__entry" v-if="post">
                        <div class="fullscreen-view__media">
                     <template v-if="post.post_type">
                        <template v-if="post.post_type=='image'">
                            <img v-bind:src="post.url" class="fullscreen-view__media" alt="">
                        </template>

                         <template v-if="post.post_type=='rich:video'">
      <video class="fullscreen-view__media" preload="metadata" disableremoteplayback="true" playsinline="" style="visibility: visible;"><source :src="post.url" type="video/mp4"></video>
                        =
                        </template>
  </template>
                        </div>
                
                <div class="fullscreen-view__overlay">
                    <div class="item-panel item-panel--pad-unsafe-area">
                        <div class="item-panel__left"><a class="item-panel__title" href="">{{post.post_title}}</a><div class="item-panel__description">{{post.description}}</div>
                        <a class="item-panel__discover" href="">More like this
                            <i class="fa fa-long-arrow-right item-panel__discover-icon"></i>
                        </a>
                        </div>
                        <div class="item-panel__right">
                            <a class="item-panel__button" title="Source" :href="'https://reddit.com/'+post.permalink" target="__blank" rel="noopener noreferrer">
                            
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="item-panel__icon"><path d="M74.6 256c0-38.3 31.1-69.4 69.4-69.4h88V144h-88c-61.8 0-112 50.2-112 112s50.2 112 112 112h88v-42.6h-88c-38.3 0-69.4-31.1-69.4-69.4zm85.4 22h192v-44H160v44zm208-134h-88v42.6h88c38.3 0 69.4 31.1 69.4 69.4s-31.1 69.4-69.4 69.4h-88V368h88c61.8 0 112-50.2 112-112s-50.2-112-112-112z"></path></svg>
                            </a>
                            <like :post_id="post.id" :type="type"></like>
                            
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
import Like from './likes.vue'
import navbar from './navbar.vue'

export default {
      components: {
            Like,navbar
        },
    data(){
    return {
        post:"",
        posts:"",
        slug:"",
        apppath:"",
        post_id:"",
        liked:"",
        likes:"",
        type:"",
        top:0,
        x:200,
        previous:"",
        next:""
    }
  },

    beforeMount(){
    this.apppath=this.appurl;
    this.slug=this.$route.params.slug;
    this.postdetail();
  },
  methods:{
      postdetail:function(){
          this.slug=this.$route.params.slug;
        axios.get(this.appurl+'api/postdetail/'+this.slug)
        .then((response)=> {
            //console.log(response.data.posts[1]);
            this.top=300;
            //this.posts=response.data.posts;
            this.post=response.data.posts[1];
            this.type=response.data.type;
            this.previous=response.data.posts[0].slug;
            this.next=response.data.posts[2].slug;
            
            }
        )
        .catch(error=> console.log(error))
        
    },
        nextpost:function(){
            this.$router.push({ name: 'Post',params: { slug: this.next } }).catch(()=>{});
        },
        previouspost:function(){
            this.$router.push({ name: 'Post',params: { slug: this.previous } }).catch(()=>{});
        },
        handleScroll: function(e) {
    var currentScrollPosition = e.srcElement.scrollTop;
    if (currentScrollPosition > this.scrollPosition) {
        this.nextpost();
    }
    if (currentScrollPosition < this.scrollPosition) {
        this.previouspost();       
    }
    this.scrollPosition = currentScrollPosition;
},
     
                  
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
       // call the method if the route changes
       '$route': {
         handler: 'postdetail',
         immediate: true // runs immediately with mount() instead of calling method on mount hook
       }
    },
    

}
</script>
<style scoped>
.btn{
    position: fixed;
    z-index: 999;
    top: 50vh;
    color:#fff !important;
    font-size: 1.5rem;
}
.btn-previous{
    left:0;
}
.btn-next{
    right:10px;
}
</style>