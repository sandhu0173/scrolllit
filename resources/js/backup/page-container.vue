<template>
<div>
    <navbar></navbar>
    <div class="container-fluid">
        <div class="row flex-xl-nowrap2">
          <div class="bd-sidebar border-bottom-0 col-md-3 col-xl-2 col-12">
               
                
                <nav id="bd-docs-nav" aria-label="Main navigation" class="bd-links d-none d-md-block">
                  <div class="bd-toc-item">
                        <a href="javascript:void(0)"  class="bd-toc-link" v-bind:class="{ active: isTop }" v-on:click="topposts" target="_self"><font-awesome-icon icon="chart-bar" />  Top </a>  
                    </div>
                    <div class="bd-toc-item">
                        <a href="javascript:void(0)"  v-bind:class="{ active: isHot }" v-on:click="hotposts" class="bd-toc-link" target="_self"> <font-awesome-icon icon="fire" /> Hot</a>
                    </div> 
                     
    </nav>
    
    </div>
    <div class="bd-content col-md-9 col-xl-8 col-12 pb-md-3 pl-md-5 text-center">
      <div class="visible-xs">
        <button class="top-button" v-on:click="topposts"  v-bind:class="{ active: isTop }">Top</button>
        <button class="top-button" v-on:click="hotposts"  v-bind:class="{ active: isHot }"> Hot</button>
      </div>
                <div class="clearfix d-block"></div>
        <main class="bd-main scrollable" ref="msgContainer">
      
<div class="card" v-for="list in lists" v-bind:key="list.key">
 
  <div class="card-body description">
    <h5 class="card-title">{{list.data.title}}</h5>
    <p class="card-text">{{list.data.selftext}}</p>
  </div>
  <div class="card-content">
  <template v-if="list.data.media">
  <div class="iframe" v-html="$options.filters.addhtml(list.data.media.oembed.html)"></div>
  </template>
  </div>
 <!-- <template v-if="list.data.media">
    <div v-if="list.data.preview.reddit_video_preview">
  <video controls>
  <source :src="list.data.preview.reddit_video_preview.fallback_url" type="video/mp4">
  <source :src="list.data.preview.reddit_video_preview.fallback_url" type="video/ogg">
Your browser does not support the video tag.
</video>
</div>
  </template>-->
   <div class="card-body links">
    <a :href="'https://reddit.com'+list.data.permalink" class="card-link"><font-awesome-icon icon="thumbs-up" /> <span>{{list.data.ups}} </span></a>
    <a :href="'https://reddit.com'+list.data.permalink" class="card-link"><font-awesome-icon icon="thumbs-down" /> <span>{{list.data.downs}} </span></a>
    <a :href="'https://reddit.com'+list.data.permalink" class="card-link"><font-awesome-icon icon="comment" /> <span>{{list.data.num_comments}} </span></a>
  </div>
  </div>

        </main>
        </div>
        <nav aria-label="Secondary navigation" aria-hidden="true" class="bd-toc col-xl-2 d-none d-xl-block">
            
            <ul class="nav section-nav flex-column"><!----> </ul>
        
        </nav>
        </div>
        </div>

    </div>
</template>
<script>
export default{
  data(){
    return {
      lists:"",
      isTop:true,
      isHot:false,
    }
  },
  created(){
    axios.get('http://localhost/tiktok/public/api/hot')
        .then((response)=> this.lists=response.data)
        .catch(error=>"something went wrong")
  },
  methods: {
    topposts:function(){
      this.isTop=true;
      this.isHot=false;
        axios.get('http://localhost/tiktok/public/api/top')
        .then((response)=> this.lists=response.data)
        .catch(error=>"something went wrong")
    },
    hotposts:function(){
      this.isTop=false;
      this.isHot=true;
        axios.get('http://localhost/tiktok/public/api/hot')
        .then((response)=> this.lists=response.data)
        .catch(error=>"something went wrong")
    },
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
    }
  },
  
}
</script>
<style scoped>

</style>