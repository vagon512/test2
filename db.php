<?php
include_once "include/functions.php";

$connect = "mysql:host=".DB_HOST.";dbname=".DB_NAME;

try {
    $db = new PDO($connect, DB_USER, DB_PASS);

    }
catch (PDOException $e) {
  print "Error!: " . $e->getMessage();
  die();
}

?>
