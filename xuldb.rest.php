<?php

// rest backend for xuldb

/* intentionally procedural, for PHP4 */

require_once("./xuldb.inc");

session_start();

/*
  * main entry point for this
  * before doing anything interesting, we should make
*/

// POST?? how to post from xul...

if($_GET['op']) {
    if($_GET['op'] == "login") {
        $cfg = array(
            "uname" => $_GET['uname'],
            "passwd" => $_GET['passwd'],
            "host" => $_GET['host'],
            "db" => $_GET['db'],
        );
        $xdb = new xuldb($cfg);
        if($xdb->stat = 'logged_in') {
            $_SESSION['cfg'] = $cfg;
            $_SESSION['logged_in'] = true;
            echo _myjson_encode(array(1, "Login Succeeded!", $_SERVER['HTTP_COOKIE']));
        } else {
            session_destroy();
            echo "[0, 'login Failed: " + $xdb->stat + "']";
            exit;
        }
    } else if($_GET['op'] == "debug") {
        echo _dump($_SESSION);
    //} else if($_SESSION['logged_in']) { // need to test before this

    } else if($_GET['op'] == "logout") {
        session_destroy();
        echo _myjson_encode(array(1, "logged out"));
    } else {
        $xdb = new xuldb($_SESSION['cfg']);
        echo $_SESSION;

        switch($_GET['op']) {
            case "databases":
                $tree = $xdb->tree();
                echo _myjson_encode($tree);
                break;
            case "tables":
                echo _myjson_encode($x = $xdb->tables($_GET['db']));
                break;

            case "cols":
                echo _myjson_encode($x = $xdb->columns($_GET['table']));
                break;

            case "desc":
                //echo _dump($x = $xdb->describe($_GET['table']));
                echo _myjson_encode($x = $xdb->describe($_GET['table']));
                break;
        }
    /*} else {
        echo "foo!";
        // emulate 404 with header() ??*/
    }

}

//--

?>
