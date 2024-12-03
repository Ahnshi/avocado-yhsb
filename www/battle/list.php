<?php
include_once('./_common.php');
include_once(G5_PATH.'/head.php');

/* DATA: 캐릭터 목록 */
$ch_arr = array();
$ch_all = get_character_list();
for ($i=0; $i < sizeof($ch_all); $i++) {
	// 내 캐릭터, npc 캐릭터를 제외한 모든 캐릭터를 전투 가능 캐릭터 목록에 넣음
	if($ch_all[$i]['ch_id'] != $character['ch_id'] && $ch_all[$i]['ch_type'] != 'npc'){
		array_push($ch_arr, $ch_all[$i]);
	}
}

/* DATA: 진행중인 전투장 목록 */
$room_arr = get_battle_room_list();

/* DATA: 종료된 전투장 목록 */
$room_ko_arr = get_battle_room_ko_list();

/* DATA: 내 급식 랭킹 */
$my_ranking = get_character_ranking($character['ch_id']);
?>

<link rel="stylesheet" href="/css/battle.css">

<div id="battle-room-wrap">

	<div id="battle-room-title" class="title-bar">전투장 입장</div>

	<div id="battle-room-top-wrap">

		<!-- 전투 가능한 캐릭터 목록 -->
		<div id="character-list-wrap">
			<div class="title-bar"><span>전투 가능한 캐릭터 목록</span></div>
			<div class="contents">
				<ul>
					<?for ($i=0; $i < sizeof($ch_arr); $i++){
						echo "<li class=\"list-character\" data-ch_id=\"{$ch_arr[$i]['ch_id']}\">";
						echo "<span>{$ch_arr[$i]['ch_name']}</span>";
						echo "<img src=\"{$ch_arr[$i]['ch_thumb']}\"/>";
						echo "</li>";
					}?>
				</ul>
			</div>
		</div>

		<!-- 전투방 생성 -->
		<div id="make-room-wrap">
			<div id="make-room-left" class="make-room-half">
				<input type="hidden" name="br_ch1_id" id="br_ch1_id" value="<?=$character['ch_id']?>"/>
				<div class="ch-name-bar my-name">
					<span id="ch1_name" class='ch-name'><?=$character['ch_name']?></span>
					<span class='seperater'>|</span>
					<span class='ch-rank-num-title'>급식서열</span>
					<span id="ch1_rank_num" class='ch-rank-num'><?=$my_ranking?>위</span>
				</div>
				<div id="ch-portrait-left" class="contents ch-portrait-box">
					<img src="<?=$character['ch_head']?>">
				</div>
			</div>
			<div id="make-room-right" class="make-room-half">
				<input type="hidden" name="br_ch2_id" id="br_ch2_id" value=""/>
				<div class="ch-name-bar deactive">
					<span id="ch2_name" class='ch-name'></span>
					<span class='seperater'>|</span>
					<span class='ch-rank-num-title'>급식서열</span>
					<span id="ch2_rank_num" class='ch-rank-num'>0위</span>
				</div>
				<div id="ch-portrait-right" class="contents ch-portrait-box">
					<img src="">
				</div>
			</div>
			<div id="make-room-vs"></div>
			<button id="make-room-button">방 생성하기</button>
		</div>

	</div>

	<div id="battle-room-bottom-wrap">
		<div id="battle-room-list-wrap">
			<div class="title-bar"><span>진행 중인 전투</span></div>
			<div id="room-list-box" class="contents">
				<? for ($i=0; $i < sizeof($room_arr); $i++) {
					$url = "./view.php?br_id={$room_arr[$i]['br_id']}";
					$is_own = ($room_arr[$i]['br_ch1_id'] == $character['ch_id']) || ($room_arr[$i]['br_ch2_id'] == $character['ch_id']); //내 캐릭터가 참여한 전투방일경우 1, 아니라면 0;
					$text = $is_own ? '입장하기' : '관전하기';?>
					<div class="room-item <?=$is_own?'is-own':''?>">
					<div class="room-thumb-wrap">
						<div class="room-thumb-box">
							<div class="img-box"><img src="<?=$room_arr[$i]['ch1_thumb']?>"/></div>
							<div class="name-box"><span><?=$room_arr[$i]['ch1_name']?></span></div>
						</div>
						<div class="room-thumb-box">
							<div class="img-box"><img src="<?=$room_arr[$i]['ch2_thumb']?>"/></div>
							<div class="name-box"><span><?=$room_arr[$i]['ch2_name']?></span></div>
						</div>
						<div class="room-vs-img"></div>
					</div>
					<div class="room-item-btn" onclick="location.href='<?=$url?>'"><span><?=$text?></span></div>
				</div>
				<? } ?>
			</div>
		</div>
		<div id="battle-room-ko-list-wrap">
			<div class="title-bar"><span>지난 전투</span></div>
			<div class="contents">
				<? for ($i=0; $i < sizeof($room_ko_arr); $i++) {
					$url = "./view.php?br_id={$room_ko_arr[$i]['br_id']}";
					$ch1_win = $room_ko_arr[$i]['ch1_current_hp'] > 0;
					$ch2_win = $room_ko_arr[$i]['ch2_current_hp'] > 0;
					echo "<div class=\"room-item\" onclick=\"location.href='$url'\">";
					echo '<div class="ch1"><span class="ch1-name">'.$room_ko_arr[$i]['ch1_name'].'</span><span class="room-result ch1-result '.($ch1_win?'win':'loss').'">'.($ch1_win?'승리':'패배').'</span></div>';
					echo '<div class="ch2"><span class="ch2-name">'.$room_ko_arr[$i]['ch2_name'].'</span><span class="room-result ch2-result '.($ch2_win?'win':'loss').'">'.($ch2_win?'승리':'패배').'</span></div>';
					echo '<div class="vs-img"></div></div>';
				} ?>
			</div>
		</div>
	</div>

