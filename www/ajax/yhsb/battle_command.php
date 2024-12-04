<?php
include_once('./_common.php');

if($_POST['command']&&$_POST['br_id']&&$_POST['ch_id']){

  $WIN = 10;
  $LOSS = 5;

  $command = $_POST['command'];
  $br_id = $_POST['br_id'];
  $ch_id = $_POST['ch_id'];
  $in_id = $_POST['in_id'];

  $room = get_battle_room($br_id);

  $sql = "SELECT * FROM avo_battle_room_log WHERE br_id = {$br_id}";
  $result = sql_query($sql);
  $room_log = sql_fetch_array($result);

  // VALUES
  $im_ch1 = ($room['br_ch1_id'] == $character['ch_id']);
  $im_ch2 = ($room['br_ch2_id'] == $character['ch_id']);
  $is_my_turn = (($room['br_ch1_id'] == $character['ch_id']) && ($room['br_ch1_turn'])) || (($room['br_ch2_id'] == $character['ch_id']) && ($room['br_ch2_turn']));
  $is_first_turn = !$room_log ? true : false; //선공 시 데미지 감소

  if(!$im_ch1 && !$im_ch2){
    http_response_code(400); echo '내가 소속된 전투장이 아닙니다'; exit;
  }else if($room['br_status']=='KNOCKOUT'){
    http_response_code(400); echo '전투가 종료되었습니다'; exit;
  }else if(!$is_my_turn){
    http_response_code(400); echo '상대의 턴입니다'; exit;
  }else if(get_is_weekend()){
    http_response_code(400); echo '주말에는 전투 활동이 불가능합니다'; exit;
  }

  // 내 캐릭터 정보
  $ch = get_character_full_info($character['ch_id']);

  // 상대 캐릭터 정보
  $re_ch_id = $im_ch1 ? $room['br_ch2_id'] : $room['br_ch1_id'];
  $re_ch = get_character_full_info($re_ch_id);

  // 양측 캐릭터 정보(avo_battle_room 기준)
  $ch1 = get_character_full_info($room['br_ch1_id']);
  $ch2 = get_character_full_info($room['br_ch2_id']);

  // 공격
  if($command=='공격'){

    //1. 'DAMAGE' 연동 스탯 불러오기
    // - 첫 턴인 경우 1/3로 감소
    // - 내 대미지 추가량(회상스킬 사용 시 증가) 반영 -> 평타에만 반영되며 이 수치가 있을 경우 필살기 사용 불가
    // - 상대의 대미지 경감률(회상스킬 사용 시 증가) 반영

    //2. 대미지 적용 후 변동된 체력 계산
    // - 0 이하로 내려갈 경우 0으로

    //3. 커맨드 메시지 작성
    // - "{A}의 공격이 {B}에게 {damage}의 대미지를 입혔습니다.\n현재 {B}의 남은 체력은 {hp}입니다"

    //4. 에너지 증가, 내 대미지 추가량 초기화, 상대의 대미지 경감률 초기화, 내 턴을 false로 변경하고 상대 턴을 true로 변경

    if($im_ch1){

      $brl_ch1_command = $command;

      //1. 'DAMAGE' 연동 스탯 불러오기
      $damage = get_status_extra('DAMAGE', $room['br_ch1_id'])['value'];
      if($is_first_turn) $damage = round($damage / 3);
      $damage = $damage + $room['br_ch1_dmg_buff'];
      $damage = round((100 - $room['br_ch2_dmg_cut_rate'])/100 * $damage);
      $brl_ch2_damaged += $damage;

      //2. 대미지 적용 후 변동된 체력 계산
      $hp = ($room['ch2_current_hp'] - $damage) >= 0 ? $room['ch2_current_hp'] - $damage : 0;

      //3. 커맨드 메시지 작성
      $brl_ch1_text = "{$ch['ch_name']}의 공격이 {$re_ch['ch_name']}에게 {$damage}의 대미지를 입혔습니다.<br/>현재 {$re_ch['ch_name']}의 남은 체력은 {$hp}입니다.";

      //4. 에너지 증가, 내 대미지 추가량 초기화, 상대의 대미지 경감률 초기화, 내 턴을 false로 변경하고 상대 턴을 true로 변경
      $sql = "UPDATE avo_battle_room SET br_ch1_energy = br_ch1_energy + 1, br_ch1_dmg_buff = 0, br_ch2_dmg_cut_rate = 0, br_ch1_turn = FALSE, br_ch2_turn = TRUE WHERE br_id = {$br_id}";
      sql_query($sql);

    }else{

      $brl_ch2_command = $command;

      //1. 'DAMAGE' 연동 스탯 불러오기
      $damage = get_status_extra('DAMAGE', $room['br_ch2_id'])['value'];
      if($is_first_turn) $damage = round($damage / 3);
      $damage = $damage + $room['br_ch2_dmg_buff'];
      $damage = round((100 - $room['br_ch1_dmg_cut_rate'])/100 * $damage);
      $brl_ch1_damaged += $damage;

      //2. 대미지 적용 후 변동된 체력 계산
      $hp = ($room['ch1_current_hp'] - $damage) >= 0 ? $room['ch1_current_hp'] - $damage : 0;

      //3. 커맨드 메시지 작성
      $brl_ch2_text = "{$ch['ch_name']}의 공격이 {$re_ch['ch_name']}에게 {$damage}의 대미지를 입혔습니다.<br/>현재 {$re_ch['ch_name']}의 남은 체력은 {$hp}입니다.";

      //4. 에너지 증가, 내 대미지 추가량 초기화, 상대의 대미지 경감률 초기화, 내 턴을 false로 변경하고 상대 턴을 true로 변경
      $sql = "UPDATE avo_battle_room SET br_ch2_energy = br_ch2_energy + 1, br_ch2_dmg_buff = 0, br_ch1_dmg_cut_rate = 0, br_ch2_turn = FALSE, br_ch1_turn = TRUE WHERE br_id = {$br_id}";
      sql_query($sql);

    }

  }else if($command=='필살기'){

    //0. VALIDATION
    // - 에너지가 3미만일 경우 사용 불가
    // - 이 전투방에서 이미 사용한 경우 사용 불가
    // - 나에게 대미지 버프가 걸려있는 경우(회상 사용 직후로 판단) 사용 불가

    //1. 'DAMAGE' 연동 스탯 불러오기
    // - 대미지 20추가(필살기)
    // - 첫 턴인 경우 1/3로 감소
    // - 상대의 대미지 경감률(회상스킬 사용 시 증가) 반영

    //2. 대미지 적용 후 변동된 체력 계산
    // - 0 이하로 내려갈 경우 0으로

    //3. 커맨드 메시지 작성
    // - "{A}의 필살기가 {B}에게 {damage}의 대미지를 입혔습니다.\n현재 {B}의 남은 체력은 {hp}입니다"

    //4. 에너지 초기화, 상대의 대미지 경감률 초기화, 내 턴을 false로 변경하고 상대 턴을 true로 변경

    if($im_ch1){

      //0. VALIDATION
      if($room['br_ch1_energy']<3){
        http_response_code(400);
        echo '에너지가 부족합니다.';
        exit;
      }else if($room['br_ch1_skill_used']){
        http_response_code(400);
        echo '한 번만 사용할 수 있습니다.';
        exit;
      }else if($room['br_ch1_dmg_buff']>0){
        http_response_code(400);
        echo '회상카드 사용 후에는 필살기 사용이 불가능합니다.';
        exit;
      }

      $brl_ch1_command = $command;

      //1. 'DAMAGE' 연동 스탯 불러오기
      $damage = get_status_extra('DAMAGE', $room['br_ch1_id'], 0, 20)['value']; //필살기는 대미지 20 추가
      if($is_first_turn) $damage = round($damage / 3);
      $damage = round((100 - $room['br_ch2_dmg_cut_rate'])/100 * $damage);
      $brl_ch2_damaged += $damage;

      //2. 대미지 적용 후 변동된 체력 계산
      $hp = ($room['ch2_current_hp'] - $damage) >= 0 ? $room['ch2_current_hp'] - $damage : 0;

      //3. 커맨드 메시지 작성
      $brl_ch1_text = "{$ch['ch_name']}의 필살기가 {$re_ch['ch_name']}에게 {$damage}의 대미지를 입혔습니다.<br/>현재 {$re_ch['ch_name']}의 남은 체력은 {$hp}입니다.";

      //4. 에너지 초기화, 상대의 대미지 경감률 초기화, 필살기 사용 여부 true로 변경, 내 턴을 false로 변경하고 상대 턴을 true로 변경
      $sql = "UPDATE avo_battle_room SET br_ch1_energy = 0, br_ch1_skill_used = 1, br_ch2_dmg_cut_rate = 0, br_ch1_turn = FALSE, br_ch2_turn = TRUE  WHERE br_id = {$br_id}";
      sql_query($sql);

    }else{

      //0. VALIDATION
      if($room['br_ch2_energy']<3){
        http_response_code(400);
        echo '에너지가 부족합니다.';
        exit;
      }else if($room['br_ch2_skill_used']){
        http_response_code(400);
        echo '한 번만 사용할 수 있습니다.';
        exit;
      }else if($room['br_ch2_dmg_buff']>0){
        http_response_code(400);
        echo '회상카드 사용 후에는 필살기 사용이 불가능합니다.';
        exit;
      }

      $brl_ch2_command = $command;

      //1. 'DAMAGE' 연동 스탯 불러오기
      $damage = get_status_extra('DAMAGE', $room['br_ch2_id'], 0, 20)['value']; //필살기는 대미지 20 추가
      if($is_first_turn) $damage = round($damage / 3);
      $damage = round((100 - $room['br_ch1_dmg_cut_rate'])/100 * $damage);
      $brl_ch1_damaged += $damage;

      //2. 대미지 적용 후 변동된 체력 계산
      $hp = ($room['ch1_current_hp'] - $damage) >= 0 ? $room['ch1_current_hp'] - $damage : 0;

      //3. 커맨드 메시지 작성
      $brl_ch2_text = "{$ch['ch_name']}의 필살기가 {$re_ch['ch_name']}에게 {$damage}의 대미지를 입혔습니다.<br/>현재 {$re_ch['ch_name']}의 남은 체력은 {$hp}입니다.";

      //4. 에너지 초기화, 상대의 대미지 경감률 초기화, 필살기 사용 여부 true로 변경, 내 턴을 false로 변경하고 상대 턴을 true로 변경
      $sql = "UPDATE avo_battle_room SET br_ch2_energy = 0, br_ch2_skill_used = 1, br_ch1_dmg_cut_rate = 0, br_ch2_turn = FALSE, br_ch1_turn = TRUE  WHERE br_id = {$br_id}";
      sql_query($sql);

    }

  }else if($command=='회상'){

    //0. VALIDATION
    // - 이미 회상 카드를 사용한 경우 사용 불가

    //1. 커맨드 메시지 작성
    // - 프로필에 등록 된 회상 멘트 불러오기

    //2. 내 대미지 추가량 증가(10), 내 대미지 경감률 증가(50%), 회상 카드 사용 여부 true로 변경

    if($im_ch1){

      //0. VALIDATION
      if($room['br_ch1_flashback_used']){
        http_response_code(400);
        echo '한 번만 사용할 수 있습니다.';
        exit;
      }

      $brl_ch1_command = $command;

      //1. 커맨드 메시지 작성
      $brl_ch1_text = get_character_info($room['br_ch1_id'], 'flashback_text');

      //2. 내 대미지 추가량 증가(10), 내 대미지 경감률 증가(50%), 회상 카드 사용 여부 true로 변경
      $sql = "UPDATE avo_battle_room SET br_ch1_dmg_buff = 10, br_ch1_dmg_cut_rate = 50.00, br_ch1_flashback_used = TRUE WHERE br_id = {$br_id}";
      sql_query($sql);

    }else{

      //0. VALIDATION
      if($room['br_ch2_flashback_used']){
        http_response_code(400);
        echo '한 번만 사용할 수 있습니다.';
        exit;
      }

      $brl_ch2_command = $command;

      //1. 커맨드 메시지 작성
      $brl_ch2_text = get_character_info($room['br_ch2_id'], 'flashback_text');

      //2. 내 대미지 추가량 증가(10), 내 대미지 경감률 증가(50%), 회상 카드 사용 여부 true로 변경
      $sql = "UPDATE avo_battle_room SET br_ch2_dmg_buff = 10, br_ch2_dmg_cut_rate = 50.00, br_ch2_flashback_used = TRUE WHERE br_id = {$br_id}";
      sql_query($sql);

    }

  }else if($command=='아이템' && $in_id){

    $in = get_inventory_item($in_id);
    if(!$in){ http_response_code(400); echo '오류가 발생했습니다'; exit; }

    if($im_ch1){

      if($room['br_ch1_potion_used']){ http_response_code(400); echo '물약은 전투당 한 번만 사용 가능합니다'; exit; }

      $brl_ch1_command = $command;
      $brl_ch1_text = "{$in['it_name']}을 사용했습니다.";

      if($in['it_type']=='체력회복'){ //'체력회복약'
        $brl_ch1_damaged = $in['it_value'] * -1;
      }else if($in['it_type']=='에너지회복'){ //'에너지회복약'
        $br_ch1_energy = $room['br_ch1_energy'] + 1;
      }else{
        http_response_code(400); echo '사용할 수 없는 아이템입니다'; exit;
      }
      delete_inventory($in['in_id'], 0);

      $sql = "UPDATE avo_battle_room SET br_ch1_potion_used = TRUE";
      if(isset($br_ch1_energy)) $sql = $sql.", br_ch1_energy = '{$br_ch1_energy}'";
      $sql = $sql."WHERE br_id = {$br_id}";

      sql_query($sql);

    }else{

      if($room['br_ch2_potion_used']){ http_response_code(400); echo '물약은 전투당 한 번만 사용 가능합니다'; exit; }

      $brl_ch2_command = $command;
      $brl_ch2_text = "{$in['it_name']}을 사용했습니다.";

      if($in['it_type']=='체력회복'){ //'체력회복약'
        $brl_ch2_damaged = $in['it_value'] * -1;
      }else if($in['it_type']=='에너지회복'){ //'에너지회복약'
        $br_ch2_energy = $room['br_ch2_energy'] + 1;
      }else{
        http_response_code(400); echo '사용할 수 없는 아이템입니다'; exit;
      }
      delete_inventory($in['in_id'], 0);

      $sql = "UPDATE avo_battle_room SET br_ch2_potion_used = TRUE";
      if(isset($br_ch2_energy)) $sql = $sql.", br_ch2_energy = '{$br_ch2_energy}'";
      $sql = $sql."WHERE br_id = {$br_id}";
    }

  }else{
    http_response_code(400); echo '오류가 발생했습니다'; exit;
  }

  $sql ="INSERT INTO avo_battle_room_log SET brl_created_datetime = NOW()";
  if(isset($br_id)) $sql = $sql.", br_id = '{$br_id}'";
  if(isset($brl_ch1_command)) $sql = $sql.", brl_ch1_command = '{$brl_ch1_command}'";
  if(isset($brl_ch1_damaged)) $sql = $sql.", brl_ch1_damaged = '{$brl_ch1_damaged}'";
  if(isset($brl_ch1_text)) $sql = $sql.", brl_ch1_text = '{$brl_ch1_text}'";
  if(isset($brl_ch2_command)) $sql = $sql.", brl_ch2_command = '{$brl_ch2_command}'";
  if(isset($brl_ch2_damaged)) $sql = $sql.", brl_ch2_damaged = '{$brl_ch2_damaged}'";
  if(isset($brl_ch2_text)) $sql = $sql.", brl_ch2_text = '{$brl_ch2_text}'";

  // 대미지 계산 후 생존 여부 검색
  sql_query($sql);
  $room = get_battle_room($br_id);
  if($room['ch1_current_hp']<=0){
    sql_query("UPDATE avo_battle_room SET br_status = 'KNOCKOUT' WHERE br_id = {$br_id}");
    sql_query("UPDATE avo_character SET ch_score = ch_score + {$WIN} WHERE ch_id = {$ch2['ch_id']};");
    sql_query("UPDATE avo_character SET ch_score = ch_score + {$LOSS} WHERE ch_id = {$ch1['ch_id']};");
  }else if($room['ch2_current_hp']<=0){
    sql_query("UPDATE avo_battle_room SET br_status = 'KNOCKOUT' WHERE br_id = {$br_id}");
    sql_query("UPDATE avo_character SET ch_score = ch_score + {$WIN} WHERE ch_id = {$ch1['ch_id']};");
    sql_query("UPDATE avo_character SET ch_score = ch_score + {$LOSS} WHERE ch_id = {$ch2['ch_id']};");
  }

  if($room['ch1_current_hp']<=0||$room['ch2_current_hp']<=0){
    // 양측에 포인트 지급
    insert_point($ch1['mb_id'], 100, '전투 종료');
    insert_point($ch2['mb_id'], 100, '전투 종료');
  }

  exit;
}
?>