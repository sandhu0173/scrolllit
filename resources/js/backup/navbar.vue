<template>
<div>
  <button aria-label="Toggle sidebar" v-on:click="opensidebar" class="sidebar-button sidebar-button--animate " v-bind:class="{ 'sidebar-button--open': isOpen}">
    <div class="sidebar-button__circle"><i class="fa fa-bars sidebar-button__icon sidebar-button__icon--svg"></i>
  </div>
  </button>
  <nav class="sidebar  sidebar--animate" v-bind:class="{ 'sidebar--open': isOpen}">
    
    <router-link :to="{ name: 'Home' }" class="sidebar__logo-link side-title" >
{{sitetitle}}

    </router-link>

  
  <div class="sidebar__items">

   <router-link :to="{ name: 'Home' }" class="sidebar__item" ><i class="fa fa-home"></i> <div class="sidebar__title">Home</div></router-link>


   <router-link :to="{ name: 'Favorites' }" class="sidebar__item" ><i class="fa fa-heart"></i><div class="sidebar__title">Favorites</div></router-link>


   <router-link :to="{ name: 'Following' }" class="sidebar__item" ><i class="fa fa-plus"></i>
  <div class="sidebar__title">Following</div></router-link>

  <router-link :to="{ name: 'Discover' }" class="sidebar__item" ><i class="fa fa-search"></i>
  <div class="sidebar__title">Discover</div></router-link>
 
    
    <a v-on:click="logout" v-if="isLogin" class="sidebar__item" href="" rel="noopener noreferrer"><i class="fa fa-sign-out"></i><div class="sidebar__title">Log out</div></a>
  <a data-toggle="modal" v-else  data-target=".login-modal" class="sidebar__item" href="" rel="noopener noreferrer"><i class="fa fa-sign-in"></i><div class="sidebar__title">Log in</div></a>
  
<button class="sidebar__item"  @click="scrolldown" type="button">
<i class="fa fa-clock-o"></i><div class="sidebar__title">Auto Scroll</div>
</button>
    
    </div>
    </nav>
    <div class="modal fade login-modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <nav>
  <div class="nav nav-tabs" id="nav-tab" role="tablist">
    <a class="nav-item nav-link active" id="nav-login-tab" data-toggle="tab" href="#nav-login" role="tab" aria-controls="nav-login" aria-selected="true">LOG IN</a>
    <a class="nav-item nav-link" id="nav-signup-tab" data-toggle="tab" href="#nav-signup" role="tab" aria-controls="nav-signup" aria-selected="false">REGISTER</a>
    
  </div>
</nav>
<div class="tab-content pb-2 pt-2" id="nav-tabContent">
  <div class="tab-pane fade show active" id="nav-login" role="tabpanel" aria-labelledby="nav-login-tab">
    <div class="col-sm-12 mt-2">
      <p class="alert alert-danger" v-if="lerror">{{lerror}}</p>
    <form v-on:submit="loginuser" method="POST">
      <div class="form-group">
        <label><strong>Username</strong> </label>
        <input type="email" required class="form-control" v-model="login.username" >
      </div>
      <div class="form-group">
        <label><strong>Password</strong></label>
        <input class="form-control" required type="password" v-model="login.password">
      </div>
      <div class="form-group">
        <button class="btn btn-login">Login</button>
      </div> 
    </form>
    </div>
  </div>
  <div class="tab-pane fade" id="nav-signup" role="tabpanel" aria-labelledby="nav-signup-tab">
    <div class="col-sm-12 mt-2">

    <p v-for="serror in serrors" v-bind:key="serror.key" class="alert alert-danger">{{serror}}</p>
    <form method="POST"  v-on:submit="registeruser">
      <div class="form-group">
        <label><strong>Username</strong></label>
        <input type="email" required class="form-control" v-model="signup.username">
      </div>
      <div class="form-group">
        <label><strong>Password</strong></label>
        <input class="form-control" required type="password" v-model="signup.password">
      </div>
      <div class="form-group">
        <button class="btn btn-signup">Register!</button>
      </div> 
    </form>
    </div>
    </div>
  
</div>
    </div>
  </div>
</div>

<div class="auto-scroll-popup " v-on:mouseover="shower"  v-bind:class="{ show: open}" >
  <i class="fa fa-times auto-scroll-popup__close" @click="stopscroll"></i>
  
  <div class="auto-scroll-popup__header">
    
    <i class="fa fa-clock-o auto-scroll-popup__icon"></i>
    
    <div class="auto-scroll-popup__title">Auto scroll</div></div><div class="auto-scroll-popup__controls">

      <i v-on:click="pausescroll" v-if="isPlay" class="fa fa-pause auto-scroll-popup__play"></i>
      <i v-else v-on:click="playscroll" class="fa fa-play auto-scroll-popup__play"></i>
      
      <div class="slider">
        
          <input type="range" v-on:change="changerange" min="1" max="5" class="form-control-range" v-model="range" id="formControlRange">
        </div>
        </div>
        </div>

