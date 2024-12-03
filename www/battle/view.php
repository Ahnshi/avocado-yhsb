<?php
include_once('./_common.php');
include_once(G5_PATH.'/head.php');

if(!$_GET['br_id']) alert('잘못된 접근입니다');
$br_id = $_GET['br_id'];
$ch_id = $character['ch_id'];

/* DATA: 전투장 */
$room = get_battle_room($br_id);
if(!$room) alert('잘못된 접근입니다');

/* DATA: 전투장 기록 */
$room_log_arr = get_battle_room_log_list($room['br_id']);

/* DATA: 캐릭터 */
$ch1 = get_character_full_info($room['br_ch1_id']);
if(!$ch1) alert('캐릭터가 존재하지 않습니다');

$ch2 = get_character_full_info($room['br_ch2_id']);
if(!$ch2) alert('캐릭터가 존재하지 않습니다');

$character = get_character_full_info($character['ch_id']);

/* DATA: 인벤토리(회복아이템만) */
$inventory = get_inven_potion_list($character['ch_id']);

$is_own = ($room['br_ch1_id'] == $character['ch_id']) || ($room['br_ch2_id'] == $character['ch_id']); //내 캐릭터가 참여한 전투방일경우 1, 아니라면 0;
$is_my_turn = (($room['br_ch1_id'] == $character['ch_id']) && ($room['br_ch1_turn'])) || (($room['br_ch2_id'] == $character['ch_id']) && ($room['br_ch2_turn']));

$ch1_hp_rate = $room['ch1_current_hp'] / $room['br_ch1_hp'] * 100;
$ch2_hp_rate = $room['ch2_current_hp'] / $room['br_ch2_hp'] * 100;

// 대사출력
if($room_log_arr[sizeof($room_log_arr)-1]){ $last = $room_log_arr[sizeof($room_log_arr)-1]; }
if($last){
	if($ch1_hp_rate <= 0){
		$ch1_conversation = $ch1['conversation_defeat'];
		$ch2_conversation = $ch2['conversation_win'];
	}else if($ch2_hp_rate <= 0){
		$ch1_conversation = $ch1['conversation_win'];
		$ch2_conversation = $ch2['conversation_defeat'];
	}else if($last['brl_ch1_command']=='공격'||$last['brl_ch1_command']=='필살기'){
		$ch1_conversation = $ch1['conversation_attack'];
		$ch2_conversation = $ch2['conversation_defense'];
	}else if($last['brl_ch2_command']=='공격'||$last['brl_ch2_command']=='필살기'){
		$ch1_conversation = $ch1['conversation_defense'];
		$ch2_conversation = $ch2['conversation_attack'];
	}else if($last['brl_ch1_command']=='회상'){
		$ch1_conversation = $ch1['flashback_text'];
		$ch2_conversation = "";
	}else if($last['brl_ch2_command']=='회상'){
		$ch1_conversation = "";
		$ch2_conversation = $ch2['flashback_text'];
	}
}

?>
<link rel="stylesheet" href="/css/battle.css">

<!-- 배경 -->
<div id="battle-log-bg">
	<div id="bg-left" class="bg-box" style="background-image:url(<?=$room['ch1_head']?>)">
		<span id="ch1_name" class="bg-name"><?=strtoupper($ch1['eng_name'])?></span>
	</div>
	<div id="bg-right" class="bg-box" style="background-image:url(<?=$room['ch2_head']?>)">
		<span id="ch2_name" class="bg-name"><?=strtoupper($ch2['eng_name'])?></span>
	</div>
</div>

