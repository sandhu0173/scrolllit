<template>
     <div>
       
          <carousel :responsive="{0:{items:1},600:{items:1},1500:{items:1}}" :margin="10" :items="4" :nav="false" @changed="changed" @updated="updated">
<div class="fullscreen-view__entry" v-for="post in posts" v-bind:key="post.key">
                        <div class="fullscreen-view__media">
                     <template v-if="post.post_type">
                        <template v-if="post.post_type=='image'">
                            <img v-bind:src="post.url" class="fullscreen-view__media" alt="">
                        </template>

                         <template v-if="post.post_type=='rich:video'">
      <video infinite loop class="fullscreen-view__media" autoplay preload="metadata" ><source :src="post.url" type="video/mp4"></video>
                        
                        </template>
  </template>
                        </div>
                
                <div class="fullscreen-view__overlay" v-bind:class="{ hide:isHide}">
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
 
    </carousel>
     
     </div>
</template>

<script>
import carousel from 'vue-owl-carousel'
import Like from './likes.vue'
      export default {
          data() {
            return {
            }
            },
          components: { carousel,Like  },
          props: ['posts','isHide','type'],
            mounted() {
                
                console.log(this.posts);
            },
             methods: {
             
               changed:function()
               {
                 console.log('changed');
               },
               updated:function(){
                 console.log('update');
               },

               
             },

      }
</script>

<style scoped>
span.prev {
    cursor: pointer;
    color: #fff;
    font-size: 1.5rem;
    position: absolute;
    z-index: 9;
    /* top: 50%; */
    background: rgba(0,0,0,0.5);
    height: 20rem;
    text-align: center;
    padding: 0.5rem;
    vertical-align: middle;
    padding-top: 10%;
}
span.next {
    cursor: pointer;
    color: #fff;
    font-size: 1.5rem;
    position: absolute;
    z-index: 9;
    background: rgba(0,0,0,0.5);
    height: 20rem;
    text-align: center;
    padding: 0.5rem;
    vertical-align: middle;
    padding-top: 10%;
    right: 0px;
    top: 3rem;
}
</style>
