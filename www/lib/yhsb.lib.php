<?php
if (!defined('_GNUBOARD_')) exit;

// 캐릭터의 순위를 가져옵니다
function get_character_ranking($ch_id) {

	$sql = "
		SELECT (RANK() OVER (ORDER BY ch_score DESC)) as score_ranking, ch.*
		FROM yhsb.avo_character ch;
	";
	$result = sql_query($sql);

	$val = 0;
	for ($i=0; $row = sql_fetch_array($result); $i++) {
		if($row['ch_id'] == $ch_id){
			$val = $row['score_ranking'];
		}
	}

	return $val;

}

// 순위 정보를 포함한 전체 캐릭터들의 목록을 가져옵니다
function get_character_ranking_list() {

	$sql = "
		SELECT (RANK() OVER (ORDER BY ch_score DESC)) as score_ranking, ch.*, cs.si_name as si_name, lv.lv_name as lv_name
		FROM yhsb.avo_character ch
		LEFT JOIN avo_character_side cs ON ch.ch_side = cs.si_id		
		LEFT JOIN avo_level_setting lv ON ch.ch_rank = lv.lv_id
		;
	";
	$result = sql_query($sql);

	$arr = array();
	for ($i=0; $row = sql_fetch_array($result); $i++) { array_push($arr, $row); }

	return $arr;

}


// 추가 정보를 포함한 캐릭터의 전체 정보를 불러옵니다
function get_character_full_info($ch_id) {
	global $g5;

	$ch = get_character($ch_id);
	$av_result = sql_query("SELECT * FROM {$g5['value_table']} WHERE ch_id = '{$ch_id}'");
	for($i = 0; $row = sql_fetch_array($av_result); $i++) {
		$ch[$row['ar_code']] = $row['av_value'];
	}
	$ch_ranking = get_character_ranking($ch_id);
	$ch['ch_ranking'] = $ch_ranking;
	$ch['mb_nick'] = get_member_name($ch['mb_id']); // 24.11.29 2z 멤버닉네임 정보 추가

	return $ch;
}

