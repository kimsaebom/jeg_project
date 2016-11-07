<?
//==========================================================================================================================
// extract($_GET); 명령으로 인해 page.php?_POST[var1]=data1&_POST[var2]=data2 와 같은 코드가 _POST 변수로 사용되는 것을 막음
// 081029 : letsgolee 님께서 도움 주셨습니다.
//--------------------------------------------------------------------------------------------------------------------------
$ext_arr = array ('PHP_SELF', '_ENV', '_GET', '_POST', '_FILES', '_SERVER', '_COOKIE', '_SESSION', '_REQUEST',
                  'HTTP_ENV_VARS', 'HTTP_GET_VARS', 'HTTP_POST_VARS', 'HTTP_POST_FILES', 'HTTP_SERVER_VARS',
                  'HTTP_COOKIE_VARS', 'HTTP_SESSION_VARS', 'GLOBALS');
$ext_cnt = count($ext_arr);
for ($i=0; $i<$ext_cnt; $i++) {
    // POST, GET 으로 선언된 전역변수가 있다면 unset() 시킴
    if (isset($_GET[$ext_arr[$i]]))  unset($_GET[$ext_arr[$i]]);
    if (isset($_POST[$ext_arr[$i]])) unset($_POST[$ext_arr[$i]]);
}
//==========================================================================================================================


// escape string 처리 함수 지정
// addslashes 로 변경 가능
define('ESCAPE_FUNCTION', 'sql_escape_string');

// multi-dimensional array에 사용자지정 함수적용
function array_map_deep($fn, $array)
{
    if(is_array($array)) {
        foreach($array as $key => $value) {
            if(is_array($value)) {
                $array[$key] = array_map_deep($fn, $value);
            } else {
                $array[$key] = call_user_func($fn, $value);
            }
        }
    } else {
        $array = call_user_func($fn, $array);
    }

    return $array;
}


// SQL Injection 대응 문자열 필터링
function sql_escape_string($str)
{
    if(defined('G5_ESCAPE_PATTERN') && defined('G5_ESCAPE_REPLACE')) {
        $pattern = G5_ESCAPE_PATTERN;
        $replace = G5_ESCAPE_REPLACE;

        if($pattern)
            $str = preg_replace($pattern, $replace, $str);
    }

    $str = call_user_func('addslashes', $str);

    return $str;
}


//==============================================================================
// SQL Injection 등으로 부터 보호를 위해 sql_escape_string() 적용
//------------------------------------------------------------------------------
// magic_quotes_gpc 에 의한 backslashes 제거
if (get_magic_quotes_gpc()) {
    $_POST    = array_map_deep('stripslashes',  $_POST);
    $_GET     = array_map_deep('stripslashes',  $_GET);
    $_COOKIE  = array_map_deep('stripslashes',  $_COOKIE);
    $_REQUEST = array_map_deep('stripslashes',  $_REQUEST);
}

// sql_escape_string 적용
$_POST    = array_map_deep(ESCAPE_FUNCTION,  $_POST);
$_GET     = array_map_deep(ESCAPE_FUNCTION,  $_GET);
$_COOKIE  = array_map_deep(ESCAPE_FUNCTION,  $_COOKIE);
$_REQUEST = array_map_deep(ESCAPE_FUNCTION,  $_REQUEST);
//==============================================================================


// PHP 4.1.0 부터 지원됨
// php.ini 의 register_globals=off 일 경우
@extract($_GET);
@extract($_POST);
@extract($_SERVER);

//################################################################################
// 글자자르기 기본함수
//################################################################################
function cut_string($str, $len, $suffix="…", $charset="UTF-8"){
	if($charset=='UTF-8'){
		$c = substr(str_pad(decbin(ord($str{$len})),8,'0',STR_PAD_LEFT),0,2);
		if ($c == '10')
			for (;$c != '11' && $c{0} == 1;$c = substr(str_pad(decbin(ord($str{--$len})),8,'0',STR_PAD_LEFT),0,2));
		return substr($str,0,$len) . (strlen($str)-strlen($suffix) >= $len ? $suffix : '');
	} else {
		$s = substr($str, 0, $len);
		$cnt = 0;
		for ($i=0; $i<strlen($s); $i++)
			if (ord($s[$i]) > 127)
				$cnt++;
		$s = substr($s, 0, $len - ($cnt % 2));
		if (strlen($s) >= strlen($str))
			$suffix = "";
		return $s . $suffix;
	}
}

