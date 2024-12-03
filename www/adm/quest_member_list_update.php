<?php
$sub_menu = "600100";
include_once('./_common.php');

check_demo();

if (!count($_POST['chk'])) {
	alert($_POST['act_button']." 하실 항목을 하나 이상 체크하세요.");
}

auth_check($auth[$sub_menu], 'w');

if ($_POST['act_button'] == "선택수정") {

	for ($i=0; $i<count($_POST['chk']); $i++)
	{
		// 실제 번호를 넘김
		$k = $_POST['chk'][$i];
		$sql = " update {$g5['quest_member_table']}
					set qm_log = '{$_POST['qm_log'][$k]}'
					where qm_id = '{$_POST['qm_id'][$k]}' ";
		sql_query($sql);
	}

} else if($_POST['act_button'] == "선택삭제") {

	for ($i=0; $i<count($_POST['chk']); $i++)
	{
		// 실제 번호를 넘김
		$k = $_POST['chk'][$i];
		sql_query(" delete from {$g5['quest_member_table']} where qm_id = '{$_POST['qm_id'][$k]}' ");
	}
}
if ($msg)
	alert($msg);

goto_url('./quest_member_list.php?qu_id='.$qu_id);
?>
