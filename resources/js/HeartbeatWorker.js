export function heartbeat(){
    httpGET(route("Heartbeat"));
}


export function httpGET(theUrl)
{
    var xmlHttp = new XMLHttpRequest();
    xmlHttp.open( "GET", theUrl, true );
    xmlHttp.send( null );
    return xmlHttp.response;
}