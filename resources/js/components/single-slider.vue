<template>
     <div>
          <VueSlickCarousel v-bind="settings" >
      <div><img src="https://placeimg.com/200/200/any?1"></div>
      <div><img src="https://placeimg.com/200/200/any?2"></div>
      <div><img src="https://placeimg.com/200/200/any?3"></div>
      <div><img src="https://placeimg.com/200/200/any?4"></div>
      <div><img src="https://placeimg.com/200/200/any?5"></div>
      <div><img src="https://placeimg.com/200/200/any?6"></div>

    </VueSlickCarousel>
     </div>
</template>

<script>
import 'vue-slick-carousel/dist/vue-slick-carousel.css'
  import 'vue-slick-carousel/dist/vue-slick-carousel-theme.css'
  import VueSlickCarousel from 'vue-slick-carousel'
      export default {
          data() {
            return {
                settings: {
  "dots": true,
  "focusOnSelect": true,
  "infinite": true,
  "speed": 500,
  "slidesToShow": 3,
  "slidesToScroll": 3,
  "touchThreshold": 5
},
            }
            },
          components: { VueSlickCarousel },
            mounted() {
                //this.checkuserlike();
                console.log('Mounted');
            },
      }
</script>

<style scoped>

</style>
