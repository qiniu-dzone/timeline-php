<?php
header("Content-Type: text/html; charset=UTF-8");
?>
<html>
    <head>
        <title>timeline</title>
    </head>
    <div id="list">
        
    </div>
    <a href="javascript:;" id="more" style="display:none">加载更多</a>
    <body>
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js" ></script>
        <script>
            function makeItem(data) {
                ret = '<div><b>'+data.user+'('+data.date+')</b><br>'+data.desc+'<div>';
                if (data.mime.indexOf('image') >= 0) {
                    ret += '<a href="'+data.url+'" target="_blank"><img src="'+data.url+'?imageView/2/w/600"/></a>';
                } else {
                    ret += '<a href="'+data.url+'">'+data.url+'</a>'
                }
        		ret += '</div></div>';
                return ret
            }
            var lastid=-1;
            function getNewItems() {
                var postData = {lastid: lastid};
                $.get("/list-api.php?user=chzyer&pswd=adf", postData, function(data) {
                    if (data.code != 200) {
                        alert(data.code + "," + data.data);
                        return;
                    }
                    if (data.data == null || data.data.length >= data.count) {
                        $("#more").hide();
                    } else {
                        $("#more").show();
                    }
                    for (var i in data.data) {
                        $("#list").append(makeItem(data.data[i]));
                        $("#list").append("<hr>");
                        lastid = data.data[i].rid;
                    }
                });
            }
            $("#more").click(function() {
                getNewItems();
            })
            getNewItems();
        </script>
    </body>
</html>