<?
$level="";
session_start();
ini_set("memory_limit",-1);
include "../lib/config.php";
include "../lib/configdb.php";
include "../lib/func.php";
include "../lib/thumb.lib.php";

$adm_root = "/adm_w";

if($level!="S")
	alert_msg("", $adm_root."/login_w.php","");

if(!$left)$left=1;

if($left==1){
  $sub_font="메인";
	$main="Y";
  $center_page="main_w.php";

}else if($left==98){
  $sub_font="헤더설정";
  $board="tbl_header";
	include "board/header/top.php";
  $center_page="board/header/main.php";

}else if($left==2){
  $sub_font="팝업관리";
  $board="tbl_popup";
	include "board/popup/top.php";
  $center_page="board/popup/main.php";

}else if($left==3){
  $sub_font="게시판관리";
	$board="tbl_set_board";
	include "board/board_admin/top.php";
  $center_page="board/board_admin/main.php";

}else if($left==4){
  $sub_font="회원관리";
	$board="tbl_member";
	include "board/member/top.php";
  $center_page="board/member/main.php";

}else if($left==5){
  $sub_font="카테고리관리";
	$board="tbl_category";
	include "board/category/top.php";
  $center_page="board/category/main.php";

}else if($left==6){
  $sub_font="제품관리";
	$board="tbl_product";
	include "board/product/top.php";
  $center_page="board/product/main.php";

}else if($left==7){
  $sub_font="배너관리";
	$board="tbl_banner";
	include "board/banner/top.php";
  $center_page="board/banner/main.php";

}else if($left==8){
  $sub_font="매장관리";
	$board="tbl_company";
	include "board/company/top.php";
  $center_page="board/company/main.php";

}else if($left==9){
  $sub_font="온라인문의";
	$board="tbl_online";
	include "board/online/top.php";
  $center_page="board/online/main.php";

}else if($left==10){
  $sub_font="컨텐츠관리";
	$board="tbl_cons";
	include "board/cons/top.php";
  $center_page="board/cons/main.php";

}else if($left==99){
  $sub_font="접속통계";
	$board="tbl_log";
  $center_page="board/statistics/main.php";

}else if($left==97){
  $sub_font="호스팅정보";
	//$board="tbl_log";
  $center_page="board/hosting/main.php";
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="ko" xml:lang="ko">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge, chrome=1"/>
<meta content="width=1024px,user-scalable=yes,target-densitydpi=device-dpi" name="viewport"/>
<title><?=$stitle1?>관리자 페이지 입니다.</title>
<link rel="stylesheet" type="text/css" href="<?=$adm_root?>/css/master.css" />
<link rel="stylesheet" type="text/css" href="<?=$adm_root?>/css/admin.css" />
<link rel="stylesheet" type="text/css" href="<?=$adm_root?>/css/style.css" />
<script type="text/javascript" src="<?=$adm_root?>/js/jquery.min.js"></script>
<script type="text/javascript" src="<?=$adm_root?>/js/jquery.easing.js"></script>
<script type="text/javascript" src="<?=$adm_root?>/js/jquery-ui.min.js"></script>
<script type="text/javascript" src="<?=$adm_root?>/js/jquery.ui.core.min.js"></script>
<script type="text/javascript" src="<?=$adm_root?>/js/jquery.ui.datepicker.min.js"></script>
<script type="text/javascript" src="<?=$adm_root?>/js/common.js"></script>
<script type="text/javascript" src="/js/java.js"></script>
</head>
<body>
<p class="skip"><a href="#content">본문 바로가기</a></p>

<div id="wrap"><!-- wrap start -->

	<div id="header"><!-- header start -->

		<div class="header_in"><!-- header_in start -->
			<h1><a href="<?=$adm_root?>/"><span>.</span></a></h1>
			<p class="company"><a href="<?=$adm_root?>/index.php"><?=$title_name?></a></p>
			<p class="name"><?=$user_name?>(<?=$user_idno?>)님</p>
			<ul class="top_btn">
				<?if($level=="S"){?><li class="sbtn btn_gray"><a href="#none" onClick="javascript:window.open('pass_edit.php','pass_edit','width=500 height=230')">정보변경</a></li><?}?>
				<li class="sbtn btn_white"><button onclick="location.href='<?=$adm_root?>/logout.php';">로그아웃</button></li>
				<li class="sbtn btn_white"><a href="/">홈페이지 바로가기</a></li>
			</ul>
			<ul class="right_btns">
				<li><a href="http://www.webmaker21.net/" target="_blank">webm@ker21</a></li>
				<li><a href="http://www.webper.co.kr/" target="_blank">webper</a></li>
			</ul>
		</div><!-- header_in end -->

		<?include "menu.php";?>

	</div><!-- header end -->
	<hr />