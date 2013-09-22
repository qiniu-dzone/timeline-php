<?php
include "db.php";
include "upload-lib.php";

$info = mustGetAuthorizationInfo();
$timeline_policy->EndUser = $info['uid'];
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="zh-cn" dir="ltr">

<head>
	<meta charset="utf-8" />
	<title>七牛云存储 | HTML5 大文件上传</title>
	<link href="http://cdnjs.bootcss.com/ajax/libs/twitter-bootstrap/2.3.1/css/bootstrap.min.css" rel="stylesheet">
	<link href="static/css/bootstrap-fileupload.min.css" rel="stylesheet">

	<style type="text/css">
	.wrap {
		width:500px;
		margin: 0 auto;
		margin-top: 50px;
	}
	#progressbar {
		float: left;
		width:350px;
		height: 20px;
	}
	.bar {
		text-align:left;
	}
	header {
		text-align: center;
	}
	li {
		list-style-type: none;
	}
	#progressbarLabel {
		position: absolute;
		width: 500px;
		text-align: center;
	}
	</style>
</head>

<body>
	<header><h3>qiniu timeline demo upload</h3></header>
	<section>
		<div class="wrap">
			<form id="file-sample" name="file-sample">
				<div class="fileupload fileupload-new" data-provides="fileupload">
					<div class="input-append">
						<div class="uneditable-input span3"><i class="icon-file fileupload-exists"></i> 
							<span class="fileupload-preview"></span>
						</div>
						<span class="btn btn-file">
							<span class="fileupload-new">选择文件</span>
							<span class="fileupload-exists">重选</span>
							<input type="file" id="selectFiles" title="选择文件上传至七牛">
						</span><a href="#" class="btn fileupload-exists" data-dismiss="fileupload">删除</a>
						<button type="button" class="btn" id="upladBtn" name="uploadBtn" data="start">上传</button>
					</div>
					<label>状态:</label>
					<textarea style="width:95%" name="x:desc" id="desc"></textarea>
					<div id="div-result" class="alert alert-success alert-dismissable">上传结果将会显示在这里</div>
					<div class="progress progress-striped active">
						<div id="progressbar" class="bar" style="width: 0%;"></div>
						<div id="progressbarLabel"></div>
					</div>
				</div>
			</form>
		</div>
	</section>
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js" ></script>
	<script type="text/javascript" src="static/js/jquery.cookie.js"></script>
	<script type="text/javascript" src="static/js/jquery.base64.min.js"></script>
	<script type="text/javascript" src="qiniu.sdk.js?id=123123"></script>
	<script type="text/javascript" src="static/js/bootstrap-fileupload.min.js"></script>
	<script type="text/javascript">
	$(function() {

		$("#selectFiles").change(function() {
			$("#upladBtn").text("上传");
			$("#upladBtn").attr("data", "start");
			$("#progressbar").attr("style", "width: 0%")
			$("#progressbarLabel").html("");
			$("#div-result").html("上传结果将会显示在这里")
			Q.Stop();
		})
		$("#upladBtn").click(function(event) {

			if ($(this).attr("data") == "pause") {

				$(this).attr("data", "go-on");
				$(this).text("继续");
				Q.Pause();
				return;
			}

			if ($(this).attr("data") == "go-on") {
				$(this).attr("data", "pause");
				$(this).text("暂停")
				Q.Resumbe();
				return;
			}

			if ($(this).attr("data") == "start") {
				$(this).attr("data", "pause");
				$(this).text("暂停")
				Q.Histroy(true);
				Q.SetToken("<?=$timeline_policy->Token(null)?>");
				Q.addEvent("historyFound", function(his) {
					if (confirm("文件：" + his + ",未上传完成，是否继续？")) {
						Q.ResumbeHistory();
					} else {
						Q.ClearHistory();
						Q.Upload(Q.files()[0].name);
					}
				});

				//上传失败回调
				Q.addEvent("putFailure", function(msg) {
					$("#upladBtn").text("上传");
					$("#upladBtn").attr("data", "start");
					alert(msg);
				});

				//上传进度回调
				// p:0~100
				Q.addEvent("progress", function(p, s) {
					$("#progressbar").attr("style", "width: " + p + "%")
					$("#progressbarLabel").html(p + "%" + ", 速度: " + s);
				});

				//上传完成回调
				//fsize:文件大小(MB)
				//res:上传返回结果，默认为{hash:<hash>,key:<key>}
				Q.addEvent("putFinished", function(fsize, res, taking) {
					uploadSpeed = 1024 * fsize / (taking * 1000);
					if (uploadSpeed > 1024) {
						formatSpeed = (uploadSpeed / 1024).toFixed(2) + "Mb\/s";
					} else {
						formatSpeed = uploadSpeed.toFixed(2) + "Kb\/s";
					};
					$("#upladBtn").text("上传");
					$("#upladBtn").attr("data", "start");
					if (res.code == 200) {
						$("#div-result").html(
							'<strong>文件大小: </strong> ' + Q.fileSize(fsize) + "<br/>" +
							'<strong>文件地址: </strong> <a href="'+res.data.url+'">' +
							res.data.url + '</a><br/>'
						).attr("class", "alert alert-success alert-dismissable")
						$("#progressbarLabel").html("上传成功!平均速度:"+formatSpeed);
					} else {
						$("#div-result").attr("class", "alert alert-danger").html(res.data);
					}
					$("#progressbar").attr("style", "width: 100%")
				});

				Q.Bucket("qtestbucket");
				Q.SetFileInput("selectFiles");
				Q.AddParams("x:desc", $("#desc").val());
				Q.Upload(null);

				return;
			}
		});
	});
	</script>
</body>

</html>