//################################################################################
// 이미지 리사이즈 기본함수
//################################################################################
function img_resize($img_path,$width_size,$height_size){
	$size=getimagesize($img_path);
	$wsize=$size[0];
	$hsize=$size[1];
	if(($wsize/$width_size)>($hsize/$height_size)){
		$rate=$wsize/$width_size;
	}
	if(($wsize/$width_size)<=($hsize/$height_size)){
		$rate=$hsize/$height_size;
	}
	$w_size=$wsize/$rate;
	$h_size=$hsize/$rate;

	echo"width=$w_size height=$h_size border=0";
}

//################################################################################
// 에러 및 페이지이동 기본함수
//################################################################################
function alert_msg($str="", $url= "", $option="") {

	echo "<meta http-equiv=\"content-type\" content=\"text/html; charset=utf-8\">";

	if($url=="" and $option==""){
		$url="history.back()";
	}else if($option=="close"){
		$url="self.close()";
	}else if($url and $option=="") {
		$url="location.href='$url'";
	}else if($url and $option=="parent") {
		$url="parent.location.href='$url'";
	}else if($url and $option=="opener") {
		$url="opener.location.href='$url';";
		$url.="self.close();";
	}else if($option=="opener_reload") {
		$url="opener.location.reload();";
		$url.="self.close();";
	}else if($option=="stop") {
		$url="";
	}else{
		echo"
		<script type='text/javascript'>
			alert('msg error');
		</script>
		";
		exit;
	}

	if($str){
		echo"
		<script type='text/javascript'>
			alert('$str');
			$url;
		</script>
		";
	}else{
		echo"
		<script type='text/javascript'>
			$url;
		</script>
		";
	}
	exit;
}

function kslee_query($query) {
  global $db;
	$temp_bar = "<br>=============================================================================<br>";
	$result = mysql_query($query, $db) or die("DB ERROR <br>".$temp_bar."<font size='2'> Mysql_Query : ".$query."<br> Mysql_Error : ".mysql_error()."<br> Mysql Error Num : ".mysql_errno()."</font>".$temp_bar);
	return $result;
}

// mysql_query 와 mysql_error 를 한꺼번에 처리
function sql_query($sql, $error=TRUE)
{
    if ($error)
        $result = @mysql_query($sql) or die("<p>$sql<p>" . mysql_errno() . " : " .  mysql_error() . "<p>error file : $_SERVER[PHP_SELF]");
    else
        $result = @mysql_query($sql);
    return $result;
}


// 쿼리를 실행한 후 결과값에서 한행을 얻는다.
function sql_fetch($sql, $error=TRUE)
{
    $result = sql_query($sql, $error);
    $row = sql_fetch_array($result);
    return $row;
}


// 결과값에서 한행 연관배열(이름으로)로 얻는다.
function sql_fetch_array($result)
{
    $row = @mysql_fetch_assoc($result);
    return $row;
}

