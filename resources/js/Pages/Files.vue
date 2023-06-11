<script>
  import MyHeader from "../Layouts/header.vue";
  import {heartbeat, httpGET} from "../HeartbeatWorker.js"


  var w;
  export default{
    mounted() {
      heartbeat();
      setInterval(heartbeat, 5000/*300000*/);
    },
    props:{
      logged:Boolean,
      FilesList:Array,
    },
    components: {
      MyHeader
    },
    methods:{
      clickedPath(path){
        console.log(path)
      }
    }
  }
</script>

<template>
  <div style="height: 100%; width: 100%; display: flex; flex-direction: column;">
    <MyHeader :logged="this.logged"/>
    <main style="display: flex; flex-direction: column; height: 100%; border: 1px solid white;">
      <h1 style="margin: auto; margin-top: 10px; margin-bottom: 10px;">Your Files</h1>
      <div style="max-width: 80%; max-height: 90%; width:100%; height: 100%; margin: auto; border: 1px solid black;">
        <table style="margin: 0px; width: 100%;">
          <thead style="border: 1px solid black; width: 100%;">
            <th>
              <p>Root</p>
            </th>
          </thead>
          <tbody v-for="path in FilesList">
            <tr>
              <td>
                <p @click="clickedPath(path)">{{path}}</p>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </main>
  </div>
</template>

<!-- need to make a call to a new api that sends the current path and where we want to go, the api will just append the path to go to the current path-->