<!-- 컴포넌트 -->
<div id="battle-log-box">

	<!-- 체력 및 에너지 영역 -->
	<div id="vital-wrap">
		<div id="vital-left" class="vital-vertical">
			<div class="vital-name-wrap">
				<span class="vital-name"><?=$room['ch1_name']?></span>
			</div>
			<div class="vital-hp-bar-wrap">
				<div class="hp-bar-bg">
					<div class="hp-bar-fill" style="flex-grow:<?=100 - $ch1_hp_rate?>" ></div>
					<div class="hp-bar-void" style="flex-grow:<?=$ch1_hp_rate?>" ></div>
				</div>
			</div>
			<div class="energy-bar-wrap">
				<div class="energy-bar-bg">
					<div class="energy-bar-box">
						<div class="energy-item <?=$room['br_ch1_energy']>=1?'active':''?>"></div>
						<div class="energy-item <?=$room['br_ch1_energy']>=2?'active':''?>"></div>
						<div class="energy-item <?=$room['br_ch1_energy']>=3?'active':''?>"></div>
					</div>
				</div>
			</div>
		</div>
		<div id="vital-right" class="vital-vertical">
			<div class="vital-name-wrap">
				<div class="vital-name-padding"></div>
				<div class="vital-name-box">
				<span class="vital-name"><?=$room['ch2_name']?></span>
				</div>
			</div>
			<div class="vital-hp-bar-wrap">
				<div class="hp-bar-bg">
					<div class="hp-bar-fill" style="flex-grow:<?=100 - $ch2_hp_rate?>" ></div>
					<div class="hp-bar-void" style="flex-grow:<?=$ch2_hp_rate?>" ></div>
				</div>
			</div>
			<div class="energy-bar-wrap">
				<div class="energy-bar-bg">
					<div class="energy-bar-box">
						<div class="energy-item <?=$room['br_ch2_energy']>=1?'active':''?>"></div>
						<div class="energy-item <?=$room['br_ch2_energy']>=2?'active':''?>"></div>
						<div class="energy-item <?=$room['br_ch2_energy']>=3?'active':''?>"></div>
					</div>
				</div>
			</div>
		</div>
		<a onClick="window.location.reload()" style="cursor: pointer;"><div class="refresh"></div></a>
	</div>

	<!-- 텍스트 전투 로그 영역 -->
	<div id="text-log-wrap">
		<div id="flashback-wrap">
			<div class="flashback-box">
				<div class="flashback" style="background-image: url(<?=$character['flashback_img']?>);"></div>
			</div>
		</div>
		<div class="text-log-border">
		<?for ($i=0; $i < sizeof($room_log_arr); $i++) {
			$is_ch1 = $room_log_arr[$i]['brl_ch1_command']!="";
			$thumb = $is_ch1 ? $room['ch1_thumb'] : $room['ch2_thumb'];
			$command = $is_ch1 ? $room_log_arr[$i]['brl_ch1_command'] : $room_log_arr[$i]['brl_ch2_command'];
			$text = $is_ch1 ? $room_log_arr[$i]['brl_ch1_text'] : $room_log_arr[$i]['brl_ch2_text'];
			$direction = $is_ch1 ? '' : 'reverse';

			if($command=='회상'){
		?>
			<div class="text-log-box flashback <?=$direction?>">
				<div class="log-box-padding"></div>
				<div class="log-box-thumbnail-border">
					<div class="log-box-thumbnail" style="background-image:url(<?=$thumb?>)"></div>
				</div>
				<div class="log-box-text-box">
					<p class="log-command">[<?=$command?>]</p>
					<p class="log-text"><?=$text?></p>
				</div>
				<div class="log-flashback-box">
					<div class="log-flashback-img" style="background-image:url(<?=$is_ch1?$ch1['flashback_img']:$ch2['flashback_img']?>)"></div>
					<div class="log-flashback-speech"></div>
				</div>
			</div>
			<div class="text-log-box <?=$direction?>">
				<div class="log-box-padding"></div>
				<div class="log-box-thumbnail-border">
					<div class="log-box-thumbnail" style="background-image:url(<?=$thumb?>)"></div>
				</div>
				<div class="log-box-text-box">
					<p class="log-command">[<?=$command?>]</p>
					<p class="log-text">회상카드를 사용했습니다</p>
				</div>
			</div>
		<?
			}else{ //그 외의 커맨드
		?>
			<div class="text-log-box <?=$direction?>">
				<div class="log-box-padding"></div>
				<div class="log-box-thumbnail-border">
					<div class="log-box-thumbnail" style="background-image:url(<?=$thumb?>)"></div>
				</div>
				<div class="log-box-text-box">
					<p class="log-command">[<?=$command?>]</p>
					<p class="log-text"><?=$text?></p>
				</div>
			</div>
		<?}}?>
		<?if($room['br_status']=='KNOCKOUT') echo "<div class=\"text-log-box knockout\"><span>전투가 종료되었습니다!</span></div>";?>
		</div>
	</div>

	<!-- 조작 영역 -->
	<div id="command-turn-wrap">
		<div class="command-box">
			<div class="command-item" data-command="공격"><span>공격</span></div>
			<div class="command-item" data-command="회상"><span>회상</span></div>
			<div class="command-item" data-command="필살기"><span>필살기</span></div>
			<div class="command-item" data-command="아이템"><span>아이템</span></div>
		</div>
		<?if($room['br_status']!='KNOCKOUT'){?>
		<div class="turn-box"><span>현재 <?=$is_my_turn?'당신':'상대'?>의 턴입니다</span></div>
		<?}?>
	</div>

	<!-- 캐릭터 대사 출력 영역 -->
	<div id="conversation-wrap">
		<div id="conversation-left-wrap">
			<div class="conversation-box"><p><?=$ch1_conversation?></p></div>
			<div class="conversation-tail"></div>
			<div class="conversation-tail"></div>
		</div>
		<div id="conversation-right-wrap">
			<div class="conversation-box"><p><?=$ch2_conversation?></p></div>
			<div class="conversation-tail"></div>
			<div class="conversation-tail"></div>
		</div>
	</div>

	<div id="inven-wrap">
		<div class="inven-title">
			<span class='text'>보유 아이템 목록</span>
			<span id='inven-close'>닫기</span>
		</div>
		<div class="inven-box">
			<?for ($i=0; $i < sizeof($inventory); $i++) {
				echo '<div class="inven-item" data-in_id="'.$inventory[$i]['in_id'].'">';
				echo '<img src = "'.$inventory[$i]['it_img'].'"/>';
				echo '<span>'.$inventory[$i]['it_name'].'</span>';
				echo '</div>';
			}?>
		</div>
	</div>

