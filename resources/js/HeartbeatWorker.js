export function heartbeat(){
    console.log("heartbeat");
    httpGET("Heartbeat");
}


export function httpGET(theUrl)
{
    var xmlHttp = new XMLHttpRequest();
    xmlHttp.open( "GET", theUrl, true );
    xmlHttp.send( null );
    return 1;
}