<template>
<div class="container">                
      <div class="row">  
<div v-for="cat in cats" v-bind:key="cat.key" class="col-md-4 mb-3">
    <router-link :to="{ name: 'Category', params: { slug: cat.slug } }" class="btn btn-large btn-block" v-bind:style="{ background: cat.color }">
                           
                                    {{cat.title}}
                                
    </router-link>
                       
                    </div>
                    </div>
  </div>

</template>

<script>
   export default{
        
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
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
}
.btn{
    color: #fff;
    font-size: 2rem;
    padding: 10px;
}
</style>