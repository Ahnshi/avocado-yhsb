<?php
$sub_menu = '600200';
include_once('./_common.php');

check_demo();

if (!count($_POST['chk'])) {
	alert($_POST['act_button']." 하실 항목을 하나 이상 체크하세요.");
}

auth_check($auth[$sub_menu], 'w');

$count = count($_POST['chk']);
if ($_POST['act_button'] == "선택수정") {
	for ($i=0; $i<$count; $i++)
	{
		// 실제 번호를 넘김
		$k = $_POST['chk'][$i];
		$sql = " update avo_school_meal
					set sm_name = '{$_POST['sm_name'][$k]}',
						sm_cate = '{$_POST['sm_cate'][$k]}' ";
		
		$sql .= "   where sm_id = '{$_POST['sm_id'][$k]}' ";

		sql_query($sql);
	}
} else if ($_POST['act_button'] == "선택삭제") {
	
	for ($i=0; $i<$count; $i++)
	{
		// 실제 번호를 넘김
		$k = $_POST['chk'][$i];
		
		$sql = " delete from avo_school_meal where sm_id = '{$_POST['sm_id'][$k]}'";

		sql_query($sql);
	}
}

if ($msg)
	alert($msg);

goto_url('./school_meal_list.php?'.$qstr);
?>