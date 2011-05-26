var isDebugging = false;
var uri = "http://apple/xuldb/xuldb.rest.php?";
var sessId = "";
var host = "robot";
var db = "mysql";
var sessId = "";

function init() {
	loadDatabases();
}

/* login / out functions */

function logIn() {
	u = document.getElementById("uname");
	p = document.getElementById("passwd");
	xuri = uri +="op=login&uname="+u.value+"&passwd="+p.value+"&host="+host+"&db="+db;
	webMethod(xuri, null, logInProc);
}

function logInProc(stream) {
	eval("var data = " + stream);
	sessId = data[2];
	//document.getElementById("sessId").value = sessId;
	var newURI = window.location.href + "?" + sessId
	window.location.href = newURI;
}

function logOut() {
	xuri = uri + "op=logout";
	webMethod(xuri, null, logOutProc);
}

function logOutProc() {
	window.location.href = window.location.href;
}

/* datamodel request handling functions */

function loadDatabases() {
    webMethod(uri + "op=databases",null,showDatabases);
}

function showDatabases(stream) {
    setValue('sqleditor',stream);

	var dbs = eval(stream);
	var icon = "graphics/connected.bmp";
	var tree = Id("dbChildren");
	alert(dbs);
	//treeClear("treeView");

	/*for(db in dbs) {
		node = newTreeNode(dbs[db],icon);
		//alert(getKeys(node.childNodes));
		tree.appendChild(node);
	}*/

}

function loadTables() {
    webMethod(uri + "op=tables",null,showTables);
}

function showTables(stream) {
    setValue('sqleditor',stream);
    var tables = eval(stream);
    for(var i in tables) {
        alert(tables[i]);
    }
}

function loadFields(table) {
    if(!table) return;
    webMethod(uri + "op=fields","table="+table,showFields);
}

function showFields(stream) {
    setValue('sqleditor',stream);
    var fields = eval(stream);
    for(var i in fields)
    {
        alert(fields[i][0]);
    }
}

function showColumns(stream) {
	if(isDebugging){setValue('sqleditor',stream);}

	var fields = eval(stream);
	var icon   = "graphics/field.bmp";
	var tree   = Id("treeFields");
	var rows   = fields.rows;

	treeClear("treeFields");
	for(var i in rows) {
		node = newTreeNode(rows[i],icon);
		tree.appendChild(node);
	}
}

function showError(stream) {
	alert('error:'+stream);
}

function execute() {
	var sqlcommand = getValue("sqleditor");
	var url = uri + "op=query";
}

function tableFields() {
	var tree = Id("treeView");
	var selection = tree.contentView.getItemAtIndex(tree.currentIndex);
	var pair  = selection.id.split("_");
	var table = alltrim(pair[1].toLowerCase());
	if(pair[0]=="table" || pair[0]=="view") {
		setValue('labelTitle',pair[0]+": "+pair[1]);
//		alert(selection.isLoaded);
		selection.isLoaded = true;
		sql('show columns from '+table,showColumns);
	}
}

function tableBrowse() {
	var tree = Id("treeView");
	var selection = tree.contentView.getItemAtIndex(tree.currentIndex);
	var pair  = selection.id.split("_");
	var table = alltrim(pair[1].toLowerCase());
	if(pair[0]=="table") {
		var sqlcommand = "Select * from " + table;
		var url = "/sqlmanager/sqlviewer.php?query="+sqlcommand;
		window.open(url, "", "chrome,resizable");
	} else {
		alert("Only tables can be browsed!");
	}
}

function clearEditor() {
	setValue('sqleditor','');
}

function webMethod(url,request,callback,target) {
    var http = new XMLHttpRequest();
    if(!request) {
		mode="GET";
	} else {
		mode="POST";
	}
    http.open(mode,url,true);

    if(mode=="POST") {
		http.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	}
    http.onreadystatechange=function() {
		if(http.readyState==4) {
			callback(http.responseText,target);
		}
	};
    http.send(request);
}

/*--------------------------------------------------------------------------------------------------*/


