<template>
     <div>
       <div  v-if="lists.length">
       <router-link :to="{ name: 'Subreddit', params: { slug: cat.slug } }" class="btn btn-default btn-lg " >
                           
                                    {{cat.title}}{{type}}
                                
    </router-link>
          <VueSlickCarousel v-bind="settings"  >
            <template >
              <router-link v-for="list in lists" v-bind:key="list.key" :to="{ name: 'Post', params: { slug: list.slug }}" >
              <img v-bind:src="list.thumbnail" class="img-fluid post-image" :alt="list.title">
              </router-link>
            </template>

    
    </VueSlickCarousel>
     </div>
     <div v-else>
       <i class="fa fa-circle fa-spin"></i>
     </div>
     </div>
</template>

<script>
import 'vue-slick-carousel/dist/vue-slick-carousel.css'
  import 'vue-slick-carousel/dist/vue-slick-carousel-theme.css'
  import VueSlickCarousel from 'vue-slick-carousel'
      export default {
          data() {
            return {
              lists:"",
              offset:0,
                settings: {
                    "centerMode": true,
                      "centerPadding": "20px",
                      "dots": false,
                      "focusOnSelect": true,
                      "infinite": true,
                      "speed": 500,
                      "slidesToShow": 5,
                      "slidesToScroll": 5,
                      "touchThreshold": 5
                    },
            }
            },
          components: { VueSlickCarousel },
          props: ['cat','type'],
            mounted() {
                //console.log(this.type);
                this.getposts();
            },
             methods: {
               
               getposts:function(){
                 var that=this;
                 axios.get(that.appurl+'api/loadmore/subcat/'+that.type+'/'+that.cat.slug+'/'+that.offset)
                  .then((response)=>{
                      // Loop on data and push in posts
                          that.lists=response.data.posts;
                      that.offset=response.data.offset;
                  } )
                  .catch(error=>"something went wrong")
               },
               
             },

      }
</script>

<style scoped>


</style>
