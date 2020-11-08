<template>
<div  id="block" v-on:scroll="handleScroll(event)">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <a class="navbar-brand" href="#">{{sitetitle}}</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Subreddits
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
            <router-link v-for="subcategory in subcategories" :key="subcategory.key" :to="{ name: 'Subreddit', params: { slug: subcategory.slug } }" class="btn btn-large btn-block">
                           
                                r/{{subcategory.slug}}
                                
    </router-link>
          
        </div>
      </li>
      
    </ul>
    <form class="form-inline my-2 my-lg-0">
      <input class="form-control mr-sm-2" v-model="searchQuery" type="search" placeholder="Search" aria-label="Search">
      
    </form>
  </div>
</nav>
<div class="search-block d-block d-sm-none">
  <input type="" class="form-control" v-model="searchQuery"  placeholder="Search">
</div>
<div v-if="resources.length" class="search-result container-fluid" >
  
  <div class="row" v-for="resource in resources" v-bind:key="resource.key">
    <div class="col-3  col-lg-3 col-md-3  thumbnail">
                   <router-link :to="{ name: 'Subreddit', params: { slug: resource.slug } }" > <template v-if="resource.post.post_type">
                            <img v-bind:src="resource.post.thumbnail" class="img-fluid post-image" alt="">

                         
  </template></router-link>
    </div>
    <div class="col-9 col-lg-9 col-md-9 desciption">
      <router-link  :to="{ name: 'Subreddit', params: { slug: resource.slug } }" >
      <h3 class="title">{{resource.post.post_title}}</h3>
      <p class="desc">{{resource.link}} </p>
      </router-link>
    </div>
  </div>
</div>
<div class="header-bottom cover " :style="{ backgroundImage: `url(${apppath+category.cover})` }"  >
    
</div>
<div class="page-top">
    <div class="container">
        <div class="row">
            <div class="col-lg-1 nopadding"><img :src="apppath+category.profile" class="img-fluid rounded-circle profile"></div>
            <div class="col-lg-11 nopadding text-left">
                <h2 class="heading mt-2 mb-1">{{category.title}}</h2>
                <p class="desc nopadding">r/{{category.slug}}</p>
            </div>
        </div>
    </div>
</div>
<div class="data-container container">
<div class="row justify-content-center">

<div class="col-sm-7">
    <div class="page-content">
        <div class="row listing mb-3">
        <div class="card text-white bg-dark ">
            <div class="card-header">
              <ul class="search-menu">
                <li>
                <a href="javascript:void(0)" class="top-button" v-on:click="topposts"  v-bind:class="{ active: isTop }"> <font-awesome-icon icon="chart-bar" /> <span>Top</span></a></li>
                <li>
                  <a href="javascript:void(0)" class="top-button" v-on:click="hotposts"  v-bind:class="{ active: isHot }"> <font-awesome-icon icon="fire" /> <span> Hot </span></a></li>
              </ul>
              <div class="dropdown mobilemenu d-block d-sm-none">
  <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
  <template v-if="isTop">
    <font-awesome-icon icon="chart-bar" /> <span>Top</span></template>
  <template v-if="isHot">
    <font-awesome-icon icon="fire" /> <span> Hot </span></template>
  
  </button>
  <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
    <a href="javascript:void(0)" class="dropdown-item" v-on:click="topposts"  v-bind:class="{ active: isTop }"> <font-awesome-icon icon="chart-bar" /> <span>Top</span></a>
    <a class="dropdown-item"  v-on:click="hotposts"  v-bind:class="{ active: isHot }"> <font-awesome-icon icon="fire" /> <span> Hot </span></a>
    
  </div>
</div>
              
            </div>
        </div>

        </div>
        
        
        <div class="row listing mb-3"  v-for="list in lists" v-bind:key="list.key">
            <div class="card-right col-sm-12 nopadding">
                <div class="card text-white bg-dark">
                    
                    
                    <div class="card-header">{{list.post_title}}</div>

                    <div class="card-body nopadding">
                        <template v-if="list.post_type">
                        <template v-if="list.post_type=='image'">
                            <img v-bind:src="list.url" class="img-fluid post-image" alt="">
                        </template>

                         <template v-if="list.post_type=='rich:video'">
    <div v-if="list.url">
      
  <video controls>
  <source :src="list.url" type="video/mp4" autostart="false">
  <source :src="list.url" type="video/ogg" autostart="false">
Your browser does not support the video tag.
</video>
</div>
  </template>
  </template>
                    </div>
                    <div class="card-footer footer-links">
                        <like :post_id="list.post_id" :type="type"></like>
                        <a v-if="list.sauce" :href="list.sauce" class="card-link "><span class="badge badge-success">Sauce</span></a>
                    </div>
                </div>

            </div>
        </div>
    </div>    
</div>
<div class="col-sm-4">
  <div class="page-content">
  <div class="row listing mb-3">
        <div class="card text-white bg-dark">
            <div class="card-body">
              <div class="row">
              <div class="col-sm-6 nopadding">
                <div class="list-group">
  <a href="#" class="list-group-item list-group-item-action">
    Help
  </a>
  <a href="#" class="list-group-item list-group-item-action"> App</a>
  <a href="#" class="list-group-item list-group-item-action"> Coin</a>
</div>
</div>
        <div class="col-sm-6 nopadding">
                <div class="list-group">
  <a href="#" class="list-group-item list-group-item-action">About</a>
  <a href="#" class="list-group-item list-group-item-action">Careers</a>
  
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

</div>
</template>

<script>
import debounce from 'lodash/debounce';
import Like from './likes.vue'
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
            Like
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
      searchSubreddit() {
                axios.get(this.appurl+'api/search/subreddit/',{params: this.axiosParams})
        .then((response)=>{
          console.log(response.data);
          this.resources=response.data.lists;
            
        } )
        .catch(error=>"something went wrong");
  },
    topposts:function(){
      this.isTop=true;
      this.isHot=false;
      this.lists="";
        axios.get(this.appurl+'api/top/'+this.cat)
        .then((response)=>{
        //console.log(response.data);
            this.lists=response.data.posts;
            this.type=response.data.type;
            this.offset=response.data.offset;
            //console.log(this.lists);
        } )
        .catch(error=>"something went wrong");
    },
    hotposts:function(){
      this.isTop=false;
      this.lists="";
      this.isHot=true;
        axios.get(this.appurl+'api/hot/'+this.cat)
        .then((response)=>{
          //console.log(response);
            this.lists=response.data.posts;
            this.type=response.data.type;
            this.offset=response.data.offset;
        } )
        .catch(error=>"something went wrong")
    },
    catdetail:function(){

        axios.get(this.appurl+'api/subcatdetail/'+this.cat)
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
     axios.get(this.appurl+'api/loadmore/'+this.type+'/'+this.cat+'/'+this.offset)
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