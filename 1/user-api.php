<?php
include "db.php";

function register($user, $pswd) {
    if (!$user || !$pswd) replyError("miss user or pswd");
    $user = escape($user);
    $pswd = md5($pswd);

    // check if exists
    $match = fetchSql("SELECT * FROM q_user WHERE user='$user' LIMIT 0, 1");
    if ($match) replyError("user already exists");
    runSql("INSERT INTO `q_user` (`user`, `pswd`) VALUES ('$user', '$pswd')");
    $match = fetchSql("SELECT * FROM q_user WHERE user='$user' and `pswd`='$pswd' LIMIT 0, 1");
    if (!$match) replyError("server error");
    reply($match[0]);
}

function login($user, $pswd) {
    if (!$user || !$pswd) replyError("miss user or pswd");
    $user = escape($user);
    $pswd = md5($pswd);
    $match = fetchSql("SELECT * FROM q_user WHERE user='$user' and pswd='$pswd' LIMIT 0, 1");
    if (!$match) replyError("invalid username or password");
    reply($match[0]);
}

$action = $_GET['action'];
if ($action == "register") register($_GET['user'], $_GET['pswd']);
if ($action == "login") login($_GET['user'], $_GET['pswd']);
echo $action;