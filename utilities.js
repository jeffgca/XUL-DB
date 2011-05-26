function Id(element) {
    return document.getElementById(element);
}

function alltrim(anystring) {
    return anystring.replace(/^\s*|\s*$/g,"");
}

function getValue(anyControl) {
    var control = document.getElementById(anyControl);
    if(control) {return control.value;} else {return null;}
}

function setValue(anyControl,anyValue) {
    var control = document.getElementById(anyControl);
    if(control) {control.value = anyValue;}
}

function treeClear(treeName) {
    var tree = document.getElementById(treeName);
    while (tree.hasChildNodes()) {

        tree.removeChild(tree.lastChild);

    }
}

function newTreeNode(fields,icon) {
    var onlyXUL   = "http://www.mozilla.org/keymaster/gatekeeper/there.is.only.xul";
    var treeItem  = document.createElementNS(onlyXUL,"treeitem");
    var treeRow   = document.createElementNS(onlyXUL,"treerow");
    var count     = fields.length;
    var cells     = new Array(count);

    for(i=0;i<count;i++) {
        cells[i] = document.createElementNS(onlyXUL,"treecell");
        cells[i].setAttribute("label"," "+fields[i]);
    }

    if(icon!="") {
        cells[0].setAttribute("src",icon);
    }

    treeItem.setAttribute("container","true");
    for(i=0;i<count;i++) {
        treeRow.appendChild(cells[i]);
    }

    treeItem.appendChild(treeRow);
    return treeItem;
}

// text = merge("name is {0} and age is {1}",name,age)
function merge(str,args) {
    for(i=0;i<arguments.length;i++){
        str = str.replace(eval("/{"+i+"}/g"),arguments[i]);
    }
    return str;
}

// n = getRandom(1,10)
function getRandom(min, max) {
    if (typeof min == "undefined") { min = 0; }
    if (typeof max == "undefined") { max = 1; }

    return Math.floor(Math.random() * (max - min + 1)) + min;
}

// keys = getKeys({name:'jim',age:12})
function getKeys(obj,sort) {
    var list = new Array();
    for(var item in obj) list.push(item);
    if(sort) return list.sort();
    return list;
}