// 현재페이지, 총페이지수, 한페이지에 보여줄 행, URL
function get_paging($write_pages, $cur_page, $total_page, $url, $add=""){
	$str = "";
	if ($cur_page > 1) {
		$str .= "<a href='" . $url . "1{$add}' class='direction prev'>처음</a>&nbsp;";
		//$str .= "[<a href='" . $url . ($cur_page-1) . "'>이전</a>]";
	}

	$start_page = ( ( (int)( ($cur_page - 1 ) / $write_pages ) ) * $write_pages ) + 1;
	$end_page = $start_page + $write_pages - 1;

	if ($end_page >= $total_page) $end_page = $total_page;

	if ($start_page > 1) $str .= "<a href='" . $url . ($start_page-1) . "{$add}' class='direction prev'>이전</a>&nbsp;";

	if ($total_page > 1) {
		for ($k=$start_page;$k<=$end_page;$k++) {
			if ($cur_page != $k)
				$str .= "<a href='$url$k{$add}'>$k</a>&nbsp;";
			else
				$str .= "<strong>$k</strong>&nbsp;";
		}
	}

	if ($total_page > $end_page) $str .= "<a href='" . $url . ($end_page+1) . "{$add}' class='direction next'>다음</a>&nbsp;";

	if ($cur_page < $total_page) {
		//$str .= "[<a href='$url" . ($cur_page+1) . "'>다음</a>]";
		$str .= "&nbsp;<a href='$url$total_page{$add}' class='direction next'>맨끝</a>";
	}
	$str .= "";

	return $str;
}

//모바일 현재페이지, 총페이지수, 한페이지에 보여줄 행, URL
function get_paging2($write_pages, $cur_page, $total_page, $url, $add=""){
	$str = "";
	if ($cur_page > 1) {
		$str .= "<a href='" . $url . "1{$add}'>&lt;&lt;</a> ";
		//$str .= "[<a href='" . $url . ($cur_page-1) . "'>이전</a>]";
	}

	$start_page = ( ( (int)( ($cur_page - 1 ) / $write_pages ) ) * $write_pages ) + 1;
	$end_page = $start_page + $write_pages - 1;

	if ($end_page >= $total_page) $end_page = $total_page;

	if ($start_page > 1) $str .= "<a href='" . $url . ($start_page-1) . "{$add}'>&lt;</a> ";

	if ($total_page > 1) {
		for ($k=$start_page;$k<=$end_page;$k++) {
			if ($cur_page != $k)
				$str .= "<a href='$url$k{$add}'>$k</a> ";
			else
				$str .= "<a href='$url$k{$add}' class='active'>$k</a> ";
		}
	}

	if ($total_page > $end_page) $str .= "<a href='" . $url . ($end_page+1) . "{$add}'>&gt;</a> ";

	if ($cur_page < $total_page) {
		//$str .= "[<a href='$url" . ($cur_page+1) . "'>다음</a>]";
		$str .= "<a href='$url$total_page{$add}'>&gt;&gt;</a> ";
	}
	$str .= "";

	return $str;
}


//썸네일//
function MakeThumb($file_path, $save_path, $width, $height){
	$imginfo = getimagesize($file_path);
	if($imginfo[2] != 1 && $imginfo[2] != 2 && $imginfo[2] != 3)
	return "확장자가 jp(e)g/png/gif 가 아닙니다.";

	if($imginfo[2] == 1) $cfile = imagecreatefromgif($file_path);
	else if($imginfo[2] == 2) $cfile = imagecreatefromjpeg($file_path);
	else if($imginfo[2] == 3) $cfile = imagecreatefrompng($file_path);

	$wsize=$imginfo[0];
	$hsize=$imginfo[1];

	if(($wsize/$width)>($hsize/$height)){
		$rate=$wsize/$width;
	}
	if(($wsize/$width)<=($hsize/$height)){
		$rate=$hsize/$height;
	}
	$new_w=$wsize/$rate;
	$new_h=$hsize/$rate;

	$dest = imagecreatetruecolor($new_w, $new_h);

	imagecopyresampled($dest, $cfile, 0, 0, 0, 0, $new_w, $new_h, $imginfo[0], $imginfo[1]);

	if($imginfo[2] == 1) imagegif($dest, $save_path, 100);    // 1~100
	else if($imginfo[2] == 2) imagejpeg($dest, $save_path, 100); // 1~100
	else if($imginfo[2] == 3) imagepng($dest, $save_path, 9);  //  1~9

	@chmod($save_path, 0707);

	imagedestroy($dest);
	return 1;
}