// 진영의 점수를 가져옵니다
function get_side_score_1($si_id) {
	global $g5;

	$result = sql_fetch("
    SELECT si.*, IFNULL(SUM(sis.sis_score_1), 0) as sis_score_1, IFNULL(SUM(sis.sis_score_2), 0) as sis_score_2
    FROM {$g5['side_table']} si
    LEFT JOIN avo_character_side_score sis
    ON si.si_id = sis.si_id
    GROUP BY si.si_id
    HAVING si.si_id = {$si_id};
	");
	$val = $result['sis_score_1'];
	if(!$val) $val = 0;

	return $val;
}

// 진영의 점수를 가져옵니다(예비)
function get_side_score_2($si_id) {
	global $g5;

	$result = sql_fetch("
    SELECT si.*, IFNULL(SUM(sis.sis_score_1), 0) as sis_score_1, IFNULL(SUM(sis.sis_score_2), 0) as sis_score_2
    FROM {$g5['side_table']} si
    LEFT JOIN avo_character_side_score sis
    ON si.si_id = sis.si_id
    GROUP BY si.si_id
    HAVING si.si_id = {$si_id};
	");
	$val = $result['sis_score_2'];
	if(!$val) $val = 0;

	return $val;
}

// br_id를 기준으로 전투장을 불러옵니다
function get_battle_room($br_id) {

	$result = sql_fetch("
		SELECT br.*,
			(br.br_ch1_hp - IFNULL(brl_ch1_damaged, 0)) AS ch1_current_hp, (br.br_ch2_hp - IFNULL(brl_ch2_damaged, 0)) AS ch2_current_hp,
			ch1.ch_name AS ch1_name, ch1.ch_thumb AS ch1_thumb, ch1.ch_head AS ch1_head, ch1.ch_body AS ch1_body, ch1.ch_side AS ch1_side,
			ch2.ch_name AS ch2_name, ch2.ch_thumb AS ch2_thumb, ch2.ch_head AS ch2_head, ch2.ch_body AS ch2_body, ch2.ch_side AS ch2_side
		FROM avo_battle_room br
		LEFT JOIN avo_character ch1 ON br.br_ch1_id = ch1.ch_id
		LEFT JOIN avo_character ch2 ON br.br_ch2_id = ch2.ch_id
		LEFT JOIN (
			SELECT brl.br_id, SUM(brl_ch1_damaged) AS brl_ch1_damaged, SUM(brl_ch2_damaged) AS brl_ch2_damaged
				FROM avo_battle_room_log brl
			GROUP BY br_id
		) brl ON br.br_id = brl.br_id
		WHERE br.br_id = {$br_id};
	");

	return $result;

}

// 진행중인 전투장의 목록을 불러옵니다
function get_battle_room_list() {

	$sql = "
		SELECT br.*,
			(br.br_ch1_hp - IFNULL(brl_ch1_damaged, 0)) AS ch1_current_hp, (br.br_ch2_hp - IFNULL(brl_ch2_damaged, 0)) AS ch2_current_hp,
			ch1.ch_name AS ch1_name, ch1.ch_thumb AS ch1_thumb, ch1.ch_head AS ch1_head, ch1.ch_body AS ch1_body, ch1.ch_side AS ch1_side,
			ch2.ch_name AS ch2_name, ch2.ch_thumb AS ch2_thumb, ch2.ch_head AS ch2_head, ch2.ch_body AS ch2_body, ch2.ch_side AS ch2_side
		FROM avo_battle_room br
		LEFT JOIN avo_character ch1 ON br.br_ch1_id = ch1.ch_id
		LEFT JOIN avo_character ch2 ON br.br_ch2_id = ch2.ch_id
		LEFT JOIN (
			SELECT brl.br_id, SUM(brl_ch1_damaged) AS brl_ch1_damaged, SUM(brl_ch2_damaged) AS brl_ch2_damaged
				FROM avo_battle_room_log brl
			GROUP BY br_id
		) brl ON br.br_id = brl.br_id
		WHERE br_status != '' AND br_status != 'KNOCKOUT'
		ORDER BY br.br_id DESC;
	";
	$result = sql_query($sql);

	$arr = array();
	for ($i=0; $row = sql_fetch_array($result); $i++) { array_push($arr, $row); }

	return $arr;

}

// 종료된 전투장의 목록을 불러옵니다
function get_battle_room_ko_list() {

	$sql = "
		SELECT br.*,
			(br.br_ch1_hp - IFNULL(brl_ch1_damaged, 0)) AS ch1_current_hp, (br.br_ch2_hp - IFNULL(brl_ch2_damaged, 0)) AS ch2_current_hp,
			ch1.ch_name AS ch1_name, ch1.ch_thumb AS ch1_thumb, ch1.ch_head AS ch1_head, ch1.ch_body AS ch1_body, ch1.ch_side AS ch1_side,
			ch2.ch_name AS ch2_name, ch2.ch_thumb AS ch2_thumb, ch2.ch_head AS ch2_head, ch2.ch_body AS ch2_body, ch2.ch_side AS ch2_side
		FROM avo_battle_room br
		LEFT JOIN avo_character ch1 ON br.br_ch1_id = ch1.ch_id
		LEFT JOIN avo_character ch2 ON br.br_ch2_id = ch2.ch_id
		LEFT JOIN (
			SELECT brl.br_id, SUM(brl_ch1_damaged) AS brl_ch1_damaged, SUM(brl_ch2_damaged) AS brl_ch2_damaged
				FROM avo_battle_room_log brl
			GROUP BY br_id
		) brl ON br.br_id = brl.br_id
		WHERE br_status = 'KNOCKOUT'
		ORDER BY br.br_id DESC;
	";
	$result = sql_query($sql);

	$arr = array();
	for ($i=0; $row = sql_fetch_array($result); $i++) { array_push($arr, $row); }

	return $arr;

}

// 전투장아이디(br_id) 기준으로 전투 기록을 불러옵니다
function get_battle_room_log_list($br_id) {

	$sql = "
		SELECT
			brl.brl_id,
			brl.brl_ch1_command,
			brl.brl_ch1_damaged,
			brl.brl_ch1_used_item_id,
			brl.brl_ch1_text,
			brl.brl_ch2_command,
			brl.brl_ch2_damaged,
			brl.brl_ch2_used_item_id,
			brl.brl_ch2_text,
			brl.brl_created_datetime,
			br.*
		FROM avo_battle_room_log brl
		LEFT JOIN (
			SELECT br.*,
				ch1.ch_name AS ch1_name, ch1.ch_thumb AS ch1_thumb, ch1.ch_head AS ch1_head, ch1.ch_body AS ch1_body, ch1.ch_side AS ch1_side,
				ch2.ch_name AS ch2_name, ch2.ch_thumb AS ch2_thumb, ch2.ch_head AS ch2_head, ch2.ch_body AS ch2_body, ch2.ch_side AS ch2_side
			FROM avo_battle_room br
				LEFT JOIN avo_character ch1 ON br.br_ch1_id = ch1.ch_id
				LEFT JOIN avo_character ch2 ON br.br_ch2_id = ch2.ch_id
		) br ON brl.br_id = br.br_id
		WHERE brl.br_id = {$br_id};
	";
	$result = sql_query($sql);

	$arr = array();
	for ($i=0; $row = sql_fetch_array($result); $i++) { array_push($arr, $row); }

	return $arr;

}

// 보유 아이템 목록을 불러옵니다
function get_inven_list($ch_id){
	global $g5;

	$inven_list = array();
	$p_count = 0;

	// 개인 아이템 - 선물
	$pe_inven_sql = "select * from {$g5['inventory_table']} inven, {$g5['item_table']} item where inven.ch_id = '$ch_id' and item.it_id = inven.it_id and inven.se_ch_id != '' order by inven.it_id asc";
	$pe_inven_result = sql_query($pe_inven_sql);
	for($i=0; $row=sql_fetch_array($pe_inven_result); $i++) {
		$inven_list[$p_count] = $row;
		$p_count++;
	}

	// 개인 아이템 - 비선물
	$pe_inven_sql = "select *, count(*) as cnt from {$g5['inventory_table']} inven, {$g5['item_table']} item where inven.ch_id = '$ch_id' and item.it_id = inven.it_id and inven.se_ch_id = '' group by inven.it_id order by inven.it_id asc";
	$pe_inven_result = sql_query($pe_inven_sql);
	for($i; $row=sql_fetch_array($pe_inven_result); $i++) {
		$inven_list[$p_count] = $row;
		$p_count++;
	}
	$i = 0;

	return $inven_list;
}

// 보유 아이템 중 포션(에너지/체력회복) 목록을 불러옵니다
function get_inven_potion_list($ch_id){
	global $g5;

	$inven_list = array();
	$p_count = 0;

	// 개인 아이템 - 선물
	$pe_inven_sql = "select * from {$g5['inventory_table']} inven, {$g5['item_table']} item where inven.ch_id = '$ch_id' and item.it_id = inven.it_id and inven.se_ch_id != '' and item.it_type IN ('에너지회복', '체력회복') order by inven.it_id asc";
	$pe_inven_result = sql_query($pe_inven_sql);
	for($i=0; $row=sql_fetch_array($pe_inven_result); $i++) {
		$inven_list[$p_count] = $row;
		$p_count++;
	}

	// 개인 아이템 - 비선물
	$pe_inven_sql = "select *, count(*) as cnt from {$g5['inventory_table']} inven, {$g5['item_table']} item where inven.ch_id = '$ch_id' and item.it_id = inven.it_id and inven.se_ch_id = '' and item.it_type IN ('에너지회복', '체력회복') group by inven.it_id order by inven.it_id asc";
	$pe_inven_result = sql_query($pe_inven_sql);
	for($i; $row=sql_fetch_array($pe_inven_result); $i++) {
		$inven_list[$p_count] = $row;
		$p_count++;
	}
	$i = 0;

	return $inven_list;
}

?>