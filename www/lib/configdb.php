<?
// 본 파일은, DB연결 파일입니다.
$host="localhost";  // 호스트입니다. 대부분은 localhost로 하면 됩니다.
$user="kindchun";        // DB 사용자 명, 수정 하세요.
$database="kindchun";    // DB 이름, 부분을 수정 하세요.
$password="victory2016";    // DB 패스워드,부분을 수정 하세요.

$db=mysql_connect($host,$user,$password);

mysql_query("set session character_set_connection=utf8;");
mysql_query("set session character_set_results=utf8;");
mysql_query("set session character_set_client=utf8;");

mysql_select_db($database,$db);

//mysql_query("set names utf8"); //이거 써도 됨
?>