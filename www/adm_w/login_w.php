<?
$level="";
session_start();
ini_set("memory_limit",-1);
include "../lib/config.php";
include "../lib/configdb.php";
include "../lib/func.php";

$adm_root = "/adm_w";

if($level=="S")
	alert_msg("",$adm_root."/index.php","");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="ko" xml:lang="ko">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge, chrome=1"/>
<meta content="width=1024px,user-scalable=yes,target-densitydpi=device-dpi" name="viewport"/>
<title><?=$title_name?> 관리자 페이지 입니다.</title>
<link rel="stylesheet" type="text/css" href="<?=$adm_root?>/css/master.css" />
<link rel="stylesheet" type="text/css" href="<?=$adm_root?>/css/admin.css" />
<link rel="stylesheet" type="text/css" href="<?=$adm_root?>/css/style.css" />
<script type="text/javascript" src="<?=$adm_root?>/js/jquery.min.js"></script>
<script type="text/javascript" src="<?=$adm_root?>/js/jquery.easing.js"></script>
<script type="text/javascript" src="<?=$adm_root?>/js/jquery-ui.min.js"></script>
<script type="text/javascript" src="<?=$adm_root?>/js/jquery.ui.core.min.js"></script>
<script type="text/javascript" src="<?=$adm_root?>/js/jquery.ui.datepicker.min.js"></script>
<script type="text/javascript" src="<?=$adm_root?>/js/jquery.cookie.js"></script>
<script type="text/javascript" src="<?=$adm_root?>/js/common.js"></script>
<script>
$(document).ready(function() {
	var cookieid = "__<?=$user?>__";
	var savedId = $.cookie(cookieid);

	if (savedId != "") {

		$("#login-con-id").val(savedId);
		$("#login-con-id").focus();
		$("#login-con-pass").focus();
		$("input:checkbox[id=chkid]").attr("checked", true);
	}

	//로그인 클릭
	$('.inputs> li:eq(2) > input').bind('click', function() {

		if ($("#chkid").is(":checked")) {
			$.cookie(cookieid, $("#login-con-id").val(), { expires: 365, path: '/', secure: false });
		}
		else {
			$.cookie(cookieid, null, { expires: -1, path: '/', secure: false });
		}

	});

});
</script>
</head>
<body>
<p class="skip"><a href="#content">본문 바로가기</a></p>

<div id="wrap" class="login_wrap"><!-- wrap start -->

<div class="login_box"><!-- login_box start -->
<form action="login_check.php" method="post" name="admin_form">
<h2><img src="<?=$adm_root?>/image/login_title.gif" alt="" /></h2>
<ul class="inputs">
	<li><input type="text" name="idno" id="login-con-id" title= "아이디 입력" placeholder="아이디"/></li>
	<li><input type="password" id="login-con-pass" name="passwd" title= "비밀번호 입력" placeholder="비밀번호"/></li>
	<li><input type="image" src="<?=$adm_root?>/image/btn_login.gif" alt="로그인" /></li>
</ul>
<p class="check"><label><input type="checkbox" name="chkid" id="chkid"/><span>아이디저장</span></label></p>
<p class="info_lost"><!-- <a href="#">로그인 계정분실 안내</a> --></p>
<ul class="links">
	<li class="link1"><a href="http://www.webper.co.kr" target="_blank">www.webper.co.kr</a></li>
	<li class="link2">070-4323-0410</li>
	<li class="link3"><a href="mailto:webper@webmaker21.net">webper@webmaker21.net</a></li>
</ul>
<address>
<?=$title_name?> | <?=$site_addr?><br />
 <?=$site_tel?>  / <?=$site_fax?>
</address>
<p class="copy">COPYRIGHT(C) 2015 <?=$title_name?> ALL RIGHT RESERVED.</p>
</form>
</div><!-- login_box end -->

</div><!-- wrap end -->
</body>
</html>