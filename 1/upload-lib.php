<?php
include "qiniu.php";

$bucket = "timeline";
$timeline_policy = new Qiniu_RS_PutPolicy($bucket);
$timeline_policy->CallbackUrl = "http://qiniutimeline.sinaapp.com/upload-api.php?action=qiniu";
$timeline_policy->CallbackBody = "bucket=$(bucket)&uid=$(endUser)&key=$(key)&desc=$(x:desc)&mime=$(mimeType)";
