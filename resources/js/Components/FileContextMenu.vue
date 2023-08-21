<script>
    export default{
        props:{
            fileName:String
        },
        methods:{
            Delete(){
                var toSend = new FormData();
                toSend.append("path", $('#currentPath').text()+"\\"+$("#fileNameInput").val());
                $.ajax({
                  type: "post",
                  headers:{
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                          },
                  url: route('Delete'),
                  processData: false,
                  contentType: false,
                  data: toSend,
                  error: function (xhr, status, error) {
                    var err = eval("(" + xhr.responseText + ")");
                    alert(err.Message);
                  }
                })
            },
            async Download(data, filename, type){
              var file = new Blob([await data], {type: type});
              if (window.navigator.msSaveOrOpenBlob) // IE10+
                  window.navigator.msSaveOrOpenBlob(file, filename);
              else { // Others
                  var a = document.createElement("a"),
                          url = URL.createObjectURL(file);
                  a.href = url;
                  a.download = filename;
                  document.body.appendChild(a);
                  a.click();
                  setTimeout(function() {
                      document.body.removeChild(a);
                      window.URL.revokeObjectURL(url);  
                  }, 0); 
              }
            },
            DownloadClick(){
                var toSend = new FormData();
                var vm = this;
                toSend.append("path", $('#currentPath').text()+"\\"+$("#fileNameInput").val());
                $.ajax({
                  type: "post",
                  headers:{
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                          },
                  url: route('DownloadFile'),
                  processData: false,
                  contentType: false,
                  data: toSend,
                  xhrFields: {
                    responseType: 'blob' // to avoid binary data being mangled on charset conversion
                  },
                  success: async function(data, textStatus, request){
                    console.log(await data);
                    vm.Download(await data, await $("#fileNameInput").val(), await request.getResponseHeader('content-type'));
                  },
                  error: function (xhr, status, error) {
                    var err = eval("(" + xhr.responseText + ")");
                    alert(err.Message);
                  }
                })
            }
        }
    }
</script>

<template>
    <div style="border: 1px solid black;position: absolute;background-color: black;border: 1px solid white;">
        <input type="hidden" id="fileNameInput">
        <a href="javascript:void(0)" @click="Delete()"><p style="margin-left: 5px;margin-right: 5px;">Delete</p></a>
        <a href="javascript:void(0)" @click="DownloadClick()"><p style="margin-left: 5px;margin-right: 5px;">Download</p></a>
    </div>
</template>
