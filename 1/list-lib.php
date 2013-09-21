<?php
include_once "db.php";
function getList($lastid_sql, $uid_sql, $count) {
	$sql = "SELECT r.*, u.user FROM `q_resources` r LEFT JOIN `q_user` u ON u.uid=r.uid WHERE $lastid_sql $uid_sql ORDER BY `upload_time` DESC LIMIT 0, $count";
	$data = fetchSql($sql);
    if (count($data) <= 0) return $data;
    foreach($data as $k => $d) {
        $data[$k]['url'] = "http://{$d['bucket']}.u.qiniudn.com/{$d['rkey']}";
        $data[$k]['date'] = strftime('%Y-%m-%d %H:%M:%S', $d['upload_time']);
    }
	return $data;
}