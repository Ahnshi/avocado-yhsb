<?php
$sub_menu = "600100";
include_once('./_common.php');
include_once(G5_EDITOR_LIB);

auth_check($auth[$sub_menu], 'w');


/** 세력 정보 **/
if($config['cf_side_title']) {
	$ch_si = array();
	$side_result = sql_query("select si_id, si_name from {$g5['side_table']} where si_auth <= '{$member['mb_level']}' order by si_id asc");
	for($i=0; $row = sql_fetch_array($side_result); $i++) { 
		$ch_si[$i]['name'] = $row['si_name'];
		$ch_si[$i]['id'] = $row['si_id'];
	}
}



$html_title = '퀘스트';
$required = "";
$readonly = "";
if ($w == '') {

	$html_title .= ' 생성';
	$required = 'required';
	$required_valid = 'alnum_';
	$sound_only = '<strong class="sound_only">필수</strong>';


} else if ($w == 'u') {

	$html_title .= ' 수정';
	$quest = sql_fetch("select qu.*, it.it_name as qu_it_name from {$g5['quest_table']} qu left join {$g5['item_table']} it on qu.qu_it_id = it.it_id where qu.qu_id = '{$qu_id}'");
	if (!$quest['qu_id'])
		alert('존재하지 않는 퀘스트 입니다.');
	$readonly = 'readonly';
}

$g5['title'] = $html_title;
include_once ('./admin.head.php');

$pg_anchor = '<ul class="anchor">
	<li><a href="#anc_001">기본 설정</a></li>
	<li><a href="#anc_002">보상 설정</a></li>
</ul>';


$frm_submit = '<div class="btn_confirm01 btn_confirm">
	<input type="submit" value="확인" class="btn_submit" accesskey="s">
	<a href="./quest_list.php?'.$qstr.'">목록</a>'.PHP_EOL;
$frm_submit .= '</div>';

?>

<?
	include_once(G5_PLUGIN_PATH.'/jquery-ui/datepicker.php');
	if (empty($fr_date)) $fr_date = G5_TIME_YMD;
?>


<form name="fitemform" id="fitemform" action="./quest_form_update.php" onsubmit="return fitemform_submit(this)" method="post" enctype="multipart/form-data">
<input type="hidden" name="w" value="<?php echo $w ?>">
<input type="hidden" name="qu_id" value="<?php echo $qu_id ?>">
<input type="hidden" name="sfl" value="<?php echo $sfl ?>">
<input type="hidden" name="stx" value="<?php echo $stx ?>">
<input type="hidden" name="sst" value="<?php echo $sst ?>">
<input type="hidden" name="sod" value="<?php echo $sod ?>">
<input type="hidden" name="page" value="<?php echo $page ?>">

