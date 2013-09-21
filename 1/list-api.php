<?php
include "list-lib.php";

$uid = intval($_GET['uid']);
$uid_sql = $uid > 0 ? "and uid='$uid'" : '';
$info = getAuthorizationInfo();
$lastid = intval($_GET['lastid']);
if (!$lastid || $lastid <= 0) $lastid = '1=1';
else $lastid = "`rid`<$lastid";
$count = intval($_GET['count']);
if (!$count) $count = 10;

$all_count = getVar("SELECT COUNT(*) FROM `q_resources` WHERE $lastid");
$data = getList($lastid, $uid_sql, $count);

reply($data, array(count => $all_count));