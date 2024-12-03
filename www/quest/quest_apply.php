<?php
include_once('./_common.php');

$qu = sql_fetch("select * from {$g5['quest_table']} where qu_id = '{$qu_id}'");
if(!$qu['qu_id']) { 
	alert("퀘스트 정보가 존재하지 않습니다.");
}
$now = date('Y-m-d');
if($now < $qu['qu_sdate'] || $now > $qu['qu_edate']) { 
	alert("퀘스트 수행기간이 아닙니다.");
}

$is_apply = false;
$check = sql_fetch("select count(*) as cnt from {$g5['quest_member_table']} where qu_id = '{$qu_id}' and ch_id = '{$character['ch_id']}'");
if($check['cnt'] == 0) {
	$is_apply = true;
}


if($type == 'cancle') { 
	// 수행기록이 없을 경우
	if($is_apply) { 
		alert('해당퀘스트 신청내역이 없습니다.');
	}

	sql_query("delete from {$g5['quest_member_table']} where ch_id = '{$character['ch_id']}' and qu_id = '{$qu_id}'");
	$msg = '퀘스트를 취소하였습니다.';
} else {
	if(!$is_apply) { 
		alert('이미 수락한 퀘스트 입니다.');
	}
	// 퀘스트 수행정보 저장 (state 정보; R:대기중 / E : 완료 / F : 실패)
	sql_query(" insert into {$g5['quest_member_table']} set mb_id = '{$member['mb_id']}', qu_id = '{$qu_id}', ch_id = '{$character['ch_id']}', qm_state = 'R'");
	$msg = '퀘스트를 수락하였습니다.';
}

if($list == '1') { 
	alert($msg,'./index.php');
} else {
	alert($msg,'./index.php?qu_id='.$qu_id);
}
?>
