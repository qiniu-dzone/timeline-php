<?php
include "db.php";
include "upload-lib.php";



$info = mustGetAuthorizationInfo();
$timeline_policy->EndUser = $info['uid'];
#$timeline_policy->ReturnUrl = "http://qiniutimeline.sinaapp.com/upload-api.php";
header("Content-Type: text/html; charset=UTF-8");
?>
<html>
    <head>
        <title>upload</title>
    </head>
    <body>
        <form id="fileinfo" method="post" action="http://up.qiniu.com/" enctype="multipart/form-data">
            <input type="file" name="file" /><br />
            <textarea name="x:desc" rows=10></textarea><br />
            <input type="hidden" name="token" value="<?=$timeline_policy->Token(null)?>" />
            <input type="submit" />

        </form>
        <button id="upload">ajax上传</button>
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
        <script>
            $("#upload").click(function() {
            	var formData = new FormData(document.getElementById('fileinfo'));
                jQuery.ajax("http://up.qiniu.com/", {
                    processData: false,
                    contentType: false,
                    type: "POST",
                    data: formData,
                    success: function(ret) {
                        alert(JSON.stringify(ret));
                    }
                });
            });
        </script>
            
    </body>
</html>