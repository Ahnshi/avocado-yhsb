<?php
$sub_menu = "920300";
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

		$sql = " select * from avo_character_side_score where sis_id = '{$_POST['sis_id'][$k]}' ";
		$row = sql_fetch($sql);

		if (!$row['sis_id']) {
			$msg .= $row['sis_id'].' : 기존 자료가 존재하지 않습니다.\\n';
		} else {
			$sql = "
				UPDATE avo_character_side_score SET
					si_id = '{$_POST['si_id'][$k]}',
					sis_score_1 = '{$_POST['sis_score_1'][$k]}',
					sis_score_2 = '{$_POST['sis_score_2'][$k]}'
				WHERE sis_id = '{$_POST['sis_id'][$k]}';
			";
			sql_query($sql);
		}
	}
} else if ($_POST['act_button'] == "선택삭제") {
	for ($i=0; $i<$count; $i++){

		$k = $_POST['chk'][$i]; // 실제 번호를 넘김

		$sql = " select * from avo_character_side_score where sis_id = '{$_POST['sis_id'][$k]}' ";
		$row = sql_fetch($sql);

		if (!$row['sis_id']) {
			$msg .= $row['sis_id'].' : 기존 자료가 존재하지 않습니다.\\n';
		} else {
			$sql = "
				DELETE FROM avo_character_side_score
				WHERE sis_id = '{$_POST['sis_id'][$k]}';
			";
			sql_query($sql);
		}
	}
}

if ($msg)
	alert($msg);
goto_url('./side_score_list.php?'.$qstr);

?>