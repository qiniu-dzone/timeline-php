<?php
include "list-lib.php";

$action = $_GET['action'];
if ($action == "qiniu") {
    $uid = intval($_POST['uid']);
    $match = fetchSql("SELECT pswd FROM `q_user` WHERE uid=$uid LIMIT 0, 1");
    if (!$match) replyError(200, 400, "invalid uid, ".json_encode($_POST));
    $pswd = $match[0]['pswd'];
    $bucket = $_POST['bucket'];
    $key = $_POST['key'];
	$bucket = escape($bucket);
    $key = escape($key);
    $desc = escape($_POST['desc']);
    $time = time();
    $mime = escape($_POST['mime']);
    $match = fetchSql("SELECT * FROM `q_resources` WHERE bucket='$bucket' and rkey='$key' and uid=$uid LIMIT 0, 1");
    if ($match) replyError(200, 400, "duplicate resource");
    runSql("INSERT INTO `q_resources` (`bucket`, `rkey`, `upload_time`, `uid`, `desc`, `mime`) VALUES ('$bucket', '$key', '$time', $uid, '$desc', '$mime')");
    $data = getList("`rid`=".lastInsertId(), "", 1);
    reply($data[0]);
}

if ($action == "token") {
    include "upload-lib.php";
    $info = mustGetAuthorizationInfo();
    $timeline_policy->EndUser = $info['uid'];
    reply($timeline_policy->Token(null));
}

$callret = $_GET['callback_ret'];
if (!$callret) {
    //error
    $data = json_decode($_GET['error']);
    $info = json_decode($data->error);
    replyError($_GET['code'], $data);
}
header("Content-Type: application/json");
echo base64_decode($callret);