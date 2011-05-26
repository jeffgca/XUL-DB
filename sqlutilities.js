function sql(sqlCommand,onReady)
{
    var url = "/php/sql/sql.php";
    httpPost(url,sqlCommand,onReady);
}

function sqlbrowse(sqlCommand)
{
    var url = "/sqlmanager/sqlviewer.php?query="+sqlCommand;
    window.open(url, "", "chrome,resizable,width=760,height=400");
}
