<template>
     <div>
       <div  v-if="lists.length">
       <router-link :to="{ name: 'Subreddit', params: { slug: cat.slug } }" class="btn btn-default btn-lg " >
                           
                                    {{cat.title}}{{type}}
                                
    </router-link>
    <div slot="prev"><span class="prev"><i class="fa fa-arrow-left"></i></span></div>
          <carousel :responsive="{0:{items:2},600:{items:4},1500:{items:4}}" :margin="10" :items="4" :nav="false" @changed="changed" @updated="updated">
            <template >
              <router-link v-for="list in lists" v-bind:key="list.key" :to="{ name: 'Post', params: { slug: list.slug }}" >
              <template v-if="list.post_type">
                        <template v-if="list.post_type=='image'">
                            <img v-bind:src="list.url" class="img-fluid post-image" alt="">
                        </template>

                         <template v-if="list.post_type=='rich:video'">
  <img v-bind:src="list.thumbnail" class="img-fluid post-image" alt="">
  <div class="media-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="media-icon__play"><path d="M96,52v408l320-204L96,52z"></path></svg></div>
  </template>
  </template>
              </router-link>
            </template>
<div slot="next"><span class="next"><i class="fa fa-arrow-right"></i></span></div>
    
    </carousel>
     </div>
     </div>
</template>

<script>
import carousel from 'vue-owl-carousel'
      export default {
          data() {
            return {
              lists:"",
              offset:0,

            }
            },
          components: { carousel  },
          props: ['cat','type','filter'],
            mounted() {
                this.getposts();
            },
             methods: {
               getposts:function(){
                 var that=this;
                 axios.get(that.appurl+'api/loadmore/subcat/'+that.type+'/'+that.cat.slug+'/'+that.filter+'/'+that.offset)
                  .then((response)=>{
                      // Loop on data and push in posts
                          that.lists=response.data.posts;
                      that.offset=response.data.offset;
                  } )
                  .catch(error=>"something went wrong")
               },
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
