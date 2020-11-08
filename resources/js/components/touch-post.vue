<template>
<div>
  <div class="auto-scroll-popup " v-on:mouseover="shower"  v-on:mouseout="hider"  v-bind:class="{ show: open}" >
  <i class="fa fa-times auto-scroll-popup__close" @click="stopscroll"></i>
  
  <div class="auto-scroll-popup__header"  v-on:mouseover="shower">
    
    <i class="fa fa-clock-o auto-scroll-popup__icon"></i>
    
    <div class="auto-scroll-popup__title">Auto scroll</div></div><div class="auto-scroll-popup__controls">

      <i v-on:click="pausescroll" v-if="isPlay" class="fa fa-pause auto-scroll-popup__play"></i>
      <i v-else v-on:click="playscroll" class="fa fa-play auto-scroll-popup__play"></i>
      
      <div class="slider"  v-on:mouseover="shower">
          <input type="range" v-on:change="changerange" min="1" max="5" class="form-control-range" v-model="range" id="formControlRange">
        </div>
        </div>
        </div>
<div class="modal fade  bd-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content bg-dark">
      <div class="modal-header">
        <h4 class="text-light" id="exampleModalLongTitle">Keybindings</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <table class="table keybind table-dark text-light">
            <tbody>
                <tr><th>a</th><td>previous</td></tr>
                <tr><th>d</th><td>next</td></tr>
                <tr><th>s</th><td>source</td></tr>
                <tr><th>h</th><td>hide Information</td></tr>
                <tr><th>o</th><td>open subreddit</td></tr>
                <tr><th>p</th><td>play/pause slideshow</td></tr>
                <tr><th>l</th><td>favorite/like</td></tr>
                <tr><th>r</th><td>more like this</td></tr>
            </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
    <navbar></navbar>
    <div class="fullscreen-view" @scroll="handleScroll">
        
      <!-- Example single danger button -->
<div class="btn-group dropleft single-menu">
  <button type="button" class="btn btn-sm dropdown-toggle menu-btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    <i class="fa fa-ellipsis-v" aria-hidden="true"></i>
  </button>
  <div class="dropdown-menu">
    <button class="dropdown-item" @click="slideshow" type="button">Slideshow</button>
   
    <button class="dropdown-item" @click="hideinfo" v-bind:class="{ active: isHide}" type="button">Hide Information</button>
    <button class="dropdown-item" v-bind:class="{ active: isFollow}"  @click="follow" type="button">Follow</button>
    <button class="dropdown-item" data-toggle="modal" data-target=".bd-example-modal-sm" type="button">Keybindings</button>
    <button class="dropdown-item" type="button">Report</button>
  </div>
</div>
        <button v-if="hasPrevious" class="btn btn-previous" v-on:click="previousslide"><i class="fa fa-arrow-left"></i></button>
        <button  v-if="hasNext" class="btn btn-next"  v-on:click="nextslide"><i class="fa fa-arrow-right"></i></button>
        
        <div v-if="isMobile" v-touch:swipe.up="swipeup" class="fullscreen-view__scrollable" v-bind:style="{ transform:'translateY('+x+'%)' }" >
            <!---Previous block---->
                <div class="fullscreen-view__entry-container" v-for="(post,index) in posts" v-bind:key="index" :postid='post.id' :title="index"  >
                    <div v-if="(post!=null)"  class="fullscreen-view__entry">
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

                        <router-link :to="{ name: 'Subreddit', params: { slug: cat_slug } }" class="item-panel__discover morelikethis">More like this
                            <i class="fa fa-long-arrow-right item-panel__discover-icon"></i></router-link>
                        
                        </div>
                        <div class="item-panel__right">
                            <a class="item-panel__button source" title="Source" :href="'https://reddit.com/'+post.permalink" target="__blank" rel="noopener noreferrer">
                            
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="item-panel__icon"><path d="M74.6 256c0-38.3 31.1-69.4 69.4-69.4h88V144h-88c-61.8 0-112 50.2-112 112s50.2 112 112 112h88v-42.6h-88c-38.3 0-69.4-31.1-69.4-69.4zm85.4 22h192v-44H160v44zm208-134h-88v42.6h88c38.3 0 69.4 31.1 69.4 69.4s-31.1 69.4-69.4 69.4h-88V368h88c61.8 0 112-50.2 112-112s-50.2-112-112-112z"></path></svg>
                            </a>
                            <like :post_id="post.id" :type="type"></like>
                            
                            </div>
                            </div>
                            </div>
                            </div>
                          </div>
                        </div>

