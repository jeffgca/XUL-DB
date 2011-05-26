<?php

// db hackin'

require_once 'DB.php';
$user = 'root';
$pass = 'digw33d';
$host = 'robot';
$db_name = 'drupal';

$dsn = "mysql://$user:$pass@$host/$db_name";

$db = DB::connect($dsn);

if (DB::isError($db)) {
        die ($db->getMessage());
}

$result = $db->query("show databases");

while ($row = $result->fetchRow()) {
    echo print_r($row, 1)."\n";
}

$result->free();

$db->disconnect();

?>
