<template>
<div>
    <navbar></navbar>
       <div class="mb-2 mt-3 text-center">
         <h2 class="text-light "><i class="fa fa-plus"></i> Following</h2>
         </div>    
     <div class="mt-1 mb-1">
        
        <div class="filter-buttons filter-buttons--big-font">
            <button v-on:click="filter('picture')" class="filter-buttons__button " v-bind:class="[isFilter=='picture' ? 'filter-buttons__button--active' : '']"><i class="fa fa-picture-o"></i> Pictures</button>
        <button  v-on:click="filter('videos')" v-bind:class="[isFilter=='videos' ? 'filter-buttons__button--active' : '']" class="filter-buttons__button filter-buttons__button--gifs"><i class="fa fa-video-camera"></i> Videos</button>
        
        <button  v-on:click="filter('adults')" v-bind:class="[isFilter=='adults' ? 'filter-buttons__button--active' : '']" class="filter-buttons__button filter-buttons__button--nsfw"><span class="filter-buttons__icon">18+</span>NSFW</button></div>
    </div>
    <div class="mt-1 mb-1">
        
        <div class="filter-buttons filter-buttons--big-font">
            <button class="filter-buttons__button" v-on:click="topposts"  v-bind:class="{ 'filter-buttons__button--active': isTop }"><i class="fa fa-line-chart"></i> Top</button>
            <button class="filter-buttons__button" v-on:click="hotposts"  v-bind:class="{ 'filter-buttons__button--active': isHot }"><i class="fa fa-free-code-camp"></i> Hot</button>
        </div>
    </div>
<div class="container-fluid page-content">
 <div class="text-center mt-5" v-if="!isLogin">
          <h4 class="text-light " >You must log in to show following</h4>  
  </div>

      <div class="row" v-else>
          
<div v-for="cat in cats" v-bind:key="cat.key" class="col-md-12 mb-3">
    

<slider :cat="cat" :type="type" :filter="isFilter"></slider>                       
                    </div>
                    </div>
  </div>
  </div>

</template>

<script>
import slider from './slider.vue'
import navbar from './navbar.vue'
   export default{
    components: {
        slider,navbar
    },   
  data(){
    return {
      cats:"",
      following:"",
      isLogin:"",
      isTop:true,
      isHot:false,
      type:'top',
      isFilter:"picture",
      resources:"",
    }
  },

  beforeMount(){
    this.getFollowing();
    this.topposts();
  },
  watch: {
  isLogin(after, before) {

    this.getFollowing();
    //console.log("before: "+before);
    //console.log("after: "+after);
  }
},

  methods: {

    getFollowing() {
      this.cats="";
              axios.get(this.appurl+'api/following',{
              headers: {
                Authorization: `Bearer ${localStorage.getItem("access_token")}`
              }
            })
        .then((response)=>{
         // console.log(response.data);
          this.isLogin=true;
          this.cats=response.data.categories;
            
        } )
        .catch(error => {
             // console.log(error);
              this.isLogin=false;
              this.cats="";
              $('.login-modal').modal('show');
              this.checklogin();
            });
      },
      adultFollowing() {
      this.cats="";
              axios.get(this.appurl+'api/adult/following',{
              headers: {
                Authorization: `Bearer ${localStorage.getItem("access_token")}`
              }
            })
        .then((response)=>{
          this.cats=response.data.categories;
            
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
              console.log(response.data);
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
      topposts:function(){
      this.isTop=true;
      this.isHot=false;
      this.type="top";
      this.getFollowing();
    },
    hotposts:function(){
      this.isTop=false;
      this.isHot=true;
      this.type="hot";
      this.getFollowing();
      
    },
    filter(f){
      this.isFilter=f;
      if(this.isFilter=='adults')
      {
        this.adultFollowing();
      }else{
        this.getFollowing();
      }
      

    }
    
    },
  
  
}
</script>
<style scoped>


</style>