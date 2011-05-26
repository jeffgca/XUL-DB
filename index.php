<?php
header( "Content-type: application/vnd.mozilla.xul+xml" );

echo '<?xml version="1.0"?>';
echo '<?xml-stylesheet href="chrome://global/skin" type="text/css"?>';
echo '<?xml-stylesheet href="xuldb.css" type="text/css"?>';

?>

<window title="SQL Manager" width="800" height="600" onload="init();"
    xmlns:html="http://www.w3.org/1999/xhtml"
    xmlns="http://www.mozilla.org/keymaster/gatekeeper/there.is.only.xul">

    <script type="application/x-javascript" src="xuldb.js"/>
    <script type="application/x-javascript" src="utilities.js"/>
    <script type="application/x-javascript" src="sqlutilities.js"/>
    <script type="application/x-javascript" src="httprequest.js"/>

    <hbox flex="1">
        <vbox width="200">
            <toolbar>
                <toolbarbutton id="tb01" label="New"  />
                <toolbarseparator/>
                <toolbarbutton id="tb02" label="Edit" />
                <toolbarseparator/>
                <toolbarbutton id="tb03" label="View" oncommand="tableBrowse();"/>
            </toolbar>

            <tree id="treeView" flex="1" hidecolumnpicker="true" onselect="tableFields();" ondblclick="tableFields();">
                <treecols>
                    <treecol id="treeDatabases" flex="1" label="Databases" primary="true" />
                </treecols>
                <treechildren>
                    <treeitem container="true" open="true">
                        <treerow><treecell class="dataconn" label="Personal" src="graphics/connected.bmp"/></treerow>
                        <treechildren>
                            <treeitem container="true" open="true">
                                <treerow><treecell class="datatables" label=" Tables" src="graphics/tables.bmp"/>    </treerow>
                                <treechildren>
                                    <treeitem id="table_blogs"    ><treerow><treecell class="datatable" label=" Blogs"     src="graphics/table.bmp"/></treerow></treeitem>
                                    <treeitem id="table_blogroll" ><treerow><treecell class="datatable" label=" Blogroll"  src="graphics/table.bmp"/></treerow></treeitem>
                                    <treeitem id="table_customers"><treerow><treecell class="datatable" label=" Customers" src="graphics/table.bmp"/></treerow></treeitem>
                                    <treeitem id="table_invoices" ><treerow><treecell class="datatable" label=" Invoices"  src="graphics/table.bmp"/></treerow></treeitem>
                                </treechildren>
                            </treeitem>
                            <treeitem><treerow><treecell class="dataviews" label=" Views"      src="graphics/views.bmp"/>     </treerow></treeitem>
                            <treeitem><treerow><treecell class="dataprocs" label=" Procedures" src="graphics/procedures.bmp"/></treerow></treeitem>
                            <treeitem><treerow><treecell class="datafuncs" label=" Functions"  src="graphics/functions.bmp"/> </treerow></treeitem>
                            <treeitem><treerow><treecell class="datatrigs" label=" Triggers"   src="graphics/functions.bmp"/> </treerow></treeitem>
                        </treechildren>
                    </treeitem>
                    <treeitem container="true" open="true">
                        <treerow><treecell class="datadisc" label=" Accounting" src="graphics/disconnected.bmp"/></treerow>
                        <treechildren>
                            <treeitem container="true">
                                <treerow><treecell class="datatables" label=" Tables" src="graphics/tables.bmp"/></treerow>
                                <treechildren>
                                    <treeitem><treerow><treecell class="datatable" label=" Clients"    src="graphics/table.bmp"/></treerow></treeitem>
                                    <treeitem><treerow><treecell class="datatable" label=" Vendors"    src="graphics/table.bmp"/></treerow></treeitem>
                                    <treeitem><treerow><treecell class="datatable" label=" Warehouses" src="graphics/table.bmp"/></treerow></treeitem>
                                    <treeitem><treerow><treecell class="datatable" label=" Products"   src="graphics/table.bmp"/></treerow></treeitem>
                                </treechildren>
                            </treeitem>
                            <treeitem container="true">
                                <treerow><treecell class="dataviews" label=" Views" src="graphics/views.bmp"/></treerow>
                                <treechildren>
                                    <treeitem><treerow><treecell class="dataview" label=" ClientById"    src="graphics/view.bmp"/></treerow></treeitem>
                                    <treeitem><treerow><treecell class="dataview" label=" VendorById"    src="graphics/view.bmp"/></treerow></treeitem>
                                    <treeitem><treerow><treecell class="dataview" label=" WarehouseById" src="graphics/view.bmp"/></treerow></treeitem>
                                    <treeitem><treerow><treecell class="dataview" label=" ProductById"   src="graphics/view.bmp"/></treerow></treeitem>
                                </treechildren>
                            </treeitem>
                            <treeitem><treerow><treecell class="dataprocs" label=" Procedures" src="graphics/procedures.bmp"/></treerow></treeitem>
                            <treeitem><treerow><treecell class="datafuncs" label=" Functions"  src="graphics/functions.bmp"/></treerow></treeitem>
                            <treeitem><treerow><treecell class="datatrigs" label=" Triggers"   src="graphics/functions.bmp"/></treerow></treeitem>
                        </treechildren>
                    </treeitem>
                </treechildren>
            </tree>
        </vbox>

        <vbox flex="1">
            <hbox height="4"></hbox>
            <hbox height="20">
                <label id="labelTitle" style="font-family:verdana; font-size:1em; font-weight:bold;">Table - Clients</label>
            </hbox>
            <vbox flex="2" style="overflow: auto">
                <tree id="treeStructure" flex="1" hidecolumnpicker="true" seltype="single" >
                    <treecols>
                        <treecol id="colName" flex="4" label="Field Name" />
                        <treecol id="colType" flex="2" label="Type" />
                        <treecol id="colNull" flex="1" label="Null" />
                        <treecol id="colKeys" flex="1" label="Keys" />
                        <treecol id="colDefs" flex="2" label="Default" />
                        <treecol id="colMore" flex="2" label="Extra" />
                    </treecols>
                    <treechildren id="treeFields">
                    </treechildren>
                </tree>
            </vbox>
            <splitter><grippy/></splitter>
            <vbox flex="1" style="overflow: auto">
                <toolbar>
                    <toolbarbutton id="tb04" label="New"  oncommand="clearEditor();"/>
                    <toolbarseparator/>
                    <toolbarbutton id="tb05" label="Open" />
                    <toolbarseparator/>
                    <toolbarbutton id="tb06" label="Save" />
                    <toolbarseparator/>
                    <toolbarbutton id="tb07" label="Run"  oncommand="execute();"/>
                </toolbar>
                <textbox id="sqleditor" multiline="true" flex="1" value="Select * From customers" style="font-family:courier new;font-size:1.2em;"/>
            </vbox>
        </vbox>
    </hbox>
</window>

<!--<?php include "/scripts/visitor.php" ?>-->
