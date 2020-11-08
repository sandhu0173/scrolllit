<template>
      <div>
            <button @click="likepost" class="item-panel__button fav" title="Favorite">
                                
                                <i v-if="liked" class="fa fa-heart active item-panel__icon" ></i> 
                        <i v-else class="fa fa-heart item-panel__icon"></i>
                            </button>
            
      </div>
</template>
<script>
      export default {
          data(){
                return {
                postid:"",
                liked:false,
                likes:0
                }
            },
            mounted() {
                this.checkuserlike();
                //console.log(this.type);
                $('.login-modal').modal('hide');
            },
            props: ['post_id','type'],
            methods: {
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
              $('.login-modal').modal('show');
              //this.isLogin=false;
            });
                }
                  
            }
      }
</script>


<style>
      .avatar-like{
            border-radius: 50%;
      }
</style>