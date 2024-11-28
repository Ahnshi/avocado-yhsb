<?php
$sub_menu = "920200";
include_once('./_common.php');

check_demo();
auth_check($auth[$sub_menu], 'w');
check_admin_token();


$sql = "INSERT INTO avo_battle_room_log SET brl_created_datetime = NOW() ";
if(isset($_POST['br_id'])&&$_POST['br_id']!='') $sql = $sql.', br_id = "'.$_POST['br_id'].'"';
if(isset($_POST['brl_ch1_command'])&&$_POST['brl_ch1_command']!='') $sql = $sql.', brl_ch1_command = "'.$_POST['brl_ch1_command'].'"';
if(isset($_POST['brl_ch1_damaged'])&&$_POST['brl_ch1_damaged']!='') $sql = $sql.', brl_ch1_damaged = "'.$_POST['brl_ch1_damaged'].'"';
if(isset($_POST['brl_ch1_used_item_id'])&&$_POST['brl_ch1_used_item_id']!='') $sql = $sql.', brl_ch1_used_item_id = "'.$_POST['brl_ch1_used_item_id'].'"';
if(isset($_POST['brl_ch1_text'])&&$_POST['brl_ch1_text']!='') $sql = $sql.', brl_ch1_text = "'.$_POST['brl_ch1_text'].'"';
if(isset($_POST['brl_ch2_command'])&&$_POST['brl_ch2_command']!='') $sql = $sql.', brl_ch2_command = "'.$_POST['brl_ch2_command'].'"';
if(isset($_POST['brl_ch2_damaged'])&&$_POST['brl_ch2_damaged']!='') $sql = $sql.', brl_ch2_damaged = "'.$_POST['brl_ch2_damaged'].'"';
if(isset($_POST['brl_ch2_used_item_id'])&&$_POST['brl_ch2_used_item_id']!='') $sql = $sql.', brl_ch2_used_item_id = "'.$_POST['brl_ch2_used_item_id'].'"';
if(isset($_POST['brl_ch2_text'])&&$_POST['brl_ch2_text']!='') $sql = $sql.', brl_ch2_text = "'.$_POST['brl_ch2_text'].'"';
// print_r($sql);

sql_query($sql);

goto_url('./battle_room_log_list.php?'.$qstr);
?>