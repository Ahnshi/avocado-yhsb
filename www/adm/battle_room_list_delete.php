<?php
$sub_menu = "920100";
include_once('./_common.php');

check_demo();
auth_check($auth[$sub_menu], 'w');
check_admin_token();

$count = count($_POST['chk']);
if (!count($_POST['chk'])) {
	alert($_POST['act_button']." 하실 항목을 하나 이상 체크하세요.");
}

if ($_POST['act_button'] == "선택수정") {
	for ($i=0; $i<$count; $i++){

		$k = $_POST['chk'][$i]; // 실제 번호를 넘김

		$sql = "SELECT * FROM avo_battle_room WHERE br_id = '{$_POST['br_id'][$k]}' ";
		$row = sql_fetch($sql);

		if (!$row['br_id']) {
			$msg .= $row['br_id'].' : 기존 자료가 존재하지 않습니다.\\n';
		} else {
			$sql = "
				UPDATE avo_battle_room SET
					br_ch1_id = '{$_POST['br_ch1_id'][$k]}',
					br_ch1_hp = '{$_POST['br_ch1_hp'][$k]}',
					br_ch1_energy = '{$_POST['br_ch1_energy'][$k]}',
					br_ch1_skill_used = '{$_POST['br_ch1_skill_used'][$k]}',
					br_ch1_potion_used = '{$_POST['br_ch1_potion_used'][$k]}',
					br_ch1_dmg_cut_rate = '{$_POST['br_ch1_dmg_cut_rate'][$k]}',
					br_ch2_id = '{$_POST['br_ch2_id'][$k]}',
					br_ch2_hp = '{$_POST['br_ch2_hp'][$k]}',
					br_ch2_energy = '{$_POST['br_ch2_energy'][$k]}',
					br_ch2_skill_used = '{$_POST['br_ch2_skill_used'][$k]}',
					br_ch2_potion_used = '{$_POST['br_ch2_potion_used'][$k]}',
					br_ch2_dmg_cut_rate = '{$_POST['br_ch2_dmg_cut_rate'][$k]}',
					br_status = '{$_POST['br_status'][$k]}'
				WHERE br_id = '{$_POST['br_id'][$k]}';
			";
			sql_query($sql);
			print_r($sql);
		}
	}
} else if ($_POST['act_button'] == "선택삭제") {
	for ($i=0; $i<$count; $i++){

		$k = $_POST['chk'][$i]; // 실제 번호를 넘김

		$sql = "SELECT * FROM avo_battle_room WHERE br_id = '{$_POST['br_id'][$k]}' ";
		$row = sql_fetch($sql);

		if (!$row['br_id']) {
			$msg .= $row['br_id'].' : 기존 자료가 존재하지 않습니다.\\n';
		} else {
			$sql = "
				DELETE FROM avo_battle_room
				WHERE br_id = '{$_POST['br_id'][$k]}';
			";
			sql_query($sql);
		}
	}
}

if ($msg)
	alert($msg);

goto_url('./battle_room_list.php?'.$qstr);
?>