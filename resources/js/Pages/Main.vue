<script>
  import MyHeader from "../Layouts/header.vue";

  export default{
    props:{
      logged:String,
    },
    components: {
      MyHeader
    }
  }
</script>

<template>
  <div>
    <MyHeader :logged="this.logged"/>
      
  </div>
</template>