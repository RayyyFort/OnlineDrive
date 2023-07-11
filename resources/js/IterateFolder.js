export async function IterateFolder(folder,path){
    var dataToSend = new FormData();
    for await (const [key, value] of folder.entries()){
        if (value.kind === "directory"){
            IterateFolder(value,path+key+"\\");
        }
        else{
            console.log({key, value});
            postFile(value,path)
        }
    }
}

async function postFile(File,path){
    var dataToSend = new FormData();
    dataToSend.append(File.name,await File.getFile());
    dataToSend.append("path",path);
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