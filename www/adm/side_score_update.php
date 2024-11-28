<?php
$sub_menu = "400610";
include_once('./_common.php');

check_demo();
auth_check($auth[$sub_menu], 'w');
check_admin_token();

$sql = "
	INSERT INTO avo_character_side_score SET
		si_id = '{$_POST['si_id']}',
		sis_score_1 = '{$_POST['sis_score_1']}',
		sis_score_2 = '{$_POST['sis_score_2']}'
";

sql_query($sql);

goto_url('./side_score_list.php?'.$qstr);
?>