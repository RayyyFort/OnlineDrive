<script>
  import MyHeader from "../Layouts/header.vue";

  export default{
    props: {
        logged: Boolean,
    },
    components: {
      MyHeader
    }
  };
</script>

<template>
  <div>
    <MyHeader :logged="this.logged"/>
    <h1>About OnlineDrive</h1>
    <p>This my website to store files online for free !</p>
  </div>
</template>