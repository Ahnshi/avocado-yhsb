<?php
//if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가 
include_once('./_common.php');

add_stylesheet('<link rel="stylesheet" href="'.G5_URL.'/schoolmeal/css/style.css">', 0);

$sql_common = " from avo_school_meal";
$sql_search = " where sm_config != 1";

$sql = " select sm_name, sm_cate 
		{$sql_common}
		where sm_config = 1";
$sm_config = sql_fetch($sql);
$sm_names = explode('||', $sm_config['sm_name']);
$sm_cates = explode('||', $sm_config['sm_cate']);

$sql = " select sm_name, sm_cate 
		{$sql_common}
		{$sql_search}
		order by sm_cate, sm_name asc ";
$result = sql_query($sql);

$menus = array_map();
for($i=0; $row=sql_fetch_array($result); $i++)
{
	$menus[$row['sm_cate']][] = $row['sm_name'];
}

$date = date("ymd");	// 날짜별로 바뀌게 하기위한 값..

$meals = array();
for($i=0; $i<count($sm_names); $i++)
{
	$ran = rand() % count($menus[$sm_names[$i]]);
	//$ran = $date % count($menus[$sm_names[$i]]);
	$meals[$i] = $menus[$sm_names[$i]][$ran];
	array_splice($menus[$sm_names[$i]], $ran, 1);
}
?>

<div id="school-meal-box">
	<?
		for($i=0; $i<count($meals); $i++)
		{
			echo $meals[$i].'<br>';
		}
	?>	
</div>

<script>
</script>

<style>
#school-meal-box { display: flex; text-align: center;}
</style>