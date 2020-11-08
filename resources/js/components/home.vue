<template>
<div>
  <navbar></navbar>
         <div class="mb-2 mt-1 text-center">
         <h2 class="site-title text-light ">{{sitetitle}}</h2>
         </div>
<div class="container">
      <div class="row">  
<div v-for="cat in cats" v-bind:key="cat.key" class="col-md-4 mb-3">
    <router-link :to="{ name: 'Category', params: { slug: cat.slug } }" class="btn btn-large btn-block" v-bind:style="{ background: cat.color }">
                           
                                    {{cat.title}}
                                
    </router-link>
                       
                    </div>
                    </div>
  </div>
  </div>

</template>

<script>
import navbar from './navbar.vue'

   export default{
     components: {
            navbar
        },   
  data(){
    return {
      cats:"",
    }
  },
  beforeMount(){
    this.categories();
  },
  methods: {
    categories:function(){
        axios.get(this.appurl+'api/categories')
        .then((response)=> this.cats=response.data.categories)
        .catch(error=>"something went wrong")
    },
    
  },
  
  
}
</script>
<style scoped>
body {
    -webkit-tap-highlight-color: transparent;
    background-color: #1b252f;
    font-size: 14px;
    font-family: Raleway;
    width: 100%;
    margin: 0;
}
.container {
    height: 60vh;
    display: flex;
    justify-content: center;
    align-items: center;
}
.btn{
    color: #fff;
    font-size: 2rem;
    padding: 10px;
}
@media only screen and (max-width: 600px) {
  .container {
    height: 100vh;
  }
}
</style>