//썸네일2//
function thumbnail_crop($source_path, $thumbnail_path, $width, $height){
	list($img_width,$img_height, $type) = getimagesize($source_path);
	if ($type!=1 && $type!=2 && $type!=3 && $type!=15) return;
	if ($type==1) $img_sour = imagecreatefromgif($source_path);
	else if ($type==2 ) $img_sour = imagecreatefromjpeg($source_path);
	else if ($type==3 ) $img_sour = imagecreatefrompng($source_path);
	else if ($type==15) $img_sour = imagecreatefromwbmp($source_path);

	if ($img_width > $img_height) {
		$w = round($height*$img_width/$img_height);
		$h = $height;
		$x_last = round(($w-$width)/2);
		$y_last = 0;
	} else {
		$w = $width;
		$h = round($width*$img_height/$img_width);
		$x_last = 0;
		$y_last = round(($h-$height)/2);
	}

	if ($img_width < $width && $img_height < $height) {
		$img_last = imagecreatetruecolor($width, $height);
		$x_last = round(($width - $img_width)/2);
		$y_last = round(($height - $img_height)/2);

		imagecopy($img_last,$img_sour,$x_last,$y_last,0,0,$w,$h);
		imagedestroy($img_sour);
		$white = imagecolorallocate($img_last,255,255,255);
		imagefill($img_last, 0, 0, $white);
	} else {
		$img_dest = imagecreatetruecolor($w,$h);
		imagecopyresampled($img_dest, $img_sour,0,0,0,0,$w,$h,$img_width,$img_height);
		$img_last = imagecreatetruecolor($width,$height);
		imagecopy($img_last,$img_dest,0,0,$x_last,$y_last,$w,$h);
		imagedestroy($img_dest);
	}

	if ($thumbnail_path) {
		if ($type==1) imagegif($img_last, $thumbnail_path, 100);
		else if ($type==2 ) imagejpeg($img_last, $thumbnail_path, 100);
		else if ($type==3 ) imagepng($img_last, $thumbnail_path, 100);
		else if ($type==15) imagebmp($img_last, $thumbnail_path, 100);
	} else {
		if ($type==1) imagegif($img_last);
		else if ($type==2 ) imagejpeg($img_last);
		else if ($type==3 ) imagepng($img_last);
		else if ($type==15) imagebmp($img_last);
	}
	imagedestroy($img_last);
}

// 현재 서버에서 지원하는 이미지 포맷을 확인
function checkFormat($ext) {
	$gd_support_format = gd_info();
	$server_support_format = array();

	if($gd_support_format['GIF Read Support'] && $gd_support_format['GIF Create Support']) $server_support_format[] = "gif";
	if($gd_support_format['PNG Support']) $server_support_format[] = "png";
	if($gd_support_format['JPG Support']) {
		$server_support_format[] = "jpg";
		$server_support_format[] = "jpeg";
	}
	return in_array($ext, $server_support_format);
}

/* 강제크기로 절살 썸네일 시작*/
function getNewSize($img, $w, $h) {
	$img_size = getimagesize($img);
	$tmp=1;

	if($img_size[0] > $img_size[1]) {
		if($img_size[1] > $h) $tmp = $h / $img_size[1];
	} else {
		if($img_size[0] > $w) $tmp = $w / $img_size[0];
	}

	$size[0] = $img_size[0] * $tmp;
	$size[1] = $img_size[1] * $tmp;
	$size[2] = $img_size[0];
	$size[3] = $img_size[1];
	$size[4] = ($size[0] > $w) ? $size[0] - $w : 0;
	$size[5] = ($size[1] > $h) ? $size[1] - $h : 0;
	$size[6] = ($size[4]) ? $size[4]/2 : 0;
	$size[7] = ($size[5]) ? $size[5]/2 : 0;

	return $size;
}