</div>

<script>
$().ready(()=>{

	/* 전투 가능한 캐릭터 목록에서 캐릭터 클릭 */
	$('li.list-character').on('click', function() {
		const ch_id = $(this)?.data('ch_id')

		let url = "/ajax/yhsb/battle_search_character.php"
		if(ch_id) url += `?ch_id=${ch_id}`

		$.ajax({
			type:'get', async: true, url: url,
			success: (res)=>{
				
				try {
					const ch = JSON.parse(res)

					const img = $('#ch-portrait-right > img').first()
					img.attr('src', ch?.['ch_head'])
					const ch2_name = $('#ch2_name').first()
					ch2_name.text(ch?.['ch_name'])
					const br_ch2_id = $('#br_ch2_id').first()
					br_ch2_id.val(ch?.['ch_id'])
					const ch_rank_num = $('#ch2_rank_num').first()
					ch_rank_num.text(ch?.['ch_ranking'] + '위')

					$('#make-room-right .ch-name-bar').removeClass('deactive')

				} catch (e) { /* Do nothing...  */ }
			},
			error: (e)=>{ /* Do nothing...  */ }
		})
	})

	/* 전투장 생성 버튼 클릭 */
	$('#make-room-button').on('click', function() {
		let url = "/ajax/yhsb/battle_make_room.php"

		const br_ch1_id = $('#br_ch1_id').val()
		const br_ch2_id = $('#br_ch2_id').val()

		$.ajax({
			type:'post', async: true, url: url,
			data:{ br_ch1_id, br_ch2_id },
			success: (res)=>{
				const obj = JSON.parse(res)
				const br_id = obj?.br_id
				if(br_id){
					location.href = `./view.php?br_id=${br_id}`
				}
			},
			error: (e)=>{
				alert(e?.responseText ?? '오류가 발생했습니다.')
				location.reload()
			 }
		})
	})
})
</script>