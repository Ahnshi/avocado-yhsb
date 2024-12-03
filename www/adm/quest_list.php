<?php
$sub_menu = "600100";
include_once('./_common.php');


/** 세력 정보 **/
if($config['cf_side_title']) {
	$ch_si = array();
	$side_result = sql_query("select si_id, si_name from {$g5['side_table']} where si_auth <= '{$member['mb_level']}' order by si_id asc");
	for($i=0; $row = sql_fetch_array($side_result); $i++) { 
		$ch_si[$i]['name'] = $row['si_name'];
		$ch_si[$i]['id'] = $row['si_id'];
	}
}

auth_check($auth[$sub_menu], 'r');

$sql_common = " from {$g5['quest_table']} ";
$sql_search = " where (1) ";

if ($stx) {
	$sql_search .= " and ( ";
	switch ($sfl) {
		default :
			$sql_search .= " ($sfl like '%$stx%') ";
			break;
	}
	$sql_search .= " ) ";
}

if($cate) { $sql_search .= " and qu_cate = '{$cate}' "; }
if($side) { $sql_search .= " and si_id = '{$side}' "; }

if (!$sst) {
	$sst  = "qu_id";
	$sod = "desc";
}
$sql_order = " order by {$sst} {$sod} ";

$sql = " select count(*) as cnt {$sql_common} {$sql_search} {$sql_order} ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = $config['cf_page_rows'];
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$sql = " select * {$sql_common} {$sql_search} {$sql_order} limit {$from_record}, {$rows} ";
$result = sql_query($sql);

$listall = '<a href="'.$_SERVER['PHP_SELF'].'" class="ov_listall">전체목록</a>';

$g5['title'] = '퀘스트 관리';
include_once('./admin.head.php');

$colspan = 13;


?>

<div class="local_ov01 local_ov">
	<?php echo $listall ?>
	추가된 퀘스트 수 <?php echo number_format($total_count) ?>개
</div>

<form name="fsearch" id="fsearch" class="local_sch01 local_sch" method="get">

<select name="cate" id="cate">
	<option value="">분류</option>
	<?
		$quest_list = explode("||", $config['cf_quest_category']);
		for($i=0; $i < count($quest_list); $i++) { ?>
			<option value="<?=$quest_list[$i]?>" <?=$cate == $quest_list[$i] ? "selected" : ""?>><?=$quest_list[$i]?></option>
	<? } ?>
</select>

<select name="side" id="side">
	<option value=""><?=$config['cf_side_title']?> 선택</option>
	<? for($i=0; $i < count($ch_si); $i++) { ?>
		<option value="<?=$ch_si[$i]['id']?>" <?=$side == $ch_si[$i]['id'] ? "selected" : "" ?>><?=$ch_si[$i]['name']?></option>
	<? } ?>
</select>

<label for="sfl" class="sound_only">검색대상</label>
<select name="sfl" id="sfl">
	<option value="qu_title"<?php echo get_selected($_GET['sfl'], "qu_title", true); ?>>퀘스트 이름</option>
</select>
<label for="stx" class="sound_only">검색어<strong class="sound_only"> 필수</strong></label>
<input type="text" name="stx" value="<?php echo $stx ?>" id="stx">
<input type="submit" value="검색" class="btn_submit">

</form>

<?php if ($is_admin == 'super') { ?>
<div class="btn_add01 btn_add">
	<a href="./quest_form.php" id="bo_add">퀘스트 추가</a>
</div>
<?php } ?>

<form name="fitemlist" id="fitemlist" action="./quest_list_update.php" onsubmit="return fitemlist_submit(this);" method="post">
<input type="hidden" name="sst" value="<?php echo $sst ?>">
<input type="hidden" name="sod" value="<?php echo $sod ?>">
<input type="hidden" name="sfl" value="<?php echo $sfl ?>">

<input type="hidden" name="cate" value="<?php echo $cate ?>">
<input type="hidden" name="map_id" value="<?php echo $map_id ?>">

<input type="hidden" name="stx" value="<?php echo $stx ?>">
<input type="hidden" name="page" value="<?php echo $page ?>">
<input type="hidden" name="token" value="<?php echo $token ?>">

