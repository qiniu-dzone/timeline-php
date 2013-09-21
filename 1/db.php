<?php
$db = new SaeMysql();

function runSql($sql, $debug=false) {
    global $db;
    $db->runSql($sql);
    if ($db->errno() != 0) replyError($db->errmsg());
    if ($debug) echo $sql;
}

function fetchSql($sql, $debug=false) {
    global $db;
    $data = $db->getData($sql);
    if ($db->errno() != 0) replyError($db->errmsg());
    if ($debug) echo $sql;
    return $data;
}

function getVar($sql) {
    global $db;
    $data = $db->getVar($sql);
    if ($db->errno() != 0) replyError($db->errmsg());
    return $data;
}

function lastInsertId() {
    global $db;
    return $db->lastId();
}

function escape($data) {
	global $db;
    return $db->escape($data);
}

function finish($data) {
    global $db;
    $db->closeDb();
    die($data);
}

function getAuthorizationInfo() {
    $user = $_GET['user'];
    $pswd = $_GET['pswd'];
    if (!$user || !$pswd) replyError(401, "authorization required!");
    $user = escape($user);
    $pswd = md5($pswd);
    $data = fetchSql("SELECT * FROM q_user WHERE user='$user' and pswd='$pswd' LIMIT 0, 1");
    if (count($data) == 0) return null;
    return $data[0];
}

function mustGetAuthorizationInfo() {
	$info = getAuthorizationInfo();
    if (!$info) replyError(400, "invalid authorization!");
    return $info;
}

function reply($data, $ex=array()) {
    $data = array (
        code => 200,
        data => $data,
    );
    foreach($ex as $k => $d) {
        $data[$k] = $d;
    }
    header("Content-Type: application/json");
    finish(json_encode($data));
}


function replyError($code, $ret_code="", $info="") {
    global $db;
    if (!$ret_code) {
        $info = $code;
        $code = 400;
        $ret_code = 400;
    }
    if (!$info) {
        $info = $ret_code;
        $ret_code = $code;
    }
    header("HTTP/1.1 $code Bad Request");
    header("Content-Type: application/json");
    $data = array (
        code => $ret_code,
        data => $info,
    );
    finish(json_encode($data));
}