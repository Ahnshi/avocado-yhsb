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

$qm = sql_fetch("select * from {$g5['quest_member_table']} where qm_id = '{$qm_id}'");


if(!$qm['qm_id']) { 
	// 수행기록이 없을 경우
	alert('해당퀘스트 신청내역이 없습니다.');
}

// 퀘스트 아이템 제출
// - 아이템 선택하지 않을경우 제출 X
// - 선택한 아이템 인벤토리에서 제거
if($qu['qu_it_id'] > 0 ){
	if($sub_qu_it == ""){
		alert("퀘스트에 제출할 아이템을 등록하세요");
	}else {
		sql_query("delete from {$g5['inventory_table']} where in_id = '{$sub_qu_it}'");
	}
}

sql_query("update {$g5['quest_member_table']} set qm_log = '{$qm_log}', qm_state = 'E' where qm_id = '{$qm_id}'");
$msg = "제출이 완료되었습니다.";


// 보상 획득 부분
if($qu['it_id']) { 
	// 아이템 획득
	// 아이템 획득에 성공한 경우, 해당 아이템을 인벤토리에 삽입
	// 아이템 획득에 성공 시
	$item_result = get_item($qu['it_id']);
	insert_inventory($character['ch_id'], $item_result['it_id'], $item_result);
}
if($qu['qu_point']) { 
	// 소지금 변동
	$m_re_type = $config['cf_money_pice'];
	insert_point($member['mb_id'], $qu['qu_point'], $qu['qu_title'], 'money', time(), '이벤트');
}
if($qu['qu_exp']) { 
	// 경험치 변동
	$m_re_type = $config['cf_exp_pice'];
	$exp_content=$qu['qu_title'];

	$action = '획득';
	if($qu['qu_exp'] < 0) {
		$action = '차감';
	}
	insert_exp($character['ch_id'], $qu['qu_exp'], $exp_content, $action);
}

if($list == '1') { 
	alert($msg,'./index.php');
} else {
	alert($msg,'./index.php?qu_id='.$qu_id);
}
?>
