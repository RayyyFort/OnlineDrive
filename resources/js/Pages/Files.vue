<script>
  import MyHeader from "../Layouts/header.vue";
  import {heartbeat, httpGET} from "../HeartbeatWorker.js"
import axios from "axios";
import { list } from "postcss";

  var w;
  export default{
    mounted() {
      heartbeat();
      setInterval(heartbeat, 5000/*300000*/);
    },
    props:{
      logged:Boolean,
      FilesList:Array,
      CurrentFolder:String,
      dirArr:Array,
    },
    components: {
      MyHeader
    },
    data() {
      return{
        current: this.CurrentFolder,
        Filess: this.FilesList,
        areDir: this.dirArr,
      }
    },
    methods:{
      clickedPath(nextpath){
        var currentpath = $('#currentPath').text()
        if (nextpath == ".."){
          for (let i = currentpath.length; i > 0; i--) {
            if (currentpath[i] == "\\"){
              pathtogo = currentpath.substring(0,i);
              break;
            }
          }
        }
        else{
          var pathtogo = currentpath+"\\"+nextpath;
        }
        var vm = this;
        console.log(pathtogo);
        $.ajax({
          type: "get",
          url: route('Files.CustomPath', {PathToGo: pathtogo}),
          data: $(this).serialize(),
          dataType: 'json',
          success: async function(data) {
            vm.Filess = data[0];
            vm.current = pathtogo;
            vm.areDir = data[1];
          }
        })
        //window.location.href = route('Files.CustomPath', {PathToGo: pathtogo});

      }
    }
  }
</script>

<template>
  <div style="height: 100%; width: 100%; display: flex; flex-direction: column;">
    <MyHeader :logged="this.logged"/>
    <main style="display: flex; flex-direction: column; height: 100%; border: 1px solid white;">
      <h1 style="margin: auto; margin-top: auto; margin-bottom: auto;">Your Files</h1>
      <div style="max-width: 80%; max-height: 90%; width:100%; height: 100%; margin: auto; margin-top: 0px; border: 1px solid black;">
        <table style="margin: 0px; width: 100%;">
          <thead style="border: 1px solid black; width: 100%;">
            <th>
              <p id="currentPath">{{ current }}</p>
            </th>
          </thead>
          <tbody v-for="path in Filess">
            <tr>
              <td>
                <a @click="clickedPath(path)">{{path}}</a>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </main>
  </div>
</template>

<!-- need to make a call to a new api that sends the current path and where we want to go, the api will just append the path to go to the current path-->