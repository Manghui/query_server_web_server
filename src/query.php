<?php
error_reporting(0);

include("./utils.php");

$serverId = @$_GET["id"];
$serverKey = @$_GET["key"];

if (!preg_match('/^[A-Za-z0-9_]+$/', $serverId)) {
    die(json_encode(array("success"=>false, "reason"=>"ServerID input error.")));
}
$conn = createMysqlConnection();
$result = $conn->query("select `key` from " . db_prefix . "query_users where `id` = '{$serverId}'");
if($result->num_rows <= 0){
    die(json_encode(array("success"=>false, "reason"=>"Server ID does not exist.")));
}
if(substr(md5(mysqli_fetch_array($result)["key"].mh_salt),8,16) != $serverKey){
    die(json_encode(array("success"=>false, "reason"=>"Server Key mismatch.")));
}
$result = mysqli_fetch_array($conn->query("select `data`,`updatetime` from " . db_prefix . "query_datas where `serverid` = '{$serverId}'"));

$data = json_decode(base64_decode($result["data"]), true);
if(is_null($data)){
    die(json_encode(array("success"=>false, "reason"=>"Cannot get data from this server ID.")));
}
$data["updatetime"] = (int)$result["updatetime"];
$data["success"] = true;

exit (json_encode($data));
