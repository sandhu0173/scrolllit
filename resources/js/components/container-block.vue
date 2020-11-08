<template>
<div >
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <a class="navbar-brand" href="#">{{title}}</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item active">
        <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#">Link</a>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Dropdown
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
          <a class="dropdown-item" href="#">Action</a>
          <a class="dropdown-item" href="#">Another action</a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="#">Something else here</a>
        </div>
      </li>
      <li class="nav-item">
        <a class="nav-link disabled" href="#">Disabled</a>
      </li>
    </ul>
    <form class="form-inline my-2 my-lg-0">
      <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
      <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
    </form>
  </div>
</nav>
<div class="header-bottom"></div>
<div class="page-top">
    
</div>
<div class="container">
<div class="row justify-content-center">

<div class="col-sm-8">
    <div class="page-content">
        <div class="row listing mb-3">
        <div class="card text-white bg-dark ">
            <div class="card-header">
                <a href="javascript:void(0)" class="top-button" v-on:click="topposts"  v-bind:class="{ active: isTop }"> <font-awesome-icon icon="chart-bar" /> <span>Top</span></a>
                <a href="javascript:void(0)" class="top-button" v-on:click="hotposts"  v-bind:class="{ active: isHot }"> <font-awesome-icon icon="fire" /> <span> Hot </span></a>

            </div>
        </div>
        </div>
        
        <div class="row listing mb-3"  v-for="list in lists" v-bind:key="list.key">
            <div class="card-right col-sm-12 nopadding">
                <div class="card text-white bg-dark">
                    
                    
                    <div class="card-header">{{list.data.title}}</div>

                    <div class="card-body nopadding">
                        <template v-if="list.data.post_hint">
                        <template v-if="list.data.post_hint=='image'">
                            <img v-bind:src="list.data.url" class="img-fluid" alt="">
                        </template>

                         <template v-if="list.data.post_hint=='rich:video'">
    <div v-if="list.data.preview.reddit_video_preview">
  <video controls>
  <source :src="list.data.preview.reddit_video_preview.fallback_url" type="video/mp4">
  <source :src="list.data.preview.reddit_video_preview.fallback_url" type="video/ogg">
Your browser does not support the video tag.
</video>
</div>
  </template>
  </template>
                    </div>
                    <div class="card-footer footer-links">
                        <a :href="'https://reddit.com'+list.data.permalink" class="card-link"><font-awesome-icon icon="thumbs-up" /> <span>{{list.data.ups}} </span></a>
    <a :href="'https://reddit.com'+list.data.permalink" class="card-link"><font-awesome-icon icon="thumbs-down" /> <span>{{list.data.downs}} </span></a>
    <a :href="'https://reddit.com'+list.data.permalink" class="card-link"><font-awesome-icon icon="comment" /> <span>{{list.data.num_comments}} </span></a>
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
  <a href="#" class="list-group-item list-group-item-action">Reddit App</a>
  <a href="#" class="list-group-item list-group-item-action">Reddit Coin</a>
</div>
</div>
        <div class="col-sm-6 nopadding">
                <div class="list-group">
  <a href="#" class="list-group-item list-group-item-action">About</a>
  <a href="#" class="list-group-item list-group-item-action">Careers</a>
  <a href="#" class="list-group-item list-group-item-action">Press</a>
  <a href="#" class="list-group-item list-group-item-action">Advertise</a>
  <a href="#" class="list-group-item list-group-item-action">Blog</a>
  <a href="#" class="list-group-item list-group-item-action">Terms</a>
  <a href="#" class="list-group-item list-group-item-action">Content Policy</a>
  <a href="#" class="list-group-item list-group-item-action">Privacy Policy</a>
  <a href="#" class="list-group-item list-group-item-action">Mod Policy</a>
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
   export default{
        
  data(){
    return {
      lists:"",
      isTop:true,
      isHot:false,
    }
  },
  beforeMount(){
    this.topposts();
  },
  methods: {
    topposts:function(){
      this.isTop=true;
      this.isHot=false;
        axios.get(this.appurl+'/api/top')
        .then((response)=> this.lists=response.data)
        .catch(error=>"something went wrong")
    },
    hotposts:function(){
      this.isTop=false;
      this.isHot=true;
        axios.get(this.appurl+'/api/hot')
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
