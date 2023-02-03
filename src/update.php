<?php
error_reporting(0);

include("./utils.php");

$data = json_decode(file_get_contents("php://input"));

if(is_null($data)){
    die(json_encode(array("success"=>false, "reason"=>"Data can not be null.")));
}

$data = base64_encode(json_encode($data));
if(strlen($data)>=24*1024){
    die(json_encode(array("success"=>false, "reason"=>"Data too long.")));
}

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
$timestamp = strtotime("now");

$sql = 'insert into `manghui_query_datas`(`serverid`,`data`,`updatetime`) VALUES("manghui_first","'.$data.'","'.$timestamp.'") ON DUPLICATE KEY UPDATE `data`="'.$data.'",`updatetime`='.$timestamp;
$conn->query($sql);

die(json_encode(array("success"=>true)));