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
    <div class="d-flex flex-column">
      <h1 style="margin-left:auto;margin-right: auto;">About OnlineDrive</h1>
      <div style="margin-left:auto;margin-right: auto;margin-top: 20px;">
        <p>This is a website to store files online for free !</p>
        <p>Our primary goal is just to host a file server for everyone to enjoy</p>
        <p>Every user has access to unlimited storage</p>
      </div>
    </div>
  </div>
</template>