<section id="anc_001">
	<h2 class="h2_frm">기본 설정</h2>
	<?php echo $pg_anchor ?>

	<div class="tbl_frm01 tbl_wrap">
		<table>
			<caption>퀘스트 기본 설정</caption>
			<colgroup>
				<col style="width: 130px;">
				<col style="width: 100px;">
				<col>
			</colgroup>
			<tbody>
				<tr>
					<th scope="row">사용</th>
					<td colspan="2">
						<input type="checkbox" name="qu_use" value="1" <?=$quest['qu_use'] ? "checked" : ""?> />
					</td>
				</tr>
				<tr>
					<th scope="row">퀘스트 분류</th>
					<td colspan="2">
						<select name="qu_cate">
							<option value="">설정안함</option>
							<?
								$quest_list = explode("||", $config['cf_quest_category']);
								for($i=0; $i < count($quest_list); $i++) { ?>
									<option value="<?=$quest_list[$i]?>" <?=$quest['qu_cate'] == $quest_list[$i] ? "selected" : ""?>><?=$quest_list[$i]?></option>
							<? } ?>
						</select>
					</td>
				</tr>

				<tr>
					<th scope="row">수행가능진영</th>
					<td colspan="2">
						<select name="si_id" id="si_id">
							<option value=""><?=$config['cf_side_title']?> 선택</option>
							<? for($i=0; $i < count($ch_si); $i++) { ?>
								<option value="<?=$ch_si[$i]['id']?>" <?=$quest['si_id'] == $ch_si[$i]['id'] ? "selected" : "" ?>><?=$ch_si[$i]['name']?></option>
							<? } ?>
						</select>
					</td>
				</tr>

				<tr>
					<th scope="row">퀘스트 이름</th>
					<td colspan="2">
						<input type="text" name="qu_title" value="<?php echo get_text($quest['qu_title']) ?>" id="qu_title" required class="required" size="50" maxlength="120">
					</td>
				</tr>
				<tr>
					<th rowspan="3">메인이미지</th>
					<td class="bo-right">직접등록</td>
					<td>
						<input type="file" name="qu_img_file" value="" size="50">
					</td></tr><tr>
					<td class="bo-right">외부경로</td>
					<td>
						<input type="text" name="qu_img" value="<?=$quest['qu_img']?>" size="50"/>
					</td></tr><tr>
					<td class="bo-right">이미지</td>
					<td><? if($quest['qu_img']) { ?><img src="<?=$quest['qu_img']?>" style="max-height: 150px;"/><? } ?></td>
				</tr>

				<tr>
					<th rowspan="2">퀘스트 제출</th>
					<td class="bo-right">유형</td>
					<td>
						<select name="qu_type">
							<option value="url" <?=$quest['qu_it_id'] == 0 ? "selected" : "" ?>>URL 제출</option>
							<option value="item" <?=$quest['qu_it_id'] > 0 ? "selected" : "" ?>>아이템 제출</option>
						</select>
					</td>
				</tr>
				<tr>
					<td class="bo-right">제출 아이템</th>
					<td class="bo-right">
						<input type="hidden" name="qu_it_id" id="qu_it_id" value="<?=$quest['qu_it_id']?>" />
						<input type="text" name="qu_it_name" value="<?=$quest['qu_it_name']?>" id="qu_it_name" onkeyup="get_ajax_item(this, 'qu_item_list', 'qu_it_id');" />
						<div id="qu_item_list" class="ajax-list-box"><div class="list"></div></div>
					</td>
				</tr>

				<tr>
					<th rowspan="2">
						퀘스트 기간
					</th>
					<td>
						시작일
					</td>
					<td>
						<input type="text" name="qu_sdate" value="<?php echo $quest['qu_sdate']; ?>" id="qu_sdate" class="date"  size="21" maxlength="19">
					</td>
				</tr>
				<tr>
					<td>
						종료일
					</td>
					<td>
						<input type="text" name="qu_edate" value="<?php echo $quest['qu_edate']; ?>" id="qu_edate" class="date"  size="21" maxlength="19">
					</td>
				</tr>

				<tr>
					<th scope="row">퀘스트 내용</th>
					<td colspan="2">
						<textarea name="qu_content"><?=$quest['qu_content']?></textarea>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
</section>

<?php echo $frm_submit; ?>

<section id="anc_001">
	<h2 class="h2_frm">보상 설정</h2>
	<?php echo $pg_anchor ?>

	<div class="tbl_frm01 tbl_wrap">
		<table>
			<colgroup>
				<col style="width: 130px;">
				<col>
			</colgroup>
			<tbody>
				<tr>
					<th scope="row">획득 아이템</th>
					<td >
						<input type="hidden" name="it_id" id="it_id" value="<?=$quest['it_id']?>" />
						<input type="text" name="it_name" value="<?=get_item_name($quest['it_id'])?>" id="it_name" onkeyup="get_ajax_item(this, 'item_list', 'it_id');" />
						<div id="item_list" class="ajax-list-box"><div class="list"></div></div>
					</td>
				</tr>
				<tr>
					<th scope="row">획득 <?=$config['cf_money']?></th>
					<td>
						<input type="text" name="qu_point" value="<?php echo $quest['qu_point']; ?>">
					</td>
				</tr>
				<tr>
					<th scope="row">획득 <?=$config['cf_exp_name']?></th>
					<td>
						<input type="text" name="qu_exp" value="<?php echo $quest['qu_exp']; ?>">
					</td>
				</tr>
			</tbody>
		</table>
	</div>

</section>

<?php echo $frm_submit; ?>

</form>


<script>
$(function(){
	$(".date").datepicker({ changeMonth: true, changeYear: true, dateFormat: "yy-mm-dd", showButtonPanel: true, yearRange: "c-99:c+99" });
});

function fitemform_submit(f)
{
	return true;
}

</script>

<?php
include_once ('./admin.tail.php');
?>