<div class="tbl_head01 tbl_wrap">
	<table>
		<caption><?php echo $g5['title']; ?> 목록</caption>
		<colgroup>
			<col style="width:  40px;" />
			<col style="width: 100px;" />
			<col style="width: 140px;" />
			<col />
			
			<col style="width: 110px;"/>
			<col style="width: 110px;"/>

			<col style="width: 50px;"/>
			<col style="width: 110px;"/>
			<col style="width: 80px;"/>
			<col style="width: 80px;"/>
			
			<col style="width: 50px;"/>
			<col style="width: 100px;"/>
			<col style="width: 50px;"/>
			<col style="width: 50px;"/>
		</colgroup>
		<thead>
			<tr>
				<th scope="col" class="bo-right">
					<input type="checkbox" name="chkall" value="1" id="chkall" onclick="check_all(this.form)">
				</th>
				<th scope="col">분류</th>
				<th scope="col">세력</th>
				<th scope="col">퀘스트 이름</th>

				<th scope="col">시작일</th>
				<th scope="col">종료일</th>
				
				<th scope="col" colspan="2">아이템</th>
				<th scope="col"><?=$config['cf_money']?></th>
				<th scope="col"><?=$config['cf_exp_name']?></th>

				<th scope="col">사용</th>
				<th scope="col">참가자</th>
				<th scope="col">보기</th>
				<th scope="col">관리</th>
			</tr>
		</thead>
		<tbody>
			<?php
			for ($i=0; $quest=sql_fetch_array($result); $i++) {
				$one_update = '<a href="./quest_form.php?w=u&amp;qu_id='.$quest['qu_id'].'&amp;'.$qstr.'">수정</a>';
				$bg = 'bg'.($i%2);
			?>

			<tr class="<?php echo $bg; ?>">
				<td>
					<input type="checkbox" name="chk[]" value="<?php echo $i ?>" id="chk_<?php echo $i ?>">
					<input type="hidden" name="qu_id[<?php echo $i ?>]" value="<?php echo $quest['qu_id'] ?>" />
				</td>

				<td>
					<select name="qu_cate[<?php echo $i ?>]" style="display: block; width:100%;">
						<option value="">설정안함</option>
							<?
								$quest_list = explode("||", $config['cf_quest_category']);
								for($j=0; $j < count($quest_list); $j++) { ?>
									<option value="<?=$quest_list[$j]?>" <?=$quest['qu_cate'] == $quest_list[$j] ? "selected" : ""?>><?=$quest_list[$j]?></option>
							<? } ?>
					</select>
				</td>
				<td>
					<select name="si_id[<?php echo $i ?>]" style="display: block; width:100%;">
						<option value=""><?=$config['cf_side_title']?> 선택</option>
					<? for($j=0; $j < count($ch_si); $j++) { ?>
						<option value="<?=$ch_si[$j]['id']?>" <?=$quest['si_id'] == $ch_si[$j]['id'] ? "selected" : "" ?>><?=$ch_si[$j]['name']?></option>
					<? } ?>
					</select>
				</td>
				<td class="txt-center">
					<input type="text" name="qu_title[<?php echo $i ?>]" value="<?php echo get_text($quest['qu_title']) ?>" size="20" style="width:100%;">
				</td>

				<td class="txt-center">
					<input type="text" name="qu_sdate[<?php echo $i ?>]" value="<?php echo get_text($quest['qu_sdate']) ?>" id="qu_sdate_<?php echo $i ?>" size="20" style="width:100%;">
				</td>
				<td class="txt-center">
					<input type="text" name="qu_edate[<?php echo $i ?>]" value="<?php echo get_text($quest['qu_edate']) ?>" id="qu_edate_<?php echo $i ?>" size="20" style="width:100%;">
				</td>

				<td>
					<? if($quest['it_id']) { ?>
						<img src="<?=get_item_img($quest['it_id'])?>" style="max-width: 20px;"/>
					<? } ?>
				</td>
				<td>
					<?=get_item_name($quest['it_id'])?>
				</td>
				<td>
					<input type="text" name="qu_point[<?php echo $i ?>]" value="<?php echo get_text($quest['qu_point']) ?>" size="20" style="width:100%;">
				</td>
				<td>
					<input type="text" name="qu_exp[<?php echo $i ?>]" value="<?php echo get_text($quest['qu_exp']) ?>" size="20" style="width:100%;">
				</td>

				<td>
					<input type="checkbox" name="qu_use[<?php echo $i ?>]" value="1" <?php echo $quest['qu_use']?"checked":"" ?>>
				</td>

				<td>
					<a href="./quest_member_list.php?qu_id=<?=$quest['qu_id']?>">참가자목록</a>
				</td>
				<td class="td_mngsmall">
					<a href="<?=G5_URL?>/quest/index.php?qu_id=<?=$quest['qu_id']?>" target="_blank" onclick="popup_window(this.href, '', 'width=780, height=800'); return false;">보기</a>
				</td>
				<td class="td_mngsmall">
					<?php echo $one_update ?>
				</td>
			</tr>
			
			<?php
			}
			if ($i == 0)
				echo '<tr><td colspan="'.$colspan.'" class="empty_table">자료가 없습니다.</td></tr>';
			?>
		</tbody>
	</table>
</div>

<div class="btn_list01 btn_list">
	<input type="submit" name="act_button" value="선택수정" onclick="document.pressed=this.value">
	<?php if ($is_admin == 'super') { ?>
	<input type="submit" name="act_button" value="선택삭제" onclick="document.pressed=this.value">
	<?php } ?>
</div>

</form>

<?php echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, $_SERVER['PHP_SELF'].'?'.$qstr.'&amp;cate='.$cate.'&amp;cate2='.$cate2.'&amp;map_id='.$map_id.'&amp;page='); ?>

<script>
function fitemlist_submit(f)
{
	if (!is_checked("chk[]")) {
		alert(document.pressed+" 하실 항목을 하나 이상 선택하세요.");
		return false;
	}

	if(document.pressed == "선택삭제") {
		if(!confirm("선택한 자료를 정말 삭제하시겠습니까?")) {
			return false;
		}
	}

	return true;
}
</script>

<?php
include_once('./admin.tail.php');
?>
