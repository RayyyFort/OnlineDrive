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
            Download(){
                var toSend = new FormData();
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
                  success: async function(data){
                    console.log(await data);
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
        <a href="javascript:void(0)" @click="Download()"><p style="margin-left: 5px;margin-right: 5px;">Download</p></a>
    </div>
</template>