</div>

</template>

<script>

    export default {
       data(){
          return {
            isPlay:false,
            isOpen:false,
            serrors:{},
            lerror:"",
            isLogin:false,
            login:{
              username:"",
              password:"",
            },
            signup:{
              username:"",
              password:"",
            },
            open:"",
            range:1,
            timee:""

          }
        },
        
        mounted() {
          this.checkuser();  
          this.range=localStorage.getItem("range")
        },
        methods: {
          opensidebar:function(){
            this.isOpen=!this.isOpen;
          },
          logout(e){
            e.preventDefault();
            var url=this.appurl+'api/auth/logout';
            axios.get(url,{
              headers: {
                Authorization: `Bearer ${localStorage.getItem("access_token")}`
              }
            })
            .then(response => {
              //console.log(response.data);
              localStorage.clear();
              this.isLogin=false;
              
            })
            .catch(error => {
              //console.log(error);
              //this.isLogin=false;
            });
          },
           changerange(){
            this.pausescroll();
            this.playscroll();
            localStorage.setItem('range',this.range);
          },
          checkuser()
          {
            var url=this.appurl+'api/auth/user';
            axios.get(url,{
              headers: {
                Authorization: `Bearer ${localStorage.getItem("access_token")}`
              }
            })
            .then(response => {
              //console.log(response.data);
              this.isLogin=true;
              
            })
            .catch(error => {
              //console.log(error);
              this.isLogin=false;
            });
            
          },
          loginuser(e)
          {
            var that=this;
            //$('.login-modal').modal('hide');
                e.preventDefault();
                var url=this.appurl+'api/auth/login';
                axios.post(url, {
                email:this.login.username,
                password:this.login.password,
            })
            .then(function(response){
              that.lerror="";
                if(response.data.status=="error")
                {
                  that.lerror=response.data.error;
                }
                if(response.data.status=="success")
                {
                  that.isLogin=true;
                  localStorage.setItem('access_token',response.data.access_token);
                  localStorage.setItem('expire_at',response.data.expires_at);
                  $('.login-modal').modal('hide');
                  that.checkuser();
                }
            }).catch(function(error){
            })
          },
          registeruser(e)
          {
            e.preventDefault();
            var that=this;
            that.serrors={};
            console.log(this.signup);
            var url=this.appurl+'api/auth/signup';
            axios.post(url, {
            email:this.signup.username,
            password:this.signup.password,
            })
            .then(function(response){
                //console.log(response.data.errors);
                if(response.data.status=="error")
                {
                  that.serrors=response.data.errors;
                }
                {
                if(response.data.status=="success")
                  that.isLogin=true;
                  localStorage.setItem('access_token',response.data.access_token);
                  localStorage.setItem('expire_at',response.data.expires_at);
                  $('.login-modal').modal('hide');
                }
            }).catch(function(error){
              //console.log('error');
              //console.log(error);
            })
          },
          scrolldown(){
            this.open=true;
            this.isPlay=true;
            this.hider();
           // console.log(this.isPlay);
            this.timee=setInterval(function(){
              $('html, body').animate({scrollTop: '+=10px'}, 10);
              
            },this.range*10);
          },
          stopscroll(){
            //console.log('stop');
            clearInterval(this.timee);
            this.open=false;
          },
          playscroll()
          {
            this.open=true;
            this.isPlay=true;
            this.timee=setInterval(function(){
              $('html, body').animate({scrollTop: '+=10px'}, 10);
            },this.range*100);
          },
          pausescroll()
          {
            var that=this;
            that.isPlay=false;
            clearInterval(that.timee);
          },
          shower(){
            $('.auto-scroll-popup').css('opacity','1');
            this.hider();
          },
          hider(){
            setTimeout(function(){
              $('.auto-scroll-popup').css('opacity','0');
            },5000);
            
          }
        },
    }
</script>
<style scoped>
button {
    border: none;
    background-color: inherit;
    font: inherit;
    color: inherit;
    -webkit-tap-highlight-color: transparent;
}
.btn-login,.btn-signup {
    color: #fff;
    background-color: #d00a54 !important;
    border-color: #d00a54 !important;
    width: 100%;
}
.btn-login:hover,.btn-signup:hover{
  color: #fff;
}
.nav-tabs .nav-link{
  padding: 0.8rem 2.5rem;
  color: #000;
}
.nav-tabs .nav-link.active{
  background-color: transparent;
  border-color: transparent;
    border-bottom: 2px solid #d00a54;
}
.auto-scroll-popup.show{
  display: block;
  z-index: 9;
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
    bottom: 0;
    right: 16rem;
    bottom:1rem;
    margin: 2.5rem 0;
    transform: translateX(50%);
    background-color: #1b252f;
    box-shadow: 1rem 1rem 4rem rgba(0,0,0,.5);
    border-radius: 5px;
    padding: 2rem;
    fill: #fff;
    z-index: 3;
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
    width: 25rem;
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