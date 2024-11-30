<?php
include_once('./_common.php');
define('_MAIN_', true);

if(defined('G5_THEME_PATH')) {
	require_once(G5_THEME_PATH.'/main.php');
	return;
}
include_once(G5_PATH.'/head.php');
add_stylesheet('<link rel="stylesheet" href="'.G5_CSS_URL.'/main.css">', 0);
add_stylesheet('<link rel="stylesheet" href="'.G5_CSS_URL.'/main.yhsb.css">', 0);
include_once(G5_PATH."/intro.php");

$MAX_SCORE = 1000;

$left_score1 =0; $left_score1_rate =0;
$right_score1=0; $right_score1_rate=0;

$left_score1 = get_side_score_1(1);
$left_score1_rate = $left_score1 / $MAX_SCORE * 100;
$right_score1 = get_side_score_1(2);
$right_score1_rate = $right_score1 / $MAX_SCORE * 100;
?>

<div id="main_body">

<?
$main_content = get_site_content('site_main');
if($main_content) {
	echo $main_content;
} else {
?>

<div id="main-wrap">
	<div id="main_floating_image"><img src="/static/img/img_megaphone.png"/></div>
	<div id="main-score-wrap">
		<div class="score-box left">
			<div class="score-num-box"><span class="score-num"><?=$left_score1?></span></div>
			<div class="score-bar-box">
				<div class="score-fill" style="flex:<?=$left_score1_rate?> <?=$left_score1_rate?> auto"></div>
				<div class="score-void" style="flex:<?=100-$left_score1_rate?> <?=100-$left_score1_rate?> auto"></div>
			</div>
			<div class="logo-box"></div>
		</div>
		<div class="score-box right">
			<div class="score-num-box"><span class="score-num"><?=$right_score1?></span></div>
			<div class="score-bar-box">
				<div class="score-fill" style="flex:<?=$right_score1_rate?> <?=$right_score1_rate?> auto"></div>
				<div class="score-void" style="flex:<?=100-$right_score1_rate?> <?=100-$right_score1_rate?> auto"></div>
			</div>
			<div class="logo-box"></div>
		</div>
		<div class="main-score-vs"></div>
	</div>

	<div id="main-body-wrap">
		<div id="main-top-wrap">
			<div class="slide-wrap">
			<? include(G5_PATH."/templete/txt.visual.php"); ?>
			</div>
			<div class="twitter-wrap">
			<? include(G5_PATH."/templete/txt.twitter.php"); ?>
			</div>
		</div>
		<div id="main-notice-wrap">
			<marquee id="middel_notice" behavior="alternate/slide" direction="up/right/..." loop="" scrollmount="" scrolldelay="" style="text-shadow: 0px 0px 5px rgb(0 0 0);">< 현재 가개장 기간입니다. ></marquee>
		</div>
		<div id="main-bottom-wrap">
			<div class="bottom-left-wrap">
				<div id="button-battle"></div>
				<div class="banner-box"><img style=" position: relative; top: 66px; left: 198px;" src="/static/img/yhsb_icon.png"/></div>
				<div class="main-mobile calendar-wrap">
					<table class="calendar-table">
						<thead>
							<tr style="height:25px"><th>급식표</th></tr>
						</thead>
						<tbody class="">
							<tr>
								<td>
									<div class="no-data">신청기간 이후<br>공개됩니다.</div>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
			<div class="calendar-wrap">
				<table class="calendar-table">
					<thead>
						<tr style="height:25px"><th>급식표</th></tr>
					</thead>
					<tbody class="">
						<tr>
							<td>
								<div class="no-data">신청기간 이후 공개됩니다.</div>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="login-wrap">   
			<? include(G5_PATH."/templete/txt.outlogin.php"); ?>
			</div>
		</div>
	</div>
	<div class="main-mobile mobile-banner-box">
	</div>
</div>

<?php } ?>
</div>

<script>
$(function() {
	window.onload = function() {
		$('#body').css('opacity', 1);

		//신청기간에는 전투란 막아둠
		//$('#button-battle').on('click',()=>{
		//	location.href = '/battle/list.php';
		//})
	};
});
</script>

<?
include_once(G5_PATH.'/tail.php');
?>