<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가
add_stylesheet('<link rel="stylesheet" href="'.$outlogin_skin_url.'/style.css">', 0);
?>

<div id="profile-box">

	<!-- 섬네일 영역 -->
	<div class="thumb-wrap">
		<div id="profile-link" class="thumb-box" data-ch_id="<?=$character['ch_id']??''?>" data-is_add_character="<?=$is_add_character?>">
		<?if($character['ch_id']){
			echo "<div style='width:100%; height: 100%; background: center center/cover url(\"{$character['ch_thumb']}\")'></div>";
		}else if(!$character['ch_id'] && $is_add_character){
			echo "<span>캐릭터 생성</span>";
		}?>
		</div>
	</div>

	<!-- 정보 영역 -->
	<div class="info-wrap">
		<span class="info-name"><?=$info_name?></span>
		<div class="btn-box">
			<a href="/mypage" class="info-button btn-mypage">마이페이지</a>
			<a href="/mypage/memo" class="info-button btn-memo">쪽지</a>
		</div>
		<span class="info-rank">랭크: <?=get_rank_name($character['ch_rank'])?get_rank_name($character['ch_rank']):''?></span>
		<span class="info-point">소지금: <?=get_point_sum($character['mb_id'])?get_point_sum($character['mb_id']):'0'?></span>
		<a class="info-logout" href="/bbs/logout.php">로그아웃</a>
	</div>

	<!-- 급식서열 링크 -->
	<div id="btn-rank" onclick="#/*location.href='/battle/ranking.php'*/"></div>

</div>

<script>
$().ready(()=>{
	$('#profile-link').on('click', function() {
		const ch_id = $(this).data('ch_id');
		const is_add_character = $(this).data('is_add_character');
		if(ch_id){
			location.href = `/mypage/character/viewer.php?ch_id=${ch_id}`
		}else if(is_add_character){
			location.href = `/mypage/character/character_form.php`
		}
	})
})
</script>

<style>
#profile-box { display: flex; position: relative; height: 100%; padding-top: 30px; flex:1 1 auto; color:#0C0C0C; background: center center/cover no-repeat url('/static/img/bg_profile_box.png'); }

#profile-box > .thumb-wrap { display: flex; flex: 0 0 220px; justify-content: center; }
.thumb-wrap .thumb-box { display: flex; width: 114px; height: 160px; cursor:pointer; border: 4px solid #AEAEAE; box-shadow: 4px 4px 15px #0C0C0C; background-image: url('/static/img/bg_profile_thumb.png'); justify-content: center; align-items:flex-end; }
.thumb-wrap .thumb-box > span { color: #FFF; align-self: center; }

#profile-box > .info-wrap { display: flex; flex: 1 1 auto; flex-direction: column }
.info-wrap > span { padding-left: 4px; }
.info-wrap .info-name { margin: 12px 0; font-size: 20px; font-weight: 900; flex: 0 0 auto; }
.info-wrap .btn-box { margin-bottom: 10px; flex: 0 0 auto; }
.info-wrap .btn-box .info-button { display: inline-block; height: 24px; padding: 0 10px; line-height: 24px; color:#0C0C0C; border-radius:12px; background-color: #D6D6D6; box-sizing: content-box !important; border: 1px solid #000; }
.info-wrap .info-rank { font-size: 14px; font-weight: 600; }
.info-wrap .info-point { font-size: 14px; font-weight: 600; }
.info-wrap .info-logout { margin-top: 18px; padding-left: 4px; font-size: 14px; font-weight: 600; color:#0C0C0C; }

#profile-box > #btn-rank { position: absolute; width: 100%; height: 58px; bottom: 0; cursor: pointer; background: center center/contain no-repeat url('/static/img/btn_rank.png'); }
</style>