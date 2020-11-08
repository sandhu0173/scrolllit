<template>
     <div>
       <div  v-if="lists.length">

       <router-link :to="{ name: 'Subreddit', params: { slug: cat.slug } }" class="horizontal-view__title" >{{cat.title}}</router-link>

    <div class="horizontal-view__items-container">
      <div class="horizontal-view__items-scrollable">
      <div class="horizontal-view__items" v-bind:style="{ transform: 'translateX('+trans+'px)'  }">
    
    <router-link class="horizontal-view__item" v-for="list in lists" v-bind:key="list.key" :to="{ name: 'Post', params: { slug: list.slug }}" >
    
    <template v-if="list.post_type=='image'">
      <img class="horizontal-view__media"  :srcset="list.thumbnail"  :src="list.thumbnail">
                            
                        </template>
                        <template v-if="list.post_type=='rich:video'">
  <img class="horizontal-view__media"  :srcset="list.thumbnail"  :src="list.thumbnail" >
    <div class="media-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="media-icon__play"><path d="M96,52v408l320-204L96,52z"></path></svg></div>
  </template>
    </router-link>

    </div>
    </div>
    <button @click="previous" class="horizontal-view__arrow horizontal-view__arrow--left " v-bind:class="{'horizontal-view__arrow--visible':hasPrevious}"></button>
    <button @click="next" class="horizontal-view__arrow horizontal-view__arrow--right" v-bind:class="{'horizontal-view__arrow--visible':hasNext}"></button></div>
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
              screenWidth:"",
              trans:0,
              hasPrevious:false, 
              hasNext:true,
              limit:0 
            }
            },
          props: ['cat','type','filter','topFill'],
            mounted() {
              this.screenWidth=$(window).width();
                this.getposts();
                this.fetchposts();
            },
             methods: {
               getposts:function(){
                 var that=this;
                 axios.get(that.appurl+'api/loadmore/subcat/'+that.type+'/'+that.cat.slug+'/'+that.filter+'/'+that.topFill+'/'+that.offset)
                  .then((response)=>{
                      // Loop on data and push in posts
                          that.lists=response.data.posts;
                      that.offset=response.data.offset;

                  } )
               },
                fetchposts:function(){
                  axios.get(this.appurl+'api/fetchposts/'+this.cat.slug)
                    .then((response)=> {
                        }
                    )
                },
               next(){
                 var that=this;
                 that.trans-=that.screenWidth;
                 that.hasPrevious=true;
                  axios.get(that.appurl+'api/loadmore/subcat/'+that.type+'/'+that.cat.slug+'/'+that.filter+'/'+that.topFill+'/'+that.offset)
                  .then((response)=>{ 
                    if(response.data.posts.length<1)
                    {
                      that.hasNext=false;
                    }
                    for (let i = 0; i < response.data.posts.length; i++){
                            that.lists.push(response.data.posts[i]); 
                          }
                    that.offset=response.data.offset;
                    that.limit=response.data.limit;
                    
                  })

               },
               previous(){
                 this.trans+=this.screenWidth;
                 this.offset-=this.limit;
                 if(this.offset==this.limit)
                 {
                   this.hasPrevious=false;
                 }
                   this.hasNext=true;
               }
             },
      }
</script>

<style scoped>

</style>
