<?php

// xuldb.inc

/*
    * common php functions for xuldb, mostly login / session stuff
    * storage of session stuff? will use $_SESSION for now
*/

require_once 'DB.php'; // pear::DB

## DB Stuff ( using pearDB )

class xuldb {

    public $stat = "";

    public function __construct($cfg) {
        $this->login($cfg);
    }

    public function login($cfg) {
        $this->dsn = "mysql://".$cfg['uname'].":".$cfg['passwd']."@".$cfg['host']."/".$cfg['db'];
        $r = $this->_db_connect();
    }

    private function _db_connect() {
        //echo $this->dsn;
        $this->db = DB::connect($this->dsn);
        if (DB::isError($this->db)) {
            //session_destroy();
            $this->stat = $this->db->getMessage();
            return false;
        } else {
            $this->stat = "Logged In";
            return true;
        }
    }

    public function tree() {
        //echo _dump($this);
        $sql = "show databases";
        $result = $this->db->query($sql);
        if (DB::isError($this->db)) {
            $this->stat = $this->db->getMessage();
            $out = false;
        } else {
            $out = array();
            while ($row = $result->fetchRow()) {
                //echo _dump($row);
                $out[$row[0]] = $this->tables($row[0]);
            }
        }
        return $out;
    }

    public function databases() {
        $sql = "show databases";
        $result = $this->db->query($sql);
        if (DB::isError($this->db)) {
            $this->stat = $this->db->getMessage();
            $out = false;
        } else {
            $out = array();
            while ($row = $result->fetchRow()) {
                //echo _dump($row);
                $out[] = $row;
            }
        }
        return $out;
    }

    public function tables($db) {
        if($db !== $this->cfg['db']) {
            $this->_switch_db($db);
        }
        $rs = $this->db->query("USE $db; SHOW TABLES");
        if(!DB::isError($this->db)) {
            while ($row = $rs->fetchRow()) {
                $out[] = $row[0];
            }
        } else {
            $this->stat = $this->db->getMessage();
            $out = false;
        }
        return $out;
    }

    public function columns($table) {
        $sql = "DESCRIBE $table";
        $rs = $this->db->query($sql);
        if(!DB::isError($this->db)) {
            while ($row = $rs->fetchRow()) {
                $out[] = $row[0];
            }
        } else {
            $this->stat = $this->db->getMessage();
            $out = false;
        }
        return $out;
    }

    public function describe($table) {
        $sql = "DESCRIBE $table";
        $rs = $this->db->query($sql);
        if(!DB::isError($this->db)) {
            while ($row = $rs->fetchRow(DB_FETCHMODE_ASSOC)) {
                $out[] = (object) $row;
            }
        } else {
            $this->stat = $this->db->getMessage();
            $out = false;
        }
        return $out;
    }

    public function query($sql, $mode=false) {
        // timeout? in the ui
        // paging? eventually
    }

    private function _switch_db($db) {
        $this->cfg['db'] = $db;
        $_SESSION['cfg']['db'] = $db;
        //echo _dump($this);
        $this->db = $this->login($this->cfg);
        if(!DB::isError($this->db)) {

        }
    }

}

## dumper

function _dump($data) { // pass in $_GET, etc
    $args = func_get_args();
    if(count($args) > 1) {
        return "\n<pre>\n".print_r((object) $args, 1)."\n</pre>\n";
    } else {
        return "\n<pre>\n".print_r((object) $data, 1)."\n</pre>\n";
    }
}

## JSON

function _myjson_encode($var) {
  switch (gettype($var)) {
    case 'boolean':
    case 'integer':
    case 'double':
      return $var;
    case 'resource':
    case 'string':
      return '"'. str_replace(array("\r", "\n"), array('\r', '\n'), addslashes($var)) .'"';
    case 'array':
      $output = array();
      if(!(is_array($var) && array_keys($var)!==range(0,sizeof($var)-1))) {
        foreach($var as $v) {
          $output[] = _myjson_encode($v);
        }
        return '[ ' . implode(', ', $output)." ]";
      }
    case 'object':
      $output = array();
      foreach ($var as $k => $v) {
        $output[] = $k .': '. _myjson_encode($v);
      }
      return '{ '. implode(', ', $output) .' }';
    default:
      return 'null';
  }
}

?>
