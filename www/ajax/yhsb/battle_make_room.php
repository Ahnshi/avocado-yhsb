<?
include_once('./_common.php');

if(!$_POST['br_ch1_id']||!$_POST['br_ch2_id']){
	http_response_code(400); echo '결투를 신청할 상대를 선택해주세요'; exit;
}

$sql_ch1 = " SELECT ch.* FROM avo_character ch WHERE ch.ch_id= '{$_POST['br_ch1_id']}';";
$result = sql_query($sql_ch1);
$ch1 = sql_fetch_array($result);
$ch1['ch_hp'] = get_status_extra('hp', $ch1['ch_id'])['value'];

$sql_ch2 = " SELECT ch.* FROM avo_character ch WHERE ch.ch_id= '{$_POST['br_ch2_id']}';";
$result = sql_query($sql_ch2);
$ch2 = sql_fetch_array($result);
$ch2['ch_hp'] = get_status_extra('hp', $ch2['ch_id'])['value'];

// VALIDATION
if(!$ch1) {
	http_response_code(400); echo '캐릭터가 존재하지 않습니다.'; exit;
}else if($ch1['ch_hp'] <= 0){
	http_response_code(400); echo '캐릭터의 체력이 0입니다. 스탯을 분배해주세요.'; exit;
}else if(!$ch2) {
	http_response_code(400); echo '상대 캐릭터가 존재하지 않습니다.'; exit;
}else if($ch2['ch_hp'] <= 0){
	http_response_code(400); echo '상대가 현재 전투 가능한 상황이 아닙니다(체력 부족)'; exit;
}else if($_POST['br_ch1_id']==$_POST['br_ch2_id']){
	http_response_code(400); echo '서로 다른 두 명의 캐릭터를 선택해주세요'; exit;
}else if(get_is_weekend()){
	http_response_code(400); echo '주말에는 전투 신청이 불가능합니다'; exit;
}else if(get_today_battle_req_count($_POST['br_ch1_id'])>=3){
	http_response_code(400); echo '하루에 세 번까지 전투 신청이 가능합니다'; exit;
}

// MAKE BATTLE ROOM
$sql = "
	INSERT INTO avo_battle_room SET
	br_ch1_id = {$_POST['br_ch1_id']},
	br_ch1_hp = {$ch1['ch_hp']},
	br_ch2_id = {$_POST['br_ch2_id']},
	br_ch2_hp = {$ch2['ch_hp']},
	br_status = 'READY'
";

$coin = rand(0,1); // 선,후공 결정
$sql = $coin ? $sql.", br_ch1_turn = TRUE" : $sql.", br_ch2_turn = TRUE";

sql_query($sql);
$idx = sql_insert_id($g5['connect_db']);

echo '{"br_id":'.$idx.'}';
return;
?>