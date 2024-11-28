<?php
$sub_menu = "920200";
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

		$sql = "SELECT * FROM avo_battle_room_log WHERE brl_id = '{$_POST['brl_id'][$k]}' ";
		$row = sql_fetch($sql);

		if (!$row['brl_id']) {
			$msg .= $row['brl_id'].' : 기존 자료가 존재하지 않습니다.\\n';
		} else {
			$sql = "
				UPDATE avo_battle_room_log SET
					br_id = '{$_POST['br_id'][$k]}',
					brl_ch1_command = '{$_POST['brl_ch1_command'][$k]}',
					brl_ch1_damaged = '{$_POST['brl_ch1_damaged'][$k]}',
					brl_ch1_used_item_id = '{$_POST['brl_ch1_used_item_id'][$k]}',
					brl_ch1_text = '{$_POST['brl_ch1_text'][$k]}',
					brl_ch2_command = '{$_POST['brl_ch2_command'][$k]}',
					brl_ch2_damaged = '{$_POST['brl_ch2_damaged'][$k]}',
					brl_ch2_used_item_id = '{$_POST['brl_ch2_used_item_id'][$k]}',
					brl_ch2_text = '{$_POST['brl_ch2_text'][$k]}'
				WHERE brl_id = '{$_POST['brl_id'][$k]}';
			";
			sql_query($sql);
			print_r($sql);
		}
	}
} else if ($_POST['act_button'] == "선택삭제") {
	for ($i=0; $i<$count; $i++){

		$k = $_POST['chk'][$i]; // 실제 번호를 넘김

		$sql = "SELECT * FROM avo_battle_room_log WHERE brl_id = '{$_POST['brl_id'][$k]}' ";
		$row = sql_fetch($sql);

		if (!$row['brl_id']) {
			$msg .= $row['brl_id'].' : 기존 자료가 존재하지 않습니다.\\n';
		} else {
			$sql = "
				DELETE FROM avo_battle_room_log
				WHERE brl_id = '{$_POST['brl_id'][$k]}';
			";
			sql_query($sql);
		}
	}
}

if ($msg)
	alert($msg);

goto_url('./battle_room_log_list.php?'.$qstr);
?>