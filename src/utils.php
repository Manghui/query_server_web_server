<?php
error_reporting(0);

include_once("./config.php");

function createMysqlConnection() {
    $conn = new mysqli(db_host, db_username, db_password, db_name);
    if ($conn -> connect_error) {
        die(json_encode(array("success"=>false, "reason"=>"failed to connect db server.")));
    }
    return $conn;
}