</div>

<style>
#body > div.fix-layout { position: relative; height: 952px; background-color: #888; }
</style>

<script>
<?if($br_id) echo "const br_id = {$br_id};\n"?>
<?if($character['ch_id']) echo "const ch_id = {$character['ch_id']};\n"?>
<?if($ch1_hp_rate) echo "const ch1_hp_rate = {$ch1_hp_rate};\n"?>
<?if($ch2_hp_rate) echo "const ch2_hp_rate = {$ch2_hp_rate};\n"?>

$().ready(()=>{

	if(ch1_hp_rate <= 0) {
		$('#bg-left').css({'filter':'grayscale(100%)'})
	}
	if(ch2_hp_rate <= 0) {
		$('#bg-right').css({'filter':'grayscale(100%)'})
	}

	$('#inven-close').on('click', ()=>$('#inven-wrap').removeClass('active'))

	$('.command-item').on('click', function(){
		const $command = $(this)?.data('command') ?? '';
		if($command==='아이템') {
			return $('#inven-wrap').addClass('active');
		}

		let url = "/ajax/yhsb/battle_command.php"
		const data = {
			command: $command,
			br_id: br_id,
			ch_id: ch_id,
		}

		$.ajax({
			type: 'post', async: true, url: url,
			data: data,
			success: (res)=>{
				location.reload()
			},
			error: (e)=>{
				alert(e?.responseText ?? '오류가 발생했습니다.')
				location.reload()
			 }
		})
	})

	$('.inven-item').on('click', function(){
		const in_id = $(this).data('in_id');
		console.log(in_id);

		let url = "/ajax/yhsb/battle_command.php"
		const data = {
			command: '아이템',
			br_id: br_id,
			ch_id: ch_id,
			in_id: in_id,
		}

		$.ajax({
			type: 'post', async: true, url: url,
			data: data,
			success: (res)=>{
				location.reload()
			},
			error: (e)=>{
				alert(e?.responseText ?? '오류가 발생했습니다.')
				location.reload()
			 }
		})
	})
})
</script>