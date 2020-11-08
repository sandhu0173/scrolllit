/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');


import axios from 'axios'
import VueAxios from 'vue-axios'
Vue.use(VueAxios, axios)

import VueRouter from 'vue-router';
window.Vue = require('vue');
Vue.use(VueRouter);

import $ from 'jquery'



/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))


const Home = Vue.component('Home', require('./components/home.vue').default);
const Discover = Vue.component('Discover', require('./components/Discover.vue').default);
const Post = Vue.component('Post', require('./components/post.vue').default);
const Category = Vue.component('Category', require('./components/category.vue').default);
const Subreddit = Vue.component('Subreddit', require('./components/subreddit.vue').default);
const Favorites = Vue.component('Favorites', require('./components/Favorites.vue').default);
const Following = Vue.component('Following', require('./components/Following.vue').default);

//import navbar from './components/navbar.vue';
//import sidebar from './components/sidebar.vue';
//import rightside from './components/rightsidebar.vue';

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

  
Vue.mixin({
    data: function() {
      return {
        sitetitle:"Beta",
        appurl:"http://localhost/tiktok/public/",
      }
    }
  });

const routes = [
  { path: '/', component: Home,name:"Home" },
  { path: '/discover', component: Discover,name:"Discover" },
  { path: '/favorites', component: Favorites,name:"Favorites" },
  { path: '/following', component: Following,name:"Following" },
  { path: '/c/:slug', component: Category,name:'Category' },
  { path: '/r/:slug', component: Subreddit,name:'Subreddit' },
  { path: '/post/:slug', component: Post,name:'Post' },
  { path: '*', redirect: '/' }
]
const router = new VueRouter({
  routes // short for `routes: routes`
});

const updateSizes = (obj = {}) => {
  obj.width = window.innerWidth
  obj.height = window.innerHeight
  return obj
}

Object.defineProperty(Vue.prototype, '$viewport', {
  value: Vue.observable(updateSizes())
})

window.addEventListener('resize', () => {
  updateSizes(Vue.prototype.$viewport)
})
  
const app = new Vue({
    el: '#app',
    router,
    created: function() {
        this.sitetitle = "Beta";
        this.appurl="http://localhost/tiktok/public/";
      },
    
});