<div v-else class="fullscreen-view__scrollable" v-bind:style="{ transform:'translateY(0%)' }" >
            <!---Previous block---->
                <div class="fullscreen-view__entry-container"  >
                    <div class="fullscreen-view__entry">
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
                        <router-link :to="{ name: 'Subreddit', params: { slug: cat_slug } }" class="item-panel__discover">More like this
                            <i class="fa fa-long-arrow-right item-panel__discover-icon"></i></router-link>
                        </div>
                        <div class="item-panel__right">
                            <a class="item-panel__button" title="Source" :href="'https://reddit.com/'+post.permalink" target="__blank" rel="noopener noreferrer">
                            
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="item-panel__icon"><path d="M74.6 256c0-38.3 31.1-69.4 69.4-69.4h88V144h-88c-61.8 0-112 50.2-112 112s50.2 112 112 112h88v-42.6h-88c-38.3 0-69.4-31.1-69.4-69.4zm85.4 22h192v-44H160v44zm208-134h-88v42.6h88c38.3 0 69.4 31.1 69.4 69.4s-31.1 69.4-69.4 69.4h-88V368h88c61.8 0 112-50.2 112-112s-50.2-112-112-112z"></path></svg>
                            </a>
                                   <button @click="likepost" class="item-panel__button fav" title="Favorite">
                                
                                <i v-if="liked" class="fa fa-heart active item-panel__icon" ></i> 
                        <i v-else class="fa fa-heart item-panel__icon"></i>
                            </button>
                            
                            </div>
                            </div>
                            </div>
                            </div>
                          </div>
                        </div>
                        <!--end scroller block-->
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
        isHide:false,
        posts:"",
        slug:"",
        apppath:"",
        post_id:"",
        liked:"",
        likes:"",
        type:"",
        top:0,
        x:0,
        cat_id:0,
        cat_slug:"",
        previous:"",
        next:"",
        isMobile:false,
        hasPrevious:false,
        hasNext:false,
        isFollow:false,
        open:false,
        isPlay:false,
        range:5,
        timee:"",
        post_id:"",
        subreddit:"",
        permalink:""
    }
  },
  mounted(){
    if(localStorage.getItem("showrange"))
    {
      this.range=localStorage.getItem("showrange");
    }
    window.addEventListener("keypress", e => {
    //console.log(String.fromCharCode(e.keyCode));
    var key=String.fromCharCode(e.keyCode);
    if(key=='a')
    {
      this.previousslide();
    }
    if(key=='d')
    {
      this.nextslide();
    }
    if(key=='s')
    {
      this.clicksource();
    }
    if(key=='o')
    {
      this.opensubreddit();
    }
    if(key=='h')
    {
      this.hideinfo();
    }
    if(key=='p')
    {
      this.playpauseslider();
    }
    if(key=='l')
    {
      this.likepost();
    }
    if(key=='r')
    {
      this.morelikethis();
      
    }
    
	});
  },
  methods:{
   
    clicksource(){
      //console.log('clicksource');
      //this.$router.push({ name: 'Subreddit ',params: { slug: this.cat_slug } });
      location.assign('https://reddit.com/'+this.permalink);
    },
    opensubreddit(){
        //console.log('opensubreddit');
        location.assign("https://reddit.com/"+this.subreddit);
      },
    playpauseslider(){
        //console.log('Play');
        if(this.isPlay==true)
        {
          this.pausescroll();
        }else{
          this.playscroll();
        }
      },
      morelikethis(){
        var that=this;
       // that.$router.push({ name: 'Subreddit ',params: { slug: that.cat_slug } });
        location.assign('#/r/'+this.cat_slug);
      },
       slideshow(){
         this.open=true;
            this.isPlay=true;
            this.hider();
         if(screen.width>600)
         {
           this.desktopslider();
         }else{
           this.mobileslider();
         }
    },
    desktopslider(){
         
      var that=this;
      
        this.timee=setInterval(function(){
          that.nextpost();
          that.nextslide();
          
          if(this.hasNext==false)
          {
            clearInterval(this.timee);
          }
        
      },this.range*1000);
    },
    changerange(){
            this.pausescroll();
            this.playscroll();
            localStorage.setItem('showrange',this.range);
          },
    mobileslider(){
    var that=this;
            that.open=true;
            that.isPlay=true;
            that.timee=setInterval(function(){
              that.x=parseInt(that.x)-100;
              //console.log(that.x);
              if(this.hasNext==true){
              $('.fullscreen-view__scrollable').css('transform','translateY(-'+that.x+'%);');
              that.newpost();
              }else{
                clearInterval(this.timee);
              }
              
            },this.range*1000);
          },
          stopscroll(){
            //console.log('stop');
            clearInterval(this.timee);
            this.open=false;
          },
          playscroll()
          {
            //console.log(this.range);
            this.open=true;
            this.isPlay=true;
            var that=this;
            that.timee=setInterval(function(){
              that.x=parseInt(that.x)-100;
              //console.log(that.x);
              $('.fullscreen-view__scrollable').css('transform','translateY(-'+that.x+'%);');
              that.newpost();
              if(that.hasNext==false)
          {
            clearInterval(that.timee);
          }
              
            },this.range*1000);
          },
          pausescroll()
          {
            var that=this;
            that.isPlay=false;
            clearInterval(that.timee);
          },
          shower(){
            $('.auto-scroll-popup').css('opacity','1');
            
          },
          hider(){
            setTimeout(function(){
              $('.auto-scroll-popup').css('opacity','0');
            },5000);
            
          },
    checkfollow(){
       var url=this.appurl+'api/check/follow/subreddit/'+this.cat_id;
            axios.get(url,{
              headers: {
                Authorization: `Bearer ${localStorage.getItem("access_token")}`
              }
            })
            .then(response => {
              //console.log(response.data);
              if(response.data.status=='1')
              {
                this.isFollow=true;
              }else{
                this.isFollow=false;
              }
            })
            .catch(error => {
              //console.log(error);
              this.isFollow=false;
              //this.isLogin=false;
            });
     },
     follow:function(){
  
       var url=this.appurl+'api/follow/subreddit/'+this.cat_id;
            axios.get(url,{
              headers: {
                Authorization: `Bearer ${localStorage.getItem("access_token")}`
              }
            })
            .then(response => {
              if(response.data.status=='1')
              {
                this.isFollow=true;
              }else{
                this.isFollow=false;
              }
            })
            .catch(error => {
              //console.log(error);
              this.isFollow=false;
              $('.login-modal').modal('show');
              //this.isLogin=false;
            });
     },
      checkuserlike(){
                    axios.get('api/check/post/like/'+this.post_id,{
                    headers: {
                        Authorization: `Bearer ${localStorage.getItem("access_token")}`
                    }
                    })
                    .then((response)=> {
                    this.likes=response.data.likes;
                    if(response.data.status)
                    {
                        this.liked=true;
                        
                    }else{
                        this.liked=false;
                    }
                        }
                    )
                    .catch(error=> console.log(error))
                },
                likepost:function(){
                        axios.get(this.appurl+'api/post/like/'+this.post_id,{
                        headers: {
                            Authorization: `Bearer ${localStorage.getItem("access_token")}`
                        }
                    })
                    .then((response)=> {
                            this.likes=response.data.likes;
                            if(response.data.status)
                            {
                                this.liked=true;
                                
                            }else{
                                this.liked=false;
                            }
                        }
                    )
                    .catch(error => {
              console.log("error");
              $('.login-modal').modal('show');
              //this.isLogin=false;
            });
    },
    hideinfo(){
      var that=this;
      that.isHide=!that.isHide;
      window.localStorage.setItem('hideinfo',that.isHide);
    },
      postdetail:function(){
          this.x="-100";
          this.slug=this.$route.params.slug;
          //for desktop
          if(screen.width>600)
          {
            this.isMobile=false;
            axios.get(this.appurl+'api/postdetail/'+this.slug)
            .then((response)=> {
              //console.log(response.data);
                this.post=response.data.posts[1];
                this.post_id=response.data.posts[1].id;
                this.subreddit=response.data.posts[1].subreddit;
                this.permalink=response.data.posts[1].permalink;
                this.type=response.data.type;
                this.cat_id=response.data.cat_id;
                this.cat_slug=response.data.cat_slug;
                this.checkfollow();
                this.checkuserlike();
                var previous=response.data.posts[0];
                if(previous)
                {
                  this.previous=previous.slug;
                  this.hasPrevious=true;
                }else{
                  this.previous="";
                  this.hasPrevious=false;
                }
                var next=response.data.posts[2];
                if(next)
                {
                  this.next=next.slug;
                  this.hasNext=true;
                }else{
                  this.next="";
                  this.hasNext=false;
                }
            });
          }else{
            this.isMobile=true;
            axios.get(this.appurl+'api/postdetail/'+this.slug)
            .then((response)=> {
              this.posts=response.data.posts;
                this.type=response.data.type;
                this.cat_id=response.data.cat_id;
                this.subreddit=response.data.posts[1].subreddit;
                this.cat_slug=response.data.cat_slug;
                this.checkuserlike();
                this.checkfollow();
                var previous=response.data.posts[0];
                if(previous)
                {
                  this.previous=previous.slug;
                  this.hasPrevious=true;
                }else{
                  this.previous="";
                  this.hasPrevious=false;
                }
                var next=response.data.posts[2];
                if(next)
                {
                  this.next=next.slug;
                  this.hasNext=true;
                }else{
                  this.next="";
                  this.hasNext=false;
                }
            }).catch(error => {
              //console.log(error);
              //this.isLogin=false;
            });;
          }
    },

    nextslide:function(){
          this.slug=this.next;
          //this.nextpost();
          //for desktop
            this.isMobile=false;
            axios.get(this.appurl+'api/postdetail/'+this.slug)
            .then((response)=> {
              //console.log(response.data);
              if(response.data.posts.length>0)
              {
                this.post=response.data.posts[1];
                this.post_id=response.data.posts[1].id;
                this.subreddit=response.data.posts[1].subreddit;
                this.permalink=response.data.posts[1].permalink;
                this.type=response.data.type;
                this.cat_id=response.data.cat_id;
                this.cat_slug=response.data.cat_slug;
                this.checkfollow();
                this.checkuserlike();
                var previous=response.data.posts[0];
                if(previous)
                {
                  this.previous=previous.slug;
                  this.hasPrevious=true;
                }else{
                  this.previous="";
                  this.hasPrevious=false;
                }
                var next=response.data.posts[2];
                if(next)
                {
                  this.next=next.slug;
                  this.hasNext=true;
                }else{
                  this.next="";
                  this.hasNext=false;
                }
              }else{
                this.next="";
                  this.hasNext=false;
              }
            }).catch(error => {
              clearInterval(this.timee);
            });
    },
    previousslide:function(){
          this.slug=this.previous;
          this.previouspost();
          //for desktop
            this.isMobile=false;
            axios.get(this.appurl+'api/postdetail/'+this.slug)
            .then((response)=> {
              //console.log(response.data);
                this.post=response.data.posts[1];
                this.post_id=response.data.posts[1].id;
                this.subreddit=response.data.posts[1].subreddit;
                this.permalink=response.data.posts[1].permalink;
                this.type=response.data.type;
                this.cat_id=response.data.cat_id;
                this.cat_slug=response.data.cat_slug;
                this.checkfollow();
                this.checkuserlike();
                var previous=response.data.posts[0];
                if(previous)
                {
                  this.previous=previous.slug;
                  this.hasPrevious=true;
                }else{
                  this.previous="";
                  this.hasPrevious=false;
                }
                var next=response.data.posts[2];
                if(next)
                {
                  this.next=next.slug;
                  this.hasNext=true;
                }else{
                  this.next="";
                  this.hasNext=false;
                }
            }).catch(error => {
              clearInterval(this.timee);
            });
    },
    newpost:function(){
      var that=this;
          this.slug=this.next;
          //console.log(this.next);
          //for desktop
            this.isMobile=true;
            axios.get(this.appurl+'api/post/new/'+this.slug)
            .then((response)=> {
              //console.log(response.data);
              if(response.data.posts.length>0)
              {
                for (let i = 0; i < response.data.posts.length; i++){
                   that.posts.push(response.data.posts[i]); 
                }
                this.type=response.data.type;
                this.cat_id=response.data.cat_id;
                var next=response.data.posts[0];
                if(next)
                {
                  this.next=next.slug;
                  this.hasNext=true;
                }else{
                  this.next="";
                  this.hasNext=false;
                }
              }else{
                this.next="";
                  this.hasNext=false;
              }
            }).catch(error => {
              clearInterval(this.timee);
            });
    },
    unshiftpost:function(){
      var that=this;
          that.slug=that.previous;
          //console.log(this.previous);
          //for desktop
            this.isMobile=true;
            //console.log(that.appurl+'api/post/new/previous/'+that.slug);
            axios.get(that.appurl+'api/post/new/previous/'+that.slug)
            .then((response)=> {
              if(response.data.posts.length>0)
              {
                for (let i = 0; i < response.data.posts.length; i++){
                   that.posts.unshift(response.data.posts[i]); 
                }
                this.type=response.data.type;
                this.cat_id=response.data.cat_id;
                var previous=response.data.posts[0];
                if(previous)
                {
                  this.previous=previous.slug;
                  this.hasPrevious=true;
                }else{
                  this.previous="";
                  this.hasPrevious=false;
                }
              }else{
                this.previous="";
                  this.hasPrevious=false;
              }
            }).catch(error => {
              //clearInterval(this.timee);
            });
    },
        nextpost:function(){
          //console.log(this.next);
          if(this.next){
            this.$router.push({ name: 'Post',params: { slug: this.next } });
          }
        },
        previouspost:function(){
          if(this.previous){
            this.$router.push({ name: 'Post',params: { slug: this.previous } }).catch(()=>{});
          }
        },
         swipeup(direction, event){
           if(direction=='bottom')
           {
             var that=this;
            // that.x=parseInt(that.x)+100;
            if(that.hasPrevious)
            {
              that.unshiftpost();
            }
           }
    
    },
        handleScroll: function(e) {
            var currentScrollPosition = e.srcElement.scrollTop;
            var h=screen.height;
            var quarter=h/3;
            //console.log(currentScrollPosition);
            if(currentScrollPosition%h==0)
            {
              if(this.hasNext)
              {
                this.nextpost();
                this.newpost();
              }
            }
            if (currentScrollPosition > this.scrollPosition) {
                if(currentScrollPosition>=(quarter+quarter))
                {
                  this.x-=100;
                }
            }
            if (currentScrollPosition < this.scrollPosition) {
                //this.previouspost();       
            }
            this.scrollPosition = currentScrollPosition;
        },
  },
  created() {
    this.apppath=this.appurl;
    this.slug=this.$route.params.slug;
    this.postdetail();
    var isHide=localStorage.getItem("hideinfo"); 
    if(isHide=='true')
    {
      this.isHide=true;
    }else{
      this.isHide=false;
    }
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
.single-menu {
    position: absolute;
    top: 10px;
    right: 0;
}
.menu-btn{
    position: absolute;
    top: 10px;
    right: 10px;
}
.dropleft .dropdown-toggle::before{
    display: none;
}

.table-dark.keybind th, .table-dark td, .table-dark thead th{
border: none;
    padding-top: 0px;
    padding-bottom: 5px;
    font-size: 1.2rem;
}
.auto-scroll-popup.show{
  display: block;
  z-index: 90;
}
.auto-scroll-popup {
  display:none;
    right: 0;
    transform: none;
    margin: 2.5rem;
}
.auto-scroll-popup--hidden {
    opacity: 0;
}
.auto-scroll-popup {
    position: fixed;
    top: 0;
    right: 50%;
    margin: 1rem 0;
    transform: translateX(50%);
    background-color: #1b252f;
    box-shadow: 1rem 1rem 4rem rgba(0,0,0,.5);
    border-radius: 5px;
    padding: 2rem;
    fill: #fff;
    z-index: 99;
    transition: opacity .4s;
    opacity: 1;
}
.auto-scroll-popup__close {
    position: absolute;
    right: 1rem;
    top: 1rem;
    height: 2.5rem;
    cursor: pointer;
    color:#fff
}
.auto-scroll-popup__header {
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 20px;
}
.auto-scroll-popup__icon {
    width: 3rem;
    margin-right: 1rem;
    color:#fff;
}
.auto-scroll-popup__title {
    font-weight: 400;
}
.auto-scroll-popup__controls {
    margin-top: .5rem;
    width: 15rem;
    display: flex;
    align-items: center;
}
.auto-scroll-popup__play {
  color:#fff;
    cursor: pointer;
    width: 2rem;
    box-sizing: initial;
    padding: .5rem 0 .5rem .5rem;
    margin-right: 1rem;
    -webkit-user-select: none;
    -ms-user-select: none;
    user-select: none;
    -webkit-tap-highlight-color: transparent;
}
.slider {
    cursor: pointer;
    width: 100%;
    padding: .75rem 0;
    -webkit-user-select: none;
    -ms-user-select: none;
    user-select: none;
    -webkit-tap-highlight-color: transparent;
}
.slider__line {
    width: calc(100% - 20px);
    margin: 0 auto;
    background-color: #fff;
    height: 5px;
    flex-grow: 1;
    position: relative;
}
</style>