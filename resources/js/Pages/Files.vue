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
  <div style="height: 100%; width: 100%;">
    <MyHeader :logged="this.logged"/>
    <main style="max-height: inherit; display: flex; flex-direction: column; height: 100%; border: 1px solid white;">
      <div style="max-width: 80%; max-height: 80%; width:100%; height: 100%; margin: auto; border: 1px solid white;">
        <p>test file</p>
      </div>
    </main>
  </div>
</template>