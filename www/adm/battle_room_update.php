<?php
$sub_menu = "920100";
include_once('./_common.php');

check_demo();
auth_check($auth[$sub_menu], 'w');
check_admin_token();

if($_POST['br_ch1_id']&&$_POST['br_ch2_id']){
	if($_POST['br_ch1_id']==$_POST['br_ch2_id']){
		alert('서로 다른 두 명의 캐릭터를 선택해주세요');
	}else{
		$sql = "INSERT INTO avo_battle_room SET br_ch1_id = {$_POST['br_ch1_id']}, br_ch2_id = {$_POST['br_ch2_id']} ";
		if(isset($_POST['br_ch1_hp'])) $sql = $sql.', br_ch1_hp = '.$_POST['br_ch1_hp'];
		if(isset($_POST['br_ch1_energy'])) $sql = $sql.', br_ch1_energy = '.$_POST['br_ch1_energy'];
		if(isset($_POST['br_ch1_skill_used'])) $sql = $sql.', br_ch1_skill_used = '.$_POST['br_ch1_skill_used'];
		if(isset($_POST['br_ch1_potion_used'])) $sql = $sql.', br_ch1_potion_used = '.$_POST['br_ch1_potion_used'];
		if(isset($_POST['br_ch1_dmg_cut_rate'])) $sql = $sql.', br_ch1_dmg_cut_rate = '.$_POST['br_ch1_dmg_cut_rate'];
		if(isset($_POST['br_ch2_hp'])) $sql = $sql.', br_ch2_hp = '.$_POST['br_ch2_hp'];
		if(isset($_POST['br_ch2_energy'])) $sql = $sql.', br_ch2_energy = '.$_POST['br_ch2_energy'];
		if(isset($_POST['br_ch2_skill_used'])) $sql = $sql.', br_ch2_skill_used = '.$_POST['br_ch2_skill_used'];
		if(isset($_POST['br_ch2_potion_used'])) $sql = $sql.', br_ch2_potion_used = '.$_POST['br_ch2_potion_used'];
		if(isset($_POST['br_ch2_dmg_cut_rate'])) $sql = $sql.', br_ch2_dmg_cut_rate = '.$_POST['br_ch2_dmg_cut_rate'];
		if(isset($_POST['br_status'])) $sql = $sql.', br_status = "'.$_POST['br_status'].'"';
		
		sql_query($sql);

		goto_url('./battle_room_list.php?'.$qstr);
	}
}else{
	alert('서로 다른 두 명의 캐릭터를 선택해주세요');
}
?>