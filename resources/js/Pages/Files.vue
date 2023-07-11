<script>
  import MyHeader from "../Layouts/header.vue";
  import {heartbeat, httpGET} from "../HeartbeatWorker.js";
  import {IterateFolder} from "../IterateFolder";

  export default{
    mounted() {
      heartbeat();
      setInterval(heartbeat, 300000);
    },
    props:{
      logged:Boolean,
      FilesArr:Array,
      CurrentFolder:String,
      dirArr:Array,
    },
    components: {
      MyHeader
    },
    data() {
      return{
        current: this.CurrentFolder,
        Files: this.FilesArr,
        Directories: this.dirArr,
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
        $.ajax({
          type: "get",
          url: route('Files.CustomPath', {PathToGo: pathtogo}),
          data: $(this).serialize(),
          dataType: 'json',
          success: async function(data) {
            vm.Files = await data[0];
            vm.current = pathtogo;
            vm.Directories = data[1];
            vm.isNotRoot;
          }
        });
      },
      dropfile(event, path){
        event.preventDefault();
        var dataToSend = new FormData();
        [...event.dataTransfer.items].forEach(async file => {
          var tempFile;
          await file.getAsFileSystemHandle().then(PromiseResult => {tempFile = PromiseResult;});
          console.log(tempFile);
          if (tempFile.kind === "directory"){
            IterateFolder(tempFile,this.current.replace("Root","") + ((path === null)?"":path+"\\")+tempFile.name+"\\")
          }
          else{
            dataToSend.append(tempFile.name,await tempFile.getFile());
            dataToSend.append("path",this.current.replace("Root","") + "\\" + path);
            $.ajax({
              type: "post",
              headers:{
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                      },
              url: route('fileChanged'),
              processData: false,
              contentType: false,
              data: dataToSend,
              success: function (data){
              },
              error: function (xhr, status, error) {
                var err = eval("(" + xhr.responseText + ")");
                alert(err.Message);
              }
            })
            dataToSend = new FormData();
          }
        });
      },
      dOver(event){
        event.preventDefault();
      },
      CreateNew(type){
        console.log(type);
      }
    },
    computed: {
      isNotRoot: function () {
        return this.current != 'Root';
      }
    }
  }
</script>

<template>
  <div style="height: 100%; width: 100%; display: flex; flex-direction: column;">
    <MyHeader :logged="this.logged"/>
    <main style="display: flex; flex-direction: column; height: 100%; border: 1px solid white;">
      <div style="display: flex; flex-direction: row; max-width: 80%; width: 100%; margin-left: auto;margin-right: auto; margin-top: 10px; margin-bottom: 10px;">
        <div class="dropdown">
          <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            New
          </button>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="#">Folder</a></li>
            <li><a class="dropdown-item" href="#">Txt</a></li>
          </ul>
        </div>
        <h1 style="margin:auto;">Your Files</h1>
      </div>
      <div style="max-width: 80%; max-height: 90%; width:100%; height: 100%; margin: auto; margin-top: 0px; border: 1px solid black;" @dragover="dOver($event)" @drop="dropfile($event,'')">
        <table style="margin: 0px; width: 100%;">
          <thead style="border: 1px solid black; width: 100%;">
            <th>
              <p id="currentPath">{{ current }}</p>
            </th>
          </thead>
          <tbody>
            <tr v-if="isNotRoot">
              <td>
                <a @click="clickedPath('..')">..</a>
              </td>
            </tr>
            <tr v-for="path in Directories" @dragover="dOver($event)" @drop="dropfile($event, path)">
              <td>
                <a @click="clickedPath(path)">{{path}}</a>
              </td>
            </tr>
            <tr v-for="path in Files">
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