function imageResizer($path, $image, $width, $height, $thumb_file='', $screen=true) {
	global $server_support_format;

	if($path && substr($path, -1) != "/") {
		$path .= "/";
		$ori = $path.$image;
	} else {
		$path = dirname($image)."/";
		$ori = $image;
	}

	$ext = strtolower(substr(strrchr($image,"."),1));

	if(checkFormat($ext)) {
		if(!$thumb_file) $thumb_file = $path."thumb_".$image;

		switch ($ext) {
			case "jpg": case "jpeg": $im = ImageCreateFromJPEG ($ori); break;
			case "gif": $im = ImageCreateFromGIF ($ori); break;
			case "png": $im = ImageCreateFromPNG ($ori); break;
		}

		$size = getNewSize($ori, $width, $height);

		$dst_im = ImageCreateTrueColor(($size[0]-$size[4]),($size[1]-$size[5]));

		ImageCopyResampled($dst_im, $im, 0, 0, $size[6], $size[7], $size[0]-$size[6], $size[1]-$size[7], $size[2]-$size[6], $size[3]-$size[7]);

		if($screen == true) {
			switch ($ext) {
				case "jpg": case "jpeg":
				header("Content-type: image/gif");
				ImageJPEG($dst_im, "", 100);
				break;
				case "gif":
				header("Content-type: image/gif");
				imageGIF($dst_im);
				break;
				case "png":
				header("Content-type: image/gif");
				ImagePNG($dst_im);
				break;
			}
		} else {
			switch ($ext) {
				case "jpg": case "jpeg": ImageJPEG($dst_im, $thumb_file, 100); break;
				case "gif": ImageGIF($dst_im, $thumb_file); break;
				case "png": ImagePNG($dst_im, $thumb_file); break;
			}
			@chmod($thumb_file, 0707);
		}
		imagedestroy($im);
		imagedestroy($dst_im);
	}
}
/* 강제크기로 절삭 썸네일 끝 */

//게시판정보
function board_info($val){
	$brow=mysql_fetch_array(mysql_query("select * from tbl_set_board where f_bid=$val"));
	return $brow;
}

//이미지체크
function img_check($val){
	if($val=="image/gif" or $val=="image/pjpeg" or $val=="image/jpeg" or $val=="image/bmp" or $val=="image/x-png"){
		return 1;
	}else{
		return 0;
	}
}

//값체크
function value_check($val){
	$result = preg_replace("/[ #\&\+\-%@=\/\\\:;,\.'\"\^`~\_|\!\?\*$#<>()\[\]\{\}]/i", "", $val);
	return $result;
}

//회원정보
function get_member($idno){
	return sql_fetch("select * from tbl_member where f_idno = TRIM('$idno')");
}

//첨부파일크기
function file_size($file_size){
	if($file_size < 1000000){
		$file_size=($file_size/1000);
		$file_size=round($file_size,1)."K";
	}else{
		$file_size=($file_size/1000000);
		$file_size=round($file_size,1)."M";
	}

	return $file_size;
}

//게시판내용에 이미지 출력(서버경로,웹경로,스킨경로,파일명,가로넓이,게시판코드,key,순서)
function img_view($board_data_dir,$board_http_data_dir,$board_http_dir,$file_name,$comment_img_width,$bid,$mid,$file){

	$size = getimagesize($board_data_dir."/".$file_name);

	if($size){
		$wsize=$size[0];
		$hsize=$size[1];
		if($wsize > $comment_img_width){
			if($hsize > 700) $scrollbars = "scrollbars=yes";
			$img_link="<a href='javascript:;' onclick=\"javascript:window.open('$board_http_dir/full_img.php?bid=$bid&mid=$mid&file=$file','','width=100,height=100,$scrollbars')\">";
			$w_size=$comment_img_width;

			/////이미지 비율로 줄이기///
			$read_width=$comment_img_width;
			$read_height=700;
			if(($wsize/$read_width)>($hsize/$read_height)){
				$read_rate=$wsize/$read_width;
			}
			if(($wsize/$read_width)<=($hsize/$read_height)){
				$read_rate=$hsize/$read_height;
			}
			$w_size=$wsize/$read_rate;
			$h_size=$hsize/$read_rate;
			////////////////////////////////////

		}else{
			$w_size=$wsize;
			$h_size=$hsize;
		}
	}

	if($size['mime']=="image/gif" or $size['mime']=="image/jpeg" or $size['mime']=="image/png"){
		$img = $img_link."<img src=\"$board_http_data_dir/$file_name\" width=\"$w_size\" height=\"$h_size\" alt=\"원본사진보기\" /></a><br/><br/>";
	}

	return $img;
}

