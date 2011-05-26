//---------------------------------------------------------------------
// httpGet(url,callback);
// httpPost(url,request,callback);
// httpFormPost(url,request,callback);
// httpLastModified(url,callback)
//---------------------------------------------------------------------
function httpGet(url,callback)
{
    var http = new XMLHttpRequest();
    http.open("GET", url, true);
    http.onreadystatechange=function(){if(http.readyState==4){callback(http.responseText);}};
    http.send(null);
}
//---------------------------------------------------------------------
function httpPost(url,request,callback)
{
    var http = new XMLHttpRequest();
    http.open("POST", url, true);
    http.onreadystatechange=function(){if(http.readyState==4){callback(http.responseText);}};
    http.send(request);
}
//---------------------------------------------------------------------
function httpFormPost(url,request,callback)
{
    var http = new XMLHttpRequest();
    http.open("POST", url, true);
    http.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
    http.onreadystatechange=function(){if(http.readyState==4){callback(http.responseText);}};
    http.send(request);
}
//---------------------------------------------------------------------
// sample on how to get last modified date
// good for checking RSS feeds
// url = "http://geekswithblogs.net/rebelgeekz/rss.xml"
//---------------------------------------------------------------------
function httpLastModified(url,callback)
{
    var http = new XMLHttpRequest();
    http.open("HEAD", url, true);
    http.onreadystatechange=function(){if(http.readyState==4){callback(http.getResponseHeader("Last-Modified"));}};
    http.send(null);
}
//---------------------------------------------------------------------
// End of file
//---------------------------------------------------------------------
