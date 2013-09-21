<?php
include "db.php";
include "upload-lib.php";

$info = mustGetAuthorizationInfo();
$timeline_policy->EndUser = $info['uid'];
$timeline_policy->ReturnUrl = "http://qiniutimeline.sinaapp.com/upload-api.php";
?>
<html>
    <head>
        <title>upload</title>
    </head>
    <body>
		<form method="post" action="http://up.qiniu.com/" enctype="multipart/form-data">
            <input type="file" name="file" /><br />
            <textarea name="x:desc" rows=10></textarea><br />
            <input type="hidden" name="token" value="<?=$timeline_policy->Token(null)?>" />
            <input type="submit" />
        </form>
    </body>
</html>