//게시판 첨부파일 처리
function file_save($upfile,$board_data_dir,$upfile_name){
	$path_parts = pathinfo($upfile_name);
	$upfile_name = uniqid("").".".$path_parts["extension"];
	copy($upfile,$board_data_dir."/".$upfile_name);

	return $upfile_name;
}

//게시판 웹에디터 이미지 첨부 처리(신규저장시)
function comment_file_save($comment,$board_tmp,$board_upload_dir,$board_tmp_web,$board_http_upload_dir){
	$comment_img=str_replace("\\","",$comment); // \없애기
	if(preg_match_all("/ src=\"(.*?)\"/i",$comment_img,$found)) {
		$tmp_dir = $board_tmp;
		for ($i=0; $i< count($found[1]); $i++) {
			$b = split('/tmp/',$found[1][$i]);
			$c = split('>',$b[1]);
			$tmp_file=$tmp_dir."/".$c[0];
			if(file_exists($tmp_file)==1 and $c[0]!=""){
				copy($tmp_file,$board_upload_dir."/".$c[0]); // /ksboard/tmp/ => /ksboard/upload/board/ 복사
				unlink($tmp_file); // /ksboard/tmp/ 삭제
			}
		}
	}
	$comment=str_replace($board_tmp_web,$board_http_upload_dir,$comment); // \이미지 경로 바꾸기

	return $comment;
}

//게시판 웹에디터 이미지 첨부 처리(수정시)
function comment_file_edit($comment_old,$comment,$board_tmp,$board_upload_dir,$board_tmp_web,$board_http_upload_dir){
	if(preg_match_all("/ src=\"(.*?)\"/i",$comment_old,$found)) {
		$tmp_dir=$board_tmp;
		for ($i=0; $i< count($found[1]); $i++) {
			$b = split('/board/', $found[1][$i]);
			$c = split('>', $b[1]);
			$tmp_file=$tmp_dir."/".$c[0];
			@copy($board_upload_dir."/".$c[0],$tmp_file); // /ksboard/upload/board/ => /ksboard/tmp/ 복사
			@unlink($board_upload_dir."/".$c[0]); // /ksboard/upload/board/ 삭제
			$comment=str_replace($board_http_upload_dir,$board_tmp_web,$comment); // 이미지경로 바꾸기
		}
	}

	return $comment;
}

//스팸방지//
function spam_check($board,$second,$ip,$url,$msg){
	$second_value= strtotime("-{$second} seconds");
	$now=date('Y-m-d H:i', $second_value);
	$row=sql_fetch("select count(*) as num from $board where f_wdate > '$now' and f_ip='$ip'");
	if($row[num]>0 and $ip!="115.95.221.242"){
		if($url and $msg){
			alert_msg($msg,$url,"");
		}
	}
}

// sns 공유하기
function get_sns_share_link($sns, $url, $title, $img){
    global $config;

    if(!$sns)
        return '';

    switch($sns) {
        case 'facebook':
            $str = '<li><a href="https://www.facebook.com/sharer/sharer.php?u='.urlencode($url).'&amp;p='.urlencode($title).'" class="share-facebook" target="_blank"><img src="'.$img.'" alt="페이스북에 공유"></a></li>';
            break;
        case 'twitter':
            $str = '<li><a href="https://twitter.com/share?url='.urlencode($url).'&amp;text='.urlencode($title).'" class="share-twitter" target="_blank"><img src="'.$img.'" alt="트위터에 공유"></a></li>';
            break;
        case 'googleplus':
            $str = '<li><a href="https://plus.google.com/share?url='.urlencode($url).'" class="share-googleplus" target="_blank"><img src="'.$img.'" alt="구글플러스에 공유"></a></li>';
            break;
    }

    return $str;
}
?>