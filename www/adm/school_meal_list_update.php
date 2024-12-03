<?php
$sub_menu = "600200";
include_once('./_common.php');

auth_check($auth[$sub_menu], 'w');
check_token();

if($sm_config == 1)
{
	$sql = " select count(*) as cnt
			from avo_school_meal
			where sm_config = 1";
	$row = sql_fetch($sql);

	$sm_cates = explode("||", $_POST['sm_cate_origin']);
	$sm_names = $_POST['sm_names'];

	$sm_name = '';
	for($i=0; $i<count($sm_names); $i++)
	{
		if($i!=0) $sm_name .= '||';
		$sm_name .= $sm_cates[$sm_names[$i]];
	}
	
	if($row == 0)
	{
		$sql = " insert into avo_school_meal
			set sm_cate = '{$_POST['sm_cate']}',
				sm_name = '{$sm_name}',
				sm_config = '1' ";
	}
	else
	{
		$sql = " update avo_school_meal
			set sm_cate = '{$_POST['sm_cate']}',
				sm_name = '{$sm_name}'
			where sm_config = 1 ";
	}
}
else
{
	$sql = " insert into avo_school_meal
			set sm_cate = '{$_POST['sm_cate']}',
				sm_name = '{$sm_name}' ";
}

echo $sql;

sql_query($sql);

goto_url('./school_meal_list.php?'.$qstr);
?>
