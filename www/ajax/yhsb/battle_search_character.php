<?php
include_once("./_common.php");

// 캐릭터 전체 정보
$ch = get_character_full_info($_GET['ch_id']);

// JSON 반환
if(!$ch) {
  echo "{}";
  return;
}else{
  echo json_encode($ch);
  return;
}
?>