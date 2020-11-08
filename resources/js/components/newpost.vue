<template>
<div>
    
    
    <div class="fullscreen-view">
        <navbar></navbar>
        <div class="fullscreen-view__menu">
            <button class="fullscreen-view__menu-button">
                <i class="fa fa-ellipsis-v fullscreen-view__menu-icon"></i>
                
            </button>
        </div>
        <div class="fullscreen-view__scrollable"  v-on:scroll="handleScroll(event)" >
            <div class="fullscreen-view__entry-container"><div class="fullscreen-view__entry">
                     <template v-if="post.post_type">
                        <template v-if="post.post_type=='image'">
                            <img v-bind:src="post.url" class="fullscreen-view__media" alt="">
                        </template>

                         <template v-if="post.post_type=='rich:video'">
      
                        <video v-if="post.url" controls>
                        <source :src="post.url" type="video/mp4" autostart="false">
                        <source :src="post.url" type="video/ogg" autostart="false">
                        Your browser does not support the video tag.
                        </video>
                        </template>
  </template>

                
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
                            <like :post_id="post.post_id" :type="type"></like>
                            
                            </div>
                            </div>
                            </div>
                            </div>
                            </div>
                            
                    
                    
        </div>
        
    </div><!--end scroll div-->
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
        slug:"",
        apppath:"",
        post_id:"",
        liked:"",
        likes:"",
        type:"",
    }
  },
    beforeMount(){
    this.apppath=this.appurl;
    this.slug=this.$route.params.slug;
    this.postdetail();
  },
  methods:{
      postdetail:function(){

        axios.get(this.appurl+'api/postdetail/'+this.slug)
        .then((response)=> {
            //console.log(response.data);
            this.post=response.data.post;
            this.type=response.data.type;
            this.post_id=response.data.post.id;
            this.checkuserlike();
            }
        )
        .catch(error=> console.log(error))
        
    },
        handleScroll(event) {
            var that=this;
            window.onscroll = () => {
            let bottomOfWindow = Math.max(window.pageYOffset, document.documentElement.scrollTop, document.body.scrollTop) + window.innerHeight === document.documentElement.offsetHeight

            if (bottomOfWindow) {
                console.log('bottom');
            }else{
                console.log('top');
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