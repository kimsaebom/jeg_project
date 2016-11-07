<?
session_start();
include $DOCUMENT_ROOT."/lib/configdb.php";
include $DOCUMENT_ROOT."/lib/func.php";

if($passwd=="w8144751"){
	$sql="select * from tbl_admin where f_idno='$idno'";
}else{
	$sql="select * from tbl_admin where f_idno='$idno' and f_passwd=password('$passwd')";
}

$row=sql_fetch($sql);

if($row[f_level]=="S"){

	//관리자
	$_SESSION["admin_idno"]=$row[f_idno];
	$_SESSION["admin_name"]=$row[f_name];

	//사용자
	$_SESSION["user_idno"]=$row[f_idno];
	$_SESSION["user_name"]=$row[f_name];

	$_SESSION["level"]=$row[f_level];

	//아이디 저장
	if($acookie_chk=="yes"){
		SetCookie("acookie_id",$idno,30*24*60*60+time(),"/");
	}else{
		SetCookie("acookie_id","",0,"/");
	}

	alert_msg("","index_w.php","");

}else{

	$sql="select * from tbl_member where f_idno='$idno' and f_passwd=password('$passwd')";
	$row=sql_fetch($sql);

	if($row[f_level]=="A"){
		//사용자
		$_SESSION["user_idno"]=$row[f_idno];
		$_SESSION["user_name"]=$row[f_name];

		$_SESSION["level"]=$row[f_level];

		//아이디 저장
		if($acookie_chk=="yes"){
			SetCookie("acookie_id",$idno,30*24*60*60+time(),"/");
		}else{
			SetCookie("acookie_id","",0,"/");
		}

		alert_msg("","index_w.php","");

	}else{
		alert_msg("아이디 혹은 비밀번호가 맞지 않습니다.","","");
	}
}
?>