<?php

/*
    *   xuldb:  a remote xul / php / ajax application for querying databases.
    *
    *   based initially almost entirely on George Nava's SqlManager:
    *   http://www.georgenava.com/applauncher.php
    *
    *   devnotes 16.3.2006: created basic file layout, stole a bunch of stuff
    *   from george ( the images I may need to replace )
    *
    *   todo:
    *    - user auth / session management. store valid session key where?
    *    - implement rest backend:
    *        - login / auth token
    *        - databases -> tables -? columns
    *        - add-hoc sql queries ( syntax highlight? ew. )
    *        - async busy / progress functionality for heavy queries... argh.
    *
*/

session_start();

require_once("xuldb.inc");

header( "Content-type: application/vnd.mozilla.xul+xml" );

echo '<?xml version="1.0"?>';
echo '<?xml-stylesheet href="chrome://global/skin" type="text/css"?>';
echo '<?xml-stylesheet href="xuldb.css" type="text/css"?>';

if ($_SESSION['logged_in']) {
    include("mainwin.xul");
} else {
    include("login.xul");
}


?>
