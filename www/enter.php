<?
	include_once('./_common.php');
	if($is_member & !$config['cf_open']) {
		goto_url(G5_URL.'/main.php');
	}
	if(defined('G5_THEME_PATH')) {
		require_once(G5_THEME_PATH.'/enter.php');
		return;
	}
	
	/*********** Logo Data ************/
	$logo = get_logo('pc');
	$logo_data = "";
	if($logo)		$logo_data .= "<img src='".$logo."' ";
	if($m_logo)		$logo_data .= "class='only-pc' /><img src='".$m_logo."' class='not-pc'";
	if($logo_data)	$logo_data.= " />";
	/*********************************/
?>
<!doctype html>
<html lang="ko">
<head>
	<meta charset="utf-8">
	<meta name="mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta http-equiv="imagetoolbar" content="no">
	<meta http-equiv="X-UA-Compatible" content="IE=Edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<meta name="title" content="<?=$g5['title']?>">
	<meta name="keywords" content="<?=$config['cf_site_descript']?>">
	<meta name="description" content="<?=$config['cf_site_descript']?>">

	<meta property="og:title" content="<?=$g5['title']?>">
	<meta property="og:description" content="<?=$config['cf_site_descript']?>">
	<meta property="og:url" content="<?=G5_URL?>">

	<title><?=$g5['title']?></title>

	<link rel="shortcut icon" href="<?=$config['cf_favicon']?>">
	<link rel="icon" href="<?=$config['cf_favicon']?>">
	<link media="all" type="text/css" rel="stylesheet" href="<?=G5_CSS_URL?>/enter.css">
	<?
		if(!$config['cf_7']) { 
			echo '<link rel="stylesheet" href="'.G5_DATA_URL.'/css/_design.config.css" type="text/css" />';
		}
	?>
	<style type="text/css">
		@media (max-width: 640px) {
			body {
				background-image: url(https://firebasestorage.googleapis.com/v0/b/okinawablues.appspot.com/o/timelineComment%2F%ED%99%88%ED%8E%98%EC%9D%B4%EC%A7%80%20%EB%94%94%EC%9E%90%EC%9D%B8_%EB%A9%94%EC%9D%B8_241105.png_0af422de-7099-48e9-a686-9ec5c167726f?alt=media&token=cd60028d-8de3-4dc1-bc78-c06cd1885f99);
				background-color: #262626;
				background-repeat: no-repeat;
			}
		}
	</style>

	<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
</head>
<body>


<div class="wrapper">
	<div class="inner">
		<div class="index-logo">
			<a href="./main.php">
				<?=$logo_data?>
				<p class="txt-default">본 홈페이지는 1920 * 1080 PC를 기준으로 제작되었으며, 크롬 브라우저 이용을 권장합니다.</p>
			</a>
		</div>
	</div>
</div>

<script>

window.onload=function() {
	$('html').addClass('on')
	setTimeout(function() { $('html').addClass('active') }, 800);
};
</script>

</body>
</html>
