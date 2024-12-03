<?php
$sub_menu = "600100";
include_once('./_common.php');

if ($w == "u" || $w == "d")
	check_demo();

if ($w == 'd')
	auth_check($auth[$sub_menu], "d");
else
	auth_check($auth[$sub_menu], "w");

check_admin_token();

$quest_image_path = G5_DATA_PATH."/quest";
$quest_image_url = G5_DATA_URL."/quest";

@mkdir($quest_image_path, G5_DIR_PERMISSION);
@chmod($quest_image_path, G5_DIR_PERMISSION);

// 제출 아이템관련
// - URL 제출이라면 제출 아이템 지정해도 저장되지 않음
// - 제출할 아이템이 없다면 자동으로 URL 제출로 설정
if($qu_type == 'url' || $qu_it_id == 0){
	$qu_it_id = 0;
}

$sql_common = " qu_cate		= '$qu_cate',
				si_id		= '$si_id',
				qu_title	= '$qu_title',
				qu_content	= '$qu_content',
				qu_it_id	= '$qu_it_id', 
				qu_point	= '$qu_point',
				qu_exp		= '$qu_exp',
				qu_use		= '$qu_use',
				qu_sdate	= '$qu_sdate',
				qu_edate	= '$qu_edate',
				it_id		= '{$it_id}'";

if($_FILES['qu_img_file']['name']) {
	// 확장자 따기
	$exp = explode(".", $_FILES['qu_img_file']['name']);
	$exp = $exp[count($exp)-1];

	$image_name = "quest_".time().".".$exp;
	upload_file($_FILES['qu_img_file']['tmp_name'], $image_name, $quest_image_path);
	$qu_img = $quest_image_url."/".$image_name;
}
$sql_common .= " , qu_img		= '{$qu_img}' ";

if ($w == "")
{
	$sql = " insert {$g5['quest_table']}
				set {$sql_common} ";
	sql_query($sql);
	$qu_id = sql_insert_id();
}
else if ($w == "u")
{
	$qu = sql_fetch("select * from {$g5['quest_table']} where qu_id = '{$qu_id}'");
	if(!$qu['qu_id']) {
		alert("퀘스트정보가 존재하지 않습니다.");
	}
	$sql = " update {$g5['quest_table']}
				set {$sql_common}
			  where qu_id = '$qu_id' ";
	sql_query($sql);
}
else if ($w == "d")
{
	$qu = sql_fetch("select * from {$g5['quest_table']} where qu_id = '{$qu_id}'");
	if(!$qu['qu_id']) {
		alert("퀘스트정보가 존재하지 않습니다.");
	}
	$sql = " delete from {$g5['quest_table']} where qu_id = '$qu_id' ";
	sql_query($sql);
}

goto_url("./quest_form.php?w=u&qu_id={$qu_id